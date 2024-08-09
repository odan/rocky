<?php

namespace App\Routing;

use Psr\Http\Server\MiddlewareInterface;

final class Route implements MiddlewareCollectionInterface
{
    use MiddlewareCollectionTrait;

    private array $methods;
    private string $pattern;

    /**
     * @var callable|string
     */
    private $handler;

    private ?string $name = null;

    private ?RouteGroup $group;

    public function __construct(array $methods, string $pattern, callable|string $handler, RouteGroup $group = null)
    {
        $this->methods = $methods;
        $this->pattern = $pattern;
        $this->handler = $handler;
        $this->group = $group;
    }

    public function getHandler(): callable|string
    {
        return $this->handler;
    }

    /**
     * @return array<MiddlewareInterface|string>
     */
    public function getMiddlewareStack(): array
    {
        $middlewares = $this->middleware;

        // Append middleware from all parent route groups
        $group = $this->group;
        while ($group) {
            foreach ($group->getMiddlewareStack() as $middleware) {
                $middlewares[] = $middleware;
            }
            $group = $group->getRouteGroup();
        }

        return $middlewares;
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
