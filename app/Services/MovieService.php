<?php
namespace App\Services;

use App\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MovieService
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getMovies($search)
    {
        return $this->movieRepository->getMovies($search);
    }

    public function getById($id)
    {
        return $this->movieRepository->find($id);
    }

    public function getAllPaginated()
    {
        return $this->movieRepository->paginate(10);
    }

    public function getCategories()
    {
        return $this->movieRepository->getCategories();
    }

    // 🔹 STORE
    public function storeMovie($request)
    {
        $validator = $this->validateStore($request);

        if ($validator->fails()) {
            return redirect('movies/create')
                ->withErrors($validator)
                ->withInput();
        }

        $fileName = $this->uploadCover($request->file('foto_sampul'));

        $movieData = [
            'id' => $request->id,
            'judul' => $request->judul,
            'category_id' => $request->category_id,
            'sinopsis' => $request->sinopsis,
            'tahun' => $request->tahun,
            'pemain' => $request->pemain,
            'foto_sampul' => $fileName,
        ];

        return $this->movieRepository->create($movieData);
    }

    // 🔹 UPDATE
    public function updateMovie($request, $id)
    {
        $validator = $this->validateUpdate($request);

        if ($validator->fails()) {
            return redirect("/movies/edit/{$id}")
                ->withErrors($validator)
                ->withInput();
        }

        $movie = $this->movieRepository->find($id);

        if ($request->hasFile('foto_sampul')) {
            $fileName = $this->uploadCover($request->file('foto_sampul'));

            // hapus lama
            if ($movie->foto_sampul && File::exists(public_path('images/' . $movie->foto_sampul))) {
                File::delete(public_path('images/' . $movie->foto_sampul));
            }

            $requestData = array_merge($request->all(), [
                'foto_sampul' => $fileName
            ]);
        } else {
            $requestData = $request->all();
        }

        return $this->movieRepository->update($id, $requestData);
    }

    // 🔹 DELETE
    public function deleteMovie($id)
    {
        $movie = $this->movieRepository->find($id);

        if ($movie->foto_sampul && File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }

        return $this->movieRepository->delete($id);
    }

    // =========================
    // 🔥 CLEAN CODE SECTION
    // =========================

    private function uploadCover($file)
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $fileName);
        return $fileName;
    }

    private function validateStore($request)
    {
        return Validator::make($request->all(), [
            'id' => 'required|string|max:255|unique:movies,id',
            'judul' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sinopsis' => 'required|string',
            'tahun' => 'required|integer',
            'pemain' => 'required|string',
            'foto_sampul' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }

    private function validateUpdate($request)
    {
        return Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sinopsis' => 'required|string',
            'tahun' => 'required|integer',
            'pemain' => 'required|string',
            'foto_sampul' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }
}
