<?php
declare(strict_types=1);

namespace App\Domain\Modulos;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\ObjAcceso\Objacceso;

use App\Helper\Constante;
use App\Domain\Sistema\Sistema;
use App\Domain\SistemaModulos\SistemaModulos;

class Modulos extends Model implements JsonSerializable
{
	protected $table = 'MODULOS';
	public $timestamps = false;
	protected $primaryKey = 'IDMODULO';
	//protected $guarded = ['IDMODULOS', 'IDSISTEMA'];


	public static function sistemas1()
    {
		return (new Modulos())->belongsToMany(new SistemaModulos())->using(new SistemaModulos());
	}

	public static function sistemas()
    {
       //return (new Sistema())->hasMany(new SistemaModulos(),"IDSISTEMA","IDSISTEMA");
	   //return Sistema::hasMany(Sistema::class);

	   return (new Modulos())->hasManyThrough(
			new Sistema(), //modelo final
			new SistemaModulos(), //modelo intermedio
			'IDMODULO', // Foreign key on users table... . El tercer argumento es el nombre de la clave externa en el modelo intermedio
			'IDSISTEMA', // Foreign key on posts table...
			'IDMODULO', // Local key on countries table...
			'IDSISTEMA' // Local key on users table...
		);



	}



	public static function objacceso()
    {
       return (new Modulos())->hasMany(new Objacceso(),"IDMODULO","IDMODULO")->with('menus');
	   //return Sistema::hasMany(Sistema::class);
	}


	public static function getTableColumns() {
        return (new Modulos)
            ->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing((new Modulos)->getTable());
	}

	public static function getTableFilas($idSistema) {
		$result = array();
		//$datos = ["IDSISTEMA"=>$idSistema, "ESTADO"=> 1];
		$query = Modulos::query()
		->selectRaw("MODULOS.*,MODULOS.IDMODULO AS ACCION, SISTEMA_MODULOS.IDSISTEMA as IDSISTEMA ")
		->join("SISTEMA_MODULOS", "SISTEMA_MODULOS.IDMODULO", "=", "MODULOS.IDMODULO")
		->where("SISTEMA_MODULOS.IDSISTEMA",$idSistema)
		->get()
		->toArray();
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

	public static function getModulosActivo(){
		$result = array();
		$query = Modulos::query()
		->selectRaw("IDMODULO,NOMBRE")
		->where("ESTADO",Constante::ESTADO_ACTIVO)
		->get()
		->toArray();
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

	public static function getModulosActivoByIdSistema($IDSISTEMA){
		$result = array();
		$query = Modulos::query()
		->selectRaw("MODULOS.IDMODULO as id,MODULOS.NOMBRE as text")
		->where("MODULOS.ESTADO",Constante::ESTADO_ACTIVO)
		->join("SISTEMA_MODULOS", "SISTEMA_MODULOS.IDMODULO", "=", "MODULOS.IDMODULO")
		->where("SISTEMA_MODULOS.IDSISTEMA",$IDSISTEMA)
		->get()
		->toArray();
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


	public static function ListarModulos(){
		$result = array();
		$query = Modulos::query()
		->selectRaw("*")
		->orderBy("orden")
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
		$result["success"] = $success;
		$result["message"] = $message;
		return $result;
	}
}
