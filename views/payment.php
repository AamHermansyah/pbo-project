<?php
require_once '../models/Database.php';
require_once '../models/Authentication.php';
require_once '../models/Payment.php';
session_start();

if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])) {
  Authentication::navigation('login.php');
}

echo  $_SESSION["admin_id"];

$db = new Database();
$payment = new Payment($db->getConnection());

// Data dari halaman movie detail
if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST["payment"])) {
  $movie_title = $_POST["movie_title"];
  $movie_id = $_POST["movie_id"];
  $movie_image = $_POST["movie_image"];
  $movie_year = $_POST["movie_year"];
  $movie_rating = $_POST["movie_rating"];
  $movie_price = $_POST["movie_price"];
  $admin_fee = $_POST["admin_fee"];
  $service_fee = $_POST["service_fee"];
  $created_at = $_POST["created_at"];
} elseif (isset($_POST["payment"])) {
  // Menghandle data form pembayaran
  $name = $_POST["name"];
  $address = $_POST["address"];
  $city = $_POST["city"];
  $zip_code = $_POST["zip_code"];
  $country = $_POST["country"];
  $cardholder_name = $_POST["cardholder_name"];
  $card_number = $_POST["card_number"];
  $exp_month = $_POST["exp_month"];
  $exp_year = $_POST["exp_year"];
  $cvc_number = $_POST["cvc_number"];
  $user_id;

  if (empty($_SESSION['user_id'])) {
    $user_id = $_SESSION["admin_id"];
  } else {
    $user_id = $_SESSION["user_id"];
  }

  $movie_id = $_POST["movie_id"];
  $order_at = $_POST["order_at"];
  $admin_fee = $_POST["admin_fee"];
  $service_fee = $_POST["service_fee"];
  $total_price = $_POST["total_price"];
  $movie_title = $_POST["movie_title"];
  $movie_image = $_POST["movie_image"];
  $movie_year = $_POST["movie_year"];
  $movie_rating = $_POST["movie_rating"];

  // Organize data into an associative array
  $formData = array(
    'name' => $name,
    'address' => $address,
    'city' => $city,
    'zip_code' => $zip_code,
    'country' => $country,
    'cardholder_name' => $cardholder_name,
    'card_number' => $card_number,
    'exp_month' => $exp_month,
    'exp_year' => $exp_year,
    'cvc_number' => $cvc_number,
    'movie_id' => $movie_id,
    'order_at' => $order_at,
    'admin_fee' => $admin_fee,
    'service_fee' => $service_fee,
    'total_price' => $total_price,
    'user_id' => $user_id,
    'movie_title' => $movie_title,
    'movie_year' => $movie_year,
    'movie_rating' => $movie_rating,
    'movie_image' => $movie_image,
  );

  $data = $payment->createPayment($formData);

  if (isset($data['id'])) {
    Authentication::navigation('invoice.php?id=' . $data['id'] . '&payment=successfull');
  } else {
    Authentication::navigation('home.php');
  }
} else {
  Authentication::navigation('home.php');
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $movie_title ?> | Payment</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="xl:container mx-auto px-4 sm:px-10">
    <header class="py-4">
      <div class="w-full flex items-center justify-between gap-4">
        <a href="home.php" class="text-2xl font-bold mb-4 text-center">
          <span class="text-blue-500">Badas</span>Film
        </a>
        <div class="flex items-center gap-4">
          <?php if(empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])): ?>
            <a href="login.php"
              class="block focus:outline-none text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
              Login
            </a>
          <?php else: ?>
            <div class="flex items-center gap-2">
              <?php if(!empty($_SESSION['admin_id'])): ?>
                <a href="dashboard.php" class="block px-2 py-1 -mt-1.5 text-gray-700">
                  <img src="../assets/dashboard-icon.svg" alt="dashboard-icon" class="w-[22px] aspect-square object-cover">
                </a>
              <?php endif ?>
              <a href="list-invoice.php" class="block px-2 py-1 -mt-1.5 text-gray-700">
                <img src="../assets/invoice-icon.svg" alt="invoice-icon" class="w-[32px] aspect-square object-cover">
              </a>
            </div>
            <a href="logout.php"
              class="block focus:outline-none text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
              Logout
            </a>
          <?php endif ?>
        </div>
      </div>
    </header>
    <main class="py-10 max-w-6xl mx-auto">
      <div class="flex items-center justify-between gap-4 flex-wrap">
        <button onclick="history.back()" class="inline-block mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" viewBox="0 0 476.213 476.213" xml:space="preserve">
            <polygon points="476.213,223.107 57.427,223.107 151.82,128.713 130.607,107.5 0,238.106 130.607,368.714 151.82,347.5   57.427,253.107 476.213,253.107 "/>
          </svg>
        </button>
        <h2 class="mb-4 text-3xl sm:text-4xl font-bold text-gray-900">Pembayaran</h2>
      </div>
      <div class="w-full grid grid-cols-12 gap-y-10 sm:gap-x-10 mt-10">
        <div class="col-span-12 sm:col-span-4">
          <div class="relative w-full max-w-[200px] sm:max-w-[300px] mx-auto sm:mx-0 rounded-xl aspect-[2/3] overflow-hidden border">
            <img src="<?= $movie_image ?>" alt="poster" class="absolute inset-0 bg-cover">
          </div>
        </div>
        <div class="col-span-12 sm:col-span-8 space-y-10">
          <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium">Warning alert!</span> Silahkan lakukan pembayaran segera untuk menghindari
              kesalahan sistem.
            </div>
          </div>
          <div class="w-full">
            <div class="flex flex-col sm:flex-row sm:gap-4 sm:items-center sm:justify-between">
              <h2 class="mb-4 text-3xl sm:text-4xl font-bold text-gray-600">Order Detail</h2>
              <h4 class="text-2xl font-bold mb-4">
                <span class="text-blue-500">Badas</span>Film
              </h4>
            </div>
            <div class="sm:text-right">
              <h6 class="font-medium text-xl">Cihideung Corporation</h6>
              <address class="not-italic text-gray-600">Gg. Delima, Tugujaya, Cihideung, Tasikmalaya</address>
              <span class="block mt-2 text-gray-400"><?= $created_at ?></span>
            </div>
          </div>
          <div class="w-full relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                  <th scope="col" class="px-6 py-3">
                    Judul Film
                  </th>
                  <th scope="col" class="px-6 py-3 rounded-r-lg">
                    Harga
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="bg-white">
                  <td class="px-6 py-4">
                    <?= $movie_title ?>
                  </td>
                  <td class="px-6 py-4">
                    Rp<?= number_format($movie_price) ?>
                  </td>
                </tr>
              </tbody>
              <tfoot class="text-right">
                <tr class="font-semibold text-gray-900">
                  <td class="px-6 py-3">Admin Fee</td>
                  <td class="px-6 py-3"><?= 'Rp' . number_format($admin_fee) ?></td>
                </tr>
                <tr class="font-semibold text-gray-900">
                  <td class="px-6 py-3">Service Payment Fee</td>
                  <td class="px-6 py-3"><?= 'Rp' . number_format($service_fee) ?></td>
                </tr>
                <tr class="font-bold text-gray-900 border-t border-t-2 text-lg">
                  <td class="px-6 py-3">Total</td>
                  <td class="px-6 py-3"><?= 'Rp' . number_format($movie_price + $admin_fee + $service_fee) ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <form method="post" action="payment.php" class="w-full bg-white grid grid-cols-1 lg:grid-cols-2 gap-10">
        <input type="hidden" name="payment" value="payment">
        <input type="hidden" name="movie_id" value="<?= $movie_id; ?>">
        <input type="hidden" name="movie_title" value="<?= $movie_title; ?>">
        <input type="hidden" name="movie_rating" value="<?= $movie_rating; ?>">
        <input type="hidden" name="movie_year" value="<?= $movie_year; ?>">
        <input type="hidden" name="movie_image" value="<?= $movie_image; ?>">
        <input type="hidden" name="order_at" value="<?= $created_at; ?>">
        <input type="hidden" name="admin_fee" value="<?= $admin_fee; ?>">
        <input type="hidden" name="service_fee" value="<?= $service_fee; ?>">
        <input type="hidden" name="total_price" value="<?= $movie_price + $admin_fee + $service_fee; ?>">

        <div class="py-8">
          <h2 class="mb-4 text-xl font-bold text-gray-900">Informasi Pembayaran</h2>
          <div>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <div class="sm:col-span-2">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                <input type="text" name="name" id="name"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan nama lengkap" required="">
              </div>
              <div class="sm:col-span-2">
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900">Alamat Lengkap</label>
                <input type="text" name="address" id="address"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan alamat lengkap" required="">
              </div>
              <div class="w-full">
                <label for="city" class="block mb-2 text-sm font-medium text-gray-900">Kota</label>
                <input type="text" name="city" id="city"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan nama kota" required="">
              </div>
              <div class="w-full">
                <label for="zip_code" class="block mb-2 text-sm font-medium text-gray-900">Kode ZIP</label>
                <input type="number" name="zip_code" id="zip_code"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan kode ZIP" required="">
              </div>
              <div class="sm:col-span-2">
                <label for="country" class="block mb-2 text-sm font-medium text-gray-900">Negara</label>
                <select id="country" name="country"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" required="">
                  <option selected="">Pilih negara</option>
                  <option value="Japan">Japan</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="USA">USA</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="py-8">
          <h2 class="mb-4 text-xl font-bold text-gray-900">Informasi Kartu Kredit</h2>
          <div>
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <div class="sm:col-span-2">
                <label for="cardholder_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Pemilik
                  Kartu</label>
                <input type="text" name="cardholder_name" id="cardholder_name"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan nama pemilik kartu" required="">
              </div>
              <div class="sm:col-span-2">
                <label for="card_number" class="block mb-2 text-sm font-medium text-gray-900">Nomor Kartu</label>
                <input type="text" name="card_number" id="card_number"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan nomor kartu" required="">
              </div>
              <div class="w-full">
                <label for="exp_month" class="block mb-2 text-sm font-medium text-gray-900">Bulan Kadaluarsa</label>
                <input type="number" name="exp_month" id="exp_month"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan bulan kadaluarsa" required="">
              </div>
              <div class="w-full">
                <label for="exp_year" class="block mb-2 text-sm font-medium text-gray-900">Tahun Kadaluarsa</label>
                <input type="number" name="exp_year" id="exp_year"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan tahun kadaluarsa" required="">
              </div>
              <div class="sm:col-span-2">
                <label for="cvc_number" class="block mb-2 text-sm font-medium text-gray-900">Nomor CVC</label>
                <input type="text" name="cvc_number" id="cvc_number"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                  placeholder="Masukan nomor CVC" required="">
              </div>
              <div class="sm:col-span-2 flex items-center justify-end gap-4 pt-10">
                <button type="submit" name="payment"
                  class="w-max text-gray-500 bg-white border-2 border-gray-400 focus:ring-4 focus:ring-gray-300 font-medium rounded-full px-5 py-2.5 mr-2 mb-2 cursor-pointer"
                  onclick="history.back()">
                  Kembali
                </button>
                <button type="submit"
                  class="w-max text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full px-5 py-2.5 mr-2 mb-2 cursor-pointer">
                  Proses Pembayaran
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </main>
  </div>
</body>

</html>