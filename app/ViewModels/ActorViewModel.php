<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class ActorViewModel extends ViewModel
{
    protected array $actor;
    protected array $social;
    protected array $credits;

    public function __construct(array $actor, array $social, array $credits)
    {
        $this->actor = $actor;
        $this->social = $social;
        $this->credits = $credits;
    }

    public function actor(): array
    {
        return [
            'id' => $this->actor['id'] ?? null,
            'name' => $this->actor['name'] ?? 'Unknown',
            'birthday' => isset($this->actor['birthday']) ? Carbon::parse($this->actor['birthday'])->format('M d, Y') : null,
            'age' => isset($this->actor['birthday']) ? Carbon::parse($this->actor['birthday'])->age : null,
            'place_of_birth' => $this->actor['place_of_birth'] ?? null,
            'homepage' => $this->actor['homepage'] ?? null,
            'biography' => $this->actor['biography'] ?? null,
            'profile_path' => !empty($this->actor['profile_path'])
                ? 'https://image.tmdb.org/t/p/w300/' . $this->actor['profile_path']
                : 'https://via.placeholder.com/300x450',
        ];
    }

    public function social(): array
    {
        return [
            'twitter' => !empty($this->social['twitter_id']) ? 'https://twitter.com/' . $this->social['twitter_id'] : null,
            'facebook' => !empty($this->social['facebook_id']) ? 'https://facebook.com/' . $this->social['facebook_id'] : null,
            'instagram' => !empty($this->social['instagram_id']) ? 'https://instagram.com/' . $this->social['instagram_id'] : null,
        ];
    }

    public function knownForMovies(): array
    {
        return collect($this->credits['cast'] ?? [])
            ->sortByDesc('popularity')
            ->take(5)
            ->map(fn($movie) => [
                'id' => $movie['id'] ?? null,
                'title' => $movie['title'] ?? $movie['name'] ?? 'Untitled',
                'poster_path' => !empty($movie['poster_path'])
                    ? 'https://image.tmdb.org/t/p/w185' . $movie['poster_path']
                    : 'https://via.placeholder.com/185x278',
                'media_type' => $movie['media_type'] ?? null,
                'linkToPage' => isset($movie['id'])
                    ? ($movie['media_type'] === 'movie'
                        ? route('movies.show', $movie['id'])
                        : route('tv.show', $movie['id']))
                    : null,
            ])
            ->all();
    }

    public function credits(): array
    {
        return collect($this->credits['cast'] ?? [])
            ->map(fn($movie) => [
                'release_date' => $movie['release_date'] ?? $movie['first_air_date'] ?? null,
                'release_year' => isset($movie['release_date']) || isset($movie['first_air_date'])
                    ? Carbon::parse($movie['release_date'] ?? $movie['first_air_date'])->format('Y')
                    : 'Future',
                'title' => $movie['title'] ?? $movie['name'] ?? 'Untitled',
                'character' => $movie['character'] ?? '',
                'linkToPage' => isset($movie['id'])
                    ? ($movie['media_type'] === 'movie'
                        ? route('movies.show', $movie['id'])
                        : route('tv.show', $movie['id']))
                    : null,
            ])
            ->sortByDesc('release_date')
            ->all();
    }
}
