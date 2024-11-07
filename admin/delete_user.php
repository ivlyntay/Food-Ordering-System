<?php
include("../connection.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Prepare and execute the delete query
if (isset($_GET['uid'])) {
    $user_id = $_GET['uid'];
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: user_management.php");
exit();
?>
