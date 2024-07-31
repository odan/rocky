<?php

declare(strict_types = 1);

namespace App\Routing;

use Psr\Http\Server\MiddlewareInterface;

trait MiddlewareAwareTrait
{
    /**
     * @var array
     */
    private array $middleware = [];

    public function getMiddlewareStack(): array
    {
        return $this->middleware;
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
}
