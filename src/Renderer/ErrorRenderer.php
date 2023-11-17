<?php

namespace App\Renderer;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

final class ErrorRenderer
{
    private ResponseFactoryInterface $responseFactory;
    private JsonRenderer $renderer;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        JsonRenderer $jsonRenderer
    ) {
        $this->responseFactory = $responseFactory;
        $this->renderer = $jsonRenderer;
    }

    public function render(
        Throwable $exception,
        ServerRequestInterface $request,
        int $httpStatusCode
    ): ResponseInterface {
        $response = $this->responseFactory->createResponse($httpStatusCode);

        // JSON
        if (str_contains($request->getHeaderLine('Accept'), 'application/json')) {
            $response = $response->withAddedHeader('Content-Type', 'application/json');

            $data = [
                'error' => [
                    'message' => $exception->getMessage(),
                ],
            ];

            return $this->renderer->json($response, $data);
        }

        // HTML
        $response = $response->withAddedHeader('Content-Type', 'text/html');

        $response->getBody()->write(
            sprintf(
                "\n<br>Error %s (%s)\n<br>Message: %s\n<br>File: %s, Line: %s ",
                $response->getStatusCode(),
                $response->getReasonPhrase(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            )
        );

        return $response;
    }
}
