<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../config/EntidadBase.php";
		require_once "../config/functions.php";

		$en = new EntidadBase("seccion_especifica");

		$conexion = $en->db();
		$id_usuario = $_SESSION["id"];

		//SE VERIFICA LA VARIABLE CRITERIO PARA VER SI EXISTE Y NO ESTE VACIA
		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			//SE TOMA LA VARIABLE CRITERIO Y SU VALOR SE PASA A MINUSCULAS
			$condicion = strtolower($_GET["criterio"]);

			//SI EL CRITERIO ES DE LISTADO
			if($condicion == "listado"){

				//CONSULTA QUE OBTIENE LAS SECCIONES ESPECIFICAS:
				$resultado = $conexion->query("
					SELECT 
						seccion_especifica.id, 
						grado.nombre AS grado, 
						seccion.nombre AS seccion, 
						seccion_especifica.fecha_registrado 
					FROM seccion_especifica 
					INNER JOIN grado ON seccion_especifica.grado = grado.id 
					INNER JOIN seccion ON seccion_especifica.seccion = seccion.id 
					WHERE 1 
					ORDER BY grado.id ASC, seccion.nombre ASC");

				
				if($resultado->num_rows > 0){
					while($datos[] = $resultado->fetch_assoc());
					array_pop($datos);
					$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_js;
				}else{
					echo "No existen registros";
				}

			//SI EL CRITERIO ES DE REGISTRAR UNA SECCION ESPECIFICA
			}else if($condicion == "registrar"){

				$seccion = $_GET["seccion"];
				$grado = $_GET["grado"];

				$resultado = $conexion->query("SELECT id FROM seccion_especifica WHERE grado = $grado AND seccion = $seccion");

				if($resultado->num_rows == 0){
					$resultado = $conexion->query("INSERT INTO seccion_especifica (grado, seccion, fecha_registrado) VALUES ($grado, $seccion, NOW())");

					if($resultado){
						$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE SECCIÓN',NOW(),NOW())");

						$datos = array('mensaje' => "¡SECCIÓN REGISTRADA CON EXITO!", 'advertencia' => 1);
						$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $datos_js;
					}else{
						echo "¡FALLO EN EL REGISTRO DE LA SECCIÓN!";
					}
				}else{
					echo "¡Esta sección ya se encuentra registrada!";
				}
			}else if($condicion == "eliminar"){
				$resultado = $conexion->query("DELETE FROM seccion_especifica WHERE id = " . $_GET["id_seccion"]);
				if($resultado){
					$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'ELIMINADO DE SECCIÓN',NOW(),NOW())");

					$datos = array('mensaje' => "La sección ha sido eliminada", 'advertencia' => 1);
					$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_js;
				}else{
					echo "Error al eliminar a la sección";
				}
			}else{
				echo "Esta opción no es valida";
			}
		}else{
			echo "Elija que desea hacer con mensualidad";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>