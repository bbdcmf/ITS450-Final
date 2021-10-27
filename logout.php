<?php
	session_start();
	ob_start(); // need this for redirecting the user to a different page
	require('header.php');
	require('html/loggedIn.html');
	session_destroy();
	echo("<h3>Logout successful. You will now be redirected to the main page");
	$location = BASE_URL . 'index.php';
	header( "Refresh:3; url=$location", true, 303);
	exit();
	echo("</h3></div></body></html>");
?>
