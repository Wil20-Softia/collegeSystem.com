<?php
	require_once "EntidadBase.php";
	require_once "functions.php";
	$entidad = new EntidadBase("usuario");
	$conexion = $entidad->db();

	//PARA MODIFICAR LA PASSWORD DEL SUPER USUARIO CUANDO SE OLVIDE.
	/*$pass_encry = encryp_pass("12345678");
	$conexion->query("UPDATE usuario SET password = '$pass_encry' WHERE id = 1 AND tipo = 1");*/

	//PARA REGISTRAR AL SUPER USUARIO POR PRIMERA VEZ EN LA BASE DE DATOS.
	/*
	$id_tipo_comprador = $entidad->registrarComprador("usuario");

	$pass_encry = encryp_pass("yvone2019");
	$conexion->query("INSERT INTO usuario (cedula, correo, primer_nombre, primer_apellido, sexo, tipo, habilitado, ultima_sesion, tipo_comprador, password) VALUES ('V-4313924','yvoviedo@gmail.com','IVONE','OVIEDO','FEMENINO',1,1,'',$id_tipo_comprador,'$pass_encry')");*/
?>