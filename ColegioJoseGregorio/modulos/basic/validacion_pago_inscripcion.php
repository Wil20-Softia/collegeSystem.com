<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/EntidadBase.php";

		$entidad = new EntidadBase("tipo_estudiante");
		$conexion = $entidad->db();
		
		$tipo_estudiante = $_GET["tipo_estudiante"]; //NUMERO DE ID
		$periodo_actual = $entidad->obtener_Id_periodoActual();
		
		$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $tipo_estudiante");
		$te = $re->fetch_assoc();
		$type_student = $te["tipo"]; //NOMBRE DEL TIPO DE ESTUDIANTE

		if($type_student == "momento_estudiante"){
			$estudiante_actual = $conexion->query("SELECT id FROM momento_estudiante WHERE tipo_estudiante = $tipo_estudiante AND periodo_escolar = $periodo_actual");
			if($estudiante_actual->num_rows == 1){
				$rq = $conexion->query("
					SELECT 
						tipo_deuda_inscripcion.id 
					FROM tipo_deuda_inscripcion 
					INNER JOIN momento_estudiante ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id 
					WHERE 
						momento_estudiante.tipo_estudiante = $tipo_estudiante AND
						(tipo_deuda_inscripcion.estado_pago = 3 OR tipo_deuda_inscripcion.estado_pago = 2)");
				if($rq->num_rows == 0){
					echo "¡EL ESTUDIANTE HA CANCELADO TODA SU DEUDA!";
				}else{
					echo 1;
				}
			}else{
				echo 1;
			}
		}else if($type_student == "estudiante_deudor_antiguo"){
			echo 1;
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>