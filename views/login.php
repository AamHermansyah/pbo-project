<?php
require_once '../models/Database.php';
require_once '../models/Authentication.php';
session_start();

$db = new Database();
$authentication = new Authentication($db->getConnection());

if (!empty($_SESSION['user_id']) || !empty($_SESSION['admin_id'])) {
  $authentication->navigation('dashboard.php');
}

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $data = $authentication->login($email, $password);

  // Menyimpan data hasil login ke session
  if ($authentication->status == 'authenticated') {
    $_SESSION['name'] = $data['fullname'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['role'] = $data['role'];

    if ($data['role'] == 'user') {
      $_SESSION['user_id'] = $data['id'];
      $authentication->navigation('home.php');
    } else {
      $_SESSION['admin_id'] = $data['id'];
      $authentication->navigation('dashboard.php');
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div class="relative xl:container mx-auto px-4 sm:px-10">
    <div class="w-full min-h-screen flex items-center justify-center flex-col py-10">
      <div class="absolute inset-0">
        <img
          src="https://source.unsplash.com/random/1600x900/?forest"
          alt="bg-login"
          class="hidden md:block w-full h-full object-cover"
        >
        <img
          src="https://source.unsplash.com/random/900x1600/?forest"
          alt="bg-login"
          class="block md:hidden w-full h-full object-cover"
        >
      </div>
      <form method="post" class="relative w-full mt-4 max-w-2xl bg-white bg-opacity-60 backdrop-blur rounded-xl py-10 px-6 sm:px-10 shadow-md">
        <h2 class="text-3xl font-bold mb-4 text-center">
          <span class="text-blue-500">Badas</span>Film
        </h2>
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
        <?php if ($authentication->status == 'email-not-exist' || $authentication->status == 'password-is-wrong'): ?>
          <p class="text-sm mb-2 text-red-500">
            Password atau email yang anda masukan salah.
          </p>
          <?php elseif ($authentication->status == 'server-error'): ?>
            <p class="text-sm mb-2 text-red-500">
            Server Error. Silahkan coba lagi!
          </p>
        <?php endif; ?>
        <input
          type="submit"
          name="login"
          value="Login"
          class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 cursor-pointer"
        >
        <span class="inline-block w-full">
          Belum punya akun? <a href="register.php" class="underline">Register</a>
        </span>
      </form>
    </div>
  </div>
</body>
</html>
