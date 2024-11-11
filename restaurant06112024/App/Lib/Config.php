<?php
namespace App\Lib;

class Config
{
    private static $config = [];

    /**
     * Load configuration file
     */
    public static function load($filePath)
    {
        if (file_exists($filePath)) {
            self::$config = require $filePath;
        }
    }

    /**
     * Get a configuration value by key
     *
     * @param string $key The configuration key, e.g., 'database.host'
     * @param mixed $default Default value if the key is not found
     * @return mixed
     */
    // public static function get($key, $default = null)
    // {
    //     $keys = explode('.', $key);
    //     $value = self::$config;

    //     foreach ($keys as $segment) {
    //         if (isset($value[$segment])) {
    //             $value = $value[$segment];
    //         } else {
    //             return $default;
    //         }
    //     }

    //     return $value;
    // }


    /**
     * Get configuration values by key
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        $config = [
            'jwt_secret_key' => 'your_secret_key_here', // Replace with your actual secret key
            'db_host' => 'localhost',
            'db_name' => 'restaurant',
            'db_user' => 'root',
            'db_pass' => '',
        ];

        return $config[$key] ?? null;
    }
}
