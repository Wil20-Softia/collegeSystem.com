let titulo_deudores_antiguos = `
    <div class="column-8-12 mar-der">Estudiantes Deudores Antiguos</div> 

    <div class="caja-busqueda column-3-12 pd-btigual">
        <input type="text" id="busqueda_deudor_antiguo" class="wh-completo" placeholder="Cedula o Nombre"/>
    </div>
    <div class="column-m-12 pd-btigual">
    	<button type="submit" id="btn-busqueda_deudor_antiguo" class="btn-pq btn-azul txt-light-spq"><i class="fas fa-search"></i></button>
    </div>`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_deudores_antiguos = `
	<div class="wh-completo">
        <div class="r-h12 pd-sm">
            <div class="wh-completo bd-1p-bln sombra-gris-caja-completa fondo-gris-azulado">
                <div class="r-h1 fondo-azul-blanco">
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual  bd-blanco">Cedula</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Nombre</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Apellido</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Tlfn. Repre.</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Meses Deuda</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz bd-de  bd-blanco">Total Bruto</div>
                    <div class="column-3-12"></div>
                </div>
                <div id="listado_deudores_antiguos" class="r-h11 scrolleable fondo-blanco-transparente fila-int-azul-gris fila-hover-blan">                    
                </div>
            </div>
        </div> 
    </div>
`;

function main_deudores_antiguos(){
	seccion_estudiantes = main_deudores_antiguos;
	window.document.title = 'Sistema. Estudiantes Deudore Antiguos';
	$("#titulo_seccion").html(titulo_deudores_antiguos);
	$("#seccion_dinamica_principal").html(plantilla_deudores_antiguos);
	listado_deudor_antiguo();
}

$(document).ready(function() {
	$(document).on("click","#control_deudores_antiguos",main_deudores_antiguos);

	$(document).on('click', '.cancelar_mensualidad_antiguo', function() {
		var tipo_e = parseInt($($(this)[0]).attr("id"));
		$(".cargando").css('display', 'block');
		$.ajax({
            async: false,
            url: "modulos/basic/verificar_mora.php",
            dataType : "json",
            type : "GET",
            data: {tipo_estudiante: tipo_e}
        })
        .done(function(response){
	        setTimeout(function(){
		        main_facturacion_normal();
		        obtenerDatos({opcion:"estudiante_factura", tipo_estudiante: tipo_e}, renderizarDatos, "modulos/obtenerDatos.php");
		        pagos_estudiante_mensualidad(tipo_e, "#contenedor_meses_factura");
		        $(".cargando").css('display', 'none');
	        },1000);
    	})
    	.fail(function(response) {
            $(".close").trigger('click');
            $(".cargando").css('display', 'none');
            alertar(3, response.responseText);
            setTimeout(function(){
                ocultarAlerta(0.001);
            },5999);
        });
	});

	$(document).on("click","#btn-busqueda_deudor_antiguo",function(e){
        e.preventDefault();
        let valor = $("#busqueda_deudor_antiguo").val();
        listado_deudor_antiguo('busqueda',valor);
    });
});

function listado_deudor_antiguo(criterio = 'listado_completo', filtro = ''){
    $.ajax({
        async: false,
        url: 'modulos/deudores_antiguos.php',
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
	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                ${datos.cedula}
	            </div>

	            <div class="column-1m-12 texto-centrado txt-bold-pq pd-btigual">
	            	<a href="#" id="${datos.cedula}" class="modificar_estudiante texto-subrayado texto-centrado text-danger" title="Modificar Datos del Estudiante">
	                       ${datos.nombre}
	                </a>
	            </div>
	                    
	            <div class="column-1m-12 texto-centrado txt-bold-pq pd-btigual">
	                ${datos.apellido}
	            </div>

	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                ${datos.telefono_repre}
	            </div>

	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
		            <a href="#" class="meses_deuda_estudiante texto-subrayado texto-centrado text-danger" title="Listado de Meses y sus Facturas" data-toggle="modal" data-target="#estudiante_${datos.id_tipo_estudiante}">
	                       ${datos.cantidad_meses_deuda}
	                </a>
	                <!-- The Modal -->
		            <div class="modal fade text-dark" id="estudiante_${datos.id_tipo_estudiante}">
		                <div class="modal-dialog modal-dialog-centered">
		                   	<div class="modal-content">
		                        <!-- Modal Header -->
		                        <div class="modal-header">
		                           	<strong class="column-10-12 texto-centrado txt-mf20">MESES EN DEUDA.</strong>
		                            <button type="button" class="close" data-dismiss="modal">&times;</button>
		                        </div> 
		                        <!-- Modal body -->
		                        <div class="modal-body">
		                            <div class="column-12-12">
										<div class="r-h1m bd-2p bd-negro bd-completo fondo-azul-verdoso">
											<div class="column-3-12 bd-2p bd-negro bd-de">MES</div>
											<div class="column-9-12">FACTURAS</div>
										</div>
										`;
										let meses_estudiante = datos.meses_estudiante;
										meses_estudiante.forEach(me => {
                    						let facturas = me.facturas_mes;
                    						if(facturas != 0){
												template += `
												<div class="r-h1m bd-2p bd-negro bd-ab">
													<div class="txt-bold-spq column-3-12 pd-btigual">${me.mes}
													</div>
													<div class="column-9-12">`;
													let tam = facturas.length;
							                        for (let p = 0; p < tam; p++){
							                            template += "<a href='#' id='"+facturas[p]+"' class='btn btn-outline-primary btn-ver-factura_normal'>"+facturas[p]+"</a>";
							                        }
												template += `
													</div>
												</div>`;
											}else{
												template += `
												<div class="r-h1m bd-2p bd-negro bd-ab">
													<div class="txt-bold-spq column-3-12 pd-btigual">
														${me.mes}
													</div>
													<div class="column-9-12">
													</div>
												</div>`;
											}
										});
						template +=`</div>
		                        </div>
		                    </div>
		                </div>
		            </div>
	            </div>
	
	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                 ${datos.total_bruto}
	            </div>`;

	            if(datos.inscribir == 1){
	            	template+=`
					<div class="column-1-12 texto-centrado txt-bold-pq pd-btigual">
	                	<a href="#" title="Cancelar Mensualidad" class="btn btn-primary txt-pq pd-ssm cancelar_mensualidad_antiguo desactivar" id="${datos.id_tipo_estudiante}">
	                   		<i class="fas fa-money-check-alt"></i>
	                	</a>
	            	</div>

	            	<div class="column-1-12 texto-centrado txt-bold-pq pd-btigual">
	                	<a href="#" title="Inscribir al Estudiante" id="${datos.id_tipo_estudiante}" class="btn btn-success txt-pq pd-ssm cancelar_inscripcion">
	                   		<i class="fas fa-upload"></i>
	                	</a>
	            	</div>`;
	            }else if(datos.inscribir == 0){
	            	template+=`
	            	<div class="column-1-12 texto-centrado txt-bold-pq pd-btigual">
	                	<a href="#" title="Cancelar Mensualidad" class="btn btn-primary txt-pq pd-ssm cancelar_mensualidad_antiguo" id="${datos.id_tipo_estudiante}">
	                   		<i class="fas fa-money-check-alt"></i>
	                	</a>
	            	</div>

	            	<div class="column-1-12 texto-centrado txt-bold-pq pd-btigual">
	                	<a href="#" title="Inscribir al Estudiante" id="${datos.id_tipo_estudiante}" class="btn btn-success txt-pq pd-ssm cancelar_inscripcion desactivar">
	                   		<i class="fas fa-upload"></i>
	                	</a>
	            	</div>`;
	            }  

                template += `
                <div class="column-1-12 texto-centrado txt-bold-pq pd-btigual">
	                <a href="#" title="Eliminar al Estudiante" id="${datos.cedula}" te="${datos.id_tipo_estudiante}" class="btn btn-danger txt-pq pd-ssm deshabilitar_estudiante">
	                   	<i class="fas fa-trash-alt"></i>
	                </a>
	            </div>  
            </div>
          	`;
        });
        $("#listado_deudores_antiguos").html(template);
    })
    .fail(function(response) {
        $("#listado_deudores_antiguos").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}