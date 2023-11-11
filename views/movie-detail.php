<?php
require_once '../models/Authentication.php';
session_start();

if (!isset($_GET['id'])) {
  Authentication::navigation('home.php');
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Film | BadasFilm</title>
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
    <main class="pt-4 sm:pt-10 pb-10">
      <a href="home.php" class="inline-block mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="20px" width="20px" version="1.1" id="Layer_1" viewBox="0 0 476.213 476.213" xml:space="preserve">
          <polygon points="476.213,223.107 57.427,223.107 151.82,128.713 130.607,107.5 0,238.106 130.607,368.714 151.82,347.5   57.427,253.107 476.213,253.107 "/>
        </svg>
      </a>
      <div class="w-full flex flex-col md:flex-row items-start gap-4 sm:gap-10">
        <div class="md:basis-[40%] w-full">
          <div class="relative w-full aspect-[2/3] rounded-xl overflow-hidden bg-gray-100 animate-pulse">
            <img
              id="movie-cover"
              src=""
              alt="detail-film"
              class="hidden w-full h-full object-cover"
            >
            <span id="movie-sold" class="absolute bottom-4 right-3 py-2 px-4 rounded-full text-lg font-semibold bg-white animate-pulse">
              Loading...
            </span>
          </div>
          <div class="mt-4 space-y-6">
            <h6 id="movie-price" class="text-4xl font-bold tracking-wide" data-price="100000">
              Rp100,000
            </h6>
            <div class="space-y-2">
              <span class="block text-gray-500 text-sm italic">
                Dengan membeli film anda berarti setuju dengan S&K yang berlaku<sup>*</sup>
              </span>
              <form action="payment.php" method="post">
                <input type="hidden" name="movie_title">
                <input type="hidden" name="movie_id">
                <input type="hidden" name="movie_image">
                <input type="hidden" name="movie_year">
                <input type="hidden" name="movie_rating">
                <input type="hidden" name="movie_price" value="100000">
                <input type="hidden" name="admin_fee" value="7500">
                <input type="hidden" name="service_fee" value="10000">
                <input type="hidden" name="created_at" value="<?php echo date('j F Y'); ?>">
                <button
                  type="submit"
                  name="buy"
                  class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 mr-2 mb-2 cursor-pointer"
                >
                  Beli Lisensi Film
                </button>
              </form>
            </div>
          </div>
        </div>
        <div class="w-full md:basis-[60%]">
          <div class="w-full flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-2">
              <h1 id="movie-title" class="text-2xl sm:text-3xl font-bold animate-pulse">Loading...</h1>
              <span id="movie-year" class="block text-gray-500 text-lg sm:text-xl animate-pulse">Loading...</span>
            </div>
            <div class="flex items-center gap-2">
              <span id="movie-rating" class="text-xl sm:text-2xl font-semibold">NaN</span>
              <svg xmlns="http://www.w3.org/2000/svg" height="30px" width="30px" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 543 516.4" style="enable-background:new 0 0 543 516.4;" xml:space="preserve">
                <style type="text/css">
                  .st0{fill:#FFC901;}
                </style>
                <g>
                  <g>
                    <polygon class="st0" points="271.5,0 355.4,170 543,197.3 407.2,329.6 439.3,516.4 271.5,428.2 103.7,516.4 135.8,329.6 0,197.3     187.6,170   "/>
                  </g>
                </g>
              </svg>
            </div>
          </div>
          <div class="mt-6">
            <h2 class="w-max uppercase text-base sm:text-lg tracking-wider font-semibold border-b-[4px] border-blue-500">Overview</h2>
            <div class="mt-4 space-y-2">
              <p class="text-justify text-base md:text-lg">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur rem, rerum, recusandae exercitationem sunt neque delectus quaerat tempore natus enim veritatis eius corrupti ab sed asperiores quod. Vitae, assumenda illo!
              </p>
              <p class="text-justify text-base md:text-lg">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Similique maiores repellat laudantium, cupiditate vero dicta nam quo amet asperiores non corporis ea doloremque sapiente repudiandae pariatur voluptatum maxime fuga eligendi dolores. Consequatur, vero debitis? Qui voluptatibus ipsa vel nulla ducimus.
              </p>
            </div>
          </div>
          <div class="mt-6">
            <h2 class="w-max uppercase text-base sm:text-lg tracking-wider font-semibold border-b-[4px] border-blue-500">Trailer</h2>
            <div class="mt-4">
              <iframe
                class="w-full aspect-video rounded-lg shadow-xl"
                src="https://www.youtube.com/embed/6ZfuNTqbHE8?si=2wSOMvFIHorqw8Y4"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              >
              </iframe>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script type="module">
    import Movie from '../models/Movie.js';

    const urlSearchParams = new URLSearchParams(window.location.search);
    const id = urlSearchParams.get('id');
    const options = {
      method: 'GET',
      headers: {
        'X-RapidAPI-Key': '8372c7057bmshd81f06efd0e51bep1bdee4jsnad29e9fe608c',
        'X-RapidAPI-Host': 'moviesdatabase.p.rapidapi.com'
      }
    };

    function setInputFormMovie(movieData) {
      document.querySelector('input[name="movie_title"]').value = movieData.title;
      document.querySelector('input[name="movie_id"]').value = movieData.id;
      document.querySelector('input[name="movie_image"]').value = movieData.image;
      document.querySelector('input[name="movie_year"]').value = movieData.year;
      document.querySelector('input[name="movie_rating"]').value = movieData.rating;
    }

    Promise.all([Movie.getMovieDetail(id, options), Movie.getMovieRating(id, options)])
      .then(([movie, ratings]) => {
        const srcImage = document.getElementById('movie-cover');
        const title = document.getElementById('movie-title');
        const year = document.getElementById('movie-year');
        const rating = document.getElementById('movie-rating');
        const sold = document.getElementById('movie-sold');

        if (movie?.results) {
          srcImage.parentElement.classList.remove('animate-pulse');
          srcImage.classList.remove('hidden');
          srcImage.src = movie.results.primaryImage?.url || '';

          title.classList.remove('animate-pulse');
          title.textContent = movie.results.titleText.text;

          year.classList.remove('animate-pulse');
          year.textContent = movie.results.releaseYear?.year || '-';

          rating.textContent = ratings?.results?.averageRating || 'NaN';

          sold.classList.remove('animate-pulse');
          sold.textContent = `${Math.round(Math.random() * 500)} Terjual`;

          setInputFormMovie({
            title: movie.results.titleText.text,
            id: movie.results.id,
            image: movie.results.primaryImage?.url || '',
            year: movie.results.releaseYear?.year || '-',
            rating: ratings?.results?.averageRating || 'NaN'
          });
        } else {
          alert('Film detail tidak ditemukan! Halaman dialihkan ke home.');
          window.location = 'home.php';
        }
      })
      .catch(error => {
        alert(error?.message || JSON.stringify(error));
      });
  </script>
</body>
</html>