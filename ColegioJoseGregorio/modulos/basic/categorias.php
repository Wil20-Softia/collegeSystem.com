<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("categoria_grado");

	$categorias = json_encode($mb->ejecutarSql("SELECT * FROM categoria_grado WHERE 1 ORDER BY id ASC"), JSON_UNESCAPED_UNICODE);

	echo $categorias;
?>