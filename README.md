# Sistem Pendaftaran Beasiswa Online

Sistem pendaftaran beasiswa berbasis web untuk kampus dengan validasi IPK otomatis.

## Spesifikasi Teknis
- PHP Native (tanpa framework)
- HTML/CSS (tanpa framework)
- JavaScript (vanilla)
- Database MySQL

## Fitur Utama
1. **Halaman Utama**
   - Jenis pilihan beasiswa dan syarat
   - Form pendaftaran beasiswa
   - View hasil beasiswa yang didaftarkan

2. **Form Pendaftaran**
   - Validasi email format
   - Validasi nomor HP (hanya angka)
   - Pilihan semester (1-8)
   - IPK otomatis dari sistem
   - Upload berkas syarat
   - Status ajuan otomatis

3. **Validasi IPK**
   - IPK < 3.0: Form disabled
   - IPK ≥ 3.0: Form aktif dan fokus ke pilihan beasiswa

## User Types
1. **Mahasiswa** - Pendaftar beasiswa
2. **Admin** - Verifikator beasiswa

## Instalasi
1. Import database dari `database/beasiswa_db.sql`
2. Konfigurasi database di `config/database.php`
3. Jalankan di web server (Apache/Nginx)