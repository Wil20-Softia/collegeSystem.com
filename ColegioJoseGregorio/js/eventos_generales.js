$(document).ready(function() {
	$(document).on("keyup",".busqueda",function(event) {
          let tecla = (event.keyCode ? event.keyCode : event.which);
          let campoBuscar;
          if(tecla === 13 || tecla == "Enter"){
              $(".busqueda").focus();
              campoBuscar = $(".busqueda").val();
              switch(searching){
                  case 1:
                    busqueda_control(campoBuscar, listadoEstudiantesMensualidad);
                  break;
                  case 3:
                    busqueda_control(campoBuscar, listadoEstudiantesInscripcion);
                  break;
              }
          }
    });

    $(document).on("click","#mas_tipo_pago",function(){
        change_form_fact++;
        if(change_form_fact == 1){
          let tipo_e = parseInt($("#id_estudiante").text());
          if(formulario === "mens"){
            pagos_estudiante_mensualidad(tipo_e, "#contenedor_meses_factura");
          }else if(formulario === "insc"){
            pagos_estudiante_inscripcion(tipo_e, "#contenedor_montos_inscripciones");
          }
          $("#cantidad_factura").val('');
        }
        $("#contenedor_tipos_pago")
        .append(`
            <div class="r-h4 bd-ab bd-blanco">
                <div class="column-11-12">
                    <div class="r-h6">
                        <div id="caja_tp" numero="${numeros_tipo_pago}" class="column-6-12 pd-ssm">
                            <select class="select_tipo_pago wh-completo texto-centrado pd-null" numero="${numeros_tipo_pago}">
                                <option value="0">Tipo</option>
                            </select>
                        </div>
                        <div id="caja_referencia" numero="${numeros_tipo_pago}" class="column-6-12 pd-ssm">
                            <input type="text" class="referencia_pago wh-completo texto-centrado" numero="${numeros_tipo_pago}" placeholder="Nro Referencia">
                        </div>
                    </div>
                    <div class="r-h6">
                        <div class="column-3-12"></div>
                        <div id="caja_cant-tp" numero="${numeros_tipo_pago}" class="column-6-12 pd-ssm">
                            <input type="text" class="cantidad_tp wh-completo texto-centrado" numero="${numeros_tipo_pago}" placeholder="Cantidad" value="0,00">
                        </div>
                    </div>
                </div>
                <div class="column-1-12">
                    <div class="r-h4"></div>
                    <div class="r-h5">
                        <a href="#" numero="${numeros_tipo_pago}" class="btn btn-danger eliminar_tipo_pago txt-ppq pd-ssm">
                            <i class="fas fa-minus"></i>
                        </a>
                    </div>
                </div>
            </div>
        `);
        tipos_pago("select[numero='"+numeros_tipo_pago+"']");
        $("input[numero='"+numeros_tipo_pago+"'].cantidad_tp").addClass('cal-num');
        soloNumeros("input[numero='"+numeros_tipo_pago+"'].referencia_pago");
        numeros_tipo_pago++;
    });

    $(document).on("click","#mas_tipo_pago_producto",function(){
        $("#contenedor_tipos_pago")
        .append(`
          <div class="r-h6 bd-ab bd-negro">
            <div class="column-11-12">
              <div class="r-h6">
                <div id="caja_tp" numero="${numeros_tipo_pago}" class="column-6-12 pd-ssm">
                  <select class="select_tipo_pago wh-completo texto-centrado pd-null input-transparente-negro" numero="${numeros_tipo_pago}">
                      <option value="0">Tipo</option>
                  </select>
                </div>
                <div id="caja_referencia" numero="${numeros_tipo_pago}" class="column-6-12 pd-ssm">
                  <input type="text" class="referencia_pago wh-completo texto-centrado input-transparente-negro" numero="${numeros_tipo_pago}" placeholder="Nro Referencia">
                </div>
              </div>
              <div id="box_cant_tp" class="r-h6">
                <div class="column-3-12"></div>
                <div id="caja_cant-tp" numero="${numeros_tipo_pago}" class="column-6-12 pd-ssm">
                  <input type="text" class="cantidad_tp wh-completo texto-centrado input-transparente-negro" numero="${numeros_tipo_pago}" placeholder="Cantidad" value="0,00">
                </div>
              </div>
            </div>
            <div class="column-1-12">
              <div class="r-h4"></div>
              <div class="r-h5">
                <a href="#" numero="${numeros_tipo_pago}" class="btn btn-danger eliminar_tipo_pago txt-ppq pd-ssm">
                  <i class="fas fa-minus"></i>
                </a>
              </div>
            </div>
          </div>
        `);
        tipos_pago("select[numero='"+numeros_tipo_pago+"']");
        $("input[numero='"+numeros_tipo_pago+"'].cantidad_tp").addClass('cal-num');
        soloNumeros("input[numero='"+numeros_tipo_pago+"'].referencia_pago");
        numeros_tipo_pago++;
    });

    $(document).on("click",".eliminar_tipo_pago",function(){
        if(formulario == "insc" || formulario == "mens"){
          change_form_fact++;
          if(change_form_fact == 1){
            let tipo_e = parseInt($("#id_estudiante").text());
            if(formulario === "mens"){
              pagos_estudiante_mensualidad(tipo_e, "#contenedor_meses_factura");
            }else if(formulario === "insc"){
              pagos_estudiante_inscripcion(tipo_e, "#contenedor_montos_inscripciones");
            }
            $("#cantidad_factura").val('');
          }
        }else if(formulario == "prod"){
          let abonado = Texto_Decimal($($(this)[0]).parent().parent().parent().children('.column-11-12').children('#box_cant_tp').children('#caja_cant-tp').children('.cantidad_tp').val());
          $("#abonado").html(Decimal_Texto(Texto_Decimal($("#abonado").text()) - abonado));
          $("#diferencia").html(Decimal_Texto(Texto_Decimal($("#diferencia").text()) - abonado));
        }

        $($(this)[0]).parent().parent().parent().remove();
        numeros_tipo_pago--;
    });

    $(document).on("change",".select_tipo_pago",function(){
        if(formulario == "insc" || formulario == "mens"){
          change_form_fact++;
          if(change_form_fact == 1){
            let tipo_e = parseInt($("#id_estudiante").text());
            if(formulario === "mens"){
              pagos_estudiante_mensualidad(tipo_e, "#contenedor_meses_factura");
            }else if(formulario === "insc"){
              pagos_estudiante_inscripcion(tipo_e, "#contenedor_montos_inscripciones");
            }
            $("#cantidad_factura").val('');
          }
        }
        
        let tipo_pago = parseInt($($(this)[0]).attr("numero"));
        let valor = parseInt($($(this)[0]).val());
        $("input[numero='"+tipo_pago+"'].referencia_pago").val('');
        if(valor == 1 || valor == 5){
          $("input[numero='"+tipo_pago+"'].referencia_pago").addClass('desactivar');
        }else{
            $("input[numero='"+tipo_pago+"'].referencia_pago").removeClass('desactivar');
        }
    });

    $(document).on("keyup",".referencia_pago",function(){
        if(formulario == "insc" || formulario == "mens"){
          change_form_fact++;
          if(change_form_fact == 1){
            let tipo_e = parseInt($("#id_estudiante").text());
            if(formulario === "mens"){
              pagos_estudiante_mensualidad(tipo_e, "#contenedor_meses_factura");
            }else if(formulario === "insc"){
              pagos_estudiante_inscripcion(tipo_e, "#contenedor_montos_inscripciones");
            }
            $("#cantidad_factura").val('');
          }
        }
    });

    $(document).on("keyup",".cantidad_tp",function(){
        if(formulario == "insc" || formulario == "mens"){
          change_form_fact++;
          if(change_form_fact == 1){
            let tipo_e = parseInt($("#id_estudiante").text());
            if(formulario === "mens"){
              pagos_estudiante_mensualidad(tipo_e, "#contenedor_meses_factura");
            }else if(formulario === "insc"){
              pagos_estudiante_inscripcion(tipo_e, "#contenedor_montos_inscripciones");
            }
            $("#cantidad_factura").val('');
          }
        }
    });

    $(document).on('change', '#categoria', function(event) {
        let valor = parseInt($(this).val());
        if(valor == 0){
            /*PARTE PARA DESACTIVAR Y REINICIAR UN SELECT*/
            $("#grado").addClass('desactivar');
            $("#grado").empty();
            $("#grado").html("<option value='0'>Año/Grado</option>");
            /*HASTA AQUI*/
        }else{
            $("#grado").removeClass('desactivar');
            grados_categorias("#grado", valor);
        }
        /*PARTE PARA DESACTIVAR Y REINICIAR UN SELECT*/
        $("#seccion").addClass('desactivar');
        $("#seccion").empty();
        $("#seccion").html("<option value='0'>Sección</option>");
        /*HASTA AQUI*/

        $(".btn-buscar-control").addClass('desactivar');
    });

    $(document).on("change","#grado",function(){
        let valor = parseInt($(this).val());
        if(valor == 0){
            /*PARTE PARA DESACTIVAR Y REINICIAR UN SELECT*/
            $("#seccion").addClass('desactivar');
            $("#seccion").empty();
            $("#seccion").html("<option value='0'>Sección</option>");
            /*HASTA AQUI*/
        }else{
            $("#seccion").removeClass('desactivar');
            secciones_grados("#seccion",valor);
        }

        $(".btn-buscar-control").addClass('desactivar');
    });

    $(document).on('change', '#seccion', function(event) {
        if($(this).val() == 0){
            $(".btn-buscar-control").addClass('desactivar');
        }else{
            $(".btn-buscar-control").removeClass('desactivar');
        }
    });

    $(document).on("change","#categoria_producto",function(){
        let valor = parseInt($(this).val());
        $("#subcategoria_producto").empty();
        $("#subcategoria_producto").html("<option value='0'>Subcategoria</option>");
        if(valor == 0){
            $("#subcategoria_producto").addClass('desactivar');
        }else{
            $("#subcategoria_producto").removeClass('desactivar');
            subcategorias_productos("#subcategoria_producto",valor);
        }
    });

    $(document).on('blur', ".cal-num", function(event){
        let valor = $(this).val();
        $(this).val(Decimal_Texto(valor));
    });

    $(document).on('focus', ".cal-num", function(event){
        let valor = $(this).val();
        $(this).val(Texto_Decimal(valor));
    });

    $(document).on("keypress",".campo-cedula-v",function(e){
      let tecla = (e.keyCode ? e.keyCode : e.which);
      let valor = $(this).val();
      if(valor.length == 0){
        $(this).val("V-"+valor);
      }
    });
});