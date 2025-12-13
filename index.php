<?php

if (!isset($_COOKIE['token'])) {
    header('location: login.php');
}

header('location: dashboard.php');
