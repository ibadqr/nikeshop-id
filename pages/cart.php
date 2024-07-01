<?php
session_start();
require '../php/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Initialize totalPrice variable
$totalPrice = 0;

// Fetch products in cart from database
$user_id = $_SESSION['user_id'];
$query = "SELECT cart.id, products.name, products.price, cart.quantity, (products.price * cart.quantity) AS total FROM cart INNER JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if cart is empty
$cart_empty = $result->num_rows === 0;

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $product_id = $_POST['delete_product'];
    $delete_query = "DELETE FROM cart WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $product_id);
    $delete_stmt->execute();
    header("Location: cart.php");
    exit();
}

// Handle quantity update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $update_query = "UPDATE cart SET quantity = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ii", $quantity, $product_id);
    $update_stmt->execute();
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <title>Cart</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h2>Keranjang Saya</h2>
        <?php if (!$cart_empty): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo number_format($row['price']); ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" max="99" class="form-control" style="width: 60px; display: inline-block;">
                                        <button type="submit" name="update_quantity" class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"></i></button>
                                    </form>
                                </td>
                                <td><?php echo number_format($row['total']); ?></td>
                                <td>
                                    <form action="" method="post">
                                        <button type="submit" name="delete_product" value="<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php $totalPrice += ($row['price'] * $row['quantity']); ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <div class="text-right">
                <h5>Total Harga: Rp. <?php echo number_format($totalPrice, 2); ?></h5>
            </div>
                <a href="checkout.php" class="btn btn-success">Proses Checkout</a>
            </div>
        <?php else: ?>
            <p>Keranjangmu Kosong Nih, <a href="../pages/all_products.php">Mulai Belanja</a>!</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>