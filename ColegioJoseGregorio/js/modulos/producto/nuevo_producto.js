//************ TITULO DE LA SECCION
let titulo_nuevo_producto = "Resgistro de Producto al Almacen, " + fecha_actual;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_nuevo_producto = `
    <div class="r-h1"></div>
    <div class="column-3-12 r-hm"></div>
    <form class="column-6-12 r-h10 text-dark bg-light-transparente sombra-gris-caja-completa sombra-letra-blanca">
        <div class="r-h1 texto-centrado txt-bold-men">
            Nuevo Producto
        </div>

        <div class="r-h1 mar-arr pd-ssm txt-bold-spq pd-btigual-mitad">
            Nombre o Descripción del Producto. (Max 40 Caracteres)
        </div>
        <div class="r-h1 pd-ssm">
            <input type="text" id="nombre_producto" class="wh-completo input-transparente-negro">
        </div>

        <div class="r-h1 mar-arr">
            <div class="column-6-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Categoria
            </div>
            <div class="column-6-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Subcategoria
            </div>
        </div>
        <div class="r-h1">
            <div id="caja_cat_prod" class="column-6-12 pd-ssm">
                <select type="text" id="categoria_producto" class="wh-completo input-transparente-negro">
                    <option value="0"></option>
                </select>
            </div>
            <div id="caja_subcat_prod" class="column-6-12 pd-ssm">
                <select type="text" id="subcategoria_producto" class="wh-completo input-transparente-negro desactivar">
                    <option value="0"></option>
                </select>
            </div>
        </div>
                        
        <div class="r-h1 mar-arr">
            <div class="column-6-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Precio de Venta
            </div>
        </div>
        <div class="r-h1">
            <div id="caja_pv_prod" class="column-11-12 pd-ssm">
                <input type="text" id="precio_venta_producto" class="wh-completo input-transparente-negro" value="0,00">
            </div>
        </div>
                        
        <div class="r-h1 mar-arr">
            <div class="column-12-12 pd-ssm txt-bold-pq pd-btigual-mitad texto-centrado">
                Cantidad a Agregar en Almacen
            </div>
        </div>
        <div class="r-h1">
            <div class="column-3-12"></div>
            <div id="caja_cant_prod" class="column-6-12 pd-ssm">
                <input type="text" id="cantidad_producto" class="wh-completo input-transparente-negro">
            </div>
        </div>
                
        <div class="r-hm"></div>
        <div class="r-h1 mar-arr-2p">
            <div class="column-4-12"></div>
            <div class="column-5-12">
                <button type="submit" id="boton_registrar_producto" class="btn-azul btn-pq sombra-negra-caja-completa"><i class="far fa-save"></i> Guardar Producto</button>
            </div>
        </div>
    </form>
`;

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_nuevo_producto(){
    window.document.title = 'Sistema. Registro de Producto';    
    $("#titulo_seccion").html(titulo_nuevo_producto);
    $("#seccion_dinamica_principal").html(plantilla_nuevo_producto);

    $("#precio_venta_producto").addClass('cal-num');
    NumerosConDecimal(".cal-num");

    validacion_nuevo_producto();
    categorias_producto("#categoria_producto");
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){
    $(document).on("click","#nuevo_producto",main_nuevo_producto);

    $(document).on("click","#boton_registrar_producto",function(e){
        e.preventDefault();
      
        if(!LongitudCampo("#nombre_producto",8,40) || !CampoVacio("#nombre_producto")){
            advertenciaEnfocada("#caja_nom_prod","#nombre_producto","Introdúzca el Nombre del Producto. Maximo 40 caracteres, Minimo 8. ¡ES OBLIGATORIO!",1);
        }else if(!CampoVacio("#categoria_producto")){         
                advertenciaEnfocada("#caja_cat_prod","#categoria_producto","Elijá la Categoria ¡ES OBLIGATORIO!",1);
        }else if(!CampoVacio("#subcategoria_producto")){       
                advertenciaEnfocada("#caja_subcat_prod","#subcategoria_producto","Elijá la Subcategoria. ¡ES OBLIGATORIO!",1);       
        }else if(!CampoVacio("#precio_venta_producto") || $("#precio_venta_producto").val() == 0){
            advertenciaEnfocada("#caja_pv_prod","#precio_venta_producto","Ingrese el Precio de Venta ¡ES OBLIGATORIO!",1);       
        }else if(!CampoVacio("#cantidad_producto") || $("#cantidad_producto").val() == 0){
            advertenciaEnfocada("#caja_cant_prod","#cantidad_producto","Ingrese la Cantidad de Productos ha Almacenar ¡ES OBLIGATORIO!",1);       
        }else{
            $.ajax({
                url: "modulos/producto/producto_registrar.php",
                type: "POST",
                dataType: 'json',
                data: {
                    nombre : $("#nombre_producto").val(),
                    subcategoria : $("#subcategoria_producto").val(),
                    precio_venta : Texto_Decimal($("#precio_venta_producto").val()),
                    cantidad : parseInt($("#cantidad_producto").val())
                },
                beforeSend: function(){
                    $(".cargando").css('display', 'block');
                }
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    main_inventario();
                    ocultarAlerta(0.001);
                },1999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    main_home_productos(datos_usuario.id_usuario,datos_usuario.nombre_usuario, datos_usuario.logo_usuario, datos_usuario.ultima_sesion, datos_usuario.identificador_usuario);
                    ocultarAlerta(0.001);
                },9999);
            })
            .always(function(){
              $(".cargando").css('display', 'none');
            });
        }
    });
});


function validacion_nuevo_producto(){
    Validaciones(4,"#nombre_producto","#caja_nom_prod","Introdúzca el Nombre del Producto. Maximo 40 caracteres, Minimo 8. ¡ES OBLIGATORIO!","",8,40);
    Validaciones(1,"#categoria_producto","#caja_cat_prod","Elijá la Categoria ¡ES OBLIGATORIO!");
    Validaciones(1,"#subcategoria_producto","#caja_subcat_prod","Elijá la Subcategoria. ¡ES OBLIGATORIO!");
    soloNumeros("#cantidad_producto");
}