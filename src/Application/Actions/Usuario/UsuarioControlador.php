<?php
declare(strict_types=1);

namespace App\Application\Actions\Usuario;

//helper
use App\Domain\Login\Login;
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;
/*fin ayuda*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UriInterface as Uri;

use App\Domain\Usuario\Usuario;
//creando token
use Firebase\JWT\JWT;
use App\Helper\Constante;

class UsuarioControlador extends UsuarioAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $users = $this->usuarioRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users);
    }


    public function index(Request $request, Response $response, $args){
	  echo("edwing");
	  exit();
      $users = $this->usuarioRepository->findAll();
      $this->logger->info("Users list was viewed.");
      $obj1 = JsonRenderer::render($response,200,$users);
      return $obj1;
  	}


	  // RETORNA DATOS DEL USUARIO EN CASO EXISTA
	  public function getColumnasUsuario(Request $request, Response $response, $args){
		$columnasUsuario = $this->usuarioRepository->getColumnasUsuario();
		$obj1 = JsonRenderer::render($response,200,$columnasUsuario);
		return $obj1;
	  }

	  public function getFilasUsuario(Request $request, Response $response, $args){
		$filasUsuario = $this->usuarioRepository->getFilasUsuario();
		  return JsonRenderer::RespuestaJSON($response,$filasUsuario);
	  }

	  //actualiza los campos del usuario
public function resetearClaveUsuario(Request $request, Response $response, $args){

	try {

		$parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
		$datos = json_decode($parametros["usuario"], true);
		$DATOS  = array('IDUSUARIO' => $datos["IDUSUARIO"], 'CLAVE' => Hash::hash(Constante::CLAVE_RESET) );
		$USUARIO = $this->usuarioRepository->resetearClave($DATOS);
		$datin["usuario"]=$USUARIO;

		$mensaje ="se reseteo correctamente la clave";
		$estado = true;

	} catch (\ErrorException $e) {
		$mensaje="Algo no salio muy bien";
		$estado=false;
		}

	   $datin["mensaje"]= $mensaje;
	   $datin["success"] = $estado;


		/*[{"team": ""},]*/
	   $obj1 = JsonRenderer::render($response,200,$datin);

	   return $obj1;
	}



    	// VERFIFICAR LAS CREDENCIALES DE ACCESO
public function Login(Request $request, Response $response, $args) {



    //$datos = $request->getQueryParams();
    $datos = $request->getParsedBody();

    $usuario = $datos["correo"];
    $password = $datos["clave"];

    $password = Hash::hash($password);

    $vrf_usuario = ["USUARIO" => $usuario];

    $tmp = $this->usuarioRepository->VerificarLogin($vrf_usuario);


		if ($tmp["success"]) {
			$vrf_password = [
				"USUARIO" => $usuario,
				"CLAVE" => $password
			];

			$tmp2 = $this->usuarioRepository->VerificarLogin($vrf_password);

			if ($tmp2["success"]) {
				$vrf_baja = [
					"USUARIO" => $usuario,
					"CLAVE" => $password,
					"BAJA" => 0
				];
				$tmp3 = $this->usuarioRepository->VerificarLogin($vrf_baja);

				if ($tmp3["success"]) {
					$vrf_estado = [
						"USUARIO" => $usuario,
						"CLAVE" => $password,
						"USUARIO.ESTADO" => 1
					];
					$tmp4 = $this->usuarioRepository->VerificarLogin($vrf_estado);

					if ($tmp4["success"]) {

						$datin["IDUSUARIO"] = $tmp4["data"]["IDUSUARIO"];
						//CREAR TOKEND Y ENVIAR
						//$codificar=$tmp4["data"]["USUARIO"].$tmp4["data"]["CLAVE"].date("d/m/y");
						//$token = Hash::hash($codificar);
						//c2356069e9d1e79ca924378153cfbbfb4d4416b1f99d41a2940bfdb66c5319db
						//$hash="daf8362006f1d5843533349a0f3786201af5be4ba7d629048259ab705a467a4c";
						//$v=Hash::hash_equals($token, $hash);
                        //VERIFICAR TOKEN CON JWT
                        $token = $this->usuarioRepository->crearToken($tmp4);
						//verifica si hay sesiones abiertas

                        $sesiones =  $this->loginRepository->verificaSesionAbierta($tmp4["data"]["IDUSUARIO"]);


						//guardar el login

						if ($sesiones["success"]) {
							# sesion activa no es necesario crea
							$login = Login::find($sesiones["data"][0]["IDLOGIN"]);
							$login->REFRESH_TOKEN=$login->ACCESS_TOKEN; //anterior
							$login->ACCESS_TOKEN=$token["token"]; //nuevo
 							$login->EXPIRES_IN=$token["datos"]->exp;
 							$login->IAT_IN=$token["datos"]->iat;
 							$login->TOKEN_TYPE=$token["datos"]->tipo_token;
 							$login->NRO_INGRESOS=$login->NRO_INGRESOS+1;
							$login->update();
						}else{
							$login = new Login;
							$login->ACCESS_TOKEN=$token["token"];
							$login->EXPIRES_IN=$token["datos"]["exp"];
							$login->IAT_IN=$token["datos"]["iat"];
							$login->ESTADO=$token["datos"]["estado"];
							$login->TIPO_ACCESO=$token["datos"]["tipo_acceso"];
							$login->TOKEN_TYPE=$token["datos"]["tipo_token"];
							$login->IDUSUARIO=$tmp4["data"]["IDUSUARIO"];
							$login->save();
						}


                //se crea las sesiones
				$sesionUsuario = $this->loginRepository->loginSesion($tmp4["data"]["IDUSUARIO"]);

				$datin["usuario"]= $sesionUsuario["session"];

                //fin sesion
                	if (array_key_exists('tipo', $datos)) {
                		if ($datos["tipo"]=='app') {
                		    $datin["loginApp"]=$this->loginRepository->loginDatosApp($tmp4["data"]["IDUSUARIO"]);
                		}
                	}
						$datin["IDUSUARIO"] = $tmp4["data"]["IDUSUARIO"];
						$mensaje = "Acceso Correcto";
						$estado = true;
						$clase = "alert-success";
					} else {
						$mensaje = "El USUARIO se encuentra DESHABILITADO. Contacte con el Administrador General del Sistema.";
						$estado = false;
						$clase = "alert-warning";
					}
				} else {
					$mensaje = "USUARIO DADO DE BAJA. Contacte con el Administrador General del Sistema.";
					$estado = false;
					$clase = "alert-warning";
				}
			} else {
				$mensaje = "La CONTRASEÑA ingresada es INCORRECTA.";
				$estado = false;
				$clase = "alert-danger";

			}
		} else {
			$mensaje = "El USUARIO ingresado NO EXISTE.";
			$estado = false;
			$clase = "alert-danger";

		}

		$datin["mensaje"] = $mensaje;
		$datin["success"] = $estado;
		$datin["clase"] = $clase;

		$obj1 = JsonRenderer::render($response,200,$datin);
		return $obj1;
	}

    public  function token(Request $request, Response $response, $args): Response
    {

        $time = time();
        $key = 'edwin';

        $token = array(
            'iat' => $time, // Tiempo que inició el token
            'exp' => $time + (1*60), // Tiempo que expirará el token (+1 hora)
            'data' => [ // información del usuario
                'id' => 2,
                'name' => 'Edwin'
            ]
        );
        $jwt = JWT::encode($token, $key);
        $data = JWT::decode($jwt, $key, array('HS256'));
        $datin=["clave"=>$data, "hash"=> "Bearer ".$jwt, "token"=>$jwt];

        $obj1 = JsonRenderer::render($response,200,$datin);
        return $obj1;
    }


    public function login2222(Request $request, Response $response){

/*         $payload = json_encode(['hello' => 'world'], JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json'); */


        try {

//$datin["Scheme"]=$request->getScheme();
//$datin["Autority"]=$request->getAuthority();
//$datin["UserInfo"]=$request->getUserInfo();
//$datin["Host"]=$request->getHost();
//$datin["Port"]=$request->getPort();
//$datin["Path"]=$request->getPath();
//$datin["BasePath"]=$request->getBasePath();
//$datin["query"]=$request->getQuery();
//$datin["fragment"]=$request->getFragment();
//$datin["BaseUrl"]=$request->getBaseUrl();

//$datin["getSize"]=$request->getSize();
//$datin["tell"]=$request->tell();
//$datin["eof"]=$request->eof();
//$datin["isSeekable"]=$request->isSeekable();
//$datin["seek"]=$request->seek();
//$datin["rewind"]=$request->rewind();
//$datin["isWritable"]=$request->isWritable();
//$datin["write"]=$request->write($string);
//$datin["isReadable"]=$request->isReadable();
//$datin["read"]=$request->read($length);
//$datin["getContents"]=$request->getContents();
//$datin["getMetadata"]=$request->getMetadata($key = null);

$datin["files"] = $request->getUploadedFiles();
//$datin["getStream"] = $request->getStream();
//$datin["moveTo"] = $request->moveTo($targetPath);
//$datin["getSize"] = $request->getSize();
//$datin["getError"] = $request->getError();
//$datin["getClientFilename"] = $request->getClientFilename();
//$datin["getClientMediaType"] = $request->getClientMediaType();
//$datin["foo"] = $request->getServerParam();
//$datin["getParam"] = $request->getParam("usuario");
//$datin["getQueryParam"] = $request->getQueryParam();
//$datin["getParsedBodyParam"] = $request->getParsedBodyParam();
//$datin["getCookieParam"] = $request->getCookieParam();
//$datin["getServerParam"] = $request->getServerParam();
$datin["usuario2"] = $request->getQueryParams();
$datin["route"] = $request->getAttribute('route');
$datin["uri"] = $request->getUri();
//$datin["courseId"] = $datin["route"]->getArgument('usuario');

/* $datin["options"]  = $request
    ->withMethod('OPTIONS')
    ->withRequestTarget('*')
    ->withUri(new Uri('https://example.org/')); */

//$datin["getHeaders"] = $request->getHeaders();
$datin["getParsedBody"] = $request->withQueryParams(["clave"]);
$datin["body222"]= $request->getBody();

$datin["param"] = $request->getQueryParams(); // extrae los parametros enviados para GET or POST
            $datin["uri"] = $request->getUri();
            $datin["metodo"] = $request->getMethod();
            $datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            $datin["usuario"]=$datos;//Usuario::getUsuarioByUser($datos);
            $mensaje ="Su consulta de validación es correcta";
            $estado = true;
        } catch (\ErrorException $e) {
            $mensaje="Algo no salio muy bien";
            $estado=false;
        }
           $datin["mensaje"]= $mensaje;
           $datin["success"] = $estado;
           $obj1 = JsonRenderer::render($response,200,$datin);
           return $obj1;

    }

/*apis*/
public function getMenuAccesos(Request $request, Response $response, $args){

        try {

            $datos = $request->getParsedBody();

            $token = $datos["token"];

            $sistema = $this->usuarioRepository->getMenuAccesos($token);
            $datin["menu"]=$sistema;
            $mensaje ="se cambio de estado correctamente";
            $estado = true;

        } catch (\ErrorException $e) {
            $mensaje="Algo no salio muy bien";
            $estado=false;
            }
           $datin["mensaje"]= $mensaje;
           $datin["success"] = $estado;

            /*[{"team": ""},]*/
           $obj1 = JsonRenderer::render($response,200,$datin);
           return $obj1;
  }

//API
// RETORNA DATOS DEL USUARIO EN CASO EXISTA
public function getUsuarioById(Request $request, Response $response, $args){
  $parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
  $IDUSUARIO = json_decode($parametros["usuario"], true);
  var_dump($parametros);
  $usuario = $this->usuarioRepository->getUsuarioById($IDUSUARIO);
  $obj1 = JsonRenderer::render($response,200,$usuario);
  return $obj1;
}

//actualiza los campos del usuario
public function actualizarUsuario(Request $request, Response $response, $args){

	try {

		$parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
		$datos = json_decode($parametros["usuario"], true);
		unset($datos["action"]);
		unset($datos["NOMBRES"]);
		$actualizaUsuario = $this->usuarioRepository->actualizarUsuario($datos);
		$datin["usuario"]=$actualizaUsuario;

		$mensaje ="se actualizó correctamente el registro";
		$estado = true;

	} catch (\ErrorException $e) {
		$mensaje="Algo no salio muy bien";
		$estado=false;
		}

	   $datin["mensaje"]= $mensaje;
	   $datin["success"] = $estado;


		/*[{"team": ""},]*/
	   $obj1 = JsonRenderer::render($response,200,$datin);

	   return $obj1;
	}


}
