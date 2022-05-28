<?php

namespace App\Action;

use App\Router\RouteParser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HomeAction
{
    private RouteParser $routeParser;

    public function __construct(RouteParser $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $route = $this->routeParser->getNamedRoute('username');

        $url = $this->routeParser->urlFor('username', ['id' => 123, 'name' => 'daniel']);

        $url2 = $this->routeParser->fullUrlFor($request->getUri(), 'username', ['id' => 123, 'name' => 'daniel']);

        $response->getBody()->write('OK<br>' . var_export($args, true) . '<br>' . $url2);

        return $response;
    }
}
