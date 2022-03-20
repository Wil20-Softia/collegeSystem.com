//************ TITULO DE LA SECCION
let titulo_actualizacion_usuario = `Configuración. Actualizar Datos de Usuario`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_actualizacion_usuario = `
    <div class="r-hm"></div>
    <div class="column-m-12 r-hm"></div>
    <div class="column-11-12 r-h11">
        <div class="r-h12 bg-dark borde-caja-moderado text-light">
            <div class="column-6-12 borde-caja-moderado pd-ssm bd-de bd-2p bd-blanco">
                <div class="r-h1 txt-bold-men pd-btigual texto-centrado">
                    Actualización de Datos
                </div>

                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div id="caja_cu" class="column-6-12 pd-ssm">
                        <input type="text" id="usuario-cedula" class="wh-completo txt-bold-xl texto-centrado pd-btigual desactivar">
                    </div>
                    <div id="caja_cou" class="column-6-12 pd-ssm">
                        <input type="text" id="usuario-correo" class="wh-completo txt-bold-men texto-centrado pd-btigual desactivar">
                    </div>
                </div>

                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div id="caja_nu" class="column-6-12 pd-ssm">
                        <input type="text" id="usuario-primer_nombre" class="wh-completo txt-bold-xl texto-centrado pd-btigual">
                    </div>
                    <div id="caja_au" class="column-6-12 pd-ssm">
                        <input type="text" id="usuario-primer_apellido" class="wh-completo txt-bold-xl texto-centrado pd-btigual">
                    </div>
                </div>

                <div class="r-h1"></div>
                <div id="caja_su" class="r-h1m">
                    <div class="column-1m-12"></div>
                    <div class="column-4-12"> 
                        <div class="column-4-12 txt-bold-men">
                            <input id="mas" class="option-input-completo radio sexo_usuario" name="sexo_usuario" type="radio" value="MASCULINO">
                        </div>
                        <label class="column-8-12 pd-btigual-mitad txt-light-pq" for="mas">Masculino</label>
                    </div>
                    <div class="column-m-12"></div>
                    <div class="column-4-12"> 
                        <div class="column-4-12 txt-bold-men">
                            <input id="fem" class="option-input-completo radio sexo_usuario" name="sexo_usuario" type="radio" value="FEMENINO">
                        </div>
                        <label class="column-8-12 pd-btigual-mitad txt-light-pq" for="fem">Femenino</label>
                    </div>
                </div>

                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div class="column-4-12"></div>
                    <div class="column-7-12">
                        <button type="button" id="btn_up_data_admin" class="btn-azul txt-bold-pq pd-ssm"><i class="fas fa-user-edit"></i> Actualizar Datos</button>
                    </div>
                </div>
            </div>
            <div class="column-6-12 borde-caja-moderado pd-ssm bd-de bd-2p bd-blanco">
                <div class="r-h1 txt-bold-men pd-btigual texto-centrado">
                    Cambio de Contraseña
                </div>

                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div class="column-6-12 txt-light-men pd-btigual">
                        Contraseña Actual:
                    </div>
                    <div id="caja_pass_actual" class="column-6-12 pd-ssm">
                        <input type="password" id="pass_actual" class="wh-completo txt-bold-pq texto-centrado pd-btigual">
                    </div>
                </div>

                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div class="column-6-12 txt-light-men pd-btigual">
                        Contraseña Nueva:
                    </div>
                    <div id="caja_pass_nueva" class="column-6-12 pd-ssm">
                        <input type="password" id="pass_nueva" class="pass1 wh-completo txt-bold-pq texto-centrado pd-btigual desactivar" size="14">
                    </div>
                </div>

                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div class="column-6-12 txt-light-men pd-btigual">
                        Verificación Contraseña:
                    </div>
                    <div id="caja_pass1" class="column-6-12 pd-ssm">
                        <input type="password" id="pass1" class="wh-completo txt-bold-pq texto-centrado pd-btigual desactivar">
                    </div>
                </div>

                <div class="r-h1"></div>
                <div class="r-h1m">
                    <div class="column-4-12"></div>
                    <div class="column-7-12">
                        <button type="button" id="btn_up_pass_admin" class="btn-azul txt-bold-pq pd-ssm desactivar"><i class="far fa-edit"></i> Actualizar Contraseña</button>
                    </div>
                </div>
            </div> 
        </div>
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_actualizacion_usuario(){
    window.document.title = 'Sistema. Conf. Usuario';
    $("#titulo_seccion").html(titulo_actualizacion_usuario);
    $(".sec_din_prin").html(plantilla_actualizacion_usuario);
    obtenerDatos({opcion:"usuario_admin_datos"}, renderizarDatos, "modulos/obtenerDatos.php");
    validacion_nuevo_usuario(); //FUNCION QUE VALIDA LOS CAMPOS DEL FORMULARIO DE DATOS
    validar_password_up_usuario(); //FUNCION QUE VALIDA LOS CAMPOS DE PASSWORD
    comparacionPassword("pass1", "#btn_up_pass_admin");
    verificacionPassword("#pass_actual","#caja_pass_actual","#pass_nueva","#pass1");
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

    //CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
    $(document).on("click","#config",function(){
        main_actualizacion_usuario();
    });

    $(document).on("click","#btn_up_data_admin",function(e){
        e.preventDefault();
        if(!LongitudCampo("#usuario-primer_nombre",3,12) || !CampoVacio("#usuario-primer_nombre") || !unaPalabra("#usuario-primer_nombre")){
            advertenciaEnfocada("#caja_nu","#usuario-primer_nombre","Introdúzca el Primer Nombre del Usuario. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#usuario-primer_apellido",3,12) || !CampoVacio("#usuario-primer_apellido") || !unaPalabra("#usuario-primer_apellido")){
            advertenciaEnfocada("#caja_au","#usuario-primer_apellido","Introdúzca el Primer Apellido del Usuario. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#usuario-cedula",patron_cedula) || !CampoVacio("#usuario-cedula")){
            advertenciaEnfocada("#caja_cu","#usuario-cedula","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#usuario-correo",patron_correo) || !CampoVacio("#usuario-correo")){
            advertenciaEnfocada("#caja_cou","#usuario-correo","Formato Incorrecto, Ingrese Valores. Ej: ejemplo@email.com ¡ES OBLIGATORIO!",1);
        }else if(!validarRadio(".sexo_usuario")){
            advertenciaEnfocada("#caja_su",".sexo_usuario","DEBE ELEJIR EL SEXO. ¡ES OBLIGATORIO!",1);
        }else{
            let sexo = obtener_valor_radio(".sexo_usuario");
            datos = {
                criterio : "datos",
                cedula : $("#usuario-cedula").val(),
                correo : $("#usuario-correo").val(),
                nombre : $("#usuario-primer_nombre").val(),
                apellido : $("#usuario-primer_apellido").val(),
                sexo
            };
            $.ajax({
                url: "modulos/actualizacion_usuario.php",
                type: "GET",
                dataType: 'json',
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    if(response.tipo == 1){
                        main_home_root(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                    }else{
                        main_home(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                    }
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    ocultarAlerta(0.001);
                },4999);
            });
        }
    });// AQUI TERMINA EL EVENTO DEL BOTON DE ACTUALIZAR DATOS

    $(document).on("click","#btn_up_pass_admin",function(e){
        e.preventDefault();
        let pn = $("#pass_nueva").val();
        let pv = $("#pass1").val();

        if(!CampoVacio("#pass_actual")){
            advertenciaEnfocada("#caja_pass_actual","#pass_actual","¡Campo Obligatorio!",1);
        }else if(!CampoVacio("#pass_nueva")){
            advertenciaEnfocada("#caja_pass_nueva","#pass_nueva","¡Campo Obligatorio!",1);
        }else if(!CampoVacio("#pass1")){
            advertenciaEnfocada("#caja_pass1","#pass1","¡Campo Obligatorio!",1);
        }else if(pv != pn){
            advertenciaEnfocada("#caja_pass1","#pass1","Las Contraseñas son diferentes",1);
        }else{
            datos = {
                criterio : "password",
                pass_vieja : $("#pass_actual").val(),
                pass_nueva : pv
            };
            $.ajax({
                url: "modulos/actualizacion_usuario.php",
                type: "GET",
                dataType: 'json',
                data: datos
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    if(response.tipo == 1){
                        main_home_root(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                    }else{
                        main_home(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                    }
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    ocultarAlerta(0.001);
                },4999);
            });
        }
    });// AQUI TERMINA EL EVENTO DEL BOTON DE ACTUALIZAR CONTRASEÑA
});

function validar_password_up_usuario(){
    Validaciones(4,"#pass_nueva","#caja_pass_nueva","La Contraseña debe tener como minimo 8 Caracteres!","",8,20);
}

function comparacionPassword(puntero, boton){
    $(document).on("keyup","#"+puntero,function(){
        let pass_nueva = $("."+puntero).val();
        if($(this).val() == pass_nueva){
            quitar_advertencia("#caja_"+puntero);
            $(boton).removeClass("desactivar");
        }else{
            $(boton).addClass("desactivar");
        }
    });
}

function verificacionPassword(campo,contenedor,pn,pv){
    $(document).on("keyup",campo,function(){
        let valor = $(this).val();
        $.ajax({
            url: "modulos/basic/verificacion_password.php",
            type: "GET",
            dataType: 'json',
            data: {pass:valor}
        })
        .done(function(response){
           $(pn).removeClass('desactivar');
           $(pv).removeClass('desactivar');
        })
        .fail(function(response){
            $(pn).addClass('desactivar');
            $(pv).addClass('desactivar');
            advertenciaEnfocada(contenedor,campo,response.responseText);
            setTimeout(function(){
                quitar_advertencia(contenedor);
            },3999);
        });
    });
}