<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\ViewModels\TvViewModel;
use App\ViewModels\TvShowViewModel;
use Illuminate\Support\Facades\Http;

class TvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $popularTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/popular')
            ->json()['results'] ?? [];
    
        $topRatedTv = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/tv/top_rated')
            ->json()['results'] ?? [];
    
        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/tv/list')
            ->json()['genres'] ?? [];
    
        $viewModel = new TvViewModel($popularTv, $topRatedTv, $genres);
    
        // ✅ Truyền dữ liệu từ ViewModel vào view
        return view('tv.index', [
            'popularTv' => $viewModel->popularTv(),
            'topRatedTv' => $viewModel->topRatedTv(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        $tvshow = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/tv/{$id}?append_to_response=credits,videos,images")
            ->json();

        $viewModel = new TvShowViewModel($tvshow);

        return response()->view('tv.show', compact('viewModel'));
    }
}
