let titulo_control_facturacion_producto = `Control de Facturaciones de los Productos.`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_control_facturacion_producto = `
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
                    <a href="#" class="btn-pq btn-verde txt-bold-pq" id="btn-busqueda-fecha-producto"><i class="fas fa-search"></i> Ir</a>
                </div>
            </div>
            <div class="derecha column-auto">
                <div class="caja-busqueda column-10-12">
                    <input type="text" id="busqueda_facturaciones_productos" class="busqueda" placeholder="Id o Cedula"/>
                </div>
                <div class="column-3-12">
                    <a href="#" id="boton_busqueda_facturaciones_productos" class="btn-pq btn-verde txt-bold-pq"><i class="fas fa-search"></i></a>
                </div>
            </div>
        </div>
        <div class="r-h1 pd-btigual">
            <a href="#" title="Obtener Reporte por Fecha en PDF" id="reporte_factura_fecha_producto" class="btn-rojo izquierda txt-light-men mar-arr">
                <i class="fas fa-file-pdf"></i> Generar Reporte PDF
            </a>
        </div>
        <div class="r-h10 pd-sm">
            <div class="wh-completo bd-1p-bln sombra-gris-caja-completa fondo-gris-azulado">
                <div class="r-h1 fondo-azul-blanco">
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual  bd-blanco">Id</div>
                    <div class="column-1m-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Fecha</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Cliente</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Cant. Productos</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz  bd-blanco">Monto Total</div>
                    <div class="column-2-12 texto-centrado txt-bold-pq bd-2p pd-btigual bd-iz bd-de  bd-blanco">Usuario</div>
                    <div class="column-1-12"></div>
                </div>
                <div id="listado_facturaciones_producto" class="r-h11 scrolleable fondo-blanco-transparente fila-int-azul-gris fila-hover-blan">                    
                </div>
            </div>
        </div> 
    </div>
`

function main_control_facturacion_producto(){
	window.document.title = 'Sistema. Control Facturación Productos';
	$("#titulo_seccion").html(titulo_control_facturacion_producto);
	$("#seccion_dinamica_principal").html(plantilla_control_facturacion_producto);
	listado_facturaciones_productos();
}

$(document).ready(function() {
	$(document).on("click","#facturaciones_producto",main_control_facturacion_producto);

	$(document).on("click","#boton_busqueda_facturaciones_productos",function(e){
        let valor = $("#busqueda_facturaciones_productos").val();
        listado_facturaciones_productos('busqueda',valor);
    });

    $(document).on("click","#btn-busqueda-fecha-producto",function(){
    	let fecha_desde = $("#fecha_desde").val();
    	let fecha_hasta = $("#fecha_hasta").val();
    	listado_facturaciones_productos('rango_fechas', '', fecha_desde, fecha_hasta);
    });

    $(document).on("click","#reporte_factura_fecha_producto",function(e){
        let fecha_desde = $("#fecha_desde").val();
    	let fecha_hasta = $("#fecha_hasta").val();
        window.open("modulos/pdf/reporte_facturas_productos.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta,"factura_mensualidad.pdf","width=1100px, height=700px");
    });

    $(document).on("click",".btn-ver-factura_producto",function(e){
        let id_factura = parseInt($($(this)[0]).attr('id'));
        window.open("modulos/pdf/facturas_producto.php?cf="+id_factura,"ticket_venta_productos.pdf","width=1100px, height=700px");
    });
});

function listado_facturaciones_productos(criterio = 'listado_completo', filtro = '', fecha_desde = '', fecha_hasta = ''){
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
		};
	}
    $.ajax({
        async: false,
        url: 'modulos/producto/facturaciones_producto.php',
        type: 'GET',
        dataType: "json",
        data: datos
    })
    .done(function(response){
        let template = "";
        response.forEach(datos => {
            template += `
                <div class="r-h1m pd-btigual">
    	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
    	            	${datos.id}
    	            </div>
    	            <div class="column-1m-12 texto-centrado txt-arial-bold-pq pd-btigual">
    	                ${datos.fecha}
    	            </div>
    	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
    	                ${datos.cliente}
    	            </div>
    	            <div class="column-2-12 texto-centrado txt-bold-pq pd-btigual">
    	                ${datos.cant_productos}
    	            </div>
    	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
    	                ${datos.monto_total}
    	            </div>
    	            <div class="column-2-12 texto-centrado txt-arial-bold-pq pd-btigual">
    	                ${datos.usuario}
    	            </div>
    	        
    	        	<div class="column-1-12 texto-centrado pd-izqsolo">
    	                <a href="#" title="Ver Factura" id="${datos.id}" class="btn-ver-factura_producto btn-pq btn-rojo txt-ppq">
    	                   	<i class="far fa-eye"></i> Ticket
    	                </a>
    	           	</div>
    	        </div>`;
        });
        $("#listado_facturaciones_producto").html(template);
    })
    .fail(function(response) {
        $("#listado_facturaciones_producto").html('<div class="avisar_nada">'+response.responseText+'</div>');
    });   
}