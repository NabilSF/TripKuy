<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user']['id'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='login.php';</script>";
    exit();
}

// Mendapatkan id_hotel dari URL jika ada
$id_hotel = isset($_GET['id_hotel']) ? (int)$_GET['id_hotel'] : 0;

// Query untuk mendapatkan data hotel dari database
$hotel_data = null;
if ($id_hotel > 0) {
    $query = "SELECT * FROM hotel WHERE id_hotel = $id_hotel";
    $result = mysqli_query($koneksi, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $hotel_data = mysqli_fetch_assoc($result);
    }
}

// Jika tidak ada data hotel ditemukan
if (!$hotel_data) {
    echo "<script>alert('Hotel tidak ditemukan'); window.location.href='hotels.php';</script>";
    exit();
}

// Proses reservasi ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user  = $_SESSION['user']['id'];
    $id_hotel = $_POST['hotel_id'];
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $telepon  = $_POST['telepon'];
    $tanggal_checkin = $_POST['tanggal_checkin'];
    $tanggal_checkout = $_POST['tanggal_checkout'];
    $jumlah_kamar = $_POST['jumlah_kamar'];
    $jumlah_tamu = $_POST['jumlah_tamu'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $catatan_khusus = $_POST['catatan_khusus'];
    
    // Hitung total harga (contoh sederhana)
    $harga_per_malam = 416626; // Harga dari database atau perhitungan
    $date1 = new DateTime($tanggal_checkin);
    $date2 = new DateTime($tanggal_checkout);
    $interval = $date1->diff($date2);
    $jumlah_malam = $interval->days;
    $total_harga = $harga_per_malam * $jumlah_malam * $jumlah_kamar;
    
    // Insert ke database
    $query = "INSERT INTO reservasi 
              (id_user, id_hotel, nama, email, telepon, tanggal_checkin, tanggal_checkout, 
               jumlah_kamar, jumlah_tamu, tipe_kamar, catatan_khusus, total_harga, status) 
              VALUES 
              ('$id_user', '$id_hotel', '$nama', '$email', '$telepon', '$tanggal_checkin', 
               '$tanggal_checkout', '$jumlah_kamar', '$jumlah_tamu', '$tipe_kamar', 
               '$catatan_khusus', '$total_harga', 'pending')";
    
    if (mysqli_query($koneksi, $query)) {
        $id_reservasi = mysqli_insert_id($koneksi);
        header("Location: konfirmasi.php?id_reservasi=$id_reservasi");
        exit();
    } else {
        $error = "Terjadi kesalahan. Silakan coba lagi.";
    }
}

// Ambil data user dari session
$user_nama = $_SESSION['user']['nama'] ?? '';
$user_email = $_SESSION['user']['email'] ?? '';
$user_telepon = $_SESSION['user']['telepon'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripKuy - Formulir Reservasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2aa090;
            --secondary: #222222;
            --accent: #00A699;
            --light-bg: #f8fafc;
            --text-primary: #333333;
            --text-secondary: #666666;
            --text-light: #888888;
            --border-color: #e0e0e0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --white: #ffffff;
            --gray-light: #f5f5f5;
            --gray-medium: #e0e0e0;
            --gray-dark: #888888;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* HEADER */
        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            background-color: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--white);
        }
        
        .logo h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary);
        }
        
        .logo span {
            color: var(--primary);
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 25px;
        }
        
        nav a {
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            transition: color 0.3s;
            padding: 5px 0;
            position: relative;
        }
        
        nav a:hover {
            color: var(--primary);
        }
        
        .account-button {
            background-color: var(--white);
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .account-button:hover {
            background-color: var(--secondary);
            color: var(--white);
            border-color: var(--secondary);
        }
        
        /* Main Content */
        .reservation-container {
            display: flex;
            gap: 30px;
            margin: 40px 0;
        }
        
        .reservation-form {
            flex: 2;
            background-color: var(--white);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        
        .reservation-sidebar {
            flex: 1;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }
        
        .form-logo {
            font-size: 36px;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .form-title {
            font-size: 28px;
            color: var(--secondary);
            margin-bottom: 10px;
        }
        
        .form-subtitle {
            color: var(--text-light);
            font-size: 18px;
            font-weight: 500;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 20px;
            color: var(--secondary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--gray-light);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }
        
        .form-label.required::after {
            content: " *";
            color: var(--danger);
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background-color: var(--white);
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(42, 160, 144, 0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .date-group {
            display: flex;
            gap: 20px;
        }
        
        .date-input {
            flex: 1;
        }
        
        .error-message {
            color: var(--danger);
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        /* Sidebar */
        .hotel-summary-box {
            background-color: var(--white);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 100px;
        }
        
        .hotel-summary-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .hotel-summary-info h4 {
            color: var(--secondary);
            margin-bottom: 5px;
            font-size: 20px;
        }
        
        .hotel-summary-info p {
            color: var(--text-light);
            font-size: 14px;
        }
        
        .hotel-rating {
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: var(--gray-light);
            padding: 8px 12px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
        }
        
        .rating-stars {
            color: var(--warning);
        }
        
        .rating-number {
            font-weight: 600;
            color: var(--secondary);
        }
        
        .rating-count {
            color: var(--text-light);
            font-size: 13px;
        }
        
        .price-summary {
            background-color: var(--gray-light);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid var(--border-color);
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed var(--border-color);
        }
        
        .price-row.total {
            font-weight: 700;
            font-size: 18px;
            color: var(--secondary);
            border-bottom: none;
            padding-top: 10px;
            border-top: 2px solid var(--border-color);
            margin-top: 15px;
        }
        
        .discount-amount {
            color: var(--success);
        }
        
        .savings-box {
            background-color: rgba(245, 158, 11, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
        }
        
        .savings-text {
            color: #92400e;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 5px;
        }
        
        .savings-amount {
            font-size: 20px;
            font-weight: 700;
            color: #92400e;
        }
        
        .booking-footer {
            background-color: var(--gray-light);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Buttons */
        .submit-button {
            width: 100%;
            background: linear-gradient(to right, var(--primary), var(--accent));
            color: var(--white);
            border: none;
            padding: 18px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .submit-button:hover {
            background: linear-gradient(to right, var(--accent), #008c7a);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 166, 153, 0.3);
        }
        
        .back-button {
            background-color: var(--gray-light);
            color: var(--text-secondary);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-button:hover {
            background-color: var(--gray-medium);
        }
        
        /* Footer */
        footer {
            background-color: var(--secondary);
            color: var(--white);
            padding: 50px 0 25px;
            margin-top: 60px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-column h3 {
            font-size: 20px;
            margin-bottom: 20px;
            color: var(--white);
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 10px;
        }
        
        .footer-column a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-column a:hover {
            color: var(--primary);
        }
        
        .copyright {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #444;
            font-size: 14px;
            color: #94a3b8;
        }
        
        @media (max-width: 992px) {
            .reservation-container {
                flex-direction: column;
            }
            
            .hotel-summary-box {
                position: static;
            }
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }
            
            nav ul {
                gap: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .reservation-form {
                padding: 25px;
            }
            
            .date-group {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        @media (max-width: 480px) {
            .reservation-form {
                padding: 20px;
            }
            
            .account-button {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-suitcase-rolling"></i>
                    </div>
                    <h1>Trip<span>Kuy</span></h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="hotels.php">Hotel</a></li>
                        <li><a href="#deals">Penawaran</a></li>
                        <li><a href="#help">Bantuan</a></li>
                    </ul>
                </nav>
                <button class="account-button" id="accountButton">
                    <i class="fas fa-user"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Error Message -->
        <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <div class="reservation-container">
            <!-- Formulir Reservasi -->
            <form method="POST" class="reservation-form" id="reservationForm">
                <input type="hidden" name="hotel_id" value="<?php echo $hotel_data['id_hotel']; ?>">
                
                <div class="form-header">
                    <div class="form-logo">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h2 class="form-title">Formulir Reservasi</h2>
                    <p class="form-subtitle">Lengkapi data diri Anda untuk melanjutkan pemesanan</p>
                </div>
                
                <!-- Section 1: Data Pemesan -->
                <div class="form-section">
                    <h3 class="section-title">Data Pemesan</h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label required">Nama Lengkap</label>
                            <input type="text" class="form-input" name="nama" id="nama" 
                                   value="<?php echo htmlspecialchars($user_nama); ?>" 
                                   placeholder="Masukkan nama lengkap" required>
                            <div class="error-message" id="namaError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Email</label>
                            <input type="email" class="form-input" name="email" id="email" 
                                   value="<?php echo htmlspecialchars($user_email); ?>" 
                                   placeholder="nama@email.com" required>
                            <div class="error-message" id="emailError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Nomor Telepon</label>
                            <input type="tel" class="form-input" name="telepon" id="telepon" 
                                   value="<?php echo htmlspecialchars($user_telepon); ?>" 
                                   placeholder="081234567890" required>
                            <div class="error-message" id="teleponError"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Section 2: Detail Reservasi -->
                <div class="form-section">
                    <h3 class="section-title">Detail Reservasi</h3>
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <div class="date-group">
                                <div class="date-input">
                                    <label class="form-label required">Tanggal Check-in</label>
                                    <input type="date" class="form-input" name="tanggal_checkin" id="tanggal_checkin" 
                                           min="<?php echo date('Y-m-d'); ?>" required>
                                    <div class="error-message" id="checkinError"></div>
                                </div>
                                
                                <div class="date-input">
                                    <label class="form-label required">Tanggal Check-out</label>
                                    <input type="date" class="form-input" name="tanggal_checkout" id="tanggal_checkout" required>
                                    <div class="error-message" id="checkoutError"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Jumlah Kamar</label>
                            <select class="form-select" name="jumlah_kamar" id="jumlah_kamar" required>
                                <option value="">Pilih jumlah</option>
                                <option value="1">1 Kamar</option>
                                <option value="2">2 Kamar</option>
                                <option value="3">3 Kamar</option>
                                <option value="4">4 Kamar</option>
                            </select>
                            <div class="error-message" id="kamarError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Jumlah Tamu</label>
                            <select class="form-select" name="jumlah_tamu" id="jumlah_tamu" required>
                                <option value="">Pilih jumlah</option>
                                <option value="1">1 Tamu</option>
                                <option value="2">2 Tamu</option>
                                <option value="3">3 Tamu</option>
                                <option value="4">4 Tamu</option>
                            </select>
                            <div class="error-message" id="tamuError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tipe Kamar</label>
                            <select class="form-select" name="tipe_kamar" id="tipe_kamar" required>
                                <option value="">Pilih tipe kamar</option>
                                <option value="Deluxe Twin">Deluxe Twin</option>
                                <option value="Superior Double">Superior Double</option>
                                <option value="Executive Suite">Executive Suite</option>
                                <option value="Presidential Suite">Presidential Suite</option>
                            </select>
                            <div class="error-message" id="tipeError"></div>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label">Catatan Khusus (Opsional)</label>
                        <textarea class="form-textarea" name="catatan_khusus" id="catatan_khusus" 
                                  placeholder="Contoh: Minta kamar lantai atas, dekat lift, atau permintaan khusus lainnya..."></textarea>
                    </div>
                </div>
                
                <!-- Terms & Conditions -->
                <div class="form-section">
                    <h3 class="section-title">Syarat & Ketentuan</h3>
                    
                    <div class="form-group">
                        <div style="background-color: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color);">
                            <p style="margin-bottom: 10px;">Dengan melanjutkan pemesanan, Anda menyetujui:</p>
                            <ul style="list-style-type: disc; padding-left: 20px; color: var(--text-secondary);">
                                <li>Pembayaran harus dilakukan dalam 2 jam setelah pemesanan</li>
                                <li>Pembatalan dapat dilakukan maksimal 24 jam sebelum check-in</li>
                                <li>Check-in: 14:00 WIB, Check-out: 12:00 WIB</li>
                                <li>Data yang diisi adalah benar dan dapat dipertanggungjawabkan</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" id="agreeTerms" required style="width: 18px; height: 18px;">
                            <span>Saya telah membaca dan menyetujui syarat & ketentuan yang berlaku</span>
                        </label>
                        <div class="error-message" id="termsError"></div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="submit-button" id="submitBtn">
                    <i class="fas fa-lock"></i> Lanjutkan ke Pembayaran
                </button>
                
                <!-- Back Button -->
                <button type="button" class="back-button" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </form>
            
            <!-- Sidebar: Hotel Summary -->
            <div class="reservation-sidebar">
                <div class="hotel-summary-box">
                    <div class="hotel-summary-header">
                        <div class="hotel-summary-info">
                            <h4><?php echo htmlspecialchars($hotel_data['nama_hotel']); ?></h4>
                            <p><?php echo htmlspecialchars($hotel_data['alamat']); ?></p>
                        </div>
                        <div class="hotel-rating">
                            <div class="rating-stars" style="font-size: 14px;">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <div>
                                <div class="rating-number">4.5</div>
                                <div class="rating-count">(584 Peringkat)</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Harga Ringkasan -->
                    <div class="price-summary">
                        <div class="price-row">
                            <span>Harga per Kamar per Malam</span>
                            <span>Rp416.626</span>
                        </div>
                        <div class="price-row">
                            <span>Diskon Kupon 72%</span>
                            <span class="discount-amount">-Rp1.071.325</span>
                        </div>
                        <div class="price-row total">
                            <span>Total Perkiraan</span>
                            <span>Rp416.626</span>
                        </div>
                    </div>
                    
                    <!-- Penghematan -->
                    <div class="savings-box">
                        <div class="savings-text">
                            <i class="fas fa-piggy-bank"></i> Penghematan Anda
                        </div>
                        <div class="savings-amount">Rp1.071.325</div>
                    </div>
                    
                    <!-- Info -->
                    <div class="booking-footer">
                        <i class="fas fa-info-circle"></i>
                        Harga sudah termasuk pajak dan biaya layanan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>TripKuy</h3>
                    <p>Platform reservasi penginapan terpercaya di Indonesia. Memberikan pengalaman menginap terbaik dengan harga terjangkau.</p>
                </div>
                <div class="footer-column">
                    <h3>Tautan Cepat</h3>
                    <ul>
                        <li><a href="index.php">Beranda</a></li>
                        <li><a href="hotels.php">Hotel</a></li>
                        <li><a href="#deals">Penawaran</a></li>
                        <li><a href="#help">Bantuan</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Kontak Kami</h3>
                    <ul>
                        <li><a href="tel:+622112345678">+62 21 1234 5678</a></li>
                        <li><a href="mailto:info@tripkuy.id">info@tripkuy.id</a></li>
                        <li><a href="#">Jl. Sudirman No. 123, Jakarta</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Kebijakan</h3>
                    <ul>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Pembatalan</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> TripKuy. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Elemen form
        const reservationForm = document.getElementById('reservationForm');
        const submitBtn = document.getElementById('submitBtn');
        const accountButton = document.getElementById('accountButton');
        
        // Set min date untuk check-out berdasarkan check-in
        const checkinInput = document.getElementById('tanggal_checkin');
        const checkoutInput = document.getElementById('tanggal_checkout');
        
        checkinInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                const nextDayStr = nextDay.toISOString().split('T')[0];
                checkoutInput.min = nextDayStr;
                
                // Set default checkout jika belum diisi
                if (!checkoutInput.value || checkoutInput.value < nextDayStr) {
                    checkoutInput.value = nextDayStr;
                }
            }
        });
        
        // Set default tanggal
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        
        checkinInput.value = today.toISOString().split('T')[0];
        checkoutInput.value = tomorrow.toISOString().split('T')[0];
        checkoutInput.min = tomorrow.toISOString().split('T')[0];
        
        // Validasi form
        reservationForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset error messages
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });
            
            // Validasi nama
            const nama = document.getElementById('nama').value.trim();
            if (!nama) {
                document.getElementById('namaError').textContent = 'Nama harus diisi';
                document.getElementById('namaError').style.display = 'block';
                isValid = false;
            }
            
            // Validasi email
            const email = document.getElementById('email').value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                document.getElementById('emailError').textContent = 'Email harus diisi';
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            } else if (!emailPattern.test(email)) {
                document.getElementById('emailError').textContent = 'Format email tidak valid';
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }
            
            // Validasi telepon
            const telepon = document.getElementById('telepon').value.trim();
            const phonePattern = /^[0-9]{10,13}$/;
            if (!telepon) {
                document.getElementById('teleponError').textContent = 'Nomor telepon harus diisi';
                document.getElementById('teleponError').style.display = 'block';
                isValid = false;
            } else if (!phonePattern.test(telepon.replace(/[^0-9]/g, ''))) {
                document.getElementById('teleponError').textContent = 'Format nomor telepon tidak valid';
                document.getElementById('teleponError').style.display = 'block';
                isValid = false;
            }
            
            // Validasi tanggal
            const checkin = document.getElementById('tanggal_checkin').value;
            const checkout = document.getElementById('tanggal_checkout').value;
            if (!checkin) {
                document.getElementById('checkinError').textContent = 'Tanggal check-in harus diisi';
                document.getElementById('checkinError').style.display = 'block';
                isValid = false;
            }
            if (!checkout) {
                document.getElementById('checkoutError').textContent = 'Tanggal check-out harus diisi';
                document.getElementById('checkoutError').style.display = 'block';
                isValid = false;
            } else if (checkout <= checkin) {
                document.getElementById('checkoutError').textContent = 'Tanggal check-out harus setelah check-in';
                document.getElementById('checkoutError').style.display = 'block';
                isValid = false;
            }
            
            // Validasi dropdown
            const jumlahKamar = document.getElementById('jumlah_kamar').value;
            const jumlahTamu = document.getElementById('jumlah_tamu').value;
            const tipeKamar = document.getElementById('tipe_kamar').value;
            
            if (!jumlahKamar) {
                document.getElementById('kamarError').textContent = 'Jumlah kamar harus dipilih';
                document.getElementById('kamarError').style.display = 'block';
                isValid = false;
            }
            
            if (!jumlahTamu) {
                document.getElementById('tamuError').textContent = 'Jumlah tamu harus dipilih';
                document.getElementById('tamuError').style.display = 'block';
                isValid = false;
            }
            
            if (!tipeKamar) {
                document.getElementById('tipeError').textContent = 'Tipe kamar harus dipilih';
                document.getElementById('tipeError').style.display = 'block';
                isValid = false;
            }
            
            // Validasi terms
            const agreeTerms = document.getElementById('agreeTerms').checked;
            if (!agreeTerms) {
                document.getElementById('termsError').textContent = 'Anda harus menyetujui syarat & ketentuan';
                document.getElementById('termsError').style.display = 'block';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll ke error pertama
                const firstError = document.querySelector('.error-message[style*="display: block"]');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Disable button untuk mencegah double submit
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>