<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/ModeloBase.php";
		require_once "../../config/functions.php";

		date_default_timezone_set ("America/Caracas"); 
		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$mensaje = "";
		$advertencia = 0;

		$en = new ModeloBase("usuario");

		$conexion = $en->db();

		//SE VERIFICA LA VARIABLE CRITERIO PARA VER SI EXISTE O NO ESTE VACIA
		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			//SE TOMA LA VARIABLE CRITERIO Y SU VALOR SE PASA A MINUSCULAS
			$condicion = strtolower($_GET["criterio"]);

			//SI EL CRITERIO ES DE LISTADO
			if($condicion == "listado"){
				$usuarios = $en->ejecutarSql("
					SELECT 
						usuario.id, 
						usuario.cedula, 
						usuario.correo, 
						usuario.primer_nombre AS nombre, 
						usuario.primer_apellido AS apellido, 
						usuario.sexo, 
						tipo_comprador.ultima_modificacion AS registrado 
					FROM usuario 
					INNER JOIN tipo_comprador ON usuario.tipo_comprador = tipo_comprador.id 
					WHERE 
						usuario.habilitado = 1 AND 
						usuario.tipo = 2 
					ORDER BY usuario.id ASC");
				
				if(!$usuarios){
					$mensaje = "NO EXISTE NINGUN REGISTRO!";
					echo $mensaje;
				}else{
					$mensaje_json = json_encode($usuarios, JSON_UNESCAPED_UNICODE);
					echo $mensaje_json;
				}

			//SI EL CRITERIO ES DE REGISTRAR UNA MENSUALIDAD
			}else if($condicion == "registrar"){

				$nombre = $_GET["nombre"];
				$apellido = $_GET["apellido"];
				$cedula = $_GET["cedula"];
				$correo = $_GET["correo"];
				$sexo = $_GET["sexo"];

				$sql = "SELECT id FROM usuario WHERE (correo = '$correo' OR cedula = '$cedula') AND habilitado != 0";
				$resultado = $conexion->query($sql);

				if($resultado->num_rows == 0){
					$id_tipo_comprador = $en->registrarComprador("usuario");

					$pass_encry = encryp_pass('12345678');

					$verificacion_usuario_cedula = $conexion->query("SELECT id FROM usuario WHERE cedula = '$cedula'");


					$verificacion_usuario_correo = $conexion->query("SELECT id FROM usuario WHERE correo = '$correo'");

					if($verificacion_usuario_correo->num_rows == 0){
						if($verificacion_usuario_cedula->num_rows == 1){
							$conexion->query("UPDATE usuario SET correo = '$correo', primer_nombre = '$nombre', primer_apellido = '$apellido', sexo = '$sexo', habilitado = 1, ultima_sesion = '', password = '$pass_encry', conectado = 0 WHERE cedula = '$cedula'");
						}else{
							$registro_usuario = $conexion->query("
								INSERT INTO usuario (cedula, correo, primer_nombre, primer_apellido, sexo, tipo, habilitado, ultima_sesion, tipo_comprador, password, conectado) VALUES ('$cedula','$correo','$nombre','$apellido','$sexo',2,1,'',$id_tipo_comprador,'$pass_encry',0)");
						}

						$datos = array(
							'mensaje' => "¡USUARIO REGISTRADO CORRECTAMENTE!", 
							'advertencia' => 1,
							"id" => $_SESSION["id"],
							"nombre" => $_SESSION["nombre_completo"],
							"logo" => $_SESSION["imagen"],
							"identificador" => $_SESSION["identificador"],
							"ultima_sesion" => $_SESSION["ultima_sesion"],
							"tipo" => $_SESSION["tipo"]
						);
						$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
						echo $datos_js;
					}else{
						echo "¡CORREO YA EXISTENTE!";
					}
				}else{
					echo "¡CORREO O CEDULA DEL USUARIO, YA EXISTENTE! INTENTE CON OTRO.";
				}
			}else if($condicion == "deshabilitar"){
				$resultado = $conexion->query("UPDATE usuario SET habilitado = 0 WHERE id = " . $_GET["id_usuario"]);
				$datos = array('mensaje' => "¡Usuario Deshabilitado!", 'advertencia' => 1);
				$datos_js = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_js;
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