<?php
ob_start();
require('header.php');
if(!isset($_POST['btnRegister'])){ //If the register button has not been pressed
	require('html/register.html');
}
else{
	$good = FALSE; // Need this for ensuring there are no errors
	$errorstr = 'Error: ';// Need this as well for reason above
	$nameValues = $_POST;
	foreach($nameValues as $name => $value) {
		if($name != 'btnSubmit'){
	   		if($value == '') {
				$errorstr = $errorstr . ' ' . $name . ' missing field,';
	   		}

	   		// Set the value to variable
	   		switch($name){
	   			case 'username':
	   				$username = $value;
	   				break;
	 			case 'password':
	 				$password = $value;
		 			break;
	    		case 'confirmPassword':
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
	// Ensuring the username hasnt been taken already
	$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	if($user != '') {
		$errorstr = $errorstr . ' user with name already exists, ';
	}
	// Hash password
	$hashedPass = hash_pass($password);
	// If there are no errors, insert the users info into the DB
	if($errorstr == 'Error: ') {
		$good = TRUE;
		$stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
		$stmt->bind_param("ss", $username, $hashedPass);
		$stmt->execute();
	}
	// Get the user info from the DB and set the user's session
	if($good) {
		// Get the id of the user that we just inserted
		$stmt = $db->prepare("SELECT * FROM users WHERE username=?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
		
		$_SESSION['isLoggedInToLemonShop'] = true; // start the user's session
		$_SESSION['username'] = $username;
		
		require('html/loggedIn.html'); // Get the hello screen
	    echo("<h3>Welcome ".$username."!</h3>");
		// redirect to login
		$location = BASE_URL . 'index.php';
		header( "Refresh:3; url=$location", true, 303);
		exit();
			
	}
	// If there are errors, show them
	else {
		require('html/register.html');
		$errorstr = rtrim($errorstr, ", ");
		echo("<div class='errorDiv'>$errorstr</div>");
	}
}
?>
