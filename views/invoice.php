<?php
require_once '../models/Database.php';
require_once '../models/Authentication.php';
require_once '../models/Payment.php';
session_start();

if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])) {
  Authentication::navigation('login.php');
}

$db = new Database();
$payment = new Payment($db->getConnection());

if (isset($_GET["id"])) {
  $id = $_GET["id"];
  $data = $payment->getInvoiceById($id);

  if (empty($data)) {
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
  <title><?= $data["name"] ?> | Invoice</title>
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
      <a href="list-invoice.php" class="inline-block mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" viewBox="0 0 476.213 476.213" xml:space="preserve">
          <polygon points="476.213,223.107 57.427,223.107 151.82,128.713 130.607,107.5 0,238.106 130.607,368.714 151.82,347.5   57.427,253.107 476.213,253.107 "/>
        </svg>
      </a>
      <div class="space-y-10">
        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-10">
          <div class="relative w-full max-w-[200px] mx-auto sm:mx-0 rounded-xl aspect-[2/3] overflow-hidden border">
            <img src="<?= $data["movie_image"] ?>" alt="poster" class="absolute inset-0 bg-cover">
          </div>
          <div class="w-full">
            <?php if (isset($_GET["payment"]) && $_GET["payment"] == 'successfull'): ?>
              <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <div>
                  <span class="font-medium">Selamat menonton!</span> Pembayaran berhasil dilakukan.
                </div>
              </div>
            <?php endif; ?>
            <div class="flex flex-col sm:flex-row sm:gap-4 sm:items-center sm:justify-between">
              <h2 class="mb-4 text-3xl sm:text-4xl font-bold text-gray-600">Invoice#<?= $data["id"]; ?></h2>
              <h4 class="text-2xl font-bold mb-4">
                <span class="text-blue-500">Badas</span>Film
              </h4>
            </div>
            <div class="sm:text-right">
              <h6 class="font-medium text-xl">Cihideung Corporation</h6>
              <address class="not-italic text-gray-600">Gg. Delima, Tugujaya, Cihideung, Tasikmalaya</address>
              <span class="block mt-2 text-gray-400"><?= $data["order_at"] ?></span>
            </div>
          </div>
        </div>
        <div class="w-full relative overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
              <tr>
                <th scope="col" class="px-6 py-3">
                  Deksripsi Film
                </th>
                <th scope="col" class="px-6 py-3 rounded-r-lg"></th>
              </tr>
            </thead>
            <tbody>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Judul Film
                </td>
                <td id="movie-title" class="px-6 py-4">
                  : <?= $data["movie_title"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Tahun Rilis
                </td>
                <td id="movie-year" class="px-6 py-4">
                  : <?= $data["movie_year"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Rating
                </td>
                <td id="movie-rating" class="px-6 py-4">
                  : <?= $data["movie_rating"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Harga
                </td>
                <td class="px-6 py-4">
                  : Rp100,000
                </td>
              </tr>
            </tbody>
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
              <tr>
                <th scope="col" class="px-6 py-3">
                  Informasi Pembeli
                </th>
                <th scope="col" class="px-6 py-3 rounded-r-lg"></th>
              </tr>
            </thead>
            <tbody>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Nama
                </td>
                <td class="px-6 py-4">
                  : <?= $data["name"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Alamat
                </td>
                <td class="px-6 py-4">
                  : <?= $data["address"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Kota
                </td>
                <td class="px-6 py-4">
                  : <?= $data["city"] . ', ' . $data["country"]; ?>
                </td>
              </tr>
            </tbody>
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
              <tr>
                <th scope="col" class="px-6 py-3">
                  Informasi Kartu Kredit
                </th>
                <th scope="col" class="px-6 py-3 rounded-r-lg"></th>
              </tr>
            </thead>
            <tbody>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Nama Pemilik Kartu
                </td>
                <td class="px-6 py-4">
                  : <?= $data["cardholder_name"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Nomor Kartu
                </td>
                <td class="px-6 py-4">
                  : <?= $data["card_number"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Kadaluarsa Kartu
                </td>
                <td class="px-6 py-4">
                  : <?= $data["exp_month"] . '-' . $data["exp_year"]; ?>
                </td>
              </tr>
              <tr class="bg-white">
                <td class="px-6 py-4">
                  Nomor CVC
                </td>
                <td class="px-6 py-4">
                  : <?= $data["cvc_number"]; ?>
                </td>
              </tr>
            </tbody>
            <tfoot class="text-right">
              <tr class="font-semibold text-gray-900">
                <td class="px-6 py-3">Admin Fee</td>
                <td class="px-6 py-3"><?= 'Rp' . number_format($data["admin_fee"]) ?></td>
              </tr>
              <tr class="font-semibold text-gray-900">
                <td class="px-6 py-3">Service Payment Fee</td>
                <td class="px-6 py-3"><?= 'Rp' . number_format($data["service_fee"]) ?></td>
              </tr>
              <tr class="font-bold text-gray-900 border-t border-t-2 text-lg">
                <td class="px-6 py-3">Total</td>
                <td class="px-6 py-3"><?= 'Rp' . number_format($data["total_price"]);?></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div>
          <a
            href="list-invoice.php"
            class="block text-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 mr-2 mb-2 cursor-pointer"
          >
            Selesai
          </a>
        </div>
      </div>
    </main>
  </div>
</body>
</html>