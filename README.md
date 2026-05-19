<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🚀 Fitur Utama & Alur Proses

### 1. Manajemen Produk (`GET /api/products`)
* **Proses:** Mengambil data seluruh produk dari database yang berstatus aktif (`is_active = 1`). 
* **Optimasi:** Data dikembalikan dalam bentuk format terstruktur menggunakan API Resource untuk memastikan konsistensi response JSON.

![alt text](<Screenshot 2026-05-19 at 12.34.59.png>)

### 2. Transaksi Order (`POST /api/orders`)
* **Proses:** Endpoint ini menerima input `product_id` dan `qty` dari client lewat request body JSON.
* **Validasi Relasi:** Laravel secara ketat melakukan pengecekan data ke tabel `users` dan `products` menggunakan mekanisme *Foreign Key Constraint*. Transaksi akan otomatis gagal disimpan jika `user_id` atau `product_id` tidak terdaftar di database untuk menjaga integritas data.

![alt text](<Screenshot 2026-05-19 at 12.33.53.png>)

### 3. Dashboard Analytics & Caching (`GET /api/dashboard/summary`) — *Expert*
* **Proses Agregasi:** Sistem menghitung total pendapatan (*revenue*) dari order berkategori 'completed', menghitung total order hari ini, serta mendeteksi jumlah produk yang stoknya menipis (di bawah 5 pcs).
* **Eager Loading (Anti N+1):** Menampilkan Top 5 produk terlaris dan 10 transaksi terbaru dengan teknik `.with(['product.category'])` untuk meminimalkan beban query ke database.
* **Mekanisme Caching (300 Detik):** Seluruh hasil query dashboard dibungkus di dalam fungsi `Cache::remember` dengan durasi kedaluwarsa 300 detik (5 menit). Sistem mengembalikan indikator status `"from_cache": true` jika data ditarik dari memori cache, dan `"from_cache": false` jika data baru diambil ulang dari database.
* **Flush Cache Manual (`DELETE /api/dashboard/cache`):** Menyediakan fungsi untuk menghapus (clear) data cache dashboard secara paksa sewaktu-waktu.

![alt text](<Screenshot 2026-05-19 at 12.33.58.png>)

![alt text](<Screenshot 2026-05-19 at 12.34.14.png>)

### 4. Fitur Bonus: Scoped Binding (`GET /api/users/{user}/orders/{order}`)
* **Proses:** Mengamankan rute data spesifik transaksi dengan memastikan secara otomatis di level routing bahwa data `order` yang diminta benar-benar milik `user` yang bersangkutan. Jika tidak cocok, Laravel secara instan melempar response `404 Not Found`.

![alt text](<Screenshot 2026-05-19 at 12.34.21.png>)
