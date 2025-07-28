<?php
require_once '../config/database.php';

// Fungsi untuk mendapatkan IPK berdasarkan email (simulasi)
function getIPKByEmail($email) {
    // Simulasi IPK otomatis - dalam praktik nyata bisa dari database mahasiswa
    $ipk_simulation = [
        'john@email.com' => 3.40,
        'jane@email.com' => 2.90,
        'bob@email.com' => 3.75
    ];
    
    // Jika email tidak ditemukan, gunakan IPK random untuk demo
    if (isset($ipk_simulation[$email])) {
        return $ipk_simulation[$email];
    } else {
        // Generate IPK random antara 2.0 - 4.0 untuk demo
        return round(rand(200, 400) / 100, 2);
    }
}

// Fungsi untuk mendapatkan semua jenis beasiswa
function getAllBeasiswa() {
    try {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM jenis_beasiswa ORDER BY nama_beasiswa");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Fungsi untuk menyimpan pendaftaran beasiswa
function savePendaftaran($data) {
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO pendaftaran_beasiswa (nama, email, no_hp, semester, ipk, jenis_beasiswa_id, berkas_syarat, status_ajuan) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'belum di verifikasi')";
        
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
    } catch (Exception $e) {
        return false;
    }
}

// Fungsi untuk mendapatkan semua hasil pendaftaran
function getAllPendaftaran() {
    try {
        $pdo = getConnection();
        $sql = "SELECT p.*, j.nama_beasiswa 
                FROM pendaftaran_beasiswa p 
                LEFT JOIN jenis_beasiswa j ON p.jenis_beasiswa_id = j.id 
                ORDER BY p.created_at DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Fungsi untuk upload file
function uploadFile($file) {
    $target_dir = "../uploads/";
    
    // Buat folder uploads jika belum ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = array("pdf", "jpg", "jpeg", "png", "zip", "doc", "docx");
    
    if (!in_array($file_extension, $allowed_extensions)) {
        return false;
    }
    
    $new_filename = time() . "_" . $file["name"];
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $new_filename;
    } else {
        return false;
    }
}

// Fungsi untuk validasi email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Fungsi untuk validasi nomor HP
function validatePhone($phone) {
    return preg_match('/^[0-9]+$/', $phone);
}
?>