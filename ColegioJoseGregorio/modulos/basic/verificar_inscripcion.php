<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		require_once "../../config/EntidadBase.php";

		$datos = array();

		$entidad = new EntidadBase("momento_estudiante");
		$conexion = $entidad->db();

		$cedula_estudiante = $_POST["cedula"];

		if($_POST["periodo_escolar"] != 0){
			$id_periodo = $_POST["periodo_escolar"];
		}else{
			$id_periodo = $entidad->obtener_Id_periodoActual();
		}

		$tipo_estudiante = $_POST["tipo_estudiante"];
		$resultado_mora = $conexion->query("SELECT id FROM mora WHERE 1 ORDER BY id ASC");
		if($resultado_mora->num_rows == 0){
			echo "¡NO SE HA REGISTRADO NINGÚN PORCENTAJE DE MORA!";
		}else{
			$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $tipo_estudiante");
			$te = $re->fetch_assoc();
			$type_student = $te["tipo"];

			if($type_student == "momento_estudiante"){
				$verificar_deuda_meses = $conexion->query("
					SELECT 
						tipo_deuda_mes.id 
					FROM tipo_deuda_mes 
					INNER JOIN deuda_meses ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
					INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id 
					INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id 
					WHERE 
						momento_estudiante.tipo_estudiante = $tipo_estudiante AND
						estudiante.habilitado = 1 AND
						(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2)
				");

				if($verificar_deuda_meses->num_rows > 0){
					$resultado = $conexion->query("
								SELECT 
									COUNT(*) AS deudor 
								FROM momento_estudiante 
								INNER JOIN tipo_deuda_inscripcion ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id
								INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id 
								WHERE 
									estudiante.cedula = '$cedula_estudiante' AND
									(tipo_deuda_inscripcion.estado_pago = 3 OR 
									tipo_deuda_inscripcion.estado_pago = 2) AND
									momento_estudiante.periodo_escolar = $id_periodo");
					$deu = $resultado->fetch_assoc();
					$deudor = $deu["deudor"];

					if($deudor > 0){
						echo "DEBE CANCELAR LOS MONTOS DE INSCRIPCION REGISTRADOS HASTA AHORA PARA PROCEDER CON EL PAGO DE LA MENSUALIDAD!";
					}else{
						echo 1;
					}
				}else{
					echo "¡EL ESTUDIANTE HA CANCELADO TODA SU DEUDA!";
				}
			}else if($type_student == "estudiante_deudor_antiguo"){
				$verificar_deuda_meses = $conexion->query("
					SELECT 
						tipo_deuda_mes.id 
					FROM tipo_deuda_mes 
					INNER JOIN deuda_antigua ON deuda_antigua.tipo_deuda_mes = tipo_deuda_mes.id
					INNER JOIN estudiante_deudor_antiguo ON deuda_antigua.estudiante_deudor_antiguo = estudiante_deudor_antiguo.id 
					INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id 
					WHERE 
						estudiante_deudor_antiguo.tipo_estudiante = $tipo_estudiante AND
						estudiante.habilitado = 2 AND 
						(tipo_deuda_mes.estado_pago = 3 OR tipo_deuda_mes.estado_pago = 2)
				");
				if($verificar_deuda_meses->num_rows > 0){
					echo 1;
				}else{
					echo "¡EL ESTUDIANTE HA CANCELADO TODA SU DEUDA!";
				}
			}
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>