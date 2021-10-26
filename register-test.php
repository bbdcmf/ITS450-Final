<?php
session_start();
ob_start();
require('mysql.inc.php');
if(isset($_POST['btnRegister'])){
	$nameValues = $_POST;
	
	foreach($nameValues as $name => $value) {
        	if($name == 'userin'){
        		$username = $value;
        	}
        	elseif($name == 'passin'){
            	$password = $value;
        	}
        	elseif($name == 'passConfirm'){
        		$passconfirm = $value;
        	}	
	}
	$hashedPass = hash_pass($password);
	$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
	$stmt->bind_param("ss", $username, $hashedPass);
	$stmt->execute();
}
else{
	require('html/register.html');
}
?>
