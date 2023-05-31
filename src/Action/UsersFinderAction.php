<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UsersFinderAction
{
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
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write((string)json_encode($users));

        return $response;
    }
}
