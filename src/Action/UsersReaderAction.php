<?php

namespace App\Action;

use App\Renderer\JsonRenderer;
use App\Routing\UrlGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UsersReaderAction
{
    private UrlGenerator $urlGenerator;
    private JsonRenderer $renderer;

    public function __construct(UrlGenerator $urlGenerator, JsonRenderer $renderer)
    {
        $this->urlGenerator = $urlGenerator;
        $this->renderer = $renderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $route = $this->urlGenerator->getNamedRoute('user-url');
        $url = $this->urlGenerator->urlFor('user-url', ['id' => 123, 'name' => 'daniel']);
        $url2 = $this->urlGenerator->fullUrlFor($request->getUri(), 'user-url', $args);

        $data = [
            'route' => $route->getPattern(),
            'url' => $url,
            'url2' => $url2,
            'args' => $args,
        ];

        return $this->renderer->json($response, $data);
    }
}
