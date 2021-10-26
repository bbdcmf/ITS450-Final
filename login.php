<?php
session_start();
ob_start(); //need this for redirecting the user to a different page
require('mysql.inc.php'); //require the MYSQL info
if(isset($_POST['btnSubmit'])){ //When the user presses "Log in"
	$nameValues = $_POST;

	foreach($nameValues as $name => $value) {
        	if($name == 'userin'){
        		$username = $value;
        	}
        	elseif($name == 'passin'){
            	$password = $value;
        	}
	}
	$hashedPass = hash_pass($password);

	$stmt = $db->prepare("SELECT * FROM users WHERE username=? AND password=?"); //lookup the user from the database
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();

	if($user['id'] == ''){ //if the user cannot be found in the database
		echo("Incorrect info, try again");
		require('html/login.html');
	}
	else { //if we found the user's data in the database
		$_SESSION['id'] = $user['id'];
		require('html/loggedIn.html'); //get the hello screen
	    echo("<h3>Hello ".$username."!</h3>");
	    $location = BASE_URL . "index.php";
	    header( "Refresh:3; url=$location", true, 303); //redirect the user to index.php
		exit();
	    echo("</h1></div></body></html>");
	}
}
else{ //if the login button hasnt been pressed, just show the user the login page
	require('html/login.html');
}
?>

