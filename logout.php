<?php
	session_start();
	ob_start(); // need this for redirecting the user to a different page
	require('mysql.inc.php');
	session_destroy();
	echo("Logout successful. You will now be redirected to the main page");
	$location = BASE_URL . 'index.php';
	header( "Refresh:3; url=$location", true, 303);
	exit();
?>
