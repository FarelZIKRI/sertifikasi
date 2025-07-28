<?php
require_once 'includes/functions.php';

// Ambil semua jenis beasiswa
$beasiswaList = getAllBeasiswa();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendaftaran Beasiswa</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Sistem Pendaftaran Beasiswa</h1>
            <p>Portal Online Pendaftaran Beasiswa Kampus</p>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php" class="active">Beranda</a></li>
                <li><a href="daftar.php">Daftar Beasiswa</a></li>
                <li><a href="hasil.php">Hasil Pendaftaran</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <div class="card">
            <h2>Selamat Datang di Sistem Pendaftaran Beasiswa</h2>
            <p>Sistem ini memungkinkan mahasiswa untuk mendaftar berbagai jenis beasiswa yang tersedia di kampus. Pastikan IPK Anda memenuhi syarat minimal untuk dapat mendaftar beasiswa.</p>
        </div>

        <div class="card">
            <h2>Jenis Beasiswa dan Ketentuan</h2>
            <p>Berikut adalah jenis beasiswa yang tersedia beserta syarat-syaratnya:</p>
            
            <div class="beasiswa-grid">
                <?php foreach ($beasiswaList as $beasiswa): ?>
                <div class="beasiswa-card">
                    <h3><?php echo htmlspecialchars($beasiswa['nama_beasiswa']); ?></h3>
                    <p><?php echo htmlspecialchars($beasiswa['deskripsi']); ?></p>
                    <div class="syarat">
                        Syarat IPK Minimal: <?php echo $beasiswa['syarat_ipk']; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card">
            <h2>Cara Mendaftar Beasiswa</h2>
            <ol>
                <li>Pastikan IPK Anda minimal 3.0</li>
                <li>Pilih jenis beasiswa yang sesuai dengan kriteria Anda</li>
                <li>Isi form pendaftaran dengan lengkap dan benar</li>
                <li>Upload berkas persyaratan (PDF, JPG, PNG, atau ZIP)</li>
                <li>Submit pendaftaran dan tunggu proses verifikasi</li>
            </ol>
            <br>
            <a href="daftar.php" class="btn btn-primary">Mulai Pendaftaran</a>
        </div>

        <div class="card">
            <h2>Informasi Penting</h2>
            <ul>
                <li>IPK akan diambil secara otomatis dari sistem akademik</li>
                <li>Mahasiswa dengan IPK di bawah 3.0 tidak dapat mendaftar beasiswa</li>
                <li>Pastikan berkas yang diupload sesuai dengan persyaratan</li>
                <li>Status pendaftaran dapat dilihat di menu "Hasil Pendaftaran"</li>
                <li>Proses verifikasi dilakukan oleh tim akademik kampus</li>
            </ul>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Sistem Pendaftaran Beasiswa. Dibuat untuk keperluan akademik.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>