<?php
namespace App\Lib;

class Router
{
    private $routes = [];

    /**
     * Registers a GET route.
     *
     * @param string $path The route path.
     * @param callable|array $handler The handler to be executed for this route.
     */
    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Registers a POST route.
     *
     * @param string $path The route path.
     * @param callable|array $handler The handler to be executed for this route.
     */
    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Registers a PUT route.
     *
     * @param string $path The route path.
     * @param callable|array $handler The handler to be executed for this route.
     */
    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Registers a DELETE route.
     *
     * @param string $path The route path.
     * @param callable|array $handler The handler to be executed for this route.
     */
    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Adds a route to the routing table.
     *
     * @param string $method The HTTP method.
     * @param string $path The route path.
     * @param callable|array $handler The handler to execute.
     */
    private function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->formatPath($path),
            'handler' => $handler,
        ];
    }

    /**
     * Matches an incoming request to a route and returns route info.
     *
     * @param string $method The HTTP request method.
     * @param string $uri The request URI.
     * @return array|null
     */
    public function match($method, $uri)
    {
        $uri = $this->formatPath($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $uri, $matches)) {
                array_shift($matches); // Remove the full match
                return [
                    'handler' => $route['handler'],
                    'params' => $this->extractParams($route['path'], $matches),
                ];
            }
        }
        
        return null;
    }

    /**
     * Formats a path to a regex for matching and extracting parameters.
     *
     * @param string $path The route path.
     * @return string
     */
    private function formatPath($path)
    {
        // Convert route parameters (e.g., /user/{id}) to regex patterns
        return '~^' . preg_replace('~\{(\w+)\}~', '(?P<$1>[^/]+)', $path) . '$~';
    }

    /**
     * Extracts named parameters from the URI using the route pattern.
     *
     * @param string $pattern The regex pattern with named groups.
     * @param array $matches Matched parameters from preg_match.
     * @return array
     */
    private function extractParams($pattern, $matches)
    {
        $params = [];
        if (preg_match_all('~\(\?P<(\w+)>\[.+\]\)~', $pattern, $paramNames)) {
            foreach ($paramNames[1] as $index => $name) {
                $params[$name] = $matches[$index] ?? null;
            }
        }
        return $params;
    }
}
