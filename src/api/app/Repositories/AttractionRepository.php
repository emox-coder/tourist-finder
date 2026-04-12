<?php
namespace App\Repositories;

interface AttractionRepository {
    public function create($data);
    public function getAll();
    public function getTopDestinations();
    public function getById($id);
    public function update($id, $data);
    public function delete($id);
    public function getThreeCards();
    public function getAllThreeCards();
    public function getThreeCard($id);
    public function addThreeCard($data);
    public function updateThreeCard($id, $data);
    public function deleteThreeCard($id);
}
