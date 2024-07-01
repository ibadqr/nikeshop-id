<?php
function getAllProducts($conn) {
    $query = "SELECT * FROM products";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>