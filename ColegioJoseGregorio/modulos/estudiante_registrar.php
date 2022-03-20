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
		require_once "../config/EntidadBase.php";
		require_once "../config/functions.php";

		//SE RECIBE EL OBJETO JSON DEL CLIENTE
		$superdata = $_POST["superdata"];
		//SE DECODIFICA EL OBJETO JSON A ARRAY
		$array_superdata = json_decode($superdata, true, 512, JSON_BIGINT_AS_STRING);

		$datos_estudiante = $array_superdata[0];
		$datos_importantes = $array_superdata[1]; 

		$id_usuario = $_SESSION["id"];
		//1.
		//DATOS DEL ESTUDIANTE.
		$e1n = $datos_estudiante["estudiante_primer_nombre"]; 
	    $e2n = $datos_estudiante["estudiante_segundo_nombre"];
	    $e1a = $datos_estudiante["estudiante_primer_apellido"];
	    $e2a = $datos_estudiante["estudiante_segundo_apellido"];
	    $ec = $datos_estudiante["estudiante_cedula"];
	    $estudiante_cedulado = $datos_estudiante["estudiante_cedulado"];

	    //DATOS DEL REPRESENTANTE.
	    $r1n = $datos_estudiante["representante_primer_nombre"];
	    $r2n = $datos_estudiante["representante_segundo_nombre"];
	    $r1a = $datos_estudiante["representante_primer_apellido"];
	    $r2a = $datos_estudiante["representante_segundo_apellido"];
	    $rc = $datos_estudiante["representante_cedula"];
	    $rt = $datos_estudiante["representante_telefono"];

		$entidad = new EntidadBase("estudiante");

		$conexion = $entidad->db();

		//NUEVO INGRESO = 1 Y REGULAR = 0
		if($datos_importantes["tipo_estudiante"] == 1 || $datos_importantes["tipo_estudiante"] == 0){

	    	//SECCION EN DONDE SE REGISTRARÁ AL ESTUDIANTE.
		    $seccion = $datos_importantes["seccion_especifica"];

		    //MES DE INSCRIPCION DEL ESTUDIANTE.
		    $mes_inscripcion = (int)$datos_importantes["mes_inscripcion"];

		    //2.
		    if($estudiante_cedulado == 1){
		    	$resultado = $conexion->query("SELECT id FROM estudiante WHERE cedula = '$ec' AND (habilitado = 1 OR habilitado = 2 OR habilitado = 3)");
		    }else{
		    	$resultado = $conexion->query("SELECT id FROM estudiante WHERE primer_nombre LIKE '$e1n' AND segundo_nombre LIKE '$e2n' AND primer_apellido LIKE '$e1a' AND segundo_apellido LIKE '$e2a' AND (habilitado = 1 OR habilitado = 2 OR habilitado = 3)");
		    }
			
			if($resultado->num_rows == 0){
				//4.
				$id_periodo_actual = $entidad->obtener_Id_periodoActual();

				if(!$id_periodo_actual){
					$mensaje = "¡Periodo Escolar Actual no Registrado!";
					$advertencia = 2;
					$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
					$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_json;					
				}else{

					$resultado = $conexion->query("SELECT id FROM meses_periodo WHERE periodo_escolar = $id_periodo_actual AND mensualidad = 1");

					if($resultado->num_rows == 0){
						//8.
						$resultado = $conexion->query("SELECT id FROM tipo_inscripcion WHERE tipo LIKE 'Insc' AND periodo_escolar = $id_periodo_actual");

						if($resultado->num_rows >= 1){

							//VERIFICACION SI EXISTE EL CUPO PARA EL AÑO ESCOLAR ACTUAL
							$restl = $conexion->query("SELECT id FROM tipo_inscripcion WHERE tipo LIKE 'Cupo' AND periodo_escolar = $id_periodo_actual");

							if(($restl->num_rows == 1 && $datos_importantes["tipo_estudiante"] == 1) || $datos_importantes["tipo_estudiante"] == 0){

								//8.
								while($id_inscripciones[] = $resultado->fetch_assoc());
								array_pop($id_inscripciones);

								//10.
								$resultado = $conexion->query("SELECT id FROM representante WHERE cedula = '$rc'");

								if($resultado->num_rows == 0){
									//11.
									$id_comprador = $entidad->registrarComprador("representante");
									$conexion->query("
										INSERT INTO representante 
											(cedula, tipo_comprador, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono) 
										VALUES 
											('$rc',$id_comprador,'$r1n','$r2n','$r1a','$r2a','$rt')");
									$id_representante = $conexion->insert_id;	
								}else{
									//10.
									$ri = $resultado->fetch_assoc();
									$id_representante = $ri["id"];
								}
								
								$resultado_deshabilitado_cedulado = $conexion->query("SELECT id FROM estudiante WHERE cedula = '$ec' AND habilitado = 0 AND cedulado = 1");

								$resultado_deshabilitado_no_cedulado = $conexion->query("SELECT id FROM estudiante WHERE primer_nombre LIKE '$e1n' AND segundo_nombre LIKE '$e2n' AND primer_apellido LIKE '$e1a' AND segundo_apellido LIKE '$e2a' AND habilitado = 0 AND cedulado = 0");

								if($resultado_deshabilitado_cedulado->num_rows == 1 && $resultado_deshabilitado_no_cedulado->num_rows == 0){
									$ob_ide = $resultado_deshabilitado_cedulado->fetch_assoc();
									$id_estudiante = $ob_ide["id"];
									$conexion->query("UPDATE estudiante SET representante=$id_representante, primer_nombre='$e1n', segundo_nombre='$e2n', primer_apellido='$e1a', segundo_apellido='$e2a', habilitado=1 WHERE id = $id_estudiante");
								}else if($resultado_deshabilitado_cedulado->num_rows == 0 && $resultado_deshabilitado_no_cedulado->num_rows == 0){
									if($estudiante_cedulado == 0){
										$conexion->query("INSERT INTO no_cedulado () VALUES ()");
										$id_no_cedulado = $conexion->insert_id;
										$ec = "X-" . $id_no_cedulado;
									}
									$id_comprador = $entidad->registrarComprador("estudiante");
									$conexion->query("INSERT INTO estudiante (cedula, representante, tipo_comprador, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cedulado, habilitado) VALUES ('$ec', $id_representante, $id_comprador, '$e1n', '$e2n', '$e1a', '$e2a', $estudiante_cedulado, 1)");
										
									$id_estudiante = $conexion->insert_id;
								}else if($resultado_deshabilitado_cedulado->num_rows == 0 && $resultado_deshabilitado_no_cedulado->num_rows == 1){
									$ob_ide = $resultado_deshabilitado_no_cedulado->fetch_assoc();
									$id_estudiante = $ob_ide["id"];
									if($estudiante_cedulado == 0){
										$conexion->query("UPDATE estudiante SET representante=$id_representante, primer_nombre='$e1n', segundo_nombre='$e2n', primer_apellido='$e1a', segundo_apellido='$e2a', habilitado=1 WHERE id = $id_estudiante");
									}else{
										$conexion->query("UPDATE estudiante SET cedula='$ec', representante=$id_representante, primer_nombre='$e1n', segundo_nombre='$e2n', primer_apellido='$e1a', segundo_apellido='$e2a', habilitado=1, cedulado=$estudiante_cedulado WHERE id = $id_estudiante");
									}
								}

								if($datos_importantes["tipo_estudiante"] != 0){
									$nuevo = 1;
									$frase = "NUEVO INGRESO";
								}else{
									$nuevo = 0;
									$frase = "REGULAR";
								}

								$id_tipo_estudiante = $entidad->registrarTipoEstudiante("momento_estudiante");

								$resultado_me = $conexion->query("
									INSERT INTO momento_estudiante 
										(estudiante, tipo_estudiante, seccion_especifica, periodo_escolar, nuevo) 
									VALUES 
										($id_estudiante, $id_tipo_estudiante,$seccion,$id_periodo_actual,$nuevo)");
								if(!$resultado_me){
									echo $conexion->error. " - ". $resultado_me;
								}else{
								$id_momento_estudiante = $conexion->insert_id;
								//14.

								//PARTE DONDE SE LE REGISTRA EL CUPO AL ESTUDIANTE PARA QUE LO CANCELE.
								$cupo = $restl->fetch_assoc();
								$id_cupo = $cupo["id"];
								if($datos_importantes["tipo_estudiante"] != 0){
									$conexion->query("
										INSERT INTO tipo_deuda_inscripcion (tipo_inscripcion, momento_estudiante, estado_pago, diferencia) VALUES ($id_cupo,$id_momento_estudiante,3,0)");
								}else{
									$conexion->query("
										INSERT INTO tipo_deuda_inscripcion (tipo_inscripcion, momento_estudiante, estado_pago, diferencia) VALUES ($id_cupo,$id_momento_estudiante,4,0)");
								}

								//17.
								for($i = 1; $i<=12; $i++){
									if($i >= $mes_inscripcion && $i <= 12){
										$estado_pago = 3;
									}else{
										$estado_pago = 4;
									}

									//15.
									$resultado_mes = $conexion->query("SELECT id FROM meses_periodo WHERE periodo_escolar = $id_periodo_actual AND mes = $i");
									$m = $resultado_mes->fetch_assoc();
									$month = $m["id"];

									$conexion->query("INSERT INTO tipo_deuda_mes (estado_pago, diferencia) VALUES ($estado_pago,0)");
									$id_tipo_deuda = $conexion->insert_id;

									$conexion->query("
										INSERT INTO deuda_meses (meses_periodo, momento_estudiante, tipo_deuda_mes) VALUES ($month, $id_momento_estudiante, $id_tipo_deuda)");
								}
								
								//18.
								for($j = 0; $j < count($id_inscripciones); $j++){
									//19.
									$conexion->query("INSERT INTO tipo_deuda_inscripcion (tipo_inscripcion, momento_estudiante, estado_pago, diferencia) VALUES (".$id_inscripciones[$j]["id"].",$id_momento_estudiante,3,0)");
								}

								$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario, 'REGISTRO DE ESTUDIANTE, '" . $frase .",NOW(),NOW())");
								$mensaje = "¡ESTUDIANTE REGISTRADO Y ACTUALIZADO EXITOSAMENTE!";
								$advertencia = 1;
								$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
								$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
								echo $datos_json;
								}
							//SINO EXISTE EL CUPO PARA EL PERIODO ESCOLAR ACTUAL
							}else if($restl->num_rows == 0 && $datos_importantes["tipo_estudiante"] == 1){
								//9.
								$mensaje = "¡No se ha registrado el Monto del CUPO para el Año Escolar Actual!";
								$advertencia = 2;
								$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
								$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
								echo $datos_json;
							}
						}else{
							//9.
							$mensaje = "¡No se ha registrado Monto de Inscripción para el Año Escolar Actual!";
							$advertencia = 2;
							$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
							$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
							echo $datos_json;
						}
					}else{
						//7.
						$mensaje = "¡No se han registrado Mensualidades para el Año Escolar Actual!";
						$advertencia = 2;
						$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
						$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $datos_json;
					}
				}
			}else{
				//3.
				if($estudiante_cedulado == 1){
					echo "¡Estudiante con Cedula ya Existente!";
				}else{
					echo "¡Estudiante con Nombres y Apellidos Completos ya Existente!";
				}
			}

		//DEUDOR ANTIGUO = 2
	    }else if($datos_importantes["tipo_estudiante"] == 2){
	    	$mes_desde = (int)$datos_importantes["mes_desde"];
	    	$mes_hasta = (int)$datos_importantes["mes_hasta"];
	    	//2.
			if($estudiante_cedulado == 1){
		    	$resultado = $conexion->query("SELECT id FROM estudiante WHERE cedula = '$ec' AND (habilitado = 1 OR habilitado = 2 OR habilitado = 3)");
		    }else{
		    	$resultado = $conexion->query("SELECT id FROM estudiante WHERE primer_nombre LIKE '$e1n' AND segundo_nombre LIKE '$e2n' AND primer_apellido LIKE '$e1a' AND segundo_apellido LIKE '$e2a' AND (habilitado = 1 OR habilitado = 2 OR habilitado = 3)");
		    }

			if($resultado->num_rows == 0){
				$id_periodo_actual = $entidad->obtener_Id_periodoActual();

				if(!$id_periodo_actual){
					$mensaje = "¡Periodo Escolar Actual no Registrado!";
					$advertencia = 2;
					$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
					$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_json;
				}else if($resultado->num_rows == 0){
					$resultado = $conexion->query("SELECT id FROM meses_periodo WHERE periodo_escolar = $id_periodo_actual AND mensualidad = 1");

					if($resultado->num_rows == 0){
						$tomar_mensualidad = $conexion->query("SELECT id FROM mensualidad WHERE 1 ORDER BY id DESC LIMIT 0,1");
						$id_mensualidad = $tomar_mensualidad->fetch_assoc();
						$id_mensualidad = $id_mensualidad["id"];

						$resultado = $conexion->query("SELECT id FROM representante WHERE cedula = '$rc'");

						if($resultado->num_rows == 0){
							$id_comprador = $entidad->registrarComprador("representante");
							$conexion->query("
								INSERT INTO representante 
									(cedula, tipo_comprador, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono) 
								VALUES 
									('$rc',$id_comprador,'$r1n','$r2n','$r1a','$r2a','$rt')");
							$id_representante = $conexion->insert_id;	
						}else{
							$ri = $resultado->fetch_assoc();
							$id_representante = $ri["id"];
						}

						$resultado_deshabilitado_cedulado = $conexion->query("SELECT id FROM estudiante WHERE cedula = '$ec' AND habilitado = 0 AND cedulado = 1");

						$resultado_deshabilitado_no_cedulado = $conexion->query("SELECT id FROM estudiante WHERE primer_nombre LIKE '$e1n' AND segundo_nombre LIKE '$e2n' AND primer_apellido LIKE '$e1a' AND segundo_apellido LIKE '$e2a' AND habilitado = 0 AND cedulado = 0");

						if($resultado_deshabilitado_cedulado->num_rows == 1 && $resultado_deshabilitado_no_cedulado->num_rows == 0){
							$ob_ide = $resultado_deshabilitado_cedulado->fetch_assoc();
							$id_estudiante = $ob_ide["id"];
							$conexion->query("UPDATE estudiante SET representante=$id_representante, primer_nombre='$e1n', segundo_nombre='$e2n', primer_apellido='$e1a', segundo_apellido='$e2a', habilitado=2 WHERE id = $id_estudiante");
						}else if($resultado_deshabilitado_cedulado->num_rows == 0 && $resultado_deshabilitado_no_cedulado->num_rows == 0){
							if($estudiante_cedulado == 0){
								$conexion->query("INSERT INTO no_cedulado () VALUES ()");
								$id_no_cedulado = $conexion->insert_id;
								$ec = "X-" . $id_no_cedulado;
							}
							$id_comprador = $entidad->registrarComprador("estudiante");
							$conexion->query("INSERT INTO estudiante (cedula, representante, tipo_comprador, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, cedulado, habilitado) VALUES ('$ec', $id_representante, $id_comprador, '$e1n', '$e2n', '$e1a', '$e2a', $estudiante_cedulado, 2)");
										
							$id_estudiante = $conexion->insert_id;
						}else if($resultado_deshabilitado_cedulado->num_rows == 0 && $resultado_deshabilitado_no_cedulado->num_rows == 1){
							$ob_ide = $resultado_deshabilitado_no_cedulado->fetch_assoc();
							$id_estudiante = $ob_ide["id"];
							if($estudiante_cedulado == 0){
								$conexion->query("UPDATE estudiante SET representante=$id_representante, primer_nombre='$e1n', segundo_nombre='$e2n', primer_apellido='$e1a', segundo_apellido='$e2a', habilitado=2 WHERE id = $id_estudiante");
							}else{
								$conexion->query("UPDATE estudiante SET cedula='$ec', representante=$id_representante, primer_nombre='$e1n', segundo_nombre='$e2n', primer_apellido='$e1a', segundo_apellido='$e2a', habilitado=2, cedulado=$estudiante_cedulado WHERE id = $id_estudiante");
							}
						}
								
						$id_tipo_estudiante = $entidad->registrarTipoEstudiante("estudiante_deudor_antiguo");
						
						$conexion->query("INSERT INTO estudiante_deudor_antiguo (estudiante, tipo_estudiante, habilitado) VALUES ($id_estudiante, $id_tipo_estudiante, 1)");
						$id_estudiante_duedor_antiguo = $conexion->insert_id;

						for($i = $mes_desde; $i <= $mes_hasta; $i++){
							$conexion->query("INSERT INTO tipo_deuda_mes (estado_pago, diferencia) VALUES (3,0)");
							$id_tipo_deuda = $conexion->insert_id;

							$resultado = $conexion->query("INSERT INTO deuda_antigua (estudiante_deudor_antiguo, mes, tipo_deuda_mes) VALUES ($id_estudiante_duedor_antiguo, $i, $id_tipo_deuda)");
						}
						
						$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario, 'REGISTRO DE ESTUDIANTE, DEUDOR ANTIGUO',NOW(),NOW())");
						$mensaje = "¡ESTUDIANTE REGISTRADO EXITOSAMENTE!";
						$advertencia = 1;
						$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
						$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $datos_json;
					}else{
						//7.
						$mensaje = "¡No se han registrado Mensualidades para el Año Escolar Actual!";
						$advertencia = 2;
						$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
						$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $datos_json;
					}
				}
			}else{
				if($estudiante_cedulado == 1){
					echo "¡Estudiante con Cedula ya Existente!";
				}else{
					echo "¡Estudiante con Nombres y Apellidos Completos ya Existente!";
				}
			}
	    }
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>