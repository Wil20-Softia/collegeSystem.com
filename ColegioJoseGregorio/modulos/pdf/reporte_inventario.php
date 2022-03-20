<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		include "../../config/Plantilla.php";
		require "../../config/EntidadBase.php";

		setlocale(LC_ALL,'es_VE.UTF-8');
		date_default_timezone_set ("America/Caracas"); 
	    $aa = (int)date("Y");
		$ma = (int)date("m");

		$fecha_actual_escrita = reg_date(date("Y-m-d"));

		$advertencia = 0;
		$mensaje = "";
		$datos = array();

		$entidad = new EntidadBase("producto");

		$conexion = $entidad->db();
		
		//$nombre_usuario = $_SESSION["nombre_completo"];
		$criterio = $_GET["c"];

		$pdf = new PDF(); 
		$entidad->establecerYearActual();
		$entidad->establecerMesActual();
		$periodo_escolar = $entidad->obtener_periodoActual();
		$pdf->year_inicia = $periodo_escolar["yearDesde"];
		$pdf->year_termina = $periodo_escolar["yearHasta"];
		
		if(!empty($criterio)){
			if($criterio == "todos"){
				$b = 1;
				$t = "Todos los Productos";
				$sql = "
					SELECT
						producto.id,
						producto.descripcion AS nombre,
						producto.precio_venta,
						producto.cantidad_existente,
						producto.ultimo_abastecimiento,
						categoria_producto.nombre AS nombre_categoria
					FROM producto
					INNER JOIN sub_categoria_producto ON producto.sub_categoria_producto = sub_categoria_producto.id
					INNER JOIN categoria_producto ON sub_categoria_producto.categoria_producto = categoria_producto.id
					WHERE
						producto.habilitado = 1
					ORDER BY producto.id ASC";
			}else if($criterio == "subcategoria"){
				$subcategoria = $_GET["v"];
				$b = 1;
				$t = "Subcategoria de Producto";
				$sql = "
					SELECT
						producto.id,
						producto.descripcion AS nombre,
						producto.precio_venta,
						producto.cantidad_existente,
						producto.ultimo_abastecimiento,
						categoria_producto.nombre AS nombre_categoria
					FROM producto
					INNER JOIN sub_categoria_producto ON producto.sub_categoria_producto = sub_categoria_producto.id
					INNER JOIN categoria_producto ON sub_categoria_producto.categoria_producto = categoria_producto.id
					WHERE
						producto.sub_categoria_producto = $subcategoria AND
						producto.habilitado = 1
					ORDER BY producto.id ASC
				";
			}else{
				$b = 0;
				$pdf->AddPage();
				$pdf->Ln(30);
				$pdf->SetFont('Arial','BI',11);
				$pdf->Cell(0,10,utf8_decode("¡OPCIÓN NO EXISTENTE PARA GENERAR REPORTE DE INVENTARIO!"),1,1,'C');
			}
		}else{
			$b = 0;
			$pdf->AddPage();
			$pdf->Ln(30);
			$pdf->SetFont('Arial','BI',11);
			$pdf->Cell(0,10,utf8_decode("¡NO HA SELECCIONADO NINGUNA OPCIÓN PARA GENERAR REPORTE DE INVENTARIO!"),1,1,'C');
		}

		if($b = 1){
			$resultado = $conexion->query($sql);
			if($resultado->num_rows > 0){
				$pdf->AddPage();
				$pdf->SetMargins(10,10,10);
				$pdf->SetAutoPageBreak(true, 15);

				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(0,5,utf8_decode("Reporte de Inventario."),0,1,'L');
				$pdf->SetFont('Arial','',10);
				$pdf->Cell(80,5,utf8_decode("Fecha de Revisión: ".$fecha_actual_escrita),0,1,'L');

				$re = $conexion->query("SELECT SUM(cantidad_existente) AS productos_stock, SUM(cantidad_existente * precio_venta) AS dinero_stock FROM producto WHERE 1");
				$datos_stock = $re->fetch_assoc();
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(80,5,utf8_decode("Productos en Inventario: ".$datos_stock["productos_stock"]),0,0,'L');
 
				$pdf->Cell(0,5,utf8_decode("Dinero en Stock Bs. ".formatearNumerico(round($datos_stock["dinero_stock"],2))),0,1,'R');

				$pdf->SetFont('Arial','',10);
				//$pdf->Cell(0,5,utf8_decode("Realizado por (Administrador(a)): ".$nombre_usuario),0,1,'L');
				$pdf->Ln(2);

				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(0,5,utf8_decode("Descripción de Busqueda: ".$t),0,1,'L');
				$pdf->Ln(5);

				//CABECERA DE LA TABLA
				$pdf->SetX(7);
				$pdf->SetFont('Arial','B',11);
				$pdf->Cell(30,8,"Cod",1,0,"C");
				$pdf->Cell(50,8,"Nombre",1,0,"C");
				$pdf->Cell(30,8,"En Existencia",1,0,"C");
				$pdf->Cell(35,8,"Precio Venta",1,0,"C");
				$pdf->Cell(31,8,"Categoria",1,0,"C");
				$pdf->Cell(30,8,"Ult. Abast.",1,1,"C");

				$pdf->SetFont('Arial','',11);
				while($filas = $resultado->fetch_assoc()){
					$pdf->SetX(7);
					encogimientoTexto($pdf, 11, 30, 8, $filas["id"], 1, 0, "C");
					encogimientoTexto($pdf, 11, 50, 8, $filas["nombre"], 1, 0, "C");
					$pdf->Cell(30,8,$filas["cantidad_existente"],1,0,"C");
					encogimientoTexto($pdf, 11, 35, 8, formatearNumerico(round($filas["precio_venta"],2)), 1, 0, "C");
					encogimientoTexto($pdf, 11, 31, 8, $filas["nombre_categoria"], 1, 0, "C");
					$pdf->Cell(30,8,reg_date($filas["ultimo_abastecimiento"], "formato_corto"),1,1,"C");
				}
			}else{
				$pdf->AddPage();
				$pdf->Ln(30);
				$pdf->SetFont('Arial','BI',11);
				$pdf->Cell(0,10,utf8_decode("¡NO SE HAN ENCONTRADO RESULTADOS!"),1,1,'C');
			}
		}
		$pdf->Output('','reporte_inventario_productos.pdf');
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>