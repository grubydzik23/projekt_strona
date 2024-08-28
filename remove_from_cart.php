<?php
session_start();
include 'config.php';

$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$stmt->close();

header("Location: cart.php");
exit();
?>
