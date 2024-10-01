-- Buat database baru
CREATE DATABASE logistics_db;
USE logistics_db;

-- Buat tabel admins
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Buat tabel resi dengan field status
CREATE TABLE resi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_resi VARCHAR(255) NOT NULL,
    nama_pengirim VARCHAR(255) NOT NULL,
    contact_pengirim VARCHAR(100) NOT NULL,
    nama_penerima VARCHAR(255) NOT NULL,
    contact_penerima VARCHAR(100) NOT NULL,
    tanggal_pengiriman VARCHAR(50) NOT NULL,
    status ENUM('terdaftar', 'dalam_perjalanan', 'diterima', 'diambil') DEFAULT 'terdaftar',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Buat tabel activity_log
CREATE TABLE activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resi_id INT,
    activity ENUM('Inserted', 'Updated', 'Deleted', 'Status Changed'),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    no_resi VARCHAR(255),
    nama_pengirim VARCHAR(255),
    contact_pengirim VARCHAR(100),
    nama_penerima VARCHAR(255),
    contact_penerima VARCHAR(100),
    tanggal_pengiriman VARCHAR(50),
    status ENUM('terdaftar', 'dalam_perjalanan', 'diterima', 'diambil') default 'terdaftar',
    FOREIGN KEY (resi_id) REFERENCES resi(id)
);

-- Isi tabel resi dengan 10 data baru
INSERT INTO resi (no_resi, nama_pengirim, contact_pengirim, nama_penerima, contact_penerima, tanggal_pengiriman) VALUES
('001', 'Situmorang', 'situmorang@example.com', 'Tambunan', 'tambunan@example.com', '2024-06-25'),
('002', 'Simanjuntak', 'simanjuntak@example.com', 'Siregar', 'siregar@example.com', '2024-06-25'),
('003', 'Tambunan', 'tambunan@example.com', 'Situmorang', 'situmorang@example.com', '2024-06-25'),
('004', 'Siregar', 'siregar@example.com', 'Simanjuntak', 'simanjuntak@example.com', '2024-06-25'),
('005', 'Napitu', 'napitu@example.com', 'Siahaan', 'siahaan@example.com', '2024-06-25'),
('006', 'Siahaan', 'siahaan@example.com', 'Napitu', 'napitu@example.com', '2024-06-25'),
('007', 'Pangaribuan', 'pangaribuan@example.com', 'Silitonga', 'silitonga@example.com', '2024-06-25'),
('008', 'Silitonga', 'silitonga@example.com', 'Pangaribuan', 'pangaribuan@example.com', '2024-06-25'),
('009', 'Simatupang', 'simatupang@example.com', 'Sitompul', 'sitompul@example.com', '2024-06-25'),
('010', 'Sitompul', 'sitompul@example.com', 'Simatupang', 'simatupang@example.com', '2024-06-25');

-- Membuat trigger untuk sinkronisasi otomatis
DELIMITER //

CREATE TRIGGER after_resi_insert
AFTER INSERT ON resi
FOR EACH ROW
BEGIN
    INSERT INTO activity_log (resi_id, activity, timestamp, no_resi, nama_pengirim, contact_pengirim, nama_penerima, contact_penerima, tanggal_pengiriman, status)
    VALUES (NEW.id, 'Inserted', NOW(), NEW.no_resi, NEW.nama_pengirim, NEW.contact_pengirim, NEW.nama_penerima, NEW.contact_penerima, NEW.tanggal_pengiriman, NEW.status);
END //

CREATE TRIGGER after_resi_update
AFTER UPDATE ON resi
FOR EACH ROW
BEGIN
    INSERT INTO activity_log (resi_id, activity, timestamp, no_resi, nama_pengirim, contact_pengirim, nama_penerima, contact_penerima, tanggal_pengiriman, status)
    VALUES (NEW.id, 'Updated', NOW(), NEW.no_resi, NEW.nama_pengirim, NEW.contact_pengirim, NEW.nama_penerima, NEW.contact_penerima, NEW.tanggal_pengiriman, NEW.status);
END //

CREATE TRIGGER after_resi_delete
AFTER DELETE ON resi
FOR EACH ROW
BEGIN
    INSERT INTO activity_log (resi_id, activity, timestamp, no_resi, nama_pengirim, contact_pengirim, nama_penerima, contact_penerima, tanggal_pengiriman, status)
    VALUES (OLD.id, 'Deleted', NOW(), OLD.no_resi, OLD.nama_pengirim, OLD.contact_pengirim, OLD.nama_penerima, OLD.contact_penerima, OLD.tanggal_pengiriman, 'terdaftar');
END //

DELIMITER ;


drop database logistics_db;