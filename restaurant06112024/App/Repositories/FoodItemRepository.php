<?php

namespace App\Repositories;


use App\Database\Connection;
use PDO;

class FoodItemRepository
{
   protected $db;

    public function __construct()
    {
        $this->db = new Connection();
    }
 // Create a new food item
    public function create($name, $category, $iddsi_level)
    {
        $query = "INSERT INTO food_items (name, category, iddsi_level) VALUES (:name, :category, :iddsi_level)";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);
        return $stmt->execute();
    }

    // Get all food items
    public function readAll()
    {
        $query = "SELECT * FROM food_items";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    // Get a single food item by ID
    public function read($id)
    {

        $query = "SELECT * FROM food_items WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update an existing food item
    public function update($id, $name, $category, $iddsi_level)
    {
        $query = "UPDATE food_items SET name = :name, category = :category, iddsi_level = :iddsi_level WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Delete a food item
    public function delete($id)
    {
        $query = "DELETE FROM food_items WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
