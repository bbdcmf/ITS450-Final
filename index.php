<?php
session_start();
// TODO: Finish all the login/design for this file
require('header.php');
require('html/index.html');
if(isset($_POST['searchBar'])){
	echo($_POST['searchBar']);
}
?>
