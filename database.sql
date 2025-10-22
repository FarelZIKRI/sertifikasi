-- Database: sistem_beasiswa
-- Buat database
CREATE DATABASE sistem_beasiswa;
USE sistem_beasiswa;

-- Tabel jenis beasiswa
CREATE TABLE jenis_beasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_beasiswa VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    syarat_ipk DECIMAL(3,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert data jenis beasiswa
INSERT INTO jenis_beasiswa (nama_beasiswa, deskripsi, syarat_ipk) VALUES
('Beasiswa Akademik', 'Beasiswa untuk mahasiswa berprestasi akademik dengan IPK minimal 3.5', 3.50),
('Beasiswa Non-Akademik', 'Beasiswa untuk mahasiswa berprestasi non-akademik dengan IPK minimal 3.0', 3.00),
('Beasiswa Prestasi', 'Beasiswa untuk mahasiswa dengan prestasi khusus dengan IPK minimal 3.25', 3.25);

-- Tabel pendaftaran beasiswa
CREATE TABLE pendaftaran_beasiswa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    semester INT NOT NULL,
    ipk DECIMAL(3,2) NOT NULL,
    jenis_beasiswa_id INT NOT NULL,
    berkas_syarat VARCHAR(255),
    status_ajuan VARCHAR(50) DEFAULT 'belum di verifikasi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jenis_beasiswa_id) REFERENCES jenis_beasiswa(id)
);