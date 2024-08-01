<?php

declare(strict_types=1);

namespace App\Routing;

use Psr\Http\Server\MiddlewareInterface;

interface MiddlewareAwareInterface
{
    public function getMiddlewareStack(): array;

    public function add(string $middleware): self;

    public function addMiddleware(MiddlewareInterface $middleware): self;
}
