<?php
declare(strict_types=1);

//use App\Application\Middleware\SessionMiddleware;
use Tuupola\Middleware\JwtAuthentication;
use Odan\Session\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(JwtAuthentication::class);
    $app->addRoutingMiddleware();
    //agregando json rendere
    // Add the middleware globally
    //$app->add(\SlimJson\Middleware::class);

};
