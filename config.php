<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pos_db');

// Application paths
define('ROOT_PATH', dirname(__DIR__));
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('PAGES_PATH', ROOT_PATH . '/pages');
define('CSS_PATH', ROOT_PATH . '/css');
define('JS_PATH', ROOT_PATH . '/js');
define('IMAGES_PATH', ROOT_PATH . '/images');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
session_start();

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

// Set charset to ensure proper encoding
mysqli_set_charset($conn, "utf8");
?> 