<?php
require_once 'includes/functions.php';

$message = '';
$messageType = '';

// Proses form submission
if ($_POST) {
    $errors = [];
    
    // Validasi input
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $semester = intval($_POST['semester'] ?? 0);
    $ipk = floatval($_POST['ipk'] ?? 0);
    $jenis_beasiswa_id = intval($_POST['jenis_beasiswa_id'] ?? 0);
    
    // Validasi data
    if (empty($nama)) $errors[] = "Nama harus diisi";
    if (empty($email) || !validateEmail($email)) $errors[] = "Email tidak valid";
    if (empty($no_hp) || !preg_match('/^[0-9]{10,}$/', $no_hp)) $errors[] = "Nomor HP tidak valid";
    if ($semester < 1 || $semester > 8) $errors[] = "Semester harus antara 1-8";
    if ($ipk < 3.0) $errors[] = "IPK minimal 3.0 untuk mendaftar beasiswa";
    if ($jenis_beasiswa_id == 0) $errors[] = "Pilih jenis beasiswa";
    
    // Validasi file upload
    $berkas_syarat = '';
    if (isset($_FILES['berkas_syarat']) && $_FILES['berkas_syarat']['error'] == 0) {
        $uploaded_file = uploadFile($_FILES['berkas_syarat']);
        if ($uploaded_file) {
            $berkas_syarat = $uploaded_file;
        } else {
            $errors[] = "Gagal upload berkas syarat";
        }
    } else {
        $errors[] = "Berkas syarat harus diupload";
    }
    
    // Jika tidak ada error, simpan data
    if (empty($errors)) {
        $data = [
            'nama' => $nama,
            'email' => $email,
            'no_hp' => $no_hp,
            'semester' => $semester,
            'ipk' => $ipk,
            'jenis_beasiswa_id' => $jenis_beasiswa_id,
            'berkas_syarat' => $berkas_syarat
        ];
        
        if (savePendaftaran($data)) {
            $message = "Pendaftaran beasiswa berhasil! Status: Belum di verifikasi";
            $messageType = "success";
            
            // Redirect ke halaman hasil setelah 2 detik
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'hasil.php';
                }, 2000);
            </script>";
        } else {
            $message = "Gagal menyimpan pendaftaran. Silakan coba lagi.";
            $messageType = "danger";
        }
    } else {
        $message = implode("<br>", $errors);
        $messageType = "danger";
    }
}

// Ambil semua jenis beasiswa untuk dropdown
$beasiswaList = getAllBeasiswa();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Beasiswa - Sistem Pendaftaran Beasiswa</title>
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
                <li><a href="daftar.php" class="active">Daftar Beasiswa</a></li>
                <li><a href="hasil.php">Hasil Pendaftaran</a></li>
            </ul>
        </div>
    </nav>

    <main class="container">
        <div class="card">
            <h2>Form Pendaftaran Beasiswa</h2>
            
            <!-- Alert Container -->
            <div id="alertContainer">
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <form id="beasiswaForm" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" required 
                           value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor HP *</label>
                    <input type="text" id="no_hp" name="no_hp" required 
                           placeholder="Contoh: 08123456789"
                           value="<?php echo htmlspecialchars($_POST['no_hp'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="semester">Semester Saat Ini *</label>
                    <select id="semester" name="semester" required>
                        <option value="">Pilih Semester</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>" 
                                <?php echo (isset($_POST['semester']) && $_POST['semester'] == $i) ? 'selected' : ''; ?>>
                                Semester <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ipk">IPK Terakhir *</label>
                    <input type="number" id="ipk" name="ipk" required 
                           min="0" max="4" step="0.01" 
                           placeholder="Contoh: 3.50"
                           value="<?php echo htmlspecialchars($_POST['ipk'] ?? ''); ?>">
                    <small>Masukkan IPK dengan format desimal (contoh: 3.50)</small>
                </div>

                <!-- IPK Status Display -->
                <div id="ipkStatus" class="ipk-display" style="display: none;">
                    Status IPK akan muncul di sini
                </div>

                <div class="form-group">
                    <label for="jenis_beasiswa_id">Pilihan Beasiswa *</label>
                    <select id="jenis_beasiswa_id" name="jenis_beasiswa_id" required>
                        <option value="">Pilih Jenis Beasiswa</option>
                        <?php foreach ($beasiswaList as $beasiswa): ?>
                            <option value="<?php echo $beasiswa['id']; ?>"
                                data-min-ipk="<?php echo $beasiswa['syarat_ipk']; ?>"
                                <?php echo (isset($_POST['jenis_beasiswa_id']) && $_POST['jenis_beasiswa_id'] == $beasiswa['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($beasiswa['nama_beasiswa']); ?> 
                                (Min. IPK: <?php echo $beasiswa['syarat_ipk']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="berkas_syarat">Upload Berkas Syarat *</label>
                    <input type="file" id="berkas_syarat" name="berkas_syarat" 
                           accept=".pdf,.jpg,.jpeg,.png,.zip" required>
                    <small>Format yang diizinkan: PDF, JPG, PNG, ZIP (Maksimal 5MB)</small>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary">
                    Daftar Beasiswa
                </button>
            </form>
        </div>

        <div class="card">
            <h2>Informasi Penting</h2>
            <ul>
                <li>Pastikan semua data yang diisi benar dan sesuai</li>
                <li>Masukkan IPK terakhir Anda dengan benar (format: 0.00 - 4.00)</li>
                <li>Sistem akan memvalidasi kesesuaian IPK dengan jenis beasiswa yang dipilih</li>
                <li>Berkas syarat harus dalam format PDF, JPG, PNG, atau ZIP</li>
                <li>Ukuran file maksimal 5MB</li>
                <li>Status pendaftaran awal adalah "Belum di verifikasi"</li>
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