<?php
declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {

    $_ENV['JWT_SECRET'] = 'edwin';

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                // BD DESARROLLO
                'db' =>
                [
                    'driver' => 'mysql',
                    'host' => 'localhost:3308',
                    'database' => 'sentinel',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                    'options' => [
                        // Turn off persistent connections
                        PDO::ATTR_PERSISTENT => false,
                        // Enable exceptions
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        // Emulate prepared statements
                        PDO::ATTR_EMULATE_PREPARES => FALSE,
                        // Set default fetch mode to array
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        // Set character set
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',

                            PDO::ATTR_STRINGIFY_FETCHES => false

                    ],
                ], //agregando jwt
                'jwt_authentication' => [
                    "path" => "/api", /* or ["/api", "/admin"] */
                    //"secret" => "supersecretkeyyoushouldnotcommittogithub",
                    'secret' => $_ENV['JWT_SECRET'],
                    'attribute' => "decoded_token_data",
                    //'header' => "token",
                    'algorithm' => 'HS256',
                    'secure' => false, // only for localhost for prod and test env set true
                    'error' =>  function ($response, $arguments) {
                        $data['status'] = 401;
                        $data['error'] = 'Usted no está autorizado/'. $arguments['message'];
                        return $response
                            ->withHeader('Content-Type', 'application/json;charset=utf-8')
                            ->getBody()->write(json_encode(
                                $data,
                                JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
                            ));
                    }
                ],
                'json_render'=> [
                    'json.status' => true,
                    'json.override_error' => true,
                    'json.override_notfound' => true
                ],
                'session'=> [
                        'name' => 'webapp',
                        'cache_expire' => 0,
                ]
            ]);
        }
    ]);
};
