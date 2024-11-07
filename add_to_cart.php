<?php
session_start();
require_once("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $image = $_POST["image"];

    // Sanitize inputs
    $id = intval($id);
    $name = htmlspecialchars($name);
    $price = floatval($price);
    $quantity = intval($quantity);
    $image = htmlspecialchars($image);

    $item = [
        "id" => $id,
        "name" => $name,
        "price" => $price,
        "quantity" => $quantity,
        "image" => $image
    ];

    // Initialize cart if it doesn't exist
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    // If item already exists in the cart, update the quantity
    if (isset($_SESSION["cart"][$id])) {
        $_SESSION["cart"][$id]["quantity"] += $quantity;
    } else {
        $_SESSION["cart"][$id] = $item;
    }

    header("Location: foodmenu.php");
    exit();
}
?>

