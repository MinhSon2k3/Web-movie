<?php

namespace App\Http\Controllers;

use App\ViewModels\MovieViewModel;
use App\ViewModels\MoviesViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $apiToken = config('services.tmdb.token');

        $popularMovies = Http::acceptJson()->withToken($apiToken)
            ->get('https://api.themoviedb.org/3/movie/popular')
            ->throw()
            ->json('results', []);

        $nowPlayingMovies = Http::acceptJson()->withToken($apiToken)
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->throw()
            ->json('results', []);

        $genres = Http::acceptJson()->withToken($apiToken)
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->throw()
            ->json('genres', []);

        $viewModel = new MoviesViewModel($popularMovies, $nowPlayingMovies, $genres);

        return view('movies.index', $viewModel);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $apiToken = config('services.tmdb.token');

        $movie = Http::acceptJson()->withToken($apiToken)
            ->get("https://api.themoviedb.org/3/movie/{$id}?append_to_response=credits,videos,images")
            ->throw()
            ->json();

        $viewModel = new MovieViewModel($movie);

        return view('movies.show', $viewModel);
    }
}
