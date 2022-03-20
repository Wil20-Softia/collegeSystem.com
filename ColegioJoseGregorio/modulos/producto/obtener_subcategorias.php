<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("sub_categoria_producto");

	if(isset($_GET["categoria"]) && !empty($_GET["categoria"])){
		$subcategorias = $mb->ejecutarSql("SELECT id, nombre FROM sub_categoria_producto WHERE categoria_producto = ". $_GET["categoria"] ." ORDER BY id ASC");
		
	  	if(!$subcategorias){
			echo "NO EXISTE NINGUN REGISTRO!";
		}else{
			$mensaje_json = json_encode($subcategorias, JSON_UNESCAPED_UNICODE);
			echo $mensaje_json;
		}
	}else{
		echo 0;
	}	
?>