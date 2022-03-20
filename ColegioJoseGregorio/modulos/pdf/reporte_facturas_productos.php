<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		include "../../config/Plantilla.php";
		require "../../config/EntidadBase.php";

		setlocale(LC_ALL,'es_VE.UTF-8');
		date_default_timezone_set ("America/Caracas"); 
	    $aa = (int)date("Y");
		$ma = (int)date("m");

		$advertencia = 0;
		$mensaje = "";
		$datos = array();

		$entidad = new EntidadBase("factura_producto");

		$conexion = $entidad->db();
		
		$nombre_usuario = $_SESSION["nombre_completo"];
		$fecha_desde = $_GET["fecha_desde"];
		$fecha_hasta = $_GET["fecha_hasta"];

		$fecha_actual = strtotime(date("Y-m-d"));

		if(empty($fecha_desde) && empty($fecha_hasta)){
			$mensaje = "INTRODUZCA LAS FECHAS PARA CONTINUAR!";
			$advertencia = 1;
		}else if(!empty($fecha_desde) && !empty($fecha_hasta)){
			if(strtotime($fecha_desde) > $fecha_actual){
				$mensaje = "LA FECHA DE INICIO NO DEBE SER MAYOR A LA ACTUAL";
				$advertencia = 1;
			}else if(strtotime($fecha_hasta) < strtotime($fecha_desde)){
				$mensaje = "LA FECHA FINAL NO DEBE SER MENOR A LA FECHA DE INICIO";
				$advertencia = 1;
			}else if(strtotime($fecha_hasta) > $fecha_actual){
				$fecha_hasta = date("Y-m-d");
			}
		}else if(!empty($fecha_desde) && empty($fecha_hasta)){
			if(strtotime($fecha_desde) > $fecha_actual){
				$mensaje = "LA FECHA DE INICIO NO DEBE SER MAYOR A LA ACTUAL";
				$advertencia = 1;
			}else{
				$fecha_hasta = date("Y-m-d");
			}
		}else if(empty($fecha_desde) && !empty($fecha_hasta)){
			$mensaje = "POR LO MENOS, DEBE HABER UNA FECHA DE INICIO.";
			$advertencia = 1;
		}

		//SECCION PARA EL PDF DEL REPORTE: 
		$pdf = new PDF(); //CREANDO LA INSTANCIA DE LA CLASE PDF
		$entidad->establecerYearActual();
		$entidad->establecerMesActual();
		$periodo_escolar = $entidad->obtener_periodoActual();
		$pdf->year_inicia = $periodo_escolar["yearDesde"];
		$pdf->year_termina = $periodo_escolar["yearHasta"];

		if($advertencia == 0){
			$res = $conexion->query("
						SELECT 
							fecha
						FROM tipo_factura
						WHERE 
							tipo = 'p' AND
							fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' 
						GROUP BY fecha
						ORDER BY fecha ASC");

			//SI EXISTEN FACTURAS EN ALGUNAS DE LAS FECHAS DEL RANGO
			if($res->num_rows > 0){
				$fecha_desde_escrita = reg_date($fecha_desde);
				$fecha_hasta_escrita = reg_date($fecha_hasta);

				$pdf->AddPage();
				$pdf->SetMargins(10,10,10);
				$pdf->SetAutoPageBreak(true, 15);

				$pdf->SetFont('Arial','',12);
				$pdf->Cell(0,5,utf8_decode("Reporte de Facturaciones de Venta de Productos."),0,1,'L');
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(80,5,utf8_decode("Desde el: ".$fecha_desde_escrita),0,0,'L');
				$pdf->Cell(0,5,utf8_decode("Hasta el: ".$fecha_hasta_escrita),0,1,'R');
				$pdf->SetFont('Arial','',12);
				$pdf->Cell(0,5,utf8_decode("Realizado por (Administrador(a)): ".$nombre_usuario),0,1,'L');
				$pdf->Ln(5);
				while($row = $res->fetch_assoc()){
					$fecha = $row["fecha"];

					//TITULO POR DIA
					$pdf->SetFont('Arial','BI',12);
					$pdf->Cell(0,5,utf8_decode("Día: ".reg_date($fecha)),0,1,'L');
					$pdf->Ln(2);
					$total_diario = 0;
					$total_efectivo = 0;
					$total_transferencia = 0;
					$total_punto_venta = 0;
					$total_cheque = 0;
					$total_nomina = 0;

					for($i = 1; $i <= 5; $i++){
						$resultado = $conexion->query("
							SELECT 
								SUM(pago_factura.monto) AS total 
							FROM tipo_factura 
							INNER JOIN pago_factura ON pago_factura.tipo_factura = tipo_factura.id 
							WHERE 
								pago_factura.tipo_pago = $i AND 
								tipo_factura.fecha = '$fecha' AND
								tipo_factura.tipo = 'p'
						");
						$rarray = array();
						$rarray = $resultado->fetch_assoc();
						$tipo_total_pago = $rarray["total"];
						if(is_null($tipo_total_pago)){
							$tipo_total_pago = 0;
						}

						if($i==1) {
							$total_efectivo = $tipo_total_pago;
						}else if($i == 2){
							$total_transferencia = $tipo_total_pago;
						}else if($i == 3){
							$total_punto_venta = $tipo_total_pago;
						}else if($i == 4){
							$total_cheque = $tipo_total_pago;
						}else if($i == 5){
							$total_nomina = $tipo_total_pago;
						}

						$total_diario += $tipo_total_pago;
					}

					$resultado = $conexion->query("
						SELECT 
							SUM(factura_producto.diferencia) AS diferencia_total 
						FROM tipo_factura 
						INNER JOIN factura_producto ON factura_producto.tipo_factura = tipo_factura.id 
						WHERE 
							tipo_factura.fecha = '$fecha' AND
							tipo_factura.tipo = 'p'");
					$rarray = array();
					$rarray = $resultado->fetch_assoc();
					$total_diferencia = $rarray["diferencia_total"];

					$total_real_diario = $total_diario - $total_diferencia;

					//CABECERA DE LA TABLA PARA LAS FACTURAS.
					$pdf->SetX(7);
					$pdf->SetFont('Arial','B',11);
					$pdf->Cell(45,5,"Id",1,0,"C");
					$pdf->Cell(42,5,"Cliente",1,0,"C");
					$pdf->Cell(35,5,utf8_decode("Hora"),1,0,"C");
					$pdf->Cell(40,5,"Cant. Productos",1,0,"C");
					$pdf->Cell(44,5,"Total",1,1,"C");

					//OBTENCION DE TODAS LAS FACTURAS DEL DIA CONSULTADO.
					$result = $conexion->query("
						SELECT
							factura_producto.id,
							factura_producto.cantidad_productos,
							tipo_factura.monto_total,
							tipo_factura.hora,
							tipo_factura.cliente
						FROM factura_producto
						INNER JOIN tipo_factura ON factura_producto.tipo_factura = tipo_factura.id
						INNER JOIN usuario ON tipo_factura.usuario = usuario.id
						WHERE
							tipo_factura.fecha = '$fecha' AND
							tipo_factura.tipo = 'P'
						ORDER BY factura_producto.id ASC
					");

					$pdf->SetFont('Arial','',10);
					while($filas = $result->fetch_assoc()){
						$id_cliente = $filas["cliente"];
						$re = $conexion->query("SELECT tipo, id FROM tipo_comprador WHERE cliente = $id_cliente");
						$datos_comprador = $re->fetch_assoc();

						$tipo_comprador = $datos_comprador["tipo"];
						$id_comprador = $datos_comprador["id"];

						$res = $conexion->query("SELECT cedula FROM $tipo_comprador WHERE tipo_comprador = $id_comprador");
						$cedula_cliente = $res->fetch_assoc();
						$cedula_cliente = $cedula_cliente["cedula"];

						$pdf->SetX(7);
						$pdf->Cell(45,5,$filas["id"],1,0,"C");
						$pdf->Cell(42,5,$cedula_cliente,1,0,"C");
						$pdf->Cell(35,5,utf8_decode(date('h:i:s a', strtotime($filas["hora"]))),1,0,"C");
						$pdf->Cell(40,5,$filas["cantidad_productos"],1,0,"C");
						$pdf->Cell(44,5,formatearNumerico(round($filas["monto_total"],2)),1,1,"C");
					}
					$pdf->Ln(1);
					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Total Efectivo:"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,3,utf8_decode(formatearNumerico(round($total_efectivo,2))),0,1,"R");

					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Total Transferencia:"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,3,utf8_decode(formatearNumerico(round($total_transferencia,2))),0,1,"R");

					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Total Punto de Venta:"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,3,utf8_decode(formatearNumerico(round($total_punto_venta,2))),0,1,"R");

					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Total Depositos (Cheques):"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,3,utf8_decode(formatearNumerico(round($total_cheque,2))),0,1,"R");

					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Total Pagos o Descuento Nomina:"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(3,3,"+","B",0);
					$pdf->Cell(32,3,utf8_decode(formatearNumerico(round($total_nomina,2))),"B",1,"R");

					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Total Bruto del Día:"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,3,utf8_decode(formatearNumerico(round($total_diario,2))),0,1,"R");

					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Diferencia Total:"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(3,3,"-","B",0);
					$pdf->Cell(32,3,utf8_decode(formatearNumerico(round($total_diferencia,2))),"B",1,"R");

					$pdf->SetFont('Arial','B',8);
					$pdf->Cell(100,3,"",0,0);
					$pdf->Cell(50,3,utf8_decode("Total Neto del Día:"),0,0,"L");
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(35,3,utf8_decode(formatearNumerico(round($total_real_diario,2))),0,1,"R");

					$pdf->Ln(5);
				}
			}else{
				//ADVERTIR QUE NO HAY SECCIONES PARA ESTE PERIODO ESCOLAR.
				//QUE REGISTRE A LOS ESTUDIANTE PARA PROCEDER.
				$pdf->AddPage();
				$pdf->Ln(30);
				$pdf->SetFont('Arial','BI',11);
				$pdf->Cell(0,10,utf8_decode("¡NO SE HAN ENCONTRADO FACTURAS EN EL RANGO DE FECHAS!"),1,1,'C');
			}
		}else{
			//ENVIAR EL MENSAJE ERROR ENCONTRADO ANTERIORMENTE.
			$pdf->AddPage();
			$pdf->Ln(30);
			$pdf->SetFont('Arial','BI',11);
			$pdf->Cell(0,10,utf8_decode($mensaje),1,1,'C');
		}

		$pdf->Output('','reporte_facturas_estudiantes.pdf');
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>