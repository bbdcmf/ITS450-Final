<?php
ob_start(); // need this for redirecting the user to a different page
require('header.php');

// When the user presses "Log in"
if(isset($_POST['btnSubmit'], $_POST['username'], $_POST['password'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$hashedPass = hash_pass($password);
	
	// lookup the user from the database
	$stmt = $db->prepare("SELECT * FROM users WHERE username=? AND password=?");
	$stmt->bind_param("ss", $username, $hashedPass);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	
	// if the user cannot be found in the database
	if(!isset($user['id'])){
		require('html/login.html');
		echo("Don't have an account? Register <a href=" . BASE_URL . "register.php>here</a>
		</div></div>
		<div class='errorDiv'>Incorrect info, try again</div></body></html>");
	}
	// if we found the user's data in the database
	else {
		$_SESSION['id'] = $user['id'];
		require('html/loggedIn.html'); // get the hello screen
	    echo("<h3>Hello ".$username."!</h3>");
	    // if the user wanted to buy something and was redirected to login before purchasing the product,
	    // redirect them to the page to purchase the product they chose
	    if(isset($_SESSION['chosenID'])){
	    	$location = BASE_URL . "purchase.php?id=" . $_SESSION['chosenID'];
	    	header( "Refresh:1; url=$location", true, 303); // redirect the user to the product they want to purchase
	    	exit();
	    }
	    // if the user was just trying to login
	    else{
	    	$location = BASE_URL . "index.php";
	    	header( "Refresh:1; url=$location", true, 303); // redirect the user to index.php
			exit();
	    	echo("</h1></div></body></html>");
	    }
	}
}
// if the login button hasnt been pressed, just show the user the login page
else{
	require('html/login.html');
	echo("Don't have an account? Register <a href=" . BASE_URL . "register.php>here</a>
	</div></div></body></html>");
}
?>

