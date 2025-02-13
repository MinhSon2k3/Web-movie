<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class ActorsViewModel extends ViewModel
{
    protected array $popularActors;
    protected int $page;

    public function __construct(array $popularActors, int $page)
    {
        $this->popularActors = $popularActors;
        $this->page = $page;
    }

    public function popularActors(): array
    {
        return collect($this->popularActors)->map(function ($actor) {
            return [
                'id' => $actor['id'] ?? null,
                'name' => $actor['name'] ?? 'Unknown',
                'profile_path' => !empty($actor['profile_path'])
                    ? 'https://image.tmdb.org/t/p/w235_and_h235_face' . $actor['profile_path']
                    : 'https://ui-avatars.com/api/?size=235&name=' . urlencode($actor['name'] ?? 'Unknown'),
                'known_for' => $this->formatKnownFor($actor['known_for'] ?? []),
            ];
        })->all();
    }

    private function formatKnownFor(array $knownFor): string
    {
        $movies = collect($knownFor)->where('media_type', 'movie')->pluck('title');
        $tvShows = collect($knownFor)->where('media_type', 'tv')->pluck('name');

        return $movies->merge($tvShows)->implode(', ');
    }

    public function page(): int
    {
        return $this->page;
    }

    public function previous(): ?int
    {
        return $this->page > 1 ? $this->page - 1 : null;
    }

    public function next(): ?int
    {
        return $this->page < 500 ? $this->page + 1 : null;
    }
}
