let titulo_estudiantes_espera = `
    <div class="column-8-12 mar-der">Estudiantes En Espera</div>
    <div class="caja-busqueda column-3-12 pd-btigual">
        <input type="text" id="busqueda_estudiantes_espera" class="wh-completo" placeholder="Cedula o Nombre"/>
    </div>
    <div class="column-m-12 pd-btigual">
        <button type="submit" id="btn-busqueda_estudiantes_espera" class="btn-pq btn-azul txt-light-spq"><i class="fas fa-search"></i></button>
    </div>`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_estudiantes_espera = `
	<div class="wh-completo">
        <div class="r-h12 pd-sm">
            <div class="wh-completo bd-1p-bln sombra-gris-caja-completa fondo-gris-azulado">
                <div class="r-h1 fondo-azul-blanco">
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual  bd-blanco">Cedula</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Nombre</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Apellido</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Grado Actual</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz bd-de  bd-blanco">Sección Actual</div>
                    <div class="column-2-12">
						<div class="r-h6 txt-ppq texto-centrado">Periodo Actual</div>
						<div id="periodo_escolar_actual" class="r-h6 txt-ppq texto-centrado">2019 - 2020</div>
                    </div>
                </div>
                <div id="listado_estudiantes_espera" class="r-h11 scrolleable fondo-blanco-transparente fila-int-azul-gris fila-hover-blan">                    
                </div>
            </div>
        </div> 
    </div>
`;

function main_estudiantes_espera(){
    seccion_estudiantes = main_estudiantes_espera;
	window.document.title = 'Sistema. Estudiantes en Espera';
	$("#titulo_seccion").html(titulo_estudiantes_espera);
	$("#seccion_dinamica_principal").html(plantilla_estudiantes_espera);
	listado_estudiantes_espera();
}

$(document).ready(function(){
	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#control_estudiantes_espera",main_estudiantes_espera);

	$(document).on("click","#btn-busqueda_estudiantes_espera",function(e){
        e.preventDefault();
        let valor = $("#busqueda_estudiantes_espera").val();
        listado_estudiantes_espera('busqueda',valor);
    });
});

function listado_estudiantes_espera(criterio = 'listado_completo', filtro = ''){
    $.ajax({
        async: false,
        url: 'modulos/estudiantes_espera.php',
        type: 'GET',
        dataType: "json",
        data: {
        	criterio : criterio,
        	busqueda : filtro
        }
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
        template += `
            <div class="r-h1m pd-btigual">
	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
	            	${datos.cedula}
	            </div>

	         	<div class="column-2-12 texto-centrado txt-bold-pq pd-btigual">
	                ${datos.nombre}
	            </div>
	                    
	            <div class="column-2-12 texto-centrado txt-bold-pq pd-btigual">
	                ${datos.apellido}
	            </div>

	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                ${datos.grado}
	            </div>

	            <div class="column-2-12 texto-centrado txt-bold-pq pd-btigual">
		            "${datos.seccion}"
	            </div>

				<div class="column-1-12 texto-centrado txt-bold-pq pd-btigual">
	                <a href="#" title="Inscribir al Estudiante" id="${datos.id_tipo_estudiante}" class="btn btn-success txt-pq pd-ssm cancelar_inscripcion">
	                   	<i class="fas fa-upload"></i>
	                </a>
	            </div>

	            <div class="column-1-12 texto-centrado txt-bold-pq pd-btigual">
	                <a href="#" title="Eliminar al Estudiante" id="${datos.cedula}" te="${datos.id_tipo_estudiante}" class="btn btn-danger txt-pq pd-ssm deshabilitar_estudiante">
	                   	<i class="fas fa-trash-alt"></i>
	                </a>
	            </div>        
            </div>
          	`
        });
        $("#listado_estudiantes_espera").html(template);
    })
    .fail(function(response) {
        $("#listado_estudiantes_espera").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}