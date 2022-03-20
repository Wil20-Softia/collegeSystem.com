<?php
	require_once "Conectar.php";
	require_once "functions.php";
	$obj = new Conectar();

	if($obj->restauracion($_FILES["file_restaurar"])){
		$advertencia = 1;
		$mensaje = "Se ha restaurado el Sistema Con Exito.
		En un Momento se recargará la Pagina.";
		
		$dir_file = $_SERVER["SCRIPT_FILENAME"];
        $dir_file = explode("/", $dir_file);
        $folder_proyect = $dir_file[0] ."/".$dir_file[1]."/".$dir_file[2]."/".$dir_file[3];
        $destiny_folder = $folder_proyect . "/backups/";
        
		delete_folder($destiny_folder);

		$obj->conexion()->query("UPDATE usuario SET conectado = 0 WHERE 1");
	}else{
		$advertencia = 0;
		$mensaje = "Ha Ocurrido un ERROR FATAL. Revise la Base de Datos del Sistema";
	}

	$datos = array('mensaje' => $mensaje, 'advertencia' => $advertencia);
	$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
	echo $datos_json;
?>