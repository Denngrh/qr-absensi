# Instalasi dan Setup

## 1. Install Dependencies
```bash
composer install
npm install
```

## 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

## 3. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=qr_absensi
DB_USERNAME=root
DB_PASSWORD=
```

## 4. Jalankan Migration dan Seeder
```bash
php artisan migrate
php artisan db:seed
```

## 5. Install Package untuk PDF Export (Optional)
```bash
composer require barryvdh/laravel-dompdf
```

Tambahkan ke `config/app.php`:
```php
'providers' => [
    // ...
    Barryvdh\DomPDF\ServiceProvider::class,
],

'aliases' => [
    // ...
    'PDF' => Barryvdh\DomPDF\Facade::class,
],
```

## 6. Jalankan Aplikasi
```bash
php artisan serve
```

## Login Credentials
- **Email**: admin@admin.com
- **Password**: password

---

## Fitur yang Tersedia

### 1. Autentikasi
- Login admin dengan validasi role
- Logout

### 2. CRUD Mahasiswa
- Tambah, Edit, Hapus data mahasiswa
- Field: NIM, Nama, Jurusan, Semester, Email, No HP

### 3. CRUD Panitia
- Tambah, Edit, Hapus data panitia
- Field: NIP, Nama, Jabatan, Email, No HP

### 4. QR Code Management
- Generate QR Code untuk event
- View/Download QR Code
- Edit dan nonaktifkan QR Code
- Field: Event Name, Date, Time, Location, Description

### 5. Scan QR Code
- Input manual atau scan QR Code
- Pilih tipe peserta (Mahasiswa/Panitia)
- Validasi duplikasi absensi
- Feedback realtime

### 6. Rekap Absensi
- Filter berdasarkan: Event, Tanggal, Tipe Peserta
- Export ke PDF
- Tampilan tabel lengkap

### 7. Dashboard
- Statistik total (Mahasiswa, Panitia, QR Code, Absensi)
- Daftar absensi terbaru

---

## Sample Data yang Sudah Disiapkan

### Admin
- Email: admin@admin.com
- Password: password

### Mahasiswa
1. NIM: 12345678, Nama: John Doe, Jurusan: Teknik Informatika
2. NIM: 87654321, Nama: Jane Smith, Jurusan: Sistem Informasi

### Panitia
1. NIP: 198001012020011001, Nama: Dr. Ahmad Wijaya, Jabatan: Ketua Panitia
2. NIP: 198502022020022002, Nama: Sarah Kusuma, Jabatan: Sekretaris

---

## CDN yang Digunakan
- Bootstrap 5.3.0
- Bootstrap Icons 1.11.0
- jQuery 3.6.0
- QRCode.js 1.0.0

---

## Struktur Database

### Table: users
- id, name, email, password, role (admin/mahasiswa/panitia)

### Table: mahasiswas
- id, nim, nama, jurusan, semester, email, no_hp

### Table: panitias
- id, nip, nama, jabatan, email, no_hp

### Table: qr_codes
- id, code, event_name, event_date, start_time, end_time, location, description, is_active

### Table: absensis
- id, qr_code_id, participant_type, participant_id, scan_time, status, notes

---

## Tips Penggunaan

1. **Generate QR Code**: Buat event baru di menu QR Code, lalu download QR Code yang dihasilkan
2. **Scan Absensi**: Buka menu Scan QR, masukkan kode QR (atau scan), pilih tipe peserta dan nama peserta
3. **Lihat Rekap**: Buka menu Rekap Absensi untuk melihat dan export data absensi
4. **Export PDF**: Gunakan tombol Export PDF untuk download rekap dalam format PDF

---

## Troubleshooting

### Error saat migrate
```bash
php artisan migrate:fresh --seed
```

### QR Code tidak muncul
Pastikan CDN QRCode.js sudah terload dengan baik. Cek koneksi internet.

### Export PDF tidak jalan
Install package dompdf terlebih dahulu:
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```
