<?php
require_once '../includes/functions.php';
require_once '../includes/crud_functions.php';

$message = '';
$message_type = '';

// Proses CRUD
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update':
            $id = (int)$_POST['id'];
            $data = [
                'nama' => trim($_POST['nama']),
                'email' => trim($_POST['email']),
                'no_hp' => trim($_POST['no_hp']),
                'semester' => (int)$_POST['semester'],
                'jenis_beasiswa_id' => (int)$_POST['jenis_beasiswa_id']
            ];
            
            if (updatePendaftaran($id, $data)) {
                $message = 'Data pendaftaran berhasil diupdate!';
                $message_type = 'success';
            } else {
                $message = 'Gagal mengupdate data pendaftaran!';
                $message_type = 'error';
            }
            break;
            
        case 'update_status':
            $id = (int)$_POST['id'];
            $status = $_POST['status'];
            
            if (updateStatusPendaftaran($id, $status)) {
                $message = 'Status berhasil diupdate!';
                $message_type = 'success';
            } else {
                $message = 'Gagal mengupdate status!';
                $message_type = 'error';
            }
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            if (deletePendaftaran($id)) {
                $message = 'Data pendaftaran berhasil dihapus!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menghapus data pendaftaran!';
                $message_type = 'error';
            }
            break;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_data = getPendaftaranById((int)$_GET['edit']);
}

// Ambil semua data pendaftaran
$pendaftaran_list = getAllPendaftaran();

// Pencarian dan filter
$search_keyword = $_GET['search'] ?? '';
$filter_status = $_GET['status'] ?? '';

if ($search_keyword || $filter_status) {
    $pendaftaran_list = searchPendaftaran($search_keyword, $filter_status);
}

// Ambil data beasiswa untuk dropdown
$beasiswa_list = getAllBeasiswa();

// Statistik
$stats = getStatistikPendaftaran();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pendaftaran Beasiswa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .search-container {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Kelola Pendaftaran Beasiswa</h1>
            <p>Edit, Verifikasi, dan Hapus Pendaftaran</p>
        </div>
    </header>

    <div class="container">
        <!-- Navigation -->
        <div style="margin-bottom: 2rem;">
            <a href="admin.php" class="btn btn-primary">← Kembali ke Admin</a>
            <a href="index.php" class="btn btn-primary">Halaman Utama</a>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>">
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>

        <!-- Statistik -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?= $stats['total'] ?? 0 ?></div>
                <div>Total Pendaftaran</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['status']['belum di verifikasi'] ?? 0 ?></div>
                <div>Belum Verifikasi</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['status']['diverifikasi'] ?? 0 ?></div>
                <div>Diverifikasi</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $stats['status']['ditolak'] ?? 0 ?></div>
                <div>Ditolak</div>
            </div>
        </div>

        <!-- Form Edit (jika ada) -->
        <?php if ($edit_data): ?>
        <div class="form-container">
            <h2>Edit Data Pendaftaran</h2>
            
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" class="form-control" required
                               value="<?= htmlspecialchars($edit_data['nama']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required
                               value="<?= htmlspecialchars($edit_data['email']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="no_hp">Nomor HP *</label>
                        <input type="tel" id="no_hp" name="no_hp" class="form-control" required
                               value="<?= htmlspecialchars($edit_data['no_hp']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="semester">Semester *</label>
                        <select id="semester" name="semester" class="form-control" required>
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?= $i ?>" <?= $edit_data['semester'] == $i ? 'selected' : '' ?>>
                                Semester <?= $i ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="jenis_beasiswa_id">Jenis Beasiswa *</label>
                    <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="form-control" required>
                        <?php foreach ($beasiswa_list as $beasiswa): ?>
                        <option value="<?= $beasiswa['id'] ?>" 
                                <?= $edit_data['jenis_beasiswa_id'] == $beasiswa['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($beasiswa['nama_beasiswa']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>IPK Saat Ini</label>
                    <input type="text" class="form-control" readonly 
                           value="<?= number_format($edit_data['ipk'], 2) ?>">
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                    <a href="manage_pendaftaran.php" class="btn" style="background: #6c757d; color: white;">Batal</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Pencarian dan Filter -->
        <div class="search-container">
            <form method="GET" style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
                <div class="form-group" style="flex: 2; margin-bottom: 0;">
                    <label for="search">Cari Pendaftaran</label>
                    <input type="text" id="search" name="search" class="form-control" 
                           placeholder="Cari berdasarkan nama atau email..."
                           value="<?= htmlspecialchars($search_keyword) ?>">
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label for="status">Filter Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="belum di verifikasi" <?= $filter_status === 'belum di verifikasi' ? 'selected' : '' ?>>
                            Belum Verifikasi
                        </option>
                        <option value="diverifikasi" <?= $filter_status === 'diverifikasi' ? 'selected' : '' ?>>
                            Diverifikasi
                        </option>
                        <option value="ditolak" <?= $filter_status === 'ditolak' ? 'selected' : '' ?>>
                            Ditolak
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                <?php if ($search_keyword || $filter_status): ?>
                <a href="manage_pendaftaran.php" class="btn" style="background: #6c757d; color: white;">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="content-section active">
            <h2>Daftar Pendaftaran Beasiswa</h2>
            
            <?php if (empty($pendaftaran_list)): ?>
            <div class="alert alert-info">
                <?= ($search_keyword || $filter_status) ? 'Tidak ada data yang sesuai dengan pencarian/filter.' : 'Belum ada pendaftaran beasiswa.' ?>
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
                        <?php foreach ($pendaftaran_list as $pendaftaran): ?>
                        <tr>
                            <td><?= $pendaftaran['id'] ?></td>
                            <td><strong><?= htmlspecialchars($pendaftaran['nama']) ?></strong></td>
                            <td><?= htmlspecialchars($pendaftaran['email']) ?></td>
                            <td><?= htmlspecialchars($pendaftaran['no_hp']) ?></td>
                            <td><?= $pendaftaran['semester'] ?></td>
                            <td><?= number_format($pendaftaran['ipk'], 2) ?></td>
                            <td><?= htmlspecialchars($pendaftaran['nama_beasiswa'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($pendaftaran['berkas_syarat']): ?>
                                <a href="../uploads/<?= htmlspecialchars($pendaftaran['berkas_syarat']) ?>" 
                                   target="_blank" class="btn btn-sm">Lihat</a>
                                <?php else: ?>
                                N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="id" value="<?= $pendaftaran['id'] ?>">
                                    <select name="status" class="form-control" style="width: auto; font-size: 0.9rem;" 
                                            onchange="this.form.submit()">
                                        <option value="belum di verifikasi" <?= $pendaftaran['status_ajuan'] === 'belum di verifikasi' ? 'selected' : '' ?>>
                                            Belum Verifikasi
                                        </option>
                                        <option value="diverifikasi" <?= $pendaftaran['status_ajuan'] === 'diverifikasi' ? 'selected' : '' ?>>
                                            Diverifikasi
                                        </option>
                                        <option value="ditolak" <?= $pendaftaran['status_ajuan'] === 'ditolak' ? 'selected' : '' ?>>
                                            Ditolak
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td><?= date('d/m/Y', strtotime($pendaftaran['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?= $pendaftaran['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Yakin ingin menghapus pendaftaran ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $pendaftaran['id'] ?>">
                                        <button type="submit" class="btn btn-sm" 
                                                style="background: #dc3545; color: white;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>