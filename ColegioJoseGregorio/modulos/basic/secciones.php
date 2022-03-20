<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("seccion");

	$secciones = json_encode($mb->ejecutarSql("SELECT * FROM seccion ORDER BY seccion.nombre ASC"), JSON_UNESCAPED_UNICODE);

	echo $secciones;	
?>