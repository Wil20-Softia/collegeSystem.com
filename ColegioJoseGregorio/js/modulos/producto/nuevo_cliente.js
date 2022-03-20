//************ TITULO DE LA SECCION
let titulo_nuevo_cliente = "Registro del Cliente, " + fecha_actual;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_nuevo_cliente = `
    <div class="r-h1"></div>
    <div class="column-4-12 r-hm"></div>
    <form class="column-4-12 r-h10 text-light borde-caja-moderado fondo-azul-celeste sombra-negra-caja-completa sombra-letra-negra">
        <div class="r-h1 texto-centrado txt-bold-men">
            Datos del Cliente
        </div>

        <div class="r-h1">
            <div class="column-6-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Cedula
            </div>
        </div>
        <div id="caja-ced-cli" class="r-h1 pd-ssm">
            <input type="text" id="cedula_cliente_registro" class="wh-completo input-transparente-blanco desactivar">
        </div>

        <div class="r-h1">
            <div class="column-6-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Nombre
            </div>
            <div class="column-6-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Apellido
            </div>
        </div>
        <div class="r-h1">
            <div id="caja-nom-cli" class="column-6-12 pd-ssm">
                <input type="text" id="nombre_cliente_registro" class="wh-completo input-transparente-blanco">
            </div>
            <div id="caja-ape-cli" class="column-6-12 pd-ssm">
                <input type="text" id="apellido_cliente_registro" class="wh-completo input-transparente-blanco">
            </div>
        </div>
                
        <div class="r-h1">
            <div class="column-8-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Dirección de Habitación
            </div>
        </div>
        <div id="caja-dir-cli" class="r-h2 mar-arr pd-ssm">
            <textarea id="direccion_cliente_registro" class="wh-completo textarea-transparente-blanco" name="" id="" cols="30" rows="10"></textarea>
        </div>
                
        <div class="r-h1">
            <div class="column-8-12 pd-ssm txt-bold-pq pd-btigual-mitad">
                Número Telefonico
            </div>
        </div>
        <div id="caja-tel-cli" class="r-h1 mar-arr pd-ssm">
            <input type="text" id="telefono_cliente_registro" class="wh-completo input-transparente-blanco">
        </div>

        <div class="r-h1 mar-arr-2p">
            <div class="column-4-12"></div>
            <div class="column-5-12">
                <button type="submit" id="boton_registrar_cliente" class="btn-blanco btn-pq sombra-negra-caja-completa"><i class="far fa-save"></i> Guardar Cliente</button>
            </div>
        </div>
    </form>
`;

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_nuevo_cliente(cedula){
    window.document.title = 'Sistema. Registro del Cliente';    
    $("#titulo_seccion").html(titulo_nuevo_cliente);
    $("#seccion_dinamica_principal").html(plantilla_nuevo_cliente);
    $("#cedula_cliente_registro").val(cedula);
    validacion_nuevo_cliente();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){
    $(document).on("click","#boton_registrar_cliente",function(e){
        e.preventDefault();
        if(!LongitudCampo("#nombre_cliente_registro",3,12) || !CampoVacio("#nombre_cliente_registro") || !unaPalabra("#nombre_cliente_registro")){
            advertenciaEnfocada("#caja-nom-cli","#nombre_cliente_registro","Introduzca el Nombre del Cliente. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#apellido_cliente_registro",3,12) || !CampoVacio("#apellido_cliente_registro") || !unaPalabra("#apellido_cliente_registro")){
            advertenciaEnfocada("#caja-ape-cli","#apellido_cliente_registro","Introduzca el Apellido del Cliente. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#cedula_cliente_registro",patron_cedula) || !CampoVacio("#cedula_cliente_registro")){
            advertenciaEnfocada("#caja-ced-cli","#cedula_cliente_registro","No debe dejarlo vacio. Formato: [V|E]-12345678. ¡ES OBLIGATORIO!",1);
        }else if(!LongitudCampo("#direccion_cliente_registro",8,70) || !CampoVacio("#direccion_cliente_registro")){
            advertenciaEnfocada("#caja-dir-cli","#direccion_cliente_registro","Introduzca la Dirección Basica del Cliente. Maximo 70 caracteres, Minimo 8. ¡ES OBLIGATORIO!",1);
        }else if(!CampoPatron("#telefono_cliente_registro",patron_telefono) || !CampoVacio("#telefono_cliente_registro")){
            advertenciaEnfocada("#caja-tel-cli","#telefono_cliente_registro","Formato Incorrecto, Ingrese Valores. Ej: 0426-6638765. ¡ES OBLIGATORIO!",1);
        }else{
            var ced_cliente = $("#cedula_cliente_registro").val();
            let data_cliente = {
                cliente_nombre : $("#nombre_cliente_registro").val(),
                cliente_apellido : $("#apellido_cliente_registro").val(),
                cliente_cedula : ced_cliente,
                cliente_direccion : $("#direccion_cliente_registro").val(),
                cliente_telefono : $("#telefono_cliente_registro").val()
            };

            $.ajax({
                url: "modulos/producto/cliente_registrar.php",
                type: "POST",
                dataType: 'json',
                data: data_cliente,
                beforeSend: function(){
                    $(".cargando").css('display', 'block');
                }
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    main_facturacion_productos(ced_cliente);
                    ocultarAlerta(0.001);
                },1999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    main_home_productos(datos_usuario.id_usuario,datos_usuario.nombre_usuario, datos_usuario.logo_usuario, datos_usuario.ultima_sesion, datos_usuario.identificador_usuario);
                    ocultarAlerta(0.001);
                },3999);
            })
            .always(function(){
              $(".cargando").css('display', 'none');
            });
        }
    });
});

/*VALIDACIÓN DEL FORMULARIO DE REGISTRO DEL CLIENTE*/
function validacion_nuevo_cliente(){
    //validacion para nombre cliente.
    Validaciones(4,"#nombre_cliente_registro","#caja-nom-cli","Introduzca el Nombre del Cliente. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#nombre_cliente_registro");

    //validacion para apellido Cliente.
    Validaciones(4,"#apellido_cliente_registro","#caja-ape-cli","Introduzca el Apellido del Cliente. Maximo 12 caracteres, Minimo 3. ¡ES OBLIGATORIO!","",3,12);
    mayusculasCampo("#apellido_cliente_registro");

    //validacion de la direccion del cliente.
    Validaciones(4,"#direccion_cliente_registro","#caja-dir-cli","Introduzca la Dirección Basica del Cliente. Maximo 70 caracteres, Minimo 8. ¡ES OBLIGATORIO!","",8,70);

    //validacion de la cedula del cliente.
    PosicionCaracterCampo("#cedula_cliente_registro","-",1);
    mayusculasCampo("#cedula_cliente_registro");
    numerosDeCedula("#cedula_cliente_registro");
    Validaciones(2,"#cedula_cliente_registro","#caja-ced-cli","No debe dejarlo vacio. Formato: [V | E]-12345678. ¡ES OBLIGATORIO!",patron_cedula);

    //validacion para telefono del cliente.
    Validaciones(2,"#telefono_cliente_registro","#caja-tel-cli","Formato Incorrecto, Ingrese Valores. Ej: 0426-6638765. ¡ES OBLIGATORIO!",patron_telefono);
    soloNumeros("#telefono_cliente_registro");
    PosicionCaracterCampo("#telefono_cliente_registro","-",4);
}
/*TERMINA LA VALIDACIÓN DEL FORMULARIO*/