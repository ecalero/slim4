<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Componente;

use App\Domain\Componente\Componente;
use App\Domain\Componente\ComponenteNotFoundException;
use App\Domain\Componente\ComponenteRepository;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;


use Illuminate\Database\Connection;

//creando token
use Firebase\JWT\JWT;
use Odan\Session\SessionInterface;

class InMemoryComponenteRepository implements ComponenteRepository
{



    /**
     * The constructor.
     *
     * @param Connection $connection The database connection
     */
    protected $connection;
    /**
     * @var Componente[]
     */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
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
        return Componente::listarUsers();
        //return $this->connection->table('USUARIO')->get()->toArray();
        //return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): Componente
    {
        if (!isset($this->users[$id])) {
            throw new ComponenteNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * {@inheritdoc}
     */

    public function getUserById(int $userId): array
    {

       // $row = $this->connection->table('USUARIO')->find($userId);
        $row = Componente::getUserById($userId);
        if(!$row) {
            throw new \DomainException(sprintf('Componente not found: %s', $userId));
        }

        return $row;
    }


        /**
     * {@inheritdoc}
     */

    public function VerificarLogin(array $USUARIO): array
    {
       // $row = $this->connection->table('USUARIO')->find($userId);

        $row = Componente::VerificarLogin($USUARIO);
        if(!$row) {
            throw new \DomainException(sprintf('Componente not found: %s', $userId));
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

       $usuario = new Componente;
       $usuario =  Componente::getComponente($token);
       $menuaccesos = Componente::listaAccesos($usuario->IDUSUARIO);

       $idObjAccesos = Array();
       $idModulos = Array();
       foreach ($menuaccesos["data"][0]["roles"][0]["accesos"] as $key => $acceso) {
         # code...
         array_push($idObjAccesos, $acceso["IDOBJACCESO"]);
         array_push($idModulos, $acceso["IDMODULO"]);
       }
       $datos["IDOBJACCESOS"] = $idObjAccesos;
       $datos["IDMODULOS"] = array_unique($idModulos);

       $sistema = Componente::getOpciones($datos);
       return $sistema;

    }

    /* FUNCIONES DE SISTEMA*/
    public function getColumnasComponente(): array{
        $columnas =Componente::getTableColumns();
        $caracteres = array("_");
        $index =0;
        foreach ($columnas as $index=>$columna) {
            $colum[$index]["name"]=$columna;
            $colum[$index]["title"]=str_replace($caracteres, " ", $columna);
            $colum[$index]["breakpoints"]="xs sm";
            $colum[$index]["funcion"]="(row: ComponenteData) => `\${row.".$columna."}`";
            //$colum[$index]["formatter"]=new Raw("function(value, options, rowData){ return '<span>' + value + '</span>';}");
            //$colum[$index]["ACCION"]="A";
        }
      /*  $colum[$index+1]["name"]="ACCION";
            $colum[$index+1]["title"]="ACCIÓN";
            $colum[$index+1]["breakpoints"]="xs sm";*/
        //$json = Encoder::encode($colum);
        //return JsonRenderer::RespuestaJSONEncoder($response,$json);
        return $colum;
    }
    public function getFilasComponente(): array{
        $filas =Componente::getTableFilas();
        return $filas["data"];
    }


     public function nuevoComponente(Componente $componente): Componente{
       $res = Componente::nuevoComponente($componente);
       return $res;
     }

}
