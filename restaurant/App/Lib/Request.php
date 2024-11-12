<?php
namespace App\Lib;

class Request
{
    /**
     * Gets the HTTP request method (e.g., GET, POST).
     *
     * @return string
     */
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Gets the full request URI path.
     *
     * @return string
     */
    public static function getUri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Gets all request headers.
     *
     * @return array
     */
    public static function getHeaders()
    {
        return getallheaders();
    }

    /**
     * Gets a specific header from the request.
     *
     * @param string $header The name of the header to retrieve.
     * @return string|null
     */
    public static function getHeader($header)
    {
        $headers = self::getHeaders();
        return $headers[$header] ?? null;
    }

    /**
     * Gets all query parameters from the URL.
     *
     * @return array
     */
    public static function getQueryParams()
    {
        return $_GET;
    }

    /**
     * Gets a specific query parameter by name.
     *
     * @param string $key The name of the query parameter.
     * @param mixed $default The default value if the parameter does not exist.
     * @return mixed
     */
    public static function getQueryParam($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Gets the request body, decoded as JSON if applicable.
     *
     * @return array|null
     */
    public static function getBody()
    {
        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);
        
        // If JSON decoding fails, return raw input as an array
        return is_array($data) ? $data : [];
    }

    /**
     * Gets a specific value from the request body.
     *
     * @param string $key The key to retrieve from the body.
     * @param mixed $default The default value if the key does not exist.
     * @return mixed
     */
    public static function getBodyParam($key, $default = null)
    {
        $body = self::getBody();
        return $body[$key] ?? $default;
    }

    /**
     * Determines if the request is of a specific HTTP method.
     *
     * @param string $method The HTTP method to check for (e.g., 'POST', 'GET').
     * @return bool
     */
    public static function isMethod($method)
    {
        return strtoupper(self::getMethod()) === strtoupper($method);
    }
}
