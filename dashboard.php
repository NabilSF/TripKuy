<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'vendor/autoload.php';
require 'koneksi.php';

$secret = 'budiman';

if (isset($_COOKIE['token'])) {
    $decoded = JWT::decode($_COOKIE['token'], new Key($secret, 'HS256'));
    $id = $decoded->id_user;
    $res = mysqli_query($conn, "SELECT id_user,nama,email,no_telepon FROM users WHERE id_user=$id");
    $data = mysqli_fetch_assoc($res);
    echo "<strong>ID</strong>: " . $data['id_user'];
    echo "<br><strong>Nama</strong>: " . $data['nama'];
    echo "<br><strong>Email</strong>: " . $data['email'];
    echo "<br><strong>Telepon</strong>: " . $data['no_telepon'];
} else {
    header('location: login.php');
}
