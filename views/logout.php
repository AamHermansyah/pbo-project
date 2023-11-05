<?php
require_once '../models/Authentication.php';
session_start();

// Hapus semua data sesi
session_destroy();

Authentication::navigation('login.php');
?>
