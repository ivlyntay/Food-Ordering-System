<?php
include("../connection.php");

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    header("Location: ../login.php");
    exit(); // Ensure the script stops executing after redirect
}

$menu_items = $conn->query("
    SELECT o.id, u.username, o.item, o.quantity, o.price, o.status, o.date
    FROM orders o
    JOIN user u ON o.user_id = u.id"
);

if (!$menu_items) {
    echo "Error: " . $conn->error; // Display the SQL error for debugging purposes
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders</title>
    <link rel="stylesheet" href="../css/add_food.css">
    <link rel="stylesheet" href="../css/admin_main.css">
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
        <h2>All Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Food name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Ordered Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $menu_items->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['item']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td>
                            <a href="edit_order.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="delete_order.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
