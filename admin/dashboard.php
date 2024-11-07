<?php
include("../connection.php");

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    header("Location: ../login.php");
    exit(); // Ensure the script stops executing after redirect
}

// Get the number of dishes from the menu_items table
$dishes_result = $conn->query("SELECT COUNT(*) AS totalDishes FROM menu_items");
$totalDishes = $dishes_result->fetch_assoc()['totalDishes'];

// Get the number of customers from the user table
$users_result = $conn->query("SELECT COUNT(*) AS totalCustomers FROM user WHERE role <> 'admin'");
$totalCustomers = $users_result->fetch_assoc()['totalCustomers'];

// Get the number of orders from the orders table
$orders_result = $conn->query("SELECT COUNT(*) AS totalOrders FROM orders");
$totalOrders = $orders_result->fetch_assoc()['totalOrders'];

// Get the number of processing orders from the orders table where status is 'pending'
$processing_orders_result = $conn->query("SELECT COUNT(*) AS processingOrders FROM orders WHERE status='Processing'");
$processingOrders = $processing_orders_result->fetch_assoc()['processingOrders'];

// Get the number of served orders from the orders table where status is 'served'
$served_orders_result = $conn->query("SELECT COUNT(*) AS servedOrders FROM orders WHERE status='Served'");
$servedOrders = $served_orders_result->fetch_assoc()['servedOrders'];

// Get the number of cancelled orders from the orders table where status is 'cancelled'
$cancelled_orders_result = $conn->query("SELECT COUNT(*) AS cancelledOrders FROM orders WHERE status='Cancelled'");
$cancelledOrders = $cancelled_orders_result->fetch_assoc()['cancelledOrders'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/add_food.css">
    <link rel="stylesheet" href="../css/admin_main.css">
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<header>
    <img src="../img/logo2.png" height="50px" alt="logo2">
    <nav>
        <a href="dashboard.php">DASHBOARD</a>
        <a href="user_management.php">USER</a>
        <a href="add_fooditem.php">FOOD</a>
        <a href="all_order.php">ORDER</a>
        <a href="../logout.php">Logout</a>
    </nav>
</header>
<body>
<div class="container">
    <div class="dashboard">
        <h1 class="dashboard-title">Admin Dashboard</h1>
        <div class="dashboard-cards">
            <div class="card">
                <img src="../img/dashboard/dishes.png" alt="Dishes">
                <h3><?php echo $totalDishes; ?></h3>
                <p>Dishes</p>
            </div>
            <div class="card">
                <img src="../img/dashboard/user.png" alt="Customers">
                <h3><?php echo $totalCustomers; ?></h3>
                <p>Customers</p>
            </div>
        </div>
        <div class="dashboard-cards-secondrow">
            <div class="card">
                <img src="../img/dashboard/load.png" alt="Processing Orders">
                <h3><?php echo $processingOrders; ?></h3>
                <p>Processing Orders</p>
            </div>
            <div class="card">
                <img src="../img/dashboard/tick.png" alt="Served Orders">
                <h3><?php echo $servedOrders; ?></h3>
                <p>Served Orders</p>
            </div>
            <div class="card">
                <img src="../img/dashboard/wrong.png" alt="Cancelled Orders">
                <h3><?php echo $cancelledOrders; ?></h3>
                <p>Cancelled Orders</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
