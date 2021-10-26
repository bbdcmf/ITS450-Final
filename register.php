<?php
session_start();
ob_start();
$nameValues = array();
require('mysql.inc.php');
if(!isset($_POST['btnRegister'])){
	require('html/register.html');
}
else{
	$good = FALSE;
	$errorstr = '';
	$nameValues = $_POST;
	foreach($nameValues as $name => $value) {
		if($name != 'btnSubmit'){
	   		if($value == '') {
				$errorstr = $errorstr . $name . ' missing field, ';
	   		}

	   		// set the value to variable
	   		switch($name){
	   			case 'userin':
	   				$username = $value;
	   				break;
	 			case 'passin':
	 				$password = $value;
		 			break;
	    		case 'passConfirm':
	    			$conpass = $value;
	    			break;
				default:
					break;
			}
		}
	}
	if($password != $conpass) {
		$errorstr = $errorstr . ' mismatch password, ';
	}
	$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	if($user != '') {
		$errorstr = $errorstr . ' user with name or email';
	}
	// hash password
	$hashedPass = hash_pass($password);

	if($errorstr == '') {
		$good = TRUE;
		$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
		$stmt->bind_param("ss", $username, $hashedPass);
		$stmt->execute();
	}

	if($good) {
		//get the id of the user that we just inserted
		$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		
		$_SESSION['id'] = $user['id'];
		
		echo('You will now be redirected to the main page');
		// redirect to login
		$location = BASE_URL . 'index.php';
		header( "Refresh:3; url=$location", true, 303);
		exit();
			
	}
	else {
		require('html/register.html');
		echo($errorstr);
	}
}
?>
