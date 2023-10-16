## Tentang Zaiko Track

Zaiko Track adalah sebuah sistem manajemen informasi yang digunakan untuk mengelola data inventaris barang di Jurusan SIJA. Sistem ini memudahkan bagi para teknisi SIJA dalam melacak dan mengelola inventaris barang, termasuk barang-barang yang tersedia, perawatan yang diperlukan, dan catatan pemeliharaan. Zaiko Track dapat diakses melalui browser baik dari PC, Laptop, maupun perangkat mobile (smartphone).

## Teknologi

### Produksi
-   [php 8.x](https://www.php.net/)
-   [MySQL](https://www.mysql.com/)
-   [Laravel 10.x](https://laravel.com)
-   [Excel](https://laravel-excel.com/)
-   [simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode)
-   [Bootstrap 4.x - 5,x](https://getbootstrap.com/)
-   [sweetalert2](https://sweetalert2.github.io/)
-   [jQuery](https://jquery.com/)
-   [Datatables](https://datatables.net/)
-   [Brezee](https://laravel.com/docs/10.x/starter-kits)
-   Simple Mail Tranfer Protocol / SMTP Gmail

### Pengembangan
-   [Git](https://git-scm.com/)
-   [Github](https://github.com/)


## Pengunaan

Berikut adalah tahap-tahap untuk mengunakan dan menjalankan aplikasi ini

1. Fork repo ini
2. Clone repo milikmu

```bash
git clone https://github.com/[USERNAME]/ZaikoTrack.git
```

3. Install dependesi composer

```bash
composer install
```

4. Install npm

```bash
npm install
npm run build
```

5. Siapkan environment variable

```bash
cp .env.example .env
php artisan key:generate
```

6. Migrasi dan Seed

```bash
php artisan migrate --seed
```

7. Jalankan aplikasi

```bash
php artisan serve
```

## Kontribusi

Berikut adalah tahap-tahap untuk mengembakan fitur terbaru dan berktrbusi dalam proyek ini.

1. Fork repo ini
2. Clone milikmu fork
3. Lakukan beberapa pekerjaan
4. Bersihkan pekerjaan kalian dengan lint

```
./vendor/bin/pint
```

5. Checkout branch baru

```bash
git branch YOUR-NEW-FEATURE
git checkout YOUR-NEW-FEATURE
```

6. Commit

```bash
git init
git add .
git commit -m "[Your message] -yourname"
```

7. Push branch milikmu ke fork milikmu

```bash
git push -u origin --set-upstream YOUR-NEW-FEATURE
```

8. Pergi ke github UI dan buat a PR dari fork milikmu dan branch, dan merge dengan MAIN upstream kita
