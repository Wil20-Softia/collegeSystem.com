<?php
	/*
		> DESCRIPCION:
		* La Lista de Morosos: aquella en la cual se mostraran todos los estudiantes
		que deban hasta el mes actual.
	
	*/
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		include "../../config/Plantilla.php";
		require "../../config/EntidadBase.php";

		setlocale(LC_ALL,'es_VE.UTF-8');
		date_default_timezone_set ("America/Caracas"); 
		$fecha_caducidad = date("Y") . "-" . date("m") . "-28";
		$fecha_caducidad = reg_date($fecha_caducidad);
		$fecha_actual_escrita = date("Y") . "-" . date("m") . "-" . date("d");
	    $fecha_actual_escrita = reg_date($fecha_actual_escrita);
	    $aa = (int)date("Y");
		$ma = (int)date("m");

		$datos = array();

		$entidad = new EntidadBase("momento_estudiante");

		$conexion = $entidad->db();
		$disponible = 0;

		$mesActual = mes_actual_ordernado($ma);
		$id_periodo = $entidad->obtener_Id_periodoActual();

		if($mesActual == 11 || $mesActual == 12){
			$id_periodo -= 1;
		}
		
		//SECCION PARA EL PDF DEL REPORTE:
		$pdf = new PDF(); //CREANDO LA INSTANCIA DE LA CLASE PDF
		
		$periodo_actual = $entidad->obtener_periodoActual();
		//VARIABLE PARA COLOCAR EL PERIODO DEL AÑO ESCOLAR ACTUAL.	
		$pdf->year_inicia = $periodo_actual["yearDesde"];
		$pdf->year_termina = $periodo_actual["yearHasta"];

		//TOMAR TODAS LAS SECCIONES ESPECIFICAS QUE EXISTEN EN EL AÑO ESCOLAR ACTUAL.
		$res = $conexion->query("
					SELECT 
						seccion_especifica 
					FROM momento_estudiante 
					WHERE 
						periodo_escolar = $id_periodo 
					GROUP BY seccion_especifica 
					ORDER BY seccion_especifica ASC");

		//SI EXISTEN SECCIONES ESPECIFICA EN EL AÑO ESCOLAR ACTUAL.
		if($res->num_rows > 0){
			while($row = $res->fetch_assoc()){
				$seccion_especifica = $row["seccion_especifica"];

				$resultadoDIns = $conexion->query(
						"
						SELECT 
							momento_estudiante.id AS deudores
						FROM momento_estudiante 
						LEFT JOIN tipo_deuda_inscripcion ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id
						WHERE 
							(tipo_deuda_inscripcion.estado_pago = 3 OR tipo_deuda_inscripcion.estado_pago = 2) AND 
							momento_estudiante.seccion_especifica = $seccion_especifica AND 
							momento_estudiante.periodo_escolar = $id_periodo
                        GROUP BY momento_estudiante.id"
				);
				$deudores_ins = $resultadoDIns->num_rows;
				
				//CANTIDAD DE DEUDORES DE LA SECCIÓN.
				$resultado = $conexion->query("
						SELECT 
							COUNT(*) AS deudores 
						FROM momento_estudiante 
						INNER JOIN deuda_meses ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id
						INNER JOIN mes ON meses_periodo.mes = mes.id
						WHERE 
							mes.id = $mesActual AND 
							(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2) AND 
							momento_estudiante.seccion_especifica = $seccion_especifica AND 
							momento_estudiante.periodo_escolar = $id_periodo");
				$deu = $resultado->fetch_assoc();
				$deudores_mens = (int)$deu["deudores"];

				if($deudores_ins == 0){
					if($deudores_mens == 0){
						continue;
					}
					$deudores = $deudores_mens;
				}
				if($deudores_mens != 0){
					$deudores = $deudores_mens;
				}else{
					$deudores = $deudores_ins;
				}

				$pdf->AddPage();
				//CANTIDAD DE ESTUDIANTES DE LA SECCIÓN.
				$resultado = $conexion->query("
						SELECT 
							COUNT(*) AS total_seccion 
						FROM momento_estudiante 
						WHERE 
							seccion_especifica = $seccion_especifica AND 
							periodo_escolar = $id_periodo");
				$ts = $resultado->fetch_assoc();
				$total_seccion = (int)$ts["total_seccion"];

				if($total_seccion >= $deudores){
					$porcentaje_deudor = ($deudores*100)/$total_seccion;
				}else{
					$porcentaje_deudor = 0.01;
				}

				$porcentaje_deudor = round($porcentaje_deudor,2);

				$pdf->SetFont('Arial','',9);
				$pdf->Cell(80,4,utf8_decode("Emitido el: ".$fecha_actual_escrita),0,1,'L');
				$pdf->Ln(2);

				$pdf->Cell(0,4,utf8_decode("Deudores Actuales: ".formatearNumerico($porcentaje_deudor)."% de la Población de ésta sección."),0,1,'L');

				$pdf->Ln(2);

				$resultado = $conexion->query("
					SELECT 
						porcentaje 
					FROM mora 
					WHERE 1 
					ORDER BY id DESC 
					LIMIT 0,1");
				$pm = $resultado->fetch_assoc();
				$porcentaje_mora = formatearNumerico($pm["porcentaje"]);

				$pdf->Cell(0,4,utf8_decode("Porcentaje Actual por Mora: ".$porcentaje_mora."%"),0,1,'L');

				$pdf->Ln(2);

				$resultado = $conexion->query("
						SELECT 
							id, 
							MONTH(fecha_registrado) AS mes, 
							monto 
						FROM mensualidad 
						WHERE 1 
						ORDER BY id DESC 
						LIMIT 0,1");
				$datos_mensualidad = $resultado->fetch_assoc();
				$id_ultima_mensualidad = $datos_mensualidad["id"];
				$ultimo_mes_mensualidad = $datos_mensualidad["mes"];
				
				$monto_mensualidad = round($datos_mensualidad["monto"],2);

				$monto_ultima_mensualidad = formatearNumerico($monto_mensualidad);

				$resultado = $conexion->query("
						SELECT 
							monto 
						FROM mensualidad 
						WHERE 
							id = $id_ultima_mensualidad - 1");
				$datos_mensualidad_ant = $resultado->fetch_assoc();
				$monto_mensualidad_anterior = formatearNumerico(round($datos_mensualidad_ant["monto"],2));

				$pdf->Cell(90,4,utf8_decode("Mensualidad desde ".reg_date($ultimo_mes_mensualidad)." de: Bs. ".$monto_ultima_mensualidad),0,0,'L');
				$pdf->Cell(0,4,utf8_decode("Mensualidad anterior a ésta de: Bs. ".$monto_mensualidad_anterior),0,1,'R');
				$pdf->Ln(2);

				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(0,4,utf8_decode("Montos Inscripción"),0,1,'L');
				$pdf->SetFont('Arial','',9);

				$resu = $conexion->query("
						SELECT
							id,
							SUBSTRING(tipo,1,1) AS tipo, 
							monto 
						FROM tipo_inscripcion 
						WHERE periodo_escolar = $id_periodo ORDER BY id ASC");
				$montos_inscripcion_cadena = "";

				while($montos_inscripcion = $resu->fetch_assoc()){
					$montos_inscripcion_cadena .= "(".$montos_inscripcion["tipo"].". ".$montos_inscripcion["id"]."): Bs. ".formatearNumerico($montos_inscripcion["monto"])." | ";
				}
				//$montos_inscripcion_cadena = substr($montos_inscripcion_cadena, 0, -3);
				$montos_inscripcion_cadena = trim($montos_inscripcion_cadena, ' | ');

				$pdf->Cell(0,4,utf8_decode($montos_inscripcion_cadena),0,1,'L');
				$pdf->Ln(2);

				$resultado = $conexion->query("
						SELECT 
							grado.nombre AS grado, 
							seccion.nombre AS seccion 
						FROM seccion_especifica 
						INNER JOIN grado ON seccion_especifica.grado = grado.id 
						INNER JOIN seccion ON seccion_especifica.seccion = seccion.id 
						WHERE 
							seccion_especifica.id = $seccion_especifica");
				$datos_secc_esp = $resultado->fetch_assoc();
				
				$grado = $datos_secc_esp["grado"];
				$sección = $datos_secc_esp["seccion"];

				$pdf->SetTextColor(0);
				$pdf->SetDrawColor(122,122,122);
				$pdf->SetFont('Arial','B',14);
				$pdf->Cell(0,7,utf8_decode("Morosos " . $grado . " - Sección " . $sección),0,1,'L');
				
				//CABECERA DE LA TABLA PARA LOS MOROSOS.
				$pdf->SetX(7);
				$pdf->SetFont('Arial','',11);
				$pdf->Cell(32,8,"Nombre",1,0,"C");
				$pdf->Cell(32,8,"Apellido",1,0,"C");
				$pdf->Cell(38,8,utf8_decode("Deuda Inscripción"),1,0,"C");
				$pdf->Cell(72,4,"Deuda Mensualidad",1,0,"C");
				$pdf->Cell(32,8,"Total Bruto",1,0,"C");

				$pdf->Cell(0,4,"",0,1,"L");
				$pdf->SetX(7);
				$pdf->Cell(102,4,"",0,0,"C");
				$pdf->Cell(6,4,"S",1,0,"C");
				$pdf->Cell(6,4,"O",1,0,"C");
				$pdf->Cell(6,4,"N",1,0,"C");
				$pdf->Cell(6,4,"D",1,0,"C");
				$pdf->Cell(6,4,"E",1,0,"C");
				$pdf->Cell(6,4,"F",1,0,"C");
				$pdf->Cell(6,4,"M",1,0,"C");
				$pdf->Cell(6,4,"A",1,0,"C");
				$pdf->Cell(6,4,"M",1,0,"C");
				$pdf->Cell(6,4,"J",1,0,"C");
				$pdf->Cell(6,4,"J",1,0,"C");
				$pdf->Cell(6,4,"A",1,0,"C");
				$pdf->Cell(32,4,"",0,1,"C");
				//FIN DE LA SECCIÓN.

				if($deudores_mens != 0){
					//OBTENCION DE TODOS LOS ESTUDIANTES DE LA SECCION CON SUS DEUDAS RESPECTIVAS.
					$result = $conexion->query("
						SELECT 
							momento_estudiante.id AS id_estudiante,
					    	CONCAT(estudiante.primer_nombre,' ',estudiante.segundo_nombre) AS nombre, 
					    	CONCAT(estudiante.primer_apellido,' ',estudiante.segundo_apellido) AS apellido 
						FROM momento_estudiante 
						INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id 
						INNER JOIN deuda_meses ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id 
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
						INNER JOIN mes ON meses_periodo.mes = mes.id 
						WHERE 
							momento_estudiante.seccion_especifica = $seccion_especifica AND 
						    momento_estudiante.periodo_escolar = $id_periodo AND 
						    mes.id = $mesActual AND 
						    (tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2) 
					    GROUP BY momento_estudiante.id 
					    ORDER BY estudiante.primer_apellido ASC");
				}else if($deudores_ins != 0){
					$result = $conexion->query("
						SELECT 
							momento_estudiante.id AS id_estudiante,
					    	CONCAT(estudiante.primer_nombre,' ',estudiante.segundo_nombre) AS nombre, 
					    	CONCAT(estudiante.primer_apellido,' ',estudiante.segundo_apellido) AS apellido
						FROM momento_estudiante
						INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
						LEFT JOIN tipo_deuda_inscripcion ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id
						WHERE 
							(tipo_deuda_inscripcion.estado_pago = 3 OR tipo_deuda_inscripcion.estado_pago = 2) AND 
							momento_estudiante.seccion_especifica = $seccion_especifica AND 
							momento_estudiante.periodo_escolar = $id_periodo
                        GROUP BY momento_estudiante.id");
				}

				while($filas = $result->fetch_assoc()){
					$id_estudiante = $filas["id_estudiante"];
					$nombre_estudiante = $filas["nombre"];
					$apellido_estudiante = $filas["apellido"];

					$pdf->SetX(7);
					$pdf->SetFont('Arial','',9);
					encogimientoTexto($pdf, 9, 32, 5, utf8_decode($nombre_estudiante), 1, 0, "C");
					encogimientoTexto($pdf, 9, 32, 5, utf8_decode($apellido_estudiante), 1, 0, "C");

					//BUSQUEDA DE LOS MONTOS DE INSCRIPCION QUE DEBE
					//PETICION PARA EL TOTAL EN DEUDA DE LOS MONTOS DE LAS INSCRIPCIONES
					$obtencion = $conexion->query("
						SELECT
							tipo_inscripcion.id,
							SUBSTRING(tipo_inscripcion.tipo,1,1) AS tipo, 
							tipo_deuda_inscripcion.estado_pago, 
							tipo_deuda_inscripcion.diferencia, 
							tipo_inscripcion.monto 
						FROM momento_estudiante 
						INNER JOIN tipo_deuda_inscripcion ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id 
						INNER JOIN tipo_inscripcion ON tipo_deuda_inscripcion.tipo_inscripcion = tipo_inscripcion.id 
						WHERE 
							momento_estudiante.id = $id_estudiante AND 
							(tipo_deuda_inscripcion.estado_pago = 2 OR 
							tipo_deuda_inscripcion.estado_pago = 3) 
						ORDER BY tipo_inscripcion.id ASC");

					$total_mi = 0;
					if($obtencion->num_rows > 0){
						$cadena_montos = "";
						while($registro = $obtencion->fetch_assoc()){
							$cadena_montos .= $registro["tipo"].". ".$registro["id"]." | ";
							if($registro["estado_pago"] == 2){
								$total_mi += (float)$registro["diferencia"];
							}else if($registro["estado_pago"] == 3){
								$total_mi += (float)$registro["monto"];
							}
						}
						$total_mi = round($total_mi,2);
						$cadena_montos = trim($cadena_montos, ' | ');
						encogimientoTexto($pdf, 9, 38, 5, "(".$cadena_montos . "): ".formatearNumerico(round($total_mi,2)), 1, 0, "C");
					}else{
						$pdf->Cell(38,5," ",1,0,"C");
					}

					//OBTENER TODOS LOS MESES CON SU ESTADO DE PAGO.
					//PETICION PARA EL TOTAL EN DEUDA DE LOS MESES HASTA EL MES ACTUAL.
					$respta = $conexion->query("
						SELECT 
							mes.id AS mes, 
							tipo_deuda_mes.estado_pago, 
							tipo_deuda_mes.diferencia 
						FROM momento_estudiante 
						INNER JOIN deuda_meses ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id 
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
						INNER JOIN mes ON meses_periodo.mes = mes.id  
						WHERE 
							momento_estudiante.id = $id_estudiante
						ORDER BY mes.id ASC");
					$total_mm = 0;
					if($respta->num_rows > 0){
						while($get_meses = $respta->fetch_assoc()){
							//SI EL MES ES MENOR O IGUAL AL ACTUAL
							if($get_meses["estado_pago"] == 1 || $get_meses["estado_pago"] == 4){
								$pdf->Cell(6,5,"*",1,0,"C");
							}else{
								if($get_meses["mes"] <= $mesActual){
									if($get_meses["estado_pago"] == 2){
										$total_mm += (float)$get_meses["diferencia"];
										$pdf->Cell(6,5,"?",1,0,"C");
									}else if($get_meses["estado_pago"] == 3){
										$total_mm += $monto_mensualidad;
										$pdf->Cell(6,5,"X",1,0,"C");
									}else{
										$pdf->Cell(6,5,"...",1,0,"C");
									}
								}else{
									$pdf->Cell(6,5,"...",1,0,"C");
								}
							}
						}
						$total_mm = round($total_mm,2);
					}				

					//SUMAR LOS TOTALES OBTENIDOS Y FORMATEARLOS PARA MOSTRAR
					$pdf->Cell(32,5,formatearNumerico(round($total_mi+$total_mm,2)),1,1,"C");
				}
			}
		}else{
			//ADVERTIR QUE NO HAY SECCIONES PARA ESTE PERIODO ESCOLAR.
			//QUE REGISTRE A LOS ESTUDIANTE PARA PROCEDER.
			$pdf->AddPage();
			$pdf->Ln(30);
			$pdf->SetFont('Arial','BI',11);
			$pdf->Cell(0,10,utf8_decode("¡NO SE HAN ENCONTRADO SECCIONES RESPECTIVAS AL PERIODO ESCOLAR ACTUAL!"),1,1,'C');
		}

		$pdf->Output('','reporte_morosos.pdf');
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>