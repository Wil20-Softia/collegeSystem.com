//************ TITULO DE LA SECCION
let titulo_mensualidades_mora = `Panel de Control de Mensualidad y Mora.`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_mensualidades_mora = `
	<div class="wh-completo pd-ssm">
        <div class="r-h12 bg-dark borde-caja-moderado text-light">
            <div class="column-6-12 borde-caja-moderado pd-ssm bd-de bd-2p bd-blanco">
                <div class="r-h1 txt-bold-men pd-btigual texto-centrado">
                    Mensualidades
                </div>
                <div class="r-h7 mar-arr bd-blanco sombra-blanca-caja-completa">
                    <div class="fila-auto bd-ab txt-bold-pq pd-btigual">
                        <div class="column-4-12 bd-de texto-centrado pd-btigual txt-light-pq">Monto Bs</div>
                        <div class="column-4-12 bd-de texto-centrado pd-btigual txt-light-pq">Fecha Registrado</div>
                        <div class="column-4-12 texto-centrado pd-btigual txt-light-pq">Usuario</div>
                    </div>
                    <div id="listado_mensualidades" class="r-h10 scrolleable fila-hover-gris-boots">

                        <!--LISTADO DE LAS MENSUALIDADES DEL PERIODO ESCOLAR-->

                    </div>
                </div>
                <div class="r-h1"></div>
                <div class="r-h1">
                    <div id="caja-mm" class="column-6-12 pd-ssm">
                        <input type="text" id="monto_mensualidad" class="wh-completo" placeholder="Digite el Monto" value="0,00">
                    </div>
                    <div class="column-6-12">
                        <button type="button" id="btn_registrar_mensualidad" class="btn-azul-izq">Actualizar Mensualidad</button>
                    </div>
                </div>
            </div>
            <div class="column-6-12 borde-caja-moderado pd-ssm bd-de bd-2p bd-blanco">
                <div class="r-h1 txt-bold-men pd-btigual texto-centrado">
                    Porcentajes de Mora
                </div>
                <div class="r-h7 mar-arr bd-blanco sombra-blanca-caja-completa">
                    <div class="fila-auto bd-ab txt-bold-pq pd-btigual">
                        <div class="column-6-12 bd-de texto-centrado pd-btigual txt-light-pq">ID</div>
                        <div class="column-6-12 bd-de texto-centrado pd-btigual txt-light-pq">PORCENTAJE</div>
                    </div>
                    <div id="listado_moras" class="r-h10 scrolleable fila-hover-gris-boots">

                        <!--LISTADO DE LOR PORCENTAJES DE MORA-->

                    </div>
                </div>
                <div class="r-h1"></div>
                <div class="r-h1">
                    <div id="caja-por-mor" class="column-6-12 pd-ssm">
                        <input type="text" id="porcentaje_mora" class="wh-completo" placeholder="Digite el Porcentaje" value="0,00">
                    </div>
                    <div class="column-6-12">
                        <button type="button" id="btn_registrar_mora" class="btn-azul-izq">Registrar Mora</button>
                    </div>
                </div>
            </div> 
        </div>
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_mensualidades_mora(){
    window.document.title = 'Sistema. Mensualidades y Mora.';
	$("#titulo_seccion").html(titulo_mensualidades_mora);
	$("#seccion_dinamica_principal").html(plantilla_mensualidades_mora);
    
    listado_mensualidades_actuales();
    listado_porcentajes_mora();

    $("#monto_mensualidad").addClass('cal-num');
    $("#porcentaje_mora").addClass('cal-num');
    NumerosConDecimal(".cal-num");
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#mensualidad_mora",function(){
		main_mensualidades_mora();
	});
    
    $(document).on("click","#btn_registrar_mensualidad",function(){
        if(!CampoVacio("#monto_mensualidad") || $("#monto_mensualidad").val() == 0){
            advertenciaEnfocada("#caja-mm","#monto_mensualidad","¡Este Campo es Obligatorio¡",1);
        }else{
            datos = {
                criterio : "registrar",
                monto_mensualidad : Texto_Decimal($("#monto_mensualidad").val(),"0")
            };

            $.ajax({
                url: 'modulos/mensualidad.php',
                type: 'GET',
                dataType: "json",
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_mensualidades_actuales();
                    $("#monto_mensualidad").val("");
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_mensualidades_actuales();
                    $("#monto_mensualidad").val("");
                    ocultarAlerta(0.001);
                },4999);
            });  
        }
    });

    $(document).on("click","#btn_registrar_mora",function(){
        if(!CampoVacio("#porcentaje_mora") || $("#porcentaje_mora").val() == 0){
            advertenciaEnfocada("#caja-por-mor","#porcentaje_mora","¡El Porcentaje es Obligatorio¡",1);
        }else{
            datos = {
                criterio : "registrar",
                porcentaje_mora : Texto_Decimal($("#porcentaje_mora").val(), "0")
            };

            $.ajax({
                url: 'modulos/mora.php',
                type: 'GET',
                dataType: "json",
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_porcentajes_mora();
                    $("#porcentaje_mora").val("");
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_porcentajes_mora();
                    $("#porcentaje_mora").val("");
                    ocultarAlerta(0.001);
                },4999);
            });  
        }
    });
});

function listado_mensualidades_actuales(){
    $.ajax({
        async: false,
        url: 'modulos/mensualidad.php',
        type: 'GET',
        dataType: "json",
        data: {criterio : 'listado'}
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
        template += `
            <div class="r-h2 bd-ab txt-bold-pq pd-btigual">
                <div class="column-4-12 texto-centrado pd-btigual txt-light-pq">${datos.monto}</div>
                <div class="column-4-12 texto-centrado pd-btigual txt-light-pq">${datos.fecha_registrado}</div>
                <div class="column-4-12 texto-centrado scrolleable pd-btigual txt-light-pq">${datos.usuario}</div>
            </div>
            `
        });
        $("#listado_mensualidades").html(template);
    })
    .fail(function(response) {
        $("#listado_mensualidades").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}

function listado_porcentajes_mora(){
    $.ajax({
        async: false,
        url: 'modulos/mora.php',
        type: 'GET',
        dataType: "json",
        data: {criterio : 'listado'}
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
        template += `
            <div class="r-h2 bd-ab txt-bold-pq pd-btigual">
                <div class="column-6-12 texto-centrado pd-btigual txt-arial-bold-pq">${datos.id}</div>
                <div class="column-6-12 texto-centrado pd-btigual txt-arial-bold-pq">${datos.porcentaje}%</div>
            </div>
            `
        });
        $("#listado_moras").html(template);
    })
    .fail(function(response) {
        $("#listado_moras").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}