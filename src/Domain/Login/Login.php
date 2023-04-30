<?php
declare(strict_types=1);

namespace App\Domain\Login;
use App\Domain\Usuario\Usuario;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;


class Login extends Model implements JsonSerializable 
{

	protected $table = 'LOGIN';
	public $timestamps = false;
    protected $primaryKey = 'IDLOGIN';
    
    
    public static function verificaSesionAbierta($idUsuario){
        $result = array();
        $datos = ["IDUSUARIO"=>$idUsuario, "ESTADO"=> 1];
        $query = Login::query()
        ->select("*")
        ->where($datos)
        ->get()
        ->toArray();
        
        if (!empty($query)) {
            $result["data"] = $query;
            $success = true;
            $message = "Se tiene la siguiente sesion activa";
        } else {
            $success = false;
            $message = "No existen sesiones activas";
        }
        $result["success"] = $success;
        $result["message"] = $message;
        return $result;
    }
    
    public static function validaToken($token){
        $result = array();
        $datos = ["ACCESS_TOKEN"=>$token, "ESTADO"=> 1];
        $query = Login::query()
        ->select("*")
        ->where($datos)
        ->get()
        ->toArray();
        
        if (!empty($query)) {
            $result["data"] = $query;
            $success = true;
            $message = "Se tiene la siguiente sesion activa";
        } else {
            $success = false;
            $message = "No existen sesiones activas";
        }
        $result["success"] = $success;
        $result["message"] = $message;
        return $result;
    }
    
    
    
    public static function NuevoRegistro($data){
        Usuario::insert($data);
    }
    
    public static function ActualizarRegistro($idusuario,$data){
        Usuario::query()
        ->where(['idusuario'=>intval($idusuario)])
        ->update($data);
    }


    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'IDLOGIN' => $this->IDLOGIN,
            'LOGIN' => $this->LOGIN,
            'CLAVE' => $this->CLAVE,
            'DESCRIPCION' => $this->DESCRIPCION,
        ];
    }
}


