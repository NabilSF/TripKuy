<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user']['id'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='login.php';</script>";
    exit();
}
$data = mysqli_query($conn, "
    SELECT h.*, 
           tk.harga as harga_kamar,
           tk.nama_kamar,
           tk.kapasitas_orang
    FROM hotel h
    LEFT JOIN (
        SELECT id_hotel, MIN(harga) as min_harga
        FROM tipe_kamar 
        GROUP BY id_hotel
    ) as min_harga ON h.id_hotel = min_harga.id_hotel
    LEFT JOIN tipe_kamar tk ON h.id_hotel = tk.id_hotel AND tk.harga = min_harga.min_harga
    ORDER BY h.id_hotel
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripKuy | Hotel Terbaik di Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    :root {
        --primary: #2aa090;
        --secondary: #222;
        --text-dark: #222;
        --text-light: #717171;
        --light-gray: #f7f7f7;
        --gray: #ddd;
        --radius: 12px;
        --shadow: 0 6px 16px rgba(0,0,0,0.12);
        --transition: all 0.2s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: var(--text-dark);
        line-height: 1.5;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    header {
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
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
        background-color: #2aa090;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #fff;
    }

    .logo h1 {
        font-size: 28px;
        font-weight: 700;
        color: #222;
    }

    .logo span {
        color: #2aa090;
    }

    nav ul {
        display: flex;
        list-style: none;
        gap: 25px;
    }

    nav a {
        text-decoration: none;
        color: #666;
        font-weight: 500;
        transition: color 0.3s;
        padding: 5px 0;
    }

    nav a:hover {
        color: var(--primary);
    }

    .account-button {
        background-color: #fff;
        color: #666;
        border: 1px solid #ddd;
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
        background-color: #222;
        color: #fff;
        border-color: #222;
    }

    .hero {
        position: relative;
        background: linear-gradient(135deg, #1d856eff, #2a75a0ff);
        color: #fff;
        padding: 80px 40px;
        margin-bottom: 40px;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1920') center/cover;
        opacity: 0.2;
    }

    .hero-content {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }

    .hero h1 {
        font-size: 48px;
        margin-bottom: 16px;
        font-weight: 700;
    }

    .hero p {
        font-size: 20px;
        opacity: 0.9;
        margin-bottom: 30px;
        font-weight: 400;
        color: var(--light-gray);
    }

    .hero-badges {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }

    .badge {
        font-weight: 500;
        font-size: 14px;
    }

    .booking-nav {
        margin: -40px 40px 30px;
        display: flex;
        justify-content: center;
    }

    .booking-wrapper {
        max-width: 900px;
        width: 100%;
    }

    .booking-form {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 40px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        padding: 10px;
        gap: 4px;
    }

    .booking-item {
        flex: 1;
        padding: 10px 18px;
        border-radius: 40px;
        cursor: pointer;
        transition: var(--transition);
    }

    .booking-item:hover {
        background: var(--light-gray);
    }

    .booking-item label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-light);
        margin-bottom: 3px;
        font-weight: 600;
    }

    .booking-item input {
        border: none;
        outline: none;
        font-size: 14px;
        width: 100%;
        background: transparent;
        color: var(--text-dark);
        font-weight: 500;
    }

    .booking-submit {
        background: var(--primary);
        border: none;
        color: #fff;
        border-radius: 50px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: var(--transition);
    }

    .booking-submit i {
        background: #fff;
        color: var(--primary);
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .booking-submit:hover {
        background: #13665bff;
    }

    .filters {
        max-width: 1400px;
        margin: 0 auto 30px;
        padding: 0 40px;
    }

    .filter-tabs {
        display: flex;
        gap: 20px;
        border-bottom: 1px solid var(--gray);
        padding-bottom: 15px;
    }

    .filter-tab {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 30px;
        background: #fff;
        border: 1px solid var(--gray);
        cursor: pointer;
        transition: var(--transition);
        font-weight: 500;
        font-size: 14px;
    }

    .filter-tab.active {
        background: var(--text-dark);
        color: #fff;
        border-color: var(--text-dark);
        font-weight: 600;
    }

    .filter-tab:hover {
        background: var(--light-gray);
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 40px 60px;
    }

    .hotel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .hotel-card {
        background: #fff;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: var(--transition);
        cursor: pointer;
        position: relative;
    }

    .hotel-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow);
    }

    .card-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .hotel-card:hover .card-image img {
        transform: scale(1.05);
    }

    .wishlist-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255,255,255,0.9);
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .wishlist-btn:hover {
        background: #fff;
        transform: scale(1.1);
    }

    .rating {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(0,0,0,0.7);
        color: #fff;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .rating i {
        color: #FFB700;
    }

    .card-content {
        padding: 20px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .card-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 12;
        line-height: 1.3;
    }

    .price {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
    }

    .price span {
        font-size: 14px;
        font-weight: 400;
        color: var(--text-light);
    }

    .location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-light);
        font-size: 14px;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .location i {
        color: var(--primary);
    }

    p {
        font-size: 14px;
        color: var(--text-dark);
        line-height: 1.6;
    }

    .facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--gray);
    }

    .facility {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: var(--text-light);
        font-weight: 500;
    }

    .card-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
    }

    .btn-detail {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }

    .btn-detail:hover {
        background: #13665bff;
    }

    footer {
        background: var(--light-gray);
        padding: 40px;
        text-align: center;
    }

    footer h3 {
        font-weight: 600;
        margin-bottom: 15px;
    }

    footer ul {
        list-style: none;
    }

    footer ul li a {
        text-decoration: none;
        color: var(--text-dark);
        font-weight: 400;
    }

    footer ul li a:hover {
        color: var(--primary);
    }

    @media (max-width: 992px) {
        .hero h1 {
            font-size: 36px;
        }
        
        .hero p {
            font-size: 18px;
        }
        
        .booking-form {
            flex-wrap: wrap;
        }
        
        .booking-item {
            flex: 1 1 50%;
        }
        
        .booking-submit {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .hero {
            padding: 50px 20px;
        }
        
        .hero h1 {
            font-size: 28px;
        }
        
        .container {
            padding: 0 20px 40px;
        }
        
        .booking-nav {
            margin: -20px 20px 20px;
        }
        
        .filter-tabs {
            flex-wrap: wrap;
        }
        
        .hotel-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 576px) {
        .booking-item {
            flex: 1 1 100%;
        }
        
        .hero-badges {
            flex-direction: column;
            gap: 10px;
        }
    }

    /* Responsive Header */
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
    }
    </style>
</head>
<body>

<header>
  <div class="container">
    <div class="header-content">

      <!-- Logo -->
      <div class="logo">
        <div class="logo-icon"><i class="fas fa-suitcase-rolling"></i></div>
        <h1>Trip<span>Kuy</span></h1>
      </div>

      <!-- Navigasi -->
      <nav>
        <ul>
          <li><a href="home.php">Beranda</a></li>
          <li><a href="hotels.php">Hotel</a></li>
          <li><a href="#deals">Penawaran</a></li>
          <li><a href="#help">Bantuan</a></li>
        </ul>
      </nav>

      <!-- Tombol akun -->
      <button class="account-button" id="accountButton">
        <i class="fas fa-user"></i>
      </button>

    </div>
  </div>
</header>

<section class="hero">
    <div class="hero-content">
        <h1>Dunia Adalah Tempatmu Berpijak</h1>
        <p>Pengalaman menginap tak terlupakan dengan fasilitas premium</p>
        <div class="hero-badges">
            <div class="badge"><i class="fas fa-shield-alt"></i> Keamanan Terjamin</div>
            <div class="badge"><i class="fas fa-star"></i> Rating 4.8+</div>
            <div class="badge"><i class="fas fa-headset"></i> Dukungan 24/7</div>
        </div>
    </div>
</section>

<section class="booking-nav">
    <div class="booking-wrapper">
        <form class="booking-form">
            <div class="booking-item"><label>Lokasi</label><input type="text" placeholder="Ke mana?"></div>
            <div class="booking-item"><label>Check-in</label><input type="date"></div>
            <div class="booking-item"><label>Check-out</label><input type="date"></div>
            <div class="booking-item"><label>Tamu</label><input type="number" value="1"></div>
            <button class="booking-submit"><i class="fas fa-search"></i><span>Cari</span></button>
        </form>
    </div>
</section>

<div class="filters">
    <div class="filter-tabs">
        <div class="filter-tab active"><i class="fas fa-hotel"></i>Semua</div>
        <div class="filter-tab"><i class="fas fa-crown"></i>Luxury</div>
        <div class="filter-tab"><i class="fas fa-umbrella-beach"></i>Resort</div>
        <div class="filter-tab"><i class="fas fa-home"></i>Villa</div>
    </div>
</div>

<div class="container">
    <div class="hotel-grid">
        <?php 
        while ($h = mysqli_fetch_assoc($data)): 
            // Format harga dari database
            $price = $h['harga_kamar'] ? number_format($h['harga_kamar'], 0, ',', '.') : '1.500.000';
            
            // Generate rating (tetap random seperti sebelumnya)
            $rating = rand(40, 50) / 10;
            
            // Info kamar
            $kamar_nama = $h['nama_kamar'] ?: 'Deluxe Room';
            $kapasitas = $h['kapasitas_orang'] ?: 2;
        ?>
        <div class="hotel-card">
            <div class="card-image">
                <img src="https://source.unsplash.com/random/400x300/?hotel,<?= urlencode($h['nama_hotel']) ?>" alt="<?= htmlspecialchars($h['nama_hotel']) ?>">
                <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                <div class="rating"><i class="fas fa-star"></i><span><?= number_format($rating, 1) ?></span></div>
            </div>
            <div class="card-content">
                <div class="card-header">
                    <h3 class="card-title"><?= htmlspecialchars($h['nama_hotel']) ?></h3>
                    <div class="price">Rp<?= $price ?><span>/malam</span></div>
                </div>
                <div class="location">
                    <i class="fas fa-map-marker-alt"></i>
                    <?= htmlspecialchars($h['alamat']) ?>
                </div>
                <p><?= htmlspecialchars($h['deskripsi']) ?></p>
                <div class="facilities">
                    <div class="facility"><i class="fas fa-wifi"></i><span>WiFi</span></div>
                    <div class="facility"><i class="fas fa-swimming-pool"></i><span>Pool</span></div>
                    <div class="facility"><i class="fas fa-utensils"></i><span>Restoran</span></div>
                    <div class="facility"><i class="fas fa-bed"></i><span><?= $kamar_nama ?></span></div>
                    <div class="facility"><i class="fas fa-user"></i><span><?= $kapasitas ?> orang</span></div>
                </div>
                <div class="card-actions">
                    <a href="detail.php?id=<?= $h['id_hotel'] ?>" class="btn-detail">Lihat Detail</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<footer>
    <div style="max-width:1400px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:40px">
        <div><h3>TripKuy</h3><ul><li><a href="#">Tentang</a></li><li><a href="#">Karir</a></li><li><a href="#">Blog</a></li></ul></div>
        <div><h3>Destinasi</h3><ul><li><a href="#">Jakarta</a></li><li><a href="#">Bali</a></li><li><a href="#">Bandung</a></li></ul></div>
        <div><h3>Partner</h3><ul><li><a href="#">Jadi Partner</a></li><li><a href="#">Afiliasi</a></li></ul></div>
        <div><h3>Lainnya</h3><ul><li><a href="#">Syarat</a></li><li><a href="#">Privasi</a></li></ul></div>
    </div>
    <div style="padding:20px;color:var(--text-light)">&copy; 2024 TripKuy. All rights reserved.</div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wishlist functionality
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#FF385C';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '';
            }
        });
    });

    // Filter tabs
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Search functionality
    const searchBtn = document.querySelector('.booking-submit');
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const locationInput = document.querySelector('.booking-item input[type="text"]');
            const query = locationInput.value.trim();
            if (query) {
                alert(`Mencari hotel di: ${query}`);
            }
        });
    }
});
</script>

</body>
</html>
