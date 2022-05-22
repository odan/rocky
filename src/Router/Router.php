<?php

namespace App\Router;

use FastRoute\RouteCollector;

class Router
{
    private ?Router $parent = null;
    private RouteCollector $collector;
    private string $groupPattern;
    private array $middleware;

    public function __construct(RouteCollector $collector, string $pattern = '')
    {
        $this->parent = null;
        $this->collector = $collector;
        $this->groupPattern = $pattern;
        $this->middleware = [];
    }

    public function get(string $path, string $controller): Router
    {
        $newRoute = new self($this->collector, $this->groupPattern);
        $newRoute->parent = $this;

        $handler = function () use ($controller, $newRoute) {
            $middlewares = $newRoute->middleware;
            $parent = $newRoute->parent;
            while ($parent) {
                foreach ($parent->middleware as $middleware) {
                    $middlewares[] = $middleware;
                }
                $parent = $parent->parent;
            }

            return [
                'handler' => $controller,
                'middleware' => $middlewares,
            ];
        };

        $this->collector->get($this->groupPattern . $path, $handler);

        return $newRoute;
    }

    public function group(string $pattern, callable $callable): Router
    {
        $routeGroup = new self($this->collector, $this->groupPattern . $pattern);
        $routeGroup->parent = $this;

        // Collect routes
        $callable($routeGroup);

        return $routeGroup;
    }

    public function middleware(string $middleware): self
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    public function getRouteCollector(): RouteCollector
    {
        return $this->collector;
    }
}
