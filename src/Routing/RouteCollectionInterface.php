<?php

declare(strict_types=1);

namespace App\Routing;

interface RouteCollectionInterface
{
    public function map(array $methods, string $path, string $handler): Route;

    public function group(string $path, callable $handler): MiddlewareCollectionInterface;

    public function any(string $path, string $handler): Route;

    public function delete(string $path, string $handler): Route;

    public function get(string $path, string $handler): Route;

    public function head(string $path, string $handler): Route;

    public function options(string $path, string $handler): Route;

    public function patch(string $path, string $handler): Route;

    public function post(string $path, string $handler): Route;

    public function put(string $path, string $handler): Route;
}
