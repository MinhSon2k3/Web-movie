<?php

namespace App\ViewModels;

use Carbon\Carbon;

class MovieViewModel
{
    public function __construct(private readonly array $movie) {}

    public function movie(): array
    {
        return [
            'poster_path'   => $this->movie['poster_path']
                ? 'https://image.tmdb.org/t/p/w500/' . $this->movie['poster_path']
                : 'https://via.placeholder.com/500x750',
            'vote_average'  => $this->movie['vote_average'] * 10 . '%',
            'release_date'  => Carbon::parse($this->movie['release_date'])->format('M d, Y'),
            'genres'        => collect($this->movie['genres'])->pluck('name')->implode(', '),
            'crew'          => collect($this->movie['credits']['crew'])->take(2),
            'cast'          => collect($this->movie['credits']['cast'])
                ->take(5)
                ->map(fn($cast) => [
                    'name'         => $cast['name'],
                    'character'    => $cast['character'],
                    'profile_path' => $cast['profile_path']
                        ? 'https://image.tmdb.org/t/p/w300' . $cast['profile_path']
                        : 'https://via.placeholder.com/300x450',
                ]),
            'images'        => collect($this->movie['images']['backdrops'])->take(9),
        ];
    }
}
