# Sistem Pendaftaran Beasiswa Online

Sistem web untuk pendaftaran beasiswa kampus dengan validasi IPK dan proses verifikasi admin.

## 📋 Analisis User, Hak Akses, dan Peran

### Jumlah User: **2 Jenis User**

#### 1. **Mahasiswa** (User Utama)
**Peran:**
- Pendaftar beasiswa
- Pemilik data pendaftaran

**Hak Akses:**
- ✅ Melihat jenis beasiswa dan syarat-syarat
- ✅ Mengisi form pendaftaran beasiswa
- ✅ Generate IPK otomatis dari sistem
- ✅ Memilih jenis beasiswa (jika IPK ≥ 3.0)
- ✅ Upload berkas syarat (PDF, JPG, PNG, ZIP)
- ✅ Melihat hasil pendaftaran sendiri
- ✅ Melihat status verifikasi pengajuan
- ❌ Tidak dapat mengubah status verifikasi
- ❌ Tidak dapat melihat data mahasiswa lain

#### 2. **Admin/Verifikator** (User Pengelola)
**Peran:**
- Verifikator pengajuan beasiswa
- Pengelola data sistem

**Hak Akses:**
- ✅ Melihat semua pengajuan beasiswa
- ✅ Mengubah status verifikasi (Setujui/Tolak/Pending)
- ✅ Melihat detail lengkap semua pendaftar
- ✅ Mengelola proses verifikasi
- ✅ Akses ke admin panel
- ❌ Tidak dapat mengubah data pendaftar

## 🚀 Fitur Sistem

### Halaman Utama
- **Beranda**: Overview sistem dan jenis beasiswa
- **Jenis Beasiswa**: Detail 3 jenis beasiswa dengan syarat
- **Daftar Beasiswa**: Form pendaftaran dengan validasi
- **Hasil Pendaftaran**: Tampilan data yang sudah didaftar
- **Admin Panel**: Interface untuk verifikasi

### Jenis Beasiswa (Minimal 2 + 1 Tambahan)
1. **Beasiswa Akademik**
   - IPK minimal: 3.50
   - Semester: 3-8
   - Benefit: 100% biaya kuliah + uang saku

2. **Beasiswa Non-Akademik**
   - IPK minimal: 3.00
   - Semester: 2-8
   - Benefit: 50% biaya kuliah + dana pengembangan

3. **Beasiswa Sosial Ekonomi**
   - IPK minimal: 3.00
   - Semester: 1-8
   - Benefit: 75% biaya kuliah + bantuan hidup

### Validasi Form
- ✅ Nama lengkap (wajib)
- ✅ Email dengan format validation
- ✅ Nomor HP (hanya angka, min 10 digit)
- ✅ Semester (dropdown 1-8)
- ✅ IPK otomatis generate (konstanta random)
- ✅ Pilihan beasiswa (aktif jika IPK ≥ 3.0)
- ✅ Upload berkas (PDF/JPG/PNG/ZIP, max 5MB)

### Logika IPK
- **IPK < 3.0**: Form disabled, tidak bisa lanjut
- **IPK ≥ 3.0**: Form aktif, kursor auto focus ke pilihan beasiswa
- **Status Default**: "belum di verifikasi"

## 🛠️ Teknologi

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Framework CSS**: Bootstrap 5.1.3
- **Icons**: Font Awesome 6.0.0
- **Storage**: Local Storage (simulasi database)
- **Responsive**: Mobile-friendly design

## 📱 Cara Penggunaan

### Untuk Mahasiswa:
1. Buka halaman "Jenis Beasiswa" untuk melihat syarat
2. Masuk ke "Daftar Beasiswa"
3. Isi data pribadi (nama, email, HP, semester)
4. Klik "Generate IPK" untuk mendapat IPK otomatis
5. Jika IPK ≥ 3.0, pilih jenis beasiswa dan upload berkas
6. Submit form
7. Lihat hasil di "Hasil Pendaftaran"

### Untuk Admin:
1. Masuk ke "Admin Panel"
2. Lihat semua pengajuan yang masuk
3. Review data mahasiswa
4. Ubah status: Setujui/Tolak/Pending
5. Status otomatis tersimpan

## 🔧 Instalasi

1. Clone atau download repository
2. Buka `index.html` di browser
3. Sistem siap digunakan (tidak perlu server)

## 📊 Status Verifikasi

- 🟡 **Belum di verifikasi**: Status default saat mendaftar
- 🟢 **Disetujui**: Pengajuan diterima admin
- 🔴 **Ditolak**: Pengajuan tidak memenuhi syarat

## 💾 Penyimpanan Data

Data disimpan di Local Storage browser dengan struktur:
```javascript
{
  id: timestamp,
  nama: string,
  email: string,
  hp: string,
  semester: number,
  ipk: float,
  pilihan_beasiswa: string,
  berkas_name: string,
  berkas_size: string,
  tanggal_daftar: string,
  status_ajuan: string
}
```

## 🎨 Desain

- Modern gradient background
- Responsive card-based layout
- Interactive hover effects
- Color-coded status badges
- Icon-based navigation
- Professional typography

---

**Dibuat untuk memenuhi studi kasus sistem pendaftaran beasiswa kampus online**