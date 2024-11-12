<?php

namespace App\Services;

use App\Repositories\ResidanctRepository;


class ResidentsService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new ResidanctRepository();
    }

    public function createResident($data)
    {
        if (empty($data['name']) || empty($data['category']) || !isset($data['iddsi_level'])) {
            throw new \Exception('All fields are required', 400);
        }

        return $this->repository->create($data['name'], $data['category'], $data['iddsi_level']);
    }

    public function getAllResidents()
    {
        return $this->repository->readAll();
    }

    public function getResidentById($id)
    {
        $resident = $this->repository->read($id);
        if (!$resident) {
            throw new \Exception('Resident not found', 404);
        }
        return $resident;
    }

    public function updateResident($id, $data)
    {
        if (empty($data['name']) || empty($data['category']) || !isset($data['iddsi_level'])) {
            throw new \Exception('All fields are required', 400);
        }

        $updated = $this->repository->update($id, $data['name'], $data['category'], $data['iddsi_level']);
        if (!$updated) {
            throw new \Exception('Resident not found or update failed', 404);
        }

        return true;
    }

    public function deleteResident($id)
    {
        $deleted = $this->repository->delete($id);
        if (!$deleted) {
            throw new \Exception('Resident not found or deletion failed', 404);
        }

        return true;
    }

    
}
