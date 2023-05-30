<?php

use App\Middleware\ApiExceptionMiddleware;
use App\Middleware\OutgoingMiddleware;
use App\Routing\Router;

return function (Router $router) {
    $router->get('/', \App\Action\HomeAction::class);
    $router->get('/users/{name}/{id:[0-9]+}', \App\Action\HomeAction::class)->setName('username');

    $router->group('/api', function (Router $router) {
        $router->get('/users', \App\Action\UserListAction::class);

        $router->get('', \App\Action\UserListAction::class)->add(OutgoingMiddleware::class);

        $router->group('/pizzas', function (Router $router) {
            $router->get('', \App\Action\UserListAction::class)
                ->add(OutgoingMiddleware::class)
                ->add(OutgoingMiddleware::class)
                ->add(OutgoingMiddleware::class);
        })->add(OutgoingMiddleware::class)
            ->add(OutgoingMiddleware::class);
    })->add(ApiExceptionMiddleware::class);
};
