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

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `no_telepon`) VALUES
(1, 'Edward Newgate', 'edward@gmail.ac.id', 'hash_edward_123', 'admin', '081122334455'),
(2, 'Kira Yamato', 'kira.yamato@gmail.com', 'hash_kira_001', 'user', '085700011122'),
(3, 'Banager Links', 'banager@gmail.com', 'hash_banager_002', 'user', '081299988877'),
(4, 'Andi Wijaya', 'andi.wijaya@gmail.com', 'hash_andi_003', 'user', '081344455566'),
(5, 'Dewi Lestari', 'dewi.lestari@gmail.com', 'hash_dewi_004', 'user', '081911223344');

CREATE TABLE hotel (
    id_hotel INT AUTO_INCREMENT PRIMARY KEY,
    nama_hotel VARCHAR(255) UNIQUE,
    email VARCHAR(64) UNIQUE,
    alamat VARCHAR(255),
    kontak VARCHAR(64),
    deskripsi VARCHAR(255)
);

INSERT INTO `hotel` (`id_hotel`, `nama_hotel`, `email`, `alamat`, `kontak`, `deskripsi`) VALUES
(1, 'Hotel Indonesia Kempinski', 'info.jakarta@kempinski.com', 'Jl. MH Thamrin No. 1, Menteng, Jakarta Pusat', '(021) 23583800', 'Hotel bintang 5 bersejarah pertama di Indonesia dengan fasilitas mewah di pusat ibu kota.'),
(2, 'The Gaia Hotel Bandung', 'reservation@thegaiabandung.com', 'Jl. Dr. Setiabudi No. 430, Ledeng, Bandung', '(022) 20280780', 'Resor kontemporer yang menawarkan konsep Active/Rest dengan pemandangan pegunungan Bandung.'),
(3, 'Hotel Tentrem Yogyakarta', 'info.jogja@hoteltentrem.com', 'Jl. AM. Sangaji No. 72-74, Yogyakarta', '(0274) 6415555', 'Hotel mewah yang menggabungkan keramahan tradisional Jawa dengan fasilitas modern.'),
(4, 'Pullman Lombok Merujani', 'all_reservation@pullman-lombok.com', 'ITDC Mandalika Tourism Complex, Kuta, Lombok Tengah', '(0370) 7525100', 'Resor tepi pantai bintang 5 yang eksklusif di dekat Sirkuit Internasional Mandalika.'),
(5, 'CLARO Makassar', 'info@claromakassar.com', 'Jl. A. P. Pettarani No. 03, Makassar', '(0411) 833888', 'Hotel bisnis terbesar di Makassar dengan fasilitas konvensi yang sangat lengkap.'),
(6, 'Padma Hotel Semarang', 'reservation.semarang@padmahotels.com', 'Jl. Sultan Agung No. 86, Semarang', '(024) 33000900', 'Hotel resor di perbukitan Semarang dengan desain arsitektur yang ikonik dan elegan.'),
(7, 'Conrad Bali', 'conrad_bali@hilton.com', 'Jl. Pratama No. 168, Tanjung Benoa, Bali', '(0361) 778788', 'Resor mewah di tepi pantai Bali yang terkenal dengan kolam renang laguna yang luas.'),
(8, 'InterContinental Jakarta Pondok Indah', 'reservation.jktpi@ihg.com', 'Jl. Metro Pondok Indah, Jakarta Selatan', '(021) 39507355', 'Hotel bintang 5 premium yang terintegrasi langsung dengan Pondok Indah Mall.'),
(9, 'Grand Hyatt Jakarta', 'jakarta.grand@hyatt.com', 'Jl. M.H. Thamrin Kav. 28-30, Jakarta Pusat', '(021) 29921234', 'Hotel bisnis ikonik yang terletak di Bundaran HI dengan akses langsung ke Plaza Indonesia.'),
(10, 'Shangri-La Jakarta', 'slj@shangri-la.com', 'Kota BNI, Jl. Jend. Sudirman Kav. 1, Jakarta', '(021) 29229999', 'Hotel bintang 5 klasik yang menawarkan ketenangan di tengah distrik bisnis Jakarta.'),
(11, 'JW Marriott Hotel Jakarta', 'res.jkt@marriott.com', 'Jl. Lingkar Mega Kuningan Kav. E 1.2, Jakarta', '(021) 57988888', 'Terletak di kawasan bisnis Mega Kuningan, menawarkan kemewahan bagi pelancong bisnis.'),
(12, 'Mulia Senayan Hotel', 'info@hotelmulia.com', 'Jl. Asia Afrika, Senayan, Jakarta Pusat', '(021) 5747777', 'Hotel mewah yang menghadap langsung ke Lapangan Golf Senayan dengan layanan kelas dunia.'),
(13, 'The Westin Jakarta', 'westin.jakarta@westin.com', 'Jl. HR Rasuna Said Kav. C-22, Jakarta Selatan', '(021) 27887788', 'Hotel tertinggi di Indonesia yang menawarkan pemandangan panorama kota Jakarta dari ketinggian.'),
(14, 'Alila Villas Uluwatu', 'uluwatu@alilahotels.com', 'Jl. Belimbing Sari, Tambiyak, Pecatu, Bali', '(0361) 8482166', 'Resor ramah lingkungan dengan desain arsitektur unik yang menggantung di atas tebing.'),
(15, 'Bulgari Resort Bali', 'infobali@bulgarihotels.com', 'Jl. Goa Lempeh, Banjar Dinas Kangin, Uluwatu', '(0361) 8471000', 'Perpaduan gaya tradisional Bali dengan desain Italia modern yang sangat eksklusif.'),
(16, 'Ayana Resort Bali', 'reservation@ayanaresort.com', 'Jl. Karang Mas Sejahtera, Jimbaran, Bali', '(0361) 702222', 'Resor luas yang terkenal dengan Rock Bar dan pemandangan matahari terbenam yang indah.'),
(17, 'Gumaya Tower Hotel', 'info@gumayatowerhotel.com', 'Jl. Gajah Mada No. 59-61, Semarang', '(024) 3551999', 'Hotel gedung tertinggi di Semarang yang menawarkan fasilitas bintang 5 untuk pelancong.'),
(18, 'Adhiwangsa Hotel Solo', 'info@adhiwangsasolo.id', 'Jl. Adi Sucipto No. 146, Solo', '(0271) 7464999', 'Hotel bergaya istana yang menawarkan suasana tenang dan klasik di pusat kota Solo.'),
(19, 'Four Seasons Jakarta', 'contact@fourseasons.com', 'Jl. Gatot Subroto No. 18, Jakarta Selatan', '(021) 22771888', 'Desain interior modern yang mewah karya Bill Bensley di pusat kawasan finansial Jakarta.'),
(20, 'The Ritz-Carlton Pacific Place', 'rc.jktpp.res@ritzcarlton.com', 'Sudirman Central Business District (SCBD), Jakarta', '(021) 25501888', 'Hotel eksklusif yang menyatu dengan mal Pacific Place di kawasan bisnis paling elit.');

CREATE TABLE tipe_kamar (
    id_kamar INT AUTO_INCREMENT PRIMARY KEY,
    id_hotel INT REFERENCES hotel(id_hotel),
    nama_kamar VARCHAR(64),
    deskripsi VARCHAR(255),
    kapasitas_orang INT(2),
    total_kamar INT(3),
    harga INT
);

INSERT INTO `tipe_kamar` (`id_kamar`, `id_hotel`, `nama_kamar`, `deskripsi`, `kapasitas_orang`, `total_kamar`, `harga`) VALUES
(1, 1, 'Deluxe King Room', 'Kamar mewah dengan pemandangan Bundaran HI.', 2, 10, 3500000),
(2, 1, 'Presidential Suite', 'Fasilitas terbaik dengan ruang tamu pribadi.', 4, 2, 15000000),
(3, 2, 'Deluxe Mountain View', 'Pemandangan pegunungan Bandung yang sejuk.', 2, 20, 2200000),
(4, 3, 'Javanese Royal Suite', 'Nuansa klasik Jawa dengan kenyamanan modern.', 2, 5, 2800000),
(5, 7, 'Ocean Front Lagoon', 'Akses langsung ke kolam renang laguna.', 2, 15, 4200000),
(6, 14, 'One Bedroom Pool Villa', 'Villa pribadi dengan kolam renang menghadap laut.', 2, 8, 12000000);

CREATE TABLE review (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT REFERENCES users(id_user),
    gambar VARCHAR(255),
    rating TINYINT CHECK (rating BETWEEN 1 AND 5),
    deskripsi VARCHAR(255)
);

INSERT INTO `review` (`id_review`, `id_user`, `gambar`, `rating`, `deskripsi`) VALUES
(1, 2, 'review_hotel1.jpg', 5, 'Luar biasa! Pelayanan sangat ramah dan kamar sangat bersih.'),
(2, 3, 'review_hotel2.jpg', 4, 'Sarapannya enak sekali, tapi proses check-in agak lama.'),
(3, 4, 'review_hotel7.jpg', 5, 'Kolam renangnya juara! Sangat cocok untuk keluarga.'),
(4, 5, 'review_hotel14.jpg', 5, 'Pengalaman menginap paling berkesan di Bali.');

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

INSERT INTO `reservasi` (`id_reservasi`, `id_user`, `id_kamar`, `id_pembayaran`, `id_pembatalan`, `tanggal_reservasi`, `tanggal_check_in`, `tanggal_check_out`, `jumlah_kamar`, `total_malam`) VALUES
(1, 2, 1, 1, NULL, '2025-12-25', '2025-12-20', '2025-12-21', 1, 1),
(2, 3, 1, 2, NULL, '2025-12-25', '2025-12-24', '2025-12-26', 1, 2),
(3, 4, 3, 3, 1, '2025-12-25', '2025-11-10', '2025-11-11', 1, 1),
(4, 5, 4, 4, NULL, '2025-12-25', '2025-12-30', '2026-01-01', 1, 2);
