<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas"); 
		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$mensaje = "";
		$advertencia = 0;
		$registros = 0;
		require_once "../config/EntidadBase.php";
		require_once "../config/functions.php";
		
		$id_usuario = $_SESSION["id"];
		//1.
		//DATOS DEL ESTUDIANTE.
		$ec = $_POST["estudiante_cedula"];
		$e1n = $_POST["estudiante_primer_nombre"]; 
	    $e2n = $_POST["estudiante_segundo_nombre"];
	    $e1a = $_POST["estudiante_primer_apellido"];
	    $e2a = $_POST["estudiante_segundo_apellido"];

	    //DATOS DEL REPRESENTANTE.
	    $r1n = $_POST["representante_primer_nombre"];
	    $r2n = $_POST["representante_segundo_nombre"];
	    $r1a = $_POST["representante_primer_apellido"];
	    $r2a = $_POST["representante_segundo_apellido"];
	    $rt = $_POST["representante_telefono"];
	    $rc = $_POST["representante_cedula"];

	    $est_cedulado = $_POST["estudiante_cedulado"];
	    $modi_cedEst = $_POST["modificar_cedula"];

	    $id_estudiante = $_POST["id_estudiante"];

		$entidad = new EntidadBase("estudiante");

		$conexion = $entidad->db();

		if($est_cedulado == 1 || ($est_cedulado == 0 && $modi_cedEst == 0)){
			$resultado = $conexion->query("SELECT id FROM estudiante WHERE cedula = '$ec' AND habilitado != 0");

			if($resultado->num_rows == 1){
				$sql_modificar = "
					UPDATE estudiante 
					INNER JOIN representante ON estudiante.representante = representante.id 
					INNER JOIN tipo_comprador ON estudiante.tipo_comprador = tipo_comprador.id 
					SET 
						estudiante.primer_nombre='$e1n', 
						estudiante.segundo_nombre='$e2n',
						estudiante.primer_apellido='$e1a',
						estudiante.segundo_apellido='$e2a',
						tipo_comprador.ultima_modificacion = NOW(),
						representante.primer_nombre='$r1n', 
						representante.segundo_nombre='$r2n', 
						representante.primer_apellido='$r1a', 
						representante.segundo_apellido='$r2a', 
						representante.telefono='$rt' 
					WHERE
						estudiante.cedula = '$ec'";	

				$sql_modificar_prin = "UPDATE estudiante INNER JOIN representante ON estudiante.representante = representante.id INNER JOIN tipo_comprador ON estudiante.tipo_comprador = tipo_comprador.id SET estudiante.primer_nombre='$e1n', estudiante.segundo_nombre='$e2n', estudiante.primer_apellido='$e1a', estudiante.segundo_apellido='$e2a', tipo_comprador.ultima_modificacion = NOW(), representante.primer_nombre='$r1n', representante.segundo_nombre='$r2n', representante.primer_apellido='$r1a', representante.segundo_apellido='$r2a', representante.telefono='$rt', ";

				$sql_modificar_fin = " WHERE estudiante.cedula = '$ec'";

			}else{
				$sql_modificar = "";
				echo "¡ESTUDIANTE NO EXISTENTE!";
			}
		}else if($est_cedulado == 0 && $modi_cedEst == 1){
			$resultado = $conexion->query("SELECT id FROM estudiante WHERE cedula = '$ec' AND habilitado != 0");

			if($resultado->num_rows == 0){
				$resultado = $conexion->query("SELECT id FROM estudiante WHERE cedula = '$ec' AND habilitado = 0");
				if($resultado->num_rows == 0){
					$sql_modificar = "
						UPDATE estudiante 
						INNER JOIN representante ON estudiante.representante = representante.id 
						INNER JOIN tipo_comprador ON estudiante.tipo_comprador = tipo_comprador.id 
						SET 
							estudiante.cedula='$ec',
							estudiante.primer_nombre='$e1n', 
							estudiante.segundo_nombre='$e2n',
							estudiante.primer_apellido='$e1a',
							estudiante.segundo_apellido='$e2a',
							estudiante.cedulado=1,
							tipo_comprador.ultima_modificacion = NOW(),
							representante.primer_nombre='$r1n', 
							representante.segundo_nombre='$r2n', 
							representante.primer_apellido='$r1a', 
							representante.segundo_apellido='$r2a', 
							representante.telefono='$rt' 
						WHERE
							estudiante.id = $id_estudiante";

					$sql_modificar_prin = "UPDATE estudiante INNER JOIN representante ON estudiante.representante = representante.id INNER JOIN tipo_comprador ON estudiante.tipo_comprador = tipo_comprador.id SET estudiante.cedula='$ec', estudiante.primer_nombre='$e1n', estudiante.segundo_nombre='$e2n', estudiante.primer_apellido='$e1a', estudiante.segundo_apellido='$e2a', estudiante.cedulado=1, tipo_comprador.ultima_modificacion = NOW(), representante.primer_nombre='$r1n', representante.segundo_nombre='$r2n', representante.primer_apellido='$r1a', representante.segundo_apellido='$r2a', representante.telefono='$rt', ";

					$sql_modificar_fin = " WHERE estudiante.id = $id_estudiante";

				}else{
					$sql_modificar = "";
					echo "¡CEDULA A MODIFICAR YA REGISTRADA EN ESTUDIANTE ELIMINADO, PERO CON FACTURAS EN EL SISTEMA, INTENTE CON OTRA!";
				}	
			}else{
				$sql_modificar = "";
				echo "¡CEDULA A MODIFICAR YA REGISTRADA EN ESTUDIANTE ACTIVO!";
			}
		}

		$ver_cedRepEst = $conexion->query("SELECT estudiante.cedula FROM estudiante INNER JOIN representante ON estudiante.representante = representante.id WHERE estudiante.id = $id_estudiante AND representante.cedula = '$rc'");

		if($ver_cedRepEst->num_rows == 1){
			if($sql_modificar != ""){
				$resultado = $conexion->query($sql_modificar);

				if($resultado){
					$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario, 'MODIFICACIÓN DE DATOS DEL ESTUDIANTE',NOW(),NOW())");

					$mensaje = "¡LOS DATOS DEL ESTUDIANTE HAN SIDO ACTUALIZADOS!";
					$advertencia = 1;
					$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
					$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
					echo $datos_json;
				}else{
					echo "ERROR 1: ". $conexion->error;
				}
			}
		}else if($ver_cedRepEst->num_rows == 0){
			$ver_cedRep = $conexion->query("SELECT id FROM representante WHERE cedula = '$rc'");
			if($ver_cedRep->num_rows == 1){
				$id_representante = $ver_cedRep->fetch_assoc();
				$id_representante = $id_representante["id"];
			}else{
				$id_comprador = $entidad->registrarComprador("representante");
				$conexion->query("INSERT INTO representante (cedula, tipo_comprador, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, telefono) VALUES ('$rc',$id_comprador,'$r1n','$r2n','$r1a','$r2a','$rt')");
				$id_representante = $conexion->insert_id;
			}

			$r_modEst = $conexion->query($sql_modificar_prin . "estudiante.representante = $id_representante" . $sql_modificar_fin);

			if($r_modEst){
				$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario, 'MODIFICACIÓN DE DATOS DEL ESTUDIANTE, REPRESENTANTE ACTUALIZADO',NOW(),NOW())");

				$mensaje = "¡LOS DATOS DEL ESTUDIANTE HAN SIDO ACTUALIZADOS!";
				$advertencia = 1;
				$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
				$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
				echo $datos_json;
			}else{
				echo "ERROR 1: ". $conexion->error;
			}
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>