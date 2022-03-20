//************ TITULO DE LA SECCION
let titulo_home = "Inicio. Actividades Realizadas Hoy, " + fecha_actual;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_home = `
	<div id="seccion_izquierda" class="column-2-12 borde-derecho-3p-gris">
            <div class="fila-h25">
                <div class="fila-dos-tercio fondo-degradado-grisblanco">
                    <div class="column-3-12">
                        <div class="r-h3"></div>
                        <a href="#" title="Principal de Control Estudiantil" id="home" class="r-h6 txt-bold-men pd-btigual texto-centrado texto-vinotinto nav-link">
                            <i class="fas fa-user-graduate"></i>
                        </a>
                    </div>
                    <div class="column-6-12">
                        <img src="img/logo.png" class="rounded wh-completo sombra-gris-caja-completa">
                    </div>
                    <div class="column-3-12">
                        <div class="r-h3"></div>
                        <a href="#" title="Principal de Productos e Inventario" id="home_productos" class="r-h6 txt-bold-men pd-btigual texto-centrado texto-vinotinto nav-link">
                            <i class="fas fa-pallet"></i>
                        </a>
                    </div>
                </div>
                <div class="fila-tercio texto-centrado fondo-vinotinto-oscuro txt-bold-pq">
                    Colegio "Dr. José Gregorio Hernández"
                </div>
            </div>
            <div class="fila-h75 fondo-gris-claro scrolleable">
                    <ul class="nav wh-completo">
                        <li class="nav-item fila-auto btn-nvertical">
                            <a id="nuevo_ingreso" class="nav-link txt-light-pq texto-vinotinto" href="#"><i class="fas fa-user-plus"></i> Nuevo</a>
                        </li>
                        <li class="nav-item btn-nvertical">
                            <a class="nav-link collapsed dropdown-toggle txt-light-pq texto-vinotinto" href="#submenu1" data-toggle="collapse" data-target="#submenu1"><i class="fas fa-book-reader"></i> Estudiante</a>
                            <div class="collapse" id="submenu1" aria-expanded="false">
                                <ul class="flex-column pl-3 nav">
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="control_mensualidad" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#"><i class="fas fa-user-clock"></i> Mensualidades</a>
                                    </li>
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="control_inscripcion" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#submenu1sub1" data-toggle="collapse" data-target="#submenu1sub1"><i class="fas fa-chalkboard-teacher"></i> Inscripciones</a>
                                    </li>
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="control_deudores_antiguos" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#"><i class="fas fa-calendar-times"></i> Antiguos</a>
                                    </li>
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="control_estudiantes_espera" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#"><i class="fas fa-stopwatch"></i> En Espera</a>
                                    </li>
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="lista_morosos" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#submenu1sub1" data-toggle="collapse" data-target="#submenu1sub1"><i class="fas fa-file-pdf"></i> Morosos</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item fila-auto btn-nvertical">
                            <a id="control_facturas" class="nav-link txt-light-pq texto-vinotinto" href="#"><i class="fas fa-money-check-alt"></i> Control Facturas</a>
                        </li>
                        <li class="nav-item btn-nvertical">
                            <a class="nav-link collapsed dropdown-toggle txt-light-pq texto-vinotinto" href="#submenu3" data-toggle="collapse" data-target="#submenu3"><i class="fas fa-hourglass-half"></i> Período</a>
                            <div class="collapse" id="submenu3" aria-expanded="false">
                                <ul class="flex-column pl-3 nav">
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="mensualidad_mora" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#"><i class="fas fa-clock"></i> Mensualidades</a>
                                    </li>
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="cupo_inscripcion" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#"><i class="fas fa-pencil-alt"></i> Inscripciones</a>
                                    </li>
                                    <li class="nav-item fila-auto pd-btigual btn-nvertical">
                                        <a id="grados_seccion_modulo" class="nav-link py-0 txt-light-pq texto-vinotinto" href="#"><i class="fas fa-cogs"></i> Secciones</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item fila-auto btn-nvertical">
                            <a id="respaldar" class="nav-link txt-light-pq texto-vinotinto" href="#"><i class="fas fa-file-archive"></i> Respaldo</a>
                        </li>
                    </ul>
            </div>
        </div>

        <div id="seccion_principal" class="column-10-12">
            <div class="fila-h1 fondo-vinotinto-oscuro">
                <div class="column-8-12">
                    <div id="titulo_seccion" class="mar-izq mar-arr-2p txt-light-men"></div>
                </div>
                <div class="column-4-12">
                    <div class="btn-group column-2-12 mar-der">
                        <a href="#" id="user_config" class="wh-completo dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">
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
            <div id="seccion_dinamica_principal" class="sec_din_prin fila-h90 fondo-degradado-grisblanco">
                <!--SECCION DE IMPRESION DINAMICA.-->
                
                <!--TERMINA LA SECCION DE IMPRESION DINAMICA.-->
            </div>
        </div>
`;

const plantilla_contenido_home = `
    <div class="container wh-completo pd-btigual">
        <div class="r-h1"></div>
        <div class="column-1m-12 fila-h80"></div>
        <div class="fila-h80 column-9-12 sombra-gris-caja-completa">
            <div class="fila-h1 bg-dark text-light borde-abajo-2p-gris texto-centrado">
                <div class="h5 column-8-12 pd-btigual-mitad">Descripcion de Actividad</div>
                <div class="h5 column-4-12 pd-btigual-mitad">Hora realizada</div>
            </div>
            <div id="contenido_tabla1" class="fila-h90 texto-centrado bg-light scrolleable fila-hover-gris-boots fila-int-dark">

                <!-- AQUI VA EL CONTENIDO DE LA TABLA DE LAS ACTIVIDADES REALIZADAS -->

            </div>
        </div>    
    <div>
`;

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_home(id_usuario, nombre_admin = "", icon_admin = "", ultima_sesion = "", identificador_admin = ""){
    window.document.title = 'Sistema. Pagina Principal';
    $("#pantallas").html(plantilla_home);
    $("#pantallas").removeClass('fondo-degradado-grisblanco bd-completo bd-2p bd-gris');
    
    $("#nombre_completo_usuario").html(nombre_admin);
    $("#logo_usuario").attr('src', icon_admin);
    $("#ultima_sesion").html(ultima_sesion);
    $("#identificador_administrador").html(identificador_admin);
    
	$("#titulo_seccion").html(titulo_home);
	$("#seccion_dinamica_principal").html(plantilla_contenido_home);

	activadades_realizadas_usuario(id_usuario);
    onEvents();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function(){

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#home",function(){
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
                main_home(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
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


function activadades_realizadas_usuario(id_usuario){
	$.ajax({
      	url: 'modulos/activadades_realizadas_usuario.php',
      	type: 'POST',
        dataType: 'json',
      	data: {usuario: id_usuario}
    })
    .done(function(response){
        let template = "";
        response.forEach(actividad => {
            template += `
            	<div class="fila-h1 borde-abajo-2p-gris">
                    <article class="column-8-12 texto-centrado pd-btigual-mitad borde-dercho-2p-gris">${actividad.nombre}</article>
                    <article class="column-4-12 pd-btigual-mitad">${actividad.fecha}</article>
                </div>
                `
        });
        $("#contenido_tabla1").html(template);
    })
    .fail(function(response) {
        $("#contenido_tabla1").html(`<div class="avisar_nada">${response.responseText}</div>`);
    });
}