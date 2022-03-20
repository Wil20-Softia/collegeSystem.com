<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		
		require_once "../../config/EntidadBase.php";
		require_once "../../config/functions.php";
		
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$da = (int)date("j");
		
		$datos = array();
		$meses = array();

		$entidad = new EntidadBase("usuario");
		$conexion = $entidad->db();

		//SE RECIBE EL OBJETO JSON DEL CLIENTE
		$superdata = $_POST["superdata"];
		//SE DECODIFICA EL OBJETO JSON A ARRAY
		$array_superdata = json_decode($superdata, true, 512, JSON_BIGINT_AS_STRING);

		$periodo_escolar_sistema = $array_superdata[0]["periodo_escolar"];
		$cantidad_sistema = $array_superdata[0]["cantidad"];
		$tipo_estudiante = $array_superdata[0]["tipo_estudiante"]; //NUMERO DE ID

		$dias_mora = $array_superdata[1];

		$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $tipo_estudiante");
		$te = $re->fetch_assoc();
		$type_student = $te["tipo"]; //NOMBRE DEL TIPO DE ESTUDIANTE

		$resultado = $conexion->query("
			SELECT 
				$type_student.id 
			FROM $type_student 
			INNER JOIN estudiante ON $type_student.estudiante = estudiante.id 
			WHERE 
				(estudiante.habilitado = 1 OR estudiante.habilitado = 2) AND 
				$type_student.tipo_estudiante = $tipo_estudiante");

		if($resultado->num_rows == 1){
			if($periodo_escolar_sistema != 0){
				$id_periodo = $periodo_escolar_sistema;
			}else{
				$id_periodo = $entidad->obtener_Id_periodoActual();
			}
			if($type_student == "momento_estudiante"){
				$sql1 = "
					SELECT 
						tipo_deuda_mes.id 
					FROM tipo_deuda_mes 
					INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
					INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id 
					INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id 
					WHERE 
						momento_estudiante.tipo_estudiante = $tipo_estudiante AND
						estudiante.habilitado = 1 AND
						(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2)
				";

				$sql2 = "
						SELECT 
							tipo_deuda_mes.id,
							mes.id AS id_mes, 
							mes.nombre, 
							tipo_deuda_mes.diferencia, 
							(mensualidad.monto - tipo_deuda_mes.diferencia) AS abono,
							tipo_deuda_mes.estado_pago 
						FROM tipo_deuda_mes
						INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
						INNER JOIN periodo_escolar ON momento_estudiante.periodo_escolar = periodo_escolar.id
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
						INNER JOIN mensualidad ON meses_periodo.mensualidad = mensualidad.id 
						INNER JOIN mes ON meses_periodo.mes = mes.id 
						WHERE 
							(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2) AND 
							momento_estudiante.tipo_estudiante = $tipo_estudiante AND
							estudiante.habilitado = 1";
			}else if($type_student == "estudiante_deudor_antiguo"){
				$sql1 = "
					SELECT 
						tipo_deuda_mes.id 
					FROM tipo_deuda_mes 
					INNER JOIN deuda_antigua ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id
					INNER JOIN estudiante_deudor_antiguo ON deuda_antigua.estudiante_deudor_antiguo = estudiante_deudor_antiguo.id 
					INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id 
					WHERE 
						estudiante_deudor_antiguo.tipo_estudiante = $tipo_estudiante AND
						estudiante.habilitado = 2 AND 
						(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2)
				";

				$m = $conexion->query("SELECT monto FROM mensualidad WHERE 1 ORDER BY id DESC LIMIT 0,1");
				$mn = $m->fetch_assoc();
				$monto_mensualidad = $mn["monto"];

				$sql2 = "
						SELECT 
							tipo_deuda_mes.id, 
							mes.nombre, 
							tipo_deuda_mes.diferencia, 
							($monto_mensualidad - tipo_deuda_mes.diferencia) AS abono,
							tipo_deuda_mes.estado_pago 
						FROM tipo_deuda_mes
						INNER JOIN deuda_antigua ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN estudiante_deudor_antiguo ON deuda_antigua.estudiante_deudor_antiguo = estudiante_deudor_antiguo.id
						INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id 
						INNER JOIN mes ON deuda_antigua.mes = mes.id 
						WHERE 
							(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2) AND 
							estudiante_deudor_antiguo.tipo_estudiante = $tipo_estudiante AND
							estudiante.habilitado = 2";
			}

			$rq = $conexion->query($sql1);
			if($rq->num_rows > 0){
				if($cantidad_sistema == 0){
					$resultado = $conexion->query($sql2);

					while($row = $resultado->fetch_assoc()){
						if($type_student == "estudiante_deudor_antiguo"){
							$mostrar_dias = 1;
						}else if($type_student == "momento_estudiante"){
							$mes_actual = $entidad->mes_actual_ordernado();
							$numero_mes = $row["id_mes"];
							if($numero_mes < $mes_actual){
								$mostrar_dias = 1;
							}else if($numero_mes == $mes_actual){
								if($da >= 6){
						            $mostrar_dias = 1;
						        }else{
						            $mostrar_dias = 0;
						        }
							}else{
								$mostrar_dias = 0;
							}		
						}
						if($row["estado_pago"] == 3){
							$abono = 0.00;
							$color_mes = "bg-danger";
						}else{
							$abono = $row["abono"];
							$color_mes = "bg-warning";
						}
						$meses[] = array(
							"id_mes" => $row["id"],
							"nombre_mes" => substr($row["nombre"], 0, 3),
							"color_mes" => $color_mes,
							"diferencia" => $row["diferencia"],
							"abonado" => $abono,
							"cancelado" => 0.00,
							"activar" => 0,
							"mostrar_dias" => $mostrar_dias
						);
						$totales = 0;
					}
					array_push($datos, $meses);
					array_push($datos, $totales);

					$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $d;
				}else{
					$subtotal = 0;
					$total_mora = 0;
					$total_completo = 0;
					$queda_mora = 0;
					$mes_pagado = 1;
					$aqui = 0;
					$valor = $cantidad_sistema;

					$coger_porcentaje_mora = $conexion->query("SELECT porcentaje FROM mora WHERE 1 ORDER BY id DESC LIMIT 0,1");
					$asociativo_porcentaje_mora = $coger_porcentaje_mora->fetch_assoc();
					$porcentaje_mora_actual = $asociativo_porcentaje_mora["porcentaje"];

					$coger_mensualidad_actual = $conexion->query("SELECT monto FROM mensualidad WHERE 1 ORDER BY id DESC LIMIT  0,1");
					$asociativo_mensualidad_actual = $coger_mensualidad_actual->fetch_assoc();
					$monto_mensualidad_actual = $asociativo_mensualidad_actual["monto"];

					$resultado = $conexion->query($sql2);

					if(is_array($dias_mora)){
						$tam_dias_mora = count($dias_mora);
						$i = 0;
						$ms = 0;

						while($row = $resultado->fetch_assoc()){
							$mes_deuda = $row["id"];
							$abono = 0;
							$cancelado = 0;
							$diferencia = 0;
							if($i < $tam_dias_mora){
								$mostrar_dias = 1;
								$mes_mora = $dias_mora[$i]["id_mes_mora"];
								$cantidad_dias = $dias_mora[$i]["cantidad_dias"];
								if($mes_mora == $mes_deuda){
									$valor_temp = $valor;
									$restando_mora = (($porcentaje_mora_actual * $cantidad_dias)/100) * $monto_mensualidad_actual;
									if($valor - ($restando_mora + $monto_mensualidad_actual) < 0){
										$queda_mora += $valor_temp;
										$valor = 0;
										$aqui = 1;
									}else{
										$valor -= $restando_mora;
										if($valor > 0 && $mes_pagado == 1){
											$total_mora += $restando_mora;
											$aqui = 0;
										}else if($valor <= 0){
											$queda_mora += $valor_temp;
											$valor = 0;
											$aqui = 1;
										}
									}
								}
								$i++;
							}else{
								$mostrar_dias = 0;
								$cantidad_dias = 0;
								$aqui = 0;
							}
							
							if($valor <= 0 || $aqui == 1){
								$activar = 0;
								if($row["estado_pago"] == 3){
									$abono = 0.00;
									$color_mes = "bg-danger";
								}else{
									$abono = $row["abono"];
									$color_mes = "bg-warning";
								}
								$mes_pagado = 0;
							}else{
								$ms++;
								$activar = 1;
								$mensualidad = $monto_mensualidad_actual;
								if($valor >= $mensualidad){
									if($row["estado_pago"] == 3){
										$abono = 0.00;
										$cancelado = $mensualidad;
										$diferencia = 0.00;
										$valor -= $mensualidad;
										$subtotal += $mensualidad;
										$color_mes = "bg-success";
										$mes_pagado = 1;
									}else{
										$abono = $row["abono"];
										$cancelado = $mensualidad;
										$diferencia = $row["diferencia"];
										$valor -= $row["diferencia"];
										$subtotal += $row["diferencia"];
										$color_mes = "bg-success";
										$mes_pagado = 1;
									}
								}else if($valor < $mensualidad){
									if($row["estado_pago"] == 3){
										if($mes_mora == $mes_deuda){
											$activar = 0;
											$abono = 0.00;
											$queda_mora += $valor;
											$cancelado = 0.00;
											$diferencia = 0.00;
											$valor = 0;
											$color_mes = "bg-danger";
											$mes_pagado = 0;
										}else{
											$abono = $valor;
											$cancelado = 0.00;
											$diferencia = $mensualidad - $valor;
											$valor = 0;
											$subtotal += $abono;
											$color_mes = "bg-warning";
											$mes_pagado = 2;
										}
									}else{
										if(($row["abono"]+$valor) > $mensualidad){
											$abono = $row["abono"];
											$cancelado = $mensualidad;
											$diferencia = $row["diferencia"];
											$valor -= $row["diferencia"];
											$subtotal += $row["diferencia"];
											$color_mes = "bg-success";
											$mes_pagado = 1;
										}else if(($row["abono"]+$valor) < $mensualidad){
											if($mes_mora == $mes_deuda){
												$activar = 0;
												$abono = 0.00;
												$queda_mora += $valor;
												$cancelado = 0.00;
												$diferencia = 0.00;
												$valor = 0;
												$color_mes = "bg-danger";
												$mes_pagado = 0;
											}else{
												$abono = ($row["abono"] + $valor);
												$subtotal += $valor;
												$cancelado = 0.00;
												$diferencia = $mensualidad - $abono;
												$valor = 0;
												$color_mes = "bg-warning";
												$mes_pagado = 2;
											}
										}else{
											$abono = $row["abono"];
											$cancelado = $mensualidad;
											$diferencia = $row["diferencia"];
											$subtotal += $valor;
											$valor = 0;
											$color_mes = "bg-success";
											$mes_pagado = 1;
										}
									}
								}
							}

							$meses[] = array(
								"id_mes" => $mes_deuda,
								"nombre_mes" => substr($row["nombre"], 0, 3),
								"color_mes" => $color_mes,
								"diferencia" => $diferencia,
								"abonado" => $abono,
								"cancelado" => $cancelado,
								"activar" => $activar,
								"mostrar_dias" => $mostrar_dias,
								"valor_dia" => $cantidad_dias
							);
						}

						if($ms < $i){
							$total_resto = $queda_mora;
						}else{
							$total_resto = $cantidad_sistema - ($total_mora + $subtotal + $queda_mora) < 0 ? 0 :  $cantidad_sistema - ($total_mora + $subtotal + $queda_mora);
						}
						
						$totales = array(
							"total_mora" => $total_mora,
							"subtotal" => $subtotal,
							"total" => $total_mora + $subtotal,
							"resto" =>  $total_resto
						);

						array_push($datos, $meses);
						array_push($datos, $totales);

						$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $d;
					}else{
						while($row = $resultado->fetch_assoc()){
							$abono = 0;
							$cancelado = 0;
							$diferencia = 0;
							if($valor <= 0){
								$activar = 0;
								if($row["estado_pago"] == 3){
									$abono = 0.00;
									$color_mes = "bg-danger";
								}else{
									$abono = $row["abono"];
									$color_mes = "bg-warning";
								}
							}else{
								$activar = 1;
								$mensualidad = $monto_mensualidad_actual;
								if($valor >= $mensualidad){
									if($row["estado_pago"] == 3){
										$abono = 0.00;
										$cancelado = $mensualidad;
										$diferencia = 0.00;
										$valor = $valor - $mensualidad;
										$color_mes = "bg-success";
									}else{
										$abono = $row["abono"];
										$cancelado = $mensualidad;
										$diferencia = $row["diferencia"];
										$valor = $valor - $row["diferencia"];
										$color_mes = "bg-success";
									}
								}else if($valor < $mensualidad){
									if($row["estado_pago"] == 3){
										$abono = $valor;
										$cancelado = 0.00;
										$diferencia = $mensualidad - $valor;
										$valor = 0;
										$color_mes = "bg-warning";
									}else{
										if(($row["abono"]+$valor) > $mensualidad){
											$abono = $row["abono"];
											$cancelado = $mensualidad;
											$diferencia = $row["diferencia"];
											$valor = $valor - $row["diferencia"];
											$color_mes = "bg-success";
										}else if(($row["abono"]+$valor) < $mensualidad){
											$abono = ($row["abono"] + $valor);
											$cancelado = 0.00;
											$diferencia = $mensualidad - ($row["abono"] + $valor);
											$valor = 0;
											$color_mes = "bg-warning";
										}else{
											$abono = $row["abono"];
											$cancelado = $mensualidad;
											$diferencia = $row["diferencia"];
											$valor = 0;
											$color_mes = "bg-success";
										}
									}
								}
							}
							$meses[] = array(
								"id_mes" => $row["id"],
								"nombre_mes" => substr($row["nombre"], 0, 3),
								"color_mes" => $color_mes,
								"diferencia" => $diferencia,
								"abonado" => $abono,
								"cancelado" => $cancelado,
								"activar" => $activar
							);
						}

						$res_diferencia = $conexion->query("SELECT 
							SUM(tipo_deuda_mes.diferencia) AS total_diferencias
						FROM tipo_deuda_mes
						INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
						INNER JOIN mensualidad ON meses_periodo.mensualidad = mensualidad.id 
						WHERE 
							tipo_deuda_mes.estado_pago = 2 AND 
							momento_estudiante.tipo_estudiante = $tipo_estudiante AND
							estudiante.habilitado = 1");

						$res_normales = $conexion->query("SELECT 
							SUM(mensualidad.monto) AS total_normales
						FROM tipo_deuda_mes
						INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
						INNER JOIN mensualidad ON meses_periodo.mensualidad = mensualidad.id 
						WHERE 
							tipo_deuda_mes.estado_pago = 3 AND 
							momento_estudiante.tipo_estudiante = $tipo_estudiante AND
							estudiante.habilitado = 1");

						$totales_diferencias = $res_diferencia->fetch_assoc();
						$totales_diferencias = $totales_diferencias["total_diferencias"];

						$totales_normales = $res_normales->fetch_assoc();
						$totales_normales = $totales_normales["total_normales"];

						$total_enDeuda = $totales_diferencias + $totales_normales;

						$total_resto = 0;

						if($cantidad_sistema > $total_enDeuda){
							$total_resto = $cantidad_sistema - $total_enDeuda;
							$a_pagar = $total_enDeuda;
						}else{
							$a_pagar = $cantidad_sistema;
						}
 
						$totales = array(
							"total_mora" => 0,
							"subtotal" => $a_pagar,
							"total" => $a_pagar,
							"resto" =>  $total_resto
						);

						array_push($datos, $meses);
						array_push($datos, $totales);

						$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $d;
					}
				}
			}else{
				$datos = array(
					'mensaje' => "¡EL ESTUDIANTE HA CANCELADO TODOS LOS MESES DE ESTE PERIODO ESCOLAR!",
					'advertencia' => 2
				);
				$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $d;
			}
		}else{
			echo "¡EL ESTUDIANTE NO EXISTE, O NO ESTA RETIRADO!";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>