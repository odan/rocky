<?php

namespace App\Action;

use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UsersFinderAction
{
    private JsonRenderer $renderer;

    public function __construct(JsonRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $users = [
            [
                'username' => 'john',
                'email' => 'john@example.com',
            ],
        ];

        return $this->renderer->json($response, $users);
    }
}
