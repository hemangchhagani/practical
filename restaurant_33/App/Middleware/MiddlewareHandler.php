<?php

namespace App\Middleware;

class MiddlewareHandler
{
    private $middlewareStack = [];

    public function __construct($middlewareStack = [])
    {
        $this->middlewareStack = $middlewareStack;
    }

    public function add($middleware)
    {
        $this->middlewareStack[] = $middleware;
    }

    public function handle($request, $next)
    {
        // Process each middleware
        foreach ($this->middlewareStack as $middleware) {
            $middleware($request);
        }

        // After all middleware has run, call the final action (e.g., controller)
        return $next();
    }
}
