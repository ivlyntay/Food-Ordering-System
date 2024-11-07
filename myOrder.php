<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the username from the database
$sql = "SELECT username FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

$sql = "SELECT item, quantity, price, date, status 
        FROM orders 
        WHERE user_id = ?
        ORDER BY date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['date']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/myorder.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md container-fluid">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="img/logo2.png" height="50px" alt="logo2">
                </a>
                <div class="welcome-message col">Welcome, <?php echo $username; ?>!</div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end align-items-center" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="foodmenu.php">Food Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="mycart.php">Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="myOrder.php">My Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>                   
                    </ul>
                </div>
            </div>
        </nav>
    </header>
<main class="container">
    <h1>My Orders</h1>
    <?php if (!empty($orders)) { ?>
        <?php foreach ($orders as $date => $orderGroup) { 
            $subtotal = 0; ?>
            <div class="table-responsive">
                <table class="table orders-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderGroup as $order) { 
                            if ($order['status'] !== 'Cancelled') {
                                $subtotal += $order['price'] * $order['quantity'];
                            } ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['item']); ?></td>
                                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                <td>RM<?php echo number_format($order['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['date']); ?></td>
                                <td style="position:relative"><span class="badge <?php echo htmlspecialchars($order['status']); ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                <!-- <td><?php echo htmlspecialchars($order['status']); ?></td> -->
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <?php
                        $serviceTax = $subtotal * 0.06;
                        $total = $subtotal + $serviceTax;
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: right;"><strong>Subtotal:</strong></td>
                            <td>RM<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right;"><strong>Service Tax (6%):</strong></td>
                            <td>RM<?php echo number_format($serviceTax, 2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                            <td>RM<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br>
        <?php } ?>
    <?php } else { ?>
        <p>You have no orders.</p>
    <?php } ?>
</main>
</body>
</html>
