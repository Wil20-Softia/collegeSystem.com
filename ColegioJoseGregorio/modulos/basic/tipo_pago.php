<?php
	require_once "../../config/ModeloBase.php";

	$mb = new ModeloBase("tipo_pago");

	$tipos_pago = json_encode($mb->ejecutarSql("SELECT * FROM tipo_pago WHERE 1 ORDER BY id ASC"), JSON_UNESCAPED_UNICODE);

	echo $tipos_pago;
?>