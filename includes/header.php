<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NikeShop Indonesia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Sesuaikan dengan path stylesheet Anda -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Tambahkan FontAwesome -->
    <style>
        html {
    position: relative;
    min-height: 100%;
}

body {
    margin-bottom: 40px; /* Tinggi footer */
    padding-bottom: 40px; /* Tinggi footer */
}

.footer {
    position: absolute;
    bottom: 10;
    width: 100%;
    height: 40px; /* Sesuaikan tinggi footer */
    line-height: 40px; /* Sesuaikan dengan tinggi footer */
    background-color: #f8f9fa; /* Warna latar belakang footer */
}
        .navbar-brand {
            font-size: 1.5rem;
        }
        .navbar-toggler {
            border: none;
            outline: none;
        }
        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath stroke="rgba(0, 0, 0, 0.5)" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">NikeShop ID</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../pages/all_products.php">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="../pages/cart.php">Keranjang</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="../pages/profile.php">Profil</a></li>
                            <a class="nav-link" href="../auth/logout.php">Keluar</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="../auth/login.php">Masuk</a></li>
                        <li class="nav-item"><a class="nav-link" href="../auth/register.php">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
