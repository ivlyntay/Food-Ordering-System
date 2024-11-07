<!DOCTYPE html>
<html lang="en">
<?php
require_once("connection.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to place an order.";
    header("Location: login.php");
    exit();
}

// new session, check whether got set session cart, if no, create new session cart
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
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
    <title>Food Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/foodmenu_style.css">
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

    <main>
        <h1>Food Menu</h1>
        <div class="container-fluid">
            <div class="row justify-content-center categories">
                <div class="col-0 col-md-1 col-lg-2 col-xl-3"></div>
                <button class="col category-button active" data-category="all">All</button>
                <button class="col category-button" data-category="pasta">Pasta</button>
                <button class="col category-button" data-category="burgers">Burgers</button>
                <button class="col category-button" data-category="desserts">Desserts</button>
                <button class="col category-button" data-category="beverages">Beverages</button>
                <div class="col-0 col-md-1 col-lg-2 col-xl-3"></div>
            </div>
            <div class="row mb-2 order-by">
                <div class="col d-flex justify-content-end ">
                    <span>Price: </span>
                    <select id="price-sort">
                        <option value="low-to-high">Low to high</option>
                        <option value="high-to-low">High to Low</option>
                    </select>
                </div>
            </div>
            <div class="row menu-items" id="menu-items">
            <?php
                // Updated SQL query to include description
                $sql = "SELECT id, category, name, price, image, description FROM menu_items";
                //$sql = "CALL GetMenuItems()";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='col-sm-6 col-lg-4 mb-2 menu-item' data-category='" . $row["category"] . "'>";
                            echo "<div id='menu-item-" . $row["id"] . "' class='card text-center rounded-3 p-2' data-price='" . $row["price"] . "'>";
                                echo "<img class='item-image card-img-top object-fit-cover rounded-3' src='img/foodmenu/" . $row["image"] . "' alt='" . $row["name"] . "'>";
                                echo "<div class='card-body'>";
                                    echo "<h2 class='card-title item-name'>" . $row["name"] . "</h2>";
                                    echo "<p class='card-text'>RM " . number_format($row["price"], 2) . "</p>";
                                    echo "<p class='item-description' style='display:none'>" . $row["description"] . "</p>"; // TODO: Display item description
                                    echo "<button class='add-to-cart-btn' data-bs-toggle='modal' data-bs-target='#item-modal' data-item-id=" . $row["id"] . ">Add to Cart</button>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "0 results";
                }
                $conn->close();
            ?>
            </div>
        </div>
    </main>
    <div class="modal fade" id="item-modal" tabindex="-1" aria-labelledby="item-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-body text-center">
                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="modal-item-image" src="img/foodmenu/cheesy-beef-burger.jpg" alt="Item Image" class="rounded item-image">
                <h2 id="modal-item-name">Burger</h2>
                <p class="modal-item-price" id="modal-item-price">RM10.00</p>
                <p id="modal-item-description">Delicious Bolognese sauce served with spaghetti</p>
                <div class="quantity-selector">
                    <button id="decrease-quantity">-</button>
                    <input type="number" id="quantity" value="1" min="1">
                    <button id="increase-quantity">+</button>
                </div>
                <button id="confirm-modal" class="confirm-btn" data-bs-dismiss="modal" type='button'>Confirm</button>
            </div>
            </div>
        </div>
    </div>
    <script src="js/foodmenu_script.js"></script>
</body>
</html>
