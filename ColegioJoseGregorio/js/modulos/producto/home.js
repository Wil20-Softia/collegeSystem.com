//************ TITULO DE LA SECCION
let titulo_home_productos = "Principal Productos, " + fecha_actual;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_home_productos = `
    <div class="r-h1 fondo-degradado-vertical-azulblanco">
        <div class="column-2-12">
            <div class="column-3-12 mar-der">
                <div class="r-h1"></div>
                <a href="#" title="Principal de Control Estudiantil" id="home" class="r-h10 txt-bold-men pd-btigual texto-centrado texto-vinotinto nav-link">
                    <i class="fas fa-user-graduate"></i>
                </a>
            </div>
            <div class="column-5-12 mar-der">
                <img src="img/logo.png" class="rounded-circle wh-completo">
            </div>
            <div class="column-3-12">
                <div class="r-h1"></div>
                <a href="#" title="Principal de Productos e Inventario" id="home_productos" class="r-h10 txt-bold-men pd-btigual texto-centrado texto-vinotinto nav-link">
                    <i class="fas fa-pallet"></i>
                </a>
            </div>
        </div>
        <div class="column-10-12">
            <div class="column-8-12 text-dark">
                <div id="titulo_seccion" class="mar-izq mar-arr-2p texto-titulo-secciones">Principal Productos</div>
            </div>
            <div class="column-4-12 text-light">
                <div class="btn-group column-2-12 mar-der">
                    <a href="#" id="user_config" class="wh-completo dropdown-toggle-split" data-toggle="dropdown">
                        <img id="logo_usuario" class="rounded-circle wh-completo">
                    </a>
                    <div class="dropdown-menu bg-secondary">
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
    </div>

    <div class="r-h1">
        <div class="column-2-12"></div>
        <div class="column-10-12">
            <div class="column-m-12"></div>
            <div class="column-2-12 pd-btigual texto-centrado mar-der">
                <div class="r-h1"></div>
                <a href="#" id="nuevo_producto" class="r-h10 pd-btigual-mitad txt-bold-spq sombra-negra-caja fondo-verde-blanco hover-blanco-verde active-verde-blanco">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>
            <div class="column-2-12 pd-btigual texto-centrado mar-der">
                <div class="r-h1"></div>
                <a href="#" id="inventario_producto" class="r-h10 pd-btigual-mitad txt-bold-spq sombra-negra-caja fondo-naranja-blanco hover-blanco-naranja active-naranja-blanco">
                    <i class="fas fa-boxes"></i> Inventario
                </a>
            </div>
            <div class="column-2-12 pd-btigual texto-centrado mar-der">
                <div class="r-h1"></div>
                <a href="#" id="facturaciones_producto" class="r-h10 pd-btigual-mitad txt-bold-spq sombra-negra-caja fondo-naranja-blanco hover-blanco-naranja active-naranja-blanco">
                    <i class="fas fa-file-invoice-dollar"></i> Ventas
                </a>
            </div>
            <div class="column-2-12 pd-btigual texto-centrado">
                <div class="r-h1"></div>
                <a href="#" id="configuraciones_producto" class="r-h10 pd-btigual-mitad txt-bold-spq sombra-negra-caja fondo-azul-blanco hover-blanco-azul active-azul-blanco">
                    <i class="fas fa-cog"></i> Configuraciones
                </a>
            </div>
        </div>
    </div>

    <div id="seccion_dinamica_principal" class="r-h10">
        <!--SECCION DE IMPRESION DINAMICA.-->
                
        <!--TERMINA LA SECCION DE IMPRESION DINAMICA.-->
    </div>
`;

const plantilla_contenido_home_productos = `
    <div class="wh-completo">
        <div class="r-h3"></div>
        <div class="r-h1 txt-light-men texto-centrado text-dark pd-btigual">
            Busqueda del Cliente para realizar Venta
        </div>
        <form class="r-h1">
            <div class="column-3m-12"></div>
            <div class="column-2-12 txt-bold-pq texto-centrado text-dark pd-btigual-mitad">
                Introduzca la Cedula
            </div>
            <div id="caja_cedCliente" class="column-2-12 pd-btigual">
                <input type="text" id="cedula_cliente" class="wh-completo" placeholder="V|E|X-00011122">
            </div>
            <div class="column-1-12 pd-btigual texto-centrado">
                <div class="r-h1"></div>
                <button type="submit" id="busqueda_cliente" class="btn-pq btn-azul txt-light-spq">
                    <i class="fas fa-search"></i> Ejecutar
                </button>
            </div>
        </form>
    </div>
`;

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_home_productos(id_usuario, nombre_admin = "", icon_admin = "", ultima_sesion = "", identificador_admin = ""){
    window.document.title = 'Sistema. Productos e Inventario';
    $("#pantallas").html(plantilla_home_productos);
    $("#pantallas").addClass('fondo-degradado-grisblanco bd-completo bd-2p bd-gris');

    $("#nombre_completo_usuario").html(nombre_admin);
    $("#logo_usuario").attr('src', icon_admin);
    $("#ultima_sesion").html(ultima_sesion);
    $("#identificador_administrador").html(identificador_admin);
    
    $("#titulo_seccion").html(titulo_home_productos);
    $("#seccion_dinamica_principal").html(plantilla_contenido_home_productos);

    PosicionCaracterCampo("#cedula_cliente","-",1);
    mayusculasCampo("#cedula_cliente");
    numerosDeCedulaX("#cedula_cliente");
    Validaciones(2,"#cedula_cliente","#caja_cedCliente","No debe dejarlo vacio. Formato: [V | E | X]-12345678. ¡ES OBLIGATORIO!",patron_cedula_estudiante);
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){

    $(document).on("click","#busqueda_cliente",function(e){
        e.preventDefault();
        if(!CampoPatron("#cedula_cliente",patron_cedula_estudiante) || !CampoVacio("#cedula_cliente")){
            advertenciaEnfocada("#caja_cedCliente","#cedula_cliente","No debe dejarlo vacio. Formato: [V | E | X]-12345678. ¡ES OBLIGATORIO!",1);
        }else{
            var valor_cedula = $("#cedula_cliente").val();
            $(".close").trigger('click');
            $(".cargando").css('display', 'block');
            $.ajax({
                async: false,
                url: 'modulos/producto/busqueda_cliente.php',
                dataType : 'json',
                type: 'GET',
                data: {
                    cedula_cliente : valor_cedula
                }
            })
            .done(function(response){
                setTimeout(() => {
                    main_facturacion_productos(valor_cedula);
                    $(".cargando").css('display', 'none');
                }, 1000);
            })
            .fail(function(response){
                setTimeout(() => {
                    main_nuevo_cliente(valor_cedula);
                    $(".cargando").css('display', 'none');
                }, 1000);
            });
        }
    });

    //CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
    $(document).on("click","#home_productos",function(){
        //CONSULTA AJAX TOMANDO LOS VALORES DE LAS VARIABLES SESSION EN PHP PARA PASARLAS POR
        //PARAMETRO.
        $(".close").trigger('click');
        $(".cargando").css('display', 'block');
        $.ajax({
            async: false,
            url: 'modulos/index.php',
            dataType : 'json',
            type: 'GET'
        })
        .done(function(response){
            setTimeout(() => {
                datos_usuario = {
                    "id_usuario" : response.id,
                    "nombre_usuario" : response.nombre,
                    "logo_usuario" : response.logo,
                    "ultima_sesion" : response.ultima_sesion,
                    "identificador_usuario" : response.identificador
                }
                main_home_productos(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                $(".cargando").css('display', 'none');
            }, 1000);
        })
        .fail(function(response){
            setTimeout(() => {
                logout();
                main_login();
                $(".cargando").css('display', 'none');
            }, 1000);
        });
    });
});