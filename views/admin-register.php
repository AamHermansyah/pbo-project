<?php
require_once '../models/Database.php';
require_once '../models/Admin.php';
session_start();

$db = new Database();
$admin = new Admin($db->getConnection());

if (!empty($_SESSION['user_id']) || !empty($_SESSION['admin_id'])) {
  $admin->navigation('dashboard.php');
}

if (isset($_POST['register'])) {
  $fullname = $_POST['fullname'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  $admin->register($fullname, $password, $email, 'admin');

  if ($admin->status == 'register-success') {
    $admin->navigation('login.php');
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div class="xl:container mx-auto px-4 sm:px-10">
    <div class="w-full min-h-screen flex items-center justify-center flex-col py-10">
      <h2 class="text-3xl font-bold">Registrasi Admin</h2>
      <form method="post" class="w-full mt-4 max-w-2xl">
        <div class="mb-6">
          <label for="fullname" class="inline-block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
          <input
            type="text"
            id="fullname"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            name="fullname"
            placeholder="Masukan nama lengkap..."
            required
          >
        </div>
        <div class="mb-6">
          <label for="email" class="inline-block mb-2 text-sm font-medium text-gray-900">Email</label>
          <input
            type="email"
            id="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            name="email"
            placeholder="Masukan email..."
            required
          >
        </div>
        <div class="mb-6">
          <label for="password" class="inline-block mb-2 text-sm font-medium text-gray-900">Kata Sandi</label>
          <input
            type="password"
            id="password"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            name="password"
            placeholder="Masukan kata sandi..."
            required
          >
        </div>
        <?php if ($admin->status == 'email-exist'): ?>
          <p class="text-sm mb-2 text-red-500">
            Email yang anda masukan sudah terdaftar didatabase
          </p>
        <?php endif; ?>
        <input
          type="submit"
          name="register"
          value="Registrasi"
          class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2"
        >
        <span class="inline-block w-full">
          Sudah punya akun? <a href="login.php" class="underline">Login</a>
        </span>
      </form>
    </div>
  </div>
</body>
</html>