<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ViewModels\ActorViewModel;
use App\ViewModels\ActorsViewModel;
use Illuminate\Support\Facades\Http;

class ActorsController extends Controller
{
    /**
     * Hiển thị danh sách các diễn viên phổ biến.
     *
     * @param  int  $page
     * @return \Illuminate\View\View
     */
    public function index($page = 1)
    {
        abort_if($page > 500, 204);

        $popularActors = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/popular?page=' . $page)
            ->json()['results'] ?? [];

        $viewModel = new ActorsViewModel($popularActors, $page);

        return view('actors.index', [
            'popularActors' => $viewModel->popularActors(),
            'page' => $viewModel->page(),
            'previous' => $viewModel->previous(),  // Thêm dòng này
            'next' => $viewModel->next(),         // Thêm dòng này
        ]);
    }

    /**
     * Hiển thị thông tin chi tiết về một diễn viên.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $actor = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/'.$id)
            ->json();
    
        $social = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/'.$id.'/external_ids')
            ->json();
    
        $credits = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/person/'.$id.'/combined_credits')
            ->json();
    
        // Khởi tạo ViewModel
        $viewModel = new ActorViewModel($actor, $social, $credits);
    
        return view('actors.show', [
            'actor' => $viewModel->actor(),
            'social' => $viewModel->social(),
            'knownForMovies' => $viewModel->knownForMovies(),  // ✅ Thêm biến này
            'credits' => $viewModel->credits(),
        ]);
    }
    
}
