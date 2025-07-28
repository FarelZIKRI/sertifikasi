<?php
require_once '../includes/functions.php';

// Proses update status
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    
    try {
        $pdo = getConnection();
        $sql = "UPDATE pendaftaran_beasiswa SET status_ajuan = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$status, $id])) {
            $message = "Status berhasil diupdate!";
            $message_type = "success";
        } else {
            $message = "Gagal mengupdate status!";
            $message_type = "error";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $message_type = "error";
    }
}

// Ambil semua data pendaftaran
$hasil_pendaftaran = getAllPendaftaran();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Verifikasi Beasiswa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Admin Panel - Verifikasi Beasiswa</h1>
            <p>Kelola dan verifikasi pendaftaran beasiswa</p>
        </div>
    </header>

    <div class="container">
        <?php if (isset($message)): ?>
        <div class="alert alert-<?= $message_type ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>

        <div class="content-section active">
            <h2>Daftar Pendaftaran Beasiswa</h2>
            
            <?php if (empty($hasil_pendaftaran)): ?>
            <div class="alert alert-info">
                Belum ada pendaftaran beasiswa.
            </div>
            <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Semester</th>
                            <th>IPK</th>
                            <th>Jenis Beasiswa</th>
                            <th>Berkas</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasil_pendaftaran as $hasil): ?>
                        <tr>
                            <td><?= $hasil['id'] ?></td>
                            <td><?= htmlspecialchars($hasil['nama']) ?></td>
                            <td><?= htmlspecialchars($hasil['email']) ?></td>
                            <td><?= htmlspecialchars($hasil['no_hp']) ?></td>
                            <td><?= $hasil['semester'] ?></td>
                            <td><?= number_format($hasil['ipk'], 2) ?></td>
                            <td><?= htmlspecialchars($hasil['nama_beasiswa'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($hasil['berkas_syarat']): ?>
                                <a href="../uploads/<?= htmlspecialchars($hasil['berkas_syarat']) ?>" 
                                   target="_blank" class="btn btn-sm">Lihat</a>
                                <?php else: ?>
                                N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?= 
                                    $hasil['status_ajuan'] === 'belum di verifikasi' ? 'status-pending' : 
                                    ($hasil['status_ajuan'] === 'diverifikasi' ? 'status-verified' : 'status-rejected') 
                                ?>">
                                    <?= htmlspecialchars($hasil['status_ajuan']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($hasil['created_at'])) ?></td>
                            <td>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $hasil['id'] ?>">
                                    <select name="status" class="form-control" style="width: auto; display: inline-block; margin-right: 5px;">
                                        <option value="belum di verifikasi" <?= $hasil['status_ajuan'] === 'belum di verifikasi' ? 'selected' : '' ?>>
                                            Belum Verifikasi
                                        </option>
                                        <option value="diverifikasi" <?= $hasil['status_ajuan'] === 'diverifikasi' ? 'selected' : '' ?>>
                                            Diverifikasi
                                        </option>
                                        <option value="ditolak" <?= $hasil['status_ajuan'] === 'ditolak' ? 'selected' : '' ?>>
                                            Ditolak
                                        </option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <div style="margin-top: 2rem;">
                <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
</body>
</html>