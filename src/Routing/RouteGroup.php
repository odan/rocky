<?php

declare(strict_types = 1);

namespace App\Routing;

use FastRoute\RouteCollector;

class RouteGroup implements MiddlewareAwareInterface, RouteCollectionInterface
{
    use MiddlewareAwareTrait;
    use RouteCollectionTrait;

    /**
     * @var callable
     */
    private $callback;
    private RouteCollector $routeCollector;
    private string $prefix;
    private Router $router;

    public function __construct(string $prefix, callable $callback, Router $router)
    {
        $this->prefix = sprintf('/%s', ltrim($prefix, '/'));
        $this->callback = $callback;
        $this->router = $router;
        $this->routeCollector = $router->getRouteCollector();
    }

    public function __invoke(): void
    {
        // This will be invoked by FastRoute
        ($this->callback)($this);
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function map(string $method, string $path, callable|string $handler): Route
    {
        $routePath = ($path === '/') ? $this->prefix : $this->prefix . sprintf('/%s', ltrim($path, '/'));
        $route = new Route($method, $routePath, $handler, $this->router);
        $this->routeCollector->addRoute($method, $path, $route);

        return $route;
    }

    public function group(string $path, callable $handler): MiddlewareAwareInterface
    {
        $routePath = ($path === '/') ? $this->prefix : $this->prefix . sprintf('/%s', ltrim($path, '/'));
        $routeGroup = new RouteGroup($routePath, $handler, $this->router);

        $this->routeCollector->addGroup($path, $routeGroup);

        return $routeGroup;
    }
}