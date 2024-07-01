<?php
session_start();
require '../php/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch order details from the database
$order_id = $_GET['order_id'];
$order_query = "SELECT o.order_id, o.total_price, oi.product_id, p.name, oi.quantity, oi.price, oi.total 
                FROM orders o
                INNER JOIN order_items oi ON o.order_id = oi.order_id
                INNER JOIN products p ON oi.product_id = p.id
                WHERE o.order_id = ?";
$order_stmt = $conn->prepare($order_query);

if (!$order_stmt) {
    die("Error preparing statement: " . $conn->error);
}

$order_stmt->bind_param("s", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

// Fetch total price from the orders table
$total_price_query = "SELECT total_price FROM orders WHERE order_id = ?";
$total_price_stmt = $conn->prepare($total_price_query);

if (!$total_price_stmt) {
    die("Error preparing statement: " . $conn->error);
}

$total_price_stmt->bind_param("s", $order_id);
$total_price_stmt->execute();
$total_price_result = $total_price_stmt->get_result();
$total_price_row = $total_price_result->fetch_assoc();
$totalPrice = $total_price_row['total_price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <title>Konfirmasi Order</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h2>Invoice Order</h2>
        <?php if ($order_result->num_rows > 0): ?>
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
                        <?php while ($row = $order_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['product_id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo number_format($row['price']); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo number_format($row['total']); ?></td>
                            </tr>
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
                    <button class="btn btn-primary" onclick="printInvoice()">Print Invoice</button>
                    <button class="btn btn-success" onclick="shareToWhatsApp()">Share to WhatsApp</button>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center">Order Tidak Ditemukan. <a href="all_products.php">Silahkan Belanja</a>.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function printInvoice() {
            var printContents = document.getElementById('invoice').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        function shareToWhatsApp() {
            var total = '<?php echo number_format($totalPrice, 2); ?>';
            var message = `*NikeShop ID Invoice*

Saya telah melakukan pemesanan produk dari NikeShop ID dengan jumlah total _*Rp ${total}*_.

Metode Pembayaran Transfer Bank:
Bank: BRI
No. Rekening: 3659987523509
A/n: Ibad QR`;
            var url = `https://wa.me/?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
