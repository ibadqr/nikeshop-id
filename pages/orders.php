<?php
session_start();
require '../php/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all orders from the database
$query = "SELECT o.order_id, o.user_id, u.username, o.total_price, o.order_date, o.status FROM orders o
          INNER JOIN users u ON o.user_id = u.id";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        .status-pending i {
            color: #ffc107; /* Warna kuning untuk pending */
        }
        .status-success i {
            color: #28a745; /* Warna hijau untuk success */
        }
        .status-canceled i {
            color: #dc3545; /* Warna merah untuk canceled */
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5">
        <h2>Manajemen Order</h2>
        <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
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
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo number_format($row['total_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                        <td class="status-<?php echo htmlspecialchars($row['status']); ?>">
                            <?php if ($row['status'] == 'pending'): ?>
                                <i class="fas fa-clock"></i>
                            <?php elseif ($row['status'] == 'success'): ?>
                                <i class="fas fa-check-circle"></i>
                            <?php else: ?>
                                <i class="fas fa-times-circle"></i>
                            <?php endif; ?>
                            <form action="update_order_status.php" method="post" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                <select name="status" class="form-control d-inline-block" style="width: auto;" onchange="this.form.submit()">
                                    <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Belum Dibayar</option>
                                    <option value="success" <?php if ($row['status'] == 'success') echo 'selected'; ?>>Sukses</option>
                                    <option value="canceled" <?php if ($row['status'] == 'canceled') echo 'selected'; ?>>Dibatalkan</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <a href="view_order.php?order_id=<?php echo htmlspecialchars($row['order_id']); ?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                            <form action="delete_order.php" method="post" style="display: inline-block;">
                                <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <div class="text-right"><a href="admin.php" >Kembali ke Home</a></div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>