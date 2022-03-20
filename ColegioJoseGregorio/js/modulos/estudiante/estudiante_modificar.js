let titulo_estudiante_modificar = "Modificación de Datos del Estudiante.";

let plantilla_estudiante_modificar = `
    <div class="wh-completo pd-sm bd-1p-ngr">
        <!--MARGENES ARRIBA E IZQUIERDO-->
        <div class="r-h1"></div>
        <div class="column-1-12 r-h1"></div>
        <div class="r-h9 column-10-12 bg-dark texto-bln-sombra-ngr bd-1p-bln sombra-gris-caja-completa">
            <div class="r-h1 bd-ab bd-blanco bd-2p pd-btigual txt-bold-pq texto-centrado">
                <div class="column-5-12">
                    <div id="caja_estuCedulado" class="column-4-12">¿Cedulado?</div>
                    <div class="column-1m-12">
                        <input type="radio" name="cedulado" id="si_cedulado" class="cedulado option-input-completo radio desactivar" value="1">
                    </div>
                    <label class="column-1m-12 txt-light-pq" for="si_cedulado">Si</label>

                    <div class="column-1-12"></div>

                    <div class="column-1m-12">
                        <input type="radio" name="cedulado" id="no_cedulado" class="cedulado option-input-completo radio desactivar" value="0">
                    </div>
                    <label class="column-1m-12 txt-light-pq" for="no_cedulado">No</label>
                </div>
                <div class="column-7-12 pd-btigual">
                    REGISTRO DEL ESTUDIANTE - PLANILLA DE DATOS
                </div>
            </div>
            <div class="r-h10">
                <div class="r-hm"></div>

                <div id="caja_contenidoModiCedEst" class="r-h1"></div>

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
                    <div id="caja_ec" class="column-3-12 pd-ssm">
                        <input type="text" id="estudiante-cedula" class="wh-completo texto-centrado" placeholder="Cedula">
                    </div>
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

                <div id="id_estudiante" class="column-5-12 r-h1 txt-ppq invisible_1"></div>

                <div class="r-h1 column-3-12 pd-izqsolo">
                    <a href="#" id="modificar_estudiante" class="btn-verde"><i class="far fa-save"></i> Guardar</a>
                </div>
            </div>
        </div>
    </div>
`;
/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_estudiante_modificar(){
    window.document.title = 'Sistema. Modificación de Estudiante';
	$("#titulo_seccion").html(titulo_estudiante_modificar);
	$("#seccion_dinamica_principal").html(plantilla_estudiante_modificar);
    
    $("#year_escolar_escrito").text(periodoActual(mesActual,yearActual));

    $("#estudiante-cedula").addClass('desactivar');

    validacion_nuevo_ingreso();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){
	$(document).on("click",".modificar_estudiante",function(){
        var cedula_estudiante = $($(this)[0]).attr('id');
        $(".close").trigger('click');
        $(".cargando").css('display', 'block');
        setTimeout(function(){
            main_estudiante_modificar();
            obtenerDatos({opcion:"estudiante_formulario", cedula_estudiante: cedula_estudiante}, renderizarDatos, "modulos/obtenerDatos.php");
            $(".cargando").css('display', 'none');
        },1000);
	});

    $(document).on("click",".modificarCedEst",function(){
        quitar_advertencia("#caja_modificarCedEst");
        let valor = parseInt(obtener_valor_radio(".modificarCedEst"));
        switch(valor){
            case 0:
                $("#estudiante-cedula").addClass('desactivar');
                break;
            case 1:
                $("#estudiante-cedula").removeClass('desactivar');
                break;
            default:
                console.log("¡NO DISPONIBLE!");
        }
    });

    $(document).on("click","#modificar_estudiante",function(e){
        e.preventDefault();
        
        let estudiante_cedulado = parseInt(obtener_valor_radio(".cedulado"));
        let modificar_cedEst = parseInt(obtener_valor_radio(".modificarCedEst"));

        if(!validarRadio(".cedulado")){
            advertenciaEnfocada("#caja_estuCedulado",".cedulado","DEBE ELEJIR UNA OPCIÓN. ¡ES OBLIGATORIO!",1);
        }else if(!validarRadio(".modificarCedEst")){
            advertenciaEnfocada("#caja_modificarCedEst",".modificarCedEst","DEBE ELEJIR UNA OPCIÓN. ¡ES OBLIGATORIO!",1);
        }if(!LongitudCampo("#estudiante-primer_nombre",3,12) || !CampoVacio("#estudiante-primer_nombre") || !unaPalabra("#estudiante-primer_nombre")){
            advertenciaEnfocada("#caja_e1n","#estudiante-primer_nombre","Introdúzca el Primer Nombre del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#estudiante-primer_apellido",3,12) || !CampoVacio("#estudiante-primer_apellido") || !unaPalabra("#estudiante-primer_apellido")){
            advertenciaEnfocada("#caja_e1a","#estudiante-primer_apellido","Introdúzca el Primer Apellido del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#estudiante-segundo_apellido",3,12) || !CampoVacio("#estudiante-segundo_apellido")){
            advertenciaEnfocada("#caja_e2a","#estudiante-segundo_apellido","Introdúzca el Segundo Apellido del Estudiante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(((estudiante_cedulado == 0 && modificar_cedEst == 1) || (estudiante_cedulado == 1)) && (!CampoPatron("#estudiante-cedula",patron_cedula) || !CampoVacio("#estudiante-cedula"))){
            advertenciaEnfocada("#caja_ec","#estudiante-cedula","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",1);
        }else if(estudiante_cedulado == 0 && modificar_cedEst == 0 && (!CampoPatron("#estudiante-cedula",patron_cedula_estudiante) || !CampoVacio("#estudiante-cedula"))){
            advertenciaEnfocada("#caja_ec","#estudiante-cedula","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#representante-primer_nombre",3,12) || !CampoVacio("#representante-primer_nombre")){
            advertenciaEnfocada("#caja_r1n","#representante-primer_nombre","Introdúzca el Primer Nombre del Representante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#representante-primer_apellido",3,12) || !CampoVacio("#representante-primer_apellido")){
            advertenciaEnfocada("#caja_r1a","#representante-primer_apellido","Introdúzca el Primer Apellido del Representante. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#representante-cedula",patron_cedula) || !CampoVacio("#representante-cedula")){
            advertenciaEnfocada("#caja_rc","#representante-cedula","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#representante-telefono",patron_telefono) || !CampoVacio("#representante-telefono")){
            advertenciaEnfocada("#caja_rt","#representante-telefono","Formato Incorrecto, Ingrese Valores. Ej: 0426-6638765. ¡ES OBLIGATORIO!",1);
        }else{
            let id_estudiante = parseInt($("#id_estudiante").text());
            let datos = {
                estudiante_primer_nombre : $("#estudiante-primer_nombre").val(),
                estudiante_segundo_nombre : $("#estudiante-segundo_nombre").val(),
                estudiante_primer_apellido : $("#estudiante-primer_apellido").val(),
                estudiante_segundo_apellido : $("#estudiante-segundo_apellido").val(),
                estudiante_cedula : $("#estudiante-cedula").val(),
                representante_primer_nombre : $("#representante-primer_nombre").val(),
                representante_segundo_nombre : $("#representante-segundo_nombre").val(),
                representante_primer_apellido : $("#representante-primer_apellido").val(),
                representante_segundo_apellido : $("#representante-segundo_apellido").val(),
                representante_cedula : $("#representante-cedula").val(),
                representante_telefono : $("#representante-telefono").val(),
                modificar_cedula : modificar_cedEst,
                estudiante_cedulado : estudiante_cedulado,
                id_estudiante : id_estudiante
            };

            $.ajax({
                url: "modulos/estudiante_modificar.php",
                type: "POST",
                dataType: 'json',
                data: datos,
                beforeSend: function(){
                    $(".cargando").css('display', 'block');
                }
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    main_control_pago();
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
