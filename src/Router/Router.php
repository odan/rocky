<?php

namespace App\Router;

use FastRoute\RouteCollector;

final class Router
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

    public function get(string $route, string $handler): Router
    {
        return $this->addRoute('GET', $route, $handler);
    }

    public function post(string $route, string $handler): Router
    {
        return $this->addRoute('POST', $route, $handler);
    }

    public function put(string $route, string $handler): Router
    {
        return $this->addRoute('PUT', $route, $handler);
    }

    public function patch(string $route, string $handler): Router
    {
        return $this->addRoute('PATCH', $route, $handler);
    }

    public function delete(string $route, string $handler): Router
    {
        return $this->addRoute('DELETE', $route, $handler);
    }

    public function addRoute(string $httpMethod, string $route, string $handler): Router
    {
        $newRoute = new self($this->collector, $this->groupPattern);
        $newRoute->parent = $this;

        $routeHandler = $this->createRouteHandler($newRoute, $handler);

        $this->collector->addRoute($httpMethod, $this->groupPattern . $route, $routeHandler);

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

    private function createRouteHandler(Router $route, string $controller): callable
    {
        return function () use ($controller, $route) {
            $middlewares = $route->middleware;
            $parent = $route->parent;
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
    }
}
