<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/ModeloBase.php";
		$id_usuario = $_SESSION["id"];
		$mb = new ModeloBase("persona");
		$conexion = $mb->db();

		$nombre = $_POST["cliente_nombre"];
		$apellido = $_POST["cliente_apellido"];
		$cedula = $_POST["cliente_cedula"];
		$direccion = $_POST["cliente_direccion"];
		$telefono = $_POST["cliente_telefono"];

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
			$id_comprador = $mb->registrarComprador("persona");
			$conexion->query("INSERT INTO persona (cedula, primer_nombre, primer_apellido, direccion, telefono, tipo_comprador) VALUES ('$cedula','$nombre','$apellido','$direccion','$telefono',$id_comprador)");

			$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE CLIENTE',NOW(),NOW())");

			$datos = array('mensaje' => "¡CLIENTE REGISTRADO EXITOSAMENTE!", 'advertencia' => 1);
			$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
			echo $datos_json;
		}else{
			echo "¡CLIENTE YA EXISTENTE!";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>