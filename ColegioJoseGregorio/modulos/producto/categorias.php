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

				$conexion->query("INSERT INTO categoria_producto (nombre) VALUES ('$nombre')");

				$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE CATERGORIA DE PRODUCTO',NOW(),NOW())");

				$datos = array('mensaje' => "¡REGISTRO EXITOSO!", 'advertencia' => 1);
				$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_json;
			}else if($condicion == "modificar"){
				$codigo = $_GET["codigo"];
				$nombre = $_GET["descripcion"];

				$conexion->query("UPDATE categoria_producto SET nombre = '$nombre' WHERE id = $codigo");

				$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'MODIFICACION DE CATERGORIA DE PRODUCTO',NOW(),NOW())");

				$datos = array('mensaje' => "¡MODIFICACIÓN EXITOSA!", 'advertencia' => 1);
				$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_json;
			}else if($condicion == "listado"){
				$resultado = $conexion->query("SELECT * FROM categoria_producto WHERE 1");

				if($resultado->num_rows > 0){
					while($da = $resultado->fetch_assoc()){
						$cant_productos = 0;
						$id_categoria = $da["id"];
						$r = $conexion->query("SELECT COUNT(id) AS cant_subcat FROM sub_categoria_producto WHERE categoria_producto = $id_categoria");
						$cant_subcat = $r->fetch_assoc();
						$cant_subcat = $cant_subcat["cant_subcat"];

						$re = $conexion->query("SELECT id FROM sub_categoria_producto WHERE categoria_producto = $id_categoria");
						if($cant_subcat > 0){
							while($d = $re->fetch_assoc()){
								$id_subcat = $d["id"];
								$res = $conexion->query("SELECT COUNT(id) AS cant_productos FROM producto WHERE sub_categoria_producto = $id_subcat");
								$cp = $res->fetch_assoc();
								$cant_productos += $cp["cant_productos"];
							}
						}

						$datos[] = array(
							"id" => $da["id"],
							"nombre" => $da["nombre"],
							"cant_subcategorias" => $cant_subcat,
							"cant_productos" => $cant_productos
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
			echo "¿QUE DESEA HACER CON LAS CATEGORIAS?";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>