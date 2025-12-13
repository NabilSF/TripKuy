<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

const secret = 'budiman';

function sign_jwt($id_user)
{
    $issuedAt = time();
    $expire = $issuedAt + (3 * 24 * 60); // 3 Hari

    $payload = [
        'iss' => 'http://localhost',
        'iat' => $issuedAt,
        'exp' => $expire,
        'id_user' => $id_user
    ];

    $cookieOptions = [
        'path' => '/',
        'expires' => $expire,
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ];
    return [
        JWT::encode($payload, secret, 'HS256'),
        $cookieOptions
    ];
}

function verify_jwt() {}
