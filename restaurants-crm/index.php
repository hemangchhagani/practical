<?php

header("Access-Control-Allow-Origin: *");  // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); // Exit here so further code does not run
}


require_once 'db.php';
require_once 'config.php';  // Load .env and secret keys

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$method = $_SERVER['REQUEST_METHOD'];
$database = new Database();
$conn = $database->getConnection();

// Extract action and id from the request URI
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$action = isset($requestUri[0]) ? $requestUri[0] : '';
$id = isset($requestUri[1]) ? $requestUri[1] : null;  // Get the ID from the URL path if available

// Now the ID should be extracted correctly for DELETE (and other) requests.


// Switch statement to handle different endpoints
switch ($action) {
    case 'food-items':
        handleFoodItems($conn, $method, $id);
        break;

    case 'residents':
        handleResidents($conn, $method, $id);
        break;

    case 'register':
        if ($method === 'POST') {
            handleRegister($conn);
        } else {
            echo json_encode(['message' => 'Method Not Allowed']);
        }
        break;

    case 'login':
        if ($method === 'POST') {
            handleLogin($conn);
        } else {
            echo json_encode(['message' => 'Method Not Allowed']);
        }
        break;

    case 'users':
        if ($method === 'GET') {
            handleGetUsers($conn);
        } else {
            echo json_encode(['message' => 'Method Not Allowed']);
        }
        break;

    default:
        // Default case for unrecognized endpoints
        echo json_encode(['message' => 'Endpoint Not Found']);
        break;
}


// Handle user registration (unchanged from the previous code)
function handleRegister($conn) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['username']) && isset($input['email']) && isset($input['password'])) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $input['email']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['message' => 'User already exists']);
            return;
        }

        $hashedPassword = password_hash($input['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $input['username']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'User registered successfully']);
        } else {
            echo json_encode(['message' => 'Registration failed']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Handle user login and generate JWT token
function handleLogin($conn) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['email']) && isset($input['password'])) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $input['email']);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($input['password'], $user['password'])) {
            // If password is correct, generate JWT token
            $jwt = generateJWT($user);
            echo json_encode([
                'message' => 'Login successful',
                'token' => $jwt,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email']
                ]
            ]);
        } else {
            echo json_encode(['message' => 'Invalid email or password']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Generate JWT token
function generateJWT($user) {
    $secretKey = $_ENV['JWT_SECRET'];
    $issuedAt = time();
    $expirationTime = $issuedAt + (int)$_ENV['JWT_EXPIRY_TIME']; // Token expiration time (e.g., 1 hour)

    $payload = [
        'iss' => $_ENV['JWT_ISSUER'],        // Issuer
        'aud' => $_ENV['JWT_AUDIENCE'],      // Audience
        'iat' => $issuedAt,                  // Issued at
        'exp' => $expirationTime,            // Expiration time
        'data' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email']
        ]
    ];

    // Generate JWT token
    $jwt = JWT::encode($payload, $secretKey, 'HS256');

    return $jwt;
}

// Handle fetching all users (for demonstration purposes, unchanged from previous)
function handleGetUsers($conn) {
    $sql = "SELECT id, username, email FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();
    echo json_encode($data);
}

// Handle CRUD for Food Items
function handleFoodItems($conn, $method, $id = null) {
    switch ($method) {
        case 'GET':
            if ($id) {
                getFoodItemById($conn, $id);  // Fetch single food item by ID
            } else {
                getFoodItems($conn);  // Fetch all food items
            }
            break;
        case 'POST':
            addFoodItem($conn);
            break;
        case 'PUT':
            updateFoodItem($conn);
            break;
        case 'DELETE':
            // Ensure the ID is extracted correctly for the DELETE method
            if ($id) {
                deleteFoodItem($conn, $id);  // Pass the ID directly
            } else {
                echo json_encode(['message' => 'Item ID is required for deletion']);
            }
            break;
        default:
            echo json_encode(['message' => 'Method Not Allowed']);
            break;
    }
}


// Fetch all food items
function getFoodItems($conn) {
    $sql = "SELECT * FROM food_items";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}

// Fetch a single food item by ID
function getFoodItemById($conn, $id) {
    $sql = "SELECT * FROM food_items WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $foodItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($foodItem) {
        echo json_encode($foodItem);
    } else {
        echo json_encode(['message' => 'Food item not found']);
    }
}

// Add a new food item
function addFoodItem($conn) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['name']) && isset($input['category']) && isset($input['iddsi_level'])) {
        $sql = "INSERT INTO food_items (name, category, iddsi_level) VALUES (:name, :category, :iddsi_level)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':category', $input['category']);
        $stmt->bindParam(':iddsi_level', $input['iddsi_level']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Food item added successfully']);
        } else {
            echo json_encode(['message' => 'Failed to add food item']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update a food item
function updateFoodItem($conn) {
    
    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    

    // Check if required fields are set
    if (isset($input['id']) && isset($input['name']) && isset($input['category']) && isset($input['iddsi_level'])) {
        $sql = "UPDATE food_items SET name = :name, category = :category, iddsi_level = :iddsi_level WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':category', $input['category']);
        $stmt->bindParam(':iddsi_level', $input['iddsi_level']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Food item updated successfully']);
        } else {
            echo json_encode(['message' => 'Failed to update food item']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
// Delete a food item
function deleteFoodItem($conn, $id) {
    if ($id) {
        $sql = "DELETE FROM food_items WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Food item deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete food item']);
        }
    } else {
        echo json_encode(['message' => 'Food item ID is required']);
    }
}


// Handle CRUD for Residents
function handleResidents($conn, $method, $id = null) {
    switch ($method) {
        case 'GET':
            getResidents($conn);
            break;
        case 'POST':
            addResident($conn);
            break;
        case 'PUT':
            updateResident($conn);
            break;
        case 'DELETE':
            if ($id) {
                deleteResident($conn, $id);  // Pass the ID directly
            } else {
                echo json_encode(['message' => 'Resident ID is required for deletion']);
            }
            break;
        default:
            echo json_encode(['message' => 'Method Not Allowed']);
            break;
    }
}

// Fetch all residents
function getResidents($conn) {
    $sql = "SELECT * FROM residents";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
}

// Add a new resident
function addResident($conn) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['name']) && isset($input['iddsi_level'])) {
        $sql = "INSERT INTO residents (name, iddsi_level) VALUES (:name, :iddsi_level)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':iddsi_level', $input['iddsi_level']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Resident added successfully']);
        } else {
            echo json_encode(['message' => 'Failed to add resident']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}

// Update a resident
function updateResident($conn) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id']) && isset($input['name']) && isset($input['iddsi_level'])) {
        $sql = "UPDATE residents SET name = :name, iddsi_level = :iddsi_level WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':iddsi_level', $input['iddsi_level']);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Resident updated successfully']);
        } else {
            echo json_encode(['message' => 'Failed to update resident']);
        }
    } else {
        echo json_encode(['message' => 'Invalid input']);
    }
}
// Delete a resident
function deleteResident($conn, $id) {
    if ($id) {
        $sql = "DELETE FROM residents WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Resident deleted successfully']);
        } else {
            echo json_encode(['message' => 'Failed to delete resident']);
        }
    } else {
        echo json_encode(['message' => 'Resident ID is required']);
    }
}
