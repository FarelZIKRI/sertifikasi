<?php
require_once '../includes/functions.php';
require_once '../includes/crud_functions.php';

$message = '';
$message_type = '';

// Proses CRUD
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $data = [
                'nim' => trim($_POST['nim']),
                'nama' => trim($_POST['nama']),
                'email' => trim($_POST['email']),
                'ipk' => (float)$_POST['ipk'],
                'semester' => (int)$_POST['semester']
            ];
            
            if (createMahasiswa($data)) {
                $message = 'Data mahasiswa berhasil ditambahkan!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menambahkan data mahasiswa! (NIM atau Email mungkin sudah ada)';
                $message_type = 'error';
            }
            break;
            
        case 'update':
            $id = (int)$_POST['id'];
            $data = [
                'nim' => trim($_POST['nim']),
                'nama' => trim($_POST['nama']),
                'email' => trim($_POST['email']),
                'ipk' => (float)$_POST['ipk'],
                'semester' => (int)$_POST['semester']
            ];
            
            if (updateMahasiswa($id, $data)) {
                $message = 'Data mahasiswa berhasil diupdate!';
                $message_type = 'success';
            } else {
                $message = 'Gagal mengupdate data mahasiswa!';
                $message_type = 'error';
            }
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            if (deleteMahasiswa($id)) {
                $message = 'Data mahasiswa berhasil dihapus!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menghapus data mahasiswa!';
                $message_type = 'error';
            }
            break;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_data = getMahasiswaById((int)$_GET['edit']);
}

// Ambil semua data mahasiswa
$mahasiswa_list = getAllMahasiswa();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Mahasiswa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .ipk-high {
            color: #28a745;
            font-weight: bold;
        }
        .ipk-low {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Kelola Data Mahasiswa</h1>
            <p>Tambah, Edit, dan Hapus Data Mahasiswa</p>
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

        <!-- Form Tambah/Edit -->
        <div class="form-container">
            <h2><?= $edit_data ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa Baru' ?></h2>
            
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit_data ? 'update' : 'create' ?>">
                <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="nim">NIM *</label>
                        <input type="text" id="nim" name="nim" class="form-control" required
                               value="<?= htmlspecialchars($edit_data['nim'] ?? '') ?>"
                               placeholder="Contoh: 12345678">
                    </div>
                    
                    <div class="form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" class="form-control" required
                               value="<?= htmlspecialchars($edit_data['nama'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required
                               value="<?= htmlspecialchars($edit_data['email'] ?? '') ?>"
                               placeholder="contoh@email.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="ipk">IPK *</label>
                        <input type="number" id="ipk" name="ipk" class="form-control" 
                               min="0" max="4" step="0.01" required
                               value="<?= $edit_data['ipk'] ?? '3.00' ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="semester">Semester Saat Ini *</label>
                    <select id="semester" name="semester" class="form-control" required>
                        <option value="">Pilih Semester</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>" <?= (isset($edit_data['semester']) && $edit_data['semester'] == $i) ? 'selected' : '' ?>>
                            Semester <?= $i ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <?= $edit_data ? 'Update Mahasiswa' : 'Tambah Mahasiswa' ?>
                    </button>
                    <?php if ($edit_data): ?>
                    <a href="manage_mahasiswa.php" class="btn" style="background: #6c757d; color: white;">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="content-section active">
            <h2>Daftar Data Mahasiswa</h2>
            <p>Data mahasiswa digunakan untuk simulasi IPK otomatis saat pendaftaran beasiswa.</p>
            
            <?php if (empty($mahasiswa_list)): ?>
            <div class="alert alert-info">
                Belum ada data mahasiswa.
            </div>
            <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>IPK</th>
                            <th>Semester</th>
                            <th>Status IPK</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mahasiswa_list as $mahasiswa): ?>
                        <tr>
                            <td><?= $mahasiswa['id'] ?></td>
                            <td><strong><?= htmlspecialchars($mahasiswa['nim']) ?></strong></td>
                            <td><?= htmlspecialchars($mahasiswa['nama']) ?></td>
                            <td><?= htmlspecialchars($mahasiswa['email']) ?></td>
                            <td>
                                <span class="<?= $mahasiswa['ipk'] >= 3.0 ? 'ipk-high' : 'ipk-low' ?>">
                                    <?= number_format($mahasiswa['ipk'], 2) ?>
                                </span>
                            </td>
                            <td><?= $mahasiswa['semester'] ?></td>
                            <td>
                                <?php if ($mahasiswa['ipk'] >= 3.0): ?>
                                <span class="status-badge status-verified">Memenuhi Syarat</span>
                                <?php else: ?>
                                <span class="status-badge status-rejected">Tidak Memenuhi</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($mahasiswa['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?= $mahasiswa['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $mahasiswa['id'] ?>">
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

        <!-- Info -->
        <div class="content-section active" style="margin-top: 2rem;">
            <h3>Informasi</h3>
            <div class="alert alert-info">
                <strong>Catatan:</strong><br>
                • Data mahasiswa digunakan untuk simulasi IPK otomatis berdasarkan email<br>
                • IPK ≥ 3.0 akan memungkinkan mahasiswa mendaftar beasiswa<br>
                • IPK < 3.0 akan menonaktifkan form pendaftaran beasiswa<br>
                • Jika email tidak ditemukan di data mahasiswa, sistem akan generate IPK random untuk demo
            </div>
        </div>
    </div>
</body>
</html>