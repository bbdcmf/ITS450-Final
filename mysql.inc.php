<?php
	// MYSQL database details
    DEFINE('DB_USER', 'webserver'); // MYSQL username
    DEFINE('DB_PASS', 'toor'); // MYSQL password
    DEFINE('DB_HOST', '10.20.0.5'); //MYSQL server IP
    DEFINE('DB_NAME', 'project');
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Function for hasing the users password
    function hash_pass($password) {
        return hash_hmac('sha256', $password, 'c#haR1891', true);
    }
    
    // Function for sanitizing user input
    function sanitize_input($data){
    	$data = trim($data); // remove unnecesary whitespace
        $data = htmlspecialchars($data); // convert special characters to HTML, helps prevent XSS
    	return $data;
    }
    
    // The base url used in redirections
    define('BASE_URL', 'http://10.20.0.6/');
    define('ENC_URL', 'https://10.20.0.6/');
?>
