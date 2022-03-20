<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("grado");

	if(isset($_GET["categoria"])){
		
		if($_GET["categoria"] != 0){
			$grados = json_encode($mb->ejecutarSql("SELECT id, nombre FROM grado WHERE categoria_grado = ". $_GET["categoria"]." ORDER BY id ASC"), JSON_UNESCAPED_UNICODE);
		}else{
			$grados = json_encode($mb->ejecutarSql("SELECT id, nombre FROM grado WHERE 1 ORDER BY id ASC"), JSON_UNESCAPED_UNICODE);
		}

	  	echo $grados;
	}else{
		echo 0;
	}	
?>