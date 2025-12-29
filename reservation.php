<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user']['id'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='login.php';</script>";
    exit();
}
$id_hotel = isset($_GET['id']) ? intval($_GET['id']) : 0;
$checkin = isset($_GET['checkin']) ? $_GET['checkin'] : date('Y-m-d');
$checkout = isset($_GET['checkout']) ? $_GET['checkout'] : date('Y-m-d', strtotime('+2 days'));
$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;

if ($id_hotel == 0 && isset($_POST['hotel_id'])) {
    $id_hotel = intval($_POST['hotel_id']);
}

if ($id_hotel == 0) {
    die("<div style='text-align: center; padding: 50px; background: #f8f9fa;'>
            <h2 style='color: #dc3545;'>Error: Hotel tidak valid!</h2>
            <p>ID Hotel tidak ditemukan atau tidak valid.</p>
            <p>Pastikan Anda mengakses halaman ini dari halaman detail hotel.</p>
            <p><a href='home.php' style='display: inline-block; margin-top: 20px; padding: 10px 20px; 
               background: #2aa090; color: white; text-decoration: none; border-radius: 5px;'>
               Kembali ke Beranda</a></p>
         </div>");
}

$hotel_query = "SELECT * FROM hotel WHERE id_hotel = ?";
$stmt = mysqli_prepare($conn, $hotel_query);
mysqli_stmt_bind_param($stmt, "i", $id_hotel);
mysqli_stmt_execute($stmt);
$hotel_result = mysqli_stmt_get_result($stmt);
$hotel_data = mysqli_fetch_assoc($hotel_result);

if (!$hotel_data) {
    die("<div style='text-align: center; padding: 50px; background: #f8f9fa;'>
            <h2 style='color: #dc3545;'>Error: Hotel tidak ditemukan!</h2>
            <p>Hotel dengan ID $id_hotel tidak ada dalam database.</p>
            <p><a href='home.php' style='display: inline-block; margin-top: 20px; padding: 10px 20px; 
               background: #2aa090; color: white; text-decoration: none; border-radius: 5px;'>
               Kembali ke Beranda</a></p>
         </div>");
}

$checkin_date = new DateTime($checkin);
$checkout_date = new DateTime($checkout);
$nights = $checkin_date->diff($checkout_date)->days;
if ($nights < 1) $nights = 1;

$kamar_query = "SELECT * FROM tipe_kamar WHERE id_hotel = ? ORDER BY harga ASC";
$stmt_kamar = mysqli_prepare($conn, $kamar_query);
mysqli_stmt_bind_param($stmt_kamar, "i", $id_hotel);
mysqli_stmt_execute($stmt_kamar);
$kamar_result = mysqli_stmt_get_result($stmt_kamar);
$kamar_data = mysqli_fetch_all($kamar_result, MYSQLI_ASSOC);

$selected_room = null;
if ($room_id > 0) {
    foreach ($kamar_data as $kamar) {
        if ($kamar['id_kamar'] == $room_id) {
            $selected_room = $kamar;
            break;
        }
    }
}

if (!$selected_room && !empty($kamar_data)) {
    $selected_room = $kamar_data[0];
}

$user_nama = "";
$user_email = "";
$user_telepon = "";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT * FROM users WHERE id_user = ?";
    $stmt_user = mysqli_prepare($conn, $user_query);
    mysqli_stmt_bind_param($stmt_user, "i", $user_id);
    mysqli_stmt_execute($stmt_user);
    $user_result = mysqli_stmt_get_result($stmt_user);
    if ($user = mysqli_fetch_assoc($user_result)) {
        $user_nama = $user['nama'] ?? "";
        $user_email = $user['email'] ?? "";
        $user_telepon = $user['no_telepon'] ?? "";
    }
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon'] ?? '');
    $tanggal_checkin = $_POST['tanggal_checkin'] ?? $checkin;
    $tanggal_checkout = $_POST['tanggal_checkout'] ?? $checkout;
    $jumlah_kamar = intval($_POST['jumlah_kamar'] ?? 1);
    $jumlah_tamu = intval($_POST['jumlah_tamu'] ?? 1);
    $tipe_kamar = mysqli_real_escape_string($conn, $_POST['tipe_kamar'] ?? '');
    $catatan_khusus = mysqli_real_escape_string($conn, $_POST['catatan_khusus'] ?? '');
    $hotel_id = intval($_POST['hotel_id'] ?? $id_hotel);
    
    $errors = [];
    
    if (empty($nama)) $errors[] = "Nama harus diisi";
    if (empty($email)) $errors[] = "Email harus diisi";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid";
    if (empty($telepon)) $errors[] = "Telepon harus diisi";
    if (empty($tanggal_checkin)) $errors[] = "Tanggal check-in harus diisi";
    if (empty($tanggal_checkout)) $errors[] = "Tanggal check-out harus diisi";
    if ($jumlah_kamar < 1) $errors[] = "Jumlah kamar harus minimal 1";
    if ($jumlah_tamu < 1) $errors[] = "Jumlah tamu harus minimal 1";
    if (empty($tipe_kamar)) $errors[] = "Tipe kamar harus dipilih";
    
    $checkin_post = new DateTime($tanggal_checkin);
    $checkout_post = new DateTime($tanggal_checkout);
    if ($checkout_post <= $checkin_post) {
        $errors[] = "Tanggal check-out harus setelah tanggal check-in";
    }
    
    if (empty($errors)) {
        $room_id_to_save = 0;
        foreach ($kamar_data as $kamar) {
            if ($kamar['nama_kamar'] == $tipe_kamar) {
                $room_id_to_save = $kamar['id_kamar'];
                break;
            }
        }
        
        $harga_per_malam = 0;
        if ($room_id_to_save > 0) {
            foreach ($kamar_data as $kamar) {
                if ($kamar['id_kamar'] == $room_id_to_save) {
                    $harga_per_malam = $kamar['harga'];
                    break;
                }
            }
        } elseif ($selected_room) {
            $harga_per_malam = $selected_room['harga'];
            $room_id_to_save = $selected_room['id_kamar'];
        }
        
        $total_harga = $harga_per_malam * $nights * $jumlah_kamar;
        
        $pembayaran_query = "INSERT INTO pembayaran (total_harga, tipe_pembayaran) VALUES (?, 'Belum Dibayar')";
        $stmt_pembayaran = mysqli_prepare($conn, $pembayaran_query);
        mysqli_stmt_bind_param($stmt_pembayaran, "i", $total_harga);
        
        if (mysqli_stmt_execute($stmt_pembayaran)) {
            $id_pembayaran = mysqli_insert_id($conn);
            
            $user_id_for_reservation = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
            
            $reservasi_query = "INSERT INTO reservasi (
                id_user, id_kamar, id_pembayaran, tanggal_reservasi, 
                tanggal_check_in, tanggal_check_out, jumlah_kamar, total_malam
            ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)";
            
            $stmt_reservasi = mysqli_prepare($conn, $reservasi_query);
            mysqli_stmt_bind_param(
                $stmt_reservasi, 
                "iiissii", 
                $user_id_for_reservation,
                $room_id_to_save,
                $id_pembayaran,
                $tanggal_checkin,
                $tanggal_checkout,
                $jumlah_kamar,
                $nights
            );
            
            if (mysqli_stmt_execute($stmt_reservasi)) {
                $reservation_id = mysqli_insert_id($conn);
                $success = "Reservasi berhasil! Nomor reservasi: #" . str_pad($reservation_id, 6, '0', STR_PAD_LEFT);
                
                $user_nama = "";
                $user_email = "";
                $user_telepon = "";
            } else {
                $error = "Gagal menyimpan reservasi: " . mysqli_error($conn);
            }
        } else {
            $error = "Gagal menyimpan data pembayaran: " . mysqli_error($conn);
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

$harga_per_malam = $selected_room ? $selected_room['harga'] : 0;
$total_harga = $harga_per_malam * $nights;
$diskon = $total_harga * 0.67; // 72% discount
$harga_setelah_diskon = $total_harga - $diskon;
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
    --secondary: #222;
    --accent: #00A699;
    --light-bg: #f8fafc;
    --text-primary: #333;
    --text-secondary: #666;
    --text-light: #888;
    --border-color: #e0e0e0;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --white: #fff;
    --gray-light: #f5f5f5;
    --gray-medium: #e0e0e0;
    --gray-dark: #888;
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

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s;
    background-color: var(--white);
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
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
</style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <div class="logo-icon"><i class="fas fa-suitcase-rolling"></i></div>
                    <h1>Trip<span>Kuy</span></h1>
                </div>
                <nav>
                </nav>
                <button class="account-button" id="accountButton"><i class="fas fa-user"></i></button>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if(!empty($error)): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if(!empty($success)): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="reservation-container">
            <form method="POST" class="reservation-form" id="reservationForm">
                <input type="hidden" name="hotel_id" value="<?php echo $id_hotel; ?>">
                
                <div class="form-header">
                    <div class="form-logo"><i class="fas fa-calendar-check"></i></div>
                    <h2 class="form-title">Formulir Reservasi</h2>
                    <p class="form-subtitle">Lengkapi data diri Anda untuk melanjutkan pemesanan</p>
                </div>

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

                <div class="form-section">
                    <h3 class="section-title">Detail Reservasi</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <div class="date-group">
                                <div class="date-input">
                                    <label class="form-label required">Tanggal Check-in</label>
                                    <input type="date" class="form-input" name="tanggal_checkin" 
                                           id="tanggal_checkin" value="<?php echo htmlspecialchars($checkin); ?>" 
                                           min="<?php echo date('Y-m-d'); ?>" required>
                                    <div class="error-message" id="checkinError"></div>
                                </div>

                                <div class="date-input">
                                    <label class="form-label required">Tanggal Check-out</label>
                                    <input type="date" class="form-input" name="tanggal_checkout" 
                                           id="tanggal_checkout" value="<?php echo htmlspecialchars($checkout); ?>" 
                                           required>
                                    <div class="error-message" id="checkoutError"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Jumlah Kamar</label>
                            <select class="form-select" name="jumlah_kamar" id="jumlah_kamar" required>
                                <option value="">Pilih jumlah</option>
                                <option value="1" <?php echo (isset($_POST['jumlah_kamar']) && $_POST['jumlah_kamar'] == '1') ? 'selected' : ''; ?>>1 Kamar</option>
                                <option value="2" <?php echo (isset($_POST['jumlah_kamar']) && $_POST['jumlah_kamar'] == '2') ? 'selected' : ''; ?>>2 Kamar</option>
                                <option value="3" <?php echo (isset($_POST['jumlah_kamar']) && $_POST['jumlah_kamar'] == '3') ? 'selected' : ''; ?>>3 Kamar</option>
                                <option value="4" <?php echo (isset($_POST['jumlah_kamar']) && $_POST['jumlah_kamar'] == '4') ? 'selected' : ''; ?>>4 Kamar</option>
                            </select>
                            <div class="error-message" id="kamarError"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Jumlah Tamu</label>
                            <select class="form-select" name="jumlah_tamu" id="jumlah_tamu" required>
                                <option value="">Pilih jumlah</option>
                                <option value="1" <?php echo (isset($_POST['jumlah_tamu']) && $_POST['jumlah_tamu'] == '1') ? 'selected' : ''; ?>>1 Tamu</option>
                                <option value="2" <?php echo (isset($_POST['jumlah_tamu']) && $_POST['jumlah_tamu'] == '2') ? 'selected' : ''; ?>>2 Tamu</option>
                                <option value="3" <?php echo (isset($_POST['jumlah_tamu']) && $_POST['jumlah_tamu'] == '3') ? 'selected' : ''; ?>>3 Tamu</option>
                                <option value="4" <?php echo (isset($_POST['jumlah_tamu']) && $_POST['jumlah_tamu'] == '4') ? 'selected' : ''; ?>>4 Tamu</option>
                            </select>
                            <div class="error-message" id="tamuError"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Tipe Kamar</label>
                            <select class="form-select" name="tipe_kamar" id="tipe_kamar" required>
                                <option value="">Pilih tipe kamar</option>
                                <?php if (!empty($kamar_data)): ?>
                                    <?php foreach ($kamar_data as $kamar): ?>
                                        <option value="<?php echo htmlspecialchars($kamar['nama_kamar']); ?>"
                                            <?php echo (isset($_POST['tipe_kamar']) && $_POST['tipe_kamar'] == $kamar['nama_kamar']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($kamar['nama_kamar']); ?> - 
                                            Rp <?php echo number_format($kamar['harga'], 0, ',', '.'); ?>/malam
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="error-message" id="tipeError"></div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Catatan Khusus (Opsional)</label>
                        <textarea class="form-textarea" name="catatan_khusus" id="catatan_khusus" 
                                  placeholder="Contoh: Minta kamar lantai atas, dekat lift, atau permintaan khusus lainnya..."><?php echo isset($_POST['catatan_khusus']) ? htmlspecialchars($_POST['catatan_khusus']) : ''; ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">Syarat & Ketentuan</h3>
                    <div class="form-group">
                        <div style="background-color:#f8fafc;padding:20px;border-radius:8px;border:1px solid var(--border-color);">
                            <p style="margin-bottom:10px">Dengan melanjutkan pemesanan, Anda menyetujui:</p>
                            <ul style="list-style-type:disc;padding-left:20px;color:var(--text-secondary)">
                                <li>Pembayaran harus dilakukan dalam 2 jam setelah pemesanan</li>
                                <li>Pembatalan dapat dilakukan maksimal 24 jam sebelum check-in</li>
                                <li>Check-in: 14:00 WIB, Check-out: 12:00 WIB</li>
                                <li>Data yang diisi adalah benar dan dapat dipertanggungjawabkan</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer">
                            <input type="checkbox" id="agreeTerms" name="agreeTerms" required 
                                   style="width:18px;height:18px" 
                                   <?php echo (isset($_POST['agreeTerms'])) ? 'checked' : ''; ?>>
                            <span>Saya telah membaca dan menyetujui syarat & ketentuan yang berlaku</span>
                        </label>
                        <div class="error-message" id="termsError"></div>
                    </div>
                </div>

                <button type="submit" class="submit-button" id="submitBtn">
                    <i class="fas fa-lock"></i> Lanjutkan ke Pembayaran
                </button>
                <button type="button" class="back-button" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </form>

            <div class="reservation-sidebar">
                <div class="hotel-summary-box">
                    <div class="hotel-summary-header">
                        <div class="hotel-summary-info">
                            <h4><?php echo htmlspecialchars($hotel_data['nama_hotel']); ?></h4>
                            <p><?php echo htmlspecialchars($hotel_data['alamat']); ?></p>
                        </div>
                        <div class="hotel-rating">
                            <div class="rating-stars" style="font-size:14px">
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

                    <div class="price-summary">
                        <?php if ($selected_room): ?>
                        <div class="price-row">
                            <span>Harga per Kamar per Malam</span>
                            <span>Rp <?php echo number_format($selected_room['harga'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="price-row">
                            <span><?php echo $nights; ?> Malam</span>
                            <span>Rp <?php echo number_format($selected_room['harga'] * $nights, 0, ',', '.'); ?></span>
                        </div>
                        <div class="price-row">
                            <span>Diskon Kupon 67%</span>
                            <span class="discount-amount">-Rp <?php echo number_format($diskon, 0, ',', '.'); ?></span>
                        </div>
                        <div class="price-row total">
                            <span>Total Perkiraan</span>
                            <span>Rp <?php echo number_format($harga_setelah_diskon, 0, ',', '.'); ?></span>
                        </div>
                        <?php else: ?>
                        <div class="price-row">
                            <span>Harga per Kamar per Malam</span>
                            <span>Rp 0</span>
                        </div>
                        <div class="price-row">
                            <span><?php echo $nights; ?> Malam</span>
                            <span>Rp 0</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="savings-box">
                        <div class="savings-text">
                            <i class="fas fa-piggy-bank"></i> Penghematan Anda
                        </div>
                        <div class="savings-amount">Rp <?php echo number_format($diskon, 0, ',', '.'); ?></div>
                    </div>

                    <div class="booking-footer">
                        <i class="fas fa-info-circle"></i> Harga sudah termasuk pajak dan biaya layanan
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <li><a href="home.php">Beranda</a></li>
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
        const reservationForm = document.getElementById('reservationForm');
        const submitBtn = document.getElementById('submitBtn');
        const checkinInput = document.getElementById('tanggal_checkin');
        const checkoutInput = document.getElementById('tanggal_checkout');

        // Set min date untuk checkout berdasarkan checkin
        checkinInput.addEventListener('change', function() {
            if (this.value) {
                const nextDay = new Date(this.value);
                nextDay.setDate(nextDay.getDate() + 1);
                const nextDayStr = nextDay.toISOString().split('T')[0];
                checkoutInput.min = nextDayStr;
                
                // Jika checkout lebih awal dari checkin+1, update checkout
                if (!checkoutInput.value || checkoutInput.value < nextDayStr) {
                    checkoutInput.value = nextDayStr;
                }
            }
        });

        // Validasi form sebelum submit
        reservationForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset error messages
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
                el.textContent = '';
            });

            // Validasi Nama
            const nama = document.getElementById('nama').value.trim();
            if (!nama) {
                document.getElementById('namaError').textContent = 'Nama harus diisi';
                document.getElementById('namaError').style.display = 'block';
                isValid = false;
            }

            // Validasi Email
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

            // Validasi Telepon
            const telepon = document.getElementById('telepon').value.trim();
            const phonePattern = /^[0-9]{10,13}$/;
            if (!telepon) {
                document.getElementById('teleponError').textContent = 'Nomor telepon harus diisi';
                document.getElementById('teleponError').style.display = 'block';
                isValid = false;
            } else if (!phonePattern.test(telepon.replace(/[^0-9]/g, ''))) {
                document.getElementById('teleponError').textContent = 'Format nomor telepon tidak valid (10-13 digit)';
                document.getElementById('teleponError').style.display = 'block';
                isValid = false;
            }

            // Validasi Tanggal
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
                    firstError.scrollIntoView({behavior: 'smooth', block: 'center'});
                }
            } else {
                // Disable button dan tampilkan loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            }
        });

        // Set min date untuk input tanggal
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        
        // Format: YYYY-MM-DD
        const todayStr = today.toISOString().split('T')[0];
        const tomorrowStr = tomorrow.toISOString().split('T')[0];
        
        // Set min untuk checkin (hari ini)
        if (!checkinInput.value) {
            checkinInput.value = todayStr;
        }
        checkinInput.min = todayStr;
        
        // Set min untuk checkout (besok)
        if (!checkoutInput.value) {
            checkoutInput.value = tomorrowStr;
        }
        checkoutInput.min = tomorrowStr;
    </script>
</body>
</html>


