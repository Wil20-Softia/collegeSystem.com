<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/ModeloBase.php";

		$id_usuario = $_SESSION["id"];
		
		$mb = new ModeloBase("producto");
		$conexion = $mb->db();

		$nombre = $_POST["nombre"];
		$subcategoria = $_POST["subcategoria"];
		$precio_venta = $_POST["precio_venta"];
		$cantidad = $_POST["cantidad"];

		$conexion->query("INSERT INTO producto (sub_categoria_producto, descripcion, precio_venta, cantidad_existente, ultimo_abastecimiento, habilitado) VALUES ($subcategoria,'$nombre',$precio_venta, $cantidad, NOW(), 1)");
		$id_producto = $conexion->insert_id;

		$conexion->query("INSERT INTO precio_producto (producto, precio_venta) VALUES ($id_producto, $precio_venta)");

		$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE PRODUCTO',NOW(),NOW())");

		$datos = array('mensaje' => "¡PRODUCTO REGISTRADO EXITOSAMENTE!", 'advertencia' => 1);
		$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
		echo $datos_json;
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>