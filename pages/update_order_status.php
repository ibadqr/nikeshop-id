<?php
session_start();
require '../php/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Update order status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $query = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status, $order_id);
    if ($stmt->execute()) {
        header("Location: orders.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>