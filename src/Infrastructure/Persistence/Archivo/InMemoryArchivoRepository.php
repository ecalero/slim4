<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Archivo;

use App\Domain\Archivo\Archivo;
use App\Domain\Archivo\ArchivoNotFoundException;
use App\Domain\Archivo\ArchivoRepository;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;


use Illuminate\Database\Connection;

//creando token
use Firebase\JWT\JWT;
use Odan\Session\SessionInterface;

class InMemoryArchivoRepository implements ArchivoRepository
{



    /**
     * The constructor.
     *
     * @param Connection $connection The database connection
     */
    protected $connection;
    /**
     * @var Archivo[]
     */
    private $users;

     /**
      * @var SessionInterface
      */
     private  $session;

    public function __construct(  Connection $connection, SessionInterface $session)
    {
        $this->connection = $connection;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        //$rows = $this->connection->table('USUARIO')->get()->toArray();
        //return $rows;
        return Archivo::listarUsers();
        //return $this->connection->table('USUARIO')->get()->toArray();
        //return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): Archivo
    {
        if (!isset($this->users[$id])) {
            throw new ArchivoNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * {@inheritdoc}
     */

    public function getUserById(int $userId): array
    {

       // $row = $this->connection->table('USUARIO')->find($userId);
        $row = Archivo::getUserById($userId);
        if(!$row) {
            throw new \DomainException(sprintf('Archivo not found: %s', $userId));
        }

        return $row;
    }


        /**
     * {@inheritdoc}
     */

    public function VerificarLogin(array $USUARIO): array
    {
       // $row = $this->connection->table('USUARIO')->find($userId);

        $row = Archivo::VerificarLogin($USUARIO);
        if(!$row) {
            throw new \DomainException(sprintf('Archivo not found: %s', $userId));
        }

        return $row;
    }

    /**
     * {@inheritdoc}
     */

    public function crearToken(array $USUARIO): array
    {
       // $row = $this->connection->table('USUARIO')->find($userId);
       $time = time();
       $key = 'edwin';
       $token = array(
           'iat' => $time, // Tiempo que inició el token
           'exp' => $time + (1*60*60*60), // Tiempo que expirará el token (+1 hora)
           'tipo_token' => 'HS256',
           'tipo_acceso' => 'NORMAL',
           'estado'=>1,
           'data' => $USUARIO
       );

       $jwt = JWT::encode($token, $key);

       $data = JWT::decode($jwt, $key, array('HS256'));

       $datin=["datos"=>$data, "token"=> $jwt];

       return $datin;

    }

    /**
     * {@inheritdoc}
     */

    public function getMenuAccesos(String $token): array
    {
       // $row = $this->connection->table('USUARIO')->find($userId);
       //$token = $this->session->get("TOKEN");

       $usuario = new Archivo;
       $usuario =  Archivo::getArchivo($token);
       $menuaccesos = Archivo::listaAccesos($usuario->IDUSUARIO);

       $idObjAccesos = Array();
       $idModulos = Array();
       foreach ($menuaccesos["data"][0]["roles"][0]["accesos"] as $key => $acceso) {
         # code...
         array_push($idObjAccesos, $acceso["IDOBJACCESO"]);
         array_push($idModulos, $acceso["IDMODULO"]);
       }
       $datos["IDOBJACCESOS"] = $idObjAccesos;
       $datos["IDMODULOS"] = array_unique($idModulos);

       $sistema = Archivo::getOpciones($datos);
       return $sistema;

    }

    /* FUNCIONES DE SISTEMA*/
    public function getColumnasArchivo(): array{
        $columnas =Archivo::getTableColumns();

        $caracteres = array("_");

        foreach ($columnas as $index=>$columna) {
            $colum[$index]["name"]=$columna;
            $colum[$index]["title"]=str_replace($caracteres, " ", $columna);
            $colum[$index]["breakpoints"]="xs sm";
            //$colum[$index]["formatter"]=new Raw("function(value, options, rowData){ return '<span>' + value + '</span>';}");
            //$colum[$index]["ACCION"]="A";
        }
        $colum[$index+1]["name"]="ACCION";
            $colum[$index+1]["title"]="ACCIÓN";
            $colum[$index+1]["breakpoints"]="xs sm";
        //$json = Encoder::encode($colum);
        //return JsonRenderer::RespuestaJSONEncoder($response,$json);
        return $colum;
    }
    public function getFilasArchivo(): array{
        $filas =Archivo::getTableFilas();
        return $filas["data"];
    }
    /**
     * {@inheritdoc}
     */
    public function nuevoArchivo(Archivo $archivo): array{

      $res = Archivo::nuevoArchivo($archivo);
      return $res;
    }

}
