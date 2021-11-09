<?php
	ob_start(); // Need this for redirecting the user to a different page
	require('header.php');
	session_destroy(); // End the user's session
	require('html/loggedIn.html');
	echo("<h3>Logout successful. You will now be redirected to the main page");
	$location = BASE_URL . 'index.php';
	header( "Refresh:1; url=$location", true, 303); // Redirect the user to index.php
	exit();
	echo("</h3></div></body></html>");
?>
