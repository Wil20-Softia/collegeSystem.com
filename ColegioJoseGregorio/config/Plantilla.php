<?php

	require "fpdf/fpdf.php";
	require "functions.php";

	class PDF extends FPDF
	{
		var $widths;
		var $aligns;
		public $year_inicia;
		public $year_termina;
		var $meses = ["","Septiembre","Octubre","Noviembre","Diciembre","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto"];

		public function __construct(){
			parent::__construct('P','mm',array(215.9,279.4));
			$this->AddFont("Abril Fatface","","AbrilFatface-Regular.php");
			$this->AddFont("RobotoCondensed","","RobotoCondensed-Regular.php");
			$this->AddFont("RobotoCondensed","B","RobotoCondensed-Bold.php");
			$this->AddFont("Ubuntu","","Ubuntu-Regular.php");
			$this->SetMargins(20,30,20);
			$this->AliasNbPages('{nb}');
			$this->SetAutoPageBreak(true, 5);
		}

		public function Header()
		{
			//Image(url,X,Y,Width,Height);
			$this->Image("../../img/logo.png", 20, 5, 26.4, 28);
			$this->SetFont("Arial", "BI", 14);
			$this->SetXY(50,10);
			$this->Cell(0, 6, utf8_decode("Colegio Privado \"Dr. José Gregorio Hernández\""),0,1,"C");
			$this->SetX(50);
			$this->Cell(0, 6, "Sabana de Mendoza, Estado Trujillo",0,1,"C");
			$this->SetX(50);
			$this->Cell(0, 6, "Periodo Escolar " . $this->year_inicia . " - " . $this->year_termina,0,1,"C");
			$this->SetY(35);
		}

		public function Footer()
		{
			$this->SetY(-15);
			$this->SetTextColor(124,131,126);
			$this->SetFont("Arial", "BI", 8);
			$this->Cell(0, 10, utf8_decode("Página " . $this->PageNo() . " / {nb}"), 0, 0, "C");
		}

		public function SetWidths($w)
		{
		    //Establecer la matriz de anchos de columna
		    $this->widths=$w;
		}

		public function SetAligns($a)
		{
		    //Establecer la matriz de alineaciones de columnas.
		    $this->aligns=$a;
		}

		public function Row($data,$margenDerecho,$r,$g,$b,$fondo)
		{
		    //Calcula la altura de la fila.
		    $nb=0;
		    for($i=0;$i<count($data);$i++)
		        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		    $h=5*$nb;
		    //Emitir un salto de página primero si es necesario
		    $this->CheckPageBreak($h);
		    
		    //Coloca el margen de la derecha
		    $this->SetX($margenDerecho);
		    
		    //Coloca el color de fondo de cada una de las celdas.
		    $this->SetFillColor($r,$g,$b);
		    $this->SetDrawColor(255);
		    //Dibuja las celdas de la fila.
		    for($i=0;$i<count($data);$i++)
		    {
		        $w=$this->widths[$i];
		        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		        //Guarda la Posición Actual.
		        $x=$this->GetX();
		        $y=$this->GetY();
		        //Dibuja los Bordes.
		        $this->Rect($x,$y,$w,$h,$fondo);
		        //Imprime El texto
		        $this->MultiCell($w,5,utf8_decode($data[$i]),0,$a);
		        //Put the position to the right of the cell
		        $this->SetXY($x+$w,$y);
		    }
		    //Go to the next line
		    $this->Ln($h);
		    // Línea de cierre
			$this->SetDrawColor(82,75,75);
			$this->SetX($margenDerecho);
		    $this->Cell(array_sum($this->widths),0,'','T');
		}

		public function CheckPageBreak($h)
		{
		    //Si la altura h causaría un desbordamiento, agregue una nueva página inmediatamente
		    if($this->GetY()+$h>$this->PageBreakTrigger)
		        $this->AddPage($this->CurOrientation);
		}

		public function NbLines($w,$txt)
		{
		    //Calcula el número de líneas que tomará un MultiCell de ancho w
		    $cw=&$this->CurrentFont['cw'];
		    if($w==0)
		        $w=$this->w-$this->rMargin-$this->x;
		    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		    $s=str_replace("\r",'',$txt);
		    $nb=strlen($s);
		    if($nb>0 and $s[$nb-1]=="\n")
		        $nb--;
		    $sep=-1;
		    $i=0;
		    $j=0;
		    $l=0;
		    $nl=1;
		    while($i<$nb)
		    {
		        $c=$s[$i];
		        if($c=="\n")
		        {
		            $i++;
		            $sep=-1;
		            $j=$i;
		            $l=0;
		            $nl++;
		            continue;
		        }
		        if($c==' ')
		            $sep=$i;
		        $l+=$cw[$c];
		        if($l>$wmax)
		        {
		            if($sep==-1)
		            {
		                if($i==$j)
		                    $i++;
		            }
		            else
		                $i=$sep+1;
		            $sep=-1;
		            $j=$i;
		            $l=0;
		            $nl++;
		        }
		        else
		            $i++;
		    }
		    return $nl;
		}
		
		public function TablaColoreadaEmpresas($cabecera, $datos)
		{
		    // Colores, ancho de línea y fuente en negrita
		    $this->SetX(10);
		    $this->SetFillColor(30,231,158);
		    $this->SetTextColor(255);
		    $this->SetDrawColor(128,0,0);
		    $this->SetLineWidth(.3);
		    $this->SetFont('','B',10);
		    // Cabecera
		    $w = array(35, 60, 35, 60);
		    for($i=0;$i<count($cabecera);$i++)
		        $this->Cell($w[$i],12,utf8_decode($cabecera[$i]),'B',0,'C',true);
		    $this->Ln();
		    // Restauración de colores y fuentes
		    $this->SetFillColor(224,235,255);
		    $this->SetTextColor(0);
		    $this->SetFont('','',9);
		    // Datos
		    $fill = false;
		    foreach($datos as $row)
		    {
		    	$this->SetX(10);
		        $this->Cell($w[0],10,utf8_decode($row[0]),'B',0,'L',$fill);
		        $this->Cell($w[1],10,utf8_decode($row[1]),'B',0,'L',$fill);
		        $this->Cell($w[2],10,utf8_decode($row[2]),'B',0,'L',$fill);
		        $this->Cell($w[3],10,utf8_decode($row[3]),'B',0,'L',$fill);
		        $this->Ln();
		        $fill = !$fill;
		    }
		    // Línea de cierre
		    $this->Cell(array_sum($w)-10,0,'','T');
		}

		public function TablaColoreadaFacturas($cabecera, $datos)
		{
			$this->SetX(12);
		    // Colores, ancho de línea y fuente en negrita
		    $this->SetFillColor(30,231,158);
		    $this->SetTextColor(255);
		    $this->SetDrawColor(128,0,0);
		    $this->SetLineWidth(.3);
		    $this->SetFont('','B',10);
		    // Cabecera
		    $w = array(15, 60, 55, 30, 30);
		    for($i=0;$i<count($cabecera);$i++)
		        $this->Cell($w[$i],12,utf8_decode($cabecera[$i]),'B',0,'C',true);
		    $this->Ln();
		    // Restauración de colores y fuentes
		    $this->SetFillColor(224,235,255);
		    $this->SetTextColor(0);
		    $this->SetFont('','',9);
		    // Datos
		    $fill = false;
		    foreach($datos as $row)
		    {
		    	$this->SetX(12);
		        $this->Cell($w[0],10,utf8_decode($row[0]),'B',0,'C',$fill);
		        $this->SetFont('Arial','',10);
		        $this->Cell($w[1], 10, utf8_decode($row[1]), 'B',0, 'L', $fill);
		    	$this->Cell($w[2],10,utf8_decode($row[2]),'B',0,'C',$fill);
		    	$this->SetFont('Arial','',9);
		        $this->Cell($w[3], 10, utf8_decode($row[4]), 'B',0, 'C', $fill);
		    	$this->SetFont('','',9);
		        $this->Cell($w[4],10,utf8_decode("BsS. ".number_format($row[3])),'B',0,'L',$fill);
		        $this->Ln();
		        $fill = !$fill;
		    }
		    // Línea de cierre
		    $this->Cell(array_sum($w)-12,0,'','T');
		}
	}

?>