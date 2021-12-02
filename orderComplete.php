<?php
ob_start(); // need this for redirecting the user to a different page
require('header.php');
if(isset($_GET['productid'])){
	$productID = $_GET['productid'];
	// Get the user's userID from the database (used for SQL below)
	$stmt = $db->prepare("SELECT id FROM users WHERE username=?");
	$stmt->bind_param("s", $_SESSION['username']);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	$userID = $user["id"];
	$stmt = $db->prepare("INSERT INTO orders (productID, userID) VALUES (?, ?)");
	$stmt->bind_param("ii", $productID, $userID);
	$stmt->execute();
	echo("<div class='loggedInDiv'><h3>Order complete!<h3></div>");
	$location = ENC_URL . "orders.php";
	header( "Refresh:1; url=$location", true, 303); // redirect the user to the orders page
	exit();
}
?>
