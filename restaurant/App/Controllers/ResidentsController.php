<?php

namespace App\Controllers;

use App\Services\ResidentsService;

class ResidentsController
{
    protected $ResidentsService;
   
   public function __construct()
    {
        $this->ResidentsService = new ResidentsService();
    }

   // Create a new resident
    public function create()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $this->ResidentsService->createResident($input);
            http_response_code(201);
            echo json_encode(['message' => 'Resident created successfully']);
        } catch (\Exception $e) {
            $result = array('message' => $e->getMessage() , 'status' => $e->getCode() ?: 500 );
            return $result;
        }
    }

    // Read all residents
    public function readAll()
    {
        try {
            $residents = $this->ResidentsService->getAllResidents();
            //echo json_encode($residents);
            $result = array('data' => $residents, 'status' => 200 );
            return $result;
        } catch (\Exception $e) {
           $result = array('message' => $e->getMessage() , 'status' => $e->getCode() ?: 500 );
            return $result;
        }
    }

    // Read a single resident by ID
    public function read($id)
    {
        try {
            $resident = $this->ResidentsService->getResidentById($id);
            //echo json_encode($resident);
            $result = array('data' => $resident, 'status' => 200 );
            return $result;
        } catch (\Exception $e) {
           $result = array('message' => $e->getMessage() , 'status' => $e->getCode() ?: 500 );
            return $result;
        }
    }

    // Update an existing resident
    public function update($id)
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $this->ResidentsService->updateResident($id, $input);
            $result = array('message' => 'Resident updated successfully', 'status' => 200 );
            return $result;
            //echo json_encode(['message' => 'Resident updated successfully']);
        } catch (\Exception $e) {
          $result = array('message' => $e->getMessage() , 'status' => $e->getCode() ?: 500 );
            return $result;
        }
    }

    // Delete a resident
    public function delete($id)
    {
        try {
            $this->ResidentsService->deleteResident($id);
            //echo json_encode(['message' => 'Resident deleted successfully']);
             $result = array('message' => 'Resident deleted successfully', 'status' => 200 );
            return $result;

        } catch (\Exception $e) {
           $result = array('message' => $e->getMessage() , 'status' => $e->getCode() ?: 500 );
            return $result;
        }
    }
    
}
