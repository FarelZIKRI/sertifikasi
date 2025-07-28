# Sistem Pendaftaran Beasiswa

Sistem pendaftaran beasiswa online untuk kampus yang dibuat menggunakan PHP Native, HTML, CSS, dan JavaScript.

## Deskripsi Proyek

Sistem ini memungkinkan mahasiswa untuk mendaftar berbagai jenis beasiswa berdasarkan IPK mereka. Sistem akan otomatis memvalidasi kelayakan mahasiswa berdasarkan IPK minimal yang dipersyaratkan untuk setiap jenis beasiswa.

## Fitur Utama

### 1. Halaman Beranda
- Informasi jenis beasiswa dan syarat-syaratnya
- Panduan cara mendaftar beasiswa
- Navigasi ke halaman pendaftaran

### 2. Pendaftaran Beasiswa
- Form pendaftaran dengan validasi lengkap
- Input IPK manual dengan validasi real-time
- Upload berkas persyaratan
- Validasi kesesuaian IPK dengan jenis beasiswa
- Disable opsi beasiswa yang tidak sesuai dengan IPK

### 3. Hasil Pendaftaran
- Tampilan semua pendaftaran dalam bentuk tabel
- Status ajuan "belum di verifikasi"
- Download berkas yang telah diupload
- Statistik pendaftaran

## Spesifikasi Teknis

- **Backend**: PHP Native (tanpa framework)
- **Frontend**: HTML5, CSS3, JavaScript (vanilla)
- **Database**: MySQL
- **Upload**: Support PDF, JPG, PNG, ZIP (max 5MB)

## Struktur Database

### Tabel `jenis_beasiswa`
- `id` (Primary Key)
- `nama_beasiswa` (VARCHAR 100)
- `deskripsi` (TEXT)
- `syarat_ipk` (DECIMAL 3,2)
- `created_at` (TIMESTAMP)

### Tabel `pendaftaran_beasiswa`
- `id` (Primary Key)
- `nama` (VARCHAR 100)
- `email` (VARCHAR 100)
- `no_hp` (VARCHAR 15)
- `semester` (INT)
- `ipk` (DECIMAL 3,2)
- `jenis_beasiswa_id` (Foreign Key)
- `berkas_syarat` (VARCHAR 255)
- `status_ajuan` (VARCHAR 50, default: 'belum di verifikasi')
- `created_at` (TIMESTAMP)

## Struktur Folder

```
sistem_beasiswa/
├── config/
│   └── database.php          # Konfigurasi database
├── css/
│   └── style.css            # Stylesheet utama
├── js/
│   └── script.js            # JavaScript untuk validasi
├── includes/
│   └── functions.php        # Fungsi-fungsi PHP
├── uploads/                 # Folder untuk file upload
├── index.php               # Halaman beranda
├── daftar.php              # Form pendaftaran
├── hasil.php               # Hasil pendaftaran
├── setup.php               # Setup otomatis sistem
├── database.sql            # Script database
├── README.md               # Dokumentasi
└── TROUBLESHOOTING.md      # Panduan troubleshooting
```

## Instalasi

### 🚀 Quick Start (Recommended)

1. **Copy Project ke Web Server:**
   ```bash
   # XAMPP
   cp -r sistem_beasiswa C:\xampp\htdocs\beasiswa
   
   # WAMP  
   cp -r sistem_beasiswa C:\wamp64\www\beasiswa
   
   # Linux
   sudo cp -r sistem_beasiswa /var/www/html/beasiswa
   ```

2. **Setup Database:**
   ```sql
   -- Buat database
   CREATE DATABASE sistem_beasiswa;
   
   -- Import data
   mysql -u root -p sistem_beasiswa < database.sql
   ```

3. **Jalankan Setup Otomatis:**
   ```
   http://localhost/beasiswa/setup.php
   ```

4. **Akses Sistem:**
   ```
   http://localhost/beasiswa/
   ```

### 📋 Manual Setup

#### 1. Persiapan Database
```sql
-- Buat database
CREATE DATABASE sistem_beasiswa;

-- Import file database.sql
mysql -u root -p sistem_beasiswa < database.sql
```

#### 2. Konfigurasi Database
Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistem_beasiswa');
```

#### 3. Setup Folder Upload
```bash
# Linux/Mac
mkdir uploads
chmod 755 uploads

# Windows - Buat folder uploads manual
# Set permission Full Control untuk Users
```

#### 4. Web Server Setup
- Pastikan PHP ≥ 7.4 dan MySQL ≥ 5.7
- Copy project ke document root
- Set permission folder yang tepat
- Akses: `http://localhost/sistem_beasiswa`

### ⚠️ Troubleshooting
Jika ada error upload file atau masalah lain, lihat file `TROUBLESHOOTING.md` untuk solusi lengkap.

## Validasi Form

### Client-side (JavaScript)
- Validasi format email
- Validasi nomor HP (hanya angka, min 10 digit)
- Validasi semester (1-8)
- Validasi IPK (0.00 - 4.00) dengan feedback real-time
- Validasi kesesuaian IPK dengan jenis beasiswa
- Validasi file upload (tipe dan ukuran)
- Real-time feedback untuk user

### Server-side (PHP)
- Validasi ulang semua input
- Sanitasi data sebelum disimpan
- Validasi IPK minimal 3.0
- Validasi file upload dengan whitelist

## Logika Bisnis

### IPK dan Kelayakan
1. IPK diinput manual oleh mahasiswa (range 0.00 - 4.00)
2. Validasi real-time saat input IPK
3. Jika IPK < 3.0: tidak bisa mendaftar beasiswa (validasi form)
4. Jika IPK < syarat beasiswa: opsi beasiswa di-disable
5. Sistem menampilkan status kelayakan IPK secara dinamis

### Jenis Beasiswa
1. **Beasiswa Akademik**: IPK minimal 3.5
2. **Beasiswa Non-Akademik**: IPK minimal 3.0
3. **Beasiswa Prestasi**: IPK minimal 3.25

### Status Pendaftaran
- Default: "belum di verifikasi"
- Dapat diupdate oleh admin (fitur bisa dikembangkan)

## User dan Hak Akses

### Jumlah Tipe User: 1
- **Mahasiswa**: User utama sistem

### Hak Akses Mahasiswa:
- Melihat informasi beasiswa
- Mendaftar beasiswa (jika memenuhi syarat IPK)
- Upload berkas persyaratan
- Melihat status pendaftaran
- Download berkas yang telah diupload

## Keamanan

### Input Validation
- Semua input divalidasi dan disanitasi
- Prepared statements untuk query database
- File upload dengan whitelist extension

### File Upload Security
- Validasi tipe file berdasarkan MIME type
- Pembatasan ukuran file (5MB)
- Rename file untuk mencegah conflict
- Folder upload di luar document root (recommended)

## Browser Support

- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+

## Pengembangan Selanjutnya

### Fitur yang Bisa Ditambahkan:
1. **Admin Panel**
   - Login admin
   - Verifikasi pendaftaran
   - Update status beasiswa
   - Laporan statistik

2. **User Authentication**
   - Login mahasiswa
   - Session management
   - Password reset

3. **Notifikasi**
   - Email notification
   - SMS notification
   - Status update alerts

4. **Advanced Features**
   - Export data ke Excel/PDF
   - Search dan filter data
   - Pagination untuk tabel besar
   - Dashboard analytics

## Troubleshooting

### Masalah Umum:

1. **Database Connection Error**
   - Periksa konfigurasi di `config/database.php`
   - Pastikan MySQL service berjalan
   - Cek username/password database

2. **File Upload Gagal**
   - Periksa permission folder `uploads` (755)
   - Cek setting `upload_max_filesize` di php.ini
   - Pastikan folder `uploads` exists

3. **JavaScript Error**
   - Periksa console browser untuk error
   - Pastikan file `js/script.js` dapat diakses
   - Cek kompatibilitas browser

## Kontribusi

Proyek ini dibuat untuk keperluan pembelajaran. Anda dapat:
- Melaporkan bug melalui issues
- Mengirim pull request untuk perbaikan
- Memberikan saran pengembangan

## Lisensi

Proyek ini dibuat untuk keperluan akademik dan pembelajaran.

---

**Dibuat oleh**: Tim Pengembang Web  
**Tanggal**: 2024  
**Versi**: 1.0