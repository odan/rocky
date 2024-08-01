<?php

namespace App\Routing;

final class RoutingResults
{
    public const NOT_FOUND = 0;
    public const FOUND = 1;
    public const METHOD_NOT_ALLOWED = 2;

    /**
     * The status is one of the constants shown above
     * NOT_FOUND = 0
     * FOUND = 1
     * METHOD_NOT_ALLOWED = 2
     */
    private int $routeStatus;

    private ?Route $route;
    private string $method;
    private string $uri;

    /**
     * @var array<string, string>
     */
    private array $routeArguments;
    private array $allowedMethods;

    /**
     * @param array<string, string> $routeArguments
     */
    public function __construct(
        int $routeStatus,
        ?Route $route,
        string $method,
        string $uri,
        array $routeArguments = [],
        array $allowedMethods = [],
    ) {
        $this->route = $route;
        $this->method = $method;
        $this->uri = $uri;
        $this->routeStatus = $routeStatus;
        $this->routeArguments = $routeArguments;
        $this->allowedMethods = $allowedMethods;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getRouteStatus(): int
    {
        return $this->routeStatus;
    }

    /**
     * @return array<string, string>
     */
    public function getRouteArguments(): array
    {
        return $this->routeArguments;
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
