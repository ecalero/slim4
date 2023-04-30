<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;

//agregado (se debe agregar )
use App\Application\Actions\Usuario\ListUsuariosAction;
use App\Application\Actions\Usuario\ViewUsuarioAction;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Odan\Session\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
//agregando JWT
use Tuupola\Middleware\JwtAuthentication;

//creando token
use Firebase\JWT\JWT;

//agregando rutas dinamicas
use App\Domain\Sistema\Sistema;
use App\Domain\Modulos\Modulos;
use Illuminate\Database\Connection;
use DI\ContainerBuilder;
use App\Helper\JsonRenderer;

use Mpdf\Mpdf;

return function (App $app) {

$app->getContainer(Connection::class)->get(Connection::class);


    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/mpdf', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world mpdf!');
        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<h1>Hello world pdf!</h1>');
        $mpdf->Output();
        return $response;
    });


    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{IDUSUARIO}', ViewUserAction::class);
    });

/*login usuarios*/
    $app->group('/usuarios', function (Group $group) {
        $group->get('', ListUsuariosAction::class);
        //$group->get('/{IDUSUARIO}', ViewUsuarioAction::class);
        $group->post('/login', 'App\Application\Actions\Usuario\LoginAction'::class . ':login');
    });

    //agregando las rutas para el login
    $app->group('/token', function (Group $group) {
        $group->get('', 'App\Application\Actions\Usuario\LoginAction'::class . ':token');
    });



    //agregando
    $app->group('/api',function(Group $group){
        $group->post('/login', ListUsuariosAction::class . ':login');

        //rutas que está controladas por el midlwhare
        $group->get('/example', function (Request $request, Response $response) {
            echo "logramos ingresar con el token de clave secreta edwin";
            $time = time();
            $key = 'my_secret_key';
            $token = array(
                'iat' => $time, // Tiempo que inició el token
                'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
                'data' => [ // información del usuario
                    'id' => 1,
                    'name' => 'Edwin'
                ]
            );
            $jwt = JWT::encode($token, getenv("JWT_SECRET"), 'HS256');
            echo $jwt;
            exit;
            return $this->get('example_controller')->getExamples($request, $response);
        })->setName('api-examples');


        $group->get('/token', function (Request $request, Response $response) {
            echo "logramos ingresar con el token de clave secreta edwin";
            exit;
            $time = time();
            $key = 'my_secret_key';

            $token = array(
                'iat' => $time, // Tiempo que inició el token
                'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
                'data' => [ // información del usuario
                    'id' => 1,
                    'name' => 'Edwin'
                ]
            );

            $jwt = JWT::encode($token, $key);

            $data = JWT::decode($jwt, $key, array('HS256'));

            //var_dump($data);
            //exit;
            echo "edwin";
            //return $data;
        });
    });


//agregando rutas
$sistemas = Sistema::select()->with('modulos')->get()->toArray();

//echo json_encode($sistemas);
//return true;
if (!empty($sistemas)) {
	foreach ($sistemas as $sistema) {
		//echo $sistema["RUTA"]."/";
      $app->group('/'.$sistema["RUTA"],function(Group $group ) use ($app, $sistema) {
		//$app->group('/'.$sistema["RUTA"],function () use ($app, $sistema) {
			//var_dump($sistema["modulos"]);
			$modulos=$sistema["modulos"];
			if (!empty($modulos)) {
				foreach ($modulos as $modulo) {
					//echo $modulo["RUTA"]."/";
					$group->group('/'.$modulo["RUTA"], function (Group $group1) use ($app, $modulo) {
						//var_dump($modulo["objacceso"]);
						$objaccesos=$modulo["objacceso"];
						if (!empty($objaccesos)) {
							foreach ($objaccesos as $objacceso) {
								//echo $objacceso["RUTA"]."/";
								$controlador = "App\Application\Actions\\".$objacceso["NOMBRE"]."\\".$objacceso["CONTROLADOR"].":index";
								$group1->get('/'.$objacceso["RUTA"], $controlador)->setName($objacceso["RUTA"]);
								//$app->post('/'.$objacceso["RUTA"].'/[{id}]', $controlador)->setName($objacceso["RUTA"].'post');
								$group1->group('/'.$objacceso["RUTA"], function (Group $group2) use ($app, $objacceso) {
									//var_dump($objacceso);

									$menus=$objacceso["menus"];
									if (!empty($menus)) {
										foreach ($menus as $menu) {
										//	echo $menu["URL"]."\n";
											$route =Array($menu["ROUTE"]);
											if ($menu["NROPARAMETROS"]==0) {
												# code...
												$group2->map($route,'/'.$menu["URL"], 'App\Application\Actions\\'.$objacceso["NOMBRE"].'\\'.$objacceso["CONTROLADOR"].':'.$menu["FUNCION"])->setName($menu["URL"]);
											}else{
												$param = "/";
												for ($i=0; $i < $menu["NROPARAMETROS"]; $i++) {
													if($i == $menu["NROPARAMETROS"]-1){
														$param = $param."{param".$i."}";
													}else{
														$param = $param."{param".$i."}/";
													}
												}
												$group2->map($route,'/'.$menu["URL"].$param, 'App\Application\Actions\\'.$objacceso["NOMBRE"].'\\'.$objacceso["CONTROLADOR"].':'.$menu["FUNCION"])->setName($menu["URL"]);
											}
											/* var_dump($param);
											var_dump($menu);
											exit; */
										}
									}
								});
							}
						}
					});
				}
			}
		});
	}
}

//$app->add(new Tuupola\Middleware\JwtAuthentication([
//    "path" => "/api", /* or ["/api", "/admin"] */
//    "secret" => "supersecretkeyyoushouldnotcommittogithub"
//]));

};
