<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		
		require_once "../../config/EntidadBase.php";
		require_once "../../config/functions.php";
		
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$da = (int)date("j");

		$entidad = new EntidadBase("tipo_estudiante");
		$conexion = $entidad->db();
		
		$tipo_estudiante = $_POST["tipo_estudiante"]; //NUMERO DE ID

		$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $tipo_estudiante");
		$te = $re->fetch_assoc();
		$type_student = $te["tipo"]; //NOMBRE DEL TIPO DE ESTUDIANTE

		$resultado = $conexion->query("
			SELECT 
				$type_student.id 
			FROM $type_student 
			INNER JOIN estudiante ON $type_student.estudiante = estudiante.id 
			WHERE 
				estudiante.habilitado != 0 AND 
				$type_student.tipo_estudiante = $tipo_estudiante");

		if($resultado->num_rows == 1){
			if($_POST["periodo_escolar"] != 0){
				$id_periodo = $_POST["periodo_escolar"];
			}else{
				$id_periodo = $entidad->obtener_Id_periodoActual();
			}
			$resultado = $conexion->query("
				SELECT 
					momento_estudiante.id AS id_estudiante
				FROM momento_estudiante 
				INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
				WHERE
					estudiante.habilitado = 1 AND
					momento_estudiante.tipo_estudiante = $tipo_estudiante AND
					momento_estudiante.periodo_escolar = $id_periodo");
			$existe_estudiante_periodo = $resultado->num_rows;
					
			//SI PERTENECE AL PERIODO ESCOLAR ELEGIDO POR EL USUARIO
			if($existe_estudiante_periodo == 1){
				$inscribir = 0;
				$rq = $conexion->query("
						SELECT 
							tipo_deuda_inscripcion.id 
						FROM tipo_deuda_inscripcion 
						INNER JOIN momento_estudiante ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id 
						WHERE 
							momento_estudiante.tipo_estudiante = $tipo_estudiante AND
							(tipo_deuda_inscripcion.estado_pago = 3 OR tipo_deuda_inscripcion.estado_pago = 2)");
				if($rq->num_rows > 0){
					if($_POST["cantidad"] == 0){
						$resultado = $conexion->query("
							SELECT 
								tipo_deuda_inscripcion.id, 
								CONCAT(tipo_inscripcion.tipo,'. ',tipo_inscripcion.id) AS nro_inscripcion,
								tipo_deuda_inscripcion.diferencia, 
								(tipo_inscripcion.monto - tipo_deuda_inscripcion.diferencia) AS abono,
								tipo_deuda_inscripcion.estado_pago 
							FROM tipo_deuda_inscripcion
							INNER JOIN momento_estudiante ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id
							INNER JOIN tipo_inscripcion ON tipo_deuda_inscripcion.tipo_inscripcion = tipo_inscripcion.id 
							WHERE
								momento_estudiante.tipo_estudiante = $tipo_estudiante AND 
								(tipo_deuda_inscripcion.estado_pago = 3 OR tipo_deuda_inscripcion.estado_pago = 2)
							ORDER BY tipo_inscripcion.tipo ASC");

						while($row = $resultado->fetch_assoc()){
							if($row["estado_pago"] == 3){
								$abono = 0.00;
								$color = "bg-danger";
							}else{
								$abono = $row["abono"];
								$color = "bg-warning";
							}
							$datos[] = array(
								"id_inscripcion" => $row["id"],
								"nombre_inscripcion" => $row["nro_inscripcion"],
								"color_inscripcion" => $color,
								"diferencia" => $row["diferencia"],
								"abonado" => $abono,
								"cancelado" => 0.00,
								"activar" => 0,
								"inscribir" => $inscribir
							);
						}

						$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $d;
					}else{
						$valor = $_POST["cantidad"];
						$resultado = $conexion->query("
							SELECT 
								tipo_deuda_inscripcion.id, 
								CONCAT(tipo_inscripcion.tipo,'. ',tipo_inscripcion.id) AS nro_inscripcion, 
								tipo_deuda_inscripcion.diferencia, 
								(tipo_inscripcion.monto - tipo_deuda_inscripcion.diferencia) AS abono,
								tipo_deuda_inscripcion.estado_pago, 
								tipo_inscripcion.monto 
							FROM tipo_deuda_inscripcion
							INNER JOIN momento_estudiante ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id
							INNER JOIN tipo_inscripcion ON tipo_deuda_inscripcion.tipo_inscripcion = tipo_inscripcion.id 
							WHERE 
								momento_estudiante.tipo_estudiante = $tipo_estudiante AND
								(tipo_deuda_inscripcion.estado_pago = 3 OR tipo_deuda_inscripcion.estado_pago = 2)
							ORDER BY tipo_inscripcion.tipo ASC");

						while($row = $resultado->fetch_assoc()){
							$abono = 0;
							$cancelado = 0;
							$diferencia = 0;
							if($valor == 0){
								$activar = 0;
								if($row["estado_pago"] == 3){
									$abono = 0.00;
									$color = "bg-danger";
								}else{
									$abono = $row["abono"];
									$color = "bg-warning";
								}
							}else{
								$activar = 1;
								$monto = $row["monto"];
								if($valor >= $monto){
									if($row["estado_pago"] == 3){
										$abono = 0.00;
										$cancelado = $monto;
										$diferencia = 0.00;
										$valor = $valor - $monto;
										$color = "bg-success";
									}else{
										$abono = $row["abono"];
										$cancelado = $monto;
										$diferencia = $row["diferencia"];
										$valor = $valor - $row["diferencia"];
										$color = "bg-success";
									}
								}else if($valor < $monto){
									if($row["estado_pago"] == 3){
										$abono = $valor;
										$cancelado = 0.00;
										$diferencia = $monto - $valor;
										$valor = 0;
										$color = "bg-warning";
									}else{
										if(($row["abono"]+$valor) > $monto){
											$abono = $row["abono"];
											$cancelado = $monto;
											$diferencia = $row["diferencia"];
											$valor = $valor - $row["diferencia"];
													$color = "bg-success";
										}else if(($row["abono"]+$valor) < $monto){
											$abono = ($row["abono"] + $valor);
											$cancelado = 0.00;
											$diferencia = $monto - ($row["abono"] + $valor);
											$valor = 0;
											$color = "bg-warning";
										}else{
											$abono = $row["abono"];
											$cancelado = $monto;
											$diferencia = $row["diferencia"];
											$valor = 0;
											$color = "bg-success";
										}
									}
								}
							}
							$datos[] = array(
								"id_inscripcion" => $row["id"],
								"nombre_inscripcion" => $row["nro_inscripcion"],
								"color_inscripcion" => $color,
								"diferencia" => $diferencia,
								"abonado" => $abono,
								"cancelado" => $cancelado,
								"activar" => $activar,
								"inscribir" => $inscribir
							);
						}

						$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $d;
					}
				}else{
					$datos = array(
						'mensaje' => "¡EL ESTUDIANTE HA CANCELADO TODA SU DEUDA!",
						'advertencia' => 2
					);
					$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $d;
				}
			}else if($existe_estudiante_periodo == 0){
				$r = $conexion->query("SELECT momento_estudiante.id FROM momento_estudiante INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id WHERE momento_estudiante.tipo_estudiante = $tipo_estudiante AND estudiante.habilitado = 3");
				if($r->num_rows == 1){
					$sql = "SELECT id, CONCAT(tipo,'. ',id) AS nro_inscripcion, monto FROM tipo_inscripcion WHERE tipo = 'Insc' AND periodo_escolar = $id_periodo ORDER BY tipo ASC";
				}else{
					$resultado_cedula = $conexion->query("SELECT estudiante.cedula FROM estudiante_deudor_antiguo INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id WHERE estudiante_deudor_antiguo.tipo_estudiante = $tipo_estudiante");
					$ced_est = $resultado_cedula->fetch_assoc();
					$cedula_estudiante = $ced_est["cedula"];

					$resultado_me = $conexion->query("SELECT momento_estudiante.id, momento_estudiante.periodo_escolar FROM estudiante INNER JOIN momento_estudiante ON momento_estudiante.estudiante = estudiante.id WHERE estudiante.cedula = '$cedula_estudiante' ORDER BY momento_estudiante.id DESC LIMIT 0,1");
					if($resultado_me->num_rows == 1){
						$sql = "SELECT id, CONCAT(tipo,'. ',id) AS nro_inscripcion, monto FROM tipo_inscripcion WHERE tipo = 'Insc' AND periodo_escolar = $id_periodo ORDER BY tipo ASC";
					}else{
						$sql = "SELECT id, CONCAT(tipo,'. ',id) AS nro_inscripcion, monto FROM tipo_inscripcion WHERE (tipo = 'Insc' OR tipo = 'Cupo') AND periodo_escolar = $id_periodo ORDER BY tipo ASC";
					}
				}
				$resultado = $conexion->query($sql);

				if($resultado->num_rows == 0){
					echo "¡NO HAY NINGÚNA INSCRIPCIÓN PARA EL PERIODO ESCOLAR ESCOGIDO, REGISTRE LAS DEBIDAS INSCRIPCIONES!";
				}else{
					$inscribir = 1;

					if($_POST["cantidad"] == 0){
						while($row = $resultado->fetch_assoc()){
							$datos[] = array(
								"id_inscripcion" => $row["id"],
								"nombre_inscripcion" => $row["nro_inscripcion"],
								"color_inscripcion" => "bg-danger",
								"diferencia" => 0.00,
								"abonado" => 0.00,
								"cancelado" => 0.00,
								"activar" => 0,
								"inscribir" => $inscribir
							);
						}

						$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $d;
					}else{
						$valor = $_POST["cantidad"];
						while($row = $resultado->fetch_assoc()){
							$activar = 0;
							$abono = 0;
							$cancelado = 0;
							$diferencia = 0;
							$color = "bg-danger";
							if($valor > 0){
								$activar = 1;
								$monto = $row["monto"];
								if($valor >= $monto){
									$abono = 0.00;
									$cancelado = $monto;
									$diferencia = 0.00;
									$valor = $valor - $monto;
									$color = "bg-success";
								}else if($valor < $monto){
									$abono = $valor;
									$cancelado = 0.00;
									$diferencia = $monto - $valor;
									$valor = 0;
									$color = "bg-warning";
								}
							}
							$datos[] = array(
								"id_inscripcion" => $row["id"],
								"nombre_inscripcion" => $row["nro_inscripcion"],
								"color_inscripcion" => $color,
								"diferencia" => $diferencia,
								"abonado" => $abono,
								"cancelado" => $cancelado,
								"activar" => $activar,
								"inscribir" => $inscribir
							);
						}

						$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $d;
					}
				}
			}
		}else{
			echo "¡EL ESTUDIANTE NO EXISTE, O ESTA RETIRADO!";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>