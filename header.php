<?php
// Start the users session, for using the $_SESSION variable
session_start();
// If the user does not have a session token, generate one for them (used to prevent CSRF)
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/project.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		// Function for validating the users' input
		<script>
			function sanitize_input(text) {
 				 var map = {
   					 '&': '&amp;',
   					 '<': '&lt;',
    					'>': '&gt;',
   					 '"': '&quot;',
   					 "'": '&#039;'
 				 };
  
  				return text.replace(/[&<>"']/g, function(m) { return map[m]; });
			}
		</script>
	</head>
	
	<nav class="headerNav">
<?php
// Create the header links shown at the top of each page
require('mysql.inc.php');
echo("<a href=" . BASE_URL . "index.php>Home</a>");
if(isset($_SESSION['isLoggedInToLemonShop']) and $_SESSION['isLoggedInToLemonShop'] == true){
	echo("
	<a href=" . ENC_URL . "account.php>Account</a>
	<a href=" . ENC_URL . "orders.php>Orders</a>
	<a href=" . BASE_URL . "logout.php>Log Out</a>");
}
else{
	echo("<a href=" . ENC_URL . "login.php>Login</a>");
}
// Add the search bar
echo("<form method='POST' action='index.php' class='searchForm'><input type='text' placeholder='Search...' class='searchBar' name='searchBar'/></form>
		  </nav><br /><br />");
?>
