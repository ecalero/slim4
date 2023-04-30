<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;


use Illuminate\Database\Capsule\Manager as Capsule;
//use App\Factory\DatabaseManagerFactory;


use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory;
use Psr\Container\ContainerInterface;

//agregando para JWT
use Tuupola\Middleware\JwtAuthentication;

//agregando JSONRENDER
use \Slim\Middleware;

//session
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\Middleware\SessionMiddleware;

//agregando MPDF
use Mpdf\Mpdf;



return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        // Database connection
            Connection::class => function (ContainerInterface $container) {
            $factory = new ConnectionFactory(new IlluminateContainer());

            //agregado
            $settings = $container->get(SettingsInterface::class);
            $dbSettings = $settings->get('db');
            //fin
            $connection = $factory->make($dbSettings);
            //agregando para conectarse con illuminate ---fake fake
            $resolver = new \Illuminate\Database\ConnectionResolver();
            $resolver->addConnection('default', $connection);
            $resolver->setDefaultConnection('default');
            \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
            //fin agregado
            $capsule = new \Illuminate\Database\Capsule\Manager;
            $capsule->addConnection($dbSettings);
            $capsule->bootEloquent();
            $capsule->setAsGlobal();
            // Disable the query log to prevent memory issues
            $connection->disableQueryLog();
            return $connection;

        },
         // Database connection
        /* Connection::class => function (ContainerInterface $container) {
/*             $factory = new ConnectionFactory(new IlluminateContainer());


            //fin
            $connection = $factory->make($dbSettings);
            // Disable the query log to prevent memory issues
            $connection->disableQueryLog();
            return $connection;

            //agregado
            $settings = $container->get(SettingsInterface::class);
            $dbSettings = $settings->get('db');

            $capsule = new \Illuminate\Database\Capsule\Manager;
            $capsule->addConnection($dbSettings);
            $capsule->bootEloquent();
            $capsule->setAsGlobal();
            $container['db'] = function ($container) {
                global $capsule;
                return $capsule;
            };

        },  */
        Capsule::class => function (ContainerInterface $container) {
            $factory = new ConnectionFactory(new IlluminateContainer());

            //agregado
            $settings = $container->get(SettingsInterface::class);
            $dbSettings = $settings->get('db');
            //fin
            $connection = $factory->make($dbSettings);


            // Disable the query log to prevent memory issues
            $connection->disableQueryLog();

            return $connection;
        },

        PDO::class => function (ContainerInterface $container) {
            return $container->get(Connection::class)->getPdo();
        },
        //agregando para JWT

        JwtAuthentication::class => static function (ContainerInterface $container):  JwtAuthentication  {
            //agregado
            $settings = $container->get(SettingsInterface::class);
            $jwtSettings = $settings->get('jwt_authentication');
            //var_dump($jwtSettings);
            //exit;
           // $settings['logger'] = $container->get(LoggerFactory::class)->createInstance('jwt');
            return new JwtAuthentication($jwtSettings);
        },
        //agregando JSON RENDER
//         Middleware::class => static function (ContainerInterface $container):  \SlimJson\Middleware  {
//             //agregado
//             $settings = $container->get(SettingsInterface::class);
//             $jwtSettings = $settings->get('json_render');
//             //var_dump($jwtSettings);
//             //exit;
//            // $settings['logger'] = $container->get(LoggerFactory::class)->createInstance('jwt');
//             return new \SlimJson\Middleware($jwtSettings);
//         },
        SessionInterface::class => function (ContainerInterface $container) {
            $settings = $container->get(SettingsInterface::class);
            $session = new PhpSession();
            $session->setOptions((array)$settings->get('session'));

            return $session;
        },

        SessionMiddleware::class => function (ContainerInterface $container) {
            return new SessionMiddleware($container->get(SessionInterface::class));
        },
        Mpdf::class => function (ContainerInterface $container) {
            //$mpdf = new \Mpdf\Mpdf();
            return new Mpdf($container->get(Mpdf::class));
        },
    ]);
};
