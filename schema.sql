DROP TABLE IF EXISTS reservasi;
DROP TABLE IF EXISTS pembatalan;
DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS tipe_kamar;
DROP TABLE IF EXISTS review;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS hotel;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(64) NOT NULL,
    email VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL,
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
