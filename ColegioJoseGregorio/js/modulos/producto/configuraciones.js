//************ TITULO DE LA SECCION
var codigo_cat = 0;
var codigo_subcat = 0;
let titulo_configuraciones_producto = `Configuraciones. Categorias y Subcategorias.`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_configuraciones_producto = `
	<div class="column-6-12">
                <div class="r-h1 txt-bold-men texto-centrado pd-btigual">
                    Categorias de Productos
                </div>
                <div class="r-h9 pd-ssm">
                    <div class="wh-completo">
                        <div class="r-h1m fondo-verde-ocuro text-light bd-3p bd-negro bd-ab">
                            <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">Nombre</div>
                            <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">Subcategorias</div>
                            <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">Cant. Productos</div>
                        </div>
                        <div id="listado_categorias" class="r-h10m scrolleable bg-light-transparente fila-int-grisclaro-blan fila-hover-gris">
                            
                        </div>
                    </div>
                </div>
                <div class="r-h2">
                    <div class="r-h3"></div>
                    <div class="r-h6">
                        <div class="column-2-12"></div>
                        <div id="caja_nom_cat" class="column-4-12 pd-ssm">
                            <input id="nombre_categoria" type="text" class="wh-completo" placeholder="Nombre">
                        </div>
                        <div class="column-3-12 pd-btigual">
                            <button id="btn_registrar_categoria" class="btn btn-primary txt-bold-spq"><i class="far fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column-6-12">
                <div class="r-h1 txt-bold-men texto-centrado pd-btigual">
                    Subcategorias de Productos
                </div>
                <div class="r-h9 pd-ssm">
                     <div class="wh-completo">
                         <div class="r-h1m fondo-verde-ocuro text-light bd-3p bd-negro bd-ab">
                             <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                                 Nombre
                             </div>
                             <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                                 Cant. Productos
                             </div>
                             <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                                 Categoria
                             </div>
                         </div>
                         <div id="listado_subcategorias" class="r-h10m scrolleable bg-light-transparente fila-int-grisclaro-blan fila-hover-gris">
                            
                         </div>
                     </div>
                </div>
                <div class="r-h2">
                    <div class="r-h3"></div>
                    <div class="r-h6">
                        <div class="column-m-12"></div>
                        <div id="caja_cat_subcat" class="column-4-12 pd-ssm">
                            <select id="opciones_categorias" class="wh-completo">
                                <option value="0">Categoria</option>
                            </select>
                        </div>
                        <div id="caja_nom_subcat" class="column-4-12 pd-ssm">
                            <input id="nombre_subcategoria" type="text" class="wh-completo" placeholder="Nombre">
                        </div>
                        <div class="column-3-12 pd-btigual">
                            <button id="btn_registrar_subcategoria" class="btn btn-primary txt-bold-spq"><i class="far fa-save"></i> Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_configuraciones_producto(){
    window.document.title = 'Sistema. Configuraciones Producto';
	$("#titulo_seccion").html(titulo_configuraciones_producto);
	$("#seccion_dinamica_principal").html(plantilla_configuraciones_producto);
    
    listado_categorias_productos();
    listado_subcategorias_productos();
    categorias_producto("#opciones_categorias");
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#configuraciones_producto",main_configuraciones_producto);

    $(document).on("click",".modificar_categoria",function(){
        modificar_cat = 1;
        codigo_cat = $($(this)[0]).attr("id");
        let descripcion =  $($(this)[0]).text();

        $("#nombre_categoria").val(descripcion);
    });

    $(document).on("click",".modificar_subcategoria",function(){
        modificar_subcat = 1;
        codigo_subcat = $($(this)[0]).attr("id");

        obtenerDatos({opcion:"subcategoria_formulario", codigo: codigo_subcat}, renderizarDatos, "modulos/obtenerDatos.php");

        $("#opciones_categorias").addClass('desactivar');
    });
    
    $(document).on("click","#btn_registrar_categoria",function(){
        if(!LongitudCampo("#nombre_categoria",4,20) || !CampoVacio("#nombre_categoria")){
            advertenciaEnfocada("#caja_nom_cat","#nombre_categoria","Ingrese el Nombre de la Categoria ¡ES OBLIGATORIO!",1);
        }else{
            if(modificar_cat === 1){
                datos = {
                    codigo : codigo_cat,
                    descripcion : $("#nombre_categoria").val(),
                    criterio : "modificar"
                };
            }else{
                datos = {
                    criterio : "registrar",
                    descripcion : $("#nombre_categoria").val()
                };
            }
            
            $.ajax({
                url: 'modulos/producto/categorias.php',
                type: 'GET',
                dataType: "json",
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_categorias_productos();
                    categorias_producto("#opciones_categorias");
                    $("#nombre_categoria").val("");
                    modificar_cat = 0;
                    codigo_cat = 0;
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_categorias_productos();
                    categorias_producto("#opciones_categorias");
                    $("#nombre_categoria").val("");
                    modificar_cat = 0;
                    codigo_cat = 0;
                    ocultarAlerta(0.001);
                },4999);
            });  
        }
    });
    
    $(document).on("click","#btn_registrar_subcategoria",function(){
        if(!CampoVacio("#opciones_categorias")){
            advertenciaEnfocada("#caja_cat_subcat","#opciones_categorias","¡Elija la Categoria a la que pertenece la Subcategoria!",1);
        }else if(!LongitudCampo("#nombre_subcategoria",4,20) || !CampoVacio("#nombre_subcategoria")){
            advertenciaEnfocada("#caja_nom_subcat","#nombre_subcategoria","Ingrese el Nombre de la Subcategoria ¡ES OBLIGATORIO!",1);
        }else{
            if(modificar_subcat === 1){
                datos = {
                    codigo : codigo_subcat,
                    descripcion : $("#nombre_subcategoria").val(),
                    criterio : "modificar"
                };
            }else{
                datos = {
                    criterio : "registrar",
                    categoria_subcategoria : $("#opciones_categorias").val(),
                    descripcion : $("#nombre_subcategoria").val()
                };
            }

            $.ajax({
                url: 'modulos/producto/subcategorias.php',
                type: 'GET',
                dataType: "json",
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_subcategorias_productos();
                    listado_categorias_productos();
                    setValueSelect("opciones_categorias", 0);
                    $("#nombre_subcategoria").val("");
                    modificar_subcat = 0;
                    codigo_subcat = 0;
                    $("#opciones_categorias").removeClass('desactivar');
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_subcategorias_productos();
                    setValueSelect("opciones_categorias", 0);
                    $("#nombre_subcategoria").val("");
                    modificar_subcat = 0;
                    codigo_subcat = 0;
                    $("#opciones_categorias").removeClass('desactivar');
                    ocultarAlerta(0.001);
                },4999);
            });  
        }
    });
});

function listado_categorias_productos(){
    $.ajax({
        async: false,
        url: 'modulos/producto/categorias.php',
        type: 'GET',
        dataType: "json",
        data: {criterio : 'listado'}
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
            template += `
                <div class="r-h1m">
                    <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                        <a href="#" id="${datos.id}" class="modificar_categoria texto-subrayado texto-centrado text-danger" title="Modificar Nombre de la Categoria">
                               ${datos.nombre}
                        </a>
                    </div>
                    <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                            ${datos.cant_subcategorias}
                    </div>
                    <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                            ${datos.cant_productos}
                    </div>
                </div>`;
        });
        $("#listado_categorias").html(template);
    })
    .fail(function(response) {
        $("#listado_categorias").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}

function listado_subcategorias_productos(){
    $.ajax({
        async: false,
        url: 'modulos/producto/subcategorias.php',
        type: 'GET',
        dataType: "json",
        data: {criterio : 'listado'}
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
            template += `
                <div class="r-h1m">
                    <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                        <a href="#" id="${datos.id}" class="modificar_subcategoria texto-subrayado texto-centrado text-danger" title="Modificar Nombre de la Subcategoria">
                               ${datos.nombre}
                        </a>
                    </div>
                    <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                            ${datos.cant_productos}
                    </div>
                    <div class="column-4-12 pd-btigual-mitad txt-bold-pq texto-centrado">
                            ${datos.categoria}
                    </div>
                </div>`;
        });
        $("#listado_subcategorias").html(template);
    })
    .fail(function(response) {
        $("#listado_subcategorias").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}