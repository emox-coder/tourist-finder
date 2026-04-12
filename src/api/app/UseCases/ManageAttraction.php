<?php
namespace App\UseCases;

use Infrastructure\Repositories\AttractionRepositoryImpl;

use App\Repositories\AttractionRepository;

class ManageAttraction {
    private $repo;

    public function __construct(AttractionRepository $repo) {
        $this->repo = $repo;
    }

    public function create($data) {
        return $this->repo->create($data);
    }

    public function getAll() {
        return $this->repo->getAll();
    }

    public function getTopDestinations() {
        return $this->repo->getTopDestinations();
    }

    public function getById($id) {
        return $this->repo->getById($id);
    }

    public function update($id, $data) {
        return $this->repo->update($id, $data);
    }

    public function getThreeCards() {
        return $this->repo->getThreeCards();
    }

    public function getAllThreeCards() {
        return $this->repo->getAllThreeCards();
    }

    public function getThreeCard($id) {
        return $this->repo->getThreeCard($id);
    }

    public function addThreeCard($data) {
        return $this->repo->addThreeCard($data);
    }

    public function updateThreeCard($id, $data) {
        return $this->repo->updateThreeCard($id, $data);
    }

    public function deleteThreeCard($id) {
        return $this->repo->deleteThreeCard($id);
    }

    public function delete($id) {
        return $this->repo->delete($id);
    }
}
