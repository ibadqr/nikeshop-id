<?php
session_start();
require '../php/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch products in the cart from the database
$query = "SELECT p.id, p.name, p.price, c.quantity, (p.price * c.quantity) AS total FROM products p
          INNER JOIN cart c ON p.id = c.product_id
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$totalPrice = 0; // Variable to store total price of all products

// Generate a unique order ID
$order_id = uniqid();

// Handle the checkout process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recalculate total price
    $result->data_seek(0); // Reset result pointer to iterate again
    while ($row = $result->fetch_assoc()) {
        $totalPrice += $row['total'];
    }

    // Insert order details into orders table
    $insert_order_query = "INSERT INTO orders (order_id, user_id, total_price) VALUES (?, ?, ?)";
    $insert_order_stmt = $conn->prepare($insert_order_query);
    
    if (!$insert_order_stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $insert_order_stmt->bind_param("sid", $order_id, $_SESSION['user_id'], $totalPrice);
    
    if (!$insert_order_stmt->execute()) {
        die("Error executing statement: " . $insert_order_stmt->error);
    }

    // Reset result pointer to iterate again
    $result->data_seek(0);

    // Insert order items into order_items table
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['id'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $total = $row['total'];

        $insert_order_items_query = "INSERT INTO order_items (order_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)";
        $insert_order_items_stmt = $conn->prepare($insert_order_items_query);
        
        if (!$insert_order_items_stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        
        $insert_order_items_stmt->bind_param("siidd", $order_id, $product_id, $quantity, $price, $total);
        
        if (!$insert_order_items_stmt->execute()) {
            die("Error executing statement: " . $insert_order_items_stmt->error);
        }
    }

    // Clear the cart
    $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $clear_cart_stmt = $conn->prepare($clear_cart_query);
    
    if (!$clear_cart_stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    
    $clear_cart_stmt->bind_param("i", $_SESSION['user_id']);
    
    if (!$clear_cart_stmt->execute()) {
        die("Error executing statement: " . $clear_cart_stmt->error);
    }

    // Redirect to order confirmation page
    header("Location: order_confirmation.php?order_id=$order_id");
    exit();
}

// Reset result pointer to iterate again
$result->data_seek(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <title>Checkout</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h2>Checkout</h2>
        <?php if ($result->num_rows > 0): ?>
            <form action="" method="POST">
                <div id="invoice">
                    <div class="table-responsive">
                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo number_format($row['price']); ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo number_format($row['total']); ?></td>
                                </tr>
                                <?php $totalPrice += $row['total']; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    </div>
                    <div class="text-right">
                        <h5>Total Harga: <?php echo number_format($totalPrice, 2); ?></h5>
                    </div>
                    <div class="mt-4">
                        <h5>Informasi Pembayaran:</h5>
                        <p>Silahkan lakukan pembayaran metode transfer Bank:</p>
                        <p>Bank: BRI</p>
                        <p>No. Rekening: 3659987523509</p>
                        <p>A/n: Ibad QR</p>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Konfirmasi Order</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <p class="text-center">Keranjangmu kosong, silahkan belanja dulu <a href="all_products.php">disini</a>.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
