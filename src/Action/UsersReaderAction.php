<?php

namespace App\Action;

use App\Routing\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UsersReaderAction
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
        $route = $this->urlGenerator->getNamedRoute('user-url');

        $url = $this->urlGenerator->urlFor('user-url', ['id' => 123, 'name' => 'daniel']);

        $url2 = $this->urlGenerator->fullUrlFor($request->getUri(), 'user-url', $args);

        $response->getBody()->write('OK<br>' . var_export($args, true) . '<br>' . $url2);

        return $response;
    }
}
