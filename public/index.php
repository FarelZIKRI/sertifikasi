<?php
require_once '../includes/functions.php';

// Ambil data beasiswa
$beasiswa_list = getAllBeasiswa();

// Proses form submission
$message = '';
$message_type = '';

if ($_POST) {
    if (isset($_POST['action']) && $_POST['action'] === 'daftar') {
        // Validasi server-side
        $errors = [];
        
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $semester = (int)($_POST['semester'] ?? 0);
        $jenis_beasiswa_id = (int)($_POST['jenis_beasiswa_id'] ?? 0);
        
        // Validasi input
        if (empty($nama)) $errors[] = 'Nama harus diisi';
        if (!validateEmail($email)) $errors[] = 'Format email tidak valid';
        if (!validatePhone($no_hp)) $errors[] = 'Nomor HP hanya boleh berisi angka';
        if ($semester < 1 || $semester > 8) $errors[] = 'Semester harus antara 1-8';
        if ($jenis_beasiswa_id === 0) $errors[] = 'Pilih jenis beasiswa';
        
        // Get IPK
        $ipk = getIPKByEmail($email);
        if ($ipk < 3.0) $errors[] = 'IPK tidak memenuhi syarat minimum (3.0)';
        
        // Upload file
        $berkas_filename = '';
        if (isset($_FILES['berkas_syarat']) && $_FILES['berkas_syarat']['error'] === 0) {
            $berkas_filename = uploadFile($_FILES['berkas_syarat']);
            if (!$berkas_filename) {
                $errors[] = 'Gagal upload berkas atau format tidak didukung';
            }
        } else {
            $errors[] = 'Berkas syarat harus diupload';
        }
        
        // Simpan jika tidak ada error
        if (empty($errors)) {
            $data = [
                'nama' => $nama,
                'email' => $email,
                'no_hp' => $no_hp,
                'semester' => $semester,
                'ipk' => $ipk,
                'jenis_beasiswa_id' => $jenis_beasiswa_id,
                'berkas_syarat' => $berkas_filename
            ];
            
            if (savePendaftaran($data)) {
                $message = 'Pendaftaran beasiswa berhasil! Status: Belum di verifikasi';
                $message_type = 'success';
            } else {
                $message = 'Gagal menyimpan pendaftaran. Silakan coba lagi.';
                $message_type = 'error';
            }
        } else {
            $message = implode('<br>', $errors);
            $message_type = 'error';
        }
    }
}

// Ambil data hasil pendaftaran
$hasil_pendaftaran = getAllPendaftaran();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pendaftaran Beasiswa Online</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1>Sistem Pendaftaran Beasiswa</h1>
            <p>Portal Online Pendaftaran Beasiswa Kampus</p>
        </div>
    </header>

    <div class="container">
        <!-- Navigation Tabs -->
        <div class="nav-tabs">
            <button class="nav-tab active" data-target="jenis-beasiswa">Jenis Beasiswa</button>
            <button class="nav-tab" data-target="daftar-beasiswa">Daftar Beasiswa</button>
            <button class="nav-tab" data-target="hasil-beasiswa">Hasil Beasiswa</button>
        </div>

        <!-- Jenis Beasiswa Section -->
        <div id="jenis-beasiswa" class="content-section active">
            <h2>Jenis Beasiswa dan Ketentuan</h2>
            <p>Berikut adalah jenis-jenis beasiswa yang tersedia beserta syarat dan ketentuannya:</p>
            
            <div class="beasiswa-grid">
                <?php foreach ($beasiswa_list as $beasiswa): ?>
                <div class="beasiswa-card">
                    <h3><?= htmlspecialchars($beasiswa['nama_beasiswa']) ?></h3>
                    <p><?= htmlspecialchars($beasiswa['deskripsi']) ?></p>
                    <div class="syarat">
                        <strong>Syarat Minimum:</strong><br>
                        • IPK minimal: <?= $beasiswa['syarat_ipk'] ?><br>
                        • <?= htmlspecialchars($beasiswa['syarat_lain']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Daftar Beasiswa Section -->
        <div id="daftar-beasiswa" class="content-section">
            <h2>Form Pendaftaran Beasiswa</h2>
            
            <?php if ($message): ?>
            <div class="alert alert-<?= $message_type ?>">
                <?= $message ?>
            </div>
            <?php endif; ?>
            
            <form id="form-beasiswa" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="daftar">
                
                <div class="form-group">
                    <label for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" class="form-control" required 
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="Masukkan email untuk cek IPK otomatis">
                </div>
                
                <!-- IPK Display -->
                <div id="ipk-display" class="ipk-display" style="display: none;">
                    <div class="ipk-value" id="ipk-value">0.00</div>
                    <div class="ipk-status" id="ipk-status">IPK Anda</div>
                </div>
                
                <div class="form-group">
                    <label for="no_hp">Nomor HP *</label>
                    <input type="tel" id="no_hp" name="no_hp" class="form-control" required 
                           pattern="[0-9]+" title="Hanya angka yang diperbolehkan"
                           value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="semester">Semester Saat Ini *</label>
                    <select id="semester" name="semester" class="form-control" required>
                        <option value="">Pilih Semester</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>" <?= (isset($_POST['semester']) && $_POST['semester'] == $i) ? 'selected' : '' ?>>
                            Semester <?= $i ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="jenis_beasiswa_id">Pilihan Beasiswa *</label>
                    <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" class="form-control" required disabled>
                        <option value="">Pilih Jenis Beasiswa</option>
                        <?php foreach ($beasiswa_list as $beasiswa): ?>
                        <option value="<?= $beasiswa['id'] ?>" 
                                <?= (isset($_POST['jenis_beasiswa_id']) && $_POST['jenis_beasiswa_id'] == $beasiswa['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($beasiswa['nama_beasiswa']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="berkas_syarat">Upload Berkas Syarat *</label>
                    <input type="file" id="berkas_syarat" name="berkas_syarat" class="form-control" 
                           accept=".pdf,.jpg,.jpeg,.png,.zip,.doc,.docx" required disabled>
                    <small style="color: #666; font-size: 0.9rem;">
                        Format yang didukung: PDF, JPG, PNG, ZIP, DOC, DOCX (Max: 5MB)
                    </small>
                </div>
                
                <button type="submit" id="submit-btn" class="btn btn-primary" disabled>
                    Daftar Beasiswa
                </button>
            </form>
        </div>

        <!-- Hasil Beasiswa Section -->
        <div id="hasil-beasiswa" class="content-section">
            <h2>Hasil Pendaftaran Beasiswa</h2>
            <p>Daftar semua pendaftaran beasiswa yang telah disubmit:</p>
            
            <?php if (empty($hasil_pendaftaran)): ?>
            <div class="alert alert-info">
                Belum ada pendaftaran beasiswa.
            </div>
            <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
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
                        <?php foreach ($hasil_pendaftaran as $hasil): ?>
                        <tr>
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
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>