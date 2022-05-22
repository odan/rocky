<?php

namespace App\Middleware;

use App\Responder\ErrorResponder;
use App\Router\HttpExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HttpExceptionMiddleware implements MiddlewareInterface
{
    private ErrorResponder $errorResponder;

    public function __construct(ErrorResponder $responder)
    {
        $this->errorResponder = $responder;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpExceptionInterface $exception) {
            return $this->errorResponder->render($exception, $request, $exception->getCode());
        }
    }
}
