# Integrasi Raja Ongkir - Dokumentasi Setup

## Overview

Sistem ini menggunakan **data wilayah lokal** dengan **mapping ke Raja Ongkir** untuk perhitungan ongkir. Ini memberikan fleksibilitas maksimal sambil tetap mendapatkan data ongkir yang akurat dari Raja Ongkir.

## Fitur yang Tersedia

✅ **Perhitungan ongkir real-time** menggunakan Raja Ongkir API  
✅ **Multiple kurir**: JNE, POS Indonesia, TIKI  
✅ **Cache hasil perhitungan** untuk performa optimal  
✅ **Mapping wilayah lokal** ke Raja Ongkir ID  
✅ **Berat otomatis** dari keranjang belanja  
✅ **UI yang user-friendly** untuk pemilihan layanan

## Setup Awal

### 1. Daftar Raja Ongkir

1. Kunjungi [Raja Ongkir](https://rajaongkir.com/)
2. Daftar akun dan pilih paket (Starter/Pro)
3. Dapatkan API Key dari dashboard

### 2. Konfigurasi Environment

Tambahkan ke file `.env`:

```env
# Raja Ongkir Configuration
RAJAONGKIR_API_KEY=your_api_key_here
RAJAONGKIR_BASE_URL=https://api.rajaongkir.com/starter
RAJAONGKIR_ORIGIN_CITY_ID=419
```

**Keterangan:**

-   `RAJAONGKIR_API_KEY`: API Key dari Raja Ongkir
-   `RAJAONGKIR_BASE_URL`: URL API (starter/pro)
-   `RAJAONGKIR_ORIGIN_CITY_ID`: ID kota asal (toko Anda)

### 3. Jalankan Migration

```bash
php artisan migrate
```

Migration ini akan menambahkan:

-   `shipping_service` dan `shipping_courier` ke tabel `orders`
-   `berat` ke tabel `produks`

### 4. Update Data Produk

Tambahkan berat produk ke database:

```sql
-- Update berat produk (dalam gram)
UPDATE produks SET berat = 1000 WHERE berat IS NULL;
```

## Mapping Wilayah

### Struktur Mapping

Sistem menggunakan mapping 2 level:

```php
// Provinsi
'34' => '5',   // DIY di sistem Anda -> ID Raja Ongkir

// Kota/Kabupaten
'3401' => '419', // Kulon Progo -> ID Raja Ongkir
'3402' => '420', // Bantul -> ID Raja Ongkir
```

### Menambah Mapping Baru

Untuk menambah wilayah baru, edit file `RajaOngkirService.php`:

```php
private function getRajaOngkirMapping()
{
    return [
        'provinces' => [
            // Tambah mapping provinsi baru
            'XX' => 'YY', // ID lokal -> ID Raja Ongkir
        ],
        'cities' => [
            // Tambah mapping kota baru
            'XXXX' => 'YYY', // ID lokal -> ID Raja Ongkir
        ]
    ];
}
```

### Cara Mendapatkan ID Raja Ongkir

1. **API Raja Ongkir** (jika paket Pro):

    ```bash
    curl -H "key: YOUR_API_KEY" \
         https://api.rajaongkir.com/starter/province
    ```

2. **Dokumentasi Raja Ongkir**:

    - [Daftar Provinsi](https://rajaongkir.com/dokumentasi/provinsi)
    - [Daftar Kota](https://rajaongkir.com/dokumentasi/kota)

3. **Manual Mapping**:
    - Bandingkan nama wilayah lokal dengan Raja Ongkir
    - Gunakan ID yang sesuai

## API Endpoints

### 1. Hitung Ongkir

```http
POST /api/shipping/calculate
Authorization: Bearer {token}
Content-Type: application/json

{
    "destination_city_id": "3401",
    "courier": "jne",
    "weight": 1000
}
```

**Response:**

```json
{
    "success": true,
    "message": "Berhasil menghitung ongkir",
    "data": {
        "origin": {
            "city_name": "Kulon Progo",
            "province": "DI Yogyakarta"
        },
        "destination": {
            "city_name": "Jakarta Selatan",
            "province": "DKI Jakarta"
        },
        "weight": 1000,
        "courier": "JNE",
        "costs": [
            {
                "service": "REG",
                "description": "Layanan Reguler",
                "cost": 15000,
                "etd": "2-3",
                "note": ""
            }
        ]
    }
}
```

### 2. Daftar Kurir

```http
GET /api/shipping/couriers
Authorization: Bearer {token}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "jne": "JNE",
        "pos": "POS Indonesia",
        "tiki": "TIKI"
    }
}
```

### 3. Update Ongkir Keranjang

```http
POST /api/shipping/update-cart
Authorization: Bearer {token}
Content-Type: application/json

{
    "shipping_cost": 15000,
    "shipping_service": "REG",
    "shipping_courier": "JNE"
}
```

## Frontend Integration

### Komponen ShippingCalculator

```tsx
<ShippingCalculator
    selectedAddressId={selectedAddressId}
    addresses={addresses}
    onShippingSelect={handleShippingSelect}
    selectedShipping={selectedShipping}
/>
```

### Handle Shipping Selection

```tsx
const handleShippingSelect = async (
    cost: number,
    service: string,
    courier: string
) => {
    setSelectedShipping({ cost, service, courier });

    // Update cart dengan ongkir baru
    await ApiConfig.post("/shipping/update-cart", {
        shipping_cost: cost,
        shipping_service: service,
        shipping_courier: courier,
    });
};
```

## Troubleshooting

### 1. Error "Kota tujuan tidak ditemukan"

**Penyebab:** Mapping kota belum ada di `RajaOngkirService.php`

**Solusi:**

1. Cek ID kota di database lokal
2. Tambah mapping ke `getRajaOngkirMapping()`
3. Restart aplikasi

### 2. Error "Invalid API Key"

**Penyebab:** API Key Raja Ongkir salah atau expired

**Solusi:**

1. Cek API Key di dashboard Raja Ongkir
2. Update `.env` file
3. Clear cache: `php artisan cache:clear`

### 3. Error "Tidak ada layanan pengiriman"

**Penyebab:**

-   Kota tujuan tidak dilayani kurir tersebut
-   Berat terlalu ringan/berat

**Solusi:**

1. Coba kurir lain (JNE, POS, TIKI)
2. Cek berat minimum/maksimum
3. Hubungi Raja Ongkir support

### 4. Performa Lambat

**Penyebab:** Terlalu banyak request ke Raja Ongkir

**Solusi:**

1. Cache sudah aktif (1 jam)
2. Kurangi request dengan debouncing
3. Upgrade ke paket Pro untuk rate limit lebih tinggi

## Monitoring & Logs

### Log Shipping Requests

```php
// Di RajaOngkirService.php
Log::info('Shipping calculation', [
    'origin' => $this->originCityId,
    'destination' => $rajaOngkirCityId,
    'weight' => $weight,
    'courier' => $courier,
    'response_time' => $responseTime
]);
```

### Cache Hit Rate

```bash
# Cek cache shipping
php artisan tinker
>>> Cache::get('shipping_cost_3401_1000_jne')
```

## Best Practices

### 1. Error Handling

```php
try {
    $result = $this->rajaOngkirService->calculateShipping($cityId, $weight, $courier);
    if (!$result['success']) {
        // Fallback ke ongkir flat
        return $this->getFlatShippingCost();
    }
} catch (Exception $e) {
    Log::error('Raja Ongkir error', ['error' => $e->getMessage()]);
    return $this->getFlatShippingCost();
}
```

### 2. Rate Limiting

```php
// Tambah rate limiting untuk API shipping
Route::middleware(['auth:api', 'throttle:10,1'])->post('shipping/calculate', ...);
```

### 3. Fallback Strategy

```php
// Jika Raja Ongkir down, gunakan ongkir flat
private function getFlatShippingCost($destinationCityId) {
    // Logic ongkir flat berdasarkan zona
    return [
        'success' => true,
        'costs' => [
            [
                'service' => 'FLAT',
                'description' => 'Ongkir Flat',
                'cost' => 15000,
                'etd' => '3-5'
            ]
        ]
    ];
}
```

## Support

Untuk bantuan lebih lanjut:

1. **Dokumentasi Raja Ongkir**: https://rajaongkir.com/dokumentasi
2. **API Status**: https://status.rajaongkir.com/
3. **Support Email**: support@rajaongkir.com

## Changelog

### v1.0.0 (2025-01-20)

-   ✅ Integrasi Raja Ongkir dengan mapping lokal
-   ✅ Support JNE, POS, TIKI
-   ✅ Cache hasil perhitungan
-   ✅ UI shipping calculator
-   ✅ API endpoints lengkap
