<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Example SQL to insert into orders table
$insertOrder = "INSERT INTO orders (item, quantity, price, date, status, user_id) 
                SELECT mi.name, uc.quantity, mi.price, NOW(), 'Processing', uc.user_id 
                FROM user_cart_items uc
                JOIN menu_items mi ON uc.menu_items_id = mi.id
                WHERE uc.user_id = ?";

$stmt = $conn->prepare($insertOrder);
$stmt->bind_param("i", $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Clear the cart after successful order placement
    $clearCart = "DELETE FROM user_cart_items";
    $conn->query($clearCart);

    // Set success message and redirect
    $_SESSION['order_success'] = "Order placed successfully!";
    header("Location: mycart.php");
    exit();
} else {
    // Set error message and redirect
    $_SESSION['order_error'] = "Error placing order: " . $conn->error;
    header("Location: mycart.php");
    exit();
}

$stmt->close();
$conn->close();
?>
