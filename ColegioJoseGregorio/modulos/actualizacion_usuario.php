<?php

	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../config/EntidadBase.php";
		require_once "../config/functions.php";

		date_default_timezone_set ("America/Caracas"); 
		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$mensaje = "";
		$advertencia = 0;

		$en = new EntidadBase("usuario");

		$conexion = $en->db();
		$id_usuario = $_SESSION["id"];
		//SE VERIFICA LA VARIABLE CRITERIO PARA VER SI EXISTE O NO ESTE VACIA
		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			//SE TOMA LA VARIABLE CRITERIO Y SU VALOR SE PASA A MINUSCULAS
			$condicion = strtolower($_GET["criterio"]);

			if($condicion == "datos"){

				$nombre = $_GET["nombre"];
				$apellido = $_GET["apellido"];
				$cedula = $_GET["cedula"];
				$sexo = $_GET["sexo"];
				$correo = $_GET["correo"];

				$resultado = $conexion->query("UPDATE usuario SET primer_nombre = '$nombre', primer_apellido = '$apellido', sexo = '$sexo' WHERE id = $id_usuario AND cedula = '$cedula' AND correo = '$correo'");

				if($resultado){
					$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'ACTUALIZACIÓN DE DATOS',NOW(),NOW())");

					$r = $conexion->query("SELECT usuario.id, usuario.cedula, CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS nombre_completo, usuario.primer_nombre AS nombre, usuario.primer_apellido AS apellido, usuario.tipo, usuario.sexo, usuario.ultima_sesion FROM usuario WHERE usuario.id = $id_usuario AND usuario.habilitado = 1");
					$d = $r->fetch_assoc();

					$_SESSION["tipo"] = $d["tipo"];
					$_SESSION["id"] = $d["id"];
					$_SESSION["nombre_completo"] = $d["nombre_completo"];
					$_SESSION["cedula"] = $d["cedula"];
					$_SESSION["primer_nombre"] = $d["nombre"];
					$_SESSION["primer_apellido"] = $d["apellido"];
					if($d["sexo"] == "FEMENINO"){
						$_SESSION["imagen"] = "./img/femenino.png";
						$_SESSION["identificador"] = "Administradora: ";
					}else{
						$_SESSION["imagen"] = "./img/masculino.png";
						$_SESSION["identificador"] = "Administrador: ";
					}
					$_SESSION["ultima_sesion"] = $d["ultima_sesion"];
					$datos = array(
						'mensaje' => "¡DATOS ACTUALIZADOS CORRECTAMENTE!", 
						'advertencia' => 1,
						"id" => $_SESSION["id"],
						"nombre" => $_SESSION["nombre_completo"],
						"logo" => $_SESSION["imagen"],
						"identificador" =>$_SESSION["identificador"],
						"ultima_sesion" => $_SESSION["ultima_sesion"],
						"tipo" => $_SESSION["tipo"]
					);
					$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_js;
				}else{
					echo "¡FALLO EN LA ACTUALIZACION DE DATOS DEL USUARIO!";
				}
			}else if($condicion == "password"){

				$pass_vieja = $_GET["pass_vieja"];
				$pass_nueva = $_GET["pass_nueva"];

				$re = $conexion->query("SELECT usuario.password FROM usuario WHERE usuario.id = $id_usuario AND usuario.habilitado = 1");

				if(strlen($pass_nueva) >= 8){
					$d = $re->fetch_assoc();
					$password_usuario = $d["password"];

					if(desencryp_pass($password_usuario, $pass_vieja)){
						$pass_encry = encryp_pass($pass_nueva);
						$resultado = $conexion->query("UPDATE usuario SET password = '$pass_encry' WHERE id = $id_usuario");
						if($resultado){
							$conexion->query("INSERT INTO historial_tareas(usuario, descripcion, fecha, hora) VALUES ($id_usuario,'CAMBIO DE CONTRASEÑA',NOW(),NOW())");

							$datos = array(
								'mensaje' => "¡PASSWORD ACTUALIZADA CORRECTAMENTE!", 
								'advertencia' => 1,
								"id" => $_SESSION["id"],
								"nombre" => $_SESSION["nombre_completo"],
								"logo" => $_SESSION["imagen"],
								"identificador" =>$_SESSION["identificador"],
								"ultima_sesion" => $_SESSION["ultima_sesion"],
								"tipo" => $_SESSION["tipo"]
							);
							$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
							echo $datos_js;
						}else{
							echo "¡FALLO EN LA PASSWORD!";
						}
					}else{
						echo "¡CONTRASEÑA ACTUAL INVALIDA!";
					}
				}else{
					echo "LA CONTRASEÑA DEBE TENER 8 CARACTERES MINIMOS!";
				}
			}else{
				echo "Esta opción no es valida";
			}
		}else{
			echo "Elija que desea realizar";
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}

?>