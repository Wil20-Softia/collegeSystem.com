<?php

	session_start();
    if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		require_once "../config/EntidadBase.php";
		require_once "../config/functions.php";

		$en = new EntidadBase("mensualidad");

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

				//CONSULTA QUE OBTIENE LAS MENSUALIDADES DEL PERIODO ACTUAL CON:
				//EL MONTO, LA FECHA EN QUE SE REGISTRO Y EL USUARIO QUE LO HIZO.
				$resultado = $conexion->query("
					SELECT 
						mensualidad.monto, 
						mensualidad.fecha_registrado, 
						CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS usuario 
					FROM mensualidad 
					INNER JOIN usuario ON  mensualidad.usuario = usuario.id
					WHERE 
						(YEAR(mensualidad.fecha_registrado) = " . $periodo_actual["yearDesde"] . " AND MONTH(mensualidad.fecha_registrado) > 7) 
						OR (YEAR(mensualidad.fecha_registrado) = ".$periodo_actual["yearHasta"]." AND MONTH(mensualidad.fecha_registrado) <= 7)");

				//SI OBITIENE MAS DE UNA MENSUALIDAD ENVIA LA LISTA AL CLIENTE.
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
					echo "No existen registros!";
				}

			//SI EL CRITERIO ES DE REGISTRAR UNA MENSUALIDAD
			}else if($condicion == "registrar"){

				//SE OBTIENE EL MES ACTUAL A COMO ESTA REGISTRADO EN EL SISTEMA
				$mes_actual = $en->mes_actual_ordernado();

				//CONSULTA QUE VERIFICA CUANTAS MENSUALIDADES HAY REGISTRADAS EN EL
				//MES ACTUAL.
				$resultado = $conexion->query("
						SELECT 
							id 
						FROM mensualidad 
						WHERE 
							YEAR(fecha_registrado) = $aa AND 
							MONTH(fecha_registrado) = $ma");

				//SI ENCUENTRA MENOS DE 2 MENSUALIDADES REGISTRADAS EN EL MES ACTUAL
				if($resultado->num_rows < 2){

					//CONSULTA QUE TOMA EL MONTO DE LA ULTIMA MENSUALIDAD REGISTRADA
					$resultado = $conexion->query("
						SELECT 
							monto 
						FROM mensualidad 
						WHERE 1 
						ORDER BY id DESC 
						LIMIT 0,1");

					//SI ENCUENTRA MENSUALIDADES REGISTRADAS DE ULTIMO
					if($resultado->num_rows > 0){
						$datos_mensualidad = $resultado->fetch_assoc();

						//VARIABLE QUE GUARAD EL MONTO DE LA ULTIMA MENSUALIDAD
						$monto_mensualidad_ant = $datos_mensualidad["monto"];
						
					//SINO ENCUENTRA MENSUALIDADES REGISTRADAS
					}else{
						$monto_mensualidad_ant = 0;
					}

					//SE REALIZA EL REGISTRO DE LA MENSUALIDAD ACTUAL
					$res = $conexion->query("
						INSERT INTO mensualidad 
							(monto, fecha_registrado, usuario) 
						VALUES 
							(".$_GET["monto_mensualidad"].",NOW(),$id_usuario)");

					//VARIABLE QUE TOMA EL ID DE LA MENSUALIDAD ACTUAL
					$id_mensualidad = $conexion->insert_id;

					//CONSULTA QUE TOMA EL ID DEL PERIODO ACTUAL
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

						$rp = $conexion->query("
							SELECT 
								id 
							FROM meses_periodo 
							WHERE 
								periodo_escolar = $id_periodo_escolar AND 
								mensualidad = 1");

						if($rp->num_rows >= 1 && $rp->num_rows <= 12){
							$desde = 1;
						}else if($rp->num_rows == 0){
							//SE REALIZA UNA CONDICION SI EL REGISTRO DE LA MENSUALIDAD
							//SI ES JULIO O AGOSTO Y YA ESTA EL NUEVO PERIODO ESCOLAR REGISTRADO
							if($mes_actual == 11 || $mes_actual == 12){
								$desde = $mes_actual; //MES ACTUAL
								$id_periodo_escolar -= 1; //PERIODO ESCOLAR ANTERIOR
							//SI SE REGISTRA EN LOS MESES QUE NO SON LOS ANTERIORES.
							}else{
								//LA VARIABLE GUARDA QUE COMIENZE DESDE EL MES ACTUAL.
								$desde = $mes_actual;
							}
						}

						//CONSULTA QUE REALIZA LA MODIFICACIÓN DE LA MENSUALIDAD
						//DE LOS MESES DEL PERIODO ACTUAL, COMENZANDO DESDE EL MES
						//QUE SE DEDUJO EN LA CONDICION ANTERIOR, HASTA EL ULTIMO MES.
						$resultado = $conexion->query("
							UPDATE meses_periodo 
							SET 
								mensualidad=$id_mensualidad 
							WHERE 
								periodo_escolar=$id_periodo_escolar AND 
								(mes >= $desde AND mes <= 12)");
							
						//VARIABLE QUE GUARDA LA DIFERENCIA QUE EXISTE
						//ENTRE LAS DOS MENSUALIDADES.
						$diferencia_mensualidad = $_GET["monto_mensualidad"] - $monto_mensualidad_ant; 

						//CONDICION QUE NO PERMITE NUMEROS NEGATIVOS
						//EN EL SISTEMA.
						if($diferencia_mensualidad <= 0){
							$diferencia_mensualidad = 0;
							$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE MENSUALIDAD',NOW(),NOW())");

							$datos = array('mensaje' => "¡MENSUALIDAD REGISTRADA!", 'advertencia' => 1);
							$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
							echo $datos_json;
						//SINO FUE NEGATIVA LA OPERACION ENTONCES SE PROCEDE
						//A REALIZAR LA OPERACIONES DE ACTUALIZACION DE DATOS.
						}else{
							$conexion->query("
								UPDATE tipo_deuda_mes
								INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
								INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id
								INNER JOIN tipo_estudiante ON momento_estudiante.tipo_estudiante = tipo_estudiante.id
								INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
								SET 
									tipo_deuda_mes.diferencia = ".$_GET["monto_mensualidad"]." - tipo_deuda_mes.diferencia
								WHERE
									tipo_estudiante.tipo LIKE 'momento_estudiante' AND
									tipo_deuda_mes.estado_pago = 2 AND 
									meses_periodo.periodo_escolar = $id_periodo_escolar AND 
									(meses_periodo.mes >= $desde AND meses_periodo.mes <= 12)");

							$conexion->query("
								UPDATE tipo_deuda_mes
								INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
								INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id
								INNER JOIN tipo_estudiante ON momento_estudiante.tipo_estudiante = tipo_estudiante.id
								INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
								SET 
									tipo_deuda_mes.diferencia = tipo_deuda_mes.diferencia + $diferencia_mensualidad, 
									tipo_deuda_mes.estado_pago = 2 
								WHERE
									tipo_estudiante.tipo LIKE 'momento_estudiante' AND
									tipo_deuda_mes.estado_pago = 1 AND 
									meses_periodo.periodo_escolar = $id_periodo_escolar AND 
									(meses_periodo.mes >= $desde AND meses_periodo.mes <= 12)");

							$conexion->query("
								UPDATE tipo_deuda_mes
								INNER JOIN deuda_antigua ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id
								INNER JOIN estudiante_deudor_antiguo ON deuda_antigua.estudiante_deudor_antiguo = estudiante_deudor_antiguo.id
								INNER JOIN tipo_estudiante ON estudiante_deudor_antiguo.tipo_estudiante = tipo_estudiante.id
								SET
									tipo_deuda_mes.diferencia = ".$_GET["monto_mensualidad"]." - tipo_deuda_mes.diferencia
								WHERE 
									tipo_estudiante.tipo LIKE 'estudiante_deudor_antiguo' AND
									tipo_deuda_mes.estado_pago = 2
							");

							$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE MENSUALIDAD',NOW(),NOW())");
											
							$datos = array('mensaje' => "¡MENSUALIDAD REGISTRADA. DATOS ACTUALIZADOS!", 'advertencia' => 1);
							$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
							echo $datos_json;
						}
					}else{
						echo "El periodo actual no existe todavía. Verificar el modulo Periodo Actual.";
					}
				}else{
					echo "No se pueden Registrar más Mensualidades en el Mes!";
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