<?php

namespace App\Controllers;



use App\Services\FoodItemsService;


class FooditemsController
{

	protected $service;

	public function __construct()
	{
		$this->service = new FoodItemsService();
	}


    // Create a new food item
	public function create()
	{
		try {
			$input = json_decode(file_get_contents('php://input'), true);
			$this->service->createFoodItem($input);
			http_response_code(201);
			echo json_encode(['message' => 'Food item created successfully']);
		} catch (\Exception $e) {
			http_response_code($e->getCode() ?: 500);
			echo json_encode(['message' => $e->getMessage()]);
		}
	}

    // Read all food items
	public function readAll()
	{
		try {
			$foodItems = $this->service->getAllFoodItems();
			echo json_encode($foodItems);
		} catch (\Exception $e) {
			http_response_code($e->getCode() ?: 500);
			echo json_encode(['message' => $e->getMessage()]);
		}
	}

    // Read a single food item by ID
	public function read($id)
	{
		try {
			$foodItem = $this->service->getFoodItemById($id);
			echo json_encode($foodItem);
		} catch (\Exception $e) {
			http_response_code($e->getCode() ?: 500);
			echo json_encode(['message' => $e->getMessage()]);
		}
	}

    // Update an existing food item
	public function update($id)
	{
		try {
			$input = json_decode(file_get_contents('php://input'), true);
			$this->service->updateFoodItem($id, $input);
			echo json_encode(['message' => 'Food item updated successfully']);
		} catch (\Exception $e) {
			http_response_code($e->getCode() ?: 500);
			echo json_encode(['message' => $e->getMessage()]);
		}
	}

    // Delete a food item
	public function delete($id)
	{
		try {
			$this->service->deleteFoodItem($id);
			echo json_encode(['message' => 'Food item deleted successfully']);
		} catch (\Exception $e) {
			http_response_code($e->getCode() ?: 500);
			echo json_encode(['message' => $e->getMessage()]);
		}
	}

}
