<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SearchDropdown extends Component
{
    public string $search = ''; // Bắt buộc khai báo kiểu dữ liệu
    public array $searchResults = [];

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $response = Http::get('https://api.themoviedb.org/3/search/movie', [
            'query' => $this->search,
            'api_key' => env('TMDB_API_KEY'),
        ]);

        $this->searchResults = $response->json()['results'] ?? [];
    }

    public function render()
    {
        return view('livewire.search-dropdown');
    }
}