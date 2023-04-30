<?php
declare(strict_types=1);

namespace App\Application\Actions\Usuario;

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

class ListUsuariosAction extends UsuarioAction
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

    public  function login444(Request $request, Response $response, $args): Response
    {
        $users = $this->usuarioRepository->findAll();
        $this->logger->info("Users list was viewed.");
        $obj1 = JsonRenderer::render($response,200,$users);
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
                'id' => 1,
                'name' => 'Edwin'
            ]
        );

        $jwt = JWT::encode($token, $key);

        $data = JWT::decode($jwt, $key, array('HS256'));
        var_dump($jwt);
        var_dump($data);
        exit;
        return $data;
    }


    public function login(Request $request, Response $response){

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



}
