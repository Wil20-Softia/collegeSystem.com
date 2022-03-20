let titulo_control_facturacion = `Control de Facturaciones al Estudiante.`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_control_facturacion = `
	<div class="wh-completo">
		<div class="r-h1 pd-btigual">
			<div class="column-9-12">
				<div class="column-3-12 txt-bold-pq pd-btigual-mitad texto-centrado">
					Por Rango de Fechas
				</div>
				<div class="column-3-12">
					<input type="date" id="fecha_desde" class="mar-arr wh-completo"/>
				</div>
				<div class="column-m-12 txt-bold-men texto-centrado">-</div>
				<div class="column-3-12">
					<input type="date" id="fecha_hasta" class="mar-arr wh-completo"/>
				</div>
				<div class="column-2-12 mar-izq-1p">
            		<a href="#" class="btn-pq btn-verde txt-bold-pq" id="btn-busqueda-fecha"><i class="fas fa-search"></i> Ir</a>
       			</div>
			</div>
			<div class="derecha column-auto">
				<div class="caja-busqueda column-10-12">
            		<input type="text" id="busqueda_facturaciones_estudiantes" class="busqueda" placeholder="Codigo o Cedula"/>
        		</div>
        		<div class="column-3-12">
        			<button type="submit" id="btn-busqueda_facturaciones_estudiantes" class="btn-pq btn-verde txt-bold-pq"><i class="fas fa-search"></i></botton>
       			</div>
		    </div>
		</div>
		<div class="r-h1 pd-btigual">
			<a href="#" title="Obtener Reporte por Fecha en PDF" id="reporte_factura_fecha" class="btn-rojo izquierda txt-light-men mar-arr"><i class="fas fa-file-pdf"></i> Generar Reporte PDF</a>
		</div>
        <div class="r-h10 pd-sm">
            <div class="wh-completo bd-1p-bln sombra-gris-caja-completa fondo-gris-azulado">
                <div class="r-h1 fondo-azul-blanco">
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual  bd-blanco">Codigo</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Fecha</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Hora</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Tipo</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Estudiante</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz bd-de  bd-blanco">Total Cancelado</div>
                    <div class="column-1-12"></div>
                </div>
                <div id="listado_facturaciones_estudiante" class="r-h11 scrolleable fondo-blanco-transparente fila-int-azul-gris fila-hover-blan">                    
                </div>
            </div>
        </div> 
    </div>
`

function main_control_facturacion(){
	window.document.title = 'Sistema. Control Facturación';
	$("#titulo_seccion").html(titulo_control_facturacion);
	$("#seccion_dinamica_principal").html(plantilla_control_facturacion);
	listado_facturaciones_estudiante();
}

$(document).ready(function() {
	$(document).on("click","#control_facturas",main_control_facturacion);

	$(document).on("click","#btn-busqueda_facturaciones_estudiantes",function(e){
        e.preventDefault();
        let valor = $("#busqueda_facturaciones_estudiantes").val();
        listado_facturaciones_estudiante('busqueda',valor);
    });

    $(document).on("click","#btn-busqueda-fecha",function(){
    	let fecha_desde = $("#fecha_desde").val();
    	let fecha_hasta = $("#fecha_hasta").val();
    	listado_facturaciones_estudiante('rango_fechas', '', fecha_desde, fecha_hasta);
    });

    $(document).on("click","#reporte_factura_fecha",function(e){
        let fecha_desde = $("#fecha_desde").val();
    	let fecha_hasta = $("#fecha_hasta").val();
        window.open("modulos/pdf/reporte_facturas_estudiantes.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta,"factura_mensualidad.pdf","width=1100px, height=700px");
    });
});

function listado_facturaciones_estudiante(criterio = 'listado_completo', filtro = '', fecha_desde = '', fecha_hasta = ''){
	let datos = {};
	if(criterio == 'rango_fechas'){
		datos = {
			criterio : criterio,
			fecha_desde : fecha_desde,
			fecha_hasta : fecha_hasta
		};
	}else if(criterio == 'busqueda' || criterio == 'listado_completo'){
		datos = {
			criterio : criterio,
			busqueda : filtro
		}
	}
    $.ajax({
        async: false,
        url: 'modulos/facturaciones_estudiante.php',
        type: 'GET',
        dataType: "json",
        data: datos
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
        template += `
            <div class="r-h1m pd-btigual">
	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
	            	${datos.codigo}
	            </div>
	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                ${datos.fecha}
	            </div>
	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                ${datos.hora}
	            </div>
	            <div class="column-2-12 texto-centrado txt-bold-pq pd-btigual">
	                `
	            	if(datos.tipo == 'i'){
	            		template += "Inscripción";
	            	}else if(datos.tipo == 'm'){
	            		template += "Mensualidad";
	            	}
	            template += `
	            </div>
	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                ${datos.estudiante}
	            </div>
	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
	                ${datos.total}
	            </div>`
	        if(datos.tipo == 'i'){
	        	template += `
	        		<div class="column-1-12 texto-centrado pd-izqsolo">
	                	<a href="#" title="Ver Factura" id="${datos.codigo}" class="btn-ver-factura_inscripcion btn-pq btn-rojo txt-bold-men">
	                   		<i class="far fa-eye"></i> Ver
	                	</a>
	           		</div>`;
	        }else if(datos.tipo == 'm'){
	        	template += `
	        		<div class="column-1-12 texto-centrado pd-izqsolo">
	                	<a href="#" title="Ver Factura" id="${datos.codigo}" class="btn-ver-factura_normal btn-pq btn-rojo txt-bold-men">
	                   		<i class="far fa-eye"></i> Ver
	                	</a>
	           		</div>`;
	        }
            template += `</div>`;
        });
        $("#listado_facturaciones_estudiante").html(template);
    })
    .fail(function(response) {
        $("#listado_facturaciones_estudiante").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}