<?php
/**
 * Setup file untuk sistem beasiswa
 * Jalankan file ini sekali untuk setup awal
 */

echo "<h2>Setup Sistem Pendaftaran Beasiswa</h2>";

// 1. Buat folder uploads
$uploads_dir = 'uploads';
if (!file_exists($uploads_dir)) {
    if (mkdir($uploads_dir, 0755, true)) {
        echo "✅ Folder uploads berhasil dibuat<br>";
    } else {
        echo "❌ Gagal membuat folder uploads<br>";
    }
} else {
    echo "✅ Folder uploads sudah ada<br>";
}

// 2. Buat file .htaccess untuk uploads
$htaccess_content = '# Uploads folder security

# Prevent execution of PHP files
<Files *.php>
    Order allow,deny
    Deny from all
</Files>

# Prevent execution of other script files
<FilesMatch "\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Allow only specific file types
<FilesMatch "\.(pdf|jpg|jpeg|png|zip)$">
    Order deny,allow
    Allow from all
</FilesMatch>

# Prevent directory browsing
Options -Indexes

# Add security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options nosniff
    Header set X-Frame-Options DENY
</IfModule>';

$htaccess_file = $uploads_dir . '/.htaccess';
if (!file_exists($htaccess_file)) {
    if (file_put_contents($htaccess_file, $htaccess_content)) {
        echo "✅ File .htaccess untuk uploads berhasil dibuat<br>";
    } else {
        echo "❌ Gagal membuat .htaccess untuk uploads<br>";
    }
} else {
    echo "✅ File .htaccess untuk uploads sudah ada<br>";
}

// 3. Test permission folder uploads
if (is_writable($uploads_dir)) {
    echo "✅ Folder uploads dapat ditulis<br>";
} else {
    echo "❌ Folder uploads tidak dapat ditulis. Ubah permission ke 755<br>";
}

// 4. Cek ekstensi PHP yang diperlukan
echo "<h3>Pengecekan Ekstensi PHP:</h3>";

$required_extensions = ['pdo', 'pdo_mysql', 'fileinfo', 'mbstring'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext: Tersedia<br>";
    } else {
        echo "❌ $ext: Tidak tersedia<br>";
    }
}

// 5. Cek setting PHP untuk upload
echo "<h3>Setting PHP Upload:</h3>";
echo "📁 upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "📁 post_max_size: " . ini_get('post_max_size') . "<br>";
echo "📁 max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "📁 file_uploads: " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "<br>";

// 6. Test koneksi database
echo "<h3>Test Koneksi Database:</h3>";
try {
    require_once 'config/database.php';
    $pdo = getConnection();
    echo "✅ Koneksi database berhasil<br>";
    
    // Cek tabel
    $tables = ['jenis_beasiswa', 'pendaftaran_beasiswa'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Tabel $table: Ada<br>";
        } else {
            echo "❌ Tabel $table: Tidak ada<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Koneksi database gagal: " . $e->getMessage() . "<br>";
    echo "💡 Pastikan database 'sistem_beasiswa' sudah dibuat dan import file database.sql<br>";
}

echo "<hr>";
echo "<h3>Langkah Selanjutnya:</h3>";
echo "<ul>";
echo "<li>Jika semua ✅, sistem siap digunakan</li>";
echo "<li>Jika ada ❌, perbaiki masalah tersebut</li>";
echo "<li>Hapus file setup.php setelah setup selesai</li>";
echo "<li>Akses <a href='index.php'>halaman utama</a></li>";
echo "</ul>";
?>