# 🏛️ Website Resmi Lembaga Adat Melayu (LAM) Kabupaten Bengkalis

Selamat datang di repositori kode sumber untuk Website Resmi Lembaga Adat Melayu (LAM) Kabupaten Bengkalis. Sistem ini dibangun dengan tujuan untuk menjadi portal informasi digital yang modern, responsif, dan aman bagi masyarakat terkait kegiatan, sejarah, dan struktur organisasi adat di Bengkalis.

Sistem ini dikembangkan menggunakan **Laravel 11** untuk pondasi *backend* dan **Filament v3** untuk manajemen konten (*admin panel*).

---

## 🚀 Fitur Utama

### 🌐 Frontend (Portal Publik)
- **Desain Responsif & Modern:** Menggunakan HTML5, Vanilla CSS dengan variabel khusus, dan Alpine.js untuk interaksi (tab, slider) tanpa membebani performa.
- **Beranda Dinamis:** Menampilkan tayangan *slide* latar belakang, banner promosi/pengumuman, berita terbaru, dan sambutan pengurus yang dapat diubah dari panel admin.
- **Profil Lembaga Terpadu:** Sistem *tab* yang intuitif untuk menampilkan Sejarah, Visi & Misi, Tugas & Fungsi, Dasar Hukum, serta Struktur Organisasi (MKA dan DPH) dalam format hierarki visual berbentuk piramida.
- **Berita & Publikasi:** Dilengkapi dengan fitur kategori, pratinjau (*thumbnail*), jumlah tayangan (*views*), dan optimasi SEO meta. Semua area kartu berita dapat di-klik untuk membaca lebih lanjut.
- **Sistem Pengaduan/Kontak:** Formulir kontak publik yang terhubung langsung ke panel admin. Dilengkapi dengan validasi **Google reCAPTCHA v2** untuk mencegah *spam*.

### 🔒 Backend (Panel Admin - Pentadbir)
- **Custom Admin Path:** Jalur admin disamarkan (menggunakan `/pentadbir`) dan jalur bawaan (`/admin`) diblokir (`404 Not Found`) untuk meningkatkan keamanan dari serangan pencarian letak admin otomatis (*automated scanning*).
- **Two-Factor Authentication (2FA):** Kewajiban menggunakan autentikasi dua langkah khusus untuk akun **Super Admin**.
- **Rate Limiting & Session Timeout:** Perlindungan *brute-force* pada halaman *login* dan fitur keluar paksa (*auto-logout*) apabila sesi admin tidak aktif dalam jangka waktu tertentu.
- **Manajemen Konten Terpusat (CMS):**
  - **Pengaturan Situs (Singleton):** Mengatur nama lembaga, logo, *favicon*, media sosial, dan alamat dari satu antarmuka tunggal.
  - **Manajemen Halaman:** Mengatur konten Beranda, Profil, Sejarah, dan Visi Misi tanpa perlu menyentuh kode.
  - **Manajemen Struktur Organisasi:** Mengelola anggota MKA dan DPH lengkap dengan foto, jabatan, dan urutan hierarki.
  - **Manajemen Berita:** Editor teks kaya (*Rich Editor*) untuk menulis artikel.
  - **Manajemen Slide & Banner:** *Upload* gambar dengan sistem *drag & drop* dan pengurutan dinamis.

---

## 🛠️ Stack Teknologi

- **Bahasa:** PHP 8.2+
- **Framework:** Laravel 11
- **Admin Panel:** Filament PHP v3
- **Database:** MySQL
- **Frontend Interactivity:** Alpine.js
- **Styling:** Custom CSS (tanpa framework eksternal yang berat)
- **Keamanan Tambahan:** Google reCAPTCHA v2, Laravel Middleware custom.

---

## 💻 Panduan Instalasi (Development)

Berikut adalah langkah-langkah untuk menjalankan proyek ini di mesin lokal Anda:

### 1. Kloning Repositori
```bash
git clone https://github.com/barbarbarox/lam_bengkalis.git
cd lam_bengkalis
```

### 2. Instalasi Dependensi
Instal dependensi PHP (Composer) dan Node.js (NPM).
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Lingkungan (*Environment*)
Salin file `.env.example` menjadi `.env`.
```bash
cp .env.example .env
php artisan key:generate
```
Edit file `.env` dan sesuaikan koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lam_bengkalis
DB_USERNAME=root
DB_PASSWORD=
```
Jangan lupa untuk menambahkan kunci **Google reCAPTCHA**:
```env
RECAPTCHA_SITE_KEY=site_key_anda
RECAPTCHA_SECRET_KEY=secret_key_anda
```

### 4. Menjalankan Migrasi & Seeder Database
Aplikasi ini sudah dilengkapi dengan *seeder* lengkap untuk menghasilkan pengaturan dasar (Singleton) dan contoh data yang sesuai dengan sistem saat ini.
```bash
php artisan migrate:fresh --seed
```
*Catatan: Perintah ini akan mengeksekusi `SuperAdminSeeder` (membuat akun admin) dan `ContentSeeder` (memasukkan data bawaan LAM Bengkalis).*

### 5. Konfigurasi Penyimpanan (*Storage*)
Agar gambar yang diunggah (seperti logo, *thumbnail* berita, foto profil) dapat diakses oleh publik, tautkan direktori *storage*:
```bash
php artisan storage:link
```

### 6. Jalankan Aplikasi Lokal
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui `http://127.0.0.1:8000`.

---

## 🔑 Akses Administrator (Pentadbir)

Untuk masuk ke panel manajemen konten, akses URL berikut:
**URL:** `http://127.0.0.1:8000/pentadbir`

**Kredensial Bawaan (Hasil Seeder):**
- **Email:** `admin@lam-bengkalis.go.id`
- **Password:** `password`

*(Catatan: Anda akan diminta untuk mengatur **Google Authenticator (2FA)** saat pertama kali masuk menggunakan akun berlevel Super Admin ini).*

---

## 📁 Struktur Direktori Penting

- `app/Filament/` - Berisi seluruh halaman, fitur form, dan antarmuka manajemen data Panel Admin (Filament).
- `app/Http/Controllers/Public/` - Pengendali (*Controllers*) untuk halaman web publik (Beranda, Profil, Berita, Kontak).
- `app/Models/` - Model *database* seperti `SiteSetting` (Singleton), `Berita`, `StrukturOrganisasi`.
- `resources/views/public/` - File antarmuka (*Blade templates*) untuk pengunjung website.
- `resources/css/app.css` - File CSS utama di mana tema warna, tata letak dasar, dan desain *card* didefinisikan.

---

## 📝 Catatan Rilis

Versi saat ini telah menyelesaikan migrasi ke MySQL, integrasi hierarki Struktur Organisasi berbasis Flexbox, perbaikan klik pada komponen Berita, dan penguatan keamanan jalur `/pentadbir` dengan pembatasan percobaan akses (*rate limiting*).

---
*Dibuat untuk melestarikan dan menyebarkan budaya serta maklumat adat Lembaga Adat Melayu Kabupaten Bengkalis.*
