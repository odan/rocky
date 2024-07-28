<?php

declare(strict_types = 1);

namespace App\Routing;

trait RouteCollectionTrait
{
    abstract public function map(string $method, string $path, callable|string $handler): Route;

    abstract public function group(string $path, callable $handler): MiddlewareAwareInterface;

    public function delete(string $path, callable|string $handler): Route
    {
        return $this->map('DELETE', $path, $handler);
    }

    public function get(string $path, callable|string $handler): Route
    {
        return $this->map('GET', $path, $handler);
    }

    public function head(string $path, callable|string $handler): Route
    {
        return $this->map('HEAD', $path, $handler);
    }

    public function options(string $path, callable|string $handler): Route
    {
        return $this->map('OPTIONS', $path, $handler);
    }

    public function patch(string $path, callable|string $handler): Route
    {
        return $this->map('PATCH', $path, $handler);
    }

    public function post(string $path, callable|string $handler): Route
    {
        return $this->map('POST', $path, $handler);
    }

    public function put(string $path, callable|string $handler): Route
    {
        return $this->map('PUT', $path, $handler);
    }
}
