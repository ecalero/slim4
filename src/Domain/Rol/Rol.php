<?php
declare(strict_types=1);

namespace App\Domain\Rol;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\Modulos\Modulos;
use App\Domain\UsuarioRol\UsuarioRol;
use App\Domain\ObjAcceso\Objacceso;
use App\Domain\Acceso\Acceso;

use App\Helper\Constante;

class Rol extends Model implements JsonSerializable
{
	protected $table = 'ROL';
	public $timestamps = false;
	protected $primaryKey = 'IDROL';


	public static function accesos()
    {
	   return (new Rol())->hasManyThrough(
			new Objacceso(), //modelo final
			new Acceso(), //modelo intermedio
			'IDROL', // Foreign key on users table... . El tercer argumento es el nombre de la clave externa en el modelo intermedio
			'IDOBJACCESO', // Foreign key on posts table...
			'IDROL', // Local key on countries table...
			'IDOBJACCESO' // Local key on users table...
	   )->with('menus','modulo')->where("ACCESO.ESTADO",Constante::ESTADO_ACTIVO);
	}


	public static function ListarRoles($pag,$tipo){
		$reg_por_pag = Constante::Item_Pag_20;
		$result = array();

		switch ($tipo) {
			case 1: // TODOS LOS REGISTROS CON PAGINACION (PARA MANTENIMIENTO DE ROLES)
				$query = Rol::query()
				->selectRaw("*")
				->offset(($pag-1)*$reg_por_pag)
				->limit($reg_por_pag)
				->get()
				->toArray();

				$query_count = Rol::query()
				->selectRaw("COUNT(DISTINCT idrol) as total_registros")
				->get()
				->toArray();

				if (!empty($query)) {
					$result["data"] = $query;
					$result["reg_por_pag"] = $reg_por_pag;
					$result["total_registros"] = $query_count[0]["total_registros"];
					$success = true;
					$message = "Los datos se listaron correctamente";
				} else {
					$result["total_registros"] = 0;
					$success = false;
					$message = "No existen resultados";
				}
			break;

			case 2: // REGISTROS FILTRADO POR ESTADO SIN PAGINACION (PARA ASIGNAR ROL A UN USUARIO)
				$query = Rol::query()
				->selectRaw("*")
				->where("estado",1)
				->get()
				->toArray();

				if (!empty($query)) {
					$result["data"] = $query;
					$success = true;
					$message = "Los datos se listaron correctamente";
				} else {
					$success = false;
					$message = "No existen resultados";
				}
			break;
		}
		$result["success"] = $success;
		$result["message"] = $message;
		return $result;
	}

	public static function FiltrarRoles($pag,$params){
		$reg_por_pag = Constante::Item_Pag_20;
		$result = array();

		$query = Rol::query()
		->selectRaw("*")
		->where("nombre_rol","like","%".$params["nombre_rol"]."%")
		->where("estado","like","%".$params["estado"]."%")
		->offset(($pag-1)*$reg_por_pag)
		->limit($reg_por_pag)
		->get()
		->toArray();

		$query_count = Rol::query()
		->selectRaw("COUNT(DISTINCT idrol) as total_registros")
		->where("nombre_rol","like","%".$params["nombre_rol"]."%")
		->where("estado","like","%".$params["estado"]."%")
		->get()
		->toArray();

		if (!empty($query)) {
			$result["data"] = $query;
			$result["reg_por_pag"] = $reg_por_pag;
			$result["total_registros"] = $query_count[0]["total_registros"];
			$success = true;
			$message = "Los datos se listaron correctamente";
		} else {
			$result["total_registros"] = 0;
			$success = false;
			$message = "No existen resultados";
		}
		$result["success"] = $success;
		$result["message"] = $message;
		return $result;
	}

	public static function DatosRolById($idrol){
		$result = array();
		$query = Rol::query()
		->selectRaw("*")
		->where("idrol",$idrol)
		->get()
		->toArray();

		if (!empty($query)) {
			$result["data"] = $query[0];
			$success = true;
			$message = "Los datos se listaron correctamente";
		} else {
			$success = false;
			$message = "No existen resultados";
		}
		$result["success"] = $success;
		$result["message"] = $message;
		return $result;
	}

	public static function DatosRolByNombre($nombre_rol){
		$result = array();
		$query = Rol::query()
		->selectRaw("*")
		->where("nombre_rol",$nombre_rol)
		->get()
		->toArray();

		if (!empty($query)) {
			$result["data"] = $query[0];
			$success = true;
			$message = "Los datos se listaron correctamente";
		} else {
			$success = false;
			$message = "No existen resultados";
		}
		$result["success"] = $success;
		$result["message"] = $message;
		return $result;
	}

	public static function NuevoRegistro($data){
		Rol::insert($data);
	}

	public static function ActualizarRegistro($idrol,$data){
		Rol::query()
		->where(['idrol'=>intval($idrol)])
		->update($data);
	}
}
