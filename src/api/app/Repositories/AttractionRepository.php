<?php
namespace App\Repositories;

interface AttractionRepository {
    public function create($data);
    public function getAll();
}
