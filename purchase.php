<?php
// TODO: Add a method for the user to purchase a product as well as search the DB for the vendors details for the payment to go to
ob_start(); // Need this for redirecting the user to a different page
require('header.php');
require('html/purchase.html');

// If the user is logged in
if(isset($_SESSION['isLoggedInToLemonShop']) and $_SESSION['isLoggedInToLemonShop'] == true){
	// Create the div where the product details are shown
	echo("<div class='purchaseDiv'>");
	// get the productID from the url
	$productID = $_GET['id'];
	// lookup the item from the DB
	$stmt = $db->prepare("SELECT * FROM shop WHERE productID = ?");
	$stmt->bind_param("i", $productID);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$productID = $row['productID'];
	$productName = $row['item'];
	$price = $row['price'];
	$desc = $row['description'];
	
 	// Get the name of the vendor
	$stmt = $db->prepare("SELECT u.* FROM users u WHERE EXISTS (SELECT NULL FROM shop s WHERE u.id = s.userID AND s.productID = ?)");
	$stmt->bind_param("i", $productID);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();
	$vendor = $row['username'];
	
	// Print all of the details
	echo("Product: " . $productName . " Price: " . $price . " Description: " . $desc . " Vendor: " . $vendor);
	echo('</div></body></html>');
}
// If the user is not logged in, send them to login.php
else{
	// Close purchase.html's page
	echo('</body></html>');
	
	// Require loggedIn.html to format the error that is given
	require('html/loggedIn.html');
	// Send the error before redirecting user to login
    echo("<h3>You must login before purchasing a product.</h3>");
	$location = BASE_URL . "login.php";
	$_SESSION['chosenID'] = $_GET['id'];
	// Redirect the user to login.php
    header( "Refresh:1; url=$location", true, 303);
	exit();
}
?>
