# Panduan Troubleshooting Sistem Beasiswa

## 🚨 Error Upload File

### Error: "No such file or directory"
```
Warning: move_uploaded_file(uploads/xxxxx.pdf): Failed to open stream: No such file or directory
```

**Penyebab:**
- Folder `uploads` belum dibuat
- Permission folder tidak tepat
- Path folder salah

**Solusi:**
1. **Jalankan Setup Otomatis:**
   ```
   http://localhost/beasiswa/setup.php
   ```

2. **Manual Setup:**
   ```bash
   # Buat folder uploads
   mkdir uploads
   chmod 755 uploads
   ```

3. **Windows (XAMPP):**
   - Buat folder `uploads` di dalam folder proyek
   - Klik kanan folder → Properties → Security → Edit
   - Berikan Full Control untuk Users

### Error: "Permission denied"
```
Warning: move_uploaded_file(): Unable to move
```

**Solusi:**
```bash
# Linux/Mac
chmod 755 uploads
chown www-data:www-data uploads

# Windows
- Klik kanan folder uploads
- Properties → Security → Edit
- Tambah permission untuk IIS_IUSRS atau Users
```

### Error: "File too large"

**Penyebab:** Setting PHP upload terlalu kecil

**Solusi - Edit php.ini:**
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_file_uploads = 20
memory_limit = 128M
```

**Restart Apache/Nginx setelah edit php.ini**

## 🗄️ Error Database

### Error: "Connection refused"
```
SQLSTATE[HY000] [2002] Connection refused
```

**Solusi:**
1. Pastikan MySQL/MariaDB berjalan
2. Cek konfigurasi di `config/database.php`
3. Pastikan database `sistem_beasiswa` sudah dibuat

### Error: "Table doesn't exist"

**Solusi:**
```sql
-- Import database
mysql -u root -p sistem_beasiswa < database.sql

-- Atau via phpMyAdmin
-- Import file database.sql
```

## ⚙️ Setup Environment

### XAMPP Setup
1. **Download & Install XAMPP**
2. **Start Apache & MySQL**
3. **Copy project ke htdocs:**
   ```
   C:\xampp\htdocs\beasiswa\
   ```
4. **Buat Database:**
   - Buka http://localhost/phpmyadmin
   - Buat database `sistem_beasiswa`
   - Import file `database.sql`

5. **Jalankan Setup:**
   ```
   http://localhost/beasiswa/setup.php
   ```

### WAMP Setup
1. **Install WAMP**
2. **Copy project ke www:**
   ```
   C:\wamp64\www\beasiswa\
   ```
3. **Setup database sama seperti XAMPP**

### LAMP (Linux) Setup
```bash
# Install Apache, MySQL, PHP
sudo apt update
sudo apt install apache2 mysql-server php php-mysql

# Copy project
sudo cp -r beasiswa /var/www/html/

# Set permission
sudo chown -R www-data:www-data /var/www/html/beasiswa
sudo chmod -R 755 /var/www/html/beasiswa

# Setup database
mysql -u root -p
CREATE DATABASE sistem_beasiswa;
exit

mysql -u root -p sistem_beasiswa < database.sql
```

## 🔧 Common Issues

### 1. **Blank Page / White Screen**
**Penyebab:** PHP Error tidak ditampilkan

**Solusi:**
```php
// Tambah di awal file PHP untuk debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### 2. **CSS/JS Tidak Load**
**Penyebab:** Path file salah

**Solusi:**
- Pastikan struktur folder benar
- Cek path di HTML: `css/style.css`, `js/script.js`

### 3. **Form Tidak Submit**
**Penyebab:** JavaScript error atau validasi gagal

**Solusi:**
- Buka Developer Tools (F12)
- Cek Console untuk error JavaScript
- Pastikan semua field required terisi

### 4. **File Upload Gagal**
**Cek Setting PHP:**
```php
<?php
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "file_uploads: " . (ini_get('file_uploads') ? 'ON' : 'OFF') . "<br>";
echo "upload_tmp_dir: " . ini_get('upload_tmp_dir') . "<br>";
?>
```

## 📝 Debug Mode

### Enable Error Reporting
```php
// Tambah di config/database.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
```

### Check Upload Info
```php
// Tambah di daftar.php untuk debug
if ($_POST) {
    echo "<pre>";
    echo "POST Data:\n";
    print_r($_POST);
    echo "\nFILES Data:\n";
    print_r($_FILES);
    echo "</pre>";
}
```

## 🆘 Bantuan Lebih Lanjut

### Log Files Lokasi:
- **XAMPP:** `C:\xampp\apache\logs\error.log`
- **WAMP:** `C:\wamp64\logs\apache_error.log`
- **Linux:** `/var/log/apache2/error.log`

### Quick Diagnostic:
```bash
# Cek Apache status
sudo systemctl status apache2

# Cek MySQL status
sudo systemctl status mysql

# Cek PHP version
php -v

# Cek PHP modules
php -m | grep -i pdo
```

### Contact Support:
Jika masih ada masalah, sertakan informasi berikut:
- OS dan versi (Windows 10, Ubuntu 20.04, etc.)
- Web server (Apache, Nginx)
- PHP version
- Error message lengkap
- Screenshot jika perlu

---

**💡 Tips:** Selalu jalankan `setup.php` terlebih dahulu sebelum menggunakan sistem!