<?php
    DEFINE('DB_USER', 'root'); // MYSQL username
    DEFINE('DB_PASS', 'toor'); // MYSQL password
    DEFINE('DB_HOST', '172.19.0.2'); // gonna have to change this IP to the IP of your MYSQL server
    DEFINE('DB_NAME', 'project');
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    // function for hasing the users password
    function hash_pass($password) {
        return hash_hmac('sha256', $password, 'c#haR1891', true);
    }
    // the base url used in redirections, gonna have to change it to whatever you use to get to your site
    define('BASE_URL', 'http://localhost:8080/');
?>
