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
    public static function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $segment) {
            if (isset($value[$segment])) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }
}
