<?php
declare(strict_types=1);

namespace App\Application\Actions\Sistema;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;
/*fin ayuda*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UriInterface as Uri;


//creando token
use Firebase\JWT\JWT;

class SistemaControlador extends SistemaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

/*         $users = $this->sistemaRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users); */
    }

    public function index(Request $request, Response $response, $args){

  	}

  		// RETORNA DATOS DEL USUARIO EN CASO EXISTA
  		public function getColumnasSistema(Request $request, Response $response, $args){
			  
			$columnasSistema = $this->sistemaRepository->getColumnasSistema();
			$obj1 = JsonRenderer::render($response,200,$columnasSistema);
			return $obj1;
  		}

  		public function getFilasSistema(Request $request, Response $response, $args){
			$filasSistema = $this->sistemaRepository->getFilasSistema();
  			return JsonRenderer::RespuestaJSON($response,$filasSistema);
  		}


/*APIS SISTEMA*/



  		public function cambiarEstado(Request $request, Response $response, $args){

              try {
                  $datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
                  Sistema::where('IDSISTEMA', $datos["idSistema"])
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

  			//guardar un nuevo sistema
  			public function nuevoSistema(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
  					$ruta = Acl::GenerarURL($datos["NOMBRE_CORTO"]);
  					$datos["RUTA"]= $ruta;
  					$idSistema = Sistema::insertGetId($datos);
  					$datos["IDSISTEMA"] = $idSistema;
  					$datos["ACCION"] = $idSistema;

  					$mensaje ="se registró correctamente el sistema";
  					$estado = true;
  					$datin["sistema"]= $datos;

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
  			//actualiza los campos del sistema
  			public function editarSistema(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/

  					Sistema::where('IDSISTEMA', $datos["IDSISTEMA"])
  					->update($datos);
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

  				//actualiza los campos del sistema
  			public function eliminarSistema(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/

  				Sistema::where('IDSISTEMA', $datos["IDSISTEMA"])
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
