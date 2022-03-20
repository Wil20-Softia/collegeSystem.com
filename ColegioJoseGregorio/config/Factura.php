<?php

	require "fpdf/fpdf.php";
	require "CifrasEnLetras.php";
	require "functions.php";

	class Factura extends FPDF{
		private $codigo;
		private $tipos_pagos = array();
		private $montos_pagos = array();
		private $productos = array();
		private $tipo_pago_producto = array();
		private $total_productos;
		private $fecha_completa;
		private $nombre_estudiante;
		private $id_usuario;
		private $grado_estudiante;
		private $seccion_estudiante;
		private $monto_total;
		private $monto_escrito;
		private $nombre_usuario;
		private $estudiante_cedulado;
		private $cedula_estudiante;
		private $cedula_representante;
		private $nombre_representante;
		private $tipo_factura;
		private $subtotal;
		private $dinero_mora;
		private $porcentaje_mora;
		private $cedula_cliente;
		private $nombre_cliente;
		private $diferencia_factura;
		protected $xc;
		protected $yc;
		protected $altura_actual;
		public $rec;

		public function __construct($tipo_modelo = "estudiante", $alto_pagina = 279.4, $tam_factura = "normal"){
			if($tipo_modelo == "estudiante"){
				//PARA FACTURA PEQUEÑA
				if($tam_factura == "pequeña"){
					parent::__construct('L','mm',array(152.4,127));
					$this->SetMargins(5,15,17.0);
					$this->SetAutoPageBreak(false, 5);
				}else if($tam_factura == "normal"){
					parent::__construct('L','mm',array(215.9,279.4/2));
					$this->SetMargins(10,10,10);
					$this->SetAutoPageBreak(false, 10);
				}else if($tam_factura == "dos_en_una"){
					parent::__construct('P','mm',array(215.9,$alto_pagina));
					$this->SetMargins(10,10,10);
					$this->SetAutoPageBreak(false, 10);
				}

				$this->rec = 0;
			}else if($tipo_modelo == "producto"){
				//TAMAÑO Y MODELADO PRINCIPAL DE LA FACTURA PRODUCTO
				parent::__construct('P','mm', array(75,$alto_pagina));
				$this->AddPage();
				$this->SetMargins(3,6,3);
				$this->SetAutoPageBreak(false, 6);
			}
		}

		function SetDash($black=false, $white=false){
	        if($black and $white)
	            $s=sprintf('[%.3f %.3f] 0 d', $black*$this->k, $white*$this->k);
	        else
	            $s='[] 0 d';
	        $this->_out($s);
    	}

		public function establecerTipoFactura($tipo){
			$this->tipo_factura = $tipo;
		}

		public function establecerCodigo($codigo){
			$this->codigo = $codigo;
		}

		public function establecerFechaCompleta($fecha,$hora){
			$hora = date('h:i:s a', strtotime($hora));
			$this->fecha_completa = reg_date($fecha) . "; Hora: " . $hora;
		}

		public function establecerNombreEstudiante($estudiante){
			$this->nombre_estudiante = $estudiante;
		}

		public function establecerCedulaEstudiante($cedula, $cedulado){
			$this->cedula_estudiante = $cedula;
			$this->estudiante_cedulado = $cedulado;
		}

		public function establecerNombreRepresentante($representante){
			$this->nombre_representante = $representante;
		}

		public function establecerCedulaRepresentante($cedula){
			$this->cedula_representante = $cedula;
		}

		public function establecerMontoTotal($monto){
			$m = str_replace(",","",$monto);
			$m = number_format($m,2,',','.'); 
			$this->monto_total = $m;

			$this->monto_escrito = $monto;
		}

		public function establecerSubtotal($monto){
			$m = str_replace(",","",$monto);
			$m = number_format($m,2,',','.'); 
			$this->subtotal = $m;
		}

		public function establecerDiferenciaFactura($diferencia){
			$m = str_replace(",","",$diferencia);
			$m = number_format($m,2,',','.');
			$this->diferencia_factura = $m;
		}

		public function establecerPorcentajeMora($porcentaje){
			$m = str_replace(",","",$porcentaje);
			$m = number_format($m,2,',','.');
			$this->porcentaje_mora = $m;
		}

		public function establecerDineroMora($monto){
			$m = str_replace(",","",$monto);
			$m = number_format($m,2,',','.'); 
			$this->dinero_mora = $m;
		}

		public function establecerGradoSeccion($grado, $seccion){
			$this->grado_estudiante = $grado;
			$this->seccion_estudiante = $seccion;
		}

		public function establecerNombreUsuario($nombre_usuario){
			$this->nombre_usuario = $nombre_usuario;
		}

		public function establecerIdUsuario($id_usuario){
			$this->id_usuario = $id_usuario;
		}

		public function establecerCedulaCliente($cedula){
			$this->cedula_cliente = $cedula;
		}

		public function establecerNombreCliente($p_nombre, $p_apellido){
			$this->nombre_cliente = $p_nombre . " " . $p_apellido;
		}

		public function establecerTotalProductos($cantidad){
			$this->total_productos = $cantidad;
		}
		

		public function formatearNumerico($numero){
			$numero = (float)$numero;
			$n = str_replace(",","",$numero);
			$n = number_format($n,2,',','.'); 
			return $n;
		}

		public function MontoEnLetras($monto){
			$m = str_replace(",","",$monto);
			$m = number_format($m,2,',',''); 
			$mont = CifrasEnLetras::convertirNumeroEnLetras("$m");
			return ucwords($mont);
		}

		public function insertarTiposPago($t = array(array())){
			$this->tipos_pagos = $t;
		}

		public function insertarMontosPago($a = array(array())){
			$this->montos_pagos = $a;
		}

		public function establecerTiposPagoProducto($t = array(array())){
			$this->tipo_pago_producto = $t;
		}

		public function establecerProductos($p = array(array())){
			$this->productos = $p;
		}

		public function dibujarRectangulo($tam_factura = "normal"){
			$this->rec++;
			$this->SetDrawColor(0);
			$this->SetLineWidth(.3);

			if($tam_factura == "pequeña"){
				$this->AddPage();
				$this->Rect(5,15,130,102,"D");
				$this->altura_actual = 15.3;
			}else if($tam_factura == "normal"){
				$this->AddPage();
				$this->Rect(10,7.5,195.9,124.7,"D");
				$this->altura_actual = 7.8;
			}else if($tam_factura == "dos_en_una"){
				if(($this->rec % 2) != 0){
					$this->AddPage();
					$this->Rect(10,7.5,195.9,124.7,"D");
					$this->altura_actual = 7.8;
				}else{
					$this->SetXY(0,(279.4/2)-2.5);
					$this->Cell(215.9, 2,"","B",0,"C");
					$this->Rect(10,147.2,195.9,124.7,"D");
					$this->altura_actual = 147.5;
				}
			}
		}

		public function dibujarFilasReciboPq($xCurrent, $yCurrent, $h, $array, $tipo, $factura){
			$this->SetXY($xCurrent,$yCurrent);
			$tamArr = count($array);
			$i = 0;
			$tam_letra = 6;
			if($tipo == "montos"){
				if($tamArr <= 6){
					$h *= 2;
				}
			}else{
				if($tamArr <= 3){
					$h *= 2;
				}
			}
			while($tamArr > 0 && $tamArr <= 12 && $i < 12){
				$this->SetX($xCurrent);
				if($tipo == "montos"){
					if($tamArr <= 6){
						$tam_letra = 7;
					}else{
						$tam_letra = 6;
					}
				}else{
					if($tamArr <= 3){
						$tam_letra = 7;
					}else{
						$tam_letra = 6;
					}
				}
				$this->SetFont("Arial","",$tam_letra);
				if(isset($array[$i]) && !empty($array[$i][0])){
					if($tipo == "montos"){
						if($factura == "Mensualidad"){
							encogimientoTexto($this, $tam_letra, 16, $h, $array[$i][0], 1);
							
							encogimientoTexto($this, $tam_letra, 14, $h, $this->formatearNumerico($array[$i][1]), 1, 0, "C");

							encogimientoTexto($this, $tam_letra, 13, $h, $this->formatearNumerico($array[$i][2]), 1, 0, "C");

							encogimientoTexto($this, $tam_letra, 13, $h, $this->formatearNumerico($array[$i][3]), 1, 0, "C");

							encogimientoTexto($this, $tam_letra, 13, $h, $this->formatearNumerico($array[$i][4]), 1, 0, "C");

							encogimientoTexto($this, $tam_letra, 13, $h, $this->formatearNumerico($array[$i][5]) . "%", 1, 1, "C");
						}else if($factura == "Inscripción"){
							encogimientoTexto($this, $tam_letra, 20, $h, $array[$i][0], 1);
							
							encogimientoTexto($this, $tam_letra, 16, $h, $this->formatearNumerico($array[$i][1]), 1, 0, "C");

							encogimientoTexto($this, $tam_letra, 16, $h, $this->formatearNumerico($array[$i][2]), 1, 0, "C");

							encogimientoTexto($this, $tam_letra, 15, $h, $this->formatearNumerico($array[$i][3]), 1, 0, "C");

							encogimientoTexto($this, $tam_letra, 15, $h, $this->formatearNumerico($array[$i][4]), 1, 1, "C");
						}
					}else{
						if($factura == "Mensualidad"){
							if($i == 7){
								break;
							}
							encogimientoTexto($this, $tam_letra, 16, $h, $array[$i][0], 1, 0);

							$this->SetFont("Arial","B",$tam_letra);
							encogimientoTexto($this, $tam_letra, 14, $h, $array[$i][1], 1, 0, "C");

							$this->SetFont("Arial","",$tam_letra);
							encogimientoTexto($this, $tam_letra, 14, $h, $this->formatearNumerico($array[$i][2]), 1, 1, "C");
						}else if($factura == "Inscripción"){
							encogimientoTexto($this, $tam_letra, 16, $h, $array[$i][0], 1, 0);
							
							$this->SetFont("Arial","B",$tam_letra);
							encogimientoTexto($this, $tam_letra, 14, $h, $array[$i][1], 1, 0, "C");
							
							$this->SetFont("Arial","",$tam_letra);
							encogimientoTexto($this, $tam_letra, 14, $h, $this->formatearNumerico($array[$i][2]), 1, 1, "C");
						}
					}
				}/*else{
					if($tipo == "montos"){
						
						if($factura == "Mensualidad"){
							$this->Cell(16, $h, utf8_decode(""),1,0,"L");
					
							$this->Cell(14, $h, utf8_decode(""),1,0,"L");
						
							$this->Cell(13, $h, utf8_decode(""),1,0,"L");
						
							$this->Cell(13, $h, utf8_decode(""),1,0,"L");

							$this->Cell(13, $h, utf8_decode(""),1,0,"L");

							$this->Cell(13, $h, utf8_decode(""),1,1,"L");
						}else if($factura == "Inscripción"){
							$this->Cell(20, $h, utf8_decode(""),1,0,"L");
					
							$this->Cell(16, $h, utf8_decode(""),1,0,"L");
						
							$this->Cell(16, $h, utf8_decode(""),1,0,"L");
						
							$this->Cell(15, $h, utf8_decode(""),1,0,"L");

							$this->Cell(15, $h, utf8_decode(""),1,1,"L");
						}
					}else{
						if($factura == "Mensualidad"){
							if($i == 7){
								break;
							}
							$this->Cell(16, $h, utf8_decode(""),1,0,"L");
							
							$this->Cell(14, $h, utf8_decode(""),1,0,"L");
							
							$this->Cell(14, $h, utf8_decode(""),1,1,"L");
						}else if($factura == "Inscripción"){
							$this->Cell(16, $h, utf8_decode(""),1,0,"L");
						
							$this->Cell(14, $h, utf8_decode(""),1,0,"L");
							
							$this->Cell(14, $h, utf8_decode(""),1,1,"L");
						}

					}
				}*/
				$i++;
			}
			
			$this->yc = $this->GetY();
			$this->xc = $this->GetX();
		}

		public function dibujarFilasReciboNrml($xCurrent, $yCurrent, $h, $array, $tipo, $factura){
			$this->SetXY($xCurrent,$yCurrent);
			$tamArr = count($array);
			$i = 0;
			while($tamArr > 0 && $tamArr <= 12 && $i < 12){
				$this->SetX($xCurrent);
				$this->SetFont("Arial","",9);
				if(isset($array[$i]) && !empty($array[$i][0])){
					if($tipo == "montos"){
						encogimientoTexto($this, 9, 23, $h, $array[$i][0], 1);
					
						$this->Cell(18, $h, $this->formatearNumerico($array[$i][1]),1,0,"C");
						
						$this->Cell(18, $h, $this->formatearNumerico($array[$i][2]),1,0,"C");
						
						$this->Cell(18, $h, $this->formatearNumerico($array[$i][3]),1,0,"C");
						if($factura == "Mensualidad"){
							$this->Cell(18, $h, $this->formatearNumerico($array[$i][4]),1,0,"C");

							$this->Cell(18, $h, $this->formatearNumerico($array[$i][5]) . "%",1,1,"C");
						}else if($factura == "Inscripción"){
							$this->Cell(18, $h, $this->formatearNumerico($array[$i][4]),1,1,"C");
						}
					}else{
						if($factura == "Mensualidad"){
							if($i == 7){
								break;
							}
							encogimientoTexto($this, 9, 25, $h, $array[$i][0], 1, 0);
					
							encogimientoTexto($this, 9, 25, $h, $array[$i][1], 1, 0, "C");
						
							encogimientoTexto($this, 9, 25, $h, $this->formatearNumerico($array[$i][2]), 1, 1, "C");
						}else if($factura == "Inscripción"){
							encogimientoTexto($this, 9, 31, $h, $array[$i][0], 1, 0);
					
							encogimientoTexto($this, 9, 31, $h, $array[$i][1], 1, 0, "C");
						
							encogimientoTexto($this, 9, 31, $h, $this->formatearNumerico($array[$i][2]), 1, 1, "C");
						}
					}
				}else{
					if($tipo == "montos"){
						$this->Cell(23, $h, utf8_decode(""),1,0,"L");
					
						$this->Cell(18, $h, utf8_decode(""),1,0,"L");
						
						$this->Cell(18, $h, utf8_decode(""),1,0,"L");
						
						$this->Cell(18, $h, utf8_decode(""),1,0,"L");
						if($factura == "Mensualidad"){
							$this->Cell(18, $h, utf8_decode(""),1,0,"L");

							$this->Cell(18, $h, utf8_decode(""),1,1,"L");
						}else if($factura == "Inscripción"){
							$this->Cell(18, $h, utf8_decode(""),1,1,"L");
						}
					}else{
						if($factura == "Mensualidad"){
							if($i == 7){
								break;
							}
							$this->Cell(25, $h, utf8_decode(""),1,0,"L");
					
							$this->Cell(25, $h, utf8_decode(""),1,0,"L");
						
							$this->Cell(25, $h, utf8_decode(""),1,1,"L");
						}else if($factura == "Inscripción"){
							$this->Cell(31, $h, utf8_decode(""),1,0,"L");
						
							$this->Cell(31, $h, utf8_decode(""),1,0,"L");
							
							$this->Cell(31, $h, utf8_decode(""),1,1,"L");
						}

					}
				}
				$i++;
			}
			
			$this->yc = $this->GetY();
			$this->xc = $this->GetX();
		}

		public function imprimirProductos($h, $productos = array()){
			$this->SetDash(1,1); //PARA LINEAS PUNTEADAS
			$tamArr = count($productos);
			for($i = 0; $i < $tamArr; $i++){
				$producto = $productos[$i];
			    encogimientoTexto($this, 7, 25, $h, utf8_decode($producto["descripcion"]), "B", 0);
				$this->Cell(8, $h, $producto["cantidad"],"B",0,"R");
				$this->Cell(17, $h,$this->formatearNumerico($producto["precio_producto"]),"B",0,"R");
				$this->Cell(0, $h, $this->formatearNumerico($producto["importe"]),"B",1,"R");
			}
			$this->SetDash(); //PARA LINEAS NORMALES
		}

		public function imprimirTipoPagoProductos($h, $tipo_pago = array()){
			$tamArr = count($tipo_pago);
			for($i = 0; $i < $tamArr; $i++){
				$tp = $tipo_pago[$i];
			    $this->Cell(20, $h, utf8_decode($tp["nombre"]),0,0,"L");
				$this->Cell(25, $h, $tp["referencia"],0,0,"C");
				$this->Cell(0, $h, $this->formatearNumerico($tp["monto"]),0,1,"R");
			}
		}

		public function dibujarFacturaReciboPq(){			
			$hLine = 1.875;

			$this->dibujarRectangulo("pequeña");
			$this->SetLineWidth(.1);
			//IMAGENES DE LA CABECERA.
			$this->SetFont("Arial","BI",11);
			$this->Image("../../img/logo.png", 5.5, $this->altura_actual, 14, 13);

			//ENUNCIADO DE LA ALCALDIA.
			$this->SetFont("Arial","BI",7);
			$this->SetXY(19, $this->altura_actual);
			$this->Cell(75, $hLine+.5, utf8_decode("Unidad Educativa Colegio"),0,1,"C");
			$this->SetX(19);
			$this->Cell(75, $hLine+.5, utf8_decode("\"Dr. José Gregorio Hernández\""),0,1,"C");
			$this->SetX(19);
			$this->Cell(75, $hLine+.5, utf8_decode("Inscrito en el M.P.P para la Educación Código: P00008-21011"),0,1,"C");
			$this->SetX(19);
			$this->Cell(75, $hLine+.5, utf8_decode("Carrera 2 entre Calles 4 y 5, Parroquia Valmore Rodriguez"),0,1,"C");
			$this->SetX(19);
			$this->Cell(75, $hLine+.5, utf8_decode("Sabana de Mendoza, Estado Trujillo 0271-4159749"),0,1,"C");

			//RIF Y NRO DE RECIBO.
			$this->SetFont("Arial","",8);
			$this->SetXY(94, $this->altura_actual+5);
			$this->Cell(0, $hLine+1.5, utf8_decode("Factura ".$this->tipo_factura),0,1,"L");
			$this->SetXY(94, $this->altura_actual+8.6);
			$this->SetFont("Arial","B",6);
			$this->Cell(10,$hLine+.5,utf8_decode('Código:'), 0, 0, "L");
			encogimientoTexto($this, 6, 29, $hLine+.5, $this->codigo, 0, 0, "L");

			$this->SetXY(5,$this->altura_actual+14);
			$this->SetFont("Arial","BIU",8);
			$this->Cell(60, $hLine+.75, utf8_decode("RIF: J-30362519-3"),0,0,"L");
			if($this->estudiante_cedulado == 1){
				$tiene_cedula = "Con";
			}else{
				$tiene_cedula = "Sin";
			}
			$this->SetFont("Arial","I",6);
			$this->Cell(70, $hLine+.75, utf8_decode("Estudiante " . $tiene_cedula . " Cedula de Identidad"),0,1,"R");

			if($this->tipo_factura == "Mensualidad"){
				$this->SetXY(5,$this->altura_actual+18);
				$this->SetFont("Arial","B",7);
				$this->Cell(28, $hLine+.5, utf8_decode("Porcentaje de Mora:"),0,0,"L");
				$this->SetFont("Arial","I",7);
				$this->Cell(35, $hLine+.5, $this->porcentaje_mora . "%",0,0,"L");
			}

			$this->SetXY(86,$this->altura_actual+18);
			$this->SetFont("Arial","BI",8);
			$this->Cell(12, $hLine+.75, utf8_decode("Por Bs."),0,0,"C");
			$this->SetFont("Arial","I",7);
			$this->Cell(0, $hLine+.75, $this->monto_total,0,1,"L");

			if($this->tipo_factura == "Mensualidad"){
				$this->SetXY(5,$this->altura_actual+22);
				$this->SetFont("Arial","B",7);
				$this->Cell(22, $hLine+.75, utf8_decode("Diferencia Bs."),0,0,"L");
				$this->SetFont("Arial","I",7);
				$this->Cell(35, $hLine+.75, $this->diferencia_factura,0,0,"L");
			}

			$this->SetXY(71,$this->altura_actual+22);
			$this->SetFont("Arial","BI",8);
			$this->Cell(7, $hLine+.75, utf8_decode("Fecha: "),0,0,"C");
			$this->SetFont("Arial","IU",7);
			$this->Cell(0, $hLine+.75, $this->fecha_completa,0,1,"L");

			$this->SetXY(5,$this->altura_actual+26);
			$this->SetFont("Arial","B",8);
			$this->Cell(35, $hLine+.75, utf8_decode("Hemos Recibido de:"),0,0,"L");
			$this->Cell(30);

			$this->SetFont("Arial","I",6);
			$this->Cell(15, $hLine+.75, utf8_decode("GRADO/AÑO:"),0,0,"L");
			$this->SetFont("Arial","BU",7);
			$this->Cell(19, $hLine+.75, utf8_decode($this->grado_estudiante),0,0,"C");
			$this->SetFont("Arial","I",6);
			$this->Cell(12, $hLine+.75, utf8_decode("SECCIÓN:"),0,0,"L");
			$this->SetFont("Arial","BU",7);
			$this->Cell(10, $hLine+.75, utf8_decode($this->seccion_estudiante),0,1,"C");
			
			$this->Ln(1);
			$this->SetX(9);
			$this->SetFont("Arial","BI",7);
			$this->Cell(22, $hLine+.75, utf8_decode(" - Representante:"),"B",0,"L");
			$this->SetFont("Arial","",7);
			encogimientoTexto($this, 7, 45, $hLine+.75, $this->nombre_representante, "B", 0, "L");
			$this->SetFont("Arial","BI",7);
			$this->Cell(10, $hLine+.75, utf8_decode("C.I:"),"B",0,"R");
			$this->SetFont("Arial","",7);
			encogimientoTexto($this, 7, 25, $hLine+.75, $this->cedula_representante, "B", 1, "L");
			
			$this->Ln(1.5);
			$this->SetX(9);
			$this->SetFont("Arial","BI",7);
			$this->Cell(35, $hLine+.75, utf8_decode(" - - Estudiante representado:"),"B",0,"L");
			$this->SetFont("Arial","",7);
			encogimientoTexto($this, 7, 45, $hLine+.75, $this->nombre_estudiante, "B", 0, "L");
			$this->SetFont("Arial","BI",7);
			$this->Cell(10, $hLine+.75, utf8_decode("C.I:"),"B",0,"R");
			$this->SetFont("Arial","",7);
			encogimientoTexto($this, 7, 25, $hLine+.75, $this->cedula_estudiante, "B", 0, "L");

			$this->SetXY(5,$this->altura_actual+38);
			$this->SetFont("Arial","B",8);
			$this->Cell(23, $hLine+.75, utf8_decode("La Cantidad de:"),0,0,"L");
			$this->SetFont("Arial","UI",7);
			encogimientoTexto($this, 7, 107, $hLine+.75, $this->MontoEnLetras($this->monto_escrito) . " Bs.", 0, 1, "L");

			$this->SetXY(5,$this->altura_actual+44);
			$this->SetFont("Arial","B",7);
			if($this->tipo_factura == "Mensualidad"){
				$this->Cell(82, $hLine+1, utf8_decode("Descripción de Pago."),1,0,"C");
				$this->SetX(91);
				$this->Cell(44, $hLine+1, utf8_decode("Tipo(s) de Pago(s)"),1,0,"C");
				$this->SetXY(5, $this->altura_actual+47);
				$this->SetFont("Arial","B",7);
				$this->Cell(16, $hLine+1.25, utf8_decode("Descripción"),1,0,"C");
				$this->Cell(14, $hLine+1.25, utf8_decode("Precio"),1,0,"C");
				$this->Cell(13, $hLine+1.25, utf8_decode("Cancelado"),1,0,"C");	
				$this->Cell(13, $hLine+1.25, utf8_decode("Abono"),1,0,"C");	
				$this->Cell(13, $hLine+1.25, utf8_decode("Diferencia"),1,0,"C");
				$this->Cell(13, $hLine+1.25, utf8_decode("Mora"),1,1,"C");
				$this->dibujarFilasReciboPq(5, $this->altura_actual+50, $hLine+1.5, $this->montos_pagos, "montos", $this->tipo_factura);

				$this->SetXY(91, $this->altura_actual+47);
				$this->SetFont("Arial","B",7);
				$this->Cell(16, $hLine+1.25, utf8_decode("Forma"),1,0,"C");
				$this->Cell(14, $hLine+1.25, utf8_decode("Referencia"),1,0,"C");	
				$this->Cell(14, $hLine+1.25, utf8_decode("Monto"),1,1,"C");
				$this->dibujarFilasReciboPq(91, $this->altura_actual+50, $hLine+1.5, $this->tipos_pagos, "pagos", $this->tipo_factura);

				$this->SetXY(91, $this->altura_actual+79);
				$this->SetFont("Arial","I",8);
				$this->Cell(19, $hLine+.5, utf8_decode("Subtotal = Bs."),0,0,"L");
				$this->Cell(0, $hLine+.5, $this->subtotal,0,1,"R");

				$this->Ln(1);
				$this->SetX(91);
				$this->Cell(19, $hLine+.5, utf8_decode("Mora = Bs."),0,0,"L");
				$this->Cell(0, $hLine+.5, utf8_decode($this->dinero_mora),0,1,"R");

				$this->Ln(1);
				$this->SetX(91);
				$this->Cell(19, $hLine+.75, utf8_decode("Total = Bs."),0,0,"L");
				$this->SetFont("Arial","BI",8);
				$this->Cell(0, $hLine+.75, $this->monto_total,"T",1,"R");
			}else if($this->tipo_factura == "Inscripción"){
				$this->Cell(82, $hLine+1, utf8_decode("Descripción de Pago."),1,0,"C");
				$this->SetX(91);
				$this->Cell(44, $hLine+1, utf8_decode("Tipo(s) de Pago(s)"),1,0,"C");
				$this->SetXY(5, $this->altura_actual+47);
				$this->SetFont("Arial","B",7);
				$this->Cell(20, $hLine+1.25, utf8_decode("Descripción"),1,0,"C");
				$this->Cell(16, $hLine+1.25, utf8_decode("Precio"),1,0,"C");
				$this->Cell(16, $hLine+1.25, utf8_decode("Cancelado"),1,0,"C");	
				$this->Cell(15, $hLine+1.25, utf8_decode("Abono"),1,0,"C");	
				$this->Cell(15, $hLine+1.25, utf8_decode("Diferencia"),1,0,"C");
				$this->dibujarFilasReciboPq(5, $this->altura_actual+50, $hLine+1.25, $this->montos_pagos, "montos", $this->tipo_factura);

				$this->SetXY(91, $this->altura_actual+47);
				$this->SetFont("Arial","B",7);
				$this->Cell(16, $hLine+1.25, utf8_decode("Forma"),1,0,"C");
				$this->Cell(14, $hLine+1.25, utf8_decode("Referencia"),1,0,"C");	
				$this->Cell(14, $hLine+1.25, utf8_decode("Monto"),1,1,"C");
				$this->dibujarFilasReciboPq(91, $this->altura_actual+50, $hLine+1.25, $this->tipos_pagos, "pagos", $this->tipo_factura);
			}

			$this->SetXY(8, $this->altura_actual+97);
			$this->SetFont("Arial","I",8);
			$this->Cell(60, $hLine, utf8_decode("Sello"),"B",0,"C");

			$this->SetXY(8, $this->altura_actual+99);
			$this->SetFont("Arial","B",8);
			$this->Cell(60, $hLine+.75, utf8_decode("Colegio \"Dr. José Gregorio Hernández\""),0,0,"C");
			$this->Cell(5);
			encogimientoTexto($this, 8, 60, $hLine+.75, utf8_decode("Administrador. " . $this->nombre_usuario), "T", 1, "C");
		}

		public function dibujarFacturaReciboNrml(){			
			$hLine = 3.75;

			$this->dibujarRectangulo("normal");
			$this->SetLineWidth(.1);
			//IMAGENES DE LA CABECERA.
			$this->SetFont("Arial","BI",11);
			$this->Image("../../img/logo.png", 10.3, $this->altura_actual, 28, 26);

			//ENUNCIADO DE LA ALCALDIA.
			$this->SetFont("Arial","BI",9);
			$this->SetXY(40, $this->altura_actual);
			$this->Cell(120, $hLine+.5, utf8_decode("Unidad Educativa Colegio"),0,1,"C");
			$this->SetX(40);
			$this->Cell(120, $hLine+.5, utf8_decode("\"Dr. José Gregorio Hernández\""),0,1,"C");
			$this->SetX(40);
			$this->Cell(120, $hLine+.5, utf8_decode("Inscrito en el M.P.P para la Educación Codigo: P00008-21011"),0,1,"C");
			$this->SetX(40);
			$this->Cell(120, $hLine+.5, utf8_decode("Carrera 2 entre Calles 4 y 5, Parroquia Valmore Rodriguez"),0,1,"C");
			$this->SetX(40);
			$this->Cell(120, $hLine+.5, utf8_decode("Sabana de Mendoza, Estado Trujillo 0271-4159749"),0,1,"C");

			//RIF Y NRO DE RECIBO.
			$this->SetFont("Arial","",10);
			$this->SetXY(159, $this->altura_actual+5);
			$this->Cell(46, $hLine+1.5, utf8_decode("Factura ".$this->tipo_factura),0,1,"L");
			$this->SetX(159);
			$this->SetFont("Arial","B",9);
			$this->Cell(46, $hLine+1.5, utf8_decode("Nro. de Factura: ".$this->codigo),0,1,"L");

			$this->SetXY(11,$this->altura_actual+28);
			$this->SetFont("Arial","BIU",10);
			$this->Cell(0, $hLine+1.25, utf8_decode("RIF: J-30362519-3"),0,1,"L");

			if($this->tipo_factura == "Mensualidad"){
				$this->SetXY(12,$this->altura_actual+34);
				$this->SetFont("Arial","B",10);
				$this->Cell(35, $hLine+.5, utf8_decode("Porcentaje de Mora:"),0,0,"L");
				$this->SetFont("Arial","I",10);
				$this->Cell(50, $hLine+.5, $this->porcentaje_mora . "%",0,0,"L");
			}

			$this->SetXY(150,$this->altura_actual+33);
			$this->SetFont("Arial","BI",10);
			$this->Cell(20, $hLine+1, utf8_decode("Por Bs."),0,0,"C");
			$this->SetX(170);
			$this->SetFont("Arial","I",9);
			$this->Cell(0, $hLine+1, $this->monto_total,0,1,"L");
			if($this->tipo_factura == "Mensualidad"){
				$this->SetXY(13,$this->altura_actual+38.3);
				$this->SetFont("Arial","B",10);
				$this->Cell(24, $hLine+.5, utf8_decode("Diferencia Bs."),0,0,"C");
				$this->SetFont("Arial","I",10);
				$this->Cell(50, $hLine+.5, $this->diferencia_factura,0,0,"L");
			}
			$this->SetXY(123,$this->altura_actual+38.3);
			$this->SetFont("Arial","BI",10);
			$this->Cell(12, $hLine+.5, utf8_decode("Fecha: "),0,0,"C");
			$this->SetX(135);
			$this->SetFont("Arial","IU",9);
			$this->Cell(0, $hLine+.5, $this->fecha_completa,0,1,"L");

			$this->SetXY(12,$this->altura_actual+43.5);
			$this->SetFont("Arial","",10);
			$this->Cell(35, $hLine+1, utf8_decode("Hemos Recibido de:"),0,0,"L");
			$this->SetFont("Arial","IU",11);
			$this->Cell(80, $hLine+1, utf8_decode($this->nombre_estudiante),0,0,"L");

			$this->SetFont("Arial","I",9);
			$this->Cell(22, $hLine+1, utf8_decode("GRADO/AÑO:"),0,0,"L");
			$this->SetFont("Arial","BU",10);
			$this->Cell(25, $hLine+1, utf8_decode($this->grado_estudiante),0,0,"C");
			$this->SetFont("Arial","I",9);
			$this->Cell(18, $hLine+1, utf8_decode("SECCIÓN:"),0,0,"L");
			$this->SetFont("Arial","BU",10);
			$this->Cell(0, $hLine+1, utf8_decode($this->seccion_estudiante),0,0,"C");
			
			$this->SetXY(12,$this->altura_actual+49);
			$this->SetFont("Arial","",10);
			$this->Cell(28, $hLine+1.25, utf8_decode("La Cantidad de:"),0,0,"L");
			$this->SetFont("Arial","I",9);
			$this->Cell(0, $hLine+1.25, utf8_decode($this->MontoEnLetras($this->monto_escrito) . " Bs."),"B",1,"L");

			$this->SetXY(10,$this->altura_actual+59);
			$this->SetFont("Arial","B",11);
			if($this->tipo_factura == "Mensualidad"){
				$this->Cell(113, $hLine, utf8_decode("Descripción de Pago."),1,0,"C");
				$this->SetX(129);
				$this->Cell(75, $hLine, utf8_decode("Tipo(s) de Pago(s)"),1,0,"C");
				$this->SetXY(10, $this->altura_actual+62.75);
				$this->SetFont("Arial","B",9);
				$this->Cell(23, $hLine, utf8_decode("Descripción"),1,0,"C");
				$this->Cell(18, $hLine, utf8_decode("Precio"),1,0,"C");
				$this->Cell(18, $hLine, utf8_decode("Cancelado"),1,0,"C");	
				$this->Cell(18, $hLine, utf8_decode("Abono"),1,0,"C");	
				$this->Cell(18, $hLine, utf8_decode("Diferencia"),1,0,"C");
				$this->Cell(18, $hLine, utf8_decode("Mora"),1,1,"C");
				$this->dibujarFilasReciboNrml(10, $this->altura_actual+66.5, $hLine, $this->montos_pagos, "montos", $this->tipo_factura);

				$this->SetXY(129, $this->altura_actual+62.75);
				$this->SetFont("Arial","B",9);
				$this->Cell(25, $hLine, utf8_decode("Forma"),1,0,"C");
				$this->Cell(25, $hLine, utf8_decode("Referencia"),1,0,"C");	
				$this->Cell(25, $hLine, utf8_decode("Monto"),1,1,"C");
				$this->dibujarFilasReciboNrml(129, $this->altura_actual+66.5, $hLine, $this->tipos_pagos, "pagos", $this->tipo_factura);

				$this->SetXY(129, $this->altura_actual+97);
				$this->SetFont("Arial","I",10);
				$this->Cell(25, $hLine, utf8_decode("Subtotal = Bs."),0,0,"L");
				$this->Cell(40, $hLine, $this->subtotal,0,1,"C");

				$this->Ln(1);
				$this->SetX(129);
				$this->Cell(25, $hLine, utf8_decode("Mora = Bs."),0,0,"L");
				$this->Cell(40, $hLine, utf8_decode($this->dinero_mora),0,1,"C");

				$this->Ln(1);
				$this->SetX(129);
				$this->Cell(25, $hLine, utf8_decode("Total = Bs."),0,0,"L");
				$this->SetFont("Arial","BI",10);
				$this->Cell(40, $hLine, $this->monto_total,"T",1,"C");
			}else if($this->tipo_factura == "Inscripción"){
				$this->Cell(95, $hLine, utf8_decode("Descripción de Pago."),1,0,"C");
				$this->SetX(111);
				$this->Cell(93, $hLine, utf8_decode("Tipo(s) de Pago(s)"),1,0,"C");
				$this->SetXY(10, $this->altura_actual+62.75);
				$this->SetFont("Arial","B",9);
				$this->Cell(23, $hLine, utf8_decode("Descripción"),1,0,"C");
				$this->Cell(18, $hLine, utf8_decode("Precio"),1,0,"C");
				$this->Cell(18, $hLine, utf8_decode("Cancelado"),1,0,"C");	
				$this->Cell(18, $hLine, utf8_decode("Abono"),1,0,"C");	
				$this->Cell(18, $hLine, utf8_decode("Diferencia"),1,0,"C");
				$this->dibujarFilasReciboNrml(10, $this->altura_actual+66.5, $hLine, $this->montos_pagos, "montos", $this->tipo_factura);

				$this->SetXY(111, $this->altura_actual+62.75);
				$this->SetFont("Arial","B",9);
				$this->Cell(31, $hLine, utf8_decode("Forma"),1,0,"C");
				$this->Cell(31, $hLine, utf8_decode("Referencia"),1,0,"C");	
				$this->Cell(31, $hLine, utf8_decode("Monto"),1,1,"C");
				$this->dibujarFilasReciboNrml(111, $this->altura_actual+66.5, $hLine, $this->tipos_pagos, "pagos", $this->tipo_factura);
			}

			$this->SetXY(15, $this->altura_actual+116);
			$this->SetFont("Arial","I",8);
			$this->Cell(95, $hLine, utf8_decode("Sello"),"B",0,"C");

			$this->SetXY(15, $this->altura_actual+120);
			$this->SetFont("Arial","B",9);
			$this->Cell(95, $hLine, utf8_decode("Colegio \"Dr. José Gregorio Hernández\""),0,0,"C");
			$this->Cell(10);
			$this->Cell(80, $hLine, utf8_decode("Administrador. " . $this->nombre_usuario),"T",1,"C");
		}

		public function dibujarFacturaProducto(){			
			$hLine = 2.5;

			$this->SetLineWidth(.1);
			//IMAGENES DE LA CABECERA.
			$this->altura_actual = 6;
			$this->Image("../../img/logo.png", 3, $this->altura_actual, 20, 17);

			//ENUNCIADO DE LA ALCALDIA.
			$this->SetFont("Arial","I",5);
			$this->SetXY(23, $this->altura_actual);
			$this->Cell(0, $hLine, utf8_decode("Unidad Educativa Colegio"),0,1,"C");
			$this->SetX(23);
			$this->Cell(0, $hLine, utf8_decode("\"Dr. José Gregorio Hernández\""),0,1,"C");

			$this->SetFont("Arial","I",5);
			$this->SetX(23);
			$this->Cell(0, $hLine, utf8_decode("Inscrito en el M.P.P para la Educación Codigo: P00008-21011"),0,1,"C");
			$this->SetX(23);
			$this->Cell(0, $hLine, utf8_decode("Carrera 2 entre Calles 4 y 5, Parroquia Valmore Rodriguez"),0,1,"C");

			$this->SetFont("Arial","I",5);
			$this->SetX(23);
			$this->Cell(0, $hLine, utf8_decode("Sabana de Mendoza, Estado Trujillo 0271-4159749"),0,1,"C");
			$this->SetX(23);
			$this->Cell(0, $hLine, utf8_decode("RIF: J-30362519-3"),0,1,"C");

			//SECCION DEL TICKET
			$this->SetFont("Arial","B",7);
			$this->SetY($this->altura_actual+18);
			$this->Cell(14, $hLine+.5, utf8_decode("Ticket Nro."),0,0,"L");
			$this->SetFont("Arial","",7);
			$this->Cell(25, $hLine+.5, utf8_decode($this->codigo),0,0,"L");
			$this->SetFont("Arial","B",7);
			$this->Cell(14, $hLine+.5, utf8_decode("Vendedor:"),0,0,"C");
			$this->SetFont("Arial","",7);
			$this->Cell(0, $hLine+.5, utf8_decode($this->id_usuario),0,1,"L");

			//SECCION DE LA FECHA
			$this->SetFont("Arial","B",7);
			$this->Cell(10, $hLine+.5, utf8_decode("Fecha:"),0,0,"L");
			$this->SetFont("Arial","I",6);
			$this->Cell(0, $hLine+.5, $this->fecha_completa,0,1,"L");

			$this->SetFont("Arial","BI",7);
			$this->Cell(25, $hLine+.5,"ORIGINAL.",0,0,"L");
			$this->Cell(0, $hLine+.5,"Datos Cliente",0,1,"L");

			$this->SetFont("Arial","B",7);
			$this->Cell(6, $hLine+.5,"C.I:",0,0,"L");
			$this->SetFont("Arial","",7);
			$this->Cell(30, $hLine+.5,$this->cedula_cliente,0,0,"L");

			$this->SetFont("Arial","B",7);
			$this->Cell(0, $hLine+.5,$this->nombre_cliente,0,1,"C");

			$this->Ln(1);
			$this->Cell(25, $hLine+.5,utf8_decode("Descripción"),"BR",0,"L");
			$this->Cell(8, $hLine+.5,"Cant.","BR",0,"L");
			$this->Cell(17, $hLine+.5,"Precio Unit.","BR",0,"L");
			$this->Cell(0, $hLine+.5,"Importe","B",1,"R");
			
			//AREA DE IMPRESION DE EL LISTADO DE PRODUCTOS COMPRADOS
			$this->imprimirProductos($hLine+2.5, $this->productos);

			$this->SetFont("Arial","B",7);
			$this->Ln($hLine);
			$this->Cell(20, $hLine+.5,utf8_decode("Tipo Pago"),"BR",0,"L");
			$this->Cell(25, $hLine+.5,"Referencia","BR",0,"L");
			$this->Cell(0, $hLine+.5,"Monto","B",1,"R");

			//AREA DE IMPRESION DE LOS TIPOS DE PAGOS EFECTUADOS EN LA COMPRA
			$this->imprimirTipoPagoProductos($hLine+1, $this->tipo_pago_producto);

			$this->SetFont("Arial","B",7);
			$this->Ln($hLine+.5);
			$this->Cell(50, $hLine+.5,"Cant. Productos",0,0,"R");
			$this->Cell(0, $hLine+.5,$this->total_productos,0,1,"R");
			$this->Cell(50, $hLine+.5,"Diferencia Bs.",0,0,"R");
			$this->Cell(0, $hLine+.5,$this->diferencia_factura,0,1,"R");
			$this->Cell(50, $hLine+.5,"Total Bs.",0,0,"R");
			$this->Cell(0, $hLine+.5,$this->monto_total,0,1,"R");

			$this->SetFont("Arial","B",7);
			$this->Cell(20, $hLine+.5,"Importe con Letra",0,1,"L");
			$this->SetFont("Arial","",6);
			encogimientoTexto($this, 6, 68, $hLine+.5, $this->MontoEnLetras($this->monto_escrito), 0, 1);

			$this->Cell(0, $hLine+.5,utf8_decode("Conserve su Ticket"),0,1,"C");
			$this->Cell(0, $hLine+.5,utf8_decode("¡Gracias por su Compra!"),0,1,"C");
			
			$this->SetFont("Arial","B",7);
			$this->Ln($hLine+1);
			$this->Cell(0, $hLine+.5,utf8_decode("FIRMA DEL VENDEDOR"),"T",1,"C");
		}
	}
?>