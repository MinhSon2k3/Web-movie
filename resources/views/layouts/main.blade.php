<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Movie App</title>
    <link rel="stylesheet" href="/css/main.css">
    @livewireStyles
</head>

<body class="font-sans bg-gray-900 text-white">
    <nav class="border-b border-gray-800">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between px-4 py-6">
            <ul class="flex flex-col md:flex-row items-center">
                <li>
                    <a href="{{ route('movies.index') }}">
                        <svg class="w-32" viewBox="0 0 96 24" fill="none">
                            <path
                                d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"
                                fill="#fff" />
                        </svg>
                    </a>
                </li>
                <li class="md:ml-16 mt-3 md:mt-0">
                    <a href="{{ route('movies.index') }}" class="hover:text-gray-300">Movies</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="{{ route('tv.index') }}" class="hover:text-gray-300">TV Shows</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="{{ route('actors.index') }}" class="hover:text-gray-300">Actors</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="#" id="favorite-list-btn" class="hover:text-gray-300">Favorite list</a>
                </li>

            </ul>
            <div class="flex flex-col md:flex-row items-center">
                @livewire('search-dropdown')

                <div class="md:ml-4 mt-3 md:mt-0">
                    <a href="#">
                        <img src="/img/sgu.jpg" alt="avatar" class="rounded-full w-8 h-8">
                    </a>
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
    <footer class="border border-t border-gray-800">
        <div class="container mx-auto text-sm px-4 py-6">
            Powered by <a href="https://www.themoviedb.org/documentation/api" class="underline hover:text-gray-300">TMDb
                API</a>
        </div>
    </footer>
    <div id="favorite-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center">
        <div class="bg-gray-900 rounded-lg p-6 w-3/4 max-w-2xl">
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-xl font-semibold">Favorite Movies</h2>
                <button id="close-modal" class="text-3xl leading-none hover:text-gray-300">&times;</button>
            </div>
            <div id="favorite-list" class="mt-4 space-y-4"></div>
        </div>
    </div>

    @livewireScripts
    @yield('scripts')
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const favoriteListBtn = document.getElementById('favorite-list-btn');
    const favoriteModal = document.getElementById('favorite-modal');
    const closeModal = document.getElementById('close-modal');
    const favoriteList = document.getElementById('favorite-list');

    // Khi bấm vào "Favorite list"
    favoriteListBtn.addEventListener('click', function() {
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        favoriteList.innerHTML = '';

        if (favorites.length === 0) {
            favoriteList.innerHTML =
                '<p class="text-gray-400">Không có phim nào trong danh sách yêu thích.</p>';
        } else {
            favorites.forEach(movie => {
                let movieItem = document.createElement('div');
                movieItem.classList.add('flex', 'items-center', 'border-b', 'border-gray-700',
                    'pb-2');

                movieItem.innerHTML = `
                    <img src="${movie.poster_path}" class="w-16 h-24 rounded">
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold">${movie.title}</h3>
                        <p class="text-sm text-gray-400">${movie.release_date}</p>
                    </div>
                    <button class="ml-auto text-red-500 hover:text-red-700 remove-favorite" data-id="${movie.id}">Xóa</button>
                `;

                favoriteList.appendChild(movieItem);
            });
        }

        favoriteModal.classList.remove('hidden');
    });

    // Đóng modal khi bấm vào nút "×"
    closeModal.addEventListener('click', function() {
        favoriteModal.classList.add('hidden');
    });

    // Xóa phim khỏi danh sách yêu thích
    favoriteList.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-favorite')) {
            let movieId = event.target.dataset.id;
            let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            favorites = favorites.filter(movie => movie.id != movieId);
            localStorage.setItem('favorites', JSON.stringify(favorites));
            event.target.parentElement.remove();

            if (favorites.length === 0) {
                favoriteList.innerHTML =
                    '<p class="text-gray-400">Không có phim nào trong danh sách yêu thích.</p>';
            }
        }
    });
});
</script>

</html>