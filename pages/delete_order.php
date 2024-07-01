<?php
session_start();
require '../php/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Delete order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    // Delete order items
    $delete_order_items_query = "DELETE FROM order_items WHERE order_id = ?";
    $delete_order_items_stmt = $conn->prepare($delete_order_items_query);
    $delete_order_items_stmt->bind_param("s", $order_id);
    $delete_order_items_stmt->execute();

    // Delete order
    $delete_order_query = "DELETE FROM orders WHERE order_id = ?";
    $delete_order_stmt = $conn->prepare($delete_order_query);
    $delete_order_stmt->bind_param("s", $order_id);
    if ($delete_order_stmt->execute()) {
        header("Location: orders.php");
        exit();
    } else {
        echo "Error deleting order: " . $conn->error;
    }
}
?>