//************ TITULO DE LA SECCION
let titulo_login = "Sistema. Login";

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_login = `
    <div id="seccion_login" class="wh-completo">
        <div class="column-2m-12 r-h1"></div>
        <div class="column-6-12 r-h1 bg-light-transparente sombra-blanca-caja-completa bordes-redondeados-abajo-30">
            <img src="img/logo.png" alt="Escudo" class="column-1m-12 rounded-circle opaco-moderado mar-izq"/>
            <div class="column-10-12 txt-bold-men texto-centrado texto-vinotinto pd-btigual">
                Colegio Privado Dr. "José Gregorio Hernández"
            </div>
        </div>
        <div class="r-h1"></div>
        <div class="column-4-12 r-hm"></div>
        <form class="column-3m-12 r-h8 mar-auto bg-light-transparente bd-completo bd-azul-claro borde-caja-moderado sombra-blanca-caja-completa bd-2p pd-ssm">
            <div class="r-h1m bd-ab bd-azul-claro">
                <div class="column-2-12 txt-bold-xl pd-btigual texto-centrado texto_rojo">
                    <i class="fas fa-user-lock"></i>
                </div>
                <div class="column-8-12 texto-centrado pd-btigual-mitad txt-bold-men texto-azul-claro">
                    Iniciar Sesión
                </div>
                <div class="column-2-12 txt-bold-xl pd-btigual texto-centrado texto_rojo">
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <div class="r-h1"></div>

            <div class="r-h1 pd-btigual txt-bold-pq texto-vinotinto">Usuario</div>
            <div class="r-h1m pd-ssm">
                <div class="column-12-12 bd-completo bd-2p bd-azul-claro">
                    <div class="column-2-12 txt-bold-men texto-azul-claro texto-centrado">
                        <i class="far fa-user"></i>
                    </div>
                    <div id="caja_login_usuario" class="column-10-12">
                        <input type="text" class="wh-completo txt-light-pq bd-null texto-centrado" id="login_usuario" autocomplete="off"/>
                    </div>
                </div>
            </div>

            <div class="r-hm"></div>

            <div class="r-h1">
                <div class="column-3-12 pd-btigual txt-bold-pq texto-vinotinto">Contraseña</div>
                <div class="column-7-12"></div>
                <a id="ver_pass" class="column-1m-12 txt-bold-men text-dark">
                    <i class="fas fa-eye-slash"></i>
                </a>
            </div>
            <div class="r-h1m pd-ssm">
                <div class="column-12-12 bd-completo bd-2p bd-azul-claro">
                    <div class="column-2-12 txt-bold-men texto-azul-claro texto-centrado">
                        <i class="fas fa-key"></i>
                    </div>
                    <div id="caja_password_usuario" class="column-10-12">
                        <input type="password" class="wh-completo texto-centrado bd-null" id="password_usuario"/>
                    </div>
                </div>
            </div>

            <div class="r-h2"></div>

            <div class="r-h2 texto-derecho pd-ssm">
            	<button type="submit" id="boton_ingresar_sistema" class="btn btn-outline-primary txt-light-pq"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
            </div>
        </form>
		<div class="r-h1"></div>
        <div class="r-h1 pd-izqtop-men">
			<botton type="button" id="restaurar" class="btn btn-warning txt-light-pq" data-toggle="modal" data-target="#exampleModal">
				<i class="fas fa-cloud-upload-alt"></i> Restaurar Sistema
			</botton>
			<!-- Formulario de la Restauracion del sistema -->
	        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                  	<div class="modal-header">
	                    	<h5 class="modal-title" id="exampleModalLabel">Restauración de Sistema</h5>
	                    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                      		<span aria-hidden="true">&times;</span>
	                    	</button>
	                  	</div>
	                  	<form id="formulario_restaurar" method="POST" enctype="multipart/form-data" autocomplete="off">
	                      	<div class="modal-body">
	                            <div class="uploader">
	                                <div id="inputval" class="input-value"></div>
	                                <label for="file_restaurar"></label>
	                                <input id="file_restaurar" class="upload" type="file" name="file_restaurar" accept=".gz">
	                            </div>
	                      	</div>
	                      	<div class="modal-footer">
	                        	<a type="button" id="btn-cerrar" class="btn btn-secondary" data-dismiss="modal">Cerrar</a>
	                        	<button type="submit" id="btn-subir-restauracion" class="btn btn-primary">Restaurar</button>
	                      	</div>
	                  	</form>
	                </div>
	            </div>
	        </div>
	        <!-- FIN.... Formulario de la Restauracion del sistema -->
        </div>
    </div>
`;

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_login(){
    window.document.title = titulo_login;
    $("#pantallas").html(plantilla_login);
    Validaciones(2,"#login_usuario","#caja_login_usuario","Es Obligatorio! ejemplo@correo.com",patron_correo);
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {
    $(document).on("click","#boton_ingresar_sistema",function(e){
        e.preventDefault();
        if(!CampoPatron("#login_usuario",patron_correo) || !CampoVacio("#login_usuario")){
            advertenciaEnfocada("#caja_login_usuario","#login_usuario","Es Obligatorio!",1);
        }else if(!CampoVacio("#password_usuario")){
            advertenciaEnfocada("#caja_password_usuario","#password_usuario","Introduzca la Contraseña!",1);
        }else{
            let usuario = $("#login_usuario").val();
            let pass = $("#password_usuario").val();

            $("#login_usuario").val("");
            $("#password_usuario").val("");

            let datos = {
                usuario,
                pass
            };

            $.ajax({
                url: 'modulos/login.php',
                type: 'GET',
                dataType: "json",
                data: datos,
                beforeSend: function(){
                    $(".cargando").css('display', 'block');
                }
            })
            .done(function(response){
                session_abierta = 1;
                if(response.tipo == 1){
                    main_home_root(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                }else{
                    main_home(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
                }
            })
            .fail(function(response) {
                alertar(3, response.responseText);
                setTimeout(function(){
                    main_login();
                    ocultarAlerta(0.001);
                },4999);
            })
            .always(function(){
                $(".cargando").css('display', 'none');
            });
        }
    });

    $(document).on("mousedown","#ver_pass",function(e){
        e.preventDefault();
        $(this).html('<i class="fas fa-eye"></i>');
        $("#password_usuario").attr('type', 'text');
    });

    $(document).on("mouseup","#ver_pass",function(e){
        e.preventDefault();
        $(this).html('<i class="fas fa-eye-slash"></i>');
        $("#password_usuario").attr('type', 'password');
    });
});