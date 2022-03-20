<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/ModeloBase.php";

		$mb = new ModeloBase("tipo_inscripcion");

		$id_periodo = $mb->obtener_Id_periodoActual();		

		$inscripciones = $mb->ejecutarSql("
					SELECT 
						id,
						tipo 
					FROM tipo_inscripcion 
					WHERE 
						periodo_escolar = $id_periodo 
					ORDER BY tipo ASC");
		if(!$inscripciones){
			$mensaje = "NO EXISTEN CUPOS, NI INSCRIPCIONES PARA ESTE AÑO ESCOLAR!";
			echo $mensaje;
		}else{
			$mensaje_json = json_encode($inscripciones, JSON_UNESCAPED_UNICODE);
			echo $mensaje_json;
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>