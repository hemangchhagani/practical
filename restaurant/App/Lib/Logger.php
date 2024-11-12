<?php
namespace App\Lib;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Exception;

class Logger
{
    
    private static $logFile = __DIR__ . '/../../logs/app.log';

    /**
     * Log an error message
     *
     * @param string $message
     * @param array $context
     */
    public static function error($message, array $context = [])
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log a debug message
     *
     * @param string $message
     * @param array $context
     */
    public static function debug($message, array $context = [])
    {
        self::log('DEBUG', $message, $context);
    }

    /**
     * Log a message with a specified level
     *
     * @param string $level
     * @param string $message
     * @param array $context
     */
    private static function log($level, $message, array $context = [])
    {
        $date = date('Y-m-d H:i:s');
        $contextString = json_encode($context);
        $logEntry = "[$date] $level: $message $contextString" . PHP_EOL;

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }
}
