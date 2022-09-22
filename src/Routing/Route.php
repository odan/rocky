<?php

namespace App\Routing;

use Psr\Http\Server\MiddlewareInterface;

final class Route
{
    private ?string $name = null;
    private ?Router $router;
    private string $controller;
    private array $middleware = [];
    private string $pattern;
    private string $httpMethod;

    public function __construct(string $httpMethod, string $pattern, string $controller, ?Router $router)
    {
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->router = $router;
        $this->httpMethod = $httpMethod;
    }

    public function __invoke(): array
    {
        $middlewares = $this->middleware;
        $router = $this->router;
        while ($router) {
            foreach ($router->getMiddlewares() as $middleware) {
                $middlewares[] = $middleware;
            }
            $router = $router->getParent();
        }

        return [
            'handler' => $this->controller,
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

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getMethod(): string
    {
        return $this->httpMethod;
    }
}
