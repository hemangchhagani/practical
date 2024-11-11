<?php
namespace App\Services;

use App\Lib\DB;
use App\Lib\Logger;
use Exception;

class ResidentsService
{
    /**
     * Adds a new residents.
     *
     * @param array $data Item data (e.g., 'name', 'description', 'price').
     * @return bool|int Returns the residents ID on success, or false on failure.
     */
    public function addResidents(array $data)
    {
        try {
            $query = "INSERT INTO items (name, description, price) VALUES (:name, :description, :price)";
            $params = [
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':price' => $data['price']
            ];
            DB::query($query, $params);

            return DB::lastInsertId();
        } catch (Exception $e) {
            Logger::error("Failed to add item", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Retrieves residents details by ID.
     *
     * @param int $residentsId
     * @return array|null
     */
    public function getResidentsById(int $residentsId)
    {
        try {
            $query = "SELECT id, name, description, price FROM items WHERE id = :id";
            $stmt = DB::query($query, [':id' => $residentsId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            Logger::error("Failed to fetch item", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Updates an residents's information.
     *
     * @param int $residentsId
     * @param array $data
     * @return bool
     */
    public function updateResidents(int $residentsId, array $data)
    {
        try {
            $query = "UPDATE items SET name = :name, description = :description, price = :price WHERE id = :id";
            $params = [
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':price' => $data['price'],
                ':id' => $residentsId
            ];
            return DB::query($query, $params) !== false;
        } catch (Exception $e) {
            Logger::error("Failed to update item", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Deletes an residents by ID.
     *
     * @param int $residentsId
     * @return bool
     */
    public function deleteResidents(int $residentsId)
    {
        try {
            $query = "DELETE FROM items WHERE id = :id";
            return DB::query($query, [':id' => $residentsId]) !== false;
        } catch (Exception $e) {
            Logger::error("Failed to delete item", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
