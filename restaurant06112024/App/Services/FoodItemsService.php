<?php

namespace App\Services;

use App\Repositories\FoodItemRepository;


class FoodItemsService
{
    public $repository;
    

    public function __construct()
    {
        $this->repository = new FoodItemRepository();
    }


    public function createFoodItem($data)
    {
        if (empty($data['name']) || empty($data['category']) || !isset($data['iddsi_level'])) {
            throw new \Exception('All fields are required', 400);
        }

        return $this->repository->create($data['name'], $data['category'], $data['iddsi_level']);
    }

    public function getAllFoodItems()
    {
        return $this->repository->readAll();
    }

    public function getFoodItemById($id)
    {
        $foodItem = $this->repository->read($id);
        if (!$foodItem) {
            throw new \Exception('Food item not found', 404);
        }
        return $foodItem;
    }

    public function updateFoodItem($id, $data)
    {
        if (empty($data['name']) || empty($data['category']) || !isset($data['iddsi_level'])) {
            throw new \Exception('All fields are required', 400);
        }

        $updated = $this->repository->update($id, $data['name'], $data['category'], $data['iddsi_level']);
        if (!$updated) {
            throw new \Exception('Food item not found or update failed', 404);
        }

        return true;
    }

    public function deleteFoodItem($id)
    {
        $deleted = $this->repository->delete($id);
        if (!$deleted) {
            throw new \Exception('Food item not found or deletion failed', 404);
        }

        return true;
    }
}
