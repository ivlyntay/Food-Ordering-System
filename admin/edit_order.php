<?php
include('../connection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to edit an order.";
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM orders WHERE id=$id");
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        echo "Order not found";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $sql = "UPDATE orders SET item='$item', quantity='$quantity', price='$price', status='$status' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Order updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: all_order.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
    <link rel="stylesheet" href="../css/add_food.css">
    <link rel="stylesheet" href="../css/admin_main.css">
    <link rel="stylesheet" href="../css/edit_order.css">
</head>
<body>
    <div class="container">
        <h2>Edit Order</h2>
        <form action="edit_order.php?id=<?php echo $order['id']; ?>" method="post" enctype="multipart/form-data">

            <label for="item">Food Name:</label>
            <input type="text" id="item" name="item" value="<?php echo $order['item']; ?>" required>

            <label for="quantity">Quantity:</label>
            <input type="text" id="quantity" name="quantity" value="<?php echo $order['quantity']; ?>" required>
           
            <label for="price">Price:</label>
            <input type="text" id="price" name="price" value="<?php echo $order['price']; ?>" required>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Processing" <?php if ($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                <option value="Served" <?php if ($order['status'] == 'Served') echo 'selected'; ?>>Served</option>
                <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <input type="submit" value="Update Order">
        </form>
        <a href="all_order.php">Return to All Orders</a>
    </div>
</body>
</html>
