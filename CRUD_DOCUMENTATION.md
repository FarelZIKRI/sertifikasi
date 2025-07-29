# Dokumentasi Sistem CRUD - Pendaftaran Beasiswa

## Overview
Sistem CRUD (Create, Read, Update, Delete) telah diimplementasikan secara lengkap untuk mengelola semua aspek sistem pendaftaran beasiswa online.

## Update Terbaru
✅ **IPK Random Generator**: Diubah dari range 2.00-4.00 menjadi **2.50-4.00** untuk lebih realistis
✅ **Sample Data**: Ditambahkan lebih banyak data mahasiswa untuk testing
✅ **Konsistensi**: Semua sistem (PHP, JavaScript, Database) menggunakan range yang sama

## Struktur CRUD

### 1. CRUD Jenis Beasiswa
**File**: `public/manage_beasiswa.php`
**Fungsi**: `includes/crud_functions.php`

#### Operasi yang Tersedia:
- **CREATE**: Menambah jenis beasiswa baru
- **READ**: Menampilkan dan mencari jenis beasiswa
- **UPDATE**: Mengedit data jenis beasiswa
- **DELETE**: Menghapus jenis beasiswa (dengan validasi)

#### Fitur Tambahan:
- ✅ Form tambah/edit dalam satu halaman
- ✅ Pencarian berdasarkan nama atau deskripsi
- ✅ Validasi penghapusan (cek apakah masih digunakan)
- ✅ Konfirmasi sebelum menghapus

### 2. CRUD Pendaftaran Beasiswa
**File**: `public/manage_pendaftaran.php`
**Fungsi**: `includes/crud_functions.php`

#### Operasi yang Tersedia:
- **CREATE**: Dilakukan melalui form pendaftaran di halaman utama
- **READ**: Menampilkan semua pendaftaran dengan filter
- **UPDATE**: Mengedit data pendaftaran dan status
- **DELETE**: Menghapus pendaftaran (termasuk file berkas)

#### Fitur Tambahan:
- ✅ Statistik dashboard (total, status)
- ✅ Pencarian berdasarkan nama/email
- ✅ Filter berdasarkan status
- ✅ Update status langsung dari dropdown
- ✅ Auto-delete file saat hapus data

### 3. CRUD Data Mahasiswa
**File**: `public/manage_mahasiswa.php`
**Fungsi**: `includes/crud_functions.php`

#### Operasi yang Tersedia:
- **CREATE**: Menambah data mahasiswa baru
- **READ**: Menampilkan semua data mahasiswa
- **UPDATE**: Mengedit data mahasiswa
- **DELETE**: Menghapus data mahasiswa

#### Fitur Tambahan:
- ✅ Validasi unique NIM dan email
- ✅ Indikator visual IPK (hijau/merah)
- ✅ Status kelayakan beasiswa
- ✅ Integrasi dengan sistem IPK otomatis

## Fungsi CRUD Utama

### Jenis Beasiswa
```php
createBeasiswa($data)       // Tambah beasiswa baru
getBeasiswaById($id)        // Ambil satu beasiswa
updateBeasiswa($id, $data)  // Update beasiswa
deleteBeasiswa($id)         // Hapus beasiswa
searchBeasiswa($keyword)    // Cari beasiswa
```

### Pendaftaran Beasiswa
```php
getPendaftaranById($id)           // Ambil satu pendaftaran
updatePendaftaran($id, $data)     // Update data pendaftaran
updateStatusPendaftaran($id, $status) // Update status
deletePendaftaran($id)            // Hapus pendaftaran
searchPendaftaran($keyword, $status) // Cari dengan filter
```

### Data Mahasiswa
```php
createMahasiswa($data)      // Tambah mahasiswa
getAllMahasiswa()           // Ambil semua mahasiswa
getMahasiswaById($id)       // Ambil satu mahasiswa
getMahasiswaByEmail($email) // Ambil berdasarkan email
updateMahasiswa($id, $data) // Update mahasiswa
deleteMahasiswa($id)        // Hapus mahasiswa
```

## Fitur Keamanan

### 1. Validasi Input
- ✅ Server-side validation untuk semua form
- ✅ Client-side validation dengan JavaScript
- ✅ Sanitasi input dengan `htmlspecialchars()`
- ✅ Prepared statements untuk mencegah SQL injection

### 2. Konfirmasi Aksi
- ✅ Konfirmasi JavaScript sebelum menghapus
- ✅ Validasi foreign key sebelum delete
- ✅ Pesan error yang informatif

### 3. File Handling
- ✅ Validasi ekstensi file upload
- ✅ Auto-delete file saat hapus data
- ✅ Unique filename untuk mencegah konflik

## Interface User

### 1. Admin Dashboard
**URL**: `public/admin.php`
- Dashboard utama admin
- Link ke semua halaman CRUD
- Quick status update pendaftaran

### 2. Kelola Jenis Beasiswa
**URL**: `public/manage_beasiswa.php`
- Form tambah/edit beasiswa
- Tabel dengan aksi edit/hapus
- Pencarian real-time

### 3. Kelola Pendaftaran
**URL**: `public/manage_pendaftaran.php`
- Statistik dashboard
- Filter dan pencarian
- Edit data pendaftaran
- Update status dropdown

### 4. Kelola Data Mahasiswa
**URL**: `public/manage_mahasiswa.php`
- Form tambah/edit mahasiswa
- Indikator visual IPK
- Status kelayakan beasiswa

## Statistik dan Reporting

### Dashboard Statistik
```php
getStatistikPendaftaran()
```
Menampilkan:
- Total pendaftaran
- Breakdown berdasarkan status
- Breakdown berdasarkan jenis beasiswa

## URL dan Navigation

### Struktur URL:
```
/public/admin.php                 - Dashboard admin
/public/manage_beasiswa.php       - CRUD jenis beasiswa
/public/manage_pendaftaran.php    - CRUD pendaftaran
/public/manage_mahasiswa.php      - CRUD data mahasiswa
```

### Parameter URL:
- `?edit=ID` - Mode edit untuk ID tertentu
- `?search=keyword` - Pencarian
- `?status=value` - Filter status

## Database Schema

### Tabel yang Terlibat:
1. `jenis_beasiswa` - Master data jenis beasiswa
2. `pendaftaran_beasiswa` - Data pendaftaran mahasiswa
3. `mahasiswa` - Data mahasiswa untuk IPK otomatis

### Relasi:
- `pendaftaran_beasiswa.jenis_beasiswa_id` → `jenis_beasiswa.id`
- `mahasiswa.email` → digunakan untuk lookup IPK otomatis

## Fitur Unggulan

### 1. One-Page CRUD
- Form tambah dan edit dalam satu halaman
- Toggle mode berdasarkan parameter URL
- UX yang smooth dan intuitif

### 2. Real-time Features
- Update status langsung dari dropdown
- Pencarian tanpa reload halaman
- Statistik yang update otomatis

### 3. Data Integrity
- Foreign key validation
- Cascade delete untuk file uploads
- Unique constraint validation

### 4. User Experience
- Responsive design
- Loading states
- Confirmation dialogs
- Success/error messages

## Cara Penggunaan

### 1. Admin Login
1. Akses `public/admin.php`
2. Pilih menu CRUD yang diinginkan

### 2. Mengelola Data
1. **Tambah**: Isi form dan klik "Tambah"
2. **Edit**: Klik tombol "Edit" di tabel
3. **Hapus**: Klik "Hapus" dan konfirmasi
4. **Cari**: Gunakan form pencarian

### 3. Workflow Typical
1. Kelola jenis beasiswa terlebih dahulu
2. Tambah data mahasiswa untuk simulasi IPK
3. Mahasiswa mendaftar melalui halaman utama
4. Admin verifikasi melalui kelola pendaftaran

## Maintenance dan Development

### File yang Perlu Diperhatikan:
- `includes/crud_functions.php` - Logika CRUD utama
- `config/database.php` - Konfigurasi database
- `assets/css/style.css` - Styling interface
- `uploads/` - Folder file berkas (perlu permission write)

### Best Practices:
- Selalu backup database sebelum update
- Test fungsi CRUD di environment development
- Monitor ukuran folder uploads
- Regular cleanup file yang tidak terpakai

Sistem CRUD ini telah lengkap dan siap untuk production dengan semua fitur keamanan dan user experience yang diperlukan.