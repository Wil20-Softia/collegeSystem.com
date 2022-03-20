<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("seccion");

	if(isset($_GET["grado"]) && !empty($_GET["grado"])){
		$secciones = $mb->ejecutarSql("SELECT seccion_especifica.id, seccion.nombre FROM seccion_especifica INNER JOIN seccion ON seccion_especifica.seccion = seccion.id WHERE seccion_especifica.grado = ". $_GET["grado"]." ORDER BY seccion.nombre ASC");

	  	if(!$secciones){
			$mensaje = "Sin Secciones";
			echo $mensaje;
		}else{
			$mensaje_json = json_encode($secciones, JSON_UNESCAPED_UNICODE);
			echo $mensaje_json;
		}
	}else{
		echo "Sin Secciones";
	}	
?>