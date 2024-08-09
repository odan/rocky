<?php

declare(strict_types=1);

namespace App\Routing;

use FastRoute\RouteCollector;

class RouteGroup implements MiddlewareCollectionInterface, RouteCollectionInterface
{
    use MiddlewareCollectionTrait;
    use RouteCollectionTrait;

    /**
     * @var callable
     */
    private $callback;
    private RouteCollector $routeCollector;
    private string $prefix;
    private Router $router;
    private ?RouteGroup $group;

    public function __construct(string $prefix, callable $callback, Router $router, RouteGroup $group = null)
    {
        $this->prefix = sprintf('/%s', ltrim($prefix, '/'));
        $this->callback = $callback;
        $this->router = $router;
        $this->routeCollector = $router->getRouteCollector();
        $this->group = $group;
    }

    public function __invoke(): void
    {
        // This will be invoked by FastRoute to collect the route groups
        ($this->callback)($this);
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Get parent route group.
     */
    public function getRouteGroup(): ?RouteGroup
    {
        return $this->group;
    }

    public function map(array $methods, string $path, callable|string $handler): Route
    {
        $routePath = ($path === '/') ? $this->prefix : $this->prefix . sprintf('/%s', ltrim($path, '/'));
        $route = new Route($methods, $routePath, $handler, $this);
        $this->routeCollector->addRoute($methods, $path, $route);

        return $route;
    }

    public function group(string $path, callable $handler): MiddlewareCollectionInterface
    {
        $routePath = ($path === '/') ? $this->prefix : $this->prefix . sprintf('/%s', ltrim($path, '/'));
        $routeGroup = new RouteGroup($routePath, $handler, $this->router, $this);

        $this->routeCollector->addGroup($path, $routeGroup);

        return $routeGroup;
    }
}
