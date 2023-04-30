<?php
declare(strict_types=1);

namespace App\Application\Actions\Archivo;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;
use App\Helper\Constante;
/*fin ayuda*/

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UriInterface as Uri;

use App\Domain\Archivo\Archivo;
//creando token
use Firebase\JWT\JWT;

class ArchivoControlador extends ArchivoAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

/*         $users = $this->archivoRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $this->respondWithData($users); */
    }

    public function index(Request $request, Response $response, $args){

  	}

    // SUBIR UN ARCHIVO PARA UN CONTENIDO
    	public function SubirArchivoContenido(Request $request, Response $response, $args) {
    		try {
    			$datos = $request->getParsedBody();
    			$idcontenido_archivo = $datos["idcontenido_archivo"];
    			$tipo_archivo = $datos["tipo_archivo"];
    			$uploadedFiles = $request->getUploadedFiles();
    			$uploadedFile = $uploadedFiles['archivo_contenido'];
    			$tipoFile  = $uploadedFile->getClientMediaType();

    			switch ($tipo_archivo) {
    				case '1':
    					$directorio = Constante::REPOSITORIO_FILE."Contenidos/Imagenes/";
    					if ($tipoFile == "image/jpeg" || $tipoFile == "image/jpg" || $tipoFile == "image/png") {
    						$tmp = ContenidoControlador::GuardarArchivo($uploadedFile,$directorio,$tipoFile);
    						if ($tmp["success"]) {
    							$idarchivo = $tmp["data"]["idarchivo"];
    							$data = [
    								"idcontenido" => $idcontenido_archivo,
    								"idarchivo" => $idarchivo
    							];
    							ContenidoArchivo::NuevoRegistro($data);
    							$mensaje = "La IMAGEN se subió correctamente.";
    							$estado = true;
    						} else {
    							$mensaje = "Hubo un error al subir la IMAGEN.";
    							$estado = false;
    						}
    					} else {
    						$mensaje = "Solo se permite archivos con extensión: .jpeg / .jpg / .png";
    						$estado = false;
    					}
    				break;

    				case '2':
    					$directorio = Constante::REPOSITORIO_FILE."Contenidos/Documentos/";
    					if ($tipoFile == "application/pdf" || $tipoFile == "application/msword" || $tipoFile == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $tipoFile == "application/vnd.ms-excel" || $tipoFile == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
    						$tmp = ContenidoControlador::GuardarArchivo($uploadedFile,$directorio,$tipoFile);
    						if ($tmp["success"]) {
    							$idarchivo = $tmp["data"]["idarchivo"];
    							$data = [
    								"idcontenido" => $idcontenido_archivo,
    								"idarchivo" => $idarchivo
    							];
    							ContenidoArchivo::NuevoRegistro($data);
    							$mensaje = "El DOCUMENTO se subió correctamente.";
    							$estado = true;
    						} else {
    							$mensaje = "Hubo un error al subir el DOCUMENTO.";
    							$estado = false;
    						}
    					} else {
    						$mensaje = "Solo se permite archivos con extensión: .pdf / .doc / .docx / .xls / .xlsx";
    						$estado = false;
    					}
    				break;
    			}
    		} catch (\ErrorException $e){
    			$mensaje = "Ocurrió un error inesperado al Guardar la Imagen: ".$e;
    			$estado = false;
    		}

    		$datin["mensaje"] = $mensaje;
    		$datin["success"] = $estado;
    		$obj1 = JsonRenderer::render($response,200,$datin);
    	}

    // ***************************************************************************************************************************************************************************************
    	// SUBIR IMAGEN
    	public function nuevoImagen(Request $request, Response $response, $args) {
    		try {
          $IDUSUARIO = (int)$request->getQueryParams('IDUSUARIO');

    			$directorio = Constante::REPOSITORIO_FILE."Contenidos/Imagenes/";
    			$uploadedFiles = $request->getUploadedFiles();
    			$uploadedFile = $uploadedFiles['upload']; //file_imagen
    			$tipoFile  = $uploadedFile->getClientMediaType();

    			if ($tipoFile == "image/jpeg" || $tipoFile == "image/jpg" || $tipoFile == "image/png") {
            //guardar el archivo en el directorio
            $tmp = ArchivoControlador::GuardarArchivo($uploadedFile,$directorio,$tipoFile,$IDUSUARIO);
            //guarda los datos del archivo en la BD
            $archivo = new Archivo();
            $archivo = $tmp["data"];


    				if ($tmp["success"]) {
    					$ruta = Constante::DOMAINSITE.$archivo->DIRECTORIO."/".$archivo->NOMBRE_ENCRIPTADO;

    					$datin["url"] = $ruta;
              $datin["fileName"] = $archivo->NOMBRE_ENCRIPTADO;
              $datin["uploaded"] = 1;

    					$mensaje = "La IMAGEN se subió correctamente en la siguiente ruta:<br><b>".$ruta."</b>";
    					$estado = true;
    				} else {
    					$mensaje = "Hubo un error al capturar la imagen guardada.";
    					$estado = false;
    				}
    			} else {
    				$mensaje = "Solo se permite archivos con extensión: .jpeg / .jpg / .png";
    				$estado = false;
    			}
    		} catch (\ErrorException $e){
    			$mensaje = "Ocurrió un error inesperado al Guardar la Imagen: ".$e;
    			$estado = false;
    		}

    		$datin["mensaje"] = $mensaje;
    		$datin["success"] = $estado;

        $obj1 = JsonRenderer::render($response,200,$datin);

        return $obj1;

    	}

    	// SUBIR DOCUMENTO
    	public function nuevoDocumento(Request $request, Response $response, $args) {
    		try {
          $IDUSUARIO = (int)$request->getQueryParams('IDUSUARIO');
    			$directorio = Constante::REPOSITORIO_FILE."Contenidos/Documentos/";
    			$uploadedFiles = $request->getUploadedFiles();
    			$uploadedFile = $uploadedFiles['upload'];
    			$tipoFile  = $uploadedFile->getClientMediaType();

    			if ($tipoFile == "application/pdf" || $tipoFile == "application/msword" || $tipoFile == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $tipoFile == "application/vnd.ms-excel" || $tipoFile == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
    				//$tmp = ContenidoControlador::GuardarArchivo($uploadedFile,$directorio,$tipoFile);
            $tmp = ArchivoControlador::GuardarArchivo($uploadedFile,$directorio,$tipoFile,$IDUSUARIO);
            //guarda los datos del archivo en la BD
            $archivo = new Archivo();
            $archivo = $tmp["data"];
            if ($tmp["success"]) {
    					//$ruta = Constante::DOMAINSITE.$directorio.$tmp["data"]["nombre_encriptado"];
              $ruta = Constante::DOMAINSITE.$archivo->DIRECTORIO."/".$archivo->NOMBRE_ENCRIPTADO;
              $datin["url"] = $ruta;
              $datin["fileName"] = $archivo->NOMBRE_ENCRIPTADO;
              $datin["uploaded"] = 1;
    					$mensaje = "El DOCUMENTO se subió correctamente en la siguiente ruta:<br><b>".$ruta."</b>";
    					$estado = true;
    				} else {
    					$mensaje = "Hubo un error al capturar el DOCUMENTO guardado.";
    					$estado = false;
    				}
    			} else {
    				$mensaje = "Solo se permite archivos con extensión: .pdf / .doc / .docx / .xls / .xlsx";
    				$estado = false;
    			}
    		} catch (\ErrorException $e){
    			$mensaje = "Ocurrió un error inesperado al Guardar el ARCHIVO: ".$e;
    			$estado = false;
    		}

    		$datin["mensaje"] = $mensaje;
    		$datin["success"] = $estado;
        $obj1 = JsonRenderer::render($response,200,$datin);

        return $obj1;
    	}

    // ***************************************************************************************************************************************************************************************
    	// GUARDAR UN ARCHIVO EN LA BD Y EN EL DIRECTORIO
    	public function GuardarArchivo($uploadedFile,$directorio,$tipoFile,$IDUSUARIO){

    		if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

          $directory=Acl::creaCarpetaConFecha($directorio); //crea la carpeta en un directorio asignado y crea la fecha
          $filename = Acl::moveUploadedFile($directory, $uploadedFile); //guarda archivo en el directorio
          $rs_peso = Acl::ObtenerPesoFile($uploadedFile->getSize()); //obtiene peso del archivo

          $archivo = new Archivo();
          $archivo->NOMBRE = $uploadedFile->getClientFilename();
          $archivo->PESO = $rs_peso["peso"];
          $archivo->UNIDAD = $rs_peso["unidad"];
          $archivo->FORMATO = $tipoFile;
          $archivo->DIRECTORIO = $directory;
          $archivo->NOMBRE_ENCRIPTADO = $filename;
          $archivo->USUARIO_CREACION = $IDUSUARIO;
          //guarda archivo en la BD
          $tmp = (array)$this->archivoRepository->nuevoArchivo($archivo);
          return $tmp;
    		}
    	}

  		// RETORNA DATOS DEL USUARIO EN CASO EXISTA
  		public function getColumnasArchivo(Request $request, Response $response, $args){

			$columnasArchivo = $this->archivoRepository->getColumnasArchivo();
			$obj1 = JsonRenderer::render($response,200,$columnasArchivo);
			return $obj1;
  		}

  		public function getFilasArchivo(Request $request, Response $response, $args){
			$filasArchivo = $this->archivoRepository->getFilasArchivo();
  			return JsonRenderer::RespuestaJSON($response,$filasArchivo);
  		}


/*APIS ARCHIVO*/



  		public function cambiarEstado(Request $request, Response $response, $args){

              try {
                  $datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
                  Archivo::where('IDARCHIVO', $datos["idArchivo"])
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

  			//guardar un nuevo archivo
  			public function nuevoArchivo(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/
  					$ruta = Acl::GenerarURL($datos["NOMBRE_CORTO"]);
  					$datos["RUTA"]= $ruta;
  					$idArchivo = Archivo::insertGetId($datos);
  					$datos["IDARCHIVO"] = $idArchivo;
  					$datos["ACCION"] = $idArchivo;

  					$mensaje ="se registró correctamente el archivo";
  					$estado = true;
  					$datin["archivo"]= $datos;

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
  			//actualiza los campos del archivo
  			public function editarArchivo(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/

  					Archivo::where('IDARCHIVO', $datos["IDARCHIVO"])
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

  				//actualiza los campos del archivo
  			public function eliminarArchivo(Request $request, Response $response, $args){

  				try {
  					$datos = $request->getParsedBody(); /*se utiliza para recibir parametros post*/

  				Archivo::where('IDARCHIVO', $datos["IDARCHIVO"])
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
