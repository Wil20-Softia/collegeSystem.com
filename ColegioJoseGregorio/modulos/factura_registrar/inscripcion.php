<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas"); 
		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$mensaje = "";
		$advertencia = 0;
		$registros = 0;
		require_once "../../config/EntidadBase.php";
		require_once "../../config/functions.php";
		
		//VARIABLE QUE GUARDA EL ID DEL USUARIO QUE REGISTRA
		$id_usuario = $_SESSION["id"];

		$entidad = new EntidadBase("tipo_factura");
		$conexion = $entidad->db();

		$id_periodo_actual = $entidad->obtener_Id_periodoActual();

		//SE RECIBE EL OBJETO JSON DEL CLIENTE
		$superdata = $_POST["superdata"];
		$array_superdata = json_decode($superdata, true, 512, JSON_BIGINT_AS_STRING);
		$datos_generales = $array_superdata[0];

		$id_estudiante = $datos_generales["id_estudiante"];
		$monto_total = $datos_generales["total_pagado"];

		$montos_pagar = $array_superdata[1];
		$tam_mp = count($montos_pagar);

		$tipos_pagos = $array_superdata[2];
		$tam_tp = count($tipos_pagos);

		$inscripcion = $datos_generales["inscribir"];

		//SI SE INSCRIBIRA EL ESTUDIANTE
		if($inscripcion == 1){
			$r = $conexion->query("
				SELECT 
					SUM(monto) AS total_completo 
				FROM tipo_inscripcion 
				WHERE
					(tipo = 'Insc' OR tipo = 'Cupo') AND 
					periodo_escolar = $id_periodo_actual");
			$monto_total_estudiante = $r->fetch_assoc();
			$monto_total_estudiante = $monto_total_estudiante["total_completo"];

			//SE VERIFICA EL MONTO PRIMERO
			if($monto_total > $monto_total_estudiante){
				echo "¡EL MONTO A PAGAR HA EXCEDIDO EL LIMITE DE PAGO! NO SE HA REALIZADO NINGÚNA INSCRIPCIÓN";
			}else{
				$bandera = 0;
				$seccion = $datos_generales["seccion"];

				//TOMAR EL TIPO DE ESTUDIANTE: PARA REALIZAR CONSULTA DEPENDIENDO DEL TIPO
				//DE ESTUDIANTE QUE SEA Y ASI EXTRAER LOS DATOS.
				$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $id_estudiante");
				$te = $re->fetch_assoc();
				$type_student = $te["tipo"]; //NOMBRE DEL TIPO DE ESTUDIANTE

				//SI ES UN MOMENTO ESTUDIANTE
				if($type_student == "momento_estudiante"){
					$r = $conexion->query("
						SELECT 
							momento_estudiante.periodo_escolar 
						FROM momento_estudiante 
						INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id 
						WHERE 
							momento_estudiante.tipo_estudiante = $id_estudiante AND
							estudiante.habilitado = 3");
					//SI ES UN ESTUDIANTE EN ESPERA Y EL PERIODO ESCOLAR ES MENOR AL ACTUAL
					if($r->num_rows == 1){
						$pe = $r->fetch_assoc();
						if($id_periodo_actual == $pe["periodo_escolar"]){
							echo "¡EL ESTUDIANTE YA ESTA INSCRITO EN EL PERIODO ESCOLAR ACTUAL!";
						}else if($id_periodo_actual > $pe["periodo_escolar"]){
							$bandera = 1;
						}
					}else if($r->num_rows <= 0){
						echo "¡EL ESTUDIANTE NO ESTA EN ESPERA PARA LA INSCRIPCIÓN!";
					}
				}else if($type_student == "estudiante_deudor_antiguo"){
					$resultado_cedula = $conexion->query("SELECT estudiante.cedula FROM estudiante_deudor_antiguo INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id WHERE estudiante_deudor_antiguo.tipo_estudiante = $id_estudiante");
					$ced_est = $resultado_cedula->fetch_assoc();
					$cedula_estudiante = $ced_est["cedula"];

					$resultado_me = $conexion->query("SELECT momento_estudiante.id, momento_estudiante.periodo_escolar FROM estudiante INNER JOIN momento_estudiante ON momento_estudiante.estudiante = estudiante.id WHERE estudiante.cedula = '$cedula_estudiante' ORDER BY momento_estudiante.id DESC LIMIT 0,1");
					if($resultado_me->num_rows == 1){
						$registra_cupo = 4;
						$pe = $resultado_me->fetch_assoc();
						if($id_periodo_actual == $pe["periodo_escolar"]){
							echo "¡EL ESTUDIANTE YA ESTA INSCRITO EN EL PERIODO ESCOLAR ACTUAL!";
						}else if($id_periodo_actual > $pe["periodo_escolar"]){
							$bandera = 1;
						}
					}else{
						$registra_cupo = 3;
						$bandera = 1;
					}
				}

				//SI BANDERA ES IGUAL A 1
					//CONTINUAR CON EL REGISTRO DEL NUEVO ESTUDIANTE Y EL PAGO DE LOS MONTOS
					//DE INSCRIPCION DE PERIODO ESCOLAR ACTUAL
				
				//SE VERIFICA SI EL ESTUDIANTE YA ESTA INSCRITO EN EL PERIODO ESCOLAR
				if($bandera == 1){
					$r = $conexion->query("SELECT estudiante FROM $type_student WHERE tipo_estudiante = $id_estudiante");
					$identificador_estudiante = $r->fetch_assoc();
					$identificador_estudiante = $identificador_estudiante["estudiante"];

					$r = $conexion->query("SELECT id FROM seccion_especifica WHERE id = $seccion");

					//SE VERIFICA SI LA SECCION ESCOGIDA PARA REGISTRAR AL ESTUDIANTE EXISTE
					if($r->num_rows == 1){

						//SE TOMAN LAS INSCRIPCIONES Y EL CUPO DEL PERIODO ESCOLAR
						$resultado_cupo = $conexion->query("SELECT id FROM tipo_inscripcion WHERE periodo_escolar = $id_periodo_actual AND tipo = 'Cupo'");

						$resultado_insc = $conexion->query("SELECT id FROM tipo_inscripcion WHERE periodo_escolar = $id_periodo_actual AND tipo = 'Insc'");

						//SE VERIFICA SI EL PERIODO ESCOLAR TIENE INSCRIPCIONES Y CUPO
						if($resultado_cupo->num_rows == 1 && $resultado_insc->num_rows >= 1){
							while($id_inscripciones[] = $resultado_insc->fetch_assoc());
							array_pop($id_inscripciones);

							$seccion_especifica = $r->fetch_assoc();
							//VARIABLE QUE GUARDA EL ID DE LA SECCION ESPECIFICA
							$seccion_especifica = $seccion_especifica["id"];

							$id_tipo_estudiante = $entidad->registrarTipoEstudiante("momento_estudiante");

							$conexion->query("UPDATE estudiante SET habilitado = 1 WHERE id = $identificador_estudiante");

							if($type_student == "estudiante_deudor_antiguo"){
								$conexion->query("UPDATE estudiante_deudor_antiguo SET habilitado = 0 WHERE tipo_estudiante = $id_estudiante");
							}

							$conexion->query("
								INSERT INTO momento_estudiante 
									(estudiante, tipo_estudiante, seccion_especifica, periodo_escolar, nuevo) 
								VALUES 
									($identificador_estudiante, $id_tipo_estudiante,$seccion_especifica,$id_periodo_actual,0)");

							$id_momento_estudiante = $conexion->insert_id;

							//PARTE DONDE SE LE REGISTRA EL CUPO DESHABILITADO. YA QUE VA A SER
							//ESTUDIANTE REGULAR
							$cupo = $resultado_cupo->fetch_assoc();
							$id_cupo = $cupo["id"];
							$conexion->query("INSERT INTO tipo_deuda_inscripcion (tipo_inscripcion, momento_estudiante, estado_pago, diferencia) VALUES ($id_cupo,$id_momento_estudiante,$registra_cupo,0)");

							//BUCLE QUE RECORRE LOS 12 MESES DEL AÑO
							for($i = 1; $i <= 12; $i++){
								$resultado_mes = $conexion->query("SELECT id FROM meses_periodo WHERE periodo_escolar = $id_periodo_actual AND mes = $i ORDER BY id ASC");
								$m = $resultado_mes->fetch_assoc();
								$month = $m["id"];

								$conexion->query("INSERT INTO tipo_deuda_mes (estado_pago, diferencia) VALUES (3,0)");
								$id_tipo_deuda = $conexion->insert_id;

								$conexion->query("INSERT INTO deuda_meses (meses_periodo, momento_estudiante, tipo_deuda_mes) VALUES ($month, $id_momento_estudiante, $id_tipo_deuda)");
							}

							//BULCE QUE INSERTA EN DEUDA INSCRIPCION
							//A LOS MONTOS INSCRIPCION DEL ESTUDIANTE
							for($j = 0; $j < count($id_inscripciones); $j++){
								$conexion->query("INSERT INTO tipo_deuda_inscripcion (tipo_inscripcion, momento_estudiante, estado_pago, diferencia) VALUES (".$id_inscripciones[$j]["id"].",$id_momento_estudiante,3,0)");
							}

							//**COMIENZA  EL PROCESO DE FACTURACIÓN
							$cc = $conexion->query("SELECT cliente FROM tipo_estudiante WHERE id = $id_tipo_estudiante");
							$cli = $cc->fetch_assoc();
							$id_cliente = $cli["cliente"];

							//CONSULTA QUE REGISTRA A LA FACTURA EN TIPO_FACTURA
							$result = $conexion->query("INSERT INTO  tipo_factura (tipo, cliente, fecha, hora, monto_total, usuario) VALUES ('i',$id_cliente,NOW(),NOW(),$monto_total,$id_usuario)");
							$id_tipo_factura = $conexion->insert_id;

							//SE REGISTRA LA FACTURA A PAGAR CON SUS DATOS
							$r = $conexion->query("INSERT INTO factura_inscripcion (tipo_factura) VALUES ($id_tipo_factura)");
							$id_factura = $conexion->insert_id;

							//BUCLE QUE RECORRE TODOS LOS TIPOS DE PAGO REALIZADOS PARA EL PAGO
							for($i = 0; $i < $tam_tp; $i++){
								$tp = $tipos_pagos[$i]["id_tp"];
								$rtp = $tipos_pagos[$i]["referencia_tp"];
								$ctp = $tipos_pagos[$i]["cantidad_tp"];

								if($tp == 1){
									$result = $conexion->query("INSERT INTO referencia_efectivo(tipo_factura) VALUES ($id_tipo_factura)");
									//SE GUARDA LA REFERENCIA EN LA VARIABLE
									$referencia = $conexion->insert_id;
								}else if($tp == 5){
									//SE REGISTRA EN LA TABLA PAGO NOMINA PARA QUE DE LA REFERENCIA
									$result = $conexion->query("INSERT INTO referencia_pago_nomina (tipo_factura) VALUES ($id_tipo_factura)");
									//SE GUARDA LA REFERENCIA EN LA VARIABLE
									$referencia = $conexion->insert_id;
								}else{
									$referencia = $rtp;
								}
								//SE INSERTA EL PAGO DE LA FACTURA EN LA TABLA RESPECTIVA
								$res = $conexion->query("INSERT INTO pago_factura (tipo_factura, tipo_pago, referencia, monto) VALUES ($id_tipo_factura,$tp,'$referencia',$ctp)");
							}
											
							//BUCLE QUE RECORRE TODOS LOS MONTOS A PAGAR POR EL ESTUDIANTE EN EL PERIDO ESCOLAR
							for($i = 0; $i < $tam_mp; $i++){
								$id_monto = $montos_pagar[$i]["id_monto"];
								$cancelado = $montos_pagar[$i]["cancelado"];
								$abonado = $montos_pagar[$i]["abonado"];
								$diferencia = $montos_pagar[$i]["diferencia"];

								if($cancelado == 0 && $abonado > 0 && $diferencia > 0){
									$estado_pago = 2;
								}else if($cancelado > 0){
									$estado_pago = 1;
									if($abonado > 0 && $diferencia > 0){
										$abonado = 0;
									}
								}

								//SE TOMA EL ID DEL MONTO DEBIDO DE INSCRIPCION
								$result = $conexion->query("SELECT id FROM tipo_deuda_inscripcion WHERE tipo_inscripcion = $id_monto AND momento_estudiante = $id_momento_estudiante");
								$ids_montos = $result->fetch_assoc();
								$id_monto_deuda = $ids_montos["id"];

								//SE REALIZA LA MODIFICACION DE LA DEUDA INSCRIPCION DEL ESTUDIANTE
								$res = $conexion->query("UPDATE tipo_deuda_inscripcion SET estado_pago = $estado_pago, diferencia = $diferencia WHERE id = $id_monto_deuda");

								//SE REGISTRAN LOS MONTOS DE LA DEUDA INSCRIPCION EN LA FACTURA PAGADA
								$conexion->query("INSERT INTO inscripciones_pagas (factura_inscripcion, tipo_deuda_inscripcion, abonado) VALUES ($id_factura,$id_monto_deuda,$abonado)");
							}

							//SE REGISTRA LA ACTIVIDAD REALIZADA
							$resultado = $conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'FACTURACIÓN DE INSCRIPCIÓN E INSCRIPCIÓN DEL ESTUDIANTE.',NOW(),NOW())");

							//SE ENVIA EL MENSAJE DE EXITO
							$datos = array('mensaje' => "¡ESTUDIANTE INSCRITO EN EL NUEVO AÑO ESCOLAR Y FACTURA REALIZADA CON EXITO! ESPERE UN MOMENTO PARA MOSTRARLA", 'advertencia' => 1, 'id_factura' => $id_tipo_factura);
							$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
							echo $datos_json;
						}else{
							echo "NO EXISTEN CUPO O INSCRIPCIONES EN EL PERIODO ESCOLAR";
						}
					}else{
						echo "¡SECCIÓN NO EXISTENTE!";
					}
				}
			}
		//SI NO SE INSCRIBIRA AL ESTUDIANTE
		}else{
			//SE REALIZAN LOS PASOS COMO EN LA FACTURA NORMAL PERO AHORA ES EN FACTURA INSCRIPCION
			$r = $conexion->query("
				SELECT 
					SUM(tipo_inscripcion.monto) AS total_completo 
				FROM tipo_deuda_inscripcion 
				INNER JOIN tipo_inscripcion ON tipo_deuda_inscripcion.tipo_inscripcion = tipo_inscripcion.id
				INNER JOIN momento_estudiante ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id 
				WHERE 
					(tipo_deuda_inscripcion.estado_pago = 2 OR tipo_deuda_inscripcion.estado_pago = 3) AND
					momento_estudiante.tipo_estudiante = $id_estudiante");
			$monto_total_estudiante = $r->fetch_assoc();
			$monto_total_estudiante = $monto_total_estudiante["total_completo"];

			if($monto_total > $monto_total_estudiante){
				echo "¡EL MONTO A PAGAR HA EXCEDIDO EL LIMITE DE PAGO!";
			}else{
				$cc = $conexion->query("SELECT cliente FROM tipo_estudiante WHERE id = $id_estudiante");
				$cli = $cc->fetch_assoc();
				$id_cliente = $cli["cliente"]; //ID DEL CLIENTE

				//CONSULTA QUE REGISTRA A LA FACTURA EN TIPO_FACTURA
				$result = $conexion->query("INSERT INTO  tipo_factura (tipo, cliente, fecha, hora, monto_total, usuario) VALUES ('i', $id_cliente,NOW(),NOW(),$monto_total,$id_usuario)");
				$id_tipo_factura = $conexion->insert_id;

				//SE REGISTRA LA FACTURA A PAGAR CON SUS DATOS
				$r = $conexion->query("INSERT INTO factura_inscripcion (tipo_factura) VALUES ($id_tipo_factura)");
				$id_factura = $conexion->insert_id;

				for($i = 0; $i < $tam_tp; $i++){
					$tp = $tipos_pagos[$i]["id_tp"];
					$rtp = $tipos_pagos[$i]["referencia_tp"];
					$ctp = $tipos_pagos[$i]["cantidad_tp"];

					if($tp == 1){
						$result = $conexion->query("INSERT INTO referencia_efectivo(tipo_factura) VALUES ($id_tipo_factura)");
						//SE GUARDA LA REFERENCIA EN LA VARIABLE
						$referencia = $conexion->insert_id;
					}else if($tp == 5){
						//SE REGISTRA EN LA TABLA PAGO NOMINA PARA QUE DE LA REFERENCIA
						$result = $conexion->query("INSERT INTO referencia_pago_nomina (tipo_factura) VALUES ($id_tipo_factura)");
						//SE GUARDA LA REFERENCIA EN LA VARIABLE
						$referencia = $conexion->insert_id;
					}else{
						$referencia = $rtp;
					}
					//SE INSERTA EL PAGO DE LA FACTURA EN LA TABLA RESPECTIVA
					$res = $conexion->query("INSERT INTO pago_factura (tipo_factura, tipo_pago, referencia, monto) VALUES ($id_tipo_factura,$tp,'$referencia',$ctp)");
				}

				for($i = 0; $i < $tam_mp; $i++){
					$id_monto = $montos_pagar[$i]["id_monto"];
					$cancelado = $montos_pagar[$i]["cancelado"];
					$abonado = $montos_pagar[$i]["abonado"];
					$diferencia = $montos_pagar[$i]["diferencia"];

					if($cancelado == 0 && $abonado > 0 && $diferencia > 0){
						$estado_pago = 2;
					}else if($cancelado > 0){
						$estado_pago = 1;
						if($abonado > 0 && $diferencia > 0){
							$abonado = 0;
						}
					}

					$conexion->query("UPDATE tipo_deuda_inscripcion SET estado_pago = $estado_pago, diferencia = $diferencia WHERE id = $id_monto");

					//SE REGISTRAN LOS MONTOS DE LA DEUDA INSCRIPCION EN LA FACTURA PAGADA
					$conexion->query("INSERT INTO inscripciones_pagas (factura_inscripcion, tipo_deuda_inscripcion, abonado) VALUES ($id_factura,$id_monto,$abonado)");
				}

				//SE REGISTRA LA ACTIVIDAD REALIZADA
				$resultado = $conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'FACTURACIÓN DE INSCRIPCIÓN',NOW(),NOW())");

				//SE ENVIA EL MENSAJE DE EXITO
				$datos = array('mensaje' => "¡FACTURA REALIZADA CON EXITO! ESPERE UN MOMENTO PARA MOSTRARLA", 'advertencia' => 1, 'id_factura' => $id_tipo_factura);
				$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_json;
			}
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>