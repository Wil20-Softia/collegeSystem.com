<?php	
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require "../../config/Factura.php";
		require "../../config/EntidadBase.php";

		$entidad = new EntidadBase("tipo_factura");
		$conexion = $entidad->db();

		$data_tp = array();
		$data_mp = array();
		$porcentaje_mora = 0;
		
		$fact = new Factura("estudiante",0,"pequeña");

		if(isset($_GET["if"]) && !empty($_GET["if"])){

			$cf = $_GET["if"];

			$r = $conexion->query("  
						SELECT 
							tipo_estudiante.tipo
						FROM tipo_factura
						INNER JOIN cliente ON tipo_factura.cliente = cliente.id
						INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id
						WHERE
							tipo_factura.id = $cf
			");

			if($r->num_rows > 0){
				$tpe = $r->fetch_assoc();
				$tipo_estudiante = $tpe["tipo"];

				$fact->establecerTipoFactura("Mensualidad");
				$fact->establecerCodigo($cf);

				if($tipo_estudiante == "momento_estudiante"){
					$r = $conexion->query("
							SELECT 
								tipo_factura.fecha, 
								tipo_factura.hora, 
								tipo_factura.monto_total,
								CONCAT(estudiante.primer_nombre,' ',SUBSTRING(estudiante.segundo_nombre,1,1),'.',' ',estudiante.primer_apellido,' ',SUBSTRING(estudiante.segundo_apellido,1,1),'.') AS nombre_estudiante,
								estudiante.cedulado,
								estudiante.cedula AS cedula_estudiante,
								CONCAT(representante.primer_nombre,' ',SUBSTRING(representante.segundo_nombre,1,1),'.',' ',representante.primer_apellido,' ',SUBSTRING(representante.segundo_apellido,1,1),'.') AS nombre_representante,
								representante.cedula AS cedula_representante,
								grado.nombre AS grado,
								seccion.nombre AS seccion,
								CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS nombre_usuario,
								factura_normal.total_mora AS dinero_mora,
								factura_normal.subtotal,
								factura_normal.diferencia AS diferencia_factura  
							FROM tipo_factura
							INNER JOIN factura_normal ON factura_normal.tipo_factura = tipo_factura.id
							INNER JOIN usuario ON tipo_factura.usuario = usuario.id
							INNER JOIN cliente ON tipo_factura.cliente = cliente.id
							INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id 
							INNER JOIN $tipo_estudiante ON $tipo_estudiante.tipo_estudiante = tipo_estudiante.id
							INNER JOIN estudiante ON $tipo_estudiante.estudiante = estudiante.id
							INNER JOIN representante ON estudiante.representante = representante.id
							INNER JOIN seccion_especifica ON $tipo_estudiante.seccion_especifica = seccion_especifica.id
							INNER JOIN grado ON seccion_especifica.grado = grado.id
							INNER JOIN seccion ON seccion_especifica.seccion = seccion.id
							WHERE 
								tipo_factura.id = $cf");
					$datosFactura = $r->fetch_assoc();
					
					$fact->establecerGradoSeccion($datosFactura["grado"], $datosFactura["seccion"]);

					$resultado = $conexion->query("
						SELECT 
							tipo_deuda_mes.id AS id_mes, 
							mes.nombre AS nombre_mes, 
							meses_pagos.estado_pago, 
							meses_pagos.diferencia, 
							meses_pagos.abonado, 
							mensualidad.monto AS monto_mensualidad,
							mora.porcentaje AS porcentaje_mora,
							meses_pagos.dias_mora 
						FROM meses_pagos 
						INNER JOIN mora ON meses_pagos.mora = mora.id
						INNER JOIN tipo_deuda_mes ON meses_pagos.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id 
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
						INNER JOIN mes ON meses_periodo.mes = mes.id 
						INNER JOIN factura_normal ON meses_pagos.factura = factura_normal.id 
						INNER JOIN mensualidad ON factura_normal.mensualidad = mensualidad.id 
						WHERE 
							factura_normal.tipo_factura = $cf");
				}else if($tipo_estudiante == "estudiante_deudor_antiguo"){
					$r = $conexion->query("
							SELECT 
								tipo_factura.fecha, 
								tipo_factura.hora, 
								tipo_factura.monto_total,
								CONCAT(estudiante.primer_nombre,' ',SUBSTRING(estudiante.segundo_nombre,1,1),'.',' ',estudiante.primer_apellido,' ',SUBSTRING(estudiante.segundo_apellido,1,1),'.') AS nombre_estudiante,
								estudiante.cedulado,
								estudiante.cedula AS cedula_estudiante,
								CONCAT(representante.primer_nombre,' ',SUBSTRING(representante.segundo_nombre,1,1),'.',' ',representante.primer_apellido,' ',SUBSTRING(representante.segundo_apellido,1,1),'.') AS nombre_representante,
								representante.cedula AS cedula_representante,
								CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS nombre_usuario,
								factura_normal.total_mora AS dinero_mora,
								factura_normal.subtotal,
								factura_normal.diferencia AS diferencia_factura  
							FROM tipo_factura
							INNER JOIN factura_normal ON factura_normal.tipo_factura = tipo_factura.id
							INNER JOIN usuario ON tipo_factura.usuario = usuario.id
							INNER JOIN cliente ON tipo_factura.cliente = cliente.id
							INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id 
							INNER JOIN $tipo_estudiante ON $tipo_estudiante.tipo_estudiante = tipo_estudiante.id
							INNER JOIN estudiante ON $tipo_estudiante.estudiante = estudiante.id
							INNER JOIN representante ON estudiante.representante = representante.id
							WHERE 
								tipo_factura.id = $cf");
					
					$fact->establecerGradoSeccion("S/N", "S/N");
					$datosFactura = $r->fetch_assoc();

					$resultado = $conexion->query("
						SELECT 
							tipo_deuda_mes.id AS id_mes, 
							mes.nombre AS nombre_mes, 
							meses_pagos.estado_pago, 
							meses_pagos.diferencia, 
							meses_pagos.abonado, 
							mensualidad.monto AS monto_mensualidad,
							mora.porcentaje AS porcentaje_mora,
							meses_pagos.dias_mora 
						FROM meses_pagos 
						INNER JOIN mora ON meses_pagos.mora = mora.id
						INNER JOIN tipo_deuda_mes ON meses_pagos.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN deuda_antigua ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN mes ON deuda_antigua.mes = mes.id 
						INNER JOIN factura_normal ON meses_pagos.factura = factura_normal.id 
						INNER JOIN mensualidad ON factura_normal.mensualidad = mensualidad.id 
						WHERE 
							factura_normal.tipo_factura = $cf");
				}

				$fact->establecerFechaCompleta($datosFactura["fecha"],$datosFactura["hora"]);
				$fact->establecerNombreEstudiante($datosFactura["nombre_estudiante"]);
				$fact->establecerCedulaEstudiante($datosFactura["cedula_estudiante"], $datosFactura["cedulado"]);
				$fact->establecerNombreRepresentante($datosFactura["nombre_representante"]);
				$fact->establecerCedulaRepresentante($datosFactura["cedula_representante"]);
				$fact->establecerNombreUsuario($datosFactura["nombre_usuario"]);
				$fact->establecerSubtotal($datosFactura["subtotal"]);
				$fact->establecerDineroMora($datosFactura["dinero_mora"]);
				$fact->establecerMontoTotal($datosFactura["monto_total"]);
				$fact->establecerDiferenciaFactura($datosFactura["diferencia_factura"]);

				if($resultado->num_rows > 0){
					while($row = $resultado->fetch_assoc()){
						$id_mes = $row["id_mes"];
						$nombre_mes = $row["nombre_mes"];
						$estado_pago = $row["estado_pago"];
						$diferencia = $row["diferencia"];
						$abonado = $row["abonado"];
						$monto_mensualidad = $row["monto_mensualidad"];
						$mora = (float)$row["porcentaje_mora"] * (int)$row["dias_mora"];
						$porcentaje_mora = (float)$row["porcentaje_mora"];

						if($estado_pago == 1){
							$r = $conexion->query("
								SELECT 
									factura_normal.tipo_factura AS factura
								FROM meses_pagos 
								INNER JOIN factura_normal ON meses_pagos.factura = factura_normal.id 
								WHERE 
									meses_pagos.tipo_deuda_mes = $id_mes 
								ORDER BY factura_normal.id DESC");
							if($r->num_rows > 1 && $diferencia > 0){
								$i = 1;
								while($fila = $r->fetch_assoc()){
									if($fila["factura"] == $cf && $i == 1){
										$cancelado = 0;
										$abonado = 0;
										$diferencia = $diferencia;
										break;
									}else{
										$cancelado = 0;
										$abonado = $abonado;
										$diferencia = 0;
									}
									$i++;
								}
							}else if($r->num_rows == 1 && $diferencia == 0){
								$cancelado = $monto_mensualidad;
								$abonado = 0;
								$diferencia = 0;
							}
						}else if($estado_pago == 2){
							$cancelado = 0;
							$abonado = $abonado;
							$diferencia = 0;
						}

						$data_mp[] = array(
							$nombre_mes,
							$monto_mensualidad,
							$cancelado,
							$abonado,
							$diferencia,
							$mora
						);
					}

					$fact->establecerPorcentajeMora($porcentaje_mora);

					$fact->insertarMontosPago($data_mp);
					
					$resultado = $conexion->query("
						SELECT 
							tipo_pago.nombre AS tipo_pago, 
							pago_factura.referencia, 
							pago_factura.monto 
						FROM pago_factura
						INNER JOIN tipo_pago ON pago_factura.tipo_pago = tipo_pago.id 
						WHERE 
							pago_factura.tipo_factura = $cf");
					if($resultado->num_rows > 0){
						while($d = $resultado->fetch_assoc()){
							$data_tp[] = array(
								$d["tipo_pago"],
								$d["referencia"],
								$d["monto"]
							);
						}
						$fact->insertarTiposPago($data_tp);
					}else{
						$fact->insertarTiposPago($data_tp);
					}
				}else{
					$fact->insertarMontosPago($data_mp);
					$fact->insertarTiposPago($data_tp);
				}
			}else{
				$fact->SetFont("Arial","BI",13);
				$fact->Cell(0,10,utf8_decode("NO EXISTE NINGÚNA FACTURACIÓN CON EL CRITERIO DE BUSQUEDA"),1,0);
			}
		}
		$fact->dibujarFacturaReciboPq();
		$fact->Output("","Factura_Mensualidad.pdf");
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>