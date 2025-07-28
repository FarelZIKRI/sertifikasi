-- Database: beasiswa_db
-- Sistem Pendaftaran Beasiswa Online

CREATE DATABASE IF NOT EXISTS beasiswa_db;
USE beasiswa_db;

-- Tabel jenis beasiswa
CREATE TABLE jenis_beasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_beasiswa VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    syarat_ipk DECIMAL(3,2) DEFAULT 3.00,
    syarat_lain TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel mahasiswa (simulasi data mahasiswa)
CREATE TABLE mahasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    ipk DECIMAL(3,2) NOT NULL,
    semester INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel pendaftaran beasiswa
CREATE TABLE pendaftaran_beasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    semester INT NOT NULL,
    ipk DECIMAL(3,2) NOT NULL,
    jenis_beasiswa_id INT,
    berkas_syarat VARCHAR(255),
    status_ajuan ENUM('belum di verifikasi', 'diverifikasi', 'ditolak') DEFAULT 'belum di verifikasi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jenis_beasiswa_id) REFERENCES jenis_beasiswa(id)
);

-- Insert data jenis beasiswa
INSERT INTO jenis_beasiswa (nama_beasiswa, deskripsi, syarat_ipk, syarat_lain) VALUES
('Beasiswa Akademik', 'Beasiswa untuk mahasiswa berprestasi akademik tinggi', 3.50, 'Aktif dalam kegiatan akademik, tidak sedang menerima beasiswa lain'),
('Beasiswa Non-Akademik', 'Beasiswa untuk mahasiswa berprestasi non-akademik', 3.00, 'Memiliki prestasi di bidang olahraga, seni, atau organisasi'),
('Beasiswa Kurang Mampu', 'Beasiswa untuk mahasiswa dari keluarga kurang mampu', 3.00, 'Melampirkan surat keterangan tidak mampu dari kelurahan');

-- Insert sample data mahasiswa (untuk simulasi IPK otomatis)
INSERT INTO mahasiswa (nim, nama, email, ipk, semester) VALUES
('12345678', 'John Doe', 'john@email.com', 3.40, 5),
('87654321', 'Jane Smith', 'jane@email.com', 2.90, 4),
('11223344', 'Bob Wilson', 'bob@email.com', 3.75, 6);