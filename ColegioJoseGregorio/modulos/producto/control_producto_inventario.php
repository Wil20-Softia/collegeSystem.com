<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		setlocale(LC_ALL,'es_VE.UTF-8');
		date_default_timezone_set ("America/Caracas"); 

		require_once "../../config/ModeloBase.php";
		include "../../config/functions.php";

		$mb = new ModeloBase("producto");
		$conexion = $mb->db();

		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){
			$id_producto = $_GET["id_producto"];

			if($_GET["criterio"] == "modificar"){
				$nuevo_nombre = $_GET["nombre"];

				$conexion->query("UPDATE producto SET descripcion = '$nuevo_nombre' WHERE id = $id_producto");
				
				$mensaje_json = json_encode(array("mensaje" => "¡Nombre del Producto Actualizado con Exito!"), JSON_UNESCAPED_UNICODE);
				echo $mensaje_json;
			}else if($_GET["criterio"] == "actualizar_precio"){
				$nuevo_precio = $_GET["precio_venta"];

				$conexion->query("UPDATE producto SET precio_venta = $nuevo_precio WHERE id = $id_producto");

				$conexion->query("INSERT INTO precio_producto (precio_venta, producto) VALUES ($nuevo_precio, $id_producto)");

				$mensaje_json = json_encode(array("mensaje" => "¡Actualización de Precios Exitosa!"), JSON_UNESCAPED_UNICODE);
				echo $mensaje_json;
			}else if($_GET["criterio"] == "agregar_productos"){
				$cantidad_agregar = $_GET["cantidad_agregar"];
				
				$conexion->query("UPDATE producto SET cantidad_existente = cantidad_existente + $cantidad_agregar, ultimo_abastecimiento = NOW() WHERE id = $id_producto");

				$mensaje_json = json_encode(array("mensaje" => "¡Productos Agregados con Exito!"), JSON_UNESCAPED_UNICODE);
				echo $mensaje_json;
			}else if($_GET["criterio"] == "eliminar"){

				$resultado = $conexion->query("SELECT id FROM productos_facturas WHERE producto = $id_producto");

				if($resultado->num_rows > 0){
					$conexion->query("UPDATE producto SET habilitado = 0 WHERE id = $id_producto");
				}else{
					$conexion->query("DELETE FROM producto WHERE id = $id_producto");
				}

				$mensaje_json = json_encode(array("mensaje" => "¡Producto Eliminado con Exito!"), JSON_UNESCAPED_UNICODE);
				echo $mensaje_json;
			}else{
				echo "OPCIÓN PARA PRODUCTO NO EXISTENTE";
			}
		}else{
			echo "¡NO SE HA DECLARADO LA CONDICIÓN A REALIZAR POR EL USUARIO!";
		}	
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>