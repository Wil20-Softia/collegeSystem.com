<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("categoria_producto");

	$categorias = $mb->ejecutarSql("SELECT * FROM categoria_producto WHERE 1 ORDER BY id ASC");
	if(!$categorias){
		echo "NO EXISTE NINGUN REGISTRO!";
	}else{
		$mensaje_json = json_encode($categorias, JSON_UNESCAPED_UNICODE);
		echo $mensaje_json;
	}	
?>