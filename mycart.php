<!DOCTYPE html>
<html lang="en">
<?php
require_once("connection.php");
session_start();

// Check for order success message
if (isset($_SESSION['order_success'])) {
    echo "<script>alert('" . $_SESSION['order_success'] . "');</script>";
    unset($_SESSION['order_success']); // Clear success message
}

// Check for order error message
if (isset($_SESSION['order_error'])) {
    echo "<script>alert('" . $_SESSION['order_error'] . "');</script>";
    unset($_SESSION['order_error']); // Clear error message
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    header("Location: login.php");
    exit();
}

// Fetch the username from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="css/mycart.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
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
    <div class="container-fluid">
    <div class="row m-2">
        <div class="col-sm-8 col-12 border mb-2">
            <h2 class="text-center m-2">My Cart</h2>
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col" class="table-header">Items</th>
                            <th scope="col" class="table-header">Price</th>
                            <th scope="col" class="table-header">Quantity</th>
                            <th scope="col" class="table-header">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT uc.menu_items_id, uc.quantity, mi.name, mi.price, mi.image 
                                FROM user_cart_items uc
                                JOIN menu_items mi ON uc.menu_items_id = mi.id
                                WHERE uc.user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <img src="img/foodmenu/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="50" height="50" class="object-fit-cover" style="margin-right: 10px;">
                                            <div><?php echo htmlspecialchars($row['name']); ?></div>
                                        </div>
                                    </td>
                                    <td class="price">RM<?php echo number_format($row['price'], 2); ?></td>
                                    <td class="quantity"><?php echo $row['quantity']; ?></td>
                                    <td><a href="remove_from_cart.php?id=<?php echo $row['menu_items_id']; ?>" style="color: red; cursor: pointer;">Delete</a></td>
                                </tr>
                            <?php }} else { ?>
                                <tr><td colspan='4'>Your cart is empty.</td></tr>
                            <?php }
                        $stmt->close();
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-4 col-12 border order-summary mb-2">
            <h3 class="text-center">ORDER SUMMARY</h3>
                <p>Subtotal: RM <span id="summary-subtotal"></span></p>
                <p>Service Tax (6%): RM <span id="service-tax"></span></p>
                <div class="total">
                    <p >TOTAL</p>
                    <p>RM <span id="total"></span></p>
                </div>
                <?php if ($result->num_rows > 0) { ?>
                    <a href="place_order.php" class="place-order-btn">Place Order</a>
                <?php } ?>
        </div>
    </div>
    <div class="row text-end me-4">
        <a href="foodmenu.php" class="continue-ordering">← Continue Ordering</a>
    </div>
    <script>
        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('tbody tr').forEach(function (row) {
                const priceText = row.querySelector('.price').textContent.replace('RM', '');
                const price = parseFloat(priceText);
                const quantity = parseInt(row.querySelector('.quantity').textContent);
                subtotal += price * quantity;
            });

            document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2);

            const serviceTax = subtotal * 0.06;
            document.getElementById('service-tax').textContent = serviceTax.toFixed(2);

            const total = subtotal + serviceTax;
            document.getElementById('total').textContent = total.toFixed(2);
        }

        window.onload = calculateTotals;
    </script>
</body>
</html>
