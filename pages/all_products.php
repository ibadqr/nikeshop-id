<?php
session_start();
require '../php/db.php';

// Handle Add to Cart action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }

    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = 1; // Default quantity

    // Insert into cart table
    $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity); // Assuming quantity is an integer
    if ($stmt->execute()) {
        // Redirect back to all_products.php after adding to cart
        header("Location: all_products.php");
        exit();
    } else {
        echo "Error: " . $stmt->error; // Display specific error message for debugging
        exit();
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
    <title>Semua Produk</title>
</head>
<body>
    <?php include '../includes/header.php'; ?> <!-- Include your header file -->

    <div class="container mt-5">
        <h2 class="mb-4">Semua Produk</h2>
        <form action="all_products.php" method="GET" class="form-inline mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search_query); ?>">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="card-text">Harga: Rp <?php echo number_format($row['price']); ?></p>
                                <form action="" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button class="btn btn-success btn-sm" type="submit" name="add_to_cart"><i class="fas fa-shopping-cart"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>    

    <?php include '../includes/footer.php'; ?> <!-- Include your footer file -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script> <!-- Icon Font Awesome -->
</body>
</html>