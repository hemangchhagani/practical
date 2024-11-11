<?php
namespace App\Lib;

class Response
{
    /**
     * Sends a JSON response.
     *
     * @param mixed $data The data to encode as JSON and send in the response.
     * @param int $statusCode HTTP status code to send with the response.
     */
    public function toJSON($data, $statusCode = 200)
    {
        // Set response header to application/json
        header('Content-Type: application/json');
        // Set HTTP status code
        http_response_code($statusCode);
        // Output JSON-encoded data
        echo json_encode($data);
        exit; // End script execution after sending response
    }

    /**
     * Sends a success response with optional data.
     *
     * @param mixed $data The data to include in the response (default is an empty array).
     */
    public function success($data = [])
    {
        $this->toJSON(['status' => 'success', 'data' => $data], 200);
    }

    /**
     * Sends an error response with an error message and status code.
     *
     * @param string $message Error message to send.
     * @param int $statusCode HTTP status code to send (default is 400).
     */
    public function error($message, $statusCode = 400)
    {
        $this->toJSON(['status' => 'failed', 'error' => $message], $statusCode);
    }

     /**
     * Send a JSON response
     *
     * @param array $data
     * @param int $status
     */
    public static function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Send a 401 Unauthorized response
     *
     * @param string $message
     */
    public static function unauthorized($message = 'Unauthorized')
    {
        self::json([
            'status' => 'error',
            'message' => $message
        ], 401);
    }

    /**
     * Send a 404 Not Found response
     *
     * @param string $message
     */
    public static function notFound($message = 'Not Found')
    {
        self::json([
            'status' => 'error',
            'message' => $message
        ], 404);
    }

    /**
     * Send a 500 Internal Server Error response
     *
     * @param string $message
     */
    public static function internalError($message = 'Internal Server Error')
    {
        self::json([
            'status' => 'error',
            'message' => $message
        ], 500);
    }
}
