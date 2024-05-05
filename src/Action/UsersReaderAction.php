<?php

namespace App\Action;

use App\Renderer\JsonRenderer;
use App\Routing\LinkGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UsersReaderAction
{
    private LinkGenerator $linkGenerator;
    private JsonRenderer $renderer;

    public function __construct(LinkGenerator $linkGenerator, JsonRenderer $renderer)
    {
        $this->linkGenerator = $linkGenerator;
        $this->renderer = $renderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        $route = $this->linkGenerator->getNamedRoute('user-url');
        $url = $this->linkGenerator->urlFor('user-url', ['id' => 123, 'name' => 'daniel']);
        $url2 = $this->linkGenerator->fullUrlFor($request->getUri(), 'user-url', $args);

        $data = [
            'route' => $route->getPattern(),
            'url' => $url,
            'url2' => $url2,
            'args' => $args,
        ];

        return $this->renderer->json($response, $data);
    }
}
