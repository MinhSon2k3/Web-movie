<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class MoviesViewModel extends ViewModel
{
    public function __construct(
        private readonly array $popularMovies,
        private readonly array $nowPlayingMovies,
        private readonly array $genres
    ) {}

    public function popularMovies()
    {
        return $this->formatMovies($this->popularMovies);
    }

    public function nowPlayingMovies()
    {
        return $this->formatMovies($this->nowPlayingMovies);
    }

    public function genres()
    {
        return collect($this->genres)->mapWithKeys(fn($genre) => [$genre['id'] => $genre['name']]);
    }

    private function formatMovies(array $movies)
    {
        $genres = $this->genres(); // Tránh gọi lại trong vòng lặp

        return collect($movies)->map(fn($movie) => [
            'poster_path'   => 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'],
            'id'            => $movie['id'],
            'title'         => $movie['title'],
            'vote_average'  => $movie['vote_average'] * 10 . '%',
            'overview'      => $movie['overview'],
            'release_date'  => Carbon::parse($movie['release_date'])->toFormattedDateString(),
            'genres'        => collect($movie['genre_ids'])->map(fn($id) => $genres->get($id))->implode(', '),
        ]);
    }
}
