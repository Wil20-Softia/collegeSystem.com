<?php

	require_once "../../config/EntidadBase.php";

	$datos = array();

	$entidad = new EntidadBase("momento_estudiante");
	$conexion = $entidad->db();

	$id_estudiante = $_POST["id_estudiante"];
	
	$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $id_estudiante");
	$te = $re->fetch_assoc();
	$type_student = $te["tipo"]; //NOMBRE DEL TIPO DE ESTUDIANTE

	if($type_student == "momento_estudiante"){
		$res = $conexion->query("
				SELECT 
					grado.id AS grado, 
					seccion.id AS seccion 
				FROM momento_estudiante 
				INNER JOIN seccion_especifica ON momento_estudiante.seccion_especifica = seccion_especifica.id 
				INNER JOIN grado ON seccion_especifica.grado = grado.id 
				INNER JOIN seccion ON seccion_especifica.seccion = seccion.id 
				WHERE 
					momento_estudiante.tipo_estudiante = $id_estudiante");
		$datos_grado_estudiante = $res->fetch_assoc();
		$grado_estudiante = $datos_grado_estudiante["grado"];
		$seccion_estudiante = $datos_grado_estudiante["seccion"];

		if($grado_estudiante == 11){
			$grados_array = array(
				array(
					"id" => 0,
					"nombre" => "Año"
				),
				array(
					"id" => 11,
					"nombre" => "5to Año"
				)
			);
		}else{
			$resultado = $conexion->query("SELECT id, nombre FROM grado WHERE id >= $grado_estudiante AND id <= ($grado_estudiante + 1)");
			while($grados_array[] = $resultado->fetch_assoc());
			array_pop($grados_array);
		}
	}else if($type_student == "estudiante_deudor_antiguo"){
		$extraer_datos = $conexion->query("SELECT estudiante.cedula FROM estudiante_deudor_antiguo INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id WHERE estudiante_deudor_antiguo.tipo_estudiante = $id_estudiante");
		$cedula_estudiante = $extraer_datos->fetch_assoc();
		$cedula_estudiante = $cedula_estudiante["cedula"];

		$me_anteriores = $conexion->query("SELECT momento_estudiante.id FROM estudiante INNER JOIN momento_estudiante ON momento_estudiante.estudiante = estudiante.id WHERE estudiante.cedula = '$cedula_estudiante' ORDER BY momento_estudiante.id DESC LIMIT 0,1");
		if($me_anteriores->num_rows == 1){
			$id_momento_estudiante = $me_anteriores->fetch_assoc();
			$id_momento_estudiante = $id_momento_estudiante["id"];

			$res = $conexion->query("
				SELECT 
					grado.id AS grado, 
					seccion.id AS seccion 
				FROM momento_estudiante 
				INNER JOIN seccion_especifica ON momento_estudiante.seccion_especifica = seccion_especifica.id 
				INNER JOIN grado ON seccion_especifica.grado = grado.id 
				INNER JOIN seccion ON seccion_especifica.seccion = seccion.id 
				WHERE 
					momento_estudiante.id = $id_estudiante");
			$datos_grado_estudiante = $res->fetch_assoc();
			$grado_estudiante = $datos_grado_estudiante["grado"];
			$seccion_estudiante = $datos_grado_estudiante["seccion"];

			if($grado_estudiante == 11){
				$grados_array = array(
					array(
						"id" => 0,
						"nombre" => "Año"
					),
					array(
						"id" => 11,
						"nombre" => "5to Año"
					)
				);
			}else{
				$resultado = $conexion->query("SELECT id, nombre FROM grado WHERE id >= $grado_estudiante AND id <= ($grado_estudiante + 1)");
				while($grados_array[] = $resultado->fetch_assoc());
				array_pop($grados_array);
			}
		}else{
			$resultado = $conexion->query("SELECT id, nombre FROM grado WHERE 1 ORDER BY id ASC");
			while($grados_array[] = $resultado->fetch_assoc());
			array_pop($grados_array);
		}
	}

	$grados = json_encode($grados_array, JSON_UNESCAPED_UNICODE);

	echo $grados;
?>