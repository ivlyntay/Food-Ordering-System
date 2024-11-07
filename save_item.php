<?php
require_once("connection.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['itemId'];
    $quantity = $_POST['quantity'];

    // Sanitize inputs
    $item_id = intval($item_id);
    $quantity = intval($quantity);

    // Check if the item already exists in the cart
    $check_sql = "SELECT quantity FROM user_cart_items WHERE menu_items_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $item_id, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Item exists, update the quantity
        $update_sql = "UPDATE user_cart_items SET quantity = quantity + ? WHERE menu_items_id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $quantity, $item_id, $user_id);

        if ($update_stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error: " . $update_sql . "<br>" . $conn->error;
        }

        $update_stmt->close();
    } else {
        // Item does not exist, insert a new record
        $insert_sql = "INSERT INTO user_cart_items (menu_items_id, user_id, quantity) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iii", $item_id, $user_id, $quantity);

        if ($insert_stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
}

$conn->close();
?>
