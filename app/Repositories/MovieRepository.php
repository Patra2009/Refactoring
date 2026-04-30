<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Movie;
use App\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    public function getMovies($search)
    {
        $query = Movie::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('sinopsis', 'like', "%$search%");
            });
        }

        return $query->paginate(6)->withQueryString();
    }

    public function getAll()
    {
        return Movie::all();
    }

    public function create($movieData)
    {
        return Movie::create($movieData);
    }

    public function find($id)
    {
        return Movie::findOrFail($id);
    }

    public function paginate($limit)
    {
        return Movie::latest()->paginate($limit);
    }

    public function update($id, $movieData)
    {
        $movie = $this->find($id); // ✅ pakai method sendiri
        return $movie->update($movieData);
    }

    public function delete($id)
    {
        $movie = $this->find($id); // ✅ pakai method sendiri
        return $movie->delete();
    }

    public function getCategories()
    {
        return Category::all();
    }
}
