<?php
namespace App\Services;

use App\Lib\DB;
use App\Lib\Logger;
use Exception;

class FoodItemsService
{
    /**
     * Adds a new item.
     *
     * @param array $data Item data (e.g., 'name', 'description', 'price').
     * @return bool|int Returns the item ID on success, or false on failure.
     */
    public function addItem(array $data)
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
     * Retrieves item details by ID.
     *
     * @param int $itemId
     * @return array|null
     */
    public function getItemById(int $itemId)
    {
        try {
            $query = "SELECT id, name, description, price FROM items WHERE id = :id";
            $stmt = DB::query($query, [':id' => $itemId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            Logger::error("Failed to fetch item", ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Updates an item's information.
     *
     * @param int $itemId
     * @param array $data
     * @return bool
     */
    public function updateItem(int $itemId, array $data)
    {
        try {
            $query = "UPDATE items SET name = :name, description = :description, price = :price WHERE id = :id";
            $params = [
                ':name' => $data['name'],
                ':description' => $data['description'],
                ':price' => $data['price'],
                ':id' => $itemId
            ];
            return DB::query($query, $params) !== false;
        } catch (Exception $e) {
            Logger::error("Failed to update item", ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Deletes an item by ID.
     *
     * @param int $itemId
     * @return bool
     */
    public function deleteItem(int $itemId)
    {
        try {
            $query = "DELETE FROM items WHERE id = :id";
            return DB::query($query, [':id' => $itemId]) !== false;
        } catch (Exception $e) {
            Logger::error("Failed to delete item", ['error' => $e->getMessage()]);
            return false;
        }
    }
}
