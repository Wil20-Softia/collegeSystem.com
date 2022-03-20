<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../config/ModeloBase.php";
		$mb = new ModeloBase("estudiante");
		$b = 0;
		$datos = array();
		$conexion = $mb->db();

		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			if($_GET["criterio"] == "busqueda"){
				$texto = $_GET["busqueda"];
				$sql = "
					SELECT 
						estudiante_deudor_antiguo.tipo_estudiante AS id_tipo_estudiante,
						estudiante.id AS id_estudiante,
						estudiante_deudor_antiguo.id AS id_estudiante_da,
						estudiante.cedula, 
						estudiante.primer_nombre AS nombre, 
						estudiante.primer_apellido AS apellido,
						representante.telefono AS telefono_repre
					FROM estudiante 
					INNER JOIN estudiante_deudor_antiguo ON estudiante_deudor_antiguo.estudiante = estudiante.id
					INNER JOIN representante ON estudiante.representante = representante.id
					WHERE 
						estudiante.habilitado = 2 AND 
						estudiante_deudor_antiguo.habilitado = 1 AND
						(estudiante.cedula = '$texto' OR CONCAT(estudiante.primer_nombre,' ',estudiante.primer_apellido) LIKE '%$texto%')";
			}else if($_GET["criterio"] == "listado_completo"){
				$sql = "
					SELECT 
						estudiante_deudor_antiguo.tipo_estudiante AS id_tipo_estudiante,
						estudiante.id AS id_estudiante,
						estudiante_deudor_antiguo.id AS id_estudiante_da,
						estudiante.cedula, 
						estudiante.primer_nombre AS nombre, 
						estudiante.primer_apellido AS apellido,
						representante.telefono AS telefono_repre
					FROM estudiante 
					INNER JOIN estudiante_deudor_antiguo ON estudiante_deudor_antiguo.estudiante = estudiante.id
					INNER JOIN representante ON estudiante.representante = representante.id
					WHERE 
						estudiante.habilitado = 2 AND
						estudiante_deudor_antiguo.habilitado = 1";
			}else{
				echo "¡NO EXISTE EL CRITERIO ESCOGIDO!";
				$b = 1;
			}

			if($b == 0){
				$resultado_general = $conexion->query($sql);
				if($resultado_general->num_rows > 0){
					$coger_mensualidad_actual = $conexion->query("SELECT monto FROM mensualidad WHERE 1 ORDER BY id DESC LIMIT  0,1");
					$asociativo_mensualidad_actual = $coger_mensualidad_actual->fetch_assoc();
					$monto_mensualidad_actual = (float)$asociativo_mensualidad_actual["monto"];

					while($row = $resultado_general->fetch_assoc()){
						$id_estudiante_da = $row["id_estudiante_da"];
						$resultado_meses_deuda = $conexion->query("   
							SELECT 
								COUNT(deuda_antigua.id) AS cantidad_meses_deuda
							FROM deuda_antigua
							INNER JOIN tipo_deuda_mes ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id
							WHERE 
								deuda_antigua.estudiante_deudor_antiguo = $id_estudiante_da AND
								(tipo_deuda_mes.estado_pago = 3 OR
								tipo_deuda_mes.estado_pago = 2)
						");
						$obtener_cantidad_meses_deuda = $resultado_meses_deuda->fetch_assoc();
						$cantidad_meses_deuda = (int)$obtener_cantidad_meses_deuda["cantidad_meses_deuda"];
						if($cantidad_meses_deuda > 0){
							$inscribir = 0;
						}else{
							$inscribir = 1;
						}

						$total_bruto = $cantidad_meses_deuda * $monto_mensualidad_actual;
						$total = str_replace(",","",$total_bruto);
						$total = number_format($total,2,',','.');

						$r = $conexion->query("
							SELECT 
								deuda_antigua.tipo_deuda_mes AS id_deuda_mes,
								mes.nombre AS mes
							FROM deuda_antigua
							INNER JOIN tipo_deuda_mes ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id
							INNER JOIN mes ON deuda_antigua.mes = mes.id
							WHERE 
								deuda_antigua.estudiante_deudor_antiguo = $id_estudiante_da
							ORDER BY deuda_antigua.id ASC");
						
						$meses_estudiante = array();
						while($reg = $r->fetch_assoc()){
							$id_deuda_mes = $reg["id_deuda_mes"];

							$res = $conexion->query("
								SELECT 
									factura_normal.tipo_factura AS factura 
								FROM meses_pagos 
								INNER JOIN factura_normal ON meses_pagos.factura = factura_normal.id 
								WHERE 
									meses_pagos.tipo_deuda_mes = $id_deuda_mes");
							if($res->num_rows == 0){
								$facturas_mes = 0;
							}else{
								$facturas_mes = array();
								while($fm = $res->fetch_assoc()){
									$facturas_mes[] = $fm["factura"];
								}
							}

							$meses_estudiante[] = array(
								"mes" => $reg["mes"],
								"facturas_mes" => $facturas_mes
							);
						}

						$datos[] = array(
							"id_tipo_estudiante"=> $row["id_tipo_estudiante"],
							"id_estudiante" => $row["id_estudiante"],
							"cedula" => $row["cedula"],
							"nombre"=> $row["nombre"],
							"apellido" => $row["apellido"],
							"telefono_repre"=> $row["telefono_repre"],
							"cantidad_meses_deuda" => $cantidad_meses_deuda,
							"inscribir" => $inscribir,
							"total_bruto" => $total,
							"meses_estudiante" => $meses_estudiante
						);
					}

					$mensaje_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $mensaje_json;
				}else{
					echo "¡NO EXISTEN RESULTADOS!";
				}
			}
		}else{
			echo "¡NO SE HA DECLARADO LA CONDICIÓN A REALIZAR POR EL USUARIO!";
		}	
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>