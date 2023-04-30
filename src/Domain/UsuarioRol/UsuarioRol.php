<?php
declare(strict_types=1);

namespace App\Domain\UsuarioRol;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\Modulos\Modulos;
use App\Domain\UsuarioRol\UsuarioRol;
use Thiagoprz\CompositeKey\HasCompositeKey;
use App\Helper\Constante;

use Illuminate\Database\Capsule\Manager as Capsule;
class UsuarioRol extends Model implements JsonSerializable
{
	protected $table = 'USUARIO_ROL';
	public $timestamps = false;
	use HasCompositeKey;
	protected $primaryKey = array('IDUSUARIO', 'IDROL');



	public static function getTableColumns() {
		/* return (new UsuarioRol)
							->getConnection()
							->getSchemaBuilder()
							->getColumnListing((new UsuarioRol)->getTable()); */


		$result = array();
		$item = UsuarioRol::query()
		->selectRaw("USUARIO_ROL.IDUSUARIO,
		USUARIO_ROL.IDROL,
		ROL.NOMBRE,
		ROL.DESCRIPCION,
		ROL.ESTADO,
		USUARIO_ROL.ESTADO")
		->join("ROL","USUARIO_ROL.IDROL","=","ROL.IDROL")
		->first();

$attributes = array_keys($item->getOriginal());

return $attributes;
		}

		public static function getTableFilas($IDUSUARIO) {
			$result = array();
			//$datos = ["IDPLANTILLA"=>$idUsuarioRol, "ESTADO"=> 1];
			/* $query = UsuarioRol::query()
				->selectRaw("*")
				->get()
				->toArray();
			var_dump($query);
			exit; */

			//$query = UsuarioRol::all()->toArray();

			/* $first = Capsule::table('USUARIO_ROL')
            ->whereNotNull('IDROL');

			$query = Capsule::table('ROL')
					->whereNotNull('IDROL')
					->union($first)
					->get()
					->toArray(); */

					$query = UsuarioRol::query()
			->selectRaw("USUARIO_ROL.IDUSUARIO,
			ROL.IDROL,
			ROL.NOMBRE,
			ROL.DESCRIPCION,
			USUARIO_ROL.ESTADO")
        ->rightJoin('ROL', function ($join) use ($IDUSUARIO) {
            $join->on('ROL.IDROL', '=', 'USUARIO_ROL.IDROL')
			->where('USUARIO_ROL.IDUSUARIO', '=', $IDUSUARIO);
        })

        ->get()
		->toArray();




			/* $query = UsuarioRol::query()
			->selectRaw("USUARIO_ROL.IDUSUARIO,
			ROL.IDROL,
			ROL.NOMBRE,
			ROL.DESCRIPCION,
			USUARIO_ROL.ESTADO")
			->crossJoin("ROL")
			->where("ROL.ESTADO",Constante::ESTADO_ACTIVO)
			->where("USUARIO_ROL.IDUSUARIO",$IDUSUARIO)
			->orWhere('USUARIO_ROL.IDUSUARIO', '=', NULL)
			->get()
				->toArray(); */



			if (!empty($query)) {
				$result["data"] = $query;
				$success = true;
				$message = "Se listo correctamente";
			} else {
				$success = false;
				$message = "No existen datos";
			}
			$result["success"] = $success;
			$result["message"] = $message;
			return $result;
			}




	public static function getRolesPorUsuario($idusuario,$tipo,$pag=1){
		$reg_por_pag = Constante::Item_Pag_5;
		$result = array();
		$campos = "USUARIO_ROL.idusuario,USUARIO_ROL.idrol,USUARIO_ROL.estado,rol.nombre_rol,usuario.username";
		switch ($tipo) {
			case "1": // ROLES POR USUARIO PARA LOGIN
				$query = UsuarioRol::query()
				->selectRaw($campos)
				->join("rol","rol.idrol","=","USUARIO_ROL.idrol")
				->join("usuario","usuario.idusuario","=","USUARIO_ROL.idusuario")
				->where("USUARIO_ROL.idusuario",$idusuario)
				->where("USUARIO_ROL.estado",1)
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
				$query = UsuarioRol::query()
				->selectRaw($campos)
				->join("rol","rol.idrol","=","USUARIO_ROL.idrol")
				->join("usuario","usuario.idusuario","=","USUARIO_ROL.idusuario")
				->where("USUARIO_ROL.idusuario",$idusuario)
				->offset(($pag-1)*$reg_por_pag)
				->limit($reg_por_pag)
				->get()
				->toArray();

				$query_count = UsuarioRol::query()
				->selectRaw("COUNT(DISTINCT USUARIO_ROL.idrol) as total_registros")
				->join("rol","rol.idrol","=","USUARIO_ROL.idrol")
				->join("usuario","usuario.idusuario","=","USUARIO_ROL.idusuario")
				->where("USUARIO_ROL.idusuario",$idusuario)
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

	public static function nuevoUsuarioRol($usuariorol){
		//UsuarioRol::insert($usuariorol);
		$usuariorol->save();
		return $usuariorol;
	}

	public static function ActualizarRegistro($idusuario,$idrol,$data){
		UsuarioRol::query()
		->where(['idusuario'=>intval($idusuario)])
		->where(['idrol'=>intval($idrol)])
		->update($data);
	}

	public static function eliminaUsuarioRol($usuariorol){
		$result = array();
		UsuarioRol::where(['IDROL'=>$usuariorol->IDROL, 'IDUSUARIO'=>$usuariorol->IDUSUARIO ])
  				->delete();
  			$success = true;
			$message = "Se eliminó el Rol al usuario correctamente";
		$result["success"] = $success;
		$result["message"] = $message;
		return $result;
	}

	public static function VerificarUsuarioRol($idusuario,$idrol){
		$result = array();
		$query = UsuarioRol::query()
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
}
