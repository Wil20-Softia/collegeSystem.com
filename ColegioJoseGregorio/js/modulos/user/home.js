//************ TITULO DE LA SECCION
let titulo_home_root = "Sistema *UserRoot. Principal";

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_home_root = `
    <div class="r-h1 fondo-vinotinto-oscuro bd-ab bd-2p bd-negro">
        <a id="home_root" href="#" class="column-1-12 mar-izq pd-ssm">
            <img src="img/logo.png" class="rounded wh-completo mar-arr">
        </a>
        <a id="home_root" href="#" class="column-5-12 pd-btigual txt-light-men text-light">
            Colegio Privado "Dr. José Gregorio Hernández"
        </a>
        <div class="column-1m-12 mar-izq"></div>
        <div class="column-4-12">
            <div class="btn-group column-2-12 mar-der">
                <a href="#" id="user_config" class="wh-completo dropdown-toggle-split" data-toggle="dropdown">
                    <img id="logo_usuario" class="rounded-circle wh-completo">
                </a>
                <div class="dropdown-menu bg-secondary">
                    <a id="config" class="dropdown-item text-white" href="#"><i class="fas fa-user-cog"></i> Configuración</a>
                    <a id="logout" class="dropdown-item text-white" href="#"><i class="fas fa-sign-out-alt"></i> Salir</a>
                </div>
            </div>
                
            <div class="mar-izq column-9-12">
                <div class="fila-h5 texto-izquierdo pd-btigual-mitad txt-arial-light-spq">
                    Ultima Sesión: <span id="ultima_sesion"></span>
                </div>
                <div class="fila-h5 texto-izquierdo pd-btigual-mitad txt-light-spq">
                    <span id="identificador_administrador"></span> <span id="nombre_completo_usuario"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="r-hm"></div>
    <div class="r-h1 pd-btigual">
        <a href="#" id="enlace_registro_usuario" title="Formulario de Registro de Usuario" class="mar-izq btn-azul txt-light-pq pd-btigual-mitad"><i class="fas fa-user-plus"></i> Registrar Usuario</a>
    </div>
    <div id="seccion_dinamica_principal_root" class="sec_din_prin r-h9m fondo-degradado-grisblanco">
        <!--SECCION DE IMPRESION DINAMICA.-->
                
        <!--TERMINA LA SECCION DE IMPRESION DINAMICA.-->
    </div>
`;

const plantilla_contenido_home_root = `
    <div class="wh-completo pd-sm">
        <div class="r-h10 sombra-gris-caja-completa">
            <div class="r-h1m bg-dark text-light bd-ab bd-blanco bd-2p texto-centrado">
                <div class="h5 column-1-12 pd-btigual-mitad">ID</div>
                <div class="h5 column-1m-12 pd-btigual-mitad">CEDULA</div>
                <div class="h5 column-2m-12 pd-btigual-mitad">CORREO</div>
                <div class="h5 column-1m-12 pd-btigual-mitad">NOMBRE</div>
                <div class="h5 column-1m-12 pd-btigual-mitad">APELLIDO</div>
                <div class="h5 column-1m-12 pd-btigual-mitad">SEXO</div>
                <div class="h5 column-1m-12 pd-btigual-mitad">REGISTRADO</div>
                <div class="h5 column-1-12 pd-btigual-mitad"></div>
            </div>
            <div id="listado_usuarios_admin" class="r-h10m texto-centrado bg-light scrolleable fila-int-gris-azulado fila-hover-blan">
                
                <!-- AQUI VA EL CONTENIDO DE LA TABLA DE LAS SECCIONES
                REGISTRADAS -->
            </div>
        </div>
    </div>
`;

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_home_root(id_usuario, nombre_admin = "", icon_admin = "", ultima_sesion = "", identificador_admin = ""){
    window.document.title = titulo_home_root;
    $("#pantallas").html(plantilla_home_root);

    $("#nombre_completo_usuario").html(nombre_admin);
    $("#logo_usuario").attr('src', icon_admin);
    $("#ultima_sesion").html(ultima_sesion);
    $("#identificador_administrador").html(identificador_admin);
    
    $("#seccion_dinamica_principal_root").html(plantilla_contenido_home_root);

    listado_usuarios_admin();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

    //CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
    $(document).on("click","#home_root",function(){
        //CONSULTA AJAX TOMANDO LOS VALORES DE LAS VARIABLES SESSION EN PHP PARA PASARLAS POR
        //PARAMETRO.
        $.ajax({
            async: false,
            url: 'modulos/index.php',
            dataType : 'json',
            type: 'GET'
        })
        .done(function(response){
            main_home_root(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
        })
        .fail(function(response) {
            logout();
            main_login();
        });
    });

    $(document).on("click",".btn-deshabilitar-usuario",function(e){
        e.preventDefault();
        if(confirm('¿Esta Seguro de querer Deshabilitar al Usuario? ¡Este Usuario No Tendra más Acceso al Sistema de Administración de Mensualidad!')){
            let id_usuario = parseInt($($(this)[0]).parent().parent().children(".id_usuario_admin").text());
            $.ajax({
                url: 'modulos/user_root/control_usuario_admin.php',
                type: 'GET',
                dataType: "json",
                data: {
                    criterio : 'deshabilitar',
                    id_usuario
                }
            })
            .done(function(response){
                alertar(response.advertencia, response.mensaje);
                setTimeout(function(){
                    listado_usuarios_admin();
                    ocultarAlerta(0.001);
                },4999);
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    listado_usuarios_admin();
                    ocultarAlerta(0.001);
                },4999);
            });
        }
    });
});

function listado_usuarios_admin(){
    $.ajax({
        async: false,
        url: 'modulos/user_root/control_usuario_admin.php',
        type: 'GET',
        dataType: "json",
        data: {criterio : 'listado'}
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
        template += `
            <div class="r-h2 bd-ab bd-blanco">
                <div class="h6 id_usuario_admin column-1-12 pd-btigual-mitad">${datos.id}</div>
                <div class="h6 column-1m-12 pd-btigual-mitad texto-centrado">${datos.cedula}</div>
                <div class="h6 column-2m-12 pd-btigual-mitad texto-centrado">${datos.correo}</div>
                <div class="h6 column-1m-12 pd-btigual-mitad texto-centrado">${datos.nombre}</div>
                <div class="h6 column-1m-12 pd-btigual-mitad texto-centrado">${datos.apellido}</div>
                <div class="h6 column-1m-12 pd-btigual-mitad texto-centrado">${datos.sexo}</div>
                <div class="h6 column-1m-12 pd-btigual-mitad texto-centrado">${datos.registrado}</div>
                <div class="column-1-12 texto-centrado txt-bold-xl texto-rojo">
                    <a href="#" class="btn-deshabilitar-usuario btn-icon-rojo" title="Deshabilitar Usuario"><i class="fas fa-user-slash"></i></a>
                </div>
            </div>
            `
        });
        $("#listado_usuarios_admin").html(template);
    })
    .fail(function(response) {
        $("#listado_usuarios_admin").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}