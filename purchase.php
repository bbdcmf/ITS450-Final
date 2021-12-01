<?php
// TODO: Add a method for the user to purchase a product as well as search the DB for the vendors details for the payment to go to
ob_start(); // Need this for redirecting the user to a different page
require('header.php');
require('html/purchase.html');

// If the user is logged in
if(isset($_SESSION['isLoggedInToLemonShop']) and $_SESSION['isLoggedInToLemonShop'] == true){
	// Create the div where the product details are shown
	echo("<div class='purchaseDiv'>");
	// Get the productID from the url
	$productID = $_GET['id'];
	// Lookup the item from the DB
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
	
	// Paypal scripts
	echo('<script src="https://www.paypal.com/sdk/js?client-id=AQUDW2NzyMB0edExztPC5xJOvSd0N7MLT4uFP3QLEL2eXpH--Yzuis0BNhNBJ1bqd5pv-zIl8QvzFwuE"></script>

  	<div id="paypal-button-container"></div>

  	<script>
  		paypal.Buttons({

    			createOrder: function(data, actions) {
      				// This function sets up the details of the transaction, including the amount and line item details.
      				return actions.order.create({
        				purchase_units: [{
          					amount: {
            						value: "' . $price . '"
          					}
        				}]
      				});
    			},
    			onApprove: function(data, actions) {
      				// This function captures the funds from the transaction.
      				return actions.order.capture().then(function(details) {
        				// When transaction sucessed, redirect the user to the orderComplete page
        				window.location.replace("' . ENC_URL . 'orderComplete.php");
      				});
    			}
  		}).render("#paypal-button-container");
  		//This function displays Smart Payment Buttons on your web page.
	</script>');
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
