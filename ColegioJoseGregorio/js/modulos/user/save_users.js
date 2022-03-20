//************ TITULO DE LA SECCION
let titulo_nuevo_usuario = "Sistema *UserRoot. Nuevo Usuario";

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_nuevo_usuario = `
	<div class="wh-completo pd-sm">
        <!--MARGENES ARRIBA E IZQUIERDO-->
        <div class="r-h1"></div>
        <div class="column-1-12 r-h1"></div>
        <div class="r-h9 column-10-12 bg-dark texto-bln-sombra-ngr bd-1p-bln sombra-gris-caja-completa">
            <div class="r-h2 bd-1p-bln pd-btigual-mitad txt-bold-men texto-centrado">
                    Formulario de Usuario
            </div>
            <div class="r-h10">
                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div class="column-6-12 texto-centrado txt-light-men">Datos del Usuario</div>
                </div>
                <div class="r-h2">
                    <div id="caja_nu" class="column-4-12 pd-ssm">
                        <input type="text" id="usuario-primer_nombre" class="wh-completo texto-centrado" placeholder="Primer Nombre">
                    </div>
                    <div id="caja_au" class="column-4-12 pd-ssm">
                        <input type="text" id="usuario-primer_apellido" class="wh-completo texto-centrado" placeholder="Primer Apellido">
                    </div>
                    <div id="caja_cu" class="column-4-12 pd-ssm">
                        <input type="text" id="usuario-cedula" class="wh-completo texto-centrado" placeholder="Cedula">
                    </div>
                </div>
                <div class="r-h2"></div>
                <div class="r-h2">
                    <div id="caja_cou" class="column-4-12 pd-ssm">
                        <input type="text" id="usuario-correo" class="wh-completo texto-centrado" placeholder="Correo Electronico">
                    </div>
                    <div class="column-2-12 txt-light-men texto-centrado">
                        Sexo:
                    </div>
                    <div id="caja_su" class="column-6-12">
                        <div class="column-4-12"> 
                            <div class="column-4-12 txt-bold-men">
                                <input id="mas" class="option-input-completo radio sexo_usuario" name="sexo_usuario" type="radio" value="MASCULINO">
                            </div>
                            <label class="column-8-12 pd-btigual-mitad txt-light-pq" for="mas">Masculino</label>
                        </div>
                        <div class="column-4-12"> 
                            <div class="column-4-12 txt-bold-men">
                                <input id="fem" class="option-input-completo radio sexo_usuario" name="sexo_usuario" type="radio" value="FEMENINO">
                            </div>
                            <label class="column-8-12 pd-btigual-mitad txt-light-pq" for="fem">Femenino</label>
                        </div>
                    </div>
                </div>

                <div class="column-5-12 r-h2"></div>

                <div class="r-h1m column-3-12 pd-izqsolo mar-arr">
                    <a href="#" id="guardar_usuario" class="btn-verde"><i class="far fa-save"></i> Guardar</a>
                </div>
            </div>
        </div>
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_nuevo_usuario(){
    window.document.title = titulo_nuevo_usuario;
	$("#seccion_dinamica_principal_root").html(plantilla_nuevo_usuario);
    validacion_nuevo_usuario();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#enlace_registro_usuario",function(){
		main_nuevo_usuario();
	});

    $(document).on("click","#guardar_usuario",function(e){
        e.preventDefault();
        if(!LongitudCampo("#usuario-primer_nombre",3,12) || !CampoVacio("#usuario-primer_nombre") || !unaPalabra("#usuario-primer_nombre")){
            advertenciaEnfocada("#caja_nu","#usuario-primer_nombre","Introdúzca el Primer Nombre del Usuario. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#usuario-primer_apellido",3,12) || !CampoVacio("#usuario-primer_apellido") || !unaPalabra("#usuario-primer_apellido")){
            advertenciaEnfocada("#caja_au","#usuario-primer_apellido","Introdúzca el Primer Apellido del Usuario. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#usuario-cedula",patron_cedula) || !CampoVacio("#usuario-cedula")){
            advertenciaEnfocada("#caja_cu","#usuario-cedula","No debe dejarlo vacio. Formato: V-12345678. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#usuario-correo",patron_correo) || !CampoVacio("#usuario-correo")){
            advertenciaEnfocada("#caja_cou","#usuario-correo","Formato Incorrecto, Ingrese Valores. Ej: ejemplo@email.com ¡ES OBLIGATORIO!",1);
        }else if(!validarRadio(".sexo_usuario")){
            advertenciaEnfocada("#caja_su",".sexo_usuario","DEBE ELEJIR EL SEXO. ¡ES OBLIGATORIO!",1);
        }else{
            let sexo = obtener_valor_radio(".sexo_usuario");
            datos = {
                criterio : "registrar",
                cedula : $("#usuario-cedula").val(),
                correo : $("#usuario-correo").val(),
                nombre : $("#usuario-primer_nombre").val(),
                apellido : $("#usuario-primer_apellido").val(),
                sexo
            };
            $.ajax({
                url: "modulos/user_root/control_usuario_admin.php",
                type: "GET",
                dataType: 'json',
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    main_home_root(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    main_nuevo_usuario();
                    ocultarAlerta(0.001);
                },4999);
            });
        }
    });// AQUI TERMINA EL EVENTO DEL BOTON DE GUARDADO
});

/*VALIDACIÓN DEL FORMULARIO DE REGISTRO DEL ESTUDIANTE*/
function validacion_nuevo_usuario(){
    //validacion para primer nombre
    Validaciones(4,"#usuario-primer_nombre","#caja_nu","Introdúzca el Primer Nombre del Usuario. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#usuario-primer_nombre");

    //validacion para primer apellido
    Validaciones(4,"#usuario-primer_apellido","#caja_au","Introdúzca el Primer Apellido del Usuario. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#usuario-primer_apellido");

    //validacion de la cedula
    PosicionCaracterCampo("#usuario-cedula","-",1);
    mayusculasCampo("#usuario-cedula");
    numerosDeCedula("#usuario-cedula");
    Validaciones(2,"#usuario-cedula","#caja_cu","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",patron_cedula);

    Validaciones(2,"#usuario-correo","#caja_cou","Formato Incorrecto, Ingrese Valores. Ej: ejemplo@email.com ¡ES OBLIGATORIO!",patron_correo);
}
/*TERMINA LA VALIDACIÓN DEL FORMULARIO*/
