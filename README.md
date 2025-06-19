<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

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

### Checkout (Buat Pesanan & Pembayaran)

-   **POST** `/api/orders` _(belum tersedia, rencana integrasi Midtrans)_

### Daftar Pesanan Pengguna

-   **GET** `/api/my-orders`
-   **Headers:** `Authorization: Bearer {token}`

### Detail Pesanan

-   **GET** `/api/orders/{order_id}` _(disarankan untuk ditambah)_
-   **Headers:** `Authorization: Bearer {token}`

---

## Catatan

-   Semua endpoint yang membutuhkan login harus menggunakan header: `Authorization: Bearer {token}`
-   Untuk pembayaran, integrasi Midtrans akan menambah endpoint baru.
-   Untuk endpoint admin, gunakan middleware admin (belum tersedia di API publik).

---

**Silakan hubungi tim backend untuk pertanyaan lebih lanjut atau permintaan endpoint tambahan!**
