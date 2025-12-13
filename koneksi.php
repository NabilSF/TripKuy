<?php

$host = 'localhost';
$user = 'rin';
$pwd = 'blank';
$db = 'tripkuy';

$conn = mysqli_connect($host, $user, $pwd, $db);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
