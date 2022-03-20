<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		require_once "../../config/EntidadBase.php";
		require_once "../../config/functions.php";
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$da = (int)date("j"); //DIA ACTUAL SIN CEROS INICIALES, PARA UTILIZAR EN OPERACIONES ARITMETICAS CON PARSEADO A ENTERO.

		$datos = array();

		$entidad = new EntidadBase("estudiante");
		$conexion = $entidad->db();

		$cedula = $_GET["cedula"]; //CEDULA DEL ESTUDIANTE A ELIMINAR.
		$tipo_estudiante = $_GET["tipo_estudiante"];

		$id_periodo_actual = $entidad->obtener_Id_periodoActual();

		$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $tipo_estudiante");
		$te = $re->fetch_assoc();
		$type_student = $te["tipo"];

		$resultado = $conexion->query("
			SELECT 
				momento_estudiante.id,
				representante.cedula AS cedula_representante
			FROM momento_estudiante 
			INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
			INNER JOIN representante ON estudiante.representante = representante.id
			WHERE 
				estudiante.cedula = '$cedula' AND 
				momento_estudiante.periodo_escolar = $id_periodo_actual AND 
				estudiante.habilitado = 1");

		if($type_student == "estudiante_deudor_antiguo"){
			$resultado1 = $conexion->query("
				SELECT 
					representante.cedula AS cedula_representante
				FROM $type_student 
				INNER JOIN estudiante ON $type_student.estudiante = estudiante.id
				INNER JOIN representante ON estudiante.representante = representante.id
				WHERE 
					estudiante.cedula = '$cedula' AND 
					estudiante.habilitado = 2
				LIMIT 1");
		}

		//SI EL ESTUDIANTE SE ENCUENTRA REGISTRADO EN EL PERIODO ESCOLAR ACTUAL
		//Y ES UN ESTUDIANTE NORMAL
		if($resultado->num_rows == 1){

			$r = $resultado->fetch_assoc();
			$entidad->establecer_momento_estudiante($r["id"]);
			$cedula_representante = $r["cedula_representante"];

			//SI HA REALIZADO PAGOS DE INSCRIPCION ENTONCES:
			if($entidad->facturaInscripcion_periodoActual($cedula)){
				
				//SE VERIFICA SI EL MES ACTUAL ES AGOSTO O SEPTIEMBRE.
				if($ma == 8 || $ma == 9){
					//SE DESHABILITA AL ESTUDIANTE POR TENER PAGOS REALIZADOS
					//Y ESTAR EN ESOS MESES DEL PERIODO ESCOLAR ACTUAL
					$entidad->deshabilitarEstudiante($cedula);
					$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE HA SIDO REMOVIDO DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
					echo $m;

				//SINO, SI ESTA EN LOS MESES ANTERIORES, ENTONCES:
				}else{
					$ma_sistema = mes_actual_ordernado($ma);
					if($entidad->deudaMeses()) {
						$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE DEBE CANCELAR LA DEUDA ACTUAL HASTA EL MES ACTUAL, PARA SER RETIRADO!", 'advertencia' => 2), JSON_UNESCAPED_UNICODE);
						echo $m;
					}else{
						$entidad->deshabilitarEstudiante($cedula);

						$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE HA SIDO REMOVIDO DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
						echo $m;
					}
				}
	
			//SI NO HA REALIZADO NINGUN PAGO
			}else{
				//SI TIENE ALGUAN FACTURACION DE MENSUALIDAD, INSCRIPCION O DE COMPRA DE PRODUCTOS
				//SE DESHABILITA PORQUE TIENE REGISTROS EN LA BASE DE DATOS
				if($entidad->facturasEstudiante($cedula, "normal", "momento_estudiante") || $entidad->facturasEstudiante($cedula, "inscripcion", "momento_estudiante") || $entidad->facturasClientes($cedula, "estudiante")){
					
					$entidad->deshabilitarEstudiante($cedula);

					$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE HA SIDO REMOVIDO DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
					echo $m;

				//SINO SE LE ENCONTRARON NINGUN TIPO DE FACTURACIONES, ENTONCES SE PROCEDE A ELIMINAR
				//AL ESTUDIANTE POR COMPLETO
				}else{
					$entidad->eliminarEstudiante($cedula, $tipo_estudiante, $cedula_representante);

					$m = json_encode(array('mensaje' => "¡SE HA ELIMINADO COMPLETAMENTE AL ESTUDIANTE DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
					echo $m;
				}
			}

		//SINO ESTA REGISTRADO EN EL PERIODO ESCOLAR ACTUAL
		//(QUIERE DECIR QUE ESTA EN LA LISTA DE LOS ESTUDIANTE EN ESPERA).
		//O ESTA EN LOS DEUDORES ANTIGUOS
		}else if($resultado->num_rows == 0){
			$deudor_antiguo = $conexion->query("SELECT estudiante.id FROM estudiante WHERE estudiante.cedula = '$cedula' AND estudiante.habilitado = 2");

			$en_espera = $conexion->query("SELECT estudiante.id FROM estudiante WHERE estudiante.cedula = '$cedula' AND estudiante.habilitado = 3");

			if($deudor_antiguo->num_rows == 1 && $en_espera->num_rows == 0){
				$r = $resultado1->fetch_assoc();
				$cedula_representante = $r["cedula_representante"];

				//MESES PAGOS DEL DEUDOR ANTIGUO
				$meses_pagos = $conexion->query("
					SELECT 
						deuda_antigua.id AS mes_pago 
					FROM estudiante 
					INNER JOIN estudiante_deudor_antiguo ON estudiante.id = estudiante_deudor_antiguo.estudiante 
					INNER JOIN deuda_antigua ON estudiante_deudor_antiguo.id = deuda_antigua.estudiante_deudor_antiguo 
					INNER JOIN tipo_deuda_mes ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id 
					WHERE 
						estudiante.cedula = '$cedula' AND 
						estudiante.habilitado = 2 AND 
						tipo_deuda_mes.estado_pago = 1");

				//MESES EN DEUDA DEL DEUDOR ANTIGUO
				$meses_deuda = $conexion->query("
					SELECT 
						deuda_antigua.id AS mes_deuda 
					FROM estudiante 
					INNER JOIN estudiante_deudor_antiguo ON estudiante.id = estudiante_deudor_antiguo.estudiante 
					INNER JOIN deuda_antigua ON estudiante_deudor_antiguo.id = deuda_antigua.estudiante_deudor_antiguo 
					INNER JOIN tipo_deuda_mes ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id 
					WHERE 
						estudiante.cedula = '$cedula' AND 
						estudiante.habilitado = 2 AND 
						(tipo_deuda_mes.estado_pago = 2 OR 
						tipo_deuda_mes.estado_pago = 3)");

				if($meses_pagos->num_rows >= 1 && $meses_deuda->num_rows >= 1){
					$m = json_encode(array('mensaje' => "¡DEBE CANCELAR TODOS LOS MESES PARA PODER RETIRARLO!", 'advertencia' => 2), JSON_UNESCAPED_UNICODE);
					echo $m;
				}else if($meses_pagos->num_rows == 0){
					$resultado1 = $conexion->query("
							SELECT 
								estudiante_deudor_antiguo.id 
							FROM estudiante_deudor_antiguo 
							INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id 
							WHERE 
								estudiante.cedula = '$cedula'");

					$resultado2 = $conexion->query("
							SELECT 
								momento_estudiante.id 
							FROM momento_estudiante 
							INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id 
							WHERE 
								estudiante.cedula = '$cedula'");

					if($resultado1->num_rows == 1 && $resultado2->num_rows == 0){
						$entidad->eliminarEstudiante($cedula,$tipo_estudiante,$cedula_representante);

						$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE HA SIDO REMOVIDO DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
						echo $m;
					}else{
						$entidad->deshabilitarEstudiante($cedula);

						$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE HA SIDO REMOVIDO DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
						echo $m;
					}
				}else if($meses_deuda->num_rows == 0){
					$entidad->deshabilitarEstudiante($cedula);

					$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE HA SIDO REMOVIDO DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
					echo $m;
				}
			}else if($en_espera->num_rows == 1 && $deudor_antiguo->num_rows == 0){
				$entidad->deshabilitarEstudiante($cedula);

				$m = json_encode(array('mensaje' => "¡EL ESTUDIANTE HA SIDO REMOVIDO DEL SISTEMA!", 'advertencia' => 1), JSON_UNESCAPED_UNICODE);
				echo $m;
			}
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>