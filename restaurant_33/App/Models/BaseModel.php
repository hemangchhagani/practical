<?php

namespace App\Models;

use PDO;
use PDOException;

class BaseModel
{
    protected $db;
    protected $table;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Find a single record by ID
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all records
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new record
    public function create(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_map(function ($item) {
            return ":$item";
        }, array_keys($data)));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Update an existing record by ID
    public function update($id, array $data)
    {
        $columns = implode(", ", array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));

        $sql = "UPDATE {$this->table} SET $columns WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Delete a record by ID
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
