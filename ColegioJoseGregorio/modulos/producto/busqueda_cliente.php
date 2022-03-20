<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("persona");
	$conexion = $mb->db();
	
	$cedula = $_GET["cedula_cliente"];

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

	if($resultado->num_rows == 0){
		echo "¡Cliente no registrado, registrelo para proceder con la venta!";
	}else{
		echo 1;
	}

?>