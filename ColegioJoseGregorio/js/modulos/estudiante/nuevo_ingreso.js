//************ TITULO DE LA SECCION
let titulo_nuevo_estudiante = "Nuevo Estudiante, " + fecha_actual;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_nuevo_ingreso = `
	<div class="wh-completo pd-sm bd-1p-ngr">
        <!--MARGENES ARRIBA E IZQUIERDO-->
        <div class="r-h1"></div>
        <div class="column-1-12 r-h1"></div>
        <div class="r-h9 column-10-12 bg-dark texto-bln-sombra-ngr bd-1p-bln sombra-gris-caja-completa">
            <div class="r-h1 bd-ab bd-blanco bd-2p txt-bold-pq texto-centrado">
                <div class="column-5-12">
                    <div id="caja_estuCedulado" class="column-4-12">¿Cedulado?</div>
                    <div class="column-1m-12">
                        <input type="radio" name="estudiante_cedulado" id="si_cedulado" class="estudiante_cedulado option-input-completo radio" value="1">
                    </div>
                    <label class="column-1m-12 txt-light-pq" for="si_cedulado">Si</label>

                    <div class="column-1-12"></div>

                    <div class="column-1m-12">
                        <input type="radio" name="estudiante_cedulado" id="no_cedulado" class="estudiante_cedulado option-input-completo radio" value="0">
                    </div>
                    <label class="column-1m-12 txt-light-pq" for="no_cedulado">No</label>
                </div>
                <div class="column-7-12 pd-btigual">
                    REGISTRO DEL ESTUDIANTE - PLANILLA DE DATOS
                </div>
            </div>
            <div class="r-h10">
                <div class="r-hm"></div>
                <div class="r-h1">
                    <div id="caja_estuInsc" class="column-4-12 txt-bold-pq texto-derecho">
                        Clase de Nuevo Estudiante:
                    </div>
                    <div class="column-m-12"></div>
                    <div class="column-7-12">
                        <div class="column-1-12">
                            <input type="radio" name="estudiante_nuevo_ingreso" id="clase_nuevo_ingreso" class="estudiante_nuevo_ingreso option-input-completo radio" value="1">
                        </div>
                        <label class="column-3-12 txt-light-pq" for="clase_nuevo_ingreso">Nuevo Ingreso</label>

                        <div class="column-m-12"></div>

                        <div class="column-1-12">
                            <input type="radio" name="estudiante_nuevo_ingreso" id="clase_regular" class="estudiante_nuevo_ingreso option-input-completo radio" value="0">
                        </div>
                        <label class="column-2-12 txt-light-pq" for="clase_regular">Regular</label>
                        
                        <div class="column-m-12"></div>

                        <div class="column-1-12">
                            <input type="radio" name="estudiante_nuevo_ingreso" id="clase_deudor_antiguo" class="estudiante_nuevo_ingreso option-input-completo radio" value="2">
                        </div>
                        <label class="column-3-12 txt-light-pq" for="clase_deudor_antiguo">Deudor Antiguo</label>
                    </div>
                </div>

                <div class="r-hm"></div>

                <div class="r-h1m">
                    <div class="column-6-12 texto-centrado pd-btigual txt-bold-pq">Datos del Estudiante</div>
                    <div id="year_escolar_valor" class="column-6-12 texto-centrado pd-btigual txt-bold-pq" valor="1">Año Escolar <span id="year_escolar_escrito"></span></div>
                </div>
                <div class="r-h1m">
                    <div id="caja_e1n" class="column-3-12 pd-ssm">
                        <input type="text" id="estudiante-primer_nombre" class="wh-completo texto-centrado" placeholder="Primer Nombre">
                    </div>
                    <div id="caja_e2n" class="column-3-12 pd-ssm">
                        <input type="text" id="estudiante-segundo_nombre" class="wh-completo texto-centrado" placeholder="Segundo Nombre">
                    </div>
                    <div id="caja_e1a" class="column-3-12 pd-ssm">
                        <input type="text" id="estudiante-primer_apellido" class="wh-completo texto-centrado" placeholder="Primer Apellido">
                    </div>
                    <div id="caja_e2a" class="column-3-12 pd-ssm">
                        <input type="text" id="estudiante-segundo_apellido" class="wh-completo texto-centrado" placeholder="Segundo Apellido">
                    </div>
                </div>
                <div class="r-h1m">

                    <div id="caja_ec" class="column-3-12 pd-ssm"></div>

                    <div id="seccion_dinamica_nuevo_ingreso" class="column-9-12"></div>

                </div>
                
                <div class="r-hm"></div>

                <div class="r-h1m column-6-12 pd-btigual texto-centrado txt-bold-pq">Datos del Representante</div>
                <div class="r-h1m">
                    <div id="caja_r1n" class="column-3-12 pd-ssm">
                        <input type="text" id="representante-primer_nombre" class="wh-completo texto-centrado" placeholder="Primer Nombre">
                    </div>
                    <div id="caja_r2n" class="column-3-12 pd-ssm">
                        <input type="text" id="representante-segundo_nombre" class="wh-completo texto-centrado" placeholder="Segundo Nombre">
                    </div>
                    <div id="caja_r1a" class="column-3-12 pd-ssm">
                        <input type="text" id="representante-primer_apellido" class="wh-completo texto-centrado" placeholder="Primer Apellido">
                    </div>
                    <div id="caja_r2a" class="column-3-12 pd-ssm">
                        <input type="text" id="representante-segundo_apellido" class="wh-completo texto-centrado" placeholder="Segundo Apellido">
                    </div>
                </div>
                <div class="r-h1m">
                    <div class="column-3-12"></div>
                    <div id="caja_rc" class="column-3-12 pd-ssm">
                        <input type="text" id="representante-cedula" class="wh-completo texto-centrado" placeholder="Cedula">
                    </div>
                    <div id="caja_rt" class="column-3-12 pd-ssm">
                        <input type="text" id="representante-telefono" class="wh-completo texto-centrado" placeholder="Nro Telefono">
                    </div>
                </div>

                <div class="column-5-12 r-h1"></div>

                <div class="r-h1 column-3-12 pd-izqsolo">
                    <a href="#" id="guardar_estudiante" class="btn-verde"><i class="far fa-save"></i> Guardar</a>
                </div>
            </div>
        </div>
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_nuevo_ingreso(){
    window.document.title = 'Sistema. Nuevo Ingreso';
	$("#titulo_seccion").html(titulo_nuevo_estudiante);
	$("#seccion_dinamica_principal").html(plantilla_nuevo_ingreso);
    $("#year_escolar_escrito").text(periodoActual(mesActual,yearActual));

    validacion_nuevo_ingreso();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#nuevo_ingreso",function(){
		main_nuevo_ingreso();
	});

    $(document).on("click",".estudiante_nuevo_ingreso",function(){
        quitar_advertencia("#caja_estuInsc");
        let valor = parseInt(obtener_valor_radio(".estudiante_nuevo_ingreso"));
        let seccion = "";
        switch(valor){
            case 0:
            case 1:
                seccion = `
                    <div id="caja_grado" class="column-4-12 pd-ssm">
                        <select id="grado_nuevo_ingreso" class="wh-completo texto-centrado">
                            <option value="0">Año/Grado</option>
                        </select>
                    </div>
                    <div id="caja_seccion" class="column-4-12 pd-ssm">
                        <select id="seccion_nuevo_ingreso" class="wh-completo texto-centrado desactivar">
                            <option value="0">Sección</option>
                        </select>
                    </div>
                    <div id="caja_mes" class="column-4-12 pd-ssm">
                        <select id="mes" class="wh-completo texto-centrado">
                            <option value="0">Mes de Inscripción</option>
                        </select>
                    </div>
                `;
                $("#seccion_dinamica_nuevo_ingreso").html(seccion);
                grados_categorias("#grado_nuevo_ingreso");
                mesesInscripcion("#mes",mesActual);
                break;
            case 2:
                seccion = `
                    <div id="caja_desde_antiguo" class="column-6-12 pd-ssm">
                        <select id="mes_desde_antiguo" class="wh-completo texto-centrado">
                        </select>
                    </div>
                    <div id="caja_hasta_antiguo" class="column-6-12 pd-ssm">
                        <select id="mes_hasta_antiguo" class="wh-completo texto-centrado desactivar">
                            <option value="0">Mes Hasta</option>
                        </select>
                    </div>
                `;
                $("#seccion_dinamica_nuevo_ingreso").html(seccion);
                colocarMeses("#mes_desde_antiguo", 1, "Mes Desde");
                break;
            default:
                console.log("¡NO DISPONIBLE!");
        }
    });

    $(document).on("click",".estudiante_cedulado",function(){
        quitar_advertencia("#caja_estuCedulado");
        let valor = parseInt(obtener_valor_radio(".estudiante_cedulado"));
        let seccion_cedula = "";
        switch(valor){
            case 0:
                seccion_cedula = `
                    <input type="text" id="estudiante-cedula" class="wh-completo texto-centrado desactivar" placeholder="Cedula">
                `;
                $("#caja_ec").html(seccion_cedula);
                break;
            case 1:
                seccion_cedula = `
                    <input type="text" id="estudiante-cedula" class="wh-completo texto-centrado" placeholder="Cedula">
                `;
                $("#caja_ec").html(seccion_cedula);
                break;
            default:
                console.log("¡NO DISPONIBLE!");
        }
    });

    $(document).on("change","#grado_nuevo_ingreso",function(){
        let valor = parseInt($(this).val());
        if(valor == 0){
            /*PARTE PARA DESACTIVAR Y REINICIAR UN SELECT*/
            $("#seccion_nuevo_ingreso").addClass('desactivar');
            $("#seccion_nuevo_ingreso").empty();
            $("#seccion_nuevo_ingreso").html("<option value='0'>Sección</option>");
            /*HASTA AQUI*/
        }else{
            $("#seccion_nuevo_ingreso").removeClass('desactivar');
            secciones_grados("#seccion_nuevo_ingreso",valor);
        }
    });

    $(document).on("change","#mes_desde_antiguo",function(e){
        e.preventDefault();
        let valor_desde = parseInt($(this).val());
        if(valor_desde == 0){
            $("#mes_hasta_antiguo").addClass('desactivar');
            $("#mes_hasta_antiguo").empty();
            $("#mes_hasta_antiguo").html("<option value='0'>Mes Hasta</option>");
        }else{
            $("#mes_hasta_antiguo").removeClass('desactivar');
            colocarMeses("#mes_hasta_antiguo", valor_desde, "Mes Hasta");
        }
    });

    $(document).on("click","#guardar_estudiante",function(e){
        e.preventDefault();

        let seccion_escogida = parseInt(obtener_valor_radio(".estudiante_nuevo_ingreso"));
        let estudiante_cedulado = parseInt(obtener_valor_radio(".estudiante_cedulado"));

        if(!validarRadio(".estudiante_cedulado")){
            advertenciaEnfocada("#caja_estuCedulado",".estudiante_cedulado","DEBE ELEJIR UNA OPCIÓN. ¡ES OBLIGATORIO!",1);
        }else if(!validarRadio(".estudiante_nuevo_ingreso")){
            advertenciaEnfocada("#caja_estuInsc",".estudiante_nuevo_ingreso","DEBE ELEJIR UNA OPCIÓN. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#estudiante-primer_nombre",3,12) || !CampoVacio("#estudiante-primer_nombre") || !unaPalabra("#estudiante-primer_nombre")){
            advertenciaEnfocada("#caja_e1n","#estudiante-primer_nombre","Introdúzca el Primer Nombre del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#estudiante-primer_apellido",3,12) || !CampoVacio("#estudiante-primer_apellido") || !unaPalabra("#estudiante-primer_apellido")){
            advertenciaEnfocada("#caja_e1a","#estudiante-primer_apellido","Introdúzca el Primer Apellido del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#estudiante-segundo_apellido",3,12) || !CampoVacio("#estudiante-segundo_apellido")){
            advertenciaEnfocada("#caja_e2a","#estudiante-segundo_apellido","Introdúzca el Segundo Apellido del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(estudiante_cedulado == 1 && (!CampoPatron("#estudiante-cedula",patron_cedula) || !CampoVacio("#estudiante-cedula"))){
            advertenciaEnfocada("#caja_ec","#estudiante-cedula","No debe dejarlo vacio. Formato: V-12345678. ¡ES OBLIGATORIO!",1);
        }else if(!CampoVacio("#grado_nuevo_ingreso")){         
                advertenciaEnfocada("#caja_grado","#grado_nuevo_ingreso","Elijá el Grado ¡ES OBLIGATORIO!",1);
        }else if(!CampoVacio("#seccion_nuevo_ingreso")){       
                advertenciaEnfocada("#caja_seccion","#seccion_nuevo_ingreso","Elijá la Sección. ¡ES OBLIGATORIO!",1);       
        }else if(!CampoVacio("#mes")){       
                advertenciaEnfocada("#caja_mes","#mes","Elijá el Mes de Inscripción. ¡ES OBLIGATORIO!",1);       
        }else if(!CampoVacio("#mes_desde_antiguo")){         
                advertenciaEnfocada("#caja_desde_antiguo","#mes_desde_antiguo","Escoja el Mes desde el cual debe el Estudiante ¡ES OBLIGATORIO!",1);
        }else if(!CampoVacio("#mes_hasta_antiguo")){       
                advertenciaEnfocada("#caja_hasta_antiguo","#mes_hasta_antiguo","Escoja el Mes hasta donde llega la deuda del Estudiante ¡ES OBLIGATORIO!",1);    
        }else if(!LongitudCampo("#representante-primer_nombre",3,12) || !CampoVacio("#representante-primer_nombre")){
            advertenciaEnfocada("#caja_r1n","#representante-primer_nombre","Introdúzca el Primer Nombre del Representante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#representante-primer_apellido",3,12) || !CampoVacio("#representante-primer_apellido")){
            advertenciaEnfocada("#caja_r1a","#representante-primer_apellido","Introdúzca el Primer Apellido del Representante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#representante-cedula",patron_cedula) || !CampoVacio("#representante-cedula")){
            advertenciaEnfocada("#caja_rc","#representante-cedula","No debe dejarlo vacio. Formato: V-12345678. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#representante-telefono",patron_telefono) || !CampoVacio("#representante-telefono")){
            advertenciaEnfocada("#caja_rt","#representante-telefono","Formato Incorrecto, Ingrese Valores. Ej: 0426-6638765. ¡ES OBLIGATORIO!",1);
        }else{
            let data_importante = {};
            let data_estudiante = {};
            let datos = [];
            if(seccion_escogida === 0 || seccion_escogida === 1){
                data_importante = {
                    seccion_especifica : $("#seccion_nuevo_ingreso").val(),
                    mes_inscripcion : $("#mes").val(),
                    tipo_estudiante : seccion_escogida 
                };
            }else if(seccion_escogida === 2){
                data_importante = {
                    mes_desde : $("#mes_desde_antiguo").val(),
                    mes_hasta : $("#mes_hasta_antiguo").val(),
                    tipo_estudiante : seccion_escogida 
                };
            }
            data_estudiante = {
                estudiante_primer_nombre : $("#estudiante-primer_nombre").val(),
                estudiante_segundo_nombre : $("#estudiante-segundo_nombre").val(),
                estudiante_primer_apellido : $("#estudiante-primer_apellido").val(),
                estudiante_segundo_apellido : $("#estudiante-segundo_apellido").val(),
                estudiante_cedula : $("#estudiante-cedula").val(),
                estudiante_cedulado : estudiante_cedulado,
                representante_primer_nombre : $("#representante-primer_nombre").val(),
                representante_segundo_nombre : $("#representante-segundo_nombre").val(),
                representante_primer_apellido : $("#representante-primer_apellido").val(),
                representante_segundo_apellido : $("#representante-segundo_apellido").val(),
                representante_cedula : $("#representante-cedula").val(),
                representante_telefono : $("#representante-telefono").val()
            };

            datos.push(data_estudiante);
            datos.push(data_importante);

            $.ajax({
                url: "modulos/estudiante_registrar.php",
                type: "POST",
                dataType: 'json',
                data: {superdata : JSON.stringify(datos)},
                beforeSend: function(){
                    $(".cargando").css('display', 'block');
                }
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    main_nuevo_ingreso();
                    ocultarAlerta(0.001);
                },1999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    ocultarAlerta(0.001);
                },9999);
            })
            .always(function(){
              $(".cargando").css('display', 'none');
            });
        }
    });// AQUI TERMINA EL EVENTO DEL BOTON DE GUARDADO
});

/*VALIDACIÓN DEL FORMULARIO DE REGISTRO DEL ESTUDIANTE*/
function validacion_nuevo_ingreso(){
    //validacion para primer nombre estudiante.
    Validaciones(4,"#estudiante-primer_nombre","#caja_e1n","Introduzca el Primer Nombre del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#estudiante-primer_nombre");
    mayusculasCampo("#estudiante-segundo_nombre");

    //validacion para primer apellido estudiante.
    Validaciones(4,"#estudiante-primer_apellido","#caja_e1a","Introduzca el Primer Apellido del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#estudiante-primer_apellido");

    //validacion para segundo apellido estudiante.
    Validaciones(4,"#estudiante-segundo_apellido","#caja_e2a","Introduzca el Segundo Apellido del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#estudiante-segundo_apellido");

    //validacion de la cedula del estudiante.
    PosicionCaracterCampo("#estudiante-cedula","-",1);
    mayusculasCampo("#estudiante-cedula");
    numerosDeCedula("#estudiante-cedula");
    Validaciones(2,"#estudiante-cedula","#caja_ec","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",patron_cedula);

    //validacion para primer nombre representante.
    Validaciones(4,"#representante-primer_nombre","#caja_r1n","Introduzca el Primer Nombre del Representante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#representante-primer_nombre");
    mayusculasCampo("#representante-segundo_nombre");
    //validacion para primer apellido representante.
    Validaciones(4,"#representante-primer_apellido","#caja_r1a","Introduzca el Primer Apellido del Representante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#representante-primer_apellido");
    mayusculasCampo("#representante-segundo_apellido");

    //validacion para telefono del representante.
    Validaciones(2,"#representante-telefono","#caja_rt","Formato Incorrecto, Ingrese Valores. Ej: 0426-6638765. ¡ES OBLIGATORIO!",patron_telefono);
    soloNumeros("#representante-telefono");
    PosicionCaracterCampo("#representante-telefono","-",4);
                
    //validacion de la cedula del representante.
    PosicionCaracterCampo("#representante-cedula","-",1);
    mayusculasCampo("#representante-cedula");
    numerosDeCedula("#representante-cedula");
    Validaciones(2,"#representante-cedula","#caja_rc","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",patron_cedula);

    //validación del grado.
    Validaciones(1,"#mes","#caja_mes","Elijá el Mes de Inscripción. ¡ES OBLIGATORIO!");

    //validación del grado.
    Validaciones(1,"#grado_nuevo_ingreso","#caja_grado","Debe elegir el Grado. ¡ES OBLIGATORIO!");

    //validación de la sección.
    Validaciones(1,"#seccion_nuevo_ingreso","#caja_seccion","Debe elegir la Sección. ¡ES OBLIGATORIO!");
}
/*TERMINA LA VALIDACIÓN DEL FORMULARIO*/
