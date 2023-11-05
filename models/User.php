<?php
require 'Authentication.php';

class User extends Authentication {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            return null;
        }
    
        $user = $result->fetch_assoc();
        return $user;
    }

    public function register($fullname, $password, $email) {
        if ($this->getUserByEmail($email) != null) {
            $this->status = 'email-exist';
        } else {
            $this->createAccount($fullname, $password, $email);
        }
    }
}
?>
