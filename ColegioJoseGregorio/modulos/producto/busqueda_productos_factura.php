<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("producto");
	$conexion = $mb->db();
	
	$busqueda = $_GET["valor_busqueda"];

	$resultado = $conexion->query("
		SELECT 
			id AS id_producto, 
			descripcion,
			precio_venta AS precio,
			cantidad_existente AS cantidad 
		FROM producto
		WHERE 
			descripcion LIKE '%$busqueda%' AND
			habilitado = 1");

	if($resultado->num_rows == 0){
		echo "¡No existe el Producto!";
	}else{
		while($productos_array[] = $resultado->fetch_assoc());
		array_pop($productos_array);
		$productos = json_encode($productos_array, JSON_UNESCAPED_UNICODE);
		echo $productos;
	}
?>