<?php

	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/EntidadBase.php";
		require_once "../../config/functions.php";

		$en = new EntidadBase("passwords_usuario");
		$conexion = $en->db();

		$id_usuario = $_SESSION["id"];
		$password = $_GET["pass"];

		$sql = "SELECT password FROM usuario WHERE id = $id_usuario AND habilitado = 1";
		$result = $conexion->query($sql);
		$d = $result->fetch_assoc();
		$password_usuario = $d["password"];

		if(desencryp_pass($password_usuario, $password)){
			echo 1;
		}else{
			echo "¡CONTRASEÑA ACTUAL INVALIDA!";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}

?>