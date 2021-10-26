<?php
session_start();
ob_start();
require('mysql.inc.php');
if(isset($_POST['btnSubmit'])){
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

	$stmt = $db->prepare("SELECT * FROM users WHERE username=? AND password=?");
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();

	if($user['id'] == ''){
		echo("Incorrect info, try again");
		require('html/login.html');
	}
	else {
		$_SESSION['id'] = $user['id'];
		require('html/loggedIn.html');
	    echo("<h3>Hello ".$username."!</h3>");
	    $location = BASE_URL . "index.php";
	    header( "Refresh:3; url=$location", true, 303);
		exit();
	    echo("</h1></div></body></html>");
	}
}
else{
	require('html/login.html');
}
?>

