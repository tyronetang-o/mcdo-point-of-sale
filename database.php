<?php
require_once 'config.php';

class Database {
    private $conn;
    
    public function __construct() {
        $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    public function query($sql) {
        return mysqli_query($this->conn, $sql);
    }
    
    public function escape($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }
    
    public function close() {
        mysqli_close($this->conn);
    }
}
?> 