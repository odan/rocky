<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\OutgoingMiddleware;
use App\Routing\RouteGroup;
use App\Routing\Router;

return function (Router $router) {
    $router->get('/', \App\Action\HomeAction::class);

    // Protected API
    $router->group('/api', function (RouteGroup $group) {
        $group->get('/users', \App\Action\UsersFinderAction::class);
        $group->get('/users/{name}/{id:[0-9]+}', \App\Action\UsersReaderAction::class)->setName('user-url');

        // Complexer example
        $group->group('/pizzas', function (RouteGroup $group) {
            $group->get('', \App\Action\UsersFinderAction::class)
                ->add(OutgoingMiddleware::class)
                ->add(OutgoingMiddleware::class)
                ->add(OutgoingMiddleware::class);
        })->add(OutgoingMiddleware::class)
            ->add(OutgoingMiddleware::class);
    })->add(AuthMiddleware::class);
};
