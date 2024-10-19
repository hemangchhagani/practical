<?php

namespace App\Controllers;

use PDO;

class FooditemsController
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db; // Assign the passed PDO instance to the $db property
    }

    // Create a new food item
    public function create()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? null;
        $category = $input['category'] ?? null;
        $iddsi_level = $input['iddsi_level'] ?? null;

        // Validate input
        if (!$name || !$category || !isset($iddsi_level)) {
            http_response_code(400);
            echo json_encode(['message' => 'All fields are required']);
            return;
        }

        // Insert the new food item into the database
        $query = "INSERT INTO food_items (name, category, iddsi_level) VALUES (:name, :category, :iddsi_level)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);

        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Food item created successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Food item creation failed']);
        }
    }

    // Read all food items
    public function readAll()
    {
        $query = "SELECT * FROM food_items";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $food_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($food_items);
    }

    // Read a single food item by ID
    public function read($id)
    {
        $query = "SELECT * FROM food_items WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $food_item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($food_item) {
            echo json_encode($food_item);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'Food item not found']);
        }
    }

    // Update an existing food item
    public function update($id)
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $name = $input['name'] ?? null;
        $category = $input['category'] ?? null;
        $iddsi_level = $input['iddsi_level'] ?? null;

        // Validate input
        if (!$name || !$category || !isset($iddsi_level)) {
            http_response_code(400);
            echo json_encode(['message' => 'All fields are required']);
            return;
        }

        $query = "UPDATE food_items SET name = :name, category = :category, iddsi_level = :iddsi_level WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount()) {
                echo json_encode(['message' => 'Food item updated successfully']);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'Food item not found']);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Food item update failed']);
        }
    }

    // Delete a food item
    public function delete($id)
    {
        $query = "DELETE FROM food_items WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount()) {
                echo json_encode(['message' => 'Food item deleted successfully']);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'Food item not found']);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Food item deletion failed']);
        }
    }
}
