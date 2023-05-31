<?php

use App\Middleware\IncomingMiddleware;
use App\Middleware\OutgoingMiddleware;
use App\Routing\Router;

return function (Router $router) {
    $router->get('/', \App\Action\HomeAction::class);

    // Protected API
    $router->group('/api', function (Router $router) {
        $router->get('/users', \App\Action\UsersFinderAction::class);
        $router->get('/users/{name}/{id:[0-9]+}', \App\Action\UsersReaderAction::class)->setName('user-url');

        // Complexer example
        $router->group('/pizzas', function (Router $router) {
            $router->get('', \App\Action\UsersFinderAction::class)
                ->add(OutgoingMiddleware::class)
                ->add(OutgoingMiddleware::class)
                ->add(OutgoingMiddleware::class);
        })->add(OutgoingMiddleware::class)
            ->add(OutgoingMiddleware::class);
    })->add(IncomingMiddleware::class);
};
