//************ TITULO DE LA SECCION
let titulo_inscripciones_cupo = `Panel de Control de Inscripción y Cupo.`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_inscripciones_cupo = `
	<div class="wh-completo pd-ssm">
        <div class="r-h12 bg-dark borde-caja-moderado text-light">
            <div class="column-6-12 borde-caja-moderado pd-ssm bd-de bd-2p bd-blanco">
                <div class="r-h1 txt-bold-men pd-btigual texto-centrado">
                   Montos de Cupo
                </div>
                <div class="r-h7 mar-arr bd-blanco sombra-blanca-caja-completa">
                    <div class="fila-auto bd-ab txt-bold-pq pd-btigual">
                        <div class="column-4-12 bd-de texto-centrado pd-btigual txt-light-pq">Monto Bs</div>
                        <div class="column-4-12 bd-de texto-centrado pd-btigual txt-light-pq">Fecha Registrado</div>
                        <div class="column-4-12 texto-centrado pd-btigual txt-light-pq">Usuario</div>
                    </div>
                    <div id="listado_cupos" class="r-h10 scrolleable fila-hover-gris-boots">

                        <!--LISTADO DE LOS CUPOS DEL PERIODO ESCOLAR-->

                    </div>
                </div>
                <div class="r-h1"></div>
                <div class="r-h1">
                    <div id="caja-cupo" class="column-6-12 pd-ssm">
                        <input type="text" id="monto_cupo" class="wh-completo" placeholder="Digite el Monto" value="0,00">
                    </div>
                    <div class="column-6-12">
                        <button type="button" id="btn_registrar_cupo" class="btn-azul-izq">Registrar Cupo</button>
                    </div>
                </div>
            </div>
            <div class="column-6-12 borde-caja-moderado pd-ssm bd-de bd-2p bd-blanco">
                <div class="r-h1 txt-bold-men pd-btigual texto-centrado">
                    Montos de Inscripción
                </div>
                <div class="r-h7 mar-arr bd-blanco sombra-blanca-caja-completa">
                    <div class="fila-auto bd-ab txt-bold-pq pd-btigual">
                        <div class="column-4-12 bd-de texto-centrado pd-btigual txt-light-pq">Monto Bs</div>
                        <div class="column-4-12 bd-de texto-centrado pd-btigual txt-light-pq">Fecha Registrado</div>
                        <div class="column-4-12 texto-centrado pd-btigual txt-light-pq">Usuario</div>
                    </div>
                    <div id="listado_inscripciones" class="r-h10 scrolleable fila-hover-gris-boots">

                        <!--LISTADO DE LAS INSCRIPCIONES DEL PERIODO ESCOLAR-->

                    </div>
                </div>
                <div class="r-h1"></div>
                <div class="r-h3">
                    <div class="r-h4">
                        <div id="caja-mi" class="column-6-12 pd-ssm">
                            <input type="text" id="monto_inscripcion" class="wh-completo" placeholder="Digite el Monto" value="0,00">
                        </div>
                        <div class="column-6-12">
                            <button type="button" id="btn_registrar_inscripcion" class="btn-azul-drch">Actualizar Monto</button>
                        </div>
                    </div>
                    <div class="r-h4 txt-light-pq pd-btigual-mitad">
                        ¿Aplicar a Estudiantes ya Registrados?
                    </div>
                    <div id="caja_estuInsc" class="r-h4 txt-bold-pq pd-btigual">
                        <div class="column-1m-12">
                            <input type="radio" name="estu_insc" id="si" class="estu_insc option-input-completo radio" value="1">
                        </div>
                        <label class="column-1-12 texto-centrado pd-btigual" for="si">Si</label>

                        <div class="column-1m-12"></div>

                        <div class="column-1m-12">
                            <input type="radio" name="estu_insc" id="no" class="estu_insc option-input-completo radio" value="0">
                        </div>
                        <label class="column-1-12 texto-centrado pd-btigual" for="no">No</label>
                    </div>
                </div>
            </div> 
        </div>
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_inscripciones_cupos(){
    window.document.title = 'Sistema. Inscripción y Cupo.';
	$("#titulo_seccion").html(titulo_inscripciones_cupo);
	$("#seccion_dinamica_principal").html(plantilla_inscripciones_cupo);
    listado_cupos_actuales();
    listado_inscripciones_actuales();
    $("#monto_cupo").addClass('cal-num');
    $("#monto_inscripcion").addClass('cal-num');
    NumerosConDecimal(".cal-num");
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#cupo_inscripcion",function(){
		main_inscripciones_cupos();
	});
    
    $(document).on("click","#btn_registrar_cupo",function(){
        if(!CampoVacio("#monto_cupo") || $("#monto_cupo").val() == 0){
            advertenciaEnfocada("#caja-cupo","#monto_cupo","¡Este Campo es Obligatorio!",1);
        }else{
            datos = {
                criterio : "registrar",
                monto_cupo : Texto_Decimal($("#monto_cupo").val(),"0")
            };

            $.ajax({
                url: 'modulos/cupo.php',
                type: 'GET',
                dataType: "json",
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_cupos_actuales();
                    $("#monto_cupo").val("");
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_cupos_actuales();
                    $("#monto_cupo").val("");
                    ocultarAlerta(0.001);
                },4999);
            });  
        }
    });

    $(document).on("click","#btn_registrar_inscripcion",function(){
        if(!CampoVacio("#monto_inscripcion") || $("#monto_inscripcion").val() == 0){
            advertenciaEnfocada("#caja-mi","#monto_inscripcion","¡Este Campo es Obligatorio!",1);
        }else if(!validarRadio(".estu_insc")){
            advertenciaEnfocada("#caja_estuInsc",".estu_insc","DEBE ELEJIR UNA OPCIÓN. SI: Aplíca para los estudiantes que ya estan registrados y para los que se registrarán. NO: Aplíca solo para los que se registrarán en un futuro. ¡ES OBLIGATORIO!",1);
        }else{
            let opcion_ins = obtener_valor_radio(".estu_insc");
            datos = {
                criterio : "registrar",
                monto_inscripcion : Texto_Decimal($("#monto_inscripcion").val(),"0"),
                opcion_ins
            };

            $.ajax({
                url: 'modulos/monto_inscripcion.php',
                type: 'GET',
                dataType: "json",
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_inscripciones_actuales();
                    $("#monto_inscripcion").val("");
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_inscripciones_actuales();
                    $("#monto_inscripcion").val("");
                    ocultarAlerta(0.001);
                },4999);
            });  
        }
    });
});

function listado_cupos_actuales(){
    $.ajax({
        async: false,
        url: 'modulos/cupo.php',
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
        $("#listado_cupos").html(template);
    })
    .fail(function(response) {
        $("#listado_cupos").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}

function listado_inscripciones_actuales(){
    $.ajax({
        async: false,
        url: 'modulos/monto_inscripcion.php',
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
        $("#listado_inscripciones").html(template);
    })
    .fail(function(response) {
        $("#listado_inscripciones").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });     
}