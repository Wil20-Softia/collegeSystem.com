let titulo_control_inventario_producto = `Inventario de Productos.`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_control_inventario_producto = `
	<div class="wh-completo">
        <div class="r-h1 pd-btigual">
            <div class="column-3-12"></div>
            <div class="column-6-12">
                <div class="column-1-12">
                    <input type="radio" name="productos_inventario_opciones" id="opcion_todos_producto" class="productos_inventario_opciones option-input-completo radio" value="1">
                </div>
                <label class="column-1-12 txt-bold-pq" for="opcion_todos_producto">
                    Todos
                </label>

                <div class="column-2-12"></div>

                <div class="column-1-12">
                    <input type="radio" name="productos_inventario_opciones" id="opcion_busqueda_normal_producto" class="productos_inventario_opciones option-input-completo radio" value="2">
                </div>
                <label class="column-1m-12 txt-bold-pq" for="opcion_busqueda_normal_producto">
                    Busqueda
                </label>
                        
                <div class="column-1m-12"></div>

                <div class="column-1-12">
                    <input type="radio" name="productos_inventario_opciones" id="opcion_busqueda_subcategoria_producto" class="productos_inventario_opciones option-input-completo radio" value="3">
                </div>
                <label class="column-2-12 txt-bold-pq" for="opcion_busqueda_subcategoria_producto">
                    Subcategoria
                </label>
            </div>
            <div class="column-3-12">
                <div class="column-6-12 txt-bold-spq pd-btigual-mitad texto-derecho">Dinero en Stock Bs.</div>
                <div id="dinero_stock" class="column-6-12 txt-arial-bold-spq pd-btigual-mitad texto-centrado"></div>
            </div>
        </div>
        <div class="r-h1">
            <div class="column-2-12 pd-btigual">
                <a href="#" title="Obtener Reporte del Inventario en PDF" id="reporte_inventario_producto" class="btn-rojo izquierda txt-light-men mar-arr">
                    <i class="fas fa-file-pdf"></i> Generar Reporte PDF
                </a>
            </div>
            <div id="opciones_busqueda_inventario" class="column-7-12">
                
            </div>
            <div class="column-3-12">
                <div class="column-6-12 txt-bold-spq pd-btigual-mitad texto-derecho">Productos en Stock</div>
                <div id="productos_stock" class="column-6-12 txt-arial-bold-spq pd-btigual-mitad texto-centrado"></div>
            </div>
        </div>
        <div class="r-h10 pd-sm">
            <div class="wh-completo bd-1p-bln sombra-gris-caja-completa fondo-gris-azulado">
                <div class="r-h1 fondo-azul-blanco">
                    <div class="column-1-12 texto-centrado txt-bold-pq bd-2p pd-btigual  bd-blanco">Id</div>
                    <div class="column-4-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Nombre</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">En Existencia</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Precio Venta</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz bd-de  bd-blanco">Subcategoria</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq pd-btigual">Ultimo Abast.</div>
                </div>
                <div id="listado_productos_inventario" class="r-h11 scrolleable fondo-blanco-transparente fila-int-azul-gris fila-hover-blan">
                                  
                </div>
            </div>
        </div> 
    </div>
`

var seccion_busqueda_subcategoria = `
    <div class="column-2-12"></div>
    <div class="column-8-12">
        <div id="caja_cat_prod" class="column-6-12 pd-ssm">
            <select type="text" id="categoria_producto" class="wh-completo categoria_busqueda_real">
                <option value="0">Categoria</option>
            </select>
        </div>
        <div id="caja_subcat_prod" class="column-6-12 pd-ssm">
            <select type="text" id="subcategoria_producto" class="subcategoria_busqueda_real wh-completo desactivar">
                <option value="0">Subcategoria</option>
            </select>
        </div>
    </div>
`;

var seccion_busqueda_normal = `
    <div class="column-3-12"></div>
    <div class="column-6-12 pd-btigual">
        <input type="text" id="busqueda_productos_inventario" class="wh-completo busqueda" placeholder="Nombre del Producto"/>
    </div>
`;

function main_inventario(){
    window.document.title = 'Sistema. Inventario de Productos';
    $("#titulo_seccion").html(titulo_control_inventario_producto);
    $("#seccion_dinamica_principal").html(plantilla_control_inventario_producto);

    listado_productos_inventario();
    $('#opcion_todos_producto').prop('checked',true);
}

$(document).ready(function() {
    $(document).on("click","#inventario_producto", main_inventario);

	$(document).on("click","#reporte_inventario_producto",function(e){
        e.preventDefault();
        let radio_seleccionado = parseInt(obtener_valor_radio(".productos_inventario_opciones"));
        switch(radio_seleccionado){
            case 1:
                window.open("modulos/pdf/reporte_inventario.php?c=todos","reporte_inventario.pdf","width=1100px, height=700px");
                break;
            case 3:
                let subcat = parseInt($("#subcategoria_producto").val());
                if(subcat != 0){
                    window.open(`modulos/pdf/reporte_inventario.php?c=subcategoria&v=${subcat}`,"reporte_inventario.pdf","width=1100px, height=700px");
                }else{
                    alertar(3, "¡SELECCIONE UNA SUBCATEGORIA PARA REALIZAR EL REPORTE!");
                    setTimeout(function(){
                        ocultarAlerta(0.001);
                    },3999); 
                }
                break;
            default:
                alertar(3, "¡NO PUEDE GENERAR REPORTE CON LA OPCIÓN SELECCIONADA!");
                setTimeout(function(){
                    ocultarAlerta(0.001);
                },3999);
        }
    });

    $(document).on("click",".productos_inventario_opciones",function(){
        quitar_advertencia("#opciones_busqueda_inventario");
        let valor = parseInt(obtener_valor_radio(".productos_inventario_opciones"));
        switch(valor){
            case 2:
                $("#opciones_busqueda_inventario").html(seccion_busqueda_normal);
                DesactivarBoton("#reporte_inventario_producto");
                break;
            case 3:
                $("#opciones_busqueda_inventario").html(seccion_busqueda_subcategoria);
                categorias_producto("#categoria_producto");
                ActivarBoton("#reporte_inventario_producto");
                break;
            default:
                $("#opciones_busqueda_inventario").html("");
                listado_productos_inventario();
                ActivarBoton("#reporte_inventario_producto");
        }
    });

    $(document).on("keyup","#busqueda_productos_inventario",function(e){
        let valor = $(this).val();
        if(valor == ""){
            listado_productos_inventario();
        }else{
            listado_productos_inventario('busqueda', valor);
        }
    });

    $(document).on("change",".subcategoria_busqueda_real",function(e){
        let valor = parseInt($(this).val());
        if(valor == 0){
            listado_productos_inventario();
        }else{
            listado_productos_inventario('subcategoria', "", valor);
        }
    });

    $(document).on("change",".categoria_busqueda_real",function(e){
        let valor = parseInt($(this).val());
        if(valor == 0){
            listado_productos_inventario();
        }
    });

    $(document).on('click', '.agregar_a_inventario', function(event) {
        event.preventDefault();
        let id_producto = parseInt($($(this)[0]).attr("id"));
        $("#formularios_productos_"+id_producto).html(`
            <div class="r-h1m">
                <div class="column-3-12 bd-1p bd-blanco bd-completo"></div>
                <div class="column-6-12 pd-btigual">
                    <input type="text" id="cantidad_a_agregar_inventario_${id_producto}" class="wh-completo" placeholder="Cantidad a Agregar">
                </div>
            </div>
            <div class="r-h1m bd-1p bd-blanco bd-completo">
                <div class="column-3-12 bd-1p bd-blanco bd-completo"></div>
                <div class="column-6-12">
                    <button type="submit" id_producto="${id_producto}" class="boton_agregar_a_inventario btn btn-success txt-bold-spq">Agregar</button>
                </div>
            </div>
        `);
        $("#nombre_opciones_producto_"+id_producto).html("Agregar Productos al Inventario.");
        soloNumeros("#cantidad_a_agregar_inventario_"+id_producto);
    });

    $(document).on("click",".boton_agregar_a_inventario", function(e){
        e.preventDefault();
        var id_producto = parseInt($($(this)[0]).attr("id_producto"));
        let cantidad_agregar = parseInt($("#cantidad_a_agregar_inventario_"+id_producto).val());
        $.ajax({
            async: false,
            url: 'modulos/producto/control_producto_inventario.php',
            type: 'GET',
            dataType: "json",
            data: {
                criterio : "agregar_productos",
                id_producto : id_producto,
                cantidad_agregar : cantidad_agregar
            }
        })
        .done(function(response) {
            alertar(1, response.mensaje);
            setTimeout(function(){
                ocultarAlerta(0.001);
                listado_productos_inventario();
            },2999);
        })
        .fail(function(response) {
            alertar(3, response.responseText);
            setTimeout(function(){
                ocultarAlerta(0.001);
            },2999);
        })
        .always(function(){
            $(".close").trigger('click');
        });
    });

    $(document).on('click', '.actualizar_precio_producto', function(event) {
        event.preventDefault();
        let id_producto = parseInt($($(this)[0]).attr("id"));
        $("#formularios_productos_"+id_producto).html(`
            <div class="r-h1m">
                <div class="column-3-12 bd-1p bd-blanco bd-completo"></div>
                <div class="column-6-12 pd-btigual">
                    <input type="text" id="precio_nuevo_venta_producto_${id_producto}" class="wh-completo" value="0,00">
                </div>
            </div>
            <div class="r-h1m bd-1p bd-blanco bd-completo">
                <div class="column-3-12 bd-1p bd-blanco bd-completo"></div>
                <div class="column-6-12">
                    <button type="submit" id_producto="${id_producto}" class="boton_actualizar_precion_venta btn btn-success txt-bold-spq">Cambiar</button>
                </div>
            </div>
        `);
        $("#nombre_opciones_producto_"+id_producto).html("Actualización de Precio.");
        $("#precio_nuevo_venta_producto_"+id_producto).addClass('cal-num');
        NumerosConDecimal(".cal-num");
    });

    $(document).on("click",".boton_actualizar_precion_venta", function(e){
        e.preventDefault();
        var id_producto = parseInt($($(this)[0]).attr("id_producto"));
        let precio_venta = Texto_Decimal($("#precio_nuevo_venta_producto_"+id_producto).val(),"0");
        $.ajax({
            async: false,
            url: 'modulos/producto/control_producto_inventario.php',
            type: 'GET',
            dataType: "json",
            data: {
                criterio : "actualizar_precio",
                id_producto : id_producto,
                precio_venta : precio_venta
            }
        })
        .done(function(response) {
            alertar(1, response.mensaje);
            setTimeout(function(){
                ocultarAlerta(0.001);
                listado_productos_inventario();
            },2999);
        })
        .fail(function(response) {
            alertar(3, response.responseText);
            setTimeout(function(){
                ocultarAlerta(0.001);
            },2999);
        })
        .always(function(){
            $(".close").trigger('click');
        });
    });

    $(document).on('click', '.modificar_nombre_producto', function(event) {
        event.preventDefault();
        let id_producto = parseInt($($(this)[0]).attr("id"));
        $("#formularios_productos_"+id_producto).html(`
            <div class="r-h1m">
                <div class="column-3-12 bd-1p bd-blanco bd-completo"></div>
                <div class="column-6-12 pd-btigual">
                    <input type="text" id="nombre_modificado_producto_${id_producto}" class="wh-completo" placeholder="Nuevo Nombre">
                </div>
            </div>
            <div class="r-h1m bd-1p bd-blanco bd-completo">
                <div class="column-3-12 bd-1p bd-blanco bd-completo"></div>
                <div class="column-6-12">
                    <button type="submit" id_producto="${id_producto}" class="boton_actualizar_nombre_producto btn btn-success txt-bold-spq">Actualizar</button>
                </div>
            </div>
        `);
        $("#nombre_opciones_producto_"+id_producto).html("Modificación del Nombre.");
    });

    $(document).on("click",".boton_actualizar_nombre_producto", function(e){
        e.preventDefault();
        var id_producto = parseInt($($(this)[0]).attr("id_producto"));
        let nombre = $("#nombre_modificado_producto_"+id_producto).val();
        $.ajax({
            async: false,
            url: 'modulos/producto/control_producto_inventario.php',
            type: 'GET',
            dataType: "json",
            data: {
                criterio : "modificar",
                id_producto : id_producto,
                nombre : nombre
            }
        })
        .done(function(response) {
            alertar(1, response.mensaje);
            setTimeout(function(){
                ocultarAlerta(0.001);
                listado_productos_inventario();
            },2999);
        })
        .fail(function(response) {
            alertar(3, response.responseText);
            setTimeout(function(){
                ocultarAlerta(0.001);
            },2999);
        })
        .always(function(){
            $(".close").trigger('click');
        });
    });

    $(document).on("click",".eliminar_producto", function(e){
        e.preventDefault();
        let id_producto = parseInt($($(this)[0]).attr("id"));
        $.ajax({
            async: false,
            url: 'modulos/producto/control_producto_inventario.php',
            type: 'GET',
            dataType: "json",
            data: {
                criterio : "eliminar",
                id_producto : id_producto
            }
        })
        .done(function(response) {
            alertar(1, response.mensaje);
            setTimeout(function(){
                ocultarAlerta(0.001);
                listado_productos_inventario();
            },2999);
        })
        .fail(function(response) {
            alertar(3, response.responseText);
            setTimeout(function(){
                ocultarAlerta(0.001);
            },2999);
        })
        .always(function(){
            $(".close").trigger('click');
        });
    });

    $(document).on('click', '.atras_modal_producto', function(event){
        event.preventDefault();
        let id_producto = parseInt($($(this)[0]).attr("id"));
        $("#formularios_productos_"+id_producto).html(`
            <div class="r-h1m mar-aba">
                <div class="column-4-12 bd-1p bd-completo bd-blanco"></div>
                <div class="column-4-12">
                    <a id="${id_producto}" href="#" class="btn btn-primary txt-bold-spq agregar_a_inventario">
                        Agregar Productos
                    </a>
                </div>
            </div>
            <div class="r-h1m mar-aba">
                <div class="column-4-12 bd-1p bd-completo bd-blanco"></div>
                <div class="column-4-12">
                    <a id="${id_producto}" href="#" class="btn btn-secondary txt-bold-spq actualizar_precio_producto">
                        Actualizar Precio
                    </a>
                </div>
            </div>
            <div class="r-h1m">
                <div class="column-4-12">
                    <a id="${id_producto}" href="#" class="btn btn-warning txt-bold-spq modificar_nombre_producto">
                        Modificar Nombre
                    </a>
                </div>
                <div class="column-4-12 text-light">SEPARACION</div>
                <div class="column-4-12">
                    <a id="${id_producto}" href="#" class="btn btn-danger txt-bold-spq">
                        Eliminar Producto
                    </a>
                </div>
            </div>
        `);
        $("#nombre_opciones_producto_"+id_producto).html("Opciones para el Producto.");
    });
});

function listado_productos_inventario(criterio = 'listado_completo', filtro = '', subcategoria = 0){
    let datos = {};
    if(criterio == 'subcategoria'){
        datos = {
            criterio : criterio,
            subcategoria : subcategoria
        };
    }else if(criterio == 'busqueda'){
        datos = {
            criterio : criterio,
            busqueda : filtro
        };
    }else if(criterio == 'listado_completo'){
        datos = {
            criterio : criterio
        };
    }
    $.ajax({
        async: false,
        url: 'modulos/producto/listado_productos_inventario.php',
        type: 'GET',
        dataType: "json",
        data: datos
    })
    .done(function(response){
        let template = "";
        response[0].forEach(datos => {
            template += `
                <div class="r-h1m">
                    <div class="column-1-12 texto-centrado pd-btigual-mitad scrolleable txt-arial-bold-spq">
                        ${datos.id}
                    </div>
                    <div class="column-4-12 pd-btigual-mitad texto-centrado scrolleable">
                        <a href="#" class="texto-subrayado text-dark txt-bold-spq" title="Agregar, Actualizar precio, Modificar Nombre, o Eliminar Producto" data-toggle="modal" data-target="#producto_${datos.id}">
                            ${datos.nombre}
                        </a>
                        <!-- The Modal -->
                        <div class="modal fade text-dark" id="producto_${datos.id}">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <button type="button" id="${datos.id}" class="atras_modal_producto btn-pq btn-naranja">Atras</button>
                                        <strong id="nombre_opciones_producto_${datos.id}" class="column-10-12 texto-centrado txt-mf20">Opciones para el Producto.</strong>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div> 
                                    <!-- Modal body -->
                                    <div id="formularios_productos_${datos.id}" class="modal-body">
                                        <div class="r-h1m mar-aba">
                                            <div class="column-4-12 bd-1p bd-completo bd-blanco"></div>
                                            <div class="column-4-12">
                                                <a id="${datos.id}" href="#" class="btn btn-primary txt-bold-spq agregar_a_inventario">
                                                    Agregar Productos
                                                </a>
                                            </div>
                                        </div>
                                        <div class="r-h1m mar-aba">
                                            <div class="column-4-12 bd-1p bd-completo bd-blanco"></div>
                                            <div class="column-4-12">
                                                <a id="${datos.id}" href="#" class="btn btn-secondary txt-bold-spq actualizar_precio_producto">
                                                    Actualizar Precio
                                                </a>
                                            </div>
                                        </div>
                                        <div class="r-h1m">
                                            <div class="column-4-12">
                                                <a id="${datos.id}" href="#" class="btn btn-warning txt-bold-spq modificar_nombre_producto">
                                                    Modificar Nombre
                                                </a>
                                            </div>
                                            <div class="column-4-12 text-light">SEPARACION</div>
                                            <div class="column-4-12">
                                                <a id="${datos.id}" href="#" class="btn btn-danger txt-bold-spq eliminar_producto">
                                                    Eliminar Producto
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
                        ${datos.cantidad_existente}
                    </div>
                    <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
                        ${Decimal_Texto(datos.precio_venta)}
                    </div>
                    <div class="column-2-12 texto-centrado txt-bold-spq pd-btigual-mitad scrolleable">
                        ${datos.nombre_subcategoria}
                    </div>
                    <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
                        ${datos.ultimo_abastecimiento}
                    </div>
                </div>`;
        });
        $("#listado_productos_inventario").html(template);
        $("#productos_stock").html(response[1].productos_stock);
        $("#dinero_stock").html(Decimal_Texto(response[1].dinero_stock));
    })
    .fail(function(response) {
        $("#listado_productos_inventario").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}