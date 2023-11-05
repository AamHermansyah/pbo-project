<?php
class Database {
    private $conn;

    public function __construct() {
        include '../config.php';
        $this->conn = $conn;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
