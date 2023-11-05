<?php
$host = 'localhost:3306';
$username = 'root';
$password = '';
$database = 'pbo';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
