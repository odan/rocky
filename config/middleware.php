<?php

return [
    \App\Middleware\ErrorMiddleware::class,
    \App\Middleware\HttpExceptionMiddleware::class,
    \App\Middleware\BasePathMiddleware::class,
    \App\Middleware\RoutingMiddleware::class,
    \App\Middleware\EndpointMiddleware::class,
    \App\Middleware\OutgoingMiddleware::class,
];
