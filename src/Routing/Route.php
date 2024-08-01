<?php

namespace App\Routing;

final class Route implements MiddlewareAwareInterface
{
    use MiddlewareAwareTrait;

    private array $methods;
    private string $pattern;
    private mixed $handler;
    private Router $router;
    private ?string $name = null;

    public function __construct(array $methods, string $pattern, callable|string $handler, Router $router)
    {
        $this->methods = $methods;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->router = $router;
    }

    public function __invoke(): array
    {
        $middlewares = $this->getMiddlewareStack();
        foreach ($this->router->getMiddlewareStack() as $middleware) {
            $middlewares[] = $middleware;
        }

        return [
            'handler' => $this->handler,
            'middleware' => $middlewares,
        ];
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }
}
