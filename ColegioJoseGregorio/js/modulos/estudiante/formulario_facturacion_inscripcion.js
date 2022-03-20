//************ TITULO DE LA SECCION
let titulo_facturacion_inscripcion = "Pago de Inscripción del Estudiante. " + fecha_actual;
/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_facturacion_inscripcion = `
	<div class="wh-completo pd-ssm bd-1p-ngr">
        <!--MARGENES ARRIBA E IZQUIERDO-->
        <div class="r-h1m"></div>
        <div class="r-h10 column-10m-12 bg-dark texto-bln-sombra-ngr bd-1p-bln sombra-gris-caja-completa">
            <div class="r-h1m bd-1p-bln">
                <div class="column-m-12 pd-btigual-mitad txt-arial-light-pq">Id:</div>
                <div id="id_estudiante" class="column-1m-12 pd-btigual-mitad txt-arial-bold-pq"></div>

                <div class="column-m-12 txt-arial-light-pq pd-btigual-mitad">C.I:</div>
                <div id="cedula_estudiante" class="column-1m-12 pd-btigual-mitad txt-arial-bold-pq"></div>

                <div id="nombre_estudiante" class="column-4-12 pd-btigual-mitad txt-light-pq"></div>
                
                <div class="column-m-12"></div>
                <div id="seccion_especifica_estudiante" class="column-1m-12 pd-btigual-mitad texto-centrado txt-arial-bold-pq"></div>
            </div>
            <div class="r-h9">
                <div class="column-6-12">
                    <div class="r-h8 pd-ssm">
                        <div class="r-h2 bd-ab txt-arial-light-pq texto-centrado bd-blanco">
                            Tipo de Pago
                        </div>
                        <div id="contenedor_tipos_pago" class="r-h10 scrolleable">
                            <div class="r-h4 bd-ab bd-blanco">
                                <div class="column-11-12">
                                    <div class="r-h6">
                                        <div id="caja_tp" numero="1" class="column-6-12 pd-ssm">
                                            <select class="select_tipo_pago wh-completo texto-centrado pd-null" numero="1">
                                                <option value="0">Tipo</option>
                                            </select>
                                        </div>
                                        <div id="caja_referencia" numero="1" class="column-6-12 pd-ssm">
                                            <input type="text" class="referencia_pago wh-completo texto-centrado" numero="1" placeholder="Nro Referencia">
                                        </div>
                                    </div>
                                    <div class="r-h6">
                                        <div class="column-3-12"></div>
                                        <div id="caja_cant-tp" numero="1" class="column-6-12 pd-ssm">
                                            <input type="text" class="cantidad_tp wh-completo texto-centrado" numero="1" placeholder="Cantidad" value="0,00">
                                        </div>
                                    </div>
                                </div>
                                <div class="column-1-12">
                                    <div class="r-h4"></div>
                                    <div class="r-h5">
                                        <a href="#" id="mas_tipo_pago" class="btn btn-primary txt-ppq pd-ssm"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="r-h2">
                        <div class="column-4-12"></div>
                        <div id="caja_btn_calcular" class="column-4-12">
                            <a href="#" id="boton_calcular_cantidad_ins" class="btn btn-primary txt-pq"><i class="fas fa-calculator"></i> Calcular</a>
                        </div>
                    </div>
                    <div id="contenedor_grado_seccion" class="r-h2">
                        <div class="column-1-12"></div>
                        <div id="caja_grado_estudiante" class="column-5-12 pd-completo-pq">
                            <select id="grado_estudiante" class="wh-completo texto-centrado">
                                <option value="0">Año/Grado</option>
                            </select>
                        </div>
                        <div id="caja_seccion_estudiante" class="column-5-12 pd-completo-pq">
                            <select id="seccion_estudiante" class="wh-completo texto-centrado desactivar">
                                <option value="0">Sección</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="column-6-12">
                    <div class="r-h1m txt-arial-bold-pq bd-ab bd-blanco texto-centrado">
                        <div class="column-1m-12"></div>
                        <div class="column-3-12 pd-btigual-mitad bd-iz bd-de bd-blanco">Descrip.</div>
                        <div class="column-2m-12 pd-btigual-mitad bd-de bd-blanco">Cancelado</div>
                        <div class="column-2m-12 pd-btigual-mitad bd-de bd-blanco">Abonado</div>
                        <div class="column-2m-12 pd-btigual-mitad bd-de bd-blanco">Diferencia</div>
                    </div>
                    <div id="contenedor_montos_inscripciones" class="r-h10m bd-ab bd-iz bd-de bd-blanco">
                        
                    </div>
                </div>
            </div>

            <div class="r-h1m">
                <div class="column-1m-12"></div>
                <div class="column-5-12">
                    <a href="#" id="imprimir_factura_inscripcion" class="btn-verde txt-light-men mar-arr"><i class="far fa-save"></i> Guardar e <i class="fas fa-print"></i> Imprimir</a>
                </div>
                <div class="column-3-12 txt-light-men texto-centrado pd-btigual-mitad">Total a Pagar: </div>
                <div id="caja_cantidad_factura" class="column-2m-12 pd-ssm">
                    <input type="text" id="cantidad_factura" class="wh-completo texto-centrado desactivar" placeholder="Total de Factura">
                </div>
            </div>
        </div>
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_facturacion_inscripcion(){
    formulario = "insc";
    searching = 2;
    numeros_tipo_pago = 2;
    window.document.title = 'Sistema. Factuarción de Inscripción';
	$("#titulo_seccion").html(titulo_facturacion_inscripcion);
	$("#seccion_dinamica_principal").html(plantilla_facturacion_inscripcion);

   
    $("input[numero='1'].cantidad_tp").addClass('cal-num');
    NumerosConDecimal(".cal-num");

    tipos_pago(".select_tipo_pago");
    soloNumeros("input[numero='1'].referencia_pago");

    //validacion de la cedula del campo.
    mayusculasCampo(".busqueda_id_estudiante_ins");
    PosicionCaracterCampo(".busqueda_id_estudiante_ins","-",1);
    Validaciones(2,".busqueda_id_estudiante_ins",".caja-busqueda","Debe Ingresar la Cedula. Ejemplo: V-22333444 o E-22333444",patron_cedula);
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){

    $(document).on("click",".cancelar_inscripcion",function(){
        var tipo_e = parseInt($($(this)[0]).attr("id"));
        $(".close").trigger('click');
        $(".cargando").css('display', 'block');
        $.ajax({
            async: false,
            url: "modulos/basic/validacion_pago_inscripcion.php",
            dataType : "json",
            type: 'GET',
            data: {tipo_estudiante : tipo_e}
        })
        .done(function(response){
            setTimeout(function(){
                main_facturacion_inscripcion();
                $("#btn-busqueda_id_estudiante_ins").addClass('desactivar');
                $(".busqueda_id_estudiante_ins").addClass('desactivar');
                $("#periodo_escolar").addClass('desactivar');
                obtenerDatos({opcion:"estudiante_factura", tipo_estudiante: tipo_e, formulario: 'insc'}, renderizarDatos, "modulos/obtenerDatos.php");
                pagos_estudiante_inscripcion(tipo_e, "#contenedor_montos_inscripciones");
                $(".cargando").css('display', 'none');
            },1000);
        })
        .fail(function(response) {
            $(".close").trigger('click');
            $(".cargando").css('display', 'none');
            alertar(3, response.responseText);
            setTimeout(function(){
                ocultarAlerta(0.001);
                seccion_pagos(seccion_estudiantes);
            },5999);
        });        
    });


    $(document).on("change","#grado_estudiante",function(){
        let valor = parseInt($(this).val());
        if(valor == 0){
            /*PARTE PARA DESACTIVAR Y REINICIAR UN SELECT*/
            $("#seccion_estudiante").addClass('desactivar');
            $("#seccion_estudiante").empty();
            $("#seccion_estudiante").html("<option value='0'>Sección</option>");
            /*HASTA AQUI*/
        }else{
            $("#seccion_estudiante").removeClass('desactivar');
            secciones_grados("#seccion_estudiante",valor);
        }
    });

    $(document).on("click","#boton_calcular_cantidad_ins",function(){
        change_form_fact = 0; //SE REINICIA LA VARIABLE QUE CONTROLA EL REINICIO
                            //DE LOS PAGOS PENDIENTES DEL ESTUDIANTE EN LA FACTURA.

        let tipo_e = parseInt($("#id_estudiante").text());

        let cantidades_tp =  $(".cantidad_tp");
        let tipos_pago    =  $(".select_tipo_pago");
        let referencias   =  $(".referencia_pago");

        let tam = cantidades_tp.length;

        var sumatoria = 0, val_tipo_pago, val_cantidad, val_referencia, padre_tp, padre_rtp, padre_ctp, cantidad_validos = 0, advertido = 1;
        
        for(var i = 0; i < tam; i++){
            val_cantidad = Texto_Decimal($(cantidades_tp[i]).val(),"0");
            padre_ctp = $(cantidades_tp[i]).parent().attr("numero");
            padre_ctp = "div[id='caja_cant-tp'][numero='"+padre_ctp+"']";
            
            val_tipo_pago = $(tipos_pago[i]).val();
            padre_tp = $(tipos_pago[i]).parent().attr("numero");
            padre_tp = "div[id='caja_tp'][numero='"+padre_tp+"']";

            val_referencia = $(referencias[i]).val();
            padre_rtp = $(referencias[i]).parent().attr("numero");
            padre_rtp = "div[id='caja_referencia'][numero='"+padre_rtp+"']";
            
            if(!CampoVacio($(tipos_pago[i]))){
                advertido = 1;
                cantidad_validos = 0;
                advertenciaEnfocada(padre_tp,$(tipos_pago[i]),"Elijá el Tipo de Pago ¡ES OBLIGATORIO!",1);
                quitarAdvertenciaBlur($(tipos_pago[i]), padre_tp);
                break;
            }else if(!CampoVacio($(referencias[i])) && (val_tipo_pago > 1 && val_tipo_pago < 5)){
                cantidad_validos = 0;
                advertido = 1;
                advertenciaEnfocada(padre_rtp, $(referencias[i]),"Digíte el número de referencia para el pago respectivo ¡ES OBLIGATORIO!",1);
                quitarAdvertenciaBlur($(referencias[i]), padre_rtp);
                break;
            }else if(!CampoVacio($(cantidades_tp[i])) || val_cantidad == 0){
                advertido = 1;
                cantidad_validos = 0;
                advertenciaEnfocada(padre_ctp,$(cantidades_tp[i]),"¡El Campo es Obligatorio!",1);
                quitarAdvertenciaBlur($(cantidades_tp[i]), padre_ctp);
                break;
            }else{
                advertido = 0;
                sumatoria += val_cantidad;
                cantidad_validos++;
            }
        }

        if(cantidad_validos === tam && sumatoria > 0 && cedula_estudiante !== ''){
            sumatoria = parseFloat(sumatoria);
            $("#cantidad_factura").val(Decimal_Texto(sumatoria));
            pagos_estudiante_inscripcion(tipo_e, "#contenedor_montos_inscripciones", sumatoria);
        }else if(advertido == 0){
            advertenciaEnfocada(".caja-busqueda",".busqueda_id_estudiante_ins","No se puede Realizar el Calculo si no existe un Estudiante. Busquelo y luego Calcule");
            quitarAdvertenciaBlur(".busqueda_id_estudiante_ins", ".caja-busqueda");
        }
    });

    $(document).on("click","#imprimir_factura_inscripcion",function(e){
        e.preventDefault();
        change_form_fact = 0; //SE REINICIA LA VARIABLE QUE CONTROLA EL REINICIO
                            //DE LOS PAGOS PENDIENTES DEL ESTUDIANTE EN LA FACTURA.
        var supremo = [];
        var array_checks = [];
        var datos_check = {};
        var array_tp = [];
        var datos_tp = {};
        var datos = {};
        let id_estudiante = parseInt($("#id_estudiante").text());
        if(id_estudiante === '' || id_estudiante === 0){
            advertenciaEnfocada(".caja-busqueda",".busqueda_id_estudiante_ins","No se puede Registrar la Factura si no existe un Estudiante. Busquelo, Calcule y Registre");
            quitarAdvertenciaBlur(".busqueda_id_estudiante_ins", ".caja-busqueda");
        }else{
            let checkElegidos = $("input[type='checkbox']:checked");
            let tam_check = $("input[type='checkbox']:checked").length;
            if(tam_check == 0){
                advertenciaEnfocada("#caja_btn_calcular","#boton_calcular_cantidad_ins","No se puede Registrar la Factura debe Calcular el Monto a pagar");
                quitarAdvertenciaBlur("#boton_calcular_cantidad_ins", "#caja_btn_calcular");
            }else{
                let cantidades_tp = $(".cantidad_tp");
                let tipos_pago = $(".select_tipo_pago");
                let referencias = $(".referencia_pago");

                let tam_tp = tipos_pago.length;

                if(inscribir == 1){
                    if(!CampoVacio("#grado_estudiante")){
                        advertenciaEnfocada("#caja_grado_estudiante","#grado_estudiante","Elijá el Grado o Año para Inscribir al Estudiante ¡ES OBLIGATORIO!",1);
                        quitarAdvertenciaBlur($("#grado_estudiante"), "#caja_grado_estudiante");
                    }else if(!CampoVacio($("#seccion_estudiante"))){
                        advertenciaEnfocada("#caja_seccion_estudiante", $("#seccion_estudiante"),"Elijá la Sección en la cual Cursará el Estudiante ¡ES OBLIGATORIO!",1);
                        quitarAdvertenciaBlur($("#seccion_estudiante"), "#caja_seccion_estudiante");
                    }else{
                        let id, valor, cancelado, abonado, diferencia, tp, ctp, rtp;
                        datos = {
                            "id_estudiante"    : id_estudiante,
                            "total_pagado"     : Texto_Decimal($("#cantidad_factura").val(),"0"),
                            "inscribir"        : inscribir,
                            "seccion"          : parseInt($("#seccion_estudiante").val())
                        };
                        supremo.push(datos);

                        for(var i = 0; i < tam_check; i++){
                            id = $(checkElegidos[i]).attr("id");
                            valor = $(checkElegidos[i]).val();
                            cancelado = Texto_Decimal($("input[puntero='"+id+"'].cancelado").val(),"0");
                            abonado = Texto_Decimal($("input[puntero='"+id+"'].abonado").val(),"0");
                            diferencia = Texto_Decimal($("input[puntero='"+id+"'].diferencia").val(),"0");

                            datos_check = {
                                "id_monto": id,
                                "cancelado" : cancelado,
                                "abonado" : abonado,
                                "diferencia" : diferencia
                            };
                            array_checks.push(datos_check);
                        }
                        supremo.push(array_checks);
                        
                        for(var i = 0; i < tam_tp; i++){
                            tp = $(tipos_pago[i]).val();
                            ctp = Texto_Decimal($(cantidades_tp[i]).val(),"0");
                            rtp = $(referencias[i]).val();
                            datos_tp = {
                                "id_tp": tp,
                                "referencia_tp" : rtp,
                                "cantidad_tp" : ctp
                            };
                            array_tp.push(datos_tp);
                        }
                        supremo.push(array_tp);
                        /* PETICION AJAX PARA ENVIAR LOS DATOS AL SERVIDOR Y GUARDARLOS*/ 
                        $.ajax({
                            url: "modulos/factura_registrar/inscripcion.php",
                            dataType : "json",
                            type: 'POST',
                            data: {superdata : JSON.stringify(supremo)}
                        })
                        .done(function(response){
                            alertar(response.advertencia, response.mensaje);
                            setTimeout(function(){
                                ocultarAlerta(0.001);
                                window.open("modulos/pdf/facturas_inscripcion.php?if="+response.id_factura,"facturas.pdf","width=1100px, height=700px");
                                seccion_pagos(seccion_estudiantes);
                            },4999);
                        })
                        .fail(function(response){
                            alertar(3, response.responseText);
                            setTimeout(function(){
                                ocultarAlerta(0.001);
                                seccion_pagos(seccion_estudiantes);
                            },4999);
                        });
                        /* AQUI TERMINA LA PETICION AJAX */
                    }
                }else{
                    let id, valor, cancelado, abonado, diferencia, tp, ctp, rtp;
                    datos = {
                        "id_estudiante"    : id_estudiante,
                        "total_pagado"     : Texto_Decimal($("#cantidad_factura").val(),"0"),
                        "inscribir"        : inscribir
                    };
                    supremo.push(datos);

                    for(var i = 0; i < tam_check; i++){
                        id = $(checkElegidos[i]).attr("id");
                        valor = $(checkElegidos[i]).val();
                        cancelado = Texto_Decimal($("input[puntero='"+id+"'].cancelado").val(),"0");
                        abonado = Texto_Decimal($("input[puntero='"+id+"'].abonado").val(),"0");
                        diferencia = Texto_Decimal($("input[puntero='"+id+"'].diferencia").val(),"0");
                       
                        datos_check = {
                            "id_monto": id,
                            "cancelado" : cancelado,
                            "abonado" : abonado,
                            "diferencia" : diferencia
                        };
                        array_checks.push(datos_check);
                    }
                    supremo.push(array_checks);
                        
                    for(var i = 0; i < tam_tp; i++){
                        tp = $(tipos_pago[i]).val();
                        ctp = Texto_Decimal($(cantidades_tp[i]).val(),"0");
                        rtp = $(referencias[i]).val();
                        datos_tp = {
                            "id_tp": tp,
                            "referencia_tp" : rtp,
                            "cantidad_tp" : ctp
                        };
                        array_tp.push(datos_tp);
                    }
                    supremo.push(array_tp);
                    /* PETICION AJAX PARA ENVIAR LOS DATOS AL SERVIDOR Y GUARDARLOS*/
                    $.ajax({
                        url: "modulos/factura_registrar/inscripcion.php",
                        dataType : "json",
                        type: 'POST',
                        data: {superdata : JSON.stringify(supremo)}
                    })
                    .done(function(response){
                        alertar(response.advertencia, response.mensaje);
                        setTimeout(function(){
                            ocultarAlerta(0.001);
                            window.open("modulos/pdf/facturas_inscripcion.php?if="+response.id_factura,"facturas.pdf","width=1100px, height=700px");
                            seccion_pagos(seccion_estudiantes);
                        },4999);
                    })
                    .fail(function(response){
                        alertar(3, response.responseText);
                        setTimeout(function(){
                            ocultarAlerta(0.001);
                            seccion_pagos(seccion_estudiantes);
                        },4999);
                    });
                    /* AQUI TERMINA LA PETICION AJAX */
                }
            }
        }
    });
});