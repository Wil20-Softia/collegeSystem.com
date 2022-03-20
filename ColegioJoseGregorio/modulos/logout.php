<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../config/EntidadBase.php";
		$entidad = new EntidadBase("usuario");	
		$conexion = $entidad->db();
		
		$conexion->query("UPDATE usuario SET ultima_sesion = NOW(), conectado = 0 WHERE id = " . $_SESSION["id"]);

		$salida = "¡SESIÓN CERRADA!";

		session_destroy();
		echo $salida;
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>