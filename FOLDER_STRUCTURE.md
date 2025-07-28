# Struktur Folder Proyek Sistem Pendaftaran Beasiswa

```
beasiswa-system/
│
├── README.md                    # Dokumentasi proyek
├── FOLDER_STRUCTURE.md         # Dokumentasi struktur folder
│
├── config/                     # Konfigurasi aplikasi
│   └── database.php           # Konfigurasi koneksi database
│
├── database/                   # File database
│   └── beasiswa_db.sql        # Script SQL untuk membuat database
│
├── includes/                   # File fungsi dan helper
│   └── functions.php          # Fungsi-fungsi PHP untuk aplikasi
│
├── assets/                     # Asset statis (CSS, JS, gambar)
│   ├── css/
│   │   └── style.css          # Stylesheet utama
│   └── js/
│       └── script.js          # JavaScript untuk interaktivitas
│
├── public/                     # File publik yang dapat diakses
│   ├── index.php              # Halaman utama sistem
│   └── admin.php              # Halaman admin untuk verifikasi
│
└── uploads/                    # Folder untuk menyimpan file upload
    └── (file berkas syarat)   # File yang diupload mahasiswa
```

## Penjelasan Struktur

### 1. Root Directory
- `README.md`: Dokumentasi utama proyek
- `FOLDER_STRUCTURE.md`: Dokumentasi struktur folder ini

### 2. config/
Berisi file konfigurasi aplikasi:
- `database.php`: Konfigurasi koneksi database MySQL dengan PDO

### 3. database/
Berisi file-file terkait database:
- `beasiswa_db.sql`: Script SQL untuk membuat database dan tabel

### 4. includes/
Berisi file-file PHP yang di-include ke file lain:
- `functions.php`: Fungsi-fungsi helper seperti validasi, upload file, CRUD database

### 5. assets/
Berisi asset statis:
- `css/style.css`: Stylesheet untuk tampilan yang modern dan responsive
- `js/script.js`: JavaScript untuk validasi form dan interaktivitas

### 6. public/
Berisi file PHP yang dapat diakses langsung:
- `index.php`: Halaman utama dengan 3 tab (Jenis Beasiswa, Daftar, Hasil)
- `admin.php`: Halaman admin untuk verifikasi status beasiswa

### 7. uploads/
Folder untuk menyimpan file yang diupload mahasiswa (berkas syarat beasiswa)

## File yang Harus Dibuat

### File Wajib:
1. `config/database.php` - Konfigurasi database
2. `database/beasiswa_db.sql` - Script database
3. `includes/functions.php` - Fungsi PHP
4. `assets/css/style.css` - Stylesheet
5. `assets/js/script.js` - JavaScript
6. `public/index.php` - Halaman utama
7. `public/admin.php` - Halaman admin

### File Opsional:
1. `README.md` - Dokumentasi
2. `FOLDER_STRUCTURE.md` - Dokumentasi struktur
3. `.htaccess` - Konfigurasi Apache (jika diperlukan)

## Cara Setup

1. Buat folder `beasiswa-system/` di web server
2. Buat semua folder dan file sesuai struktur di atas
3. Import `database/beasiswa_db.sql` ke MySQL
4. Sesuaikan konfigurasi database di `config/database.php`
5. Pastikan folder `uploads/` memiliki permission write (755 atau 777)
6. Akses `public/index.php` melalui browser

## URL Akses

- **Halaman Utama**: `http://localhost/beasiswa-system/public/index.php`
- **Halaman Admin**: `http://localhost/beasiswa-system/public/admin.php`

## Fitur yang Diimplementasi

✅ Jenis beasiswa dengan syarat (minimal 2 pilihan)
✅ Form pendaftaran dengan validasi
✅ IPK otomatis berdasarkan email
✅ Validasi IPK < 3.0 (form disabled)
✅ Validasi IPK ≥ 3.0 (form aktif, fokus ke beasiswa)
✅ Upload berkas syarat
✅ Status ajuan "belum di verifikasi"
✅ Tampilan hasil pendaftaran
✅ Halaman admin untuk verifikasi