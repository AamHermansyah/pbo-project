<?php
class Payment {
  protected $conn;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function createPayment($data) {
    $query = "INSERT INTO invoice (name, address, city, zip_code, country, cardholder_name, card_number, exp_month, exp_year, cvc_number, user_id, movie_id, order_at, admin_fee, service_fee, total_price, movie_title, movie_year, movie_rating, movie_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);

    $stmt->bind_param("sssssssiiiisssiissss",
      $data['name'],
      $data['address'],
      $data['city'],
      $data['zip_code'],
      $data['country'], 
      $data['cardholder_name'], 
      $data['card_number'], 
      $data['exp_month'], 
      $data['exp_year'], 
      $data['cvc_number'], 
      $data['user_id'], 
      $data['movie_id'], 
      $data['order_at'], 
      $data['admin_fee'], 
      $data['service_fee'], 
      $data['total_price'],
      $data['movie_title'],
      $data['movie_year'],
      $data['movie_rating'],
      $data['movie_image'],
    );

    if ($stmt->execute()) {
        // Mendapatkan id invoice
        $invoiceId = $this->conn->insert_id;

        $data['id'] = $invoiceId;

        return $data;
    } else {
        return null;
    }
}

  public function getInvoices($page = 1, $perPage = 10, $user_id = null) {
    $offset = ($page - 1) * $perPage;
    $query;
    
    if (empty($user_id)) {
      $query = "SELECT invoice.*, users.* FROM invoice LEFT JOIN users ON invoice.user_id = users.id LIMIT ?, ?";
    } else {
        $query = "SELECT invoice.*, users.* FROM invoice LEFT JOIN users ON invoice.user_id = users.id WHERE invoice.user_id = ? LIMIT ?, ?";
    }

    $stmt = $this->conn->prepare($query);
    
    if (empty($user_id)) {
      $stmt->bind_param("ii", $offset, $perPage);
    } else {
      $stmt->bind_param("iii", $user_id, $offset, $perPage);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $invoices = [];
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }

    return $invoices;
  }

  public function getInvoiceById($id) {
    $query = "SELECT * FROM invoice WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null;
    }

    $data = $result->fetch_assoc();
    return $data;
  }
}
?>
