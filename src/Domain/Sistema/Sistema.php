<?php
declare(strict_types=1);

namespace App\Domain\Sistema;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\Modulos\Modulos;
use App\Domain\SistemaModulos\SistemaModulos;
class Sistema extends Model implements JsonSerializable
{
public $table = 'SISTEMA';
public $timestamps = false;
protected $primaryKey = 'IDSISTEMA';


public static function listaSistemas(){
	$result = array();
	$query = Sistema::query()
	->select("*")
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

public static function modulos()
	{
	 return (new Sistema())->hasManyThrough(
		new Modulos(), //modelo final
		new SistemaModulos(), //modelo intermedio
		'IDSISTEMA', // Foreign key on users table... . El tercer argumento es el nombre de la clave externa en el modelo intermedio
		'IDMODULO', // Foreign key on posts table...
		'IDSISTEMA', // Local key on countries table...
		'IDMODULO' // Local key on users table...
	)->with('objacceso');
}

public static function getOpciones($datos){
	$result = array();
	//$datos = ["IDSISTEMA"=>$idSistema, "ESTADO"=> 1];
	$query = Sistema::query()
	->selectRaw("SISTEMA.*")
	->with([
		'modulos'=>function($q)use($datos){
			 return $q->with([
				'objacceso'=>function($q)use($datos){
					 return $q->whereIn('IDOBJACCESO',$datos["IDOBJACCESOS"]);}
				])->whereIn('MODULOS.IDMODULO',$datos["IDMODULOS"]);}
		])
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




			public function moduls()
	{
			return $this->belongsToMany('App\Modelo\Modulos', "SISTEMA","IDSISTEMA","IDSISTEMA");
}


public static function getTableColumns() {
			return (new Sistema)
					->getConnection()
					->getSchemaBuilder()
					->getColumnListing((new Sistema)->getTable());
}

public static function getTableFilas() {
	$result = array();
	//$datos = ["IDSISTEMA"=>$idSistema, "ESTADO"=> 1];
	$query = Sistema::query()
	->selectRaw("SISTEMA.*,SISTEMA.IDSISTEMA AS ACCION")
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


public static function getSistemasActivo(){
	$result = array();
	$query = Sistema::query()
	->selectRaw("IDSISTEMA,NOMBRE")
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

public static function listaSistemasModulos($idSistema){
	$result = array();
	$datos = ["IDSISTEMA"=>$idSistema, "ESTADO"=> 1];
	$query = Login::query()
	->select("*")
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

public static function getSistemaByRuta($ruta){
	$result = array();
	$where = ["RUTA"=>$ruta];
	$query = Sistema::query()
	->select("*")
	->where($where)
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

}
