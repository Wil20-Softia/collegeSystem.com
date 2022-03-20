<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas");
		require_once "../../config/EntidadBase.php";

		$entidad = new EntidadBase("mora");
		$conexion = $entidad->db();

		$tipo_estudiante = $_GET["tipo_estudiante"];
		
		$resultado_mora = $conexion->query("SELECT id FROM mora WHERE 1 ORDER BY id ASC");
		if($resultado_mora->num_rows == 0){
			echo "¡NO SE HA REGISTRADO NINGÚN PORCENTAJE DE MORA!";
		}else{
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
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>