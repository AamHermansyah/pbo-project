<?php
class Authentication {
  protected $conn;
  public $status = 'unauthenticated';

  public function __construct($db) {
    $this->conn = $db;
  }

  public function createAccount($fullname, $password, $email, $role = null) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query;

    if ($role) {
      $query = "INSERT INTO users (fullname, password, email, role) VALUES (?, ?, ?, ?)";
    } else {
      $query = "INSERT INTO users (fullname, password, email) VALUES (?, ?, ?)";
    }

    $stmt = $this->conn->prepare($query);
    
    if ($role) {
      $stmt->bind_param("ssss", $fullname, $hashedPassword, $email, $role);
    } else {
      $stmt->bind_param("sss", $fullname, $hashedPassword, $email);
    }

    if ($stmt->execute()) {
      $this->status = 'register-success';
    } else {
      $this->status = 'server-error';
    }
  }

  public function getUserOrAdminByEmail($email) {
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
      return null;
    }

    $data = $result->fetch_assoc();
    return $data;
  }

  public function login($email, $password) {
    $data = $this->getUserOrAdminByEmail($email);
    if ($data == null) {
      $this->status = 'email-not-exist';    
    } else {
      $query = "SELECT password FROM users WHERE email = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("s", $email);
      if (!$stmt->execute()) {
        $this->status = 'server-error';
      }
      $stmt->bind_result($hashedPassword);
      $stmt->fetch();

      if (password_verify($password, $hashedPassword)) {
        $this->status = 'authenticated';
        return $data;
      } else {
        $this->status = 'password-is-wrong';
      }
    }
  }

  public static function navigation($path) {
    header("Location: " . $path);
    exit;
  }
}
?>
