class Movie {
  constructor() {
    this.movieLoader = document.querySelector('.movie-loading');
    this.query = 'list=most_pop_movies';
    this.keywordSearch = '';
    this.options = {
      method: 'GET',
      headers: {
        'X-RapidAPI-Key': '8372c7057bmshd81f06efd0e51bep1bdee4jsnad29e9fe608c',
        'X-RapidAPI-Host': 'moviesdatabase.p.rapidapi.com'
      }
    };

    this.searchButton = document.getElementById('search-btn');
    this.searchInput = document.getElementById('search');
    this.loadMoreButton = document.getElementById('load-more');
    this.movieNotFoundText = document.querySelector('.movie-not-found');
    this.movieContainer = document.getElementById('movie-container');

    this.loadMoreButton.addEventListener('click', () => {
      if (this.keywordSearch !== '') {
        this.searchMovie(true);
      } else {
        this.fetchAll();
      }
    });

    this.searchButton.addEventListener('click', (e) => {
      e.preventDefault();
      this.keywordSearch = this.searchInput.value;
      this.searchMovie();
    });

    this.fetchAll();
  }

  searchMovie(isLoadMore = false) {
    if (!isLoadMore) this.resetDisplayAndQuery();
    else this.loadMoreButton.classList.add('hidden');

    if (this.keywordSearch) {
      this.movieLoader.classList.remove('hidden');
      fetch(`https://moviesdatabase.p.rapidapi.com/titles/search/title/${this.keywordSearch}?${this.query}`, this.options)
        .then(response => {
          if (!response.ok) {
            throw new Error('Gagal mengambil data dari API');
          }
          return response.json();
        })
        .then((data) => {
          this.movieLoader.classList.add('hidden');

          if (data?.results) this.addCards(data.results);
          if (data.next !== null) {
            this.query = data.next.replace('/titles/search/title/spider?', '');
            this.loadMoreButton.classList.remove('hidden');
          }
        })
        .catch(error => {
          alert(error?.message || JSON.stringify(error));
        });
    } else {
      this.fetchAll();
    }
  }

  fetchAll() {
    this.movieLoader.classList.remove('hidden');

    fetch('https://moviesdatabase.p.rapidapi.com/titles?' + this.query, this.options)
      .then(response => {
        if (!response.ok) {
          throw new Error('Gagal mengambil data dari API');
        }
        return response.json();
      })
      .then((data) => {
        this.movieLoader.classList.add('hidden');

        if (data?.results) this.addCards(data.results);
        if (data.next !== null) {
          this.query = data.next.replace('/titles?', '');
          this.loadMoreButton.classList.remove('hidden');
        }
      })
      .catch(error => {
        alert(error?.message || JSON.stringify(error));
      });
  }

  resetDisplayAndQuery() {
    this.query = 'list=most_pop_movies';

    this.loadMoreButton.classList.add('hidden');
    this.movieNotFoundText.classList.add('hidden');
    this.movieContainer.innerHTML = '';
  }

  addCards(data) {
    if (data.length === 0) {
      this.movieNotFoundText.classList.remove('hidden');
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

      const price = document.createElement('h2');
      price.className = 'font-semibold text-lg';
      price.innerText = `Rp${(100000).toLocaleString()}`;

      const titleH2 = document.createElement('h2');
      titleH2.className = 'uppercase font-semibold line-clamp-2 hover:text-blue-500 transition';
      titleH2.innerText = movie.titleText.text;

      // Menambahkan elemen-elemen ke dalam elemen card
      imageDiv.appendChild(image);
      textDiv.appendChild(price);
      textDiv.appendChild(titleH2);

      card.appendChild(yearSpan);
      card.appendChild(imageDiv);
      card.appendChild(textDiv);

      // Menambahkan elemen card ke dalam container
      this.movieContainer.appendChild(card);
    });
  }

  static async getMovieDetail(id, options = this.options) {
    return fetch(`https://moviesdatabase.p.rapidapi.com/titles/${id}`, options)
      .then(response => {
        if (!response.ok) {
          throw new Error('Gagal mengambil data dari API');
        }
        return response.json();
      })
  }

  static async getMovieRating(id, options = this.options) {
    return fetch(`https://moviesdatabase.p.rapidapi.com/titles/${id}/ratings`, options)
      .then(response => {
        if (!response.ok) {
          throw new Error('Gagal mengambil data dari API');
        }
        return response.json();
      })
  }
}

export default Movie;