<?php
// TODO: Make the errors from $errorstr look better when displayed
ob_start();
require('header.php');
if(!isset($_POST['btnRegister'])){ //If the register button has not been pressed
	require('html/register.html');
}
else{
	$good = FALSE; // need this for ensuring there are no errors
	$errorstr = '';// need this as well for reason above
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
	if($password != $conpass) { // if the two passwords dont match
		$errorstr = $errorstr . ' mismatch password, ';
	}
	// ensuring the username hasnt been taken already
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
	// if there are no errors, insert the users info into the DB
	if($errorstr == '') {
		$good = TRUE;
		$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
		$stmt->bind_param("ss", $username, $hashedPass);
		$stmt->execute();
	}
	// get the user info from the DB and set the user's session
	if($good) {
		// get the id of the user that we just inserted
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
	// if there are errors, show them
	else {
		require('html/register.html');
		echo($errorstr);
	}
}
?>
