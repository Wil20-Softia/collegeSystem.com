<?php

	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){

		date_default_timezone_set ("America/Caracas");
		require_once "../config/EntidadBase.php";
		require_once "../config/functions.php";

		$en = new EntidadBase("inscripcion");

		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y"); //VARIABLE CON AÑO ACTUAL.
		$ma = (int)date("m"); //VARIABLE CON MES ACTUAL.

		$id_usuario = $_SESSION["id"];

		$en->establecerYearActual();
		$en->establecerMesActual();
		//ARRAY QUE GUARDA LOS AÑOS DEL PERIODO ESCOLAR ACTUAL.
		$periodo_actual = $en->obtener_periodoActual();

		$conexion = $en->db();

		//SE VERIFICA LA VARIABLE CRITERIO PARA VER SI EXISTE O NO ESTE VACIA
		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			//SE TOMA LA VARIABLE CRITERIO Y SU VALOR SE PASA A MINUSCULAS
			$condicion = strtolower($_GET["criterio"]);

			//SI EL CRITERIO ES DE LISTADO
			if($condicion == "listado"){

				//CONSULTA QUE OBTIENE LAS INSCRIPCIONES DEL PERIODO ACTUAL CON:
				//EL MONTO, LA FECHA EN QUE SE REGISTRO Y EL USUARIO QUE LO HIZO.
				$resultado = $conexion->query("
					SELECT 
						tipo_inscripcion.monto, 
						tipo_inscripcion.fecha_registrado, 
						CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS usuario 
					FROM tipo_inscripcion 
					INNER JOIN usuario ON usuario.id = tipo_inscripcion.usuario 
					INNER JOIN periodo_escolar ON tipo_inscripcion.periodo_escolar = periodo_escolar.id
					WHERE
						tipo_inscripcion.tipo LIKE 'Insc' AND 
						periodo_escolar.year_inicia = " . $periodo_actual["yearDesde"] . " AND
						periodo_escolar.year_termina = ".$periodo_actual["yearHasta"]);

				//SI OBITIENE MAS DE UN MONTO ENVIA LA LISTA AL CLIENTE.
				if($resultado->num_rows > 0){
					while($da = $resultado->fetch_assoc()){
						$datos[] = array(
							"monto" => formatearNumerico($da["monto"]),
							"fecha_registrado" => $da["fecha_registrado"],
							"usuario" => $da["usuario"]
						);
					}
					$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_js;
				//SI NO OBTIENE MENSUALIDADES ENTONCES ENVIA UN MENSAJE AL CLIENTE.
				}else{
					echo "No existen registros";
				}

			//SI EL CRITERIO ES DE REGISTRAR UNA INSCRIPCION
			}else if($condicion == "registrar"){

				$resultado = $conexion->query("
					SELECT 
						id 
					FROM periodo_escolar 
					WHERE 
						year_inicia = " . $periodo_actual["yearDesde"] . " AND 
						year_termina = " . $periodo_actual["yearHasta"]);

				if($resultado->num_rows > 0){
					$ipe = $resultado->fetch_assoc();

					//VARIABLE QUE GUARDA EL ID DEL PERIODO ACTUAL.
					$id_periodo_escolar = $ipe["id"];

					$resultado = $conexion->query("
						INSERT INTO tipo_inscripcion 
							(tipo, periodo_escolar, monto, fecha_registrado, usuario) 
						VALUES 
							('Insc', $id_periodo_escolar, ".$_GET["monto_inscripcion"].", NOW(), $id_usuario)");

					$id_inscripcion = $conexion->insert_id;

					//SI EL USUARIO ELIGIO QUE SI SE APLICA PARA TODOS
					//LOS ESTUDIANTES ANTERIORES.
					if($_GET["opcion_ins"] == 1){
						$resultado = $conexion->query("
							SELECT 
								id 
							FROM momento_estudiante 
							WHERE 
								periodo_escolar = $id_periodo_escolar");

						$cantidad_estudiantes = $resultado->num_rows;

						if($cantidad_estudiantes > 0){
							while($r = $resultado->fetch_assoc()){
								$id_estudiante = $r["id"];

								$conexion->query("
									INSERT INTO tipo_deuda_inscripcion 
										(tipo_inscripcion, momento_estudiante, estado_pago, diferencia) 
									VALUES 
										($id_inscripcion, $id_estudiante, 3, 0)");
							}

							$conexion->query("
								INSERT INTO historial_tareas
									(usuario, descripcion, fecha, hora) 
								VALUES 
									($id_usuario,'REGISTRO DE MONTO DE INSCRIPCIÓN',NOW(),NOW())");

							$datos = array('mensaje' => "¡MONTO DE INSCRIPCIÓN REGISTRADO. DATOS ACTUALIZADOS!", 'advertencia' => 1);
							$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
							echo $datos_json;
						}else{
							$conexion->query("
								INSERT INTO historial_tareas
									(usuario, descripcion, fecha, hora) 
								VALUES 
									($id_usuario,'REGISTRO DE MONTO DE INSCRIPCIÓN',NOW(),NOW())");

							$datos = array('mensaje' => "¡MONTO DE INSCRIPCIÓN REGISTRADO!", 'advertencia' => 1);
							$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
							echo $datos_json;
						}
					}else{
						$conexion->query("
							INSERT INTO historial_tareas
								(usuario, descripcion, fecha, hora) 
							VALUES 
								($id_usuario,'REGISTRO DE MONTO DE INSCRIPCIÓN',NOW(),NOW())");

						$datos = array('mensaje' => "¡MONTO DE INSCRIPCIÓN REGISTRADO!", 'advertencia' => 1);
						$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $datos_json;
					}
				}else{
					echo "El periodo actual no existe todavía. Verificar el modulo Periodo Actual.";
				}
			}else{
				echo "Esta opción no es valida";
			}
		}else{
			echo "Elija que desea hacer con Inscripción";
		}

	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>