<?php
require_once 'config/database.php';

// Fungsi untuk mendapatkan IPK secara otomatis (simulasi)
function getIPK() {
    // Simulasi IPK random antara 2.5 - 4.0
    $ipk_options = [2.9, 3.1, 3.4, 3.7, 2.8, 3.5, 3.9, 2.6, 3.2, 3.8];
    return $ipk_options[array_rand($ipk_options)];
}

// Fungsi untuk mendapatkan semua jenis beasiswa
function getAllBeasiswa() {
    $pdo = getConnection();
    $stmt = $pdo->query("SELECT * FROM jenis_beasiswa ORDER BY nama_beasiswa");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan beasiswa berdasarkan ID
function getBeasiswaById($id) {
    $pdo = getConnection();
    $stmt = $pdo->prepare("SELECT * FROM jenis_beasiswa WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk menyimpan pendaftaran beasiswa
function savePendaftaran($data) {
    $pdo = getConnection();
    $sql = "INSERT INTO pendaftaran_beasiswa (nama, email, no_hp, semester, ipk, jenis_beasiswa_id, berkas_syarat) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['nama'],
        $data['email'],
        $data['no_hp'],
        $data['semester'],
        $data['ipk'],
        $data['jenis_beasiswa_id'],
        $data['berkas_syarat']
    ]);
}

// Fungsi untuk mendapatkan semua pendaftaran
function getAllPendaftaran() {
    $pdo = getConnection();
    $sql = "SELECT p.*, j.nama_beasiswa 
            FROM pendaftaran_beasiswa p 
            JOIN jenis_beasiswa j ON p.jenis_beasiswa_id = j.id 
            ORDER BY p.created_at DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk validasi email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fungsi untuk upload file
function uploadFile($file) {
    $target_dir = "uploads/";
    
    // Buat folder uploads jika belum ada
    if (!file_exists($target_dir)) {
        if (!mkdir($target_dir, 0755, true)) {
            error_log("Gagal membuat folder uploads");
            return false;
        }
    }
    
    // Validasi file
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        error_log("File tidak valid atau tidak ter-upload");
        return false;
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = array("pdf", "jpg", "jpeg", "png", "zip");
    
    if (!in_array($file_extension, $allowed_extensions)) {
        error_log("Ekstensi file tidak diizinkan: " . $file_extension);
        return false;
    }
    
    // Buat nama file yang aman
    $safe_filename = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file["name"]);
    $target_file = $target_dir . $safe_filename;
    
    // Cek ukuran file (max 5MB)
    if ($file["size"] > 5 * 1024 * 1024) {
        error_log("File terlalu besar: " . $file["size"] . " bytes");
        return false;
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $safe_filename;
    } else {
        error_log("Gagal move file dari " . $file["tmp_name"] . " ke " . $target_file);
        return false;
    }
}
?>