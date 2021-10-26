<?php
    DEFINE('DB_USER', 'root');
    DEFINE('DB_PASS', 'toor');
    DEFINE('DB_HOST', '172.19.0.2');
    DEFINE('DB_NAME', 'project');
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    function hash_pass($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    define('BASE_URL', 'http://localhost:8080/');
?>
