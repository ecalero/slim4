<?php
declare(strict_types=1);

namespace App\Domain\TabTablas;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\Modulos\Modulos;
class TabTablas extends Model implements JsonSerializable
{
	protected $table = 'TAB_TABLAS';
	public $timestamps = false;
	protected $primaryKey = 'IDTABLA';

	public static function getRolesPorUsuario($idusuario,$tipo,$pag=1){
		$reg_por_pag = Constante::Item_Pag_5;
		$result = array();
		$campos = "TAB_TABLAS.idusuario,TAB_TABLAS.idrol,TAB_TABLAS.estado,rol.nombre_rol,usuario.username";
		switch ($tipo) {
			case "1": // ROLES POR USUARIO PARA LOGIN
				$query = TabTablas::query()
				->selectRaw($campos)
				->join("rol","rol.idrol","=","TAB_TABLAS.idrol")
				->join("usuario","usuario.idusuario","=","TAB_TABLAS.idusuario")
				->where("TAB_TABLAS.idusuario",$idusuario)
				->where("TAB_TABLAS.estado",1)
				->where("rol.estado",1)
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

			case "2": // ROLES POR USUARIO CON PAGINACION PARA ASIGNAR ROL A UN USUARIO
				$query = TabTablas::query()
				->selectRaw($campos)
				->join("rol","rol.idrol","=","TAB_TABLAS.idrol")
				->join("usuario","usuario.idusuario","=","TAB_TABLAS.idusuario")
				->where("TAB_TABLAS.idusuario",$idusuario)
				->offset(($pag-1)*$reg_por_pag)
				->limit($reg_por_pag)
				->get()
				->toArray();

				$query_count = TabTablas::query()
				->selectRaw("COUNT(DISTINCT TAB_TABLAS.idrol) as total_registros")
				->join("rol","rol.idrol","=","TAB_TABLAS.idrol")
				->join("usuario","usuario.idusuario","=","TAB_TABLAS.idusuario")
				->where("TAB_TABLAS.idusuario",$idusuario)
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
		}

		$result["success"] = $success;
		$result["message"] = $message;
		return $result;
	}

	public static function NuevoRegistro($data){
		TabTablas::insert($data);
	}

	public static function ActualizarRegistro($idusuario,$idrol,$data){
		TabTablas::query()
		->where(['idusuario'=>intval($idusuario)])
		->where(['idrol'=>intval($idrol)])
		->update($data);
	}

	public static function VerificarTabTablas($idusuario,$idrol){
		$result = array();
		$query = TabTablas::query()
		->selectRaw("*")
		->where("idusuario",$idusuario)
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

	public static function tabTablas()
    {
			$result = array();
			$query = TabTablas::query()
			->selectRaw("*")
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



}
