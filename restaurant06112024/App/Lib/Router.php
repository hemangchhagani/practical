<?php
namespace App\Lib;

class Router
{
    public $routes = [];

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->formatPath($path),
            'handler' => $handler,
            'original_path' => $path, // keep original path for extracting params
        ];
    }

    public function match($method, $uri)
    {

        // $uri = $this->formatPath($uri);
        // echo $uri;
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $uri, $matches)) {
                
                return [
                    'handler' => $route['handler'],
                    'params' => $this->extractParams($matches),
                ];
            }
        }
        
        return null; // Return null if no route matches
    }

    public function formatPath($path)
    {
        // Convert route parameters (e.g., /user/{id}) to regex patterns for matching
        return '~^' . preg_replace('~\{(\w+)\}~', '(?P<$1>[^/]+)', $path) . '$~';
    }

    public function extractParams($matches)
    {
        // Remove numeric keys from matches to get only named parameters
        return array_filter($matches, function($key) {
            return !is_int($key);
        }, ARRAY_FILTER_USE_KEY);
    }
}
