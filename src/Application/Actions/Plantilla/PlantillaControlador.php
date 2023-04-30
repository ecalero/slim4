<?php
declare(strict_types=1);

namespace App\Application\Actions\Plantilla;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;
/*fin ayuda*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UriInterface as Uri;

use App\Domain\Plantilla\Plantilla;
//creando token
use Firebase\JWT\JWT;

class PlantillaControlador extends PlantillaAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

/*         $users = $this->plantillaRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users); */
    }

    public function index(Request $request, Response $response, $args){

  	}

  		// RETORNA DATOS DEL USUARIO EN CASO EXISTA
  		public function getColumnasPlantilla(Request $request, Response $response, $args){

			$columnasPlantilla = $this->plantillaRepository->getColumnasPlantilla();
			$obj1 = JsonRenderer::render($response,200,$columnasPlantilla);
			return $obj1;
  		}

  		public function getFilasPlantilla(Request $request, Response $response, $args){
			$filasPlantilla = $this->plantillaRepository->getFilasPlantilla();
  			return JsonRenderer::RespuestaJSON($response,$filasPlantilla);
  		}


/*APIS PLANTILLA*/



  		public function cambiarEstado(Request $request, Response $response, $args){

              try {
                  $datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
                  Plantilla::where('IDPLANTILLA', $datos["idPlantilla"])
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

  			//guardar un nuevo plantilla
  			public function nuevaPlantilla(Request $request, Response $response, $args){

  				try {
  					$parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            $datos = json_decode($parametros["plantilla"], true);
            $plantilla = new Plantilla();
            $plantilla->NOMBRE = $datos["NOMBRE"];
            $plantilla->DESCRIPCION = $datos["DESCRIPCION"];
            $res = $this->plantillaRepository->nuevaPlantilla($plantilla);

  					$mensaje ="Se registró correctamente la plantilla";
  					$estado = true;
  					$datin["plantilla"]= $res;

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
  			//actualiza los campos del plantilla
  			public function actualizarPlantilla(Request $request, Response $response, $args){

  				try {
            $parametros = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
            $datos = (object) json_decode($parametros["plantilla"], true);

            $plantilla = new Plantilla();
            //extrae los valores de los objetos
            //$e = array_values(get_object_vars($datos));

            var_dump($e);
            exit;
            $plantilla = $datos;

            $plantilla->NOMBRE = $datos["NOMBRE"];
            $plantilla->DESCRIPCION = $datos["DESCRIPCION"];
            unset($datos["action"]);
  					Plantilla::where('IDPLANTILLA', $datos["IDPLANTILLA"])
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

  				//actualiza los campos del plantilla
  			public function eliminarPlantilla(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/

  				Plantilla::where('IDPLANTILLA', $datos["IDPLANTILLA"])
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
