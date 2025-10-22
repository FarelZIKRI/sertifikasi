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
                'nama_beasiswa' => trim($_POST['nama_beasiswa']),
                'deskripsi' => trim($_POST['deskripsi']),
                'syarat_ipk' => (float)$_POST['syarat_ipk'],
                'syarat_lain' => trim($_POST['syarat_lain'])
            ];
            
            if (createBeasiswa($data)) {
                $message = 'Jenis beasiswa berhasil ditambahkan!';
                $message_type = 'success';
            } else {
                $message = 'Gagal menambahkan jenis beasiswa!';
                $message_type = 'error';
            }
            break;
            
        case 'update':
            $id = (int)$_POST['id'];
            $data = [
                'nama_beasiswa' => trim($_POST['nama_beasiswa']),
                'deskripsi' => trim($_POST['deskripsi']),
                'syarat_ipk' => (float)$_POST['syarat_ipk'],
                'syarat_lain' => trim($_POST['syarat_lain'])
            ];
            
            if (updateBeasiswa($id, $data)) {
                $message = 'Jenis beasiswa berhasil diupdate!';
                $message_type = 'success';
            } else {
                $message = 'Gagal mengupdate jenis beasiswa!';
                $message_type = 'error';
            }
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            $result = deleteBeasiswa($id);
            $message = $result['message'];
            $message_type = $result['success'] ? 'success' : 'error';
            break;
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_data = getBeasiswaById((int)$_GET['edit']);
}

// Ambil semua data beasiswa
$beasiswa_list = getAllBeasiswa();

// Pencarian
$search_keyword = $_GET['search'] ?? '';
if ($search_keyword) {
    $beasiswa_list = searchBeasiswa($search_keyword);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jenis Beasiswa</title>
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
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Kelola Jenis Beasiswa</h1>
            <p>Tambah, Edit, dan Hapus Jenis Beasiswa</p>
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
            <h2><?= $edit_data ? 'Edit Jenis Beasiswa' : 'Tambah Jenis Beasiswa Baru' ?></h2>
            
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit_data ? 'update' : 'create' ?>">
                <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="nama_beasiswa">Nama Beasiswa *</label>
                    <input type="text" id="nama_beasiswa" name="nama_beasiswa" class="form-control" required
                           value="<?= htmlspecialchars($edit_data['nama_beasiswa'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi *</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($edit_data['deskripsi'] ?? '') ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="syarat_ipk">Syarat IPK Minimum *</label>
                    <input type="number" id="syarat_ipk" name="syarat_ipk" class="form-control" 
                           min="0" max="4" step="0.01" required
                           value="<?= $edit_data['syarat_ipk'] ?? '3.00' ?>">
                </div>
                
                <div class="form-group">
                    <label for="syarat_lain">Syarat Lainnya *</label>
                    <textarea id="syarat_lain" name="syarat_lain" class="form-control" rows="3" required><?= htmlspecialchars($edit_data['syarat_lain'] ?? '') ?></textarea>
                </div>
                
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <?= $edit_data ? 'Update Beasiswa' : 'Tambah Beasiswa' ?>
                    </button>
                    <?php if ($edit_data): ?>
                    <a href="manage_beasiswa.php" class="btn" style="background: #6c757d; color: white;">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Pencarian -->
        <div class="search-container">
            <form method="GET" style="display: flex; gap: 1rem; align-items: end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label for="search">Cari Beasiswa</label>
                    <input type="text" id="search" name="search" class="form-control" 
                           placeholder="Cari berdasarkan nama atau deskripsi..."
                           value="<?= htmlspecialchars($search_keyword) ?>">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                <?php if ($search_keyword): ?>
                <a href="manage_beasiswa.php" class="btn" style="background: #6c757d; color: white;">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="content-section active">
            <h2>Daftar Jenis Beasiswa</h2>
            
            <?php if (empty($beasiswa_list)): ?>
            <div class="alert alert-info">
                <?= $search_keyword ? 'Tidak ada data yang sesuai dengan pencarian.' : 'Belum ada jenis beasiswa.' ?>
            </div>
            <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Beasiswa</th>
                            <th>Deskripsi</th>
                            <th>Syarat IPK</th>
                            <th>Syarat Lainnya</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($beasiswa_list as $beasiswa): ?>
                        <tr>
                            <td><?= $beasiswa['id'] ?></td>
                            <td><strong><?= htmlspecialchars($beasiswa['nama_beasiswa']) ?></strong></td>
                            <td><?= htmlspecialchars(substr($beasiswa['deskripsi'], 0, 100)) ?>...</td>
                            <td><?= number_format($beasiswa['syarat_ipk'], 2) ?></td>
                            <td><?= htmlspecialchars(substr($beasiswa['syarat_lain'], 0, 80)) ?>...</td>
                            <td><?= date('d/m/Y', strtotime($beasiswa['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?= $beasiswa['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Yakin ingin menghapus beasiswa ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $beasiswa['id'] ?>">
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