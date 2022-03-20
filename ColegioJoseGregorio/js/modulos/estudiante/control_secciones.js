//************ TITULO DE LA SECCION
let titulo_control_secciones = `Control de Secciones (Grado con Secciones).`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_control_secciones = `
	<div class="wh-completo pd-ssm">
        <div class="r-h1">

            <div class="column-3-12"></div>

            <div id="caja_grado" class="column-2-12 pd-ssm">
                <select id="grado_control" class="wh-completo">
                    <option value="0">Año/Grado</option>
                </select>
            </div>
            <div id="caja_seccion" class="column-1m-12 pd-ssm">
                <select id="seccion_control" class="wh-completo desactivar">
                    <option value="0">Sección</option>
                </select>
            </div>
            <div class="column-1m-12">
                <a href="#" id="btn_guardar_seccion_especifica" class="btn-verde desactivar"><i class="far fa-save"></i> Guardar</a>
            </div>

        </div>

        <div class="r-h1"></div>
        <div class="r-h1 column-1m-12"></div>
        
        <div class="r-h8 column-9-12 sombra-gris-caja-completa">
            <div class="r-h1m bg-dark text-light bd-ab bd-blanco bd-2p texto-centrado">
                <div class="h5 column-2-12 pd-btigual-mitad">Nro</div>
                <div class="h5 column-3-12 pd-btigual-mitad">Grado/Año</div>
                <div class="h5 column-3-12 pd-btigual-mitad">Sección</div>
                <div class="h5 column-3-12 pd-btigual-mitad">Fecha Registro</div>
                <div class="h5 column-1-12 pd-btigual-mitad"></div>
            </div>
            <div id="listado_secciones_especificas" class="r-h10m texto-centrado bg-light scrolleable fila-int-gris-azulado fila-hover-blan">
                
                <!-- AQUI VA EL CONTENIDO DE LA TABLA DE LAS SECCIONES
                REGISTRADAS -->
            </div>
        </div> 
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_control_secciones(){
    window.document.title = 'Sitema. Control Secciones';
	$("#titulo_seccion").html(titulo_control_secciones);
	$("#seccion_dinamica_principal").html(plantilla_control_secciones);
    grados_categorias("#grado_control");
    secciones("#seccion_control");
    listado_control_secciones();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#grados_seccion_modulo",function(){
		main_control_secciones();
	});

    $(document).on("change","#grado_control",function(){
        let valor = parseInt($(this).val());
        if(valor == 0){
            /*PARTE PARA DESACTIVAR Y REINICIAR UN SELECT*/
            $("#seccion_control").addClass('desactivar');
            setValueSelect("seccion_control", '0');
            /*HASTA AQUI*/
            $("#btn_guardar_seccion_especifica").addClass('desactivar');
        }else{
            $("#seccion_control").removeClass('desactivar');
        }
    });

    $(document).on('change', '#seccion_control', function() {
        if($(this).val() == 0){
            $("#btn_guardar_seccion_especifica").addClass('desactivar');
        }else{
            $("#btn_guardar_seccion_especifica").removeClass('desactivar');
        }
    });

    $(document).on("click","#btn_guardar_seccion_especifica",function(e){
        e.preventDefault();
        datos = {
            grado : $("#grado_control").val(),
            seccion : $("#seccion_control").val(),
            criterio : "registrar"
        };
        $.ajax({
            url: "modulos/control_secciones.php",
            type: "GET",
            dataType: 'json',
            data: datos
        })
        .done(function(response){
            alertar(response.advertencia, response.mensaje);
            setTimeout(function(){
                listado_control_secciones();
                ocultarAlerta(0.001);
            },4999);
        })
        .fail(function(response) {
            alertar(3, response.responseText);
            setTimeout(function(){
                listado_control_secciones();
                ocultarAlerta(0.001);
            },9999);
        });
    });// AQUI TERMINA EL EVENTO DEL BOTON DE GUARDADO

    $(document).on("click",".btn-eliminar-secciones",function(e){
        e.preventDefault();
        if(confirm('¿Esta Seguro de querer Eliminar a la sección? ¡Si elimina a la sección puede perder algunos datos que esten relacionados con ella, como por ejemplo los estudiantes registrados!')){
            let id_seccion = parseInt($($(this)[0]).parent().parent().children(".id_seccion").text());
            $.ajax({
                url: 'modulos/control_secciones.php',
                type: 'GET',
                dataType: "json",
                data: {
                    criterio : 'eliminar',
                    id_seccion
                }
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_control_secciones();
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_control_secciones();
                    ocultarAlerta(0.001);
                },4999);
            });
        }
    });
});


function listado_control_secciones(){
    $.ajax({
        async: false,
        url: 'modulos/control_secciones.php',
        type: 'GET',
        dataType: "json",
        data: {criterio : 'listado'}
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
        template += `
            <div class="r-h2 bd-ab bd-blanco">
                <div class="h5 id_seccion column-2-12 pd-btigual-mitad">${datos.id}</div>
                <div class="h5 column-3-12 pd-btigual-mitad texto-centrado">${datos.grado}</div>
                <div class="h5 column-3-12 pd-btigual-mitad texto-centrado">${datos.seccion}</div>
                <div class="h5 column-3-12 pd-btigual-mitad texto-centrado">${datos.fecha_registrado}</div>
                <div class="column-1-12 texto-centrado txt-bold-xl texto-rojo">
                    <a href="#" class="btn-eliminar-secciones btn-icon-rojo" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
                </div>
            </div>
            `
        });
        $("#listado_secciones_especificas").html(template);
    })
    .fail(function(response) {
        $("#listado_secciones_especificas").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}