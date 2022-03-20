<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas"); 
		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");
		
		require_once "../config/EntidadBase.php";
		require_once "../config/functions.php";

		$entidad = new EntidadBase("usuario");
		$conexion = $entidad->db();
		if(isset($_POST["opcion"]) && !empty($_POST["opcion"])){
			$select = 0;
			$radio = 0;
			$checkbox = 0;
			$texto = 0;
			$informacion = 0;
			switch ($_POST["opcion"]) {
				case 'usuario_admin_datos':
					$texto = array(
						"usuario-cedula" => $_SESSION["cedula"],
						"usuario-correo" => $_SESSION["correo"],
						"usuario-primer_nombre" => $_SESSION["primer_nombre"],
						"usuario-primer_apellido" => $_SESSION["primer_apellido"]
					);
					$radio = array(
						"sexo_usuario" => $_SESSION["sexo"]
					);
				break;

				case 'estudiante_factura':
					if(isset($_POST["tipo_estudiante"]) && !empty($_POST["tipo_estudiante"])){
						
						$tipo_estudiante = $_POST["tipo_estudiante"];

						$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $tipo_estudiante");
						$te = $re->fetch_assoc();
						$type_student = $te["tipo"]; //NOMBRE DEL TIPO DE ESTUDIANTE

						if($type_student == "momento_estudiante"){
							$resultado = $conexion->query("
								SELECT 
									$type_student.tipo_estudiante AS id_estudiante, 
									CONCAT(estudiante.primer_nombre,' ',SUBSTRING(estudiante.segundo_nombre,1,1),'.',' ',estudiante.primer_apellido,' ',SUBSTRING(estudiante.segundo_apellido,1,1),'.') AS nombre_estudiante, 
									estudiante.cedula AS cedula_estudiante, 
									CONCAT(grado.nombre,' ',seccion.nombre) AS seccion_especifica_estudiante, 
									periodo_escolar.nombre AS periodo_escolar_estudiante 
								FROM $type_student 
								INNER JOIN estudiante ON $type_student.estudiante = estudiante.id 
								INNER JOIN seccion_especifica ON $type_student.seccion_especifica = seccion_especifica.id
								INNER JOIN grado ON seccion_especifica.grado = grado.id
								INNER JOIN seccion ON seccion_especifica.seccion = seccion.id
								INNER JOIN periodo_escolar ON $type_student.periodo_escolar = periodo_escolar.id
								WHERE 
									estudiante.habilitado != 0 AND 
									$type_student.tipo_estudiante = $tipo_estudiante");
							$informacion = $resultado->fetch_assoc();
						}else if($type_student == "estudiante_deudor_antiguo"){
							$resultado = $conexion->query("
								SELECT 
									$type_student.tipo_estudiante AS id_estudiante, 
									CONCAT(estudiante.primer_nombre,' ',SUBSTRING(estudiante.segundo_nombre,1,1),'.',' ',estudiante.primer_apellido,' ',SUBSTRING(estudiante.segundo_apellido,1,1),'.') AS nombre_estudiante, 
									estudiante.cedula AS cedula_estudiante 
								FROM $type_student 
								INNER JOIN estudiante ON $type_student.estudiante = estudiante.id
								WHERE 
									estudiante.habilitado != 0 AND 
									$type_student.tipo_estudiante = $tipo_estudiante");
							$informacion = $resultado->fetch_assoc();
						}
						
						if(!isset($_POST["formulario"])){
							$resultado = $conexion->query("SELECT monto AS mensualidad_actual FROM mensualidad WHERE 1 ORDER BY id DESC LIMIT 0,1 ");
							$mo_men = $resultado->fetch_assoc();
							$monto_mensualidad = $mo_men["mensualidad_actual"];
							$total = str_replace(",","",$monto_mensualidad);
							$total = number_format($total,2,',','.');
							
							array_push_assoc($informacion, array("mensualidad_actual" => $total));
							
							$resultado = $conexion->query("SELECT porcentaje AS mora_actual FROM mora WHERE 1 ORDER BY id DESC LIMIT 0,1");
							$mo_mor = $resultado->fetch_assoc();
							$monto_mora = $mo_mor["mora_actual"];
							$total = str_replace(",","",$monto_mora);
							$total = number_format($total,2,',','.');
							array_push_assoc($informacion, array("mora_actual" => $total));
						}
					}
				break;

				case 'estudiante_formulario':
					if(isset($_POST["cedula_estudiante"]) && !empty($_POST["cedula_estudiante"])){
						$cedula = $_POST["cedula_estudiante"];

						$resultado = $conexion->query("
							SELECT 
								estudiante.id AS `id_estudiante`,
								estudiante.primer_nombre AS `estudiante-primer_nombre`, 
								estudiante.segundo_nombre AS `estudiante-segundo_nombre`, 
								estudiante.primer_apellido AS `estudiante-primer_apellido`, 
								estudiante.segundo_apellido AS `estudiante-segundo_apellido`, 
								estudiante.cedula AS `estudiante-cedula`,
								estudiante.cedulado AS `cedulado`, 
								representante.primer_nombre AS `representante-primer_nombre`, 
								representante.segundo_nombre AS `representante-segundo_nombre`, 
								representante.primer_apellido AS `representante-primer_apellido`, 
								representante.segundo_apellido AS `representante-segundo_apellido`, 
								representante.cedula AS `representante-cedula`, 
								representante.telefono AS `representante-telefono` 
							FROM estudiante 
							INNER JOIN representante ON estudiante.representante = representante.id
							WHERE 
								estudiante.cedula = '$cedula'");

						if($resultado->num_rows == 1){
							$informacion_completa = $resultado->fetch_assoc();

							$texto = array(
								"estudiante-primer_nombre" => $informacion_completa['estudiante-primer_nombre'],
								"estudiante-segundo_nombre" => $informacion_completa['estudiante-segundo_nombre'],
								"estudiante-primer_apellido" => $informacion_completa['estudiante-primer_apellido'],
								"estudiante-segundo_apellido" => $informacion_completa['estudiante-segundo_apellido'],
								"estudiante-cedula" => $informacion_completa['estudiante-cedula'],
								"representante-primer_nombre" => $informacion_completa['representante-primer_nombre'],
								"representante-segundo_nombre" => $informacion_completa['representante-segundo_nombre'],
								"representante-primer_apellido" => $informacion_completa['representante-primer_apellido'],
								"representante-segundo_apellido" => $informacion_completa['representante-segundo_apellido'],
								"representante-cedula" => $informacion_completa['representante-cedula'],
								"representante-telefono" => $informacion_completa["representante-telefono"]
							);

							$radio = array(
								"cedulado" => $informacion_completa['cedulado']
							);

							$informacion = array(
								"id_estudiante" => $informacion_completa['id_estudiante']
							);
						}
					}
				break;

				case 'cliente_factura':
					$cedula = $_POST["comprador"];

					$resultado = $conexion->query("
						SELECT 
							tipo_comprador.id, 
							tipo_comprador.tipo 
						FROM tipo_comprador 
						LEFT JOIN estudiante ON estudiante.tipo_comprador = tipo_comprador.id
						LEFT JOIN representante ON representante.tipo_comprador = tipo_comprador.id
						LEFT JOIN usuario ON usuario.tipo_comprador = tipo_comprador.id
						LEFT JOIN persona ON persona.tipo_comprador = tipo_comprador.id
						WHERE 
							estudiante.cedula = '$cedula' OR 
							representante.cedula = '$cedula' OR 
							persona.cedula = '$cedula' OR 
							usuario.cedula = '$cedula'");

					if($resultado->num_rows == 1){
						$datos_cliente = $resultado->fetch_assoc();
						$comprador = $datos_cliente["tipo"];
						$id_comprador = $datos_cliente["id"];

						if($comprador == "persona" || $comprador == "representante"){				
							$resultado = $conexion->query("SELECT tipo_comprador AS id_comprador, primer_nombre AS nombre, primer_apellido AS apellido, cedula, telefono FROM $comprador WHERE tipo_comprador = $id_comprador");
						}else if($comprador == "usuario"){
							$resultado = $conexion->query("SELECT tipo_comprador AS id_comprador, primer_nombre AS nombre, primer_apellido AS apellido, cedula, correo AS telefono FROM $comprador WHERE tipo_comprador = $id_comprador");
						}else if($comprador == "estudiante"){
							$resultado = $conexion->query("SELECT $comprador.tipo_comprador AS id_comprador, $comprador.primer_nombre AS nombre, $comprador.primer_apellido AS apellido, $comprador.cedula, representante.telefono FROM $comprador INNER JOIN representante ON estudiante.representante = representante.id WHERE $comprador.tipo_comprador = $id_comprador");
						}
						$informacion = $resultado->fetch_assoc();
					}
				break;

				case "subcategoria_formulario":
					$codigo = $_POST["codigo"];

					$resultado = $conexion->query("SELECT nombre AS nombre_subcategoria, categoria_producto AS opciones_categorias FROM sub_categoria_producto WHERE id = $codigo");

					$array_datos = $resultado->fetch_assoc();
					$select = array(
						"opciones_categorias" => $array_datos["opciones_categorias"]
					);

					$texto = array(
						"nombre_subcategoria" => $array_datos["nombre_subcategoria"]
					);
				break;

				default:
					echo "OPCIÓN NO EXISTENTE PARA OBTENER LOS DATOS!";
				break;
			}

			$datos = array(
				"selects" => $select,
				"radios" => $radio,
				"checkboxs" => $checkbox,
				"textos" => $texto,
				"informacion" => $informacion
			);
			$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
			echo $datos_json;

		}else{
			echo "¡NO SE HA DECLARADO LA OPCIÓN!";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>