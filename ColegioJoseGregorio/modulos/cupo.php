<?php
	session_start();
    if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		require_once "../config/ModeloBase.php";
		require_once "../config/functions.php";

		$mb = new ModeloBase("tipo_inscripcion");

		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");

		$id_usuario = $_SESSION["id"];
		$id_periodo_actual = $mb->obtener_Id_periodoActual();

		$conexion = $mb->db();

		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			$condicion = strtolower($_GET["criterio"]);
			if($condicion == "listado"){
				$cupos = $conexion->query("SELECT tipo_inscripcion.monto, tipo_inscripcion.fecha_registrado, CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS usuario FROM tipo_inscripcion INNER JOIN usuario ON usuario.id = tipo_inscripcion.usuario WHERE tipo_inscripcion.periodo_escolar = $id_periodo_actual AND tipo_inscripcion.tipo = 'Cupo'");
				if($cupos->num_rows > 0){
					while($da = $cupos->fetch_assoc()){
						$datos[] = array(
							"monto" => formatearNumerico($da["monto"]),
							"fecha_registrado" => $da["fecha_registrado"],
							"usuario" => $da["usuario"]
						);
					}
					$mensaje_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $mensaje_json;
				}else{
					$mensaje = "No existen registros!";
					echo $mensaje;
				}
			}else if($condicion == "registrar"){
				if(!$id_periodo_actual){
					echo "El periodo actual no existe todavía. Verificar el modulo Periodo Actual.";
				}else{
					$result = $conexion->query("SELECT id FROM tipo_inscripcion WHERE periodo_escolar = $id_periodo_actual AND tipo = 'Cupo'");
					if($result->num_rows == 0){
						$conexion->query("INSERT INTO tipo_inscripcion (tipo, periodo_escolar, monto, fecha_registrado, usuario) VALUES ('Cupo', $id_periodo_actual, ".$_GET["monto_cupo"].", NOW(), $id_usuario)");

						$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DEL CUPO DEL PERIODO ESCOLAR ACTUAL',NOW(),NOW())");
												
						$datos = array('mensaje' => "¡CUPO REGISTADO CON EXITO!", 'advertencia' => 1);
						$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $datos_json;
					}else{
						echo "Ya se ha Registrado el Cupo en el Periodo Escolar!";
					}
				}
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