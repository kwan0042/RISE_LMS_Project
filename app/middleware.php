<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\Views\TwigMiddleware;
use Slim\App;

return function (App $app) {
    // Add the session middleware to all routes
    $app->add(SessionMiddleware::class);
    // Add Twig-View Middleware to all routes
    $app->add(TwigMiddleware::createFromContainer($app));
};
