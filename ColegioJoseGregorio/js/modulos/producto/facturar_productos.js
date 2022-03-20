//************ TITULO DE LA SECCION
let titulo_facturacion_producto = "Facturacion de Productos - Venta";

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_facturacion_producto = `
    <div class="wh-completo pd-ssm">
        <div class="column-6-12 pd-ssm">
            <div class="r-h6">
                <div class="r-h2 pd-btigual mar-aba">
                    <div class="column-2-12"></div>
                    <div id="caja_busqueda_pro_fact" class="column-8-12">
                        <input type="text" id="busqueda_producto_factura" class="wh-completo" placeholder="Buscar Producto (Descripción o Id)">
                    </div>
                </div>
                <div class="r-h9m pd-ssm text-light fondo-gris-azulado borde-caja-moderado sombra-gris-caja-completa">
                    <div id="cajon_prductos_factura_busqueda" class="wh-completo scrolleable pd-ssm">
                        <!-- CAJON PARA CADA PRODUCTO EN BUSQUEDA-->
                        <div class="avisar_nada">
                            Realice una busqueda del producto a comprar por el cliente y agreguelo al Carro de Compras
                        </div>
                        <!-- HASTA AQUI CAJON PARA CADA PRODUCTO EN BUSQUEDA-->
                    </div>
                </div>
            </div>
            <div class="r-h6 pd-ssm">
                <div class="r-h10 pd-ssm">
                    <div class="r-h1m txt-bold-pq texto-centrado">
                        TIPO DE PAGO
                    </div>
                    <div id="contenedor_tipos_pago" class="r-h10m scrolleable pd-ssm bg-light-transparente sombra-gris-caja-completa">
                        <!--MODELO DE TIPO DE PAGO EN FACTURA PRODUCTOS-->
                        <div class="r-h6 bd-ab bd-negro">
                            <div class="column-11-12">
                                <div class="r-h6">
                                    <div id="caja_tp" numero="1" class="column-6-12 pd-ssm">
                                        <select class="select_tipo_pago wh-completo texto-centrado pd-null input-transparente-negro" numero="1">
                                            <option value="0">Tipo</option>
                                        </select>
                                    </div>
                                    <div id="caja_referencia" numero="1" class="column-6-12 pd-ssm">
                                        <input type="text" class="referencia_pago wh-completo texto-centrado input-transparente-negro" numero="1" placeholder="Nro Referencia">
                                    </div>
                                </div>
                                <div class="r-h6">
                                    <div class="column-3-12"></div>
                                    <div id="caja_cant-tp" numero="1" class="column-6-12 pd-ssm">
                                        <input type="text" class="cantidad_tp wh-completo texto-centrado input-transparente-negro" numero="1" placeholder="Cantidad" value="0,00">
                                    </div>
                                </div>
                            </div>
                            <div class="column-1-12">
                                <div class="r-h4"></div>
                                <div class="r-h5">
                                    <a href="#" id="mas_tipo_pago_producto" class="btn btn-primary txt-ppq pd-ssm"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                        <!--HASTA AQUI EL MODELO DE TIPO DE PAGO EN FACTURA PRODUCTOS-->
                    </div>
                </div>
                <div class="r-h2">
                    <div class="column-5-12"></div>
                    <div id="caja_btn_calcular" class="column-4-12">
                        <a href="#" id="boton_calcular_factura_producto" class="btn-pq btn-azul txt-light-pq"><i class="fas fa-calculator"></i> Abonar</a>
                    </div>
                </div>
            </div>                    
        </div>
        <div class="column-6-12 bg-light-transparente sombra-gris-caja-completa">
            <div class="r-h1 pd-btigual txt-light-men texto-centrado bd-2p bd-negro bd-ab">
                DETALLE DE VENTA
            </div>
            <div class="r-h2 pd-ssm bd-2p bd-negro bd-ab">
                <div class="r-h3">
                    <div class="column-3-12 txt-bold-spq">Datos del Cliente</div>
                    <div class="column-4-12"></div>
                    <div class="column-2-12 txt-bold-spq">ID Cliente:</div>
                    <div id="id_comprador" class="column-3-12 txt-arial-light-spq texto-subrayado"></div>
                </div>
                <div class="r-h3">
                    <div class="column-1-12"></div>
                    <div class="column-1m-12 txt-bold-spq">Cedula:</div>
                    <div id="cedula" class="column-9-12 txt-arial-light-spq texto-subrayado"></div>
                </div>
                <div class="r-h3">
                    <div class="column-1-12"></div>
                    <div class="column-1m-12 txt-bold-spq">Nombre:</div>
                    <div id="nombre" class="column-9-12 txt-arial-light-spq texto-subrayado"></div>
                </div>
                <div class="r-h3">
                    <div class="column-1-12"></div>
                    <div class="column-2-12 txt-bold-spq">Telf. o Correo:</div>
                    <div id="telefono" class="column-9-12 txt-arial-light-spq texto-subrayado"></div>
                </div>
            </div>
            <div class="r-h7 pd-ssm">
                <div class="r-h1 texto-centrado txt-bold-pq mar-aba">Carro de Compras</div>
                <div class="r-h11 bd-1p-bln sombra-gris-caja-completa fondo-gris-azulado">
                    <div class="r-h1m bd-1p bd-negro bd-ar bd-de bd-iz fondo-azul-blanco">
                        <div class="column-2-12 texto-centrado txt-bold-spq pd-btigual bd-1p bd-negro bd-de">Cant.</div>
                        <div class="column-6-12 texto-centrado txt-bold-spq pd-btigual bd-1p bd-negro bd-de">Descripción</div>
                        <div class="column-2m-12 texto-centrado txt-bold-spq pd-btigual bd-1p bd-negro bd-de">Total</div>
                        <div class="column-1m-12"></div>
                    </div>
                    <div id="productos_en_cesta" class="r-h10m bd-1p bd-negro bd-completo scrolleable fondo-blanco-transparente fila-int-azul-gris fila-hover-blan">
                        <!--SECCION DE LOS PRODUCTOS EN CESTA-->
                    </div>
                </div>
            </div>
            <div class="r-h2">
                <div class="column-6-12">
                    <div class="r-h3"></div>
                    <div class="column-2-12 r-hm"></div>
                    <div class="column-8-12 r-h6">
                        <a href="#" id="imprimir_factura_productos" class="btn-verde txt-bold-pq text-dark">
                            <i class="far fa-save"></i> Efectuar Venta <i class="fas fa-print"></i>
                        </a>
                    </div>
                </div>
                <div class="column-6-12">
                    <div class="r-h4">
                        <div class="column-5-12 txt-bold-pq pd-btigual texto-derecho">Abonado Bs.</div>
                        <div id="abonado" class="column-7-12 txt-arial-light-pq pd-btigual texto-subrayado texto-derecho"></div>
                    </div>
                    <div class="r-h4">
                        <div class="column-5-12 txt-bold-pq pd-btigual texto-derecho">Diferencia Bs.</div>
                        <div id="diferencia" class="column-7-12 txt-arial-light-pq pd-btigual texto-subrayado texto-derecho"></div>
                    </div>
                    <div class="r-h4">
                        <div class="column-5-12 txt-bold-pq pd-btigual texto-derecho">Total Bs.</div>
                        <div id="total" class="column-7-12 txt-arial-light-pq pd-btigual texto-subrayado texto-derecho"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
`;

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_facturacion_productos(cedula){
    formulario = "prod";
    window.document.title = 'Sistema. Facturación de Productos';    
    $("#titulo_seccion").html(titulo_facturacion_producto);
    $("#seccion_dinamica_principal").html(plantilla_facturacion_producto);

    obtenerDatos({opcion:"cliente_factura", comprador: cedula}, renderizarDatos, "modulos/obtenerDatos.php");

    $("input[numero='1'].cantidad_tp").addClass('cal-num');
    NumerosConDecimal(".cal-num");
    tipos_pago(".select_tipo_pago");
    soloNumeros("input[numero='1'].referencia_pago");
    soloNumeros(".cp");
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){
    $(document).on("click","#imprimir_factura_productos",function(e){
        e.preventDefault();
        if(cantidad_productos_cesta > 0 && form_prod_exitoso == 1){
            var supremo = [];
            var productos_cesta = [];
            var array_tp = [];
            var datos_tp = {};
            var datos_producto = {};
            
            let cantidades_tp = $(".cantidad_tp");
            let tipos_pago = $(".select_tipo_pago");
            let referencias = $(".referencia_pago");
            let tam_tp = tipos_pago.length;

            let datos = {
                "id_comprador" : parseInt($("#id_comprador").text()),
                "monto_total" : Texto_Decimal($("#total").text(), "0"),
                "diferencia" : Texto_Decimal($("#diferencia").text(),"0")
            };
            supremo.push(datos);

            let cant_prod = $(".deshacer_pro").length;
            for(var k = 0; k < cant_prod; k++){
                datos_producto = {
                    "id_producto": parseInt($($(".deshacer_pro")[k]).attr("id")),
                    "cantidad" : parseInt($($(".cant_pro")[k]).text())
                };
                productos_cesta.push(datos_producto);
            }
            supremo.push(productos_cesta);

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

            $.ajax({
                url: "modulos/factura_registrar/producto.php",
                dataType: "json",
                type: 'POST',
                data: {superdata : JSON.stringify(supremo)}
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    ocultarAlerta(0.001);
                    window.open("modulos/pdf/facturas_producto.php?cf="+response.codigo_factura,"factura_producto.pdf","width=1100px, height=700px");
                    main_home_productos(datos_usuario.id_usuario,datos_usuario.nombre_usuario, datos_usuario.logo_usuario, datos_usuario.ultima_sesion, datos_usuario.identificador_usuario);
                },2999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    main_home_productos(datos_usuario.id_usuario,datos_usuario.nombre_usuario, datos_usuario.logo_usuario, datos_usuario.ultima_sesion, datos_usuario.identificador_usuario);
                    ocultarAlerta(0.001);
                },2999);
            });
        }else{
            advertenciaEnfocada("#caja_busqueda_pro_fact","#busqueda_producto_factura","¡Realice Busquedas de Productos y agregue a la cesta, para poder calcular el monto!",1);
            quitarAdvertenciaBlur("#busqueda_producto_factura", "#caja_busqueda_pro_fact");
        }
    });

    $(document).on("keyup","#busqueda_producto_factura",function(){
        let busqueda = $(this).val();
        $.ajax({
            url: 'modulos/producto/busqueda_productos_factura.php',
            type: 'GET',
            dataType: 'json',
            data: {
                valor_busqueda: busqueda
            }
        })
        .done(function(response){
            let template = "";
            response.forEach(datos => {
                template += `
                    <div class="r-h4 bd-2p bd-completo bd-blanco mar-aba">
                        <div class="column-9-12">
                            <div id="descripcion_${datos.id_producto}" class="r-h4 txt-bold-spq bd-2p bd-blanco bd-de">
                                ${datos.descripcion}
                            </div>
                            <div class="r-h4 txt-bold-spq bd-2p bd-blanco bd-de">
                                <div class="column-2-12">Pecio Bs.</div>
                                <div id="precio_${datos.id_producto}" class="column-8-12">${Decimal_Texto(datos.precio)}</div>
                            </div>
                            <div class="r-h4 txt-bold-spq bd-2p bd-blanco bd-de">
                                <div class="column-3-12">
                                    En Existencia:
                                </div>
                                <div id="cant_exist_${datos.id_producto}" class="column-9-12">
                                    ${datos.cantidad}
                                </div>
                            </div>
                        </div>
                        <div class="column-1m-12 bd-2p bd-blanco bd-de">
                            <div class="r-h3 texto-centrado txt-bold-spq">Cant.</div>
                            <div class="r-h7 pd-btigual">
                                <div class="column-1-12"></div>
                                <div id="caja_cant_prod_${datos.id_producto}" class="column-10-12">
                                    <input type="text" id="cantidad_producto_${datos.id_producto}" class="cp wh-completo input-transparente-blanco">
                                </div>
                            </div>
                        </div>
                        <div class="column-1m-12 pd-btigual">
                            <div class="r-h2"></div>
                            <div class="column-2-12 r-hm"></div>
                            <a href="#" id="${datos.id_producto}" class="agregar_producto r-h8 column-8-12 txt-bold-men texto-naranja-claro texto-centrado">
                                <i class="fas fa-cart-plus"></i>
                            </a>
                        </div>
                    </div>
                `;
            });
            $("#cajon_prductos_factura_busqueda").html(template);
        })
        .fail(function(response){
            $("#cajon_prductos_factura_busqueda").html(`<div class="avisar_nada">${response.responseText}</div>`);
        });
    });

    $(document).on("click",".agregar_producto",function(e){
        e.preventDefault();
        form_prod_exitoso = 0;
        $("#abonado").html("");
        $("#diferencia").html("");

        let producto_listo = 0, producto_advertido = 0;
        let id_producto = parseInt($(this).attr('id'));
        let id_cant_prod = "#cantidad_producto_" + id_producto;
        let caja = "#caja_cant_prod_" + id_producto;
        let cantidad_existente = parseInt($("#cant_exist_" + id_producto).text());
        
        let prod_cesta = $(".deshacer_pro");
        if(prod_cesta != undefined){
            let id_producto_cesta, cantidad_producto_cesta, total_cant_prod;
            let tam_pro_ces = $(".deshacer_pro").length;
            let cantidad_pro, precio, total_producto;
            for(var j = 0; j < tam_pro_ces; j++){
                id_producto_cesta = parseInt($($(prod_cesta)[j]).attr('id'));
                if(id_producto_cesta == id_producto){
                    cantidad_producto_cesta = parseInt($($(prod_cesta)[j]).parent().parent().children('.cant_pro').text());
                    total_producto_cesta = Texto_Decimal($($(prod_cesta)[j]).parent().parent().children('.total_pro').text());
                    total_cant_prod = parseInt($(id_cant_prod).val()) + cantidad_producto_cesta;
                    if(total_cant_prod > cantidad_existente){
                        advertenciaEnfocada(caja, id_cant_prod,"¡Ingrese una Cantidad Menor o Igual a la Existente!",1);
                        quitarAdvertenciaBlur($(id_cant_prod), caja);
                        producto_advertido = 1;
                    }else{
                        $($(prod_cesta)[j]).parent().parent().children('.cant_pro').html(total_cant_prod);
                        cantidad_pro = parseInt($(id_cant_prod).val());
                        precio = Texto_Decimal($("#precio_" + id_producto).text());
                        total_producto = Decimal_Texto((cantidad_pro * precio) + total_producto_cesta);
                        $($(prod_cesta)[j]).parent().parent().children('.total_pro').html(total_producto);
                    }
                    producto_listo = 1;
                    break;
                }
            }
        }
        
        if(producto_listo == 0 && producto_advertido == 0){
            if(!CampoVacio(id_cant_prod) || $(id_cant_prod).val() == 0){
                advertenciaEnfocada(caja, id_cant_prod,"Ingrese la cantidad de productos a comprar ¡ES OBLIGATORIO!",1);
                quitarAdvertenciaBlur($(id_cant_prod), caja);
            }else if(parseInt($(id_cant_prod).val()) > cantidad_existente){
                advertenciaEnfocada(caja, id_cant_prod,"¡Ingrese una Cantidad Menor o Igual a la Existente!",1);
                quitarAdvertenciaBlur($(id_cant_prod), caja);
            }else{
                let cantidad_pro = parseInt($(id_cant_prod).val());
                let descripcion = $("#descripcion_" + id_producto).text();
                let precio = Texto_Decimal($("#precio_" + id_producto).text());
                let total_producto = cantidad_pro * precio;
                total_factura_producto += total_producto;
                let producto = `
                    <div class="r-h1m bd-1p bd-negro bd-ab">
                        <div class="cant_pro column-2-12 txt-arial-light-spq pd-btigual texto-centrado">
                            ${cantidad_pro}
                        </div>
                        <div class="desc_pro column-6-12 txt-bold-spq pd-btigual texto-centrado">
                            ${descripcion}
                        </div>
                        <div class="total_pro column-2m-12 txt-arial-light-spq pd-btigual texto-derecho">
                            ${Decimal_Texto(total_producto)}
                        </div>
                        <div class="column-1m-12">
                            <div class="r-h2"></div>
                            <div class="column-2-12 r-hm"></div>
                            <a href="#" id="${id_producto}" title="Deshacer Producto" class="deshacer_pro r-h8 column-8-12 txt-bold-spq texto_rojo texto-centrado">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                `;
                $("#productos_en_cesta").append(producto);
                $("#total").html(Decimal_Texto(total_factura_producto));
                                                                                                                                                                                                                                                                                                                                                                                   
                cantidad_productos_cesta++;
                $(id_cant_prod).val("");
                alertar(1, "¡AGREGADO!");
                setTimeout(function(){
                    ocultarAlerta(0.001);
                },999);
            }
        }else if(producto_listo == 1 && producto_advertido == 0){
            let cantidad_pro = parseInt($(id_cant_prod).val());
            let precio = Texto_Decimal($("#precio_" + id_producto).text());
            let total_producto = cantidad_pro * precio;
            total_factura_producto += total_producto;
            $("#total").html(Decimal_Texto(total_factura_producto));

            cantidad_productos_cesta++;
            $(id_cant_prod).val("");
            alertar(1, "¡AGREGADO!");
            setTimeout(function(){
                ocultarAlerta(0.001);
            },999);
        }
    });

    $(document).on("click", "#boton_calcular_factura_producto", function(e){
        if(cantidad_productos_cesta > 0){
            let cantidades_tp =  $(".cantidad_tp");
            let tipos_pago    =  $(".select_tipo_pago");
            let referencias   =  $(".referencia_pago");

            let tam = cantidades_tp.length;

            let sumatoria = 0, val_tipo_pago, val_cantidad, val_referencia, padre_tp, padre_rtp, padre_ctp, cantidad_validos = 0, advertido = 1;

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

            if(cantidad_validos === tam && sumatoria > 0){
                sumatoria = parseFloat(sumatoria);
                let diferencia = sumatoria - Texto_Decimal($("#total").text());
                if(diferencia < 0){
                    form_prod_exitoso = 0;
                }else{
                    form_prod_exitoso = 1;  
                }
                $("#abonado").html(Decimal_Texto(sumatoria));
                $("#diferencia").html(Decimal_Texto(diferencia));
            }else if(advertido == 0){
                advertenciaEnfocada("#caja_busqueda_pro_fact","#busqueda_producto_factura","¡Realice Busquedas de Productos y agregue a la cesta, para poder calcular el monto!",1);
                quitarAdvertenciaBlur("#busqueda_producto_factura", "#caja_busqueda_pro_fact");
            }
        }else{
            advertenciaEnfocada("#caja_busqueda_pro_fact","#busqueda_producto_factura","¡Realice Busquedas de Productos y agregue a la cesta, para poder calcular el monto!",1);
            quitarAdvertenciaBlur("#busqueda_producto_factura", "#caja_busqueda_pro_fact");
        }
    });

    $(document).on("click",".deshacer_pro", function(e){
        e.preventDefault();
        let total_producto = Texto_Decimal($($(this)[0]).parent().parent().children('.total_pro').text());
        let total_factura = Texto_Decimal($("#total").text()) - total_producto;
        $($(this)[0]).parent().parent().remove();
        cantidad_productos_cesta--;
        total_factura_producto -= total_producto;

        $("#total").html(Decimal_Texto(total_factura));
        if(Texto_Decimal($("#abonado").text()) > 0){
            $("#diferencia").html(Decimal_Texto(Texto_Decimal($("#abonado").text()) - total_factura));
        }
        
        alertar(3, "¡ELIMINADO!");
        setTimeout(function(){
            ocultarAlerta(0.001);
        },999);
    });
});