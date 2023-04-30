<?php
declare(strict_types=1);

namespace App\Domain\Plantilla;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\Modulos\Modulos;
use App\Domain\Plantilla\Plantilla;
class Plantilla extends Model implements JsonSerializable
{
public $table = 'PLANTILLA_WEB';
public $timestamps = false;
protected $primaryKey = 'IDPLANTILLA';


public static function listaPlantillas(){
	$result = array();
	$query = Plantilla::query()
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
	 return (new Plantilla())->hasManyThrough(
		new Modulos(), //modelo final
		new PlantillaModulos(), //modelo intermedio
		'IDPLANTILLA', // Foreign key on users table... . El tercer argumento es el nombre de la clave externa en el modelo intermedio
		'IDMODULO', // Foreign key on posts table...
		'IDPLANTILLA', // Local key on countries table...
		'IDMODULO' // Local key on users table...
	)->with('objacceso');
}

public static function getOpciones($datos){
	$result = array();
	//$datos = ["IDPLANTILLA"=>$idPlantilla, "ESTADO"=> 1];
	$query = Plantilla::query()
	->selectRaw("PLANTILLA.*")
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
			return $this->belongsToMany('App\Modelo\Modulos', "PLANTILLA","IDPLANTILLA","IDPLANTILLA");
}


public static function getTableColumns() {
return (new Plantilla)
					->getConnection()
					->getSchemaBuilder()
					->getColumnListing((new Plantilla)->getTable());
}

public static function getTableFilas() {
	$result = array();
	//$datos = ["IDPLANTILLA"=>$idPlantilla, "ESTADO"=> 1];
	/*$query = Plantilla::query()
	->selectRaw("PLANTILLA_WEB.*,PLANTILLA_WEB.IDPLANTILLA AS ACCION")
	->get()
	->toArray();*/
	$query = Plantilla::all()->toArray();
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


public static function getPlantillasActivo(){
	$result = array();
	$query = Plantilla::query()
	->selectRaw("IDPLANTILLA,NOMBRE")
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

public static function listaPlantillasModulos($idPlantilla){
	$result = array();
	$datos = ["IDPLANTILLA"=>$idPlantilla, "ESTADO"=> 1];
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

public static function getPlantillaByRuta($ruta){
	$result = array();
	$where = ["RUTA"=>$ruta];
	$query = Plantilla::query()
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

public static function nuevaPlantilla($plantilla){
	$plantilla->save();
	return $plantilla;
}

}
