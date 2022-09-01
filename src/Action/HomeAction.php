<?php

namespace App\Action;

use App\Router\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HomeAction
{
    private UrlGenerator $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $route = $this->urlGenerator->getNamedRoute('username');

        $url = $this->urlGenerator->urlFor('username', ['id' => 123, 'name' => 'daniel']);

        $url2 = $this->urlGenerator->fullUrlFor($request->getUri(), 'username', ['id' => 123, 'name' => 'daniel']);

        $response->getBody()->write('OK<br>' . var_export($args, true) . '<br>' . $url2);

        return $response;
    }
}
