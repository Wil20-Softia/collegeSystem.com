<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../config/ModeloBase.php";
		$mb = new ModeloBase("estudiante");
		$b = 0;
		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			if($_GET["criterio"] == "busqueda"){
				$texto = $_GET["busqueda"];
				$sql = "
					SELECT 
						momento_estudiante.tipo_estudiante AS id_tipo_estudiante,
						momento_estudiante.periodo_escolar AS periodo_estudiante,
						estudiante.id AS id_estudiante,
						estudiante.cedula, 
						estudiante.primer_nombre AS nombre, 
						estudiante.primer_apellido AS apellido, 
						grado.nombre AS grado, 
						seccion.nombre AS seccion 
					FROM estudiante 
					INNER JOIN momento_estudiante ON momento_estudiante.estudiante = estudiante.id 
					INNER JOIN seccion_especifica ON momento_estudiante.seccion_especifica = seccion_especifica.id 
					INNER JOIN grado ON seccion_especifica.grado = grado.id 
					INNER JOIN seccion ON seccion_especifica.seccion = seccion.id 
					WHERE 
						estudiante.habilitado = 3 AND 
						(estudiante.cedula LIKE '$texto' OR CONCAT(estudiante.primer_nombre,' ',estudiante.primer_apellido) LIKE '%$texto%')";
			}else if($_GET["criterio"] == "listado_completo"){
				$sql = "
					SELECT 
						momento_estudiante.tipo_estudiante AS id_tipo_estudiante,
						momento_estudiante.periodo_escolar AS periodo_estudiante,
						estudiante.id AS id_estudiante,
						estudiante.cedula, 
						estudiante.primer_nombre AS nombre, 
						estudiante.primer_apellido AS apellido, 
						grado.nombre AS grado, 
						seccion.nombre AS seccion 
					FROM estudiante 
					INNER JOIN momento_estudiante ON momento_estudiante.estudiante = estudiante.id 
					INNER JOIN seccion_especifica ON momento_estudiante.seccion_especifica = seccion_especifica.id 
					INNER JOIN grado ON seccion_especifica.grado = grado.id 
					INNER JOIN seccion ON seccion_especifica.seccion = seccion.id 
					WHERE 
						estudiante.habilitado = 3";
			}else{
				echo "¡NO EXISTE EL CRITERIO ESCOGIDO!";
				$b = 1;
			}

			if($b == 0){
				$estudiantes = $mb->ejecutarSql($sql);
				
				if(!$estudiantes){
					echo "¡REGISTRO NO EXISTENTE!";
				}else{
					$mensaje_json = json_encode($estudiantes, JSON_UNESCAPED_UNICODE);
					echo $mensaje_json;
				}
			}
		}else{
			echo "¡NO SE HA DECLARADO LA CONDICIÓN A REALIZAR POR EL USUARIO!";
		}	
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>