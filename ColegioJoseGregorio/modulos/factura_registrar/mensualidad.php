<?php
	session_start();
	if(isset($_SESSION["nombre_completo"]) && !empty($_SESSION["nombre_completo"])){
		date_default_timezone_set ("America/Caracas"); 
		$fecha_actual = date("Y") . "-" . date("m") . "-" . date("d");
		$aa = (int)date("Y");
		$ma = (int)date("m");
		$mensaje = "";
		$advertencia = 0;
		$registros = 0;
		require_once "../../config/EntidadBase.php";
		require_once "../../config/functions.php";
		
		//VARIABLE QUE GUARDA EL ID DEL USUARIO QUE REGISTRA
		$id_usuario = $_SESSION["id"];

		$entidad = new EntidadBase("tipo_factura");
		$conexion = $entidad->db();

		//SE RECIBE EL OBJETO JSON DEL CLIENTE
		$superdata = $_POST["superdata"];
		//SE DECODIFICA EL OBJETO JSON A ARRAY
		$array_superdata = json_decode($superdata, true, 512, JSON_BIGINT_AS_STRING);

		//ARRAY QUE GUARDA TODOS LOS DATOS IMPORTANTES
		$datos_generales = $array_superdata[0]; 

		$id_estudiante = $datos_generales["id_estudiante"]; //TIPO ESTUDIANTE
		$monto_total = $datos_generales["total_pagado"]; //TOTAL FACTURA
		$subtotal = $datos_generales["subtotal"];	//SUBTOTAL FACTURA
		$total_mora = $datos_generales["total_mora"]; //TOTAL MORA FACTURA
		$diferencia_factura = $datos_generales["diferencia_factura"]; //DIFERENCIA

		//ARRAY MONTOS A PAGAR QUE GUARDA EL ID, CANCELADO, ABONADO, DIFERENCIA
		$montos_pagar = $array_superdata[1];
		//VARIABLE QUE GUARDA EL TAMAÑO DEL ARRAY DE LOS MONTOS A PAGAR
		$tam_mp = count($montos_pagar);

		//ARRAY QUE GUARDA LOS TIPOS DE PAGO CON SU REFERENCIA Y CANTIDAD
		$tipos_pagos = $array_superdata[2];
		//VARIABLE QUE GUARDA EL TAMAÑO DEL ARRAY DE TIPO DE PAGO
		$tam_tp = count($tipos_pagos);
		/*var_dump($datos_generales);
		var_dump($montos_pagar);
		var_dump($tipos_pagos);*/
		$re = $conexion->query("SELECT tipo FROM tipo_estudiante WHERE id = $id_estudiante");
		$te = $re->fetch_assoc();
		$type_student = $te["tipo"]; //NOMBRE DEL TIPO DE ESTUDIANTE

		$rm = $conexion->query("SELECT id FROM mora WHERE 1 ORDER BY id DESC LIMIT 0,1");
		if($rm->num_rows == 0 ){
			echo "¡NO SE HA REGISTRADO PORCENTAJE DE MORA!";
		}else{
			$idm = $rm->fetch_assoc();
			$id_mora = $idm["id"];

			$cc = $conexion->query("SELECT cliente FROM tipo_estudiante WHERE id = $id_estudiante");
			$cli = $cc->fetch_assoc();
			$id_cliente = $cli["cliente"]; //ID DEL CLIENTE

			$re = $conexion->query("SELECT id FROM mensualidad WHERE 1 ORDER BY id DESC LIMIT 0,1");
			$im = $re->fetch_assoc();
			$id_mensualidad = $im["id"]; //ID DE LA ULTIMA MENSUALIDAD

			//CONSULTA QUE REGISTRA A LA FACTURA EN FACTURA_NORMAL
			$result = $conexion->query("INSERT INTO  tipo_factura (tipo, cliente, fecha, hora, monto_total, usuario) VALUES ('m', $id_cliente,NOW(),NOW(),$monto_total,$id_usuario)");
			$id_tipo_factura = $conexion->insert_id;

			$r = $conexion->query("INSERT INTO factura_normal (mensualidad, tipo_factura, total_mora, subtotal, diferencia) VALUES ($id_mensualidad,$id_tipo_factura,$total_mora,$subtotal,$diferencia_factura)");
			//VARIABLE QUE GUARDA EL ID DE LA FACTURA REGISTRADA
			$id_factura = $conexion->insert_id;

			//BUCLE QUE RECORRE TODO EL ARRAY DE TIPO_PAGO
			for($i = 0; $i < $tam_tp; $i++){
				//VARIABLE QUE GUARDA EL ID DEL TIPO DE PAGO
				$tp = $tipos_pagos[$i]["id_tp"];
				//VARIABLE QUE GUARDA LA REFERENCIA
				$rtp = $tipos_pagos[$i]["referencia_tp"];
				//VARIABLE QUE GUARDA LA CANTIDAD
				$ctp = $tipos_pagos[$i]["cantidad_tp"];

				//SI EL ID DEL TIPO DE PAGO ES 1 (EFECTIVO)
				if($tp == 1){
					//SE REGISTRA EN LA TABLA EFECTIVO PARA QUE DE LA REFERENCIA
					$result = $conexion->query("INSERT INTO referencia_efectivo(tipo_factura) VALUES ($id_tipo_factura)");
					//SE GUARDA LA REFERENCIA EN LA VARIABLE
					$referencia = $conexion->insert_id;
				}else if($tp == 5){
					//SE REGISTRA EN LA TABLA PAGO NOMINA PARA QUE DE LA REFERENCIA
					$result = $conexion->query("INSERT INTO referencia_pago_nomina (tipo_factura) VALUES ($id_tipo_factura)");
					//SE GUARDA LA REFERENCIA EN LA VARIABLE
					$referencia = $conexion->insert_id;
				}else{
					//SI NO ES 1 ENTONCES PERMANECE LA REFERENCIA QUE VIENE DEL CLIENTE
					$referencia = $rtp;
				}
				//SE INSERTA EL PAGO DE LA FACTURA EN LA TABLA RESPECTIVA
				$res = $conexion->query("INSERT INTO pago_factura (tipo_factura, tipo_pago, referencia, monto) VALUES ($id_tipo_factura,$tp,'$referencia',$ctp)");
			}

			//BUCLE QUE RECORRE TODO EL ARRAY DE LOS MONTOS A PAGAR
			for($i = 0; $i < $tam_mp; $i++){
				//VARIBLE QUE GUARDA EL ID DEL MES A PAGAR
				$id_mes = $montos_pagar[$i]["id_mes"];
				$cancelado = $montos_pagar[$i]["cancelado"];
				$abonado = $montos_pagar[$i]["abonado"];
				$diferencia = $montos_pagar[$i]["diferencia"];
				$dias_mora= $montos_pagar[$i]["dias_mora"];

				//CONDICIONAL QUE BUSCA COLOCAR EL ESTADO DE PAGO
				//DEPENDIENDO DE LOS MONTOS OBTENIDOS
				if($cancelado == 0 && $abonado > 0 && $diferencia > 0){
					$estado_pago = 2;
				}else if($cancelado > 0){
					$estado_pago = 1;
					if($abonado > 0 && $diferencia > 0){
						$abonado = 0;
					}
				}

				//CONSULTA QUE MODIFICA CADA MES QUE SE PAGARA
				//EL ESTADO DE PAGO Y COLOCA LA DIFERENCIA
				$resultado1 = $conexion->query("UPDATE tipo_deuda_mes SET estado_pago = $estado_pago, diferencia = $diferencia WHERE id = $id_mes");
				
				//CONSULTA QUE REGISTRA LOS MESES CANCELADOS EN LA FACTURA
				$resultado2 = $conexion->query("INSERT INTO meses_pagos (factura, tipo_deuda_mes, estado_pago, mora, abonado, diferencia, dias_mora) VALUES ($id_factura, $id_mes, $estado_pago, $id_mora, $abonado, $diferencia, $dias_mora)");
					
			}

			//CONSULTA QUE REGISTRA LA ACTIVIDAD REALIZADA
			$resultado = $conexion->query("INSERT INTO historial_tareas (usuario, descripcion, fecha, hora) VALUES ($id_usuario,'REGISTRO DE FACTURACIÓN DE MENSUALIDAD',NOW(),NOW())");

			//ENVIO DEL AVISO EXITOSO
			$datos = array('mensaje' => "¡FACTURA REALIZADA CON EXITO! ESPERE UN MOMENTO PARA MOSTRARLA", 'advertencia' => 1, 'id_factura' => $id_tipo_factura);
			$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);
			echo $datos_json;
		}
	}else{
		echo "¡ACCESO DENEGADO!";
	}
?>