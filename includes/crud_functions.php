<?php
require_once '../config/database.php';

// ==================== CRUD JENIS BEASISWA ====================

// CREATE - Tambah jenis beasiswa baru
function createBeasiswa($data) {
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO jenis_beasiswa (nama_beasiswa, deskripsi, syarat_ipk, syarat_lain) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nama_beasiswa'],
            $data['deskripsi'],
            $data['syarat_ipk'],
            $data['syarat_lain']
        ]);
    } catch (Exception $e) {
        return false;
    }
}

// READ - Ambil satu jenis beasiswa berdasarkan ID
function getBeasiswaById($id) {
    try {
        $pdo = getConnection();
        $sql = "SELECT * FROM jenis_beasiswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return false;
    }
}

// UPDATE - Update jenis beasiswa
function updateBeasiswa($id, $data) {
    try {
        $pdo = getConnection();
        $sql = "UPDATE jenis_beasiswa SET nama_beasiswa = ?, deskripsi = ?, syarat_ipk = ?, syarat_lain = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nama_beasiswa'],
            $data['deskripsi'],
            $data['syarat_ipk'],
            $data['syarat_lain'],
            $id
        ]);
    } catch (Exception $e) {
        return false;
    }
}

// DELETE - Hapus jenis beasiswa
function deleteBeasiswa($id) {
    try {
        $pdo = getConnection();
        
        // Cek apakah ada pendaftaran yang menggunakan beasiswa ini
        $checkSql = "SELECT COUNT(*) FROM pendaftaran_beasiswa WHERE jenis_beasiswa_id = ?";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$id]);
        $count = $checkStmt->fetchColumn();
        
        if ($count > 0) {
            return ['success' => false, 'message' => 'Tidak dapat menghapus. Masih ada pendaftaran yang menggunakan beasiswa ini.'];
        }
        
        $sql = "DELETE FROM jenis_beasiswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$id]);
        
        return ['success' => $result, 'message' => $result ? 'Beasiswa berhasil dihapus.' : 'Gagal menghapus beasiswa.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

// ==================== CRUD PENDAFTARAN BEASISWA ====================

// READ - Ambil satu pendaftaran berdasarkan ID
function getPendaftaranById($id) {
    try {
        $pdo = getConnection();
        $sql = "SELECT p.*, j.nama_beasiswa 
                FROM pendaftaran_beasiswa p 
                LEFT JOIN jenis_beasiswa j ON p.jenis_beasiswa_id = j.id 
                WHERE p.id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return false;
    }
}

// UPDATE - Update pendaftaran beasiswa
function updatePendaftaran($id, $data) {
    try {
        $pdo = getConnection();
        $sql = "UPDATE pendaftaran_beasiswa SET nama = ?, email = ?, no_hp = ?, semester = ?, jenis_beasiswa_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nama'],
            $data['email'],
            $data['no_hp'],
            $data['semester'],
            $data['jenis_beasiswa_id'],
            $id
        ]);
    } catch (Exception $e) {
        return false;
    }
}

// UPDATE - Update status pendaftaran
function updateStatusPendaftaran($id, $status) {
    try {
        $pdo = getConnection();
        $sql = "UPDATE pendaftaran_beasiswa SET status_ajuan = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$status, $id]);
    } catch (Exception $e) {
        return false;
    }
}

// DELETE - Hapus pendaftaran beasiswa
function deletePendaftaran($id) {
    try {
        $pdo = getConnection();
        
        // Ambil data pendaftaran untuk hapus file
        $pendaftaran = getPendaftaranById($id);
        if ($pendaftaran && $pendaftaran['berkas_syarat']) {
            $file_path = '../uploads/' . $pendaftaran['berkas_syarat'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        $sql = "DELETE FROM pendaftaran_beasiswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    } catch (Exception $e) {
        return false;
    }
}

// ==================== CRUD MAHASISWA ====================

// CREATE - Tambah data mahasiswa
function createMahasiswa($data) {
    try {
        $pdo = getConnection();
        $sql = "INSERT INTO mahasiswa (nim, nama, email, ipk, semester) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nim'],
            $data['nama'],
            $data['email'],
            $data['ipk'],
            $data['semester']
        ]);
    } catch (Exception $e) {
        return false;
    }
}

// READ - Ambil semua data mahasiswa
function getAllMahasiswa() {
    try {
        $pdo = getConnection();
        $stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY nama");
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// READ - Ambil satu mahasiswa berdasarkan ID
function getMahasiswaById($id) {
    try {
        $pdo = getConnection();
        $sql = "SELECT * FROM mahasiswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return false;
    }
}

// READ - Ambil mahasiswa berdasarkan email
function getMahasiswaByEmail($email) {
    try {
        $pdo = getConnection();
        $sql = "SELECT * FROM mahasiswa WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return false;
    }
}

// UPDATE - Update data mahasiswa
function updateMahasiswa($id, $data) {
    try {
        $pdo = getConnection();
        $sql = "UPDATE mahasiswa SET nim = ?, nama = ?, email = ?, ipk = ?, semester = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['nim'],
            $data['nama'],
            $data['email'],
            $data['ipk'],
            $data['semester'],
            $id
        ]);
    } catch (Exception $e) {
        return false;
    }
}

// DELETE - Hapus data mahasiswa
function deleteMahasiswa($id) {
    try {
        $pdo = getConnection();
        $sql = "DELETE FROM mahasiswa WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    } catch (Exception $e) {
        return false;
    }
}

// ==================== FUNGSI PENCARIAN ====================

// Pencarian pendaftaran berdasarkan nama, email, atau status
function searchPendaftaran($keyword, $status = '') {
    try {
        $pdo = getConnection();
        $sql = "SELECT p.*, j.nama_beasiswa 
                FROM pendaftaran_beasiswa p 
                LEFT JOIN jenis_beasiswa j ON p.jenis_beasiswa_id = j.id 
                WHERE (p.nama LIKE ? OR p.email LIKE ?)";
        
        $params = ["%$keyword%", "%$keyword%"];
        
        if ($status !== '') {
            $sql .= " AND p.status_ajuan = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Pencarian beasiswa berdasarkan nama
function searchBeasiswa($keyword) {
    try {
        $pdo = getConnection();
        $sql = "SELECT * FROM jenis_beasiswa WHERE nama_beasiswa LIKE ? OR deskripsi LIKE ? ORDER BY nama_beasiswa";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$keyword%", "%$keyword%"]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// ==================== STATISTIK ====================

// Statistik pendaftaran
function getStatistikPendaftaran() {
    try {
        $pdo = getConnection();
        
        $stats = [];
        
        // Total pendaftaran
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM pendaftaran_beasiswa");
        $stats['total'] = $stmt->fetchColumn();
        
        // Pendaftaran berdasarkan status
        $stmt = $pdo->query("SELECT status_ajuan, COUNT(*) as jumlah FROM pendaftaran_beasiswa GROUP BY status_ajuan");
        $statusData = $stmt->fetchAll();
        foreach ($statusData as $data) {
            $stats['status'][$data['status_ajuan']] = $data['jumlah'];
        }
        
        // Pendaftaran berdasarkan jenis beasiswa
        $stmt = $pdo->query("SELECT j.nama_beasiswa, COUNT(*) as jumlah 
                            FROM pendaftaran_beasiswa p 
                            JOIN jenis_beasiswa j ON p.jenis_beasiswa_id = j.id 
                            GROUP BY j.nama_beasiswa");
        $beasiswaData = $stmt->fetchAll();
        foreach ($beasiswaData as $data) {
            $stats['beasiswa'][$data['nama_beasiswa']] = $data['jumlah'];
        }
        
        return $stats;
    } catch (Exception $e) {
        return [];
    }
}
?>