<?php
namespace App\Lib;

use Throwable;

class ExceptionHandler
{
    /**
     * Handle uncaught exceptions and log errors
     *
     * @param Throwable $exception
     */
    public static function handle(Throwable $exception)
    {
        Logger::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);

        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'An internal server error occurred.']);
    }
}
