<?php
// TODO: Create page to change account settings such as password, description ect.
require('header.php');
require('html/account.html');
echo($_SESSION['username'] . ' ' . session_id());
?>
