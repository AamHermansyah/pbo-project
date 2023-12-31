<?php
require_once '../models/Database.php';
require_once '../models/Authentication.php';
require_once '../models/Payment.php';
session_start();

if (empty($_SESSION['admin_id'])) {
  Authentication::navigation('login.php');
}

$db = new Database();
$payment = new Payment($db->getConnection());

$data = $payment->getInvoices(1, 10);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | BadasFilm</title>
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
        <h2 class="mb-8 text-3xl sm:text-4xl font-bold text-gray-900">Dashboard Admin</h2>
        <span>Selamat datang admin <b><?= $_SESSION['name'] ?></b>!</span>
      </div>
      <div class="space-y-10">
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
                <th scope="col" class="px-6 py-3 rounded-r-lg">
                  Nama Pengguna
                </th>
                <th scope="col" class="px-6 py-3 rounded-r-lg">
                  Total Harga
                </th>
                <th scope="col" class="px-6 py-3 rounded-r-lg">
                  Tanggal
                </th>
                <th scope="col" class="px-6 py-3 rounded-r-lg">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $movie): ?>
                <tr class="bg-white">
                  <td class="px-6 py-4"><?= $movie['movie_title']; ?></td>
                  <td class="px-6 py-4">Rp100,000</td>
                  <td class="px-6 py-4"><?= $movie['fullname']; ?></td>
                  <td class="px-6 py-4"><?= 'Rp' . number_format($movie['total_price']); ?></td>
                  <td class="px-6 py-4"><?= $movie['order_at']; ?></td>
                  <td class="px-6 py-4">
                    <a
                      href="invoice.php?id=<?= $movie['invoice_id'] ?>&isAdmin=true"
                      class="block w-max text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 mr-2 mb-2 cursor-pointer"
                    >
                      Detail
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if (empty($data)): ?>
            <span class="block text-center px-4 py-10">Invoice masih kosong!</span>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>
</body>
</html>