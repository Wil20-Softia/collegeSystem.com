<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		setlocale(LC_ALL,'es_VE.UTF-8');
		date_default_timezone_set ("America/Caracas"); 

		require_once "../config/ModeloBase.php";
		include "../config/functions.php";

		$mb = new ModeloBase("estudiante");
		$conexion = $mb->db();
		$b = 0;
		$mensaje = "";

		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			if($_GET["criterio"] == "busqueda"){
				$texto = $_GET["busqueda"];
				$sql_me = "
					SELECT
						tipo_factura.id AS codigo,
						tipo_factura.tipo,
						tipo_factura.fecha,
						tipo_factura.hora,
						tipo_factura.monto_total AS total,
						estudiante.cedula AS estudiante
					FROM tipo_factura
					INNER JOIN cliente ON tipo_factura.cliente = cliente.id
					INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id
					INNER JOIN momento_estudiante ON momento_estudiante.tipo_estudiante = tipo_estudiante.id 
					INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
					WHERE
						(tipo_factura.tipo = 'i' OR
						tipo_factura.tipo = 'm') AND 
						estudiante.cedula = '$texto'
					ORDER BY tipo_factura.id ASC
				";

				$sql_eda = "
					SELECT
						tipo_factura.id AS codigo,
						tipo_factura.tipo,
						tipo_factura.fecha,
						tipo_factura.hora,
						tipo_factura.monto_total AS total,
						estudiante.cedula AS estudiante
					FROM tipo_factura
					INNER JOIN cliente ON tipo_factura.cliente = cliente.id
					INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id
					INNER JOIN estudiante_deudor_antiguo ON estudiante_deudor_antiguo.tipo_estudiante = tipo_estudiante.id 
					INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id
					WHERE
						(tipo_factura.tipo = 'i' OR
						tipo_factura.tipo = 'm') AND 
						estudiante.cedula = '$texto'
					ORDER BY tipo_factura.id ASC
				";

				$sql_cf = "
					SELECT
						tipo_factura.id AS codigo,
						tipo_factura.tipo,
						tipo_factura.fecha,
						tipo_factura.hora,
						tipo_factura.monto_total AS total,
						tipo_estudiante.tipo AS tipo_estudiante,
						tipo_estudiante.id AS id_tipo_estudiante
					FROM tipo_factura
					INNER JOIN cliente ON tipo_factura.cliente = cliente.id
					INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id
					WHERE
						(tipo_factura.tipo = 'i' OR
						tipo_factura.tipo = 'm') AND 
						tipo_factura.id = $texto
					ORDER BY tipo_factura.id ASC
				";
			}else if($_GET["criterio"] == "listado_completo"){
				$sql = "
					SELECT
						tipo_factura.id AS codigo,
						tipo_factura.tipo,
						tipo_factura.fecha,
						tipo_factura.hora,
						tipo_factura.monto_total AS total,
						tipo_estudiante.tipo AS tipo_estudiante,
						tipo_estudiante.id AS id_tipo_estudiante
					FROM tipo_factura
					INNER JOIN cliente ON tipo_factura.cliente = cliente.id
					INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id
					WHERE
						(tipo_factura.tipo = 'i' OR
						tipo_factura.tipo = 'm')
					ORDER BY tipo_factura.id DESC
				";
			}else if($_GET["criterio"] == "rango_fechas"){
				$fecha_desde = $_GET["fecha_desde"];
				$fecha_hasta = $_GET["fecha_hasta"];

				$fecha_actual = strtotime(date("Y-m-d"));

				if(empty($fecha_desde) && empty($fecha_hasta)){
					$mensaje = "INTRODUZCA LAS FECHAS PARA CONTINUAR!";
					$b = 1;
				}else if(!empty($fecha_desde) && !empty($fecha_hasta)){
					if(strtotime($fecha_desde) > $fecha_actual){
						$mensaje = "LA FECHA DE INICIO NO DEBE SER MAYOR A LA ACTUAL";
						$b = 1;
					}else if(strtotime($fecha_hasta) < strtotime($fecha_desde)){
						$mensaje = "LA FECHA FINAL NO DEBE SER MENOR A LA FECHA DE INICIO";
						$b = 1;
					}else if(strtotime($fecha_hasta) > $fecha_actual){
						$fecha_hasta = date("Y-m-d");
					}
				}else if(!empty($fecha_desde) && empty($fecha_hasta)){
					if(strtotime($fecha_desde) > $fecha_actual){
						$mensaje = "LA FECHA DE INICIO NO DEBE SER MAYOR A LA ACTUAL";
						$b = 1;
					}else{
						$fecha_hasta = date("Y-m-d");
					}
				}else if(empty($fecha_desde) && !empty($fecha_hasta)){
					$mensaje = "POR LO MENOS, DEBE HABER UNA FECHA DE INICIO.";
					$b = 1;
				}

				if($b == 0){
					$sql = "
						SELECT
							tipo_factura.id AS codigo,
							tipo_factura.tipo,
							tipo_factura.fecha,
							tipo_factura.hora,
							tipo_factura.monto_total AS total,
							tipo_estudiante.tipo AS tipo_estudiante,
							tipo_estudiante.id AS id_tipo_estudiante
						FROM tipo_factura
						INNER JOIN cliente ON tipo_factura.cliente = cliente.id
						INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id
						WHERE
							(tipo_factura.tipo = 'i' OR
							tipo_factura.tipo = 'm') AND
							tipo_factura.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'
						ORDER BY tipo_factura.id DESC
					";
				}else{
					echo $mensaje;
				}
			}else{
				echo "¡NO EXISTE EL CRITERIO ESCOGIDO!";
				$b = 1;
			}

			if($b == 0){
				if($_GET["criterio"] == "busqueda"){
					$resultado_fac_me = $conexion->query($sql_me);
					$resultado_fac_eda = $conexion->query($sql_eda);
					$resultado_cf = $conexion->query($sql_cf);

					if($resultado_fac_me->num_rows > 0 && $resultado_fac_eda->num_rows == 0){
						$facturas = array();
						while($row = $resultado_fac_me->fetch_assoc()){ 							
							$total = str_replace(",","",$row["total"]);
							$total = number_format($total,2,',','.');
							$hora = date('h:i:s a', strtotime($row["hora"]));

							$facturas[] = array(
								"codigo" => $row["codigo"],
								"tipo" => $row["tipo"],
								"fecha" => reg_date($row["fecha"], "formato_corto"),
								"hora" => $hora,
								"estudiante" => $row["estudiante"],
								"total" => $total
							);
						}
						$mensaje_json = json_encode($facturas, JSON_UNESCAPED_UNICODE);
						echo $mensaje_json;						
					}else if($resultado_fac_me->num_rows == 0 && $resultado_fac_eda->num_rows > 0){
						$facturas = array();
						while($row = $resultado_fac_eda->fetch_assoc()){ 							
							$total = str_replace(",","",$row["total"]);
							$total = number_format($total,2,',','.');
							$hora = date('h:i:s a', strtotime($row["hora"]));

							$facturas[] = array(
								"codigo" => $row["codigo"],
								"tipo" => $row["tipo"],
								"fecha" => reg_date($row["fecha"], "formato_corto"),
								"hora" => $hora,
								"estudiante" => $row["estudiante"],
								"total" => $total
							);
						}
						$mensaje_json = json_encode($facturas, JSON_UNESCAPED_UNICODE);
						echo $mensaje_json;	
					}else if($resultado_fac_me->num_rows > 0 && $resultado_fac_eda->num_rows > 0){
						$facturas = array();
						while($row = $resultado_fac_me->fetch_assoc()){ 							
							$total = str_replace(",","",$row["total"]);
							$total = number_format($total,2,',','.');
							$hora = date('h:i:s a', strtotime($row["hora"]));

							$facturas[] = array(
								"codigo" => $row["codigo"],
								"tipo" => $row["tipo"],
								"fecha" => reg_date($row["fecha"], "formato_corto"),
								"hora" => $hora,
								"estudiante" => $row["estudiante"],
								"total" => $total
							);
						}
						while($row = $resultado_fac_eda->fetch_assoc()){ 							
							$total = str_replace(",","",$row["total"]);
							$total = number_format($total,2,',','.');
							$hora = date('h:i:s a', strtotime($row["hora"]));

							$facturas[] = array(
								"codigo" => $row["codigo"],
								"tipo" => $row["tipo"],
								"fecha" => reg_date($row["fecha"], "formato_corto"),
								"hora" => $hora,
								"estudiante" => $row["estudiante"],
								"total" => $total
							);
						}
						$mensaje_json = json_encode($facturas, JSON_UNESCAPED_UNICODE);
						echo $mensaje_json;
					}else{
						if($resultado_cf->num_rows > 0){
							$facturas = array();
							while($row = $resultado_cf->fetch_assoc()){ 
								$id_tipo_estudiante = $row["id_tipo_estudiante"];
								$tipo_estudiante = $row["tipo_estudiante"];
								$res_est = $conexion->query("SELECT estudiante.cedula FROM $tipo_estudiante INNER JOIN estudiante ON $tipo_estudiante.estudiante = estudiante.id WHERE $tipo_estudiante.tipo_estudiante = $id_tipo_estudiante");
								$d_est = $res_est->fetch_assoc();
								$estudiante = $d_est["cedula"];
								
								$total = str_replace(",","",$row["total"]);
								$total = number_format($total,2,',','.');
								$hora = date('h:i:s a', strtotime($row["hora"]));

								$facturas[] = array(
									"codigo" => $row["codigo"],
									"tipo" => $row["tipo"],
									"fecha" => reg_date($row["fecha"], "formato_corto"),
									"hora" => $hora,
									"estudiante" => $estudiante,
									"total" => $total
								);
							}

							$mensaje_json = json_encode($facturas, JSON_UNESCAPED_UNICODE);
							echo $mensaje_json;
						}else{
							echo "¡NO EXISTEN REGISTROS!";
						}
					}
				}else{
					$resultado_facturas = $conexion->query($sql);
					if($resultado_facturas->num_rows > 0){
						$facturas = array();
						while($row = $resultado_facturas->fetch_assoc()){ 
							$id_tipo_estudiante = $row["id_tipo_estudiante"];
							$tipo_estudiante = $row["tipo_estudiante"];
							$res_est = $conexion->query("SELECT estudiante.cedula FROM $tipo_estudiante INNER JOIN estudiante ON $tipo_estudiante.estudiante = estudiante.id WHERE $tipo_estudiante.tipo_estudiante = $id_tipo_estudiante");
							$d_est = $res_est->fetch_assoc();
							$estudiante = $d_est["cedula"];
							
							$total = str_replace(",","",$row["total"]);
							$total = number_format($total,2,',','.');
							$hora = date('h:i:s a', strtotime($row["hora"]));

							$facturas[] = array(
								"codigo" => $row["codigo"],
								"tipo" => $row["tipo"],
								"fecha" => reg_date($row["fecha"], "formato_corto"),
								"hora" => $hora,
								"estudiante" => $estudiante,
								"total" => $total
							);
						}

						$mensaje_json = json_encode($facturas, JSON_UNESCAPED_UNICODE);
						echo $mensaje_json;
					}else{
						echo "¡NO EXISTEN REGISTROS!";
					}
				}	
			}
		}else{
			echo "¡NO SE HA DECLARADO LA CONDICIÓN A REALIZAR POR EL USUARIO!";
		}	
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>