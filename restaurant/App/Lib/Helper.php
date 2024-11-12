<?php

namespace App\Lib;

class Helper {
    /**
     * Sanitize input for HTML output
     *
     * @param string $input
     * @return string
     */
    public static function sanitize($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Redirect to a specified URL
     *
     * @param string $url
     */
    public static function redirect($url)
    {
        header("Location: $url");
        exit;
    }


}
