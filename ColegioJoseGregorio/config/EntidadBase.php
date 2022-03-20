<?php

  class EntidadBase{
    private $table;
    private $db;
    private $conectar;
    private $currentYear;
    private $currentMonth;
    private $currentDay;
    private $currentDayN;
    private $trimestreActual;
    protected $id_estudiante;
    protected $id_momento_estudiante;
    protected $id_usuario;

    public function __construct($table){
      $this->table = (string) $table;
      require_once 'Conectar.php';
      $this->conectar = new Conectar();
      $this->db = $this->conectar->conexion();
    }

    public function getConectar(){
      return $this->conectar;
    }

    public function db(){
      return $this->db;
    }

    public function establecerYearActual(){
      $this->currentYear = (int)date("Y");
    } 

    public function establecerMesActual(){
      $this->currentMonth = (int)date("m");
    }

    public function establecerDiaActual(){
      $this->currentDay = (int)date("j");
      $this->currentDayN = date("d");
    }

    public function establecer_momento_estudiante($momento_estudiante){
      $this->id_momento_estudiante = $momento_estudiante;
    }

    public function establecer_usuario($usuario){
      $this->id_usuario = $usuario;
    }

    public function eliminarEstudiante($cedula, $id_tipo_estudiante, $representante_cedula){
      $sql1 = $this->db->query("SELECT tipo_comprador.cliente FROM estudiante INNER JOIN tipo_comprador ON estudiante.tipo_comprador = tipo_comprador.id WHERE estudiante.cedula = '$cedula'");

      $sql2 = $this->db->query("SELECT cliente, tipo FROM tipo_estudiante WHERE id = $id_tipo_estudiante");
      
      if($sql1->num_rows == 1 && $sql2->num_rows == 1){
        $es = $sql1->fetch_assoc();
        $cliente_comprador = $es["cliente"];
        
        $res = $sql2->fetch_assoc();
        $cliente_te = $res["cliente"];
        $tipo_estudiante = $res["tipo"];

        if($tipo_estudiante == "momento_estudiante"){
          $sql3 = $this->db->query("SELECT deuda_meses.tipo_deuda_mes FROM deuda_meses INNER JOIN momento_estudiante ON deuda_meses.momento_estudiante = momento_estudiante.id INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id WHERE estudiante.cedula = '$cedula' AND momento_estudiante.tipo_estudiante = $id_tipo_estudiante");
        }else if($tipo_estudiante == "estudiante_deudor_antiguo"){
          $sql3 = $this->db->query("SELECT deuda_antigua.tipo_deuda_mes FROM deuda_antigua INNER JOIN estudiante_deudor_antiguo ON deuda_antigua.estudiante_deudor_antiguo = estudiante_deudor_antiguo.id INNER JOIN estudiante ON estudiante_deudor_antiguo.estudiante = estudiante.id WHERE estudiante.cedula = '$cedula' AND estudiante_deudor_antiguo.tipo_estudiante = $id_tipo_estudiante");
        }
        if($sql3->num_rows > 0){
          while ($r = $sql3->fetch_assoc()) {
            $tipo_deuda_mes = $r["tipo_deuda_mes"];
            $this->db->query("DELETE FROM tipo_deuda_mes WHERE id = $tipo_deuda_mes");
          }
        } 

        $hijos_representante = $this->db->query("SELECT COUNT(estudiante.id) AS cantidad_hijos FROM estudiante INNER JOIN representante ON representante.id = estudiante.representante WHERE representante.cedula = '$representante_cedula'");
        $rch = $hijos_representante->fetch_assoc();
        $cantidad_hijos = $rch["cantidad_hijos"];
        //SI EL REPRESENTANTE TIENE UN SOLO HIJO QUE ES EL ESTUDIANTE A ELIMINAR.
        if($cantidad_hijos == 1){
          if(!$this->facturasClientes($representante_cedula, "representante")){
            $resultado_representante = $this->db->query("SELECT tipo_comprador.cliente FROM representante INNER JOIN tipo_comprador ON representante.tipo_comprador = tipo_comprador.id WHERE representante.cedula = '$representante_cedula'");
            $cr = $resultado_representante->fetch_assoc();
            $cliente_representante = $cr["cliente"];
            $this->db->query("DELETE FROM cliente WHERE id = $cliente_representante");
          }
        }

        $this->db->query("DELETE FROM cliente WHERE id = $cliente_te");
        $this->db->query("DELETE FROM cliente WHERE id = $cliente_comprador");
        
        return true;
      }else{
        return false;
      }
    }

    public function deshabilitarEstudiante($cedula){
      $deshabilitar = $this->db->query("UPDATE estudiante SET habilitado = 0 WHERE cedula = '$cedula'");
      if($deshabilitar){
        return true;
      }else{
        return false;
      }
    }

    public function facturaInscripcion_periodoActual($cedula){
      $id_periodo = $this->obtener_Id_periodoActual();
      $consulta = $this->db->query("
                  SELECT
                    inscripciones_pagas.factura_inscripcion AS codigo_factura
                  FROM inscripciones_pagas
                  INNER JOIN tipo_deuda_inscripcion ON inscripciones_pagas.tipo_deuda_inscripcion = tipo_deuda_inscripcion.id
                  INNER JOIN momento_estudiante ON tipo_deuda_inscripcion.momento_estudiante = momento_estudiante.id
                  INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id
                  WHERE
                    estudiante.cedula = '$cedula' AND momento_estudiante.periodo_escolar = $id_periodo");
      if($consulta->num_rows > 0){
        return true;
      }else{
        return false;
      }
    }

    public function facturasEstudiante($cedula, $factura, $estudiante){
      //VALORES DE $factura ~= normal AND inscripcion
      //VALORES DE $estudiante ~= momento_estudiante AND estudiante_deudor_antiguo
      //COMBINADOS SI Y SOLO SI = $factura = normal OR inscripcion WITH $estudiante = momento_estudiante
      //COMBINADO SI Y SOLO SI = $factura = normal WITH $estudiante = estudiante_deudor_antiguo
      $consulta = $this->db->query("
                  SELECT
                    factura_$factura.id AS codigo_factura
                  FROM tipo_factura
                  INNER JOIN factura_$factura ON factura_$factura.tipo_factura = tipo_factura.id
                  INNER JOIN cliente ON tipo_factura.cliente = cliente.id 
                  INNER JOIN tipo_estudiante ON tipo_estudiante.cliente = cliente.id
                  INNER JOIN $estudiante ON $estudiante.tipo_estudiante = tipo_estudiante.id
                  INNER JOIN estudiante ON $estudiante.estudiante = estudiante.id
                  WHERE
                    estudiante.cedula = '$cedula'");
      if($consulta->num_rows > 0){
        return true;
      }else{
        return false;
      }
    }

    public function facturasClientes($cedula, $cliente){
      //VALORE POSIBLES PARA CLIENTE: estudiante, persona, usuario, representante
      $consulta = $this->db->query("
                  SELECT
                    factura_producto.id AS codigo_factura
                  FROM tipo_factura
                  INNER JOIN factura_producto ON factura_producto.tipo_factura = tipo_factura.id
                  INNER JOIN cliente ON tipo_factura.cliente = cliente.id 
                  INNER JOIN tipo_comprador ON tipo_comprador.cliente = cliente.id
                  INNER JOIN $cliente ON $cliente.tipo_comprador = tipo_comprador.id
                  WHERE
                    $cliente.cedula = '$cedula'");
      if($consulta->num_rows > 0){
        return true;
      }else{
        return false;
      }
    }

    public function deudaMeses(){
        $this->establecerDiaActual();
        $resultado = $this->db->query("
                      SELECT
                        mes.id
                      FROM deuda_meses
                      INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id
                      INNER JOIN mes ON meses_periodo.mes = mes.id
                      INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
                      WHERE 
                        deuda_meses.momento_estudiante = $this->id_momento_estudiante AND
                        (tipo_deuda_mes.estado_pago = 2 OR
                        tipo_deuda_mes.estado_pago = 3)
                      ORDER BY mes.id ASC
                      LIMIT 1");
        if($resultado->num_rows == 1){
            $ma = $this->mes_actual_ordernado();
            $row = $resultado->fetch_assoc();
            $numero_mes = (int)$row["id"];
            
            //SI DEBE MESES ANTERIORES AL ACTUAL
            if($numero_mes < $ma){
                $bandera = true;
            //SI DEBE EL MES ACTUAL
            }else if($numero_mes == $ma) {
                //SI EL DIA ACTUAL ES MAYOR O IGUAL A 6
                //DEBE EL MES ACTUAL
                if($this->currentDay >= 6){
                    $bandera = true;
                //SI ES MENOR QUE 6 ENTONCES NO DEBE TODAVIA EL MES
                }else{
                    $bandera = false;
                }
            //SI ES MAYOR AL MES ACTUAL NO DEBE NADA
            }else{
                $bandera = false;
            }
        //SINO SE CONCIGUIERON MESES EN DEUDA NO DEBE NADA         
        }else{
            $bandera = false;
        }

        return $bandera;
    }

    public function mes_actual_ordernado($normal = 0){
       $this->establecerMesActual();
        if($normal == 0){
            if($this->currentMonth >= 9 && $this->currentMonth <= 12){
                $this->currentMonth = $this->currentMonth - 8;
            }else{
                $this->currentMonth = $this->currentMonth + 4;
            }
        }else{
            if($this->currentMonth >= 1 && $this->currentMonth <= 4){
                $this->currentMonth = $this->currentMonth + 8;
            }else{
                $this->currentMonth = $this->currentMonth - 4;
            }
        }

        return $this->currentMonth;
    }

    public function obtener_periodoActual(){
        if($this->currentMonth >= 8){
            return array(
                "yearDesde" => (int)$this->currentYear,
                "yearHasta" => (int)($this->currentYear+1)
            );
        }else{
            return array(
                "yearDesde" => (int)($this->currentYear-1),
                "yearHasta" => (int)$this->currentYear
            );
        }
    }

    public function obtener_Id_periodoActual(){
      $this->establecerYearActual();
      $this->establecerMesActual();

      $periodo_actual = $this->obtener_periodoActual();

      $resultado = $this->db->query("SELECT id FROM periodo_escolar WHERE year_inicia = ".$periodo_actual["yearDesde"]." AND year_termina = ".$periodo_actual["yearHasta"]);
      
      if($resultado->num_rows == 1){
        $r = $resultado->fetch_assoc();
        return $r["id"];
      }else{
        return false;
      }
    }

    public function registrarCliente($tipo){
      $r = $this->db->query("INSERT INTO cliente (tipo) VALUES('$tipo')");
      return $this->db->insert_id;
    }

    public function registrarComprador($tipo){
      $id_cliente = $this->registrarCliente('c');
      $r = $this->db->query("INSERT INTO tipo_comprador (ultima_modificacion, cliente, tipo) VALUES (NOW(),$id_cliente,'$tipo')");
      return $this->db->insert_id;
    }

    public function registrarTipoEstudiante($tipo){
      $id_cliente = $this->registrarCliente('e');
      $r = $this->db->query("INSERT INTO tipo_estudiante (tipo, hora_registro, fecha_registro, cliente) VALUES ('$tipo', NOW(), NOW(), $id_cliente)");
      return $this->db->insert_id;
    }

    public function registrar_periodo_escolar(){
      if($this->currentMonth == 8 || $this->currentMonth == 9){
        $periodo_actual = $this->obtener_periodoActual();

        $sql = "SELECT id FROM periodo_escolar WHERE year_inicia = ".$periodo_actual["yearDesde"] ." AND year_termina = ".$periodo_actual["yearHasta"];
        $result = $this->db->query($sql);

        if($result->num_rows == 0){
          $registrar = "INSERT INTO periodo_escolar (nombre, year_inicia, year_termina, finalizado) VALUES ('".$periodo_actual["yearDesde"]." - ".$periodo_actual["yearHasta"]."',".$periodo_actual["yearDesde"].",".$periodo_actual["yearHasta"].",0)";
          $r = $this->db->query($registrar);
          if($r){
            $id_periodo = $this->db->insert_id;
            $ultima_mensualidad = "SELECT id FROM mensualidad WHERE 1 ORDER BY id DESC LIMIT 0,1";
            $result = $this->db->query($ultima_mensualidad);
            if($result->num_rows == 1){
              $im = $result->fetch_assoc();
              $id_mensualidad = $im["id"];
            }else{
              $id_mensualidad = 1;
            }
            for($i = 1; $i <= 12; $i++){
              $this->db->query("INSERT INTO meses_periodo (mensualidad, mes, periodo_escolar) VALUES ($id_mensualidad,$i,$id_periodo)");
            }
          }
        }
      }    
    }

    public function estudiante_debe_meses(){
      $meses_deuda = $this->db->query("
              SELECT 
                deuda_meses.id 
              FROM deuda_meses 
              INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id 
              WHERE 
                (tipo_deuda_mes.estado_pago = 3 OR 
                tipo_deuda_mes.estado_pago = 2) AND 
                deuda_meses.momento_estudiante =  $this->id_momento_estudiante");

      if($meses_deuda->num_rows > 0){
        return true;
      }else{
        return false;
      }
    }

    public function estudianteNormal_deudorAntiguo(){
      //SE OBTIENEN LOS MESES EN DEUDA QUE TIENE EL ESTUDIANTE
      $meses_deuda = $this->db->query("
              SELECT 
                tipo_deuda_mes.estado_pago, 
                tipo_deuda_mes.diferencia, 
                meses_periodo.mes
              FROM deuda_meses 
              INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id 
              INNER JOIN meses_periodo ON deuda_meses.meses_periodo = meses_periodo.id 
              WHERE 
                (tipo_deuda_mes.estado_pago = 3 OR 
                tipo_deuda_mes.estado_pago = 2) AND 
                deuda_meses.momento_estudiante = $this->id_momento_estudiante
              ORDER BY meses_periodo.mes ASC");

      //SE VERIFICA SI TIENE MESES EN DEUDA
      if($meses_deuda->num_rows > 0){
        //HABILITANDO PARA DEUDOR ANTIGUO
        $this->db->query("UPDATE estudiante SET habilitado = 2 WHERE id = $this->id_estudiante");

        $id_te = $this->registrarTipoEstudiante('estudiante_deudor_antiguo');

        //REGISTRANDO AL ESTUDIANTE COMO DEUDOR ANTIGUO
        $this->db->query("INSERT INTO estudiante_deudor_antiguo (estudiante, tipo_estudiante, habilitado) VALUES ($this->id_estudiante, $id_te, 1)");
        $id_est_da = $this->db->insert_id;

        //BUCLE EN DONDE SE PASA LA DEUDA DEL ESTUDIANTE A DEUDA ANTIGUA.
        while($rowd = $meses_deuda->fetch_assoc()){
          $estado_pago = $rowd["estado_pago"];
          $diferencia = $rowd["diferencia"];
          $mes = $rowd["mes"];

          $this->db->query("INSERT INTO tipo_deuda_mes (estado_pago, diferencia) VALUES ($estado_pago,$diferencia)");
          $id_tipo_deuda = $this->db->insert_id;

          $this->db->query("INSERT INTO deuda_antigua (estudiante_deudor_antiguo, mes, tipo_deuda_mes) VALUES ($id_est_da, $mes, $id_tipo_deuda)");
        }

        $this->db->query("
              UPDATE 
                deuda_meses
              INNER JOIN tipo_deuda_mes ON deuda_meses.tipo_deuda_mes = tipo_deuda_mes.id
              SET 
                tipo_deuda_mes.estado_pago = 4 
              WHERE
                (tipo_deuda_mes.estado_pago = 3 OR 
                tipo_deuda_mes.estado_pago = 2) AND  
                deuda_meses.momento_estudiante = $this->id_momento_estudiante");
        echo "Estudiante pasado a Deudor Antiguo";
      }
    }

    public function estudianteNormal_espera(){
      //ESTUDIANTE A ESPERA
      $modificar_estudiante = "UPDATE estudiante SET habilitado = 3 WHERE id = $this->id_estudiante";
      $resultado = $this->db->query($modificar_estudiante);
      echo "Estudiante a Estudiante en Espera";
    }

    public function traspaso_estudiante(){
      if($this->currentMonth == 8 || $this->currentMonth == 9){
        $periodo_actual = $this->obtener_periodoActual();

        $sql = "SELECT id, finalizado FROM periodo_escolar WHERE year_inicia = (".$periodo_actual["yearDesde"]." - 1) AND year_termina = (".$periodo_actual["yearHasta"]." - 1)";
        $result = $this->db->query($sql);

        if($result->num_rows == 1){
          $datos_periodo = $result->fetch_assoc();
          $finalizado = (int)$datos_periodo["finalizado"];
          $id_pe = $datos_periodo["id"];

          //VERIFICANDO SI EL PERIODO YA FUE MODIFICADO A FINALIZADO
          if($finalizado == 0){
            $consulta_estudiantes = $this->db->query("
                      SELECT 
                        estudiante.id AS estudiante, 
                        momento_estudiante.id AS momento_estudiante 
                      FROM momento_estudiante 
                      INNER JOIN estudiante ON momento_estudiante.estudiante = estudiante.id 
                      WHERE 
                        momento_estudiante.periodo_escolar = $id_pe AND 
                        estudiante.habilitado = 1");

            if($consulta_estudiantes->num_rows > 0){
              while ($filad = $consulta_estudiantes->fetch_assoc()) {
                $this->id_momento_estudiante = $filad["momento_estudiante"];
                $this->id_estudiante = $filad["estudiante"];
                echo "Verificando a Estudiante: " . $this->id_estudiante . " - Momento Estudiante: " . $this->id_momento_estudiante;
                if($this->estudiante_debe_meses()){
                  $this->estudianteNormal_deudorAntiguo();
                }else{
                  $this->estudianteNormal_espera();
                }
              }
            }

            $this->db->query("UPDATE periodo_escolar SET finalizado = 1 WHERE id = $id_pe");
            echo "Periodo Finalizado!";
          }
        }
      }    
    }

    public function borrarActividades_anterioresActual(){
      $fecha_actual = $this->currentYear . "-" .$this->currentMonth . "-" . $this->currentDayN;
      $this->db->query("DELETE FROM historial_tareas WHERE fecha < $fecha_actual");
    }
  }

?>
