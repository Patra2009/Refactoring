<?php

namespace App\Http\Controllers;

use App\Services\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(Request $request)
    {
        $movies = $this->movieService->getMovies($request->input('search'));
        return view('homepage', compact('movies'));
    }

    public function create()
    {
        $categories = $this->movieService->getCategories();
        return view('input', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->movieService->storeMovie($request);
        return redirect('/')->with('success', 'Data berhasil disimpan');
    }

    public function detail($id)
    {
        $movie = $this->movieService->getById($id);
        return view('detail', compact('movie'));
    }

    public function data()
    {
        $movies = $this->movieService->getAllPaginated();
        return view('data-movies', compact('movies'));
    }

    public function form_edit($id)
    {
        $movie = $this->movieService->getById($id);
        $categories = $this->movieService->getCategories();

        return view('form-edit', compact('movie', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->movieService->updateMovie($request, $id);
        return redirect('/movies/data')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->movieService->deleteMovie($id);
        return redirect('/movies/data')->with('success', 'Data berhasil dihapus');
    }
}
