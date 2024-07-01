<?php
session_start();
require '../php/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Handle add to cart action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = 1; // Default quantity

    // Check if product is already in cart
    $query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if product is already in cart
        $query = "UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
    } else {
        // Add new product to cart
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }

    if (!$stmt->execute()) {
        echo "Error: " . $conn->error;
    }
}

// Fetch products from the database
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $query = "SELECT * FROM products WHERE name LIKE ?";
    $stmt = $conn->prepare($query);
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param("s", $search_term);
} else {
    $query = "SELECT * FROM products";
    $stmt = $conn->prepare($query);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Icon Font Awesome -->
    <title>User Home</title>
</head>
<body>
    <?php include '../includes/header.php'; ?> <!-- Termasuk file header Anda -->

    <div class="container mt-5">
        <div class="jumbotron">
        <div class="text-center">
            <h1 class="display-4">Selamat Datang di NikeShop ID!</h1>
            <p class="lead">Dapatkan Produk Nike Original Hanya di NikeShop Indonesia. Happy Shopping!</p>
            <hr class="my-4">
            <form action="user.php" method="GET" class="form-inline-center">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Produk..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
</br>
            <a href="all_products.php" class="btn btn-success"><i class="fas fa-shopping-bag"></i> Semua Produk</a>
            <a href="user_order.php" class="btn btn-warning"><i class="fas fa-box"></i> Pesanan Saya</a>
            </div>
        </div>

        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="card-text">Harga: Rp <?php echo number_format ($row['price']); ?></p>
                                <form action="user.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-shopping-cart"></i> Tambah ke Keranjang</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?> <!-- Termasuk file footer Anda -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script> <!-- Icon Font Awesome -->
</body>
</html>