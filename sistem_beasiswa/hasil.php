<?php
require_once 'includes/functions.php';

// Ambil semua data pendaftaran
$pendaftaranList = getAllPendaftaran();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pendaftaran - Sistem Pendaftaran Beasiswa</title>
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
                <li><a href="index.php">Beranda</a></li>
                <li><a href="daftar.php">Daftar Beasiswa</a></li>
                <li><a href="hasil.php" class="active">Hasil Pendaftaran</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <div class="card">
            <h2>Hasil Pendaftaran Beasiswa</h2>
            <p>Berikut adalah daftar semua pendaftaran beasiswa yang telah disubmit:</p>
            
            <?php if (empty($pendaftaranList)): ?>
                <div class="alert alert-warning">
                    Belum ada pendaftaran beasiswa yang tersimpan.
                </div>
                <a href="daftar.php" class="btn btn-primary">Daftar Beasiswa Sekarang</a>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Semester</th>
                                <th>IPK</th>
                                <th>Jenis Beasiswa</th>
                                <th>Berkas</th>
                                <th>Status Ajuan</th>
                                <th>Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendaftaranList as $index => $pendaftaran): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($pendaftaran['nama']); ?></td>
                                <td><?php echo htmlspecialchars($pendaftaran['email']); ?></td>
                                <td><?php echo htmlspecialchars($pendaftaran['no_hp']); ?></td>
                                <td><?php echo $pendaftaran['semester']; ?></td>
                                <td><?php echo $pendaftaran['ipk']; ?></td>
                                <td><?php echo htmlspecialchars($pendaftaran['nama_beasiswa']); ?></td>
                                <td>
                                    <?php if ($pendaftaran['berkas_syarat']): ?>
                                        <a href="uploads/<?php echo htmlspecialchars($pendaftaran['berkas_syarat']); ?>" 
                                           target="_blank" class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                            Lihat Berkas
                                        </a>
                                    <?php else: ?>
                                        <span style="color: #dc3545;">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-pending">
                                        <?php echo htmlspecialchars($pendaftaran['status_ajuan']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pendaftaran['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 2rem;">
                    <h3>Statistik Pendaftaran</h3>
                    <p><strong>Total Pendaftaran:</strong> <?php echo count($pendaftaranList); ?></p>
                    <p><strong>Status Belum Diverifikasi:</strong> 
                        <?php echo count(array_filter($pendaftaranList, function($p) { 
                            return $p['status_ajuan'] === 'belum di verifikasi'; 
                        })); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Keterangan Status</h2>
            <ul>
                <li><strong>Belum di verifikasi:</strong> Pendaftaran telah diterima dan menunggu proses verifikasi dari tim akademik</li>
                <li><strong>Dalam proses:</strong> Pendaftaran sedang dalam tahap verifikasi</li>
                <li><strong>Diterima:</strong> Pendaftaran beasiswa diterima</li>
                <li><strong>Ditolak:</strong> Pendaftaran beasiswa ditolak</li>
            </ul>
        </div>

        <div class="card">
            <h2>Informasi Tambahan</h2>
            <ul>
                <li>Data yang ditampilkan adalah semua pendaftaran yang telah disubmit</li>
                <li>Status pendaftaran akan diupdate oleh tim akademik</li>
                <li>Anda dapat mengunduh berkas yang telah diupload dengan mengklik "Lihat Berkas"</li>
                <li>Untuk pertanyaan lebih lanjut, hubungi bagian akademik kampus</li>
            </ul>
            
            <div style="margin-top: 1rem;">
                <a href="daftar.php" class="btn btn-primary">Daftar Beasiswa Lagi</a>
            </div>
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