<?php
session_start();
require '../php/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch user orders from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT order_id, total_price, order_date, status FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h2>Pesanan Saya</h2>
        <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Harga</th>
                    <th>Tanggal Order</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td><?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                        <td class="status-<?php echo $row['status']; ?>">
                            <?php if ($row['status'] == 'pending'): ?>
                                <i class="fas fa-stopwatch" style="color: #ffc107;"></i> Belum Dibayar
                            <?php elseif ($row['status'] == 'success'): ?>
                                <i class="fas fa-check-circle" style="color: #28a745;"></i> Sukses
                            <?php elseif ($row['status'] == 'canceled'): ?>
                                <i class="fas fa-times-circle" style="color: #dc3545;"></i> Dibatalkan 
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="view_user_order.php?order_id=<?php echo $row['order_id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <a href="user.php" class="btn btn-secondary">Kembali ke Home</a>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>