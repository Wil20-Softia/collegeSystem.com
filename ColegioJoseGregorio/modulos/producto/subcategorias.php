<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/ModeloBase.php";
		$id_usuario = $_SESSION["id"];
		$mb = new ModeloBase("persona");
		$conexion = $mb->db();

		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){
			$condicion = strtolower($_GET["criterio"]);

			if($condicion == "registrar"){
				$nombre = $_GET["descripcion"];
				$categoria = $_GET["categoria_subcategoria"];

				$conexion->query("INSERT INTO sub_categoria_producto (nombre, categoria_producto) VALUES ('$nombre', $categoria)");

				$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE SUBCATERGORIA DE PRODUCTO',NOW(),NOW())");

				$datos = array('mensaje' => "¡REGISTRO EXITOSO!", 'advertencia' => 1);
				$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_json;
			}else if($condicion == "modificar"){
				$codigo = $_GET["codigo"];
				$nombre = $_GET["descripcion"];

				$conexion->query("UPDATE sub_categoria_producto SET nombre = '$nombre' WHERE id = $codigo");

				$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'MODIFICACION DE SUBCATERGORIA DE PRODUCTO',NOW(),NOW())");

				$datos = array('mensaje' => "¡MODIFICACIÓN EXITOSA!", 'advertencia' => 1);
				$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_json;
			}else if($condicion == "listado"){
				$resultado = $conexion->query("SELECT sub_categoria_producto.id, sub_categoria_producto.nombre, categoria_producto.nombre AS categoria FROM sub_categoria_producto INNER JOIN categoria_producto ON sub_categoria_producto.categoria_producto = categoria_producto.id WHERE 1");

				if($resultado->num_rows > 0){
					while($da = $resultado->fetch_assoc()){
						$id_subcat = $da["id"];
						
						$res = $conexion->query("SELECT COUNT(id) AS cant_productos FROM producto WHERE sub_categoria_producto = $id_subcat");
						$cp = $res->fetch_assoc();
						$cant_productos = $cp["cant_productos"];

						$datos[] = array(
							"id" => $id_subcat,
							"nombre" => $da["nombre"],
							"cant_productos" => $cant_productos,
							"categoria" => $da["categoria"]
						);
					}
					$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_js;
				}else{
					echo "¡No existen registros!";
				}
			}else{
				echo "¡Esta opción no es valida!";
			}
		}else{
			echo "¿QUE DESEA HACER CON LAS SUBCATEGORIAS?";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>