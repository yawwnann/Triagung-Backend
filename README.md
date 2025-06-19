# Dokumentasi API Backend Trijaya Agung

## Autentikasi

### Registrasi

-   **POST** `/api/register`
-   **Body:**
    ```json
    {
        "name": "Nama Pengguna",
        "email": "user@email.com",
        "password": "password"
    }
    ```
-   **Response:**
    ```json
    { "token": "..." }
    ```

### Login

-   **POST** `/api/login`
-   **Body:**
    ```json
    {
        "email": "user@email.com",
        "password": "password"
    }
    ```
-   **Response:**
    ```json
    { "token": "..." }
    ```

### Info Pengguna Saat Ini

-   **GET** `/api/me`
-   **Headers:** `Authorization: Bearer {token}`

---

## Produk & Kategori

### Daftar Produk

-   **GET** `/api/produks`

### Daftar Kategori Produk

-   **GET** `/api/kategoris`

### Daftar Banner

-   **GET** `/api/banners`

---

## Keranjang Belanja

### Lihat Keranjang

-   **GET** `/api/cart`
-   **Headers:** `Authorization: Bearer {token}`

### Tambah/Update Keranjang

-   **POST** `/api/cart`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    {
        "items": [
            { "product_id": 1, "quantity": 2 },
            { "product_id": 2, "quantity": 1 }
        ]
    }
    ```

### Edit Item Keranjang

-   **PATCH** `/api/cart/{item_id}`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    { "quantity": 3 }
    ```

### Hapus Item Keranjang

-   **DELETE** `/api/cart/{item_id}`
-   **Headers:** `Authorization: Bearer {token}`

---

## Alamat Pengiriman

### Lihat Daftar Alamat

-   **GET** `/api/addresses`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    [
        {
            "id": 1,
            "label": "Rumah",
            "recipient_name": "Budi Santoso",
            "phone": "08123456789",
            "address": "Jl. Mawar No. 1",
            "province": "Jawa Timur",
            "city": "Surabaya",
            "district": "Tegalsari",
            "postal_code": "60100",
            "is_default": true,
            "notes": "Alamat utama"
        }
    ]
    ```

### Tambah Alamat

-   **POST** `/api/addresses`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    {
        "label": "Rumah",
        "recipient_name": "Budi Santoso",
        "phone": "08123456789",
        "address": "Jl. Mawar No. 1",
        "province": "Jawa Timur",
        "city": "Surabaya",
        "district": "Tegalsari",
        "postal_code": "60100",
        "is_default": true,
        "notes": "Alamat utama"
    }
    ```
-   **Response:**
    ```json
    {
        "id": 1,
        "label": "Rumah",
        "recipient_name": "Budi Santoso",
        "phone": "08123456789",
        "address": "Jl. Mawar No. 1",
        "province": "Jawa Timur",
        "city": "Surabaya",
        "district": "Tegalsari",
        "postal_code": "60100",
        "is_default": true,
        "notes": "Alamat utama"
    }
    ```

### Edit Alamat

-   **PUT** `/api/addresses/{id}`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    {
        "phone": "08129876543"
    }
    ```
-   **Response:**
    ```json
    {
        "id": 1,
        "label": "Rumah",
        "recipient_name": "Budi Santoso",
        "phone": "08129876543",
        "address": "Jl. Mawar No. 1",
        "province": "Jawa Timur",
        "city": "Surabaya",
        "district": "Tegalsari",
        "postal_code": "60100",
        "is_default": true,
        "notes": "Alamat utama"
    }
    ```

### Hapus Alamat

-   **DELETE** `/api/addresses/{id}`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    { "success": true }
    ```

---

## Pesanan

### Lihat Daftar Pesanan Saya

-   **GET** `/api/my-orders`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    [
        {
            "id": 1,
            "order_number": "ORD-XXXXXX",
            "status": "processing",
            "grand_total": 100000,
            "items": [ ... ]
        }
    ]
    ```

### Checkout (Buat Pesanan & Pembayaran)

-   **POST** `/api/checkout`
-   **Headers:** `Authorization: Bearer {token}`
-   **Body:**
    ```json
    {
        "address_id": 1
    }
    ```
-   **Response:**
    ```json
    {
        "order": {
            "id": 1,
            "order_number": "ORD-XXXXXX",
            "status": "processing",
            "grand_total": 100000,
            "payment_token": "snap_token_midtrans",
            "items": [ ... ]
        },
        "snap_token": "snap_token_midtrans"
    }
    ```
-   **Deskripsi:**
    -   Mengubah status order 'pending' menjadi 'processing' dan mengatur alamat pengiriman.
    -   Menghasilkan Snap Token dari Midtrans untuk pembayaran.
    -   Snap token digunakan di frontend untuk menampilkan popup pembayaran.

### Integrasi Midtrans

-   Konfigurasi Midtrans diambil dari file `.env`:
    ```env
    MIDTRANS_SERVER_KEY=your_server_key
    MIDTRANS_CLIENT_KEY=your_client_key
    MIDTRANS_IS_PRODUCTION=false
    ```
-   Snap token didapatkan saat checkout dan digunakan untuk pembayaran online.
-   Kolom `payment_token` pada tabel `orders` menyimpan token Snap dari Midtrans.

### Detail Pesanan

-   **GET** `/api/order-detail/{orderId}`
-   **Headers:** `Authorization: Bearer {token}`
-   **Response:**
    ```json
    {
        "id": 1,
        "order_number": "ORD-XXXXXX",
        "status": "processing",
        "grand_total": 100000,
        "payment_token": "snap_token_midtrans",
        "items": [ ... ]
    }
    ```
-   **Deskripsi:**
    -   Mengembalikan detail pesanan milik user yang sedang login, beserta daftar itemnya.
    -   Jika order tidak ditemukan atau bukan milik user, akan mengembalikan error 404.

---

## Catatan

-   Semua endpoint yang membutuhkan login harus menggunakan header: `Authorization: Bearer {token}`
-   Untuk pembayaran, integrasi Midtrans akan menambah endpoint baru.
-   Untuk endpoint admin, gunakan middleware admin (belum tersedia di API publik).

---

**Silakan hubungi tim backend untuk pertanyaan lebih lanjut atau permintaan endpoint tambahan!**
