<?php

namespace App\Routing;

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

    public function __construct(array $methods, string $pattern, callable|string $handler)
    {
        $this->methods = $methods;
        $this->pattern = $pattern;
        $this->handler = $handler;
    }

    public function getHandler(): callable|string
    {
        return $this->handler;
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
