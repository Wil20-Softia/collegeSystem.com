<?php
	session_start();
    if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		require_once "../config/ModeloBase.php";
		require_once "../config/functions.php";

		$mb = new ModeloBase("mora");

		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");

		$id_usuario = $_SESSION["id"];

		$conexion = $mb->db();

		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			$condicion = strtolower($_GET["criterio"]);

			if($condicion == "listado"){
				$consulta = $conexion->query("SELECT * FROM mora WHERE 1 ORDER BY id DESC LIMIT 5");
				
				if($consulta->num_rows == 0){
					$mensaje = "No existen registros!";
					echo $mensaje;
				}else{
					while($row = $consulta->fetch_assoc()){
						$porcentajes[] = array(
							"id" => $row["id"],
							"porcentaje" => formatearNumerico($row["porcentaje"])
						);
					}
					$mensaje_json = json_encode($porcentajes, JSON_UNESCAPED_UNICODE);
					echo $mensaje_json;
				}
			}else if($condicion == "registrar"){
				$conexion->query("INSERT INTO mora (porcentaje) VALUES (".$_GET["porcentaje_mora"].")");

				$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE PORCENTAJE DE MORA',NOW(),NOW())");
											
				$datos = array('mensaje' => "¡PORCENTAJE REGISTADO CON EXITO!", 'advertencia' => 1);
				$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_json;
			}else{
				echo "Esta opción no es valida";
			}
		}else{
			echo "Elija que desea hacer!";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>