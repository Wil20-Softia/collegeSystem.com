<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../config/EntidadBase.php";
		
		$entidad = new EntidadBase("usuario");
		
		$entidad->establecer_usuario($_SESSION["id"]);
		$entidad->establecerYearActual();
		$entidad->establecerMesActual();
		$entidad->establecerDiaActual();
		
		$entidad->borrarActividades_anterioresActual();

		$entidad->registrar_periodo_escolar();
		$entidad->traspaso_estudiante();
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>