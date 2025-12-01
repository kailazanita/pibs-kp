-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS pibs_kelompok;
USE pibs_kelompok;

-- Create mahasiswa table
CREATE TABLE IF NOT EXISTS mahasiswa (
    nim VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data for testing
INSERT INTO mahasiswa (nim, nama, prodi) VALUES 
('2021001', 'Ahmad Rizki', 'Teknik Informatika'),
('2021002', 'Siti Nurhaliza', 'Sistem Informasi'),
('2021003', 'Budi Santoso', 'Manajemen'),
('2021004', 'Maya Sari', 'Akuntansi'),
('2021005', 'Rendi Pratama', 'Desain Komunikasi Visual')
ON DUPLICATE KEY UPDATE nim=nim;