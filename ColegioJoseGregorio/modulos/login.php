<?php
	
	require_once "../config/EntidadBase.php";
	require_once "../config/functions.php";
	
	$entidad = new EntidadBase("usuario");
	$conexion = $entidad->db();

	$usuario = $_GET["usuario"];
	$pass = $_GET["pass"];

	$r = $conexion->query("
			SELECT 
				usuario.id, 
				usuario.cedula, 
				usuario.correo, 
				CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS nombre_completo, 
				usuario.primer_nombre AS nombre, 
				usuario.primer_apellido AS apellido, 
				usuario.tipo, 
				usuario.sexo, 
				usuario.ultima_sesion, 
				usuario.password 
			FROM usuario 
			WHERE 
				usuario.correo LIKE '$usuario' AND  
				usuario.habilitado = 1");
	if($r->num_rows == 1){
		$d = $r->fetch_assoc();
		$password_usuario = $d["password"];
		if(desencryp_pass($password_usuario, $pass)){
			$re = $conexion->query("SELECT usuario.id FROM usuario WHERE usuario.correo LIKE '$usuario' AND usuario.conectado = 0");
			if($re->num_rows == 1){
				$id_usuario = $re->fetch_assoc();
				$id_usuario = $id_usuario["id"];
				$conexion->query("UPDATE usuario SET conectado = 1 WHERE id = $id_usuario");
				session_start();
				$_SESSION["tipo"] = $d["tipo"];
				$_SESSION["id"] = $id_usuario;
				$_SESSION["nombre_completo"] = $d["nombre_completo"];
				$_SESSION["cedula"] = $d["cedula"];
				$_SESSION["correo"] = $d["correo"];
				$_SESSION["primer_nombre"] = $d["nombre"];
				$_SESSION["primer_apellido"] = $d["apellido"];
				$_SESSION["sexo"] = $d["sexo"];
				if($d["sexo"] == "FEMENINO"){
					$_SESSION["imagen"] = "./img/femenino.png";
					$_SESSION["identificador"] = "Administradora: ";
				}else{
					$_SESSION["imagen"] = "./img/masculino.png";
					$_SESSION["identificador"] = "Administrador: ";
				}
				$date = new DateTime($d["ultima_sesion"]);
				$ultima_sesion = $date->format('d/m/Y h:i:s a');
				$_SESSION["ultima_sesion"] = $ultima_sesion;
				$datos = array(
					"id" => $_SESSION["id"],
					"nombre" => $_SESSION["nombre_completo"],
					"logo" => $_SESSION["imagen"],
					"identificador" =>$_SESSION["identificador"],
					"ultima_sesion" => $_SESSION["ultima_sesion"],
					"tipo" => $_SESSION["tipo"]
				);

				$d = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $d;
			}else{
				echo "¡ESTA SESIÓN YA ESTA EN USO EN OTRO DISPOSITIVO!";
			}
		}else{
			echo "¡USUARIO O CONTRASEÑA INVALIDA!";
		}
	}else{
		echo "¡USUARIO O CONTRASEÑA INVALIDA!";
	}
?>