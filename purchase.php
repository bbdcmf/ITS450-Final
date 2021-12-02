<?php
// TODO: Add a method for the user to purchase a product as well as search the DB for the vendors details for the payment to go to
ob_start(); // Need this for redirecting the user to a different page
require('header.php');
require('html/purchase.html');

// If the user is logged in
if(isset($_SESSION['isLoggedInToLemonShop']) and $_SESSION['isLoggedInToLemonShop'] == true){
	$_SESSION['chosenID'] = NULL;
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
	$quantity = $row['quantity'];
	// Print all of the details
	echo("<img src='" . $row['imgPath'] . "' style='height: 70px; width: 70px; float: left;'/><table style='padding-left: 20px;'><tr><td>Product:</td><td>" . $productName . "</td></tr><tr><td>Description:</td><td>" . $desc . "</td></tr><tr><td>Price:</td><td>$" . $price . "</td></tr><tr><td rowspan='2' style='display: flex; position: absolute; left: 119px;'>");
	// Paypal scripts
	echo('<script src="https://www.paypal.com/sdk/js?client-id=AQUDW2NzyMB0edExztPC5xJOvSd0N7MLT4uFP3QLEL2eXpH--Yzuis0BNhNBJ1bqd5pv-zIl8QvzFwuE&currency=USD&disable-funding=credit,card"></script>

  	<div id="paypal-button-container"></div>

  	<script>
  		paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    currency_code: "USD",
                    value: "' . $price + 5 . '",
                    breakdown: {
                        item_total: {
                            currency_code: "USD",
                            value: "' . $price . '"
                        },
                        shipping: {
                            currency_code: "USD",
                            value: "5.00"
                        },
                    }
                },
                items: [{
                    name: "' . $productName . '",
                    description: "' . $desc . '",
                    unit_amount: {
                         currency_code: "USD",
                         value: "' . $price . '"
                    },
                    quantity: "' . $quantity . '"
                }
                
                ]
            }]
        })
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // When the transaction succeeds:
			window.location.replace("' . ENC_URL . 'orderComplete.php?productid=' . $productID . '");
        })
    }
}).render("#paypal-button-container")

  		//This function displays Smart Payment Buttons on your web page.
	</script></td></tr></table>');
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
