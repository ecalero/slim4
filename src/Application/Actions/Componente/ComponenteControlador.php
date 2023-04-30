<?php
declare(strict_types=1);

namespace App\Application\Actions\Componente;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;
/*fin ayuda*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UriInterface as Uri;

use App\Domain\Componente\Componente;
//creando token
use Firebase\JWT\JWT;

class ComponenteControlador extends ComponenteAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

/*         $users = $this->componenteRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users); */
    }

    public function index(Request $request, Response $response, $args){

  	}

  		// RETORNA DATOS DEL USUARIO EN CASO EXISTA
  		public function getColumnasComponente(Request $request, Response $response, $args){

			$columnasComponente = $this->componenteRepository->getColumnasComponente();
			$obj1 = JsonRenderer::render($response,200,$columnasComponente);
			return $obj1;
  		}

  		public function getFilasComponente(Request $request, Response $response, $args){
			$filasComponente = $this->componenteRepository->getFilasComponente();
  			return JsonRenderer::RespuestaJSON($response,$filasComponente);
  		}


/*APIS COMPONENTE*/



  		public function cambiarEstado(Request $request, Response $response, $args){

              try {
                  $datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
                  Componente::where('IDCOMPONENTE', $datos["idComponente"])
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

  			//guardar un nuevo componente
  			public function nuevoComponente(Request $request, Response $response, $args){

  				try {
  					$parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            $datos = json_decode($parametros["componente"], true);
            $componente = new Componente();
            $componente->NOMBRE = $datos["NOMBRE"];
            $componente->TITULO = $datos["TITULO"];
            $componente->RESUMEN = $datos["RESUMEN"];



            $componente->CONTENIDO = (isset($datos['CONTENIDO']) ? $datos['CONTENIDO'] : Null);
            $componente->IDCOMPONENTEPADRE = (isset($datos['IDCOMPONENTEPADRE']) ? $datos['IDCOMPONENTEPADRE'] : Null);
            $componente->IDUSUARIOCREACION = (isset($datos['IDUSUARIOCREACION']) ? $datos['IDUSUARIOCREACION'] : Null);
            $componente->ESTADO = (isset($datos['ESTADO']) ? $datos['ESTADO'] : Null);
            $res = $this->componenteRepository->nuevoComponente($componente);

  					$mensaje ="Se registró correctamente la componente";
  					$estado = true;
  					$datin["componente"]= $res;

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
  			//actualiza los campos del componente
  			public function actualizarComponente(Request $request, Response $response, $args){

  				try {
            $parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            $datos = json_decode($parametros["componente"], true);
            $componente = new Componente();
            $componente->NOMBRE = $datos["NOMBRE"];
           // $componente->DESCRIPCION = $datos["DESCRIPCION"];
		   //eliminando los elementos que llega porque no sirve
            unset($datos["action"]);
			unset($datos["USUARIO_ACTUALIZACION"]);
			unset($datos["USUARIO_CREACION"]);
			unset($datos["ESTADONOMBRE"]);

  					Componente::where('IDCOMPONENTE', $datos["IDCOMPONENTE"])
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

  				//actualiza los campos del componente
  			public function eliminarComponente(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/

  				Componente::where('IDCOMPONENTE', $datos["IDCOMPONENTE"])
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
