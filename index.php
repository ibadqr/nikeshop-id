<?php
session_start();
require 'php/db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Arahkan pengguna berdasarkan peran mereka
if ($_SESSION['role'] == 'admin') {
    header("Location: pages/admin.php");
    exit();
} elseif ($_SESSION['role'] == 'user') {
    header("Location: pages/user.php");
    exit();
}
?>