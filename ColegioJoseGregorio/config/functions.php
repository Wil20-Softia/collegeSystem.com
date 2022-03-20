<?php	
    //RETORNA LA FECHA EN CADENA DEPENDIENDO DE LA CANTIDAD DE GUIONES QUE TENGA.
	function reg_date($dt, $d = ""){ 
        // Fecha con día y mes en Español
        $fecha = explode("-", $dt);
        $day = array("Domingo,", "Lunes,", "Martes,", "Miércoles,", "Jueves,", "Viernes," ,"Sábado,");
        $month = array("","Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        if(count($fecha) == 4){
            $fecha_reg = $day[$fecha[3]]. " " .$fecha[2]. " de ". $month[$fecha[1]]." del ".$fecha[0];
        }else if(count($fecha) == 3){
            if($d == ""){
                $fecha_reg = $fecha[2] . " de " . $month[(int)$fecha[1]] . " del " . $fecha[0];
            }else{
                if($d == "formato_corto"){
                    $fecha_reg = $fecha[2]."/".$fecha[1]."/".$fecha[0];
                }else{
                    $fecha_reg = $month[$fecha[1]]." del ".$fecha[0];
                }
            } 
        }else{
            if(count($fecha) == 1){
                $fecha_reg = $month[$fecha[0]];
            }else{
                $fecha_reg = $month[$fecha[1]]." del ".$fecha[0];
            }
        }
        
        return $fecha_reg;
    }

    //ENCOJE EL TEXTO HASTA ADAPTARSE AL TAMAÑO DE LA CELDA, EN DONDE SE ENCUENTRA.
    //MUESTRA LA CELDA YA FINALIZADO CON EL TEXTO ADAPTADO Y VUELVE A COLOCAR EL TAMAÑO
    //DE LA FUENTE A LA ORIGINAL ANTERIOR.
    function encogimientoTexto($obj, $fontSize, $cellWidth, $cellHeight, $texto, $borde=0, $saltoLinea=0, $alineacion="L"){
        //Tamaño de letra temporal.
        $tempFontSize = $fontSize;
        //bucle hasta que el ancho de la cadena es menor que el ancho de la celda.
        while($obj->GetStringWidth($texto) > $cellWidth){
            $obj->SetFontSize($tempFontSize -= 0.5);
        }
        $obj->Cell($cellWidth, $cellHeight, utf8_decode($texto), $borde, $saltoLinea, $alineacion);

        //Reseteando el Tamaño de Fuente Estandar.
        $tempFontSize = $fontSize;
        $obj->SetFontSize($fontSize);
    }

    function delete_folder($carpeta){
        foreach(glob($carpeta . "/*") as $archivos_carpeta){
          if (is_dir($archivos_carpeta)){
            delete_folder($archivos_carpeta);
          } else {
            unlink($archivos_carpeta);
          }
        }
        rmdir($carpeta);
    }

    function encontrarTrimestre($mes){
        if($mes >= 1 && $mes <= 3){
            return 1;
        }if($mes >= 4 && $mes <= 6){
            return 2;
        }if($mes >= 7 && $mes <= 9){
            return 3;
        }else{
            return 4;
        }
    }

    function periodoActual($mesActual,$yearActual){
        if($mesActual >= 8){
            return array(
                "yearDesde" => (int)$yearActual,
                "yearHasta" => (int)($yearActual+1)
            );
        }else{
            return array(
                "yearDesde" => (int)($yearActual-1),
                "yearHasta" => (int)$yearActual
            );
        }
    }

    function mes_actual_ordernado($mesActual, $normal = 0){
        if($normal == 0){
            if($mesActual >= 9 && $mesActual <= 12){
                $mesActual = $mesActual - 8;
            }else{
                $mesActual = $mesActual + 4;
            }
        }else{
            if($mesActual >= 1 && $mesActual <= 4){
                $mesActual = $mesActual + 8;
            }else{
                $mesActual = $mesActual - 4;
            }
        }

        return $mesActual;
    }

    function meses_permitidos($mes){
        if($mes == 8 || $mes == 9){
            $retorno = 9;
        }else if($mes != 6){
            $retorno = $mes;
        }
        return mes_actual_ordernado($retorno);
    }

    function formatearNumerico($numero){
        $numero = (float)$numero;
        $n = str_replace(",","",$numero);
        $n = number_format($n,2,',','.'); 
        return $n;
    }

    # Encriptando Password
    function encryp_pass($pass){
        $pass = password_hash($pass,PASSWORD_DEFAULT);
        return $pass;
    }

    # Descifrando Password
    function desencryp_pass($pass_db, $pass_user){
        if(password_verify($pass_user,$pass_db)){
          return true;
        }else{
          return false;
        }
    }

    function array_push_assoc(array &$arrayDatos, array $values){
        $arrayDatos = array_merge($arrayDatos, $values);
    }
?>