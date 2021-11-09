<?php
// TODO: Add a method for the user to purchase a product as well as search the DB for the vendors details for the payment to go to
ob_start(); // need this for redirecting the user to a different page
require('header.php');
if(isset($_SESSION['id'])){
	$productID = $_GET['id']; // get the productID from the url
	$stmt = $db->prepare("SELECT * FROM shop WHERE productID = ?"); // lookup the item from the DB
	$stmt->bind_param("i", $productID);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	echo('ID = ' . $row['productID'] . ', Name = ' . $row['item'] . ', Price = ' . $row['price'] . ', Description = ' . $row['description']);
}
else{
	require('html/loggedIn.html'); // format the error
    echo("<h3>You must login before purchasing a product.</h3>"); // Send the error before redirecting user to login
	$location = BASE_URL . "login.php";
    header( "Refresh:1; url=$location", true, 303); // redirect the user to login.php
	exit();
}
?>
