<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require_once "../../config/EntidadBase.php";
		require_once "../../config/functions.php";
		
		$id_usuario = $_SESSION["id"];

		$entidad = new EntidadBase("tipo_factura");
		$conexion = $entidad->db();

		$cantidad_totalPro = 0;

		$superdata = $_POST["superdata"];
		$array_superdata = json_decode($superdata, true, 512, JSON_BIGINT_AS_STRING);

		$datos_generales = $array_superdata[0]; 
		$id_comprador = $datos_generales["id_comprador"];
		$monto_total = $datos_generales["monto_total"];
		$diferencia_factura = $datos_generales["diferencia"];

		$productos_comprados = $array_superdata[1];
		$tam_pro_comp = count($productos_comprados);

		$tipos_pagos = $array_superdata[2];
		$tam_tp = count($tipos_pagos);

		$re = $conexion->query("SELECT cliente FROM tipo_comprador WHERE id = $id_comprador");
		$te = $re->fetch_assoc();
		$id_cliente = $te["cliente"];

		$conexion->query("INSERT INTO tipo_factura (tipo, cliente, fecha, hora, monto_total, usuario) VALUES ('p', $id_cliente,NOW(),NOW(),$monto_total,$id_usuario)");
		$id_tipo_factura = $conexion->insert_id;

		for($i = 0; $i < $tam_pro_comp; $i++){
			$cantidad = $productos_comprados[$i]["cantidad"];
			$cantidad_totalPro += $cantidad;	
		}

		$conexion->query("INSERT INTO factura_producto (tipo_factura, cantidad_productos, diferencia) VALUES ($id_tipo_factura, $cantidad_totalPro, $diferencia_factura)");
		$id_factura = $conexion->insert_id;
		$cantidad_totalPro = 0;

		for($i = 0; $i < $tam_tp; $i++){
			$tp = $tipos_pagos[$i]["id_tp"];
			$rtp = $tipos_pagos[$i]["referencia_tp"];
			$ctp = $tipos_pagos[$i]["cantidad_tp"];

			if($tp == 1){
				$conexion->query("INSERT INTO referencia_efectivo(tipo_factura) VALUES ($id_tipo_factura)");
				$referencia = $conexion->insert_id;
			}else if($tp == 5){
				$conexion->query("INSERT INTO referencia_pago_nomina (tipo_factura) VALUES ($id_tipo_factura)");
				$referencia = $conexion->insert_id;
			}else{
				$referencia = $rtp;
			}
			$conexion->query("INSERT INTO pago_factura (tipo_factura, tipo_pago, referencia, monto) VALUES ($id_tipo_factura,$tp,'$referencia',$ctp)");
		}

		for($i = 0; $i < $tam_pro_comp; $i++){
			$id_producto = $productos_comprados[$i]["id_producto"];
			$cantidad = (int)$productos_comprados[$i]["cantidad"];

			$re = $conexion->query("SELECT id, precio_venta FROM precio_producto WHERE producto = $id_producto ORDER BY id DESC LIMIT 0,1");
			$pv = $re->fetch_assoc();
			$precio = (float)$pv["precio_venta"];
			$id_precio = $pv["id"];

			$importe = $cantidad * $precio;

			$conexion->query("UPDATE producto SET cantidad_existente = cantidad_existente - $cantidad WHERE id = $id_producto");
		
			$conexion->query("INSERT INTO productos_facturas (cantidad, producto, precio_producto, importe, factura_producto) VALUES ($cantidad,$id_producto,$id_precio,$importe,$id_factura)");
					
		}

		$conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'VENTA DE PRODUCTOS. FACTURACIÓN',NOW(),NOW())");

		$datos = array('mensaje' => "¡FACTURA REALIZADA CON EXITO! ESPERE UN MOMENTO PARA MOSTRARLA", 'advertencia' => 1, 'codigo_factura' => $id_factura);
		$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
		echo $datos_json;
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>