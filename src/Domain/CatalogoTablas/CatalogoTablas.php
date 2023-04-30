<?php
declare(strict_types=1);

namespace App\Domain\CatalogoTablas;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\Modulos\Modulos;
use App\Domain\TabTablas\TabTablas;
class CatalogoTablas extends Model implements JsonSerializable
{
	protected $table = 'CATALOGO_TABLAS';
	public $timestamps = false;
	protected $primaryKey = 'IDCATALOGOTABLAS';

	public static function getRolesPorUsuario($idusuario,$tipo,$pag=1){
		$reg_por_pag = Constante::Item_Pag_5;
		$result = array();
		$campos = "CATALOGO_TABLAS.idusuario,CATALOGO_TABLAS.idrol,CATALOGO_TABLAS.estado,rol.nombre_rol,usuario.username";
		switch ($tipo) {
			case "1": // ROLES POR USUARIO PARA LOGIN
				$query = CatalogoTablas::query()
				->selectRaw($campos)
				->join("rol","rol.idrol","=","CATALOGO_TABLAS.idrol")
				->join("usuario","usuario.idusuario","=","CATALOGO_TABLAS.idusuario")
				->where("CATALOGO_TABLAS.idusuario",$idusuario)
				->where("CATALOGO_TABLAS.estado",1)
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
				$query = CatalogoTablas::query()
				->selectRaw($campos)
				->join("rol","rol.idrol","=","CATALOGO_TABLAS.idrol")
				->join("usuario","usuario.idusuario","=","CATALOGO_TABLAS.idusuario")
				->where("CATALOGO_TABLAS.idusuario",$idusuario)
				->offset(($pag-1)*$reg_por_pag)
				->limit($reg_por_pag)
				->get()
				->toArray();

				$query_count = CatalogoTablas::query()
				->selectRaw("COUNT(DISTINCT CATALOGO_TABLAS.idrol) as total_registros")
				->join("rol","rol.idrol","=","CATALOGO_TABLAS.idrol")
				->join("usuario","usuario.idusuario","=","CATALOGO_TABLAS.idusuario")
				->where("CATALOGO_TABLAS.idusuario",$idusuario)
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
		CatalogoTablas::insert($data);
	}

	public static function ActualizarRegistro($idusuario,$idrol,$data){
		CatalogoTablas::query()
		->where(['idusuario'=>intval($idusuario)])
		->where(['idrol'=>intval($idrol)])
		->update($data);
	}

	public static function VerificarCatalogoTablas($idusuario,$idrol){
		$result = array();
		$query = CatalogoTablas::query()
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

	public static function tabTablas1()
    {
			$result = array();
			$query = CatalogoTablas::query()
			->selectRaw("*")->with('getTabTablas')
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

	public static function getTabTablas()
    {
			return CatalogoTablas::with('tabtablas')
			//return (new CatalogoTablas())->hasMany(new TabTablas(),"IDCATALOGOTABLAS","IDCATALOGOTABLAS")
			//return (new CatalogoTablas())->belongsToMany(new TabTablas(), "CATALOGO_TABLAS","IDCATALOGOTABLAS","IDCATALOGOTABLAS")
			->get()
			->toArray();
	}

	public static function tabtablas()
    {
        return (new CatalogoTablas())->hasMany(new TabTablas(),"IDCATALOGOTABLAS","IDCATALOGOTABLAS");

    }



}
