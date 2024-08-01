<?php

declare(strict_types=1);

namespace App\Routing;

interface RouteCollectionInterface
{
    public function delete(string $path, callable $handler): Route;

    public function get(string $path, callable $handler): Route;

    public function head(string $path, callable $handler): Route;

    public function map(array $methods, string $path, callable $handler): Route;

    public function options(string $path, callable $handler): Route;

    public function patch(string $path, callable $handler): Route;

    public function post(string $path, callable $handler): Route;

    public function put(string $path, callable $handler): Route;
}
