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
}
