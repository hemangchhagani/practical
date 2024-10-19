<?php

namespace App\Controllers;

class UserController
{
    public function getUsers()
    {
        // Here you'd get users from a database
        $users = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
        ];

        echo json_encode($users);
    }

    public function createUser()
    {
        // You can get input from POST body
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Here you can add logic to store user in a database
        if ($input && isset($input['name'])) {
            echo json_encode(["message" => "User created", "user" => $input]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input"]);
        }
    }
}
