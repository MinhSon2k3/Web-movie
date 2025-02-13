<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class TvViewModel
{
    public array $popularTv;
    public array $topRatedTv;
    public array $genres;

    public function __construct(array $popularTv, array $topRatedTv, array $genres)
    {
        $this->popularTv = $popularTv;
        $this->topRatedTv = $topRatedTv;
        $this->genres = $genres;
    }

    public function popularTv(): Collection
    {
        return $this->formatTv($this->popularTv);
    }

    public function topRatedTv(): Collection
    {
        return $this->formatTv($this->topRatedTv);
    }

    public function genres(): Collection
    {
        return collect($this->genres)->mapWithKeys(fn ($genre) => [$genre['id'] => $genre['name']]);
    }

    private function formatTv(array $tv): Collection
    {
        return collect($tv)->map(function ($tvshow) {
            $genresFormatted = collect($tvshow['genre_ids'])
                ->map(fn ($id) => $this->genres()->get($id, ''))
                ->filter()
                ->implode(', ');

            return [
                'poster_path' => $tvshow['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500/' . $tvshow['poster_path']
                    : 'https://via.placeholder.com/500x750',
                'vote_average' => isset($tvshow['vote_average']) ? $tvshow['vote_average'] * 10 . '%' : 'N/A',
                'first_air_date' => isset($tvshow['first_air_date']) 
                    ? Carbon::parse($tvshow['first_air_date'])->format('M d, Y') 
                    : 'Unknown',
                'genres' => $genresFormatted,
                'id' => $tvshow['id'] ?? null,
                'name' => $tvshow['name'] ?? 'N/A',
                'overview' => $tvshow['overview'] ?? '',
            ];
        });
    }
}
