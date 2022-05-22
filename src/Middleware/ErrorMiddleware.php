<?php

namespace App\Middleware;

use App\Responder\ErrorResponder;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class ErrorMiddleware implements MiddlewareInterface
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
        } catch (Throwable $exception) {
            return $this->errorResponder->render(
                $exception,
                $request,
                StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR
            );
        }
    }
}
