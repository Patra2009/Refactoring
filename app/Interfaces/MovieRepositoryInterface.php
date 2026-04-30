<?php
namespace App\Interfaces;

interface MovieRepositoryInterface
{
    public function getAll();
    public function create($data);
    public function getMovies($search);
    public function find($id);
    public function paginate($limit);
    public function update($id, $data);
    public function delete($id);
    public function getCategories();
}

