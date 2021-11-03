<?php
	ob_start(); // need this for redirecting the user to a different page
	require('header.php');
	session_destroy();
	require('html/loggedIn.html');
	echo("<h3>Logout successful. You will now be redirected to the main page");
	$location = BASE_URL . 'index.php';
	header( "Refresh:2; url=$location", true, 303);
	exit();
	echo("</h3></div></body></html>");
?>
