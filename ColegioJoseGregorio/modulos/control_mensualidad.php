<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		if(isset($_POST["criterio"]) && !empty($_POST["criterio"])){

			date_default_timezone_set ("America/Caracas"); 
			require_once "../config/EntidadBase.php";
			require_once "../config/functions.php";
			
			$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
			$aa = (int)date("Y");
			$ma = (int)date("m");

			$datos = array();

			$entidad = new EntidadBase("momento_estudiante");
			$conexion = $entidad->db();
			$criterio = $_POST["criterio"];

			$id_periodo = $entidad->obtener_Id_periodoActual();
			$periodo_actual = $entidad->obtener_periodoActual();
			$year_inicia = $periodo_actual["yearDesde"];

			$sql = "
				SELECT 
					momento_estudiante.id AS id_estudiante, 
					estudiante.cedula AS cedula_estudiante,
					momento_estudiante.tipo_estudiante,
					CONCAT(estudiante.primer_nombre,' ',SUBSTRING(estudiante.segundo_nombre,1,1),'.',' ',estudiante.primer_apellido,' ',SUBSTRING(estudiante.segundo_apellido,1,1),'.') AS nombre_estudiante, 
					CONCAT(representante.primer_nombre,' ',SUBSTRING(representante.segundo_nombre,1,1),'.',' ',representante.primer_apellido,' ',SUBSTRING(representante.segundo_apellido,1,1),'.') AS nombre_representante, 
					representante.telefono AS telefono_representante, 
					representante.cedula AS cedula_representante, 
					CONCAT(grado.nombre,' ',seccion.nombre) AS seccion_especifica_estudiante,
					periodo_escolar.nombre AS year_escolar_estudiante, 
					periodo_escolar.id AS id_year_escolar_estudiante 
				FROM momento_estudiante
				INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
				INNER JOIN representante ON estudiante.representante = representante.id
				INNER JOIN seccion_especifica ON momento_estudiante.seccion_especifica = seccion_especifica.id
				INNER JOIN grado ON seccion_especifica.grado = grado.id
				INNER JOIN seccion ON seccion_especifica.seccion = seccion.id
				INNER JOIN periodo_escolar ON momento_estudiante.periodo_escolar = periodo_escolar.id
				WHERE 
					estudiante.habilitado = 1 AND 
					periodo_escolar.id = $id_periodo AND ";

			$consulta_agrupada = " GROUP BY momento_estudiante.id ORDER BY momento_estudiante.id ASC";

			if($criterio == "nombre"){
				$busqueda = $_POST["busqueda"];
				$sql .= "(estudiante.cedula = '$busqueda' OR CONCAT(estudiante.primer_nombre,' ',estudiante.primer_apellido) LIKE '%$busqueda%')" . $consulta_agrupada;

				$resultado = $conexion->query($sql);

				if($resultado && $resultado->num_rows > 0){
					while($row = $resultado->fetch_assoc()){
						$id_estudiante = $row["id_estudiante"];

						$r = $conexion->query("
							SELECT 
								deuda_meses.tipo_deuda_mes AS id_deuda_mes, 
							    estado_pago.clases_contenedor, 
							    estado_pago.clases_boton  
							FROM deuda_meses
							INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
							INNER JOIN estado_pago ON tipo_deuda_mes.estado_pago = estado_pago.id
							WHERE 
								deuda_meses.momento_estudiante = $id_estudiante
							ORDER BY deuda_meses.id ASC");

						
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
								"id_deuda_mes" => $id_deuda_mes,
								"clases_contenedor" => $reg["clases_contenedor"],
								"clases_boton" => $reg["clases_boton"],
								"facturas_mes" => $facturas_mes
							);
						}

						$estudiantes[] = array(
							"id_estudiante"=> $row["id_estudiante"],
							"nombre_estudiante"=> $row["nombre_estudiante"],
							"tipo_estudiante" => $row["tipo_estudiante"],
							'cedula_estudiante' => $row["cedula_estudiante"],
							"cedula_representante"=> $row["cedula_representante"],
							"nombre_representante"=> $row["nombre_representante"],
							"telefono_representante"=> $row["telefono_representante"],
							"seccion_especifica_estudiante"=> $row["seccion_especifica_estudiante"],
							"year_escolar_estudiante"=> $row["year_escolar_estudiante"],
							"id_year_escolar_estudiante" => $row["id_year_escolar_estudiante"],
							"meses_estudiante" => $meses_estudiante
						);
					}

					$datos = array(
						"estudiantes" => $estudiantes,
						"datos_seccion" => 0
					);
					//var_dump($datos);
					$datos_pagina_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
		  			echo $datos_pagina_js;
				}else{
					echo "No se han encontrado estudiantes - ". $conexion->error;
				}
			}else if($criterio == "seccion"){

				$sql .= "momento_estudiante.seccion_especifica = " . $_POST["seccion_especifica"] . $consulta_agrupada;

				$resultado = $conexion->query($sql);

				if($resultado && $resultado->num_rows > 0){
					while($row = $resultado->fetch_assoc()){
						$id_estudiante = $row["id_estudiante"];

						$r = $conexion->query("
							SELECT 
								deuda_meses.tipo_deuda_mes AS id_deuda_mes, 
							    estado_pago.clases_contenedor, 
							    estado_pago.clases_boton  
							FROM deuda_meses
							INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
							INNER JOIN estado_pago ON tipo_deuda_mes.estado_pago = estado_pago.id
							WHERE 
								deuda_meses.momento_estudiante = $id_estudiante
							ORDER BY deuda_meses.id ASC");

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
								"id_deuda_mes" => $reg["id_deuda_mes"],
								"clases_contenedor" => $reg["clases_contenedor"],
								"clases_boton" => $reg["clases_boton"],
								"facturas_mes" => $facturas_mes
							);
						}

						$estudiantes[] = array(
							"id_estudiante"=> $row["id_estudiante"],
							"nombre_estudiante"=> $row["nombre_estudiante"],
							'cedula_estudiante' => $row["cedula_estudiante"],
							"tipo_estudiante" => $row["tipo_estudiante"],
							"cedula_representante"=> $row["cedula_representante"],
							"nombre_representante"=> $row["nombre_representante"],
							"telefono_representante"=> $row["telefono_representante"],
							"seccion_especifica_estudiante"=> $row["seccion_especifica_estudiante"],
							"year_escolar_estudiante"=> $row["year_escolar_estudiante"],
							"id_year_escolar_estudiante" => $row["id_year_escolar_estudiante"],
							"meses_estudiante" => $meses_estudiante
						);
					}

					$mesActual = mes_actual_ordernado($ma);
					if($mesActual == 12 && $year_inicia == $aa){
						$mesActual = 1;
					}

					$resultado = $conexion->query(
						"SELECT 
							COUNT(*) AS deudores 
						FROM momento_estudiante 
						INNER JOIN deuda_meses ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id
						INNER JOIN mes ON meses_periodo.mes = mes.id
						WHERE 
							mes.id = $mesActual AND 
							(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2) AND
							momento_estudiante.seccion_especifica = " . $_POST["seccion_especifica"] . " AND 
							momento_estudiante.periodo_escolar = $id_periodo"
					);
					$deu = $resultado->fetch_assoc();
					$deudores = $deu["deudores"];

					$resultado = $conexion->query(
						"SELECT 
							COUNT(*) AS solventes 
						FROM momento_estudiante 
						INNER JOIN deuda_meses ON deuda_meses.momento_estudiante = momento_estudiante.id
						INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
						INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id
						INNER JOIN mes ON meses_periodo.mes = mes.id
						WHERE 
							mes.id = $mesActual AND 
							tipo_deuda_mes.estado_pago = 1 AND 
							momento_estudiante.seccion_especifica = " . $_POST["seccion_especifica"] . " AND 
							momento_estudiante.periodo_escolar = $id_periodo"
					);
					$sol = $resultado->fetch_assoc();
					$solventes = $sol["solventes"];

					$resultado = $conexion->query(
						"SELECT 
							COUNT(*) AS cantidad 
						FROM momento_estudiante 
						WHERE 
							momento_estudiante.seccion_especifica = " . $_POST["seccion_especifica"] . " AND 
							momento_estudiante.periodo_escolar = $id_periodo"
					);
					$can = $resultado->fetch_assoc();
					$cantidad_seccion = $can["cantidad"];

					$datos = array(
						"estudiantes" => $estudiantes,
						"datos_seccion" => array(
							"cantidad" => $cantidad_seccion,
							"deudores" => $deudores,
							"solventes" => $solventes
						)
					);
					$datos_pagina_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
		  			echo $datos_pagina_js;

				}else{
					echo ":( No se han encontrado estudiantes :(". $conexion->error;
				}
			}else{
				echo "Opción No definida";
			}
		}else{
			echo "No esta definido el criterio";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>