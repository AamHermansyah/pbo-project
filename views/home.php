<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BadasFilm</title>
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
    <main class="py-10">
      <?php if(!empty($_SESSION['name'])): ?>
        <h1>Selamat datang <b><?php echo $_SESSION['name'] ?></b></h1>
      <?php endif ?>
      <form class="w-full max-w-2xl mt-2">
        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
            </svg>
          </div>
          <input
            type="search"
            id="search"
            class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search" required>
          <button
            type="submit"
            id="search-btn"
            class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2"
          >
            Search
          </button>
        </div>
      </form>
      <div class="movie-not-found hidden my-10">
        <h2 class="text-center">
          Film tidak dapat ditemukan!
        </h2>
      </div>
      <div
        id="movie-container"
        class="w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 place-items-center my-10"
      >
        <!-- Daftar film ditampilkan disini -->
      </div>
      <div class="movie-loading hidden w-full h-[200px] flex justify-center items-center my-10">
        <div role="status">
          <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101"
            fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="currentColor" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentFill" />
          </svg>
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <div class="text-center">
        <button
          id="load-more"
          type="submit"
          class="hidden text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 cursor-pointer"
        >
          Load More
        </button>
      </div>
    </main>
  </div>
  <script type="module">
    import Movie from '../models/Movie.js';

    const movieApp = new Movie();
  </script>
</body>

</html>