<?php
declare(strict_types=1);

namespace App\Application\Actions\UsuarioRol;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;
/*fin ayuda*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UriInterface as Uri;

use App\Domain\UsuarioRol\UsuarioRol;
//creando token
use Firebase\JWT\JWT;
use App\Helper\Constante;

class UsuarioRolControlador extends UsuarioRolAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

/*         $users = $this->usuariorolRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users); */
    }

    public function index(Request $request, Response $response, $args){

  	}

  		// RETORNA DATOS DEL USUARIO EN CASO EXISTA
  		public function getColumnasUsuarioRol(Request $request, Response $response, $args){

			$columnasUsuarioRol = $this->usuariorolRepository->getColumnasUsuarioRol();
			$obj1 = JsonRenderer::render($response,200,$columnasUsuarioRol);
			return $obj1;
  		}

  		public function getFilasUsuarioRol(Request $request, Response $response, $args){
			$parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            $datos = json_decode($parametros["usuariorol"], true);
            $usuariorol = new UsuarioRol();
            $usuariorol->IDUSUARIO = $datos["IDUSUARIO"];

			$filasUsuarioRol = $this->usuariorolRepository->getFilasUsuarioRol($datos["IDUSUARIO"]);
  			return JsonRenderer::RespuestaJSON($response,$filasUsuarioRol);
  		}


/*APIS PLANTILLA*/



  		public function cambiarEstado(Request $request, Response $response, $args){

              try {
                  $datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
                  UsuarioRol::where('IDPLANTILLA', $datos["idUsuarioRol"])
                  ->update(['ESTADO' => $datos["estado"]]);
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

  			//guardar un nuevo rol
  			public function nuevaUsuarioRol(Request $request, Response $response, $args){

  				try {
  					$parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            $datos = json_decode($parametros["rol"], true);
            $rol = new UsuarioRol();
            $rol->NOMBRE = $datos["NOMBRE"];
            $rol->DESCRIPCION = $datos["DESCRIPCION"];
            $res = $this->usuariorolRepository->nuevaUsuarioRol($rol);

  					$mensaje ="Se registró correctamente la rol";
  					$estado = true;
  					$datin["rol"]= $res;

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
  			//actualiza los campos del rol
  			public function actualizarUsuarioRol(Request $request, Response $response, $args){

  				try {
            $parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            //$datos = (object) json_decode($parametros["usuariorol"], true);
			$datos = json_decode($parametros["usuariorol"], true);
            $usuariorol = new UsuarioRol();
            //extrae los valores de los objetos
            //$e = array_values(get_object_vars($datos));

            //$usuariorol = $datos;

            $usuariorol->IDUSUARIO = $datos["IDUSUARIO"];
            $usuariorol->IDROL = $datos["IDROL"];
			$usuariorol->ESTADO = $datos["ESTADO"];

			if ($usuariorol->ESTADO == Constante::ESTADO_ACTIVO) {
				# si es activo insertamos en tabla usuario_rol
				$this->usuariorolRepository->nuevoUsuarioRol($usuariorol);
			} else {
				# si es inactivo o en otro caso eliminamos el rol al usuario
				$this->usuariorolRepository->eliminaUsuarioRolbyIdRol($usuariorol);
			}

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

  				//actualiza los campos del rol
  			public function eliminarUsuarioRol(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/

  				UsuarioRol::where('IDPLANTILLA', $datos["IDPLANTILLA"])
  				->delete();
  				$mensaje ="se elimino correctamente";
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
