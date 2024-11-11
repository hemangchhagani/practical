<?php

namespace App\Controllers;

use PDO;

class ResidentsController
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db; // Assign the passed PDO instance to the $db property
    }

    // Create a new resident
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

        // Insert the new resident into the database
        $query = "INSERT INTO residents (name, category, iddsi_level) VALUES (:name, :category, :iddsi_level)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);

        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode(['message' => 'Resident created successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Resident creation failed']);
        }
    }

    // Read all residents
    public function readAll()
    {
        $query = "SELECT * FROM residents";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($residents);
    }

    // Read a single resident by ID
    public function read($id)
    {
        $query = "SELECT * FROM residents WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $resident = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resident) {
            echo json_encode($resident);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'Resident not found']);
        }
    }

    // Update an existing resident
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

        $query = "UPDATE residents SET name = :name, category = :category, iddsi_level = :iddsi_level WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount()) {
                echo json_encode(['message' => 'Resident updated successfully']);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'Resident not found']);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Resident update failed']);
        }
    }

    // Delete a resident
    public function delete($id)
    {
        $query = "DELETE FROM residents WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            if ($stmt->rowCount()) {
                echo json_encode(['message' => 'Resident deleted successfully']);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(['message' => 'Resident not found']);
            }
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Resident deletion failed']);
        }
    }
}
