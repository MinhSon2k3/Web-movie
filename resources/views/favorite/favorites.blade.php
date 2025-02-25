@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-16">
    <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Danh sách phim yêu thích</h2>
    <br>
    <br>
    <div id="favorite-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        <!-- Danh sách phim yêu thích sẽ hiển thị ở đây -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const favoriteList = document.getElementById('favorite-list');
    let favorites = JSON.parse(localStorage.getItem('favorites')) || [];

    if (favorites.length === 0) {
        favoriteList.innerHTML = `<p class="text-gray-400 text-lg">Không có phim yêu thích nào.</p>`;
        return;
    }

    favorites.forEach(movie => {
        let movieItem = document.createElement('div');
        movieItem.classList.add('bg-gray-800', 'rounded-lg', 'shadow-md', 'p-4', 'relative');

        movieItem.innerHTML = `
            <a href="/movies/${movie.id}" class="block">
                <img src="${movie.poster_path}" class="w-full h-auto rounded-md hover:opacity-75 transition duration-200">
            </a>
            <h3 class=" ml-4 text-lg font-semibold text-white mt-2">${movie.title}</h3>
            <p class=" ml-4 text-sm text-gray-400">${movie.release_date}</p>
            <br>
           <button class="ml-4 bg-orange-500 text-gray-900 rounded font-semibold px-5 py-2 hover:bg-orange-600 transition ease-in-out duration-150 remove-favorite" data-id="${movie.id}">Xóa khỏi danh sách</button>  
        `;

        favoriteList.appendChild(movieItem);
    });

    // Xóa phim yêu thích
    document.querySelectorAll('.remove-favorite').forEach(button => {
        button.addEventListener('click', function() {
            let movieId = this.dataset.id;
            favorites = favorites.filter(movie => movie.id != movieId);
            localStorage.setItem('favorites', JSON.stringify(favorites));
            this.closest('div').remove();
        });
    });
});
</script>
@endsection
