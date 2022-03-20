<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require "../../config/Factura.php";
		require "../../config/EntidadBase.php";

		$data_ip = array();
		$data_itp = array();

		$entidad = new EntidadBase("tipo_factura");
		$conexion = $entidad->db();

		$fact = new Factura("estudiante",0,"pequeña");

		if(isset($_GET["if"]) && !empty($_GET["if"])){

			$cf = $_GET["if"];

			//FACTURA POR CODIGO
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
							CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS nombre_usuario  
						FROM tipo_factura
						INNER JOIN usuario ON tipo_factura.usuario = usuario.id
						INNER JOIN cliente ON tipo_factura.cliente = cliente.id
						INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id 
						INNER JOIN momento_estudiante ON momento_estudiante.tipo_estudiante = tipo_estudiante.id
						INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
						INNER JOIN representante ON estudiante.representante = representante.id
						INNER JOIN seccion_especifica ON momento_estudiante.seccion_especifica = seccion_especifica.id
						INNER JOIN grado ON seccion_especifica.grado = grado.id
						INNER JOIN seccion ON seccion_especifica.seccion = seccion.id
						WHERE 
							tipo_factura.id = $cf");

			if($r->num_rows > 0){
				$fact->establecerTipoFactura("Inscripción");
				$fact->establecerCodigo($cf);

				//ARRAY QUE GUARDA LOS DATOS DE LA FACTURA.
				$datosFactura = $r->fetch_assoc();

				$fact->establecerFechaCompleta($datosFactura["fecha"],$datosFactura["hora"]);
				$fact->establecerMontoTotal($datosFactura["monto_total"]);
				$fact->establecerNombreEstudiante($datosFactura["nombre_estudiante"]);
				$fact->establecerCedulaEstudiante($datosFactura["cedula_estudiante"], $datosFactura["cedulado"]);
				$fact->establecerNombreRepresentante($datosFactura["nombre_representante"]);
				$fact->establecerCedulaRepresentante($datosFactura["cedula_representante"]);
				$fact->establecerGradoSeccion($datosFactura["grado"], $datosFactura["seccion"]);
				$fact->establecerNombreUsuario($datosFactura["nombre_usuario"]);

				$resultado = $conexion->query("
						SELECT 
							tipo_deuda_inscripcion.id AS id_monto,
							tipo_deuda_inscripcion.tipo_inscripcion AS id_inscripcion, 
						    tipo_inscripcion.tipo, 
						    tipo_deuda_inscripcion.estado_pago, 
						    tipo_deuda_inscripcion.diferencia, 
						    inscripciones_pagas.abonado, 
						    tipo_inscripcion.monto AS monto_inscripcion 
						FROM inscripciones_pagas 
						INNER JOIN tipo_deuda_inscripcion ON inscripciones_pagas.tipo_deuda_inscripcion = tipo_deuda_inscripcion.id
						INNER JOIN tipo_inscripcion ON tipo_deuda_inscripcion.tipo_inscripcion = tipo_inscripcion.id
						INNER JOIN factura_inscripcion ON inscripciones_pagas.factura_inscripcion = factura_inscripcion.id
						WHERE factura_inscripcion.tipo_factura = $cf");
				if($resultado->num_rows > 0){
					//REORRE CADA MONTO PAGO DE INSCRIPCION DE LA FACTURA
					while($row = $resultado->fetch_assoc()){
						$id_monto = $row["id_monto"];
						$id_inscripcion = $row["id_inscripcion"];
						$tipo_inscripcion = $row["tipo"];
						$estado_pago = $row["estado_pago"];
						$diferencia = $row["diferencia"];
						$abonado = $row["abonado"];
						$monto_inscripcion = $row["monto_inscripcion"];

						//SI ESTADO DE PAGO ES IGUAL A PAGADO (1)
						if($estado_pago == 1){
							//CONSULTA PARA VERIFICAR SI SE A PAGADO 
							//UNA O MAS VECES UN MONTO DE INSCRIPCION
							$r = $conexion->query("
								SELECT 
									factura_inscripcion.tipo_factura 
								FROM inscripciones_pagas 
								INNER JOIN factura_inscripcion ON inscripciones_pagas.factura_inscripcion = factura_inscripcion.id 
								WHERE 
									inscripciones_pagas.tipo_deuda_inscripcion = $id_monto 
								ORDER BY factura_inscripcion.id DESC");
							if($r->num_rows > 1 && $diferencia > 0){
								$i = 1;
								while($fila = $r->fetch_assoc()){
									if($fila["tipo_factura"] == $cf && $i == 1){
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
								$cancelado = $monto_inscripcion;
								$abonado = 0;
								$diferencia = 0;
							}

						//SI ESTADO DE PAGO ES IGUAL A ABONADO (2)
						}else if($estado_pago == 2){
							$cancelado = 0;
							$abonado = $abonado;
							$diferencia = 0;
						}

						$data_ip[] = array(
							$tipo_inscripcion . " " . $id_inscripcion,
							$monto_inscripcion,
							$cancelado,
							$abonado,
							$diferencia
						);
					}

					$fact->insertarMontosPago($data_ip);

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
							$data_itp[] = array(
								$d["tipo_pago"],
								$d["referencia"],
								$d["monto"]
							);
						}
						$fact->insertarTiposPago($data_itp);
					}else{
						$fact->insertarTiposPago($data_itp);
					}
				}else{
					$fact->insertarMontosPago($data_ip);
					$fact->insertarTiposPago($data_itp);
				}
			}else{
				$fact->SetFont("Arial","BI",13);
				$fact->Cell(0,10,"NO EXISTE NINGÚNA FACTURACION CON EL CRITERIO DE BUSQUEDA",1,0);
			}
		}
		$fact->dibujarFacturaReciboPq();
		$fact->Output("","Factura_Inscripcion.pdf");;
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>