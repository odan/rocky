<?php

namespace App\Routing;

use FastRoute\RouteCollector;

final class Router
{
    use RouteCollectionTrait;
    use MiddlewareAwareTrait;

    private RouteCollector $collector;
    private string $basePath = '';

    public function __construct(RouteCollector $collector)
    {
        $this->collector = $collector;
    }

    public function map(array $methods, string $path, callable|string $handler): Route
    {
        $routePattern = $this->basePath . $path;
        $route = new Route($methods, $routePattern, $handler);

        $this->collector->addRoute($methods, $routePattern, $route);

        return $route;
    }

    public function group(string $pattern, callable $callable): MiddlewareAwareInterface
    {
        $routePattern = $this->basePath . $pattern;
        $routeGroup = new RouteGroup($routePattern, $callable, $this);
        $this->collector->addGroup($routePattern, $routeGroup);

        return $routeGroup;
    }

    public function getRouteCollector(): RouteCollector
    {
        return $this->collector;
    }

    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}
