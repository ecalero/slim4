<?php
declare(strict_types=1);

namespace App\Domain\Componente;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use Illuminate\Database\Connection;
use App\Domain\Modulos\Modulos;
use App\Domain\Componente\Componente;
class Componente extends Model implements JsonSerializable
{
public $table = 'COMPONENTE';
public $timestamps = false;
protected $primaryKey = 'IDCOMPONENTE';


public static function listaComponentes(){
	$result = array();
	$query = Componente::query()
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
	 return (new Componente())->hasManyThrough(
		new Modulos(), //modelo final
		new ComponenteModulos(), //modelo intermedio
		'IDCOMPONENTE', // Foreign key on users table... . El tercer argumento es el nombre de la clave externa en el modelo intermedio
		'IDMODULO', // Foreign key on posts table...
		'IDCOMPONENTE', // Local key on countries table...
		'IDMODULO' // Local key on users table...
	)->with('objacceso');
}

public static function getOpciones($datos){
	$result = array();
	//$datos = ["IDCOMPONENTE"=>$idComponente, "ESTADO"=> 1];
	$query = Componente::query()
	->selectRaw("COMPONENTE.*")
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
			return $this->belongsToMany('App\Modelo\Modulos', "COMPONENTE","IDCOMPONENTE","IDCOMPONENTE");
}


public static function getTableColumns() {
return (new Componente)
					->getConnection()
					->getSchemaBuilder()
					->getColumnListing((new Componente)->getTable());
}

public static function getTableFilas() {
	$result = array();
	//$datos = ["IDCOMPONENTE"=>$idComponente, "ESTADO"=> 1];
	$query = Componente::query()
	->selectRaw("
	IDCOMPONENTE,
	NOMBRE,
	TITULO,
	RESUMEN,
	CONTENIDO,
	IDCOMPONENTEPADRE,
	FECHA_CREACION,
	IDUSUARIOCREACION,
	FECHA_ACTUALIZACION,
	IDUSUARIOACTUALIZACION,
	ESTADO	,
(select USUARIO.USUARIO from USUARIO where USUARIO.IDUSUARIO=COMPONENTE.IDUSUARIOCREACION) AS USUARIO_CREACION,
FECHA_ACTUALIZACION   ,
(select USUARIO.USUARIO from USUARIO where USUARIO.IDUSUARIO=COMPONENTE.IDUSUARIOACTUALIZACION) AS USUARIO_ACTUALIZACION,
(select TAB_TABLAS.NOMBRE from TAB_TABLAS where TAB_TABLAS.IDTABLA=COMPONENTE.ESTADO) AS ESTADONOMBRE
	")
	->get()
	->toArray();
	//$query = Componente::all()->toArray();
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


public static function getComponentesActivo(){
	$result = array();
	$query = Componente::query()
	->selectRaw("IDCOMPONENTE,NOMBRE")
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

public static function listaComponentesModulos($idComponente){
	$result = array();
	$datos = ["IDCOMPONENTE"=>$idComponente, "ESTADO"=> 1];
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

public static function getComponenteByRuta($ruta){
	$result = array();
	$where = ["RUTA"=>$ruta];
	$query = Componente::query()
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

public static function nuevoComponente($componente){
	$componente->save();
	return $componente;
}

}
