<?php

namespace App\Router;

use FastRoute\RouteCollector;
use Psr\Http\Server\MiddlewareInterface;

final class Router
{
    private ?Router $parent;
    private RouteCollector $collector;
    private string $groupPattern;
    private array $middleware;
    private string $basePath = '';

    public function __construct(RouteCollector $collector, string $pattern = '')
    {
        $this->parent = null;
        $this->collector = $collector;
        $this->groupPattern = $pattern;
        $this->middleware = [];
    }

    public function get(string $route, string $handler): Route
    {
        return $this->addRoute('GET', $route, $handler);
    }

    public function post(string $route, string $handler): Route
    {
        return $this->addRoute('POST', $route, $handler);
    }

    public function put(string $route, string $handler): Route
    {
        return $this->addRoute('PUT', $route, $handler);
    }

    public function patch(string $route, string $handler): Route
    {
        return $this->addRoute('PATCH', $route, $handler);
    }

    public function delete(string $route, string $handler): Route
    {
        return $this->addRoute('DELETE', $route, $handler);
    }

    public function addRoute(string $httpMethod, string $pattern, string $handler): Route
    {
        $routePattern = $this->basePath . $this->groupPattern . $pattern;
        $route = new Route($httpMethod, $routePattern, $handler, $this);

        $this->collector->addRoute($httpMethod, $routePattern, $route);

        return $route;
    }

    public function group(string $pattern, callable $callable): Router
    {
        $routePattern = $this->basePath . $this->groupPattern . $pattern;
        $routeGroup = new self($this->collector, $routePattern);
        $routeGroup->parent = $this;

        // Collect routes
        $callable($routeGroup);

        return $routeGroup;
    }

    public function add(string $middleware): self
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    public function getMiddlewares(): array
    {
        return $this->middleware;
    }

    public function getParent(): ?Router
    {
        return $this->parent;
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
