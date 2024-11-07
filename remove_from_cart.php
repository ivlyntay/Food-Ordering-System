<?php
session_start();
require_once("connection.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Remove item from session cart
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }

    // Remove item from database
    $sql = "DELETE FROM user_cart_items WHERE menu_items_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        // Redirect back to the cart page
        header("Location: mycart.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
