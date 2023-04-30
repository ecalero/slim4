<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Login;

use App\Domain\Login\Login;
use App\Domain\Login\LoginNotFoundException;
use App\Domain\Login\LoginRepository;
use App\Domain\Usuario\Usuario;
use App\Domain\CatalogoTablas\CatalogoTablas;


//helper
use App\Helper\Hash;
use App\Helper\Acl;
use App\Helper\JsonRequest;
use App\Helper\JsonRenderer;

use Odan\Session\SessionInterface;

use Illuminate\Database\Connection;

//creando token
use Firebase\JWT\JWT;

class InMemoryLoginRepository implements LoginRepository
{



    /**
     * The constructor.
     *
     * @param Connection $connection The database connection
     */
    protected $connection;
    /**
     * @var Login[]
     */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
    /**
     * @var SessionInterface
     */
    private $session;
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
        return Login::listarUsers();
        //return $this->connection->table('USUARIO')->get()->toArray();
        //return array_values($this->users);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): Login
    {
        if (!isset($this->users[$id])) {
            throw new LoginNotFoundException();
        }

        return $this->users[$id];
    }

    /**
     * {@inheritdoc}
     */

    public function getUserById(int $userId): array
    {

       // $row = $this->connection->table('USUARIO')->find($userId);
        $row = Login::getUserById($userId);
        if(!$row) {
            throw new \DomainException(sprintf('Login not found: %s', $userId));
        }

        return $row;
    }


        /**
     * {@inheritdoc}
     */

    public function VerificarLogin(array $USUARIO): array
    {
       // $row = $this->connection->table('USUARIO')->find($userId);

        $row = Login::VerificarLogin($USUARIO);
        if(!$row) {
            throw new \DomainException(sprintf('Login not found: %s', $userId));
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
           'iat' => $time, // Tiempo que inicia el token
           'exp' => $time + (1*60*60*60), // Tiempo que expira el token (+1 hora)
           'tipo' => 'HS256',
           'data' => $USUARIO
       );

       $jwt = JWT::encode($token, $key);

       $data = JWT::decode($jwt, $key, array('HS256'));

       $datin=["clave"=>$data, "token"=> $jwt, ];

       return $datin;

    }

    /**
     * {@inheritdoc}
     */

    public function verificaSesionAbierta(int $IDUSUARIO): array
    {

        $row = Login::verificaSesionAbierta($IDUSUARIO);
        if(!$row) {
            throw new \DomainException(sprintf('Login not found: %s', $IDUSUARIO));
        }

        return $row;

    }

    /**
     * {@inheritdoc}
     */

    public function loginSesion(int $IDUSUARIO): array
    {

        $tmp = Usuario::getDatosSesion($IDUSUARIO);

        if ($tmp["success"]) {
            //$session = new \App\Helper\Session;

            // Create a standard session hanndler

            // Clear all flash messages
            $flash = $this->session->getFlash();
            $flash->clear();

            // Login successfully
            // Clears all session data and regenerate session ID
            $this->session->destroy();
            $this->session->start();
            $this->session->regenerateId();

            $this->session->set('user', $tmp["data"]);
            $this->session->set('IDUSUARIO', $tmp["data"]["IDUSUARIO"]);
            $this->session->set('USUARIO', $tmp["data"]["USUARIO"]);
            $this->session->set('IDPERSONA', $tmp["data"]["IDPERSONA"]);
            $this->session->set('ESTADO', $tmp["data"]["ESTADO"]);
            $this->session->set('BAJA', $tmp["data"]["BAJA"]);
            $this->session->set('NROINTENTOS', $tmp["data"]["NROINTENTOS"]);
            $this->session->set('TIPODOCUMENTO', $tmp["data"]["TIPODOCUMENTO"]);
            $this->session->set('NRODOCUMENTO', $tmp["data"]["NRODOCUMENTO"]);
            $this->session->set('NOMBRES', $tmp["data"]["NOMBRES"]);
            $this->session->set('APELLIDO_PATERNO', $tmp["data"]["APELLIDO_PATERNO"]);
            $this->session->set('APELLIDO_MATERNO', $tmp["data"]["APELLIDO_MATERNO"]);
            $this->session->set('FOTO', $tmp["data"]["FOTO"]);
            $this->session->set('FECHA_NACIMIENTO', $tmp["data"]["FECHA_NACIMIENTO"]);
            $this->session->set('GENERO', $tmp["data"]["GENERO"]);
            $this->session->set('FECHA_REGISTRO', $tmp["data"]["FECHA_REGISTRO"]);
            $this->session->set('TIPO_PERSONA', $tmp["data"]["TIPO_PERSONA"]);
            $this->session->set('TOKEN', $tmp["data"]["ACCESS_TOKEN"]);
            $flash->add('success', 'Login successfully');
            // Commit and close the session
            $this->session->save();
            

            $datin["session"]=$tmp;
            $mensaje = "Sesi�n Iniciada Correctamente.";
            $estado = true;
        } else {
            $mensaje = "Error al crear la Variable de Sesi�n.";
            $estado = false;
        }

        $datin["mensaje"] = $mensaje;
        $datin["success"] = $estado;

        if(!$datin) {
            throw new \DomainException(sprintf('Login not found: %s', $IDUSUARIO));
        }

        return $datin;

    }
    /**
     * {@inheritdoc}
     */

    public function loginDatosApp(int $IDUSUARIO): array
    {

        $row = Usuario::getDatosApp($IDUSUARIO);
        if(!$row) {
            throw new \DomainException(sprintf('loginDatosApp not found: %s', $IDUSUARIO));
        }

        return $row;
    }

    /**
     * {@inheritdoc}
     */

    public function loginParametrosSesion(): array
    {

        $row = CatalogoTablas::getTabTablas();

        if(!$row) {
            throw new \DomainException(sprintf('loginParametrosSesion no existe'));
        }

        return $row;
    }

}
