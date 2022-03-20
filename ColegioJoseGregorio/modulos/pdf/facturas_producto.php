<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		require "../../config/Factura.php";
		require "../../config/EntidadBase.php";

		$entidad = new EntidadBase("tipo_factura");
		$conexion = $entidad->db();

		$id_ticket = $_GET["cf"];

		$data_productos = array();
		$data_tp_productos = array();
		$tc = $conexion->query("
				SELECT 
					tipo_comprador.tipo AS tipo_comprador 
				FROM factura_producto 
				INNER JOIN tipo_factura ON factura_producto.tipo_factura = tipo_factura.id
				INNER JOIN cliente ON tipo_factura.cliente = cliente.id
				INNER JOIN tipo_comprador ON tipo_comprador.cliente = cliente.id
				WHERE 
					factura_producto.id = $id_ticket");

		if($tc->num_rows == 1){
			$idtc = $tc->fetch_assoc();
			$tipo_comprador = $idtc["tipo_comprador"];

			$sql_productos = $conexion->query("
					SELECT
						producto.descripcion,
						productos_facturas.cantidad,
						precio_producto.precio_venta AS precio_producto,
						productos_facturas.importe
					FROM
						factura_producto
					INNER JOIN productos_facturas ON productos_facturas.factura_producto = factura_producto.id
					INNER JOIN producto ON productos_facturas.producto = producto.id
					INNER JOIN precio_producto ON productos_facturas.precio_producto = precio_producto.id
					WHERE 
						factura_producto.id = $id_ticket
			");

			if($sql_productos->num_rows > 0){
				while($row = $sql_productos->fetch_assoc())
            		$data_productos[] = $row;

            	$sql_tipo_pagos_productos = $conexion->query("
					SELECT
						tipo_pago.nombre,
						pago_factura.referencia,
						pago_factura.monto
					FROM
						pago_factura
					INNER JOIN tipo_factura ON pago_factura.tipo_factura = tipo_factura.id
					INNER JOIN factura_producto ON factura_producto.tipo_factura = tipo_factura.id
					INNER JOIN tipo_pago ON pago_factura.tipo_pago = tipo_pago.id
					WHERE 
						factura_producto.id = $id_ticket
				");

				if($sql_tipo_pagos_productos->num_rows > 0){
					while($row = $sql_tipo_pagos_productos->fetch_assoc())
	            		$data_tp_productos[] = $row;

	            	$sql_datos_importante = $conexion->query("
						SELECT
							factura_producto.id AS codigo,
							tipo_factura.usuario AS vendedor,
							tipo_factura.fecha,
							tipo_factura.hora,
							$tipo_comprador.cedula,
							$tipo_comprador.primer_nombre,
							$tipo_comprador.primer_apellido,
							factura_producto.cantidad_productos,
							factura_producto.diferencia,
							tipo_factura.monto_total
						FROM factura_producto
						INNER JOIN tipo_factura ON factura_producto.tipo_factura = tipo_factura.id
						INNER JOIN cliente ON tipo_factura.cliente = cliente.id
						INNER JOIN tipo_comprador ON tipo_comprador.cliente = cliente.id
						INNER JOIN $tipo_comprador ON $tipo_comprador.tipo_comprador = tipo_comprador.id
						WHERE 
							factura_producto.id = $id_ticket
					");

					$datos = $sql_datos_importante->fetch_assoc();

					//79 => alto sin ningun contenido CONSTANTE
					//5 => por cada producto
					//3.5 => por cada tipo de pago
					$alto_pagina = 79 + (count($data_productos)*5) + (count($data_tp_productos)*3.5);

					$fact = new Factura("producto",$alto_pagina);
					$fact->establecerCodigo($datos["codigo"]);
					$fact->establecerIdUsuario($datos["vendedor"]);
					$fact->establecerFechaCompleta($datos["fecha"],$datos["hora"]);
					$fact->establecerCedulaCliente($datos["cedula"]);
					$fact->establecerNombreCliente($datos["primer_nombre"], $datos["primer_apellido"]);
					$fact->establecerProductos($data_productos);
					$fact->establecerTiposPagoProducto($data_tp_productos);
					$fact->establecerTotalProductos($datos["cantidad_productos"]);
					$fact->establecerDiferenciaFactura($datos["diferencia"]);
					$fact->establecerMontoTotal($datos["monto_total"]);

					$fact->dibujarFacturaProducto();
				}else{
					$fact = new Factura("producto",40);
					$fact->SetFont("Arial","BI",9);
					$fact->Cell(0, 15, utf8_decode("¡NO SE ENCONTRARON TIPOS DE PAGO!"),0,1,"C");
				}
			}else{
				$fact = new Factura("producto",40);
				$fact->SetFont("Arial","BI",9);
				$fact->Cell(0, 15, utf8_decode("¡NO SE ENCONTRARON PRODUCTOS!"),0,1,"C");
			}
		}else{
			$fact = new Factura("producto",40);
			$fact->SetFont("Arial","BI",9);
			$fact->Cell(0, 15, utf8_decode("¡NO SE ENCONTRO NINGÚNA FACTURA"),0,1,"C");
			$fact->Cell(0, 15, utf8_decode("DE DICHA BUSQUEDA!"),0,1,"C");
		}
		$fact->Output("","ticket_venta.pdf");

	}else{
		echo "¡ACCESO DENEGADO!";
	}

?>