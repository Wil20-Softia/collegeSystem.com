<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		$datos = array(
			"id" => $_SESSION["id"],
			"nombre" => $_SESSION["nombre_completo"],
			"logo" => $_SESSION["imagen"],
			"identificador" => $_SESSION["identificador"],
			"ultima_sesion" => $_SESSION["ultima_sesion"],
			"tipo" => $_SESSION["tipo"]
		);

		$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
		echo $d;
	}else{
		echo "¡NO PUEDE INGRESAR AL SISTEMA!";
	}
?>