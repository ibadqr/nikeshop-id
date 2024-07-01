<?php
session_start();
require '../php/db.php';

// Cek apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Ambil data dari database
$productCountQuery = "SELECT COUNT(*) as count FROM products";
$productCountResult = $conn->query($productCountQuery);
$productCount = $productCountResult->fetch_assoc()['count'];

$userCountQuery = "SELECT COUNT(*) as count FROM users";
$userCountResult = $conn->query($userCountQuery);
$userCount = $userCountResult->fetch_assoc()['count'];

$orderCountQuery = "SELECT COUNT(*) as count FROM orders"; // Asumsi ada tabel orders
$orderCountResult = $conn->query($orderCountQuery);
$orderCount = $orderCountResult->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .card-counter {
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            padding: 20px 10px;
            background-color: #fff;
            height: 100px;
            border-radius: 5px;
            transition: .3s linear all;
        }
        .card-counter:hover {
            box-shadow: 4px 4px 20px #DADADA;
            transform: scale(1.05);
        }
        .card-counter.primary {
            background-color: #007bff;
            color: #FFF;
        }
        .card-counter.success {
            background-color: #28a745;
            color: #FFF;
        }
        .card-counter.info {
            background-color: #17a2b8;
            color: #FFF;
        }
        .card-counter i {
            font-size: 5em;
            opacity: 0.2;
        }
        .card-counter .count-numbers {
            position: absolute;
            right: 35px;
            top: 20px;
            font-size: 32px;
            display: block;
        }
        .card-counter .count-name {
            position: absolute;
            right: 35px;
            top: 65px;
            font-style: italic;
            text-transform: capitalize;
            opacity: 0.5;
            display: block;
            font-size: 18px;
        }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container my-5">
    <h1 class="text-center">Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="card-counter primary">
                <i class="fa fa-database"></i>
                <span class="count-numbers"><?php echo $productCount; ?></span>
                <span class="count-name">Products</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-counter success">
                <i class="fa fa-users"></i>
                <span class="count-numbers"><?php echo $userCount; ?></span>
                <span class="count-name">Users</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-counter info">
                <i class="fa fa-shopping-cart"></i>
                <span class="count-numbers"><?php echo $orderCount; ?></span>
                <span class="count-name">Orders</span>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <a href="add_product.php" class="btn btn-primary btn-block"><i class="fas fa-plus-circle"></i> Buat Produk Baru</a>
        </div>
        <div class="col-md-6">
            <a href="product.php" class="btn btn-secondary btn-block"><i class="fas fa-list"></i> Manajemen Produk</a>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <a href="manage_users.php" class="btn btn-success btn-block"><i class="fas fa-user-cog"></i> Manajemen User</a>
        </div>
        <div class="col-md-6">
            <a href="orders.php" class="btn btn-info btn-block"><i class="fas fa-shopping-cart"></i> Manajemen Orders</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
