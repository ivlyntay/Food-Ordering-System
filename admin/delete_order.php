<?php
include("../connection.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Retrieve the image file name before deleting the record
    $result = $conn->query("SELECT image FROM menu_items WHERE id=$id");
    $menu_item = $result->fetch_assoc();
    $image_path = "../img/foodmenu/" . $menu_item['image'];

    // Delete the image file from the server
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    // Delete the record from the database
    $sql = "DELETE FROM menu_items WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Menu item deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the add_fooditem.php page
    header("Location: add_fooditem.php");
    exit();
} else {
    echo "No menu item ID provided.";
}
?>
