<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="css/project.css">
	</head>
	
	<nav class="headerNav">
<?php
	require('mysql.inc.php');
	echo("<a href=" . BASE_URL . "index.php>Home</a>");
	if(isset($_SESSION['id'])){
		echo("
		<a href=" . BASE_URL . "logout.php>Log Out</a>
		</nav><br /><br />");
	}
	else{
		echo("
		<a href=" . BASE_URL . "login.php>Login</a>
		</nav><br /><br />");
	}
?>
