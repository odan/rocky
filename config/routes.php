<?php

use App\Middleware\ApiExceptionMiddleware;
use App\Middleware\OutgoingMiddleware;
use App\Router\Router;

return function (Router $router) {
    $router->get('/', \App\Action\HomeAction::class);
   /* $router->get('/user/{name}/{id:[0-9]+}', \App\Action\HomeAction::class);

    $router->group('/api', function (Router $router) {
        $router->get('/users', \App\Action\UserListAction::class);

        $router->get('', \App\Action\UserListAction::class)->middleware(OutgoingMiddleware::class);

        $router->group('/pizzas', function (Router $router) {
            $router->get('', \App\Action\UserListAction::class)
                ->middleware(OutgoingMiddleware::class)
                ->middleware(OutgoingMiddleware::class)
                ->middleware(OutgoingMiddleware::class);
        })->middleware(OutgoingMiddleware::class)
            ->middleware(OutgoingMiddleware::class);
    })->middleware(ApiExceptionMiddleware::class);*/
};
