<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/project.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	
	<nav class="headerNav">
<?php
require('mysql.inc.php');
echo("<a href=" . BASE_URL . "index.php>Home</a>");
if(isset($_SESSION['id'])){
	echo("
	<a href=" . BASE_URL . "account.php>Account</a>
	<a href=" . BASE_URL . "orders.php>Orders</a>
	<a href=" . BASE_URL . "logout.php>Log Out</a>");
}
else{
	echo("<a href=" . BASE_URL . "login.php>Login</a>");
}
echo("<form method='POST' action='index.php' class='searchForm'><input type='text' placeholder='Search...' class='searchBar' name='searchBar'/></form>
		  </nav><br /><br />");
?>
