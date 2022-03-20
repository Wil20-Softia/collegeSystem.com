<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		setlocale(LC_ALL,'es_VE.UTF-8');
		date_default_timezone_set ("America/Caracas"); 

		require_once "../../config/ModeloBase.php";
		include "../../config/functions.php";

		$mb = new ModeloBase("factura_producto");
		$conexion = $mb->db();
		$b = 0;
		$mensaje = "";

		if(isset($_GET["criterio"]) && !empty($_GET["criterio"])){

			if($_GET["criterio"] == "busqueda"){
				$texto = $_GET["busqueda"];
				$resultado = $conexion->query("
					SELECT 
						tipo_comprador.cliente 
					FROM tipo_comprador 
					LEFT JOIN estudiante ON estudiante.tipo_comprador = tipo_comprador.id
					LEFT JOIN representante ON representante.tipo_comprador = tipo_comprador.id
					LEFT JOIN usuario ON usuario.tipo_comprador = tipo_comprador.id
					LEFT JOIN persona ON persona.tipo_comprador = tipo_comprador.id
					WHERE 
						estudiante.cedula = '$texto' OR 
						representante.cedula = '$texto' OR 
						persona.cedula = '$texto' OR 
						usuario.cedula = '$texto'");
				if($resultado->num_rows == 1){
					$ic = $resultado->fetch_assoc();
					$id_cliente = $ic["cliente"];
					$sql = "
						SELECT
							factura_producto.id,
							factura_producto.cantidad_productos,
							tipo_factura.fecha,
							tipo_factura.monto_total,
							CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS usuario,
							tipo_factura.cliente
						FROM factura_producto
						INNER JOIN tipo_factura ON factura_producto.tipo_factura = tipo_factura.id
						INNER JOIN usuario ON tipo_factura.usuario = usuario.id
						WHERE
							tipo_factura.tipo = 'p' AND 
							tipo_factura.cliente = $id_cliente
						ORDER BY factura_producto.id ASC
					";
				}else if($resultado->num_rows == 0){
					$sql = "
						SELECT
							factura_producto.id,
							factura_producto.cantidad_productos,
							tipo_factura.fecha,
							tipo_factura.monto_total,
							CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS usuario,
							tipo_factura.cliente
						FROM factura_producto
						INNER JOIN tipo_factura ON factura_producto.tipo_factura = tipo_factura.id
						INNER JOIN usuario ON tipo_factura.usuario = usuario.id
						WHERE
							tipo_factura.tipo = 'p' AND 
							factura_producto.id = $texto
						ORDER BY factura_producto.id ASC
					";
				}
			}else if($_GET["criterio"] == "listado_completo"){
				$sql = "
					SELECT
						factura_producto.id,
						factura_producto.cantidad_productos,
						tipo_factura.fecha,
						tipo_factura.monto_total,
						CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS usuario,
						tipo_factura.cliente
					FROM factura_producto
					INNER JOIN tipo_factura ON factura_producto.tipo_factura = tipo_factura.id
					INNER JOIN usuario ON tipo_factura.usuario = usuario.id
					WHERE
						tipo_factura.tipo = 'p'
					ORDER BY factura_producto.id ASC
				";
			}else if($_GET["criterio"] == "rango_fechas"){
				$fecha_desde = $_GET["fecha_desde"];
				$fecha_hasta = $_GET["fecha_hasta"];

				$fecha_actual = strtotime(date("Y-m-d"));

				if(empty($fecha_desde) && empty($fecha_hasta)){
					$mensaje = "INTRODUZCA LAS FECHAS PARA CONTINUAR!";
					$b = 1;
				}else if(!empty($fecha_desde) && !empty($fecha_hasta)){
					if(strtotime($fecha_desde) > $fecha_actual){
						$mensaje = "LA FECHA DE INICIO NO DEBE SER MAYOR A LA ACTUAL";
						$b = 1;
					}else if(strtotime($fecha_hasta) < strtotime($fecha_desde)){
						$mensaje = "LA FECHA FINAL NO DEBE SER MENOR A LA FECHA DE INICIO";
						$b = 1;
					}else if(strtotime($fecha_hasta) > $fecha_actual){
						$fecha_hasta = date("Y-m-d");
					}
				}else if(!empty($fecha_desde) && empty($fecha_hasta)){
					if(strtotime($fecha_desde) > $fecha_actual){
						$mensaje = "LA FECHA DE INICIO NO DEBE SER MAYOR A LA ACTUAL";
						$b = 1;
					}else{
						$fecha_hasta = date("Y-m-d");
					}
				}else if(empty($fecha_desde) && !empty($fecha_hasta)){
					$mensaje = "POR LO MENOS, DEBE HABER UNA FECHA DE INICIO.";
					$b = 1;
				}

				if($b == 0){
					$sql = "
						SELECT
							factura_producto.id,
							factura_producto.cantidad_productos,
							tipo_factura.fecha,
							tipo_factura.monto_total,
							CONCAT(usuario.primer_nombre,' ',usuario.primer_apellido) AS usuario,
							tipo_factura.cliente
						FROM factura_producto
						INNER JOIN tipo_factura ON factura_producto.tipo_factura = tipo_factura.id
						INNER JOIN usuario ON tipo_factura.usuario = usuario.id
						WHERE
							tipo_factura.tipo = 'p' AND
							tipo_factura.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'
						ORDER BY factura_producto.id ASC
					";
				}else{
					echo $mensaje;
				}
			}else{
				echo "¡NO EXISTE EL CRITERIO ESCOGIDO!";
				$b = 1;
			}

			if($b == 0){
				$resultado = $conexion->query($sql);
				if($resultado->num_rows > 0){
					while($row = $resultado->fetch_assoc()){
						$id_cliente = $row["cliente"];
						$re = $conexion->query("SELECT tipo, id FROM tipo_comprador WHERE cliente = $id_cliente");
						$datos_comprador = $re->fetch_assoc();

						$tipo_comprador = $datos_comprador["tipo"];
						$id_comprador = $datos_comprador["id"];

						$res = $conexion->query("SELECT cedula FROM $tipo_comprador WHERE tipo_comprador = $id_comprador");
						$cedula_cliente = $res->fetch_assoc();
						$cedula_cliente = $cedula_cliente["cedula"];

						$total = str_replace(",","",$row["monto_total"]);
						$total = number_format($total,2,',','.');

						$facturas[] = array(
							"id" => $row["id"],
							"fecha" => reg_date($row["fecha"], "formato_corto"),
							"monto_total" => $total,
							"cant_productos" => $row["cantidad_productos"],
							"usuario" => $row["usuario"],
							"cliente" => $cedula_cliente
						);
					}
					$mensaje_json = json_encode($facturas, JSON_UNESCAPED_UNICODE);
					echo $mensaje_json;
				}else{
					echo "¡NO SE HAN ECONTRADO RESULTADOS!";
				}
			}
		}else{
			echo "¡NO SE HA DECLARADO LA CONDICIÓN A REALIZAR POR EL USUARIO!";
		}	
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>