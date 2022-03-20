//************ TITULO DE LA SECCION
let titulo_facturacion_normal = "Pago de Mensualidad del Estudiante. " + fecha_actual;
/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_facturacion_normal = `
	<div class="wh-completo pd-sm bd-1p-ngr">
        <!--MARGENES ARRIBA E IZQUIERDO-->
        <div class="r-h1 mar-aba">
            <div class="bd-ab bd-negro column-1m-12 pd-btigual-mitad txt-arial-light-pq">Mensualidad:</div>
            <div id="mensualidad_actual" class="bd-ab bd-negro column-2-12 pd-btigual-mitad txt-arial-bold-pq"></div>

            <div class="column-1-12"></div>

            <div class="bd-ab bd-negro column-2-12 pd-btigual-mitad txt-arial-light-pq">Porcentaje Mora:</div>
            <div id="mora_actual" class="bd-ab bd-negro column-1-12 pd-btigual-mitad txt-arial-bold-pq texto-centrado"></div>
            <div class="bd-ab bd-negro column-m-12 pd-btigual-mitad txt-arial-bold-pq">%</div>
        </div>
        <div class="column-m-12 r-h1"></div>
        <div class="r-h11 column-11-12 bg-dark texto-bln-sombra-ngr bd-1p-bln sombra-gris-caja-completa">
            <div class="r-h1 bd-1p-bln">
                <div class="column-m-12 pd-btigual-mitad txt-arial-light-pq">Id:</div>
                <div id="id_estudiante" class="column-1m-12 pd-btigual-mitad txt-arial-bold-pq"></div>

                <div class="column-m-12 txt-arial-light-pq pd-btigual-mitad">C.I:</div>
                <div id="cedula_estudiante" class="column-1m-12 pd-btigual-mitad txt-arial-bold-pq"></div>

                <div id="nombre_estudiante" class="column-4-12 pd-btigual-mitad txt-light-pq"></div>

                <div id="seccion_especifica_estudiante" class="column-2-12 pd-btigual-mitad texto-centrado txt-arial-bold-pq"></div>

                <div class="column-m-12"></div>
                <div id="periodo_escolar_estudiante" class="column-1m-12 pd-btigual-mitad texto-centrado txt-arial-bold-pq"></div>
            </div>
            <div class="r-h8">
                <div class="column-6-12">
                    <div class="r-h10 pd-ssm">
                        <div class="r-h1m bd-ab txt-arial-light-pq texto-centrado bd-blanco">
                            Tipo de Pago
                        </div>
                        <div id="contenedor_tipos_pago" class="r-h10m scrolleable">
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
                            <a href="#" id="boton_calcular_cantidad_mes" class="btn-azul txt-light-pq"><i class="fas fa-calculator"></i> Calcular</a>
                        </div>
                    </div>
                </div>

                <div class="column-6-12">
                    <div class="r-h1m txt-arial-bold-pq bd-ab bd-blanco texto-centrado">
                        <div class="column-1m-12"></div>
                        <div class="column-1m-12 pd-btigual-mitad bd-iz bd-de bd-blanco">Mes</div>
                        <div class="column-2m-12 pd-btigual-mitad bd-de bd-blanco">Cancelado</div>
                        <div class="column-2m-12 pd-btigual-mitad bd-de bd-blanco">Abonado</div>
                        <div class="column-2m-12 pd-btigual-mitad bd-de bd-blanco">Diferencia</div>
                        <div class="column-1m-12 pd-btigual-mitad bd-iz bd-de bd-blanco">Días</div>
                    </div>
                    <div id="contenedor_meses_factura" class="r-h10m bd-ab bd-iz bd-de bd-blanco">
                        
                    </div>
                </div>
            </div>

            <div class="r-h1">
                <div class="column-8-12"></div>
                <div class="column-1m-12 txt-light-pq texto-centrado pd-btigual">Monto Mora: </div>
                <div id="caja_monto_mora" class="column-2m-12 pd-ssm">
                    <input type="text" id="monto_mora" class="wh-completo texto-derecho desactivar" placeholder="Monto por Mora">
                </div>
            </div>

            <div class="r-h1">
                <div class="column-2-12"></div>
                <div class="column-6-12">
                    <a href="#" id="imprimir_factura_normal" class="btn-verde txt-light-pq mar-arr"><i class="far fa-save"></i> Guardar e <i class="fas fa-print"></i> Imprimir</a>
                </div>
                <div class="column-1m-12 txt-light-pq texto-centrado pd-btigual">Sub-Total: </div>
                <div id="caja_subtotal" class="column-2m-12 pd-ssm">
                    <input type="text" id="subtotal" class="wh-completo texto-derecho desactivar" placeholder="Sub-Total">
                </div>
            </div>

            <div class="r-h1">
                <div id="cajon_diferencia" class="column-8-12"></div>
                <div class="column-1m-12 txt-light-pq texto-centrado pd-btigual-mitad">Total a Pagar: </div>
                <div id="caja_cantidad_factura" class="column-2m-12 pd-ssm">
                    <input type="text" id="cantidad_factura" class="wh-completo texto-derecho desactivar" placeholder="Total de Factura">
                </div>
            </div>
        </div>
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_facturacion_normal(){
    formulario = "mens";
    numeros_tipo_pago = 2;
    window.document.title = 'Sistema. Factuarción de Mensualidad';
	$("#titulo_seccion").html(titulo_facturacion_normal);
	$("#seccion_dinamica_principal").html(plantilla_facturacion_normal);

    $("input[numero='1'].cantidad_tp").addClass('cal-num');
    NumerosConDecimal(".cal-num");
    
    soloNumeros("input[numero='1'].referencia_pago");
    soloNumeros(".dias_mora");
    tipos_pago(".select_tipo_pago");
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click",".cancelar_mensualidad",function(){
        var tipo_e = parseInt($($(this)[0]).attr("id"));
        var cedula_estudiante = $($(this)[0]).attr("ced");
        $(".close").trigger('click');
        $(".cargando").css('display', 'block');
        $.ajax({
            async: false,
            url: "modulos/basic/verificar_inscripcion.php",
            dataType : "json",
            type: 'POST',
            data: {
                cedula: cedula_estudiante,
                periodo_escolar: pee,
                tipo_estudiante : tipo_e
            }
        })
        .done(function(response){
            setTimeout(function(){
                main_facturacion_normal();
                obtenerDatos({opcion:"estudiante_factura", tipo_estudiante: tipo_e}, renderizarDatos, "modulos/obtenerDatos.php");
                pagos_estudiante_mensualidad(tipo_e, "#contenedor_meses_factura");
                $(".cargando").css('display', 'none');
            },1000);
        })
        .fail(function(response) {
            $(".close").trigger('click');
            $(".cargando").css('display', 'none');
            alertar(3, response.responseText);
            setTimeout(function(){
                ocultarAlerta(0.001);
            },5999);
        });
        /* AQUI TERMINA LA PETICION AJAX */
	});

    $(document).on("click","#boton_calcular_cantidad_mes",function(){
        change_form_fact = 0; //SE REINICIA LA VARIABLE QUE CONTROLA EL REINICIO
                            //DE LOS PAGOS PENDIENTES DEL ESTUDIANTE EN LA FACTURA.

        let tipo_estudiante = parseInt($("#id_estudiante").text());

        let cantidades_tp = $(".cantidad_tp");
        let tipos_pago = $(".select_tipo_pago");
        let referencias = $(".referencia_pago");
        let array_dias_mora = [];
        let datos_dias_mora = {};
        let dias_mora = $(".dias_mora");
        let tam_bucle = dias_mora.length;
        let tam = cantidades_tp.length;
        var sumatoria = 0, val_tipo_pago, val_cantidad, val_referencia, padre_tp, padre_rtp, padre_ctp, advertido = 1, sum_total = 0, valor_dm, padre_dm, mes_dia_mora;

        if(dias_mora.length != 0){
            for(var i = 0; i < tam_bucle; i++){
                valor_dm = parseInt($(dias_mora[i]).val());
                mes_dia_mora = parseInt($(dias_mora[i]).attr("puntero"));
                padre_dm = $(dias_mora[i]).parent().attr("numero");
                padre_dm = "div[id='caja_dias_mora'][numero='"+padre_dm+"']";

                if(!CampoVacio($(dias_mora[i])) || valor_dm == 0){
                    advertido = 1;
                    advertenciaEnfocada(padre_dm,$(dias_mora[i]),"Introdúzca la Cantidad de dias en Mora ¡ES OBLIGATORIO!",1);
                    quitarAdvertenciaBlur($(dias_mora[i]), padre_dm);
                    break;
                }else{
                    advertido = 0;
                    sum_total += valor_dm;
                    datos_dias_mora = {
                        "id_mes_mora" : mes_dia_mora,
                        "cantidad_dias" : valor_dm
                    }
                    array_dias_mora.push(datos_dias_mora);
                }
            }
        }else{
            console.log("No hay mora!");
            sum_total = 0;
        }
        
        if((advertido === 0 && sum_total > 0) || dias_mora.length === 0){
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
                    advertenciaEnfocada(padre_tp,$(tipos_pago[i]),"Elijá el Tipo de Pago ¡ES OBLIGATORIO!",1);
                    quitarAdvertenciaBlur($(tipos_pago[i]), padre_tp);
                    break;
                }else if(!CampoVacio($(referencias[i])) && (val_tipo_pago > 1 && val_tipo_pago < 5)) {
                    advertido = 1;
                    advertenciaEnfocada(padre_rtp, $(referencias[i]),"Digíte el número de referencia para el pago respectivo ¡ES OBLIGATORIO!",1);
                    quitarAdvertenciaBlur($(referencias[i]), padre_rtp);
                    break;
                }else if(!CampoVacio($(cantidades_tp[i])) || val_cantidad == 0){
                    advertido = 1;
                    advertenciaEnfocada(padre_ctp,$(cantidades_tp[i]),"¡Este Campo es Obligatorio!",1);
                    quitarAdvertenciaBlur($(cantidades_tp[i]), padre_ctp);
                    break;
                }else{
                    advertido = 0;
                    sumatoria+=val_cantidad;
                }
            }

            if(advertido === 0 && sumatoria > 0){
                if(dias_mora.length != 0){
                    pagos_estudiante_mensualidad(tipo_estudiante, "#contenedor_meses_factura", sumatoria, pee, array_dias_mora);
                }else{
                    pagos_estudiante_mensualidad(tipo_estudiante, "#contenedor_meses_factura", sumatoria, pee);
                } 
            }
        } 
    });

    $(document).on("click","#imprimir_factura_normal",function(e){
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
        let checkElegidos = $("input[type='checkbox']:checked");
        let tam_check = $("input[type='checkbox']:checked").length;
        if(tam_check == 0){
            advertenciaEnfocada("#caja_btn_calcular","#boton_calcular_cantidad_mes","No se puede Registrar la Factura debe Calcular el Monto a pagar");
            quitarAdvertenciaBlur("#boton_calcular_cantidad_mes", "#caja_btn_calcular");
        }else{
            let cantidades_tp = $(".cantidad_tp");
            let tipos_pago = $(".select_tipo_pago");
            let referencias = $(".referencia_pago");

            let tam_tp = tipos_pago.length;

            let id, valor, cancelado, abonado, diferencia, tp, ctp, rtp, diferencia_factura;
            id_estudiante = parseInt(id_estudiante);
            if($("#diferencia_factura") != undefined){
                diferencia_factura = Texto_Decimal($("#diferencia_factura").text(),"0");
            }else{
                diferencia_factura = 0;
            }
            datos = {
                "id_estudiante" : id_estudiante,
                "total_pagado"  : Texto_Decimal($("#cantidad_factura").val(),"0"),
                "subtotal": Texto_Decimal($("#subtotal").val(),"0"),
                "total_mora": Texto_Decimal($("#monto_mora").val(),"0"),
                "diferencia_factura" : diferencia_factura
            };
            supremo.push(datos);

            for(var i = 0; i < tam_check; i++){
                id = $(checkElegidos[i]).attr("id");
                valor = $(checkElegidos[i]).val();
                cancelado = $("input[puntero='"+id+"'].cancelado").val();
                abonado = $("input[puntero='"+id+"'].abonado").val();
                diferencia = $("input[puntero='"+id+"'].diferencia").val();
                dia_mora = $("input[puntero='"+id+"'].dias_mora").val() == undefined ? 0 : $("input[puntero='"+id+"'].dias_mora").val();
                datos_check = {
                    "id_mes": id,
                    "cancelado" : Texto_Decimal(cancelado,"0"),
                    "abonado" : Texto_Decimal(abonado,"0"),
                    "diferencia" : Texto_Decimal(diferencia,"0"),
                    "dias_mora" : dia_mora
                };
                array_checks.push(datos_check);
            }
            supremo.push(array_checks);
                    
            for(var i = 0; i < tam_tp; i++){
                tp = $(tipos_pago[i]).val();
                ctp = $(cantidades_tp[i]).val();
                rtp = $(referencias[i]).val();
                datos_tp = {
                    "id_tp": tp,
                    "referencia_tp" : rtp,
                    "cantidad_tp" : Texto_Decimal(ctp,"0")
                };
                array_tp.push(datos_tp);
            }
            supremo.push(array_tp);
            
            /* PETICION AJAX PARA ENVIAR LOS DATOS AL SERVIDOR Y GUARDARLOS*/
            $.ajax({
                url: "modulos/factura_registrar/mensualidad.php",
                dataType : "json",
                type: 'POST',
                data: {superdata : JSON.stringify(supremo)}
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    ocultarAlerta(0.001);
                    window.open("modulos/pdf/facturas_mensualidad.php?if="+response.id_factura,"facturas.pdf","width=1100px, height=700px");
                    seccion_pagos(seccion_estudiantes);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    ocultarAlerta(0.001);
                    seccion_pagos(seccion_estudiantes);
                },4999);
            });
            /* AQUI TERMINA LA PETICION AJAX*/
        }
    });
});