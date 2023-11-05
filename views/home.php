<?php
require_once '../models/Database.php';
require_once '../models/Authentication.php';
session_start();

$db = new Database();
$authentication = new Authentication($db->getConnection());

if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])) {
  $authentication->navigation('login.php');
}
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
        <a href="logout.php"
          class="inline-block focus:outline-none text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
          Logout
        </a>
      </div>
    </header>
    <main class="py-10">
      <h1>Selamat datang <b><?php echo $_SESSION['name'] ?></b></h1>
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
  <script>
    const movieLoader = document.querySelector('.movie-loading');
    let query = 'list=most_pop_movies';
    let keywordSearch = '';
    const options = {
        method: 'GET',
        headers: {
          'X-RapidAPI-Key': '8372c7057bmshd81f06efd0e51bep1bdee4jsnad29e9fe608c',
          'X-RapidAPI-Host': 'moviesdatabase.p.rapidapi.com'
        }
      };

    const searchButton = document.getElementById('search-btn');
    const searchInput = document.getElementById('search');
    const loadMoreButton = document.getElementById('load-more');
    const movieNotFoundText = document.querySelector('.movie-not-found');

    fetchAll();

    loadMoreButton.addEventListener('click', () => {
      if (keywordSearch !== '') {
        searchMovie(true);
      } else {
        fetchAll();
      }
    });

    searchButton.addEventListener('click', (e) => {
      e.preventDefault();
      keywordSearch = searchInput.value;
      searchMovie();
    });

    function searchMovie(isLoadMore = false) {
      if (!isLoadMore) resetDisplayAndQuery();
      else loadMoreButton.classList.add('hidden');

      if (keywordSearch) {
        movieLoader.classList.remove('hidden');
        fetch(`https://moviesdatabase.p.rapidapi.com/titles/search/title/${keywordSearch}?${query}`, options)
        .then(response => {
          if (!response.ok) {
            throw new Error('Gagal mengambil data dari API');
          }
          return response.json();
        })
        .then((data) => {
          movieLoader.classList.add('hidden');

          if (data?.results) addCards(data.results);
          if (data.next !== null) {
            query = data.next.replace('/titles/search/title/spider?', '');
            loadMoreButton.classList.remove('hidden');
          }
        })
        .catch(error => {
          alert(error?.message || JSON.stringify(error));
        });
      } else {
        fetchAll();
      }
    }
    
    function fetchAll() {
      movieLoader.classList.remove('hidden');

      fetch('https://moviesdatabase.p.rapidapi.com/titles?' + query, options)
        .then(response => {
          if (!response.ok) {
            throw new Error('Gagal mengambil data dari API');
          }
          return response.json();
        })
        .then((data) => {
          movieLoader.classList.add('hidden');

          if (data?.results) addCards(data.results);
          if (data.next !== null) {
            query = data.next.replace('/titles?', '');
            loadMoreButton.classList.remove('hidden');
          }
        })
        .catch(error => {
          alert(error?.message || JSON.stringify(error));
        });
    }

    function resetDisplayAndQuery() {
      query = 'list=most_pop_movies';

      loadMoreButton.classList.add('hidden');
      movieNotFoundText.classList.add('hidden');

      const element = document.getElementById('movie-container');
      element.innerHTML = '';
    }

    function addCards(data) {
      // Container untuk elemen-elemen card
      const movieContainer = document.getElementById('movie-container');

      if (data.length === 0) {
        movieNotFoundText.classList.remove('hidden');
        return;
      }

      data.forEach((movie) => {
        // Membuat elemen card
        const card = document.createElement('a');
        card.className = 'block relative card w-full space-y-2 cursor-pointer';
        card.href = `movie-detail.php?id=${movie.id}`;

        const yearSpan = document.createElement('span');
        yearSpan.className = 'block absolute top-4 right-1.5 bg-white px-2 py-1 rounded-full font-semibold z-10';
        yearSpan.innerText = movie.releaseYear?.year || '-';

        const imageDiv = document.createElement('div');
        imageDiv.className = 'w-full aspect-[2/3] bg-gray-100 rounded-md overflow-hidden';

        const image = document.createElement('img');
        if (movie.primaryImage) {
          image.className = 'w-full h-full object-cover hover:scale-[1.1] transition-all duration-200';
          image.src = movie.primaryImage.url;
        }

        const textDiv = document.createElement('div');
        textDiv.className = 'text-gray-700 text-xs sm:text-sm';

        const titleH2 = document.createElement('h2');
        titleH2.className = 'uppercase font-semibold line-clamp-2 hover:text-blue-500 transition';
        titleH2.innerText = movie.titleText.text;

        // Menambahkan elemen-elemen ke dalam elemen card
        imageDiv.appendChild(image);
        textDiv.appendChild(titleH2);

        card.appendChild(yearSpan);
        card.appendChild(imageDiv);
        card.appendChild(textDiv);

        // Menambahkan elemen card ke dalam container
        movieContainer.appendChild(card);
      });
    }
  </script>
</body>

</html>