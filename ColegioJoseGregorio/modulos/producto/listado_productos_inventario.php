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

			if($_GET["criterio"] == "busqueda"){
				$texto = $_GET["busqueda"];
				$sql = "
					SELECT
						producto.id,
						producto.descripcion AS nombre,
						producto.precio_venta,
						producto.cantidad_existente,
						producto.ultimo_abastecimiento,
						sub_categoria_producto.nombre AS nombre_subcategoria
					FROM producto
					INNER JOIN sub_categoria_producto ON producto.sub_categoria_producto = sub_categoria_producto.id
					WHERE
						producto.descripcion LIKE '%$texto%' AND
						producto.habilitado = 1
					ORDER BY producto.id ASC
				";
			}else if($_GET["criterio"] == "listado_completo"){
				$sql = "
						SELECT
							producto.id,
							producto.descripcion AS nombre,
							producto.precio_venta,
							producto.cantidad_existente,
							producto.ultimo_abastecimiento,
							sub_categoria_producto.nombre AS nombre_subcategoria
						FROM producto
						INNER JOIN sub_categoria_producto ON producto.sub_categoria_producto = sub_categoria_producto.id
						WHERE
							producto.habilitado = 1
						ORDER BY producto.id ASC
					";
			}else if($_GET["criterio"] == "subcategoria"){
				$subcategoria = $_GET["subcategoria"];
				$sql = "
						SELECT
							producto.id,
							producto.descripcion AS nombre,
							producto.precio_venta,
							producto.cantidad_existente,
							producto.ultimo_abastecimiento,
							sub_categoria_producto.nombre AS nombre_subcategoria
						FROM producto
						INNER JOIN sub_categoria_producto ON producto.sub_categoria_producto = sub_categoria_producto.id
						WHERE
							producto.sub_categoria_producto = $subcategoria AND
							producto.habilitado = 1
						ORDER BY producto.id ASC
					";
			}

			$resultado = $conexion->query($sql);
			if($resultado->num_rows > 0){
				while($row = $resultado->fetch_assoc()){
					$productos[] = array(
						"id" => $row["id"],
						"nombre" => $row["nombre"],
						"precio_venta" => $row["precio_venta"],
						"cantidad_existente" => $row["cantidad_existente"],
						"ultimo_abastecimiento" => reg_date($row["ultimo_abastecimiento"], "formato_corto"),
						"nombre_subcategoria" => $row["nombre_subcategoria"]
					);
				}
				
				$resultado = $conexion->query("SELECT SUM(cantidad_existente) AS productos_stock, SUM(cantidad_existente * precio_venta) AS dinero_stock FROM producto WHERE 1");
				$datos_completo[] = $productos;
				$datos_completo[] = $resultado->fetch_assoc();

				$mensaje_json = json_encode($datos_completo, JSON_UNESCAPED_UNICODE);
				echo $mensaje_json;
			}else{
				echo "¡NO SE HAN ECONTRADO RESULTADOS!";
			}
		}else{
			echo "¡NO SE HA DECLARADO LA CONDICIÓN A REALIZAR POR EL USUARIO!";
		}	
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>