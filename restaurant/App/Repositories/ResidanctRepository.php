<?php

namespace App\Repositories;


use App\Database\Connection;
use PDO;


class ResidanctRepository
{
     protected $db;

    public function __construct()
    {
        $this->db = new Connection();
    }

        // Create a new resident
    public function create($name, $category, $iddsi_level)
    {
        $query = "INSERT INTO residents (name, category, iddsi_level) VALUES (:name, :category, :iddsi_level)";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);
        return $stmt->execute();
    }

    // Get all residents
    public function readAll()
    {
        $query = "SELECT * FROM residents";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single resident by ID
    public function read($id)
    {
        $query = "SELECT * FROM residents WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update an existing resident
    public function update($id, $name, $category, $iddsi_level)
    {
        $query = "UPDATE residents SET name = :name, category = :category, iddsi_level = :iddsi_level WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':iddsi_level', $iddsi_level);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Delete a resident
    public function delete($id)
    {
        $query = "DELETE FROM residents WHERE id = :id";
        $stmt =$this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

}
