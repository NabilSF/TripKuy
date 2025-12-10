DROP TABLE IF EXISTS reservasi;
DROP TABLE IF EXISTS pembatalan;
DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS tipe_kamar;
DROP TABLE IF EXISTS review;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS hotel;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(64) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'admin'),
    no_telepon VARCHAR(16)
);

CREATE TABLE hotel (
    id_hotel INT AUTO_INCREMENT PRIMARY KEY,
    nama_hotel VARCHAR(255) UNIQUE,
    email VARCHAR(64) UNIQUE,
    alamat VARCHAR(255),
    kontak VARCHAR(64),
    deskripsi VARCHAR(255)
);

CREATE TABLE tipe_kamar (
    id_kamar INT AUTO_INCREMENT PRIMARY KEY,
    id_hotel INT REFERENCES hotel(id_hotel),
    nama_kamar VARCHAR(64),
    deskripsi VARCHAR(255),
    kapasitas_orang INT(2),
    total_kamar INT(3),
    harga INT
);

CREATE TABLE review (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT REFERENCES users(id_user),
    gambar VARCHAR(255),
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    deskripsi VARCHAR(255)
);


CREATE TABLE pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    total_harga INT,
    tipe_pembayaran VARCHAR(255)
);

CREATE TABLE pembatalan (
    id_pembatalan INT AUTO_INCREMENT PRIMARY KEY,
    alasan VARCHAR(255),
    tanggal_pengajuan DATE,
    catatan_admin VARCHAR(255),
    tanggal_refund DATE
);


CREATE TABLE reservasi (
    id_reservasi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT REFERENCES users(id_user),
    id_kamar INT REFERENCES tipe_kamar(id_kamar),
    id_pembayaran INT REFERENCES pembayaran(id_pembayaran),
    id_pembatalan INT REFERENCES pembatalan(id_pembatalan),
    tanggal_reservasi DATE DEFAULT NOW(),
    tanggal_check_in DATE,
    tanggal_check_out DATE,
    jumlah_kamar INT,
    total_malam INT
);

INSERT INTO users (email, password, role, no_telepon) VALUES
('admin@example.com', '$2y$10$fW9N/yO.W/K3hG2.p5Ue.eA0zC7u4oR1iMv2tXqY3sZ4aB5c6d7e', 'admin', '081234567890'),
('budi@mail.com', '$2y$10$hL5Z/tP.X/J4iH3.q6Vf.fB1aD8v5pS2jN3wXyZ4bC5d7e8f9g0h', 'user', '081345678901'),
('cindy@mail.com', '$2y$10$jO2X/uQ.Y/L5jI4.r7Wg.gC2bD9w6qT3kN4xYzA5cC6d8e9f0g1i', 'user', '081456789012'),
('dewi@mail.com', '$2y$10$kR7A/vR.Z/M6kJ5.s8Xh.hD3cE0x7rU4lO5zB6aD7e9f0g1h2j', 'user', '081567890123'),
('eko@mail.com', '$2y$10$mL1T/wS.A/N7lK6.t9Yi.iD4dF1y8sV5mM6zC7bE8f9g0h1i3k', 'user', '081678901234'),
('fajar@mail.com', '$2y$10$nW6Q/xT.B/O8mP7.u0Zj.jE5eG2z9tW6nN7aD8cE9f0g1h2j4l', 'user', '081789012345'),
('gita@mail.com', '$2y$10$pY0U/yU.C/P9nQ8.v1Ak.kF6fH3a0uX7oO8bD9dF0g1h2j4l5m', 'user', '081890123456'),
('heri@mail.com', '$2y$10$qZ4V/zV.D/Q0oR9.w2Bl.lG7gI4b1vY8pP9cE0fG1h2j4l5m6n', 'user', '081901234567'),
('indah@mail.com', '$2y$10$rA8W/A W.E/R1pS0.x3Cm.mH8hJ5c2wZ9qQ0dF1gH2j4l5m6n7p', 'user', '081123456789'),
('joko@mail.com', '$2y$10$sB2X/B X.F/S2qT1.y4Dn.nI9iK6d3xR0rR1eG2hJ4l5m6n7p8q', 'user', '081223456789');