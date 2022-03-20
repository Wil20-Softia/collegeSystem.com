<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas"); 
		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");

		require_once "../config/EntidadBase.php";
		$entidad = new EntidadBase("historial_tareas");
		$conexion = $entidad->db();

		if(isset($_POST["usuario"]) && !empty($_POST["usuario"])){
			$id_usuario = $_POST["usuario"];

			$resultado = $conexion->query("SELECT * FROM historial_tareas WHERE usuario = $id_usuario AND fecha = '$fecha_actual' ORDER BY hora DESC");

			if($resultado->num_rows > 0){
				while($row = $resultado->fetch_assoc()){
					$actividades[] = array(
						'nombre' =>	$row["descripcion"],
						'fecha' =>	date('h:i:s a', strtotime($row["hora"]))
					);
				}

				$datos_js = json_encode($actividades, JSON_UNESCAPED_UNICODE);
				echo $datos_js;
			}else{
				echo "¡No se han encontrado actividades hoy!";
			}
		}else{
			echo "No se ha conseguido la variable POST";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>