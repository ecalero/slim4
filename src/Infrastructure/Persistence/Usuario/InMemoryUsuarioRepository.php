<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Usuario;

use App\Domain\Usuario\Usuario;
use App\Domain\Usuario\UsuarioNotFoundException;
use App\Domain\Usuario\UsuarioRepository;

use App\Domain\Sistema\Sistema;

//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;


use Illuminate\Database\Connection;

//creando token
use Firebase\JWT\JWT;
use Odan\Session\SessionInterface;

class InMemoryUsuarioRepository implements UsuarioRepository
{



    /**
     * The constructor.
     *
     * @param Connection $connection The database connection
     */
    protected $connection;
    /**
     * @var Usuario[]
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
        return Usuario::listarUsers();
        //return $this->connection->table('USUARIO')->get()->toArray();
        //return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): Usuario
    {
        if (!isset($this->users[$id])) {
            throw new UsuarioNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * {@inheritdoc}
     */

    public function getUserById(int $userId): array
    {

       // $row = $this->connection->table('USUARIO')->find($userId);
        $row = Usuario::getUserById($userId);
        if(!$row) {
            throw new \DomainException(sprintf('Usuario not found: %s', $userId));
        }

        return $row;
    }


        /**
     * {@inheritdoc}
     */

    public function VerificarLogin(array $USUARIO): array
    {
       // $row = $this->connection->table('USUARIO')->find($userId);

        $row = Usuario::VerificarLogin($USUARIO);
        if(!$row) {
            throw new \DomainException(sprintf('Usuario not found: %s', $userId));
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

       $usuario = new Usuario;
       $usuario =  Usuario::getUsuario($token);
       $menuaccesos = Usuario::listaAccesos($usuario->IDUSUARIO);

       $idObjAccesos = Array();
       $idModulos = Array();
       foreach ($menuaccesos["data"][0]["roles"][0]["accesos"] as $key => $acceso) {
         # code...
         array_push($idObjAccesos, $acceso["IDOBJACCESO"]);
         array_push($idModulos, $acceso["IDMODULO"]);
       }
       $datos["IDOBJACCESOS"] = $idObjAccesos;
       $datos["IDMODULOS"] = array_unique($idModulos);

       $sistema = Sistema::getOpciones($datos);
       return $sistema;

    }

    //apis
    public function getUsuarioById(int $IDUSUARIO): array
    {
        $row = Usuario::getUsuarioById($IDUSUARIO);
        if(!$row) {
            throw new \DomainException(sprintf('Usuario not found: %s', $userId));
        }
        return $row;
    }



    /* FUNCIONES DE SISTEMA*/
    public function getColumnasUsuario(): array{
        $columnas =Usuario::getTableColumns();
        $caracteres = array("_");
        $index =0;
        foreach ($columnas as $index=>$columna) {
            $colum[$index]["name"]=$columna;
            $colum[$index]["title"]=str_replace($caracteres, " ", $columna);
            $colum[$index]["breakpoints"]="xs sm";
            $colum[$index]["funcion"]="(row: UsuarioData) => `\${row.".$columna."}`";
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
    public function getFilasUsuario(): array{
        $filas =Usuario::getTableFilas();
        return $filas["data"];
    }

    public function actualizarUsuario(array $datos): array{
        $usuario =Usuario::actualizaUsuario($datos);

        return $usuario["data"];
    }

    public function resetearClave(array $datos): array{
        $usuario =Usuario::resetearClave($datos);

        return $usuario["data"];
    }




}
