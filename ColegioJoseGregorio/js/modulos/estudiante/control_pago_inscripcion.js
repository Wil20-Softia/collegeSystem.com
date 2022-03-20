//************ TITULO DE LA SECCION
let titulo_control_pago_insc = `
    <div class="column-6-12">Control de Pago de Incripción</div> 
    <div class="derecha column-auto">
        <div class="caja-busqueda column-10-12">
            <input type="text" class="busqueda" placeholder="'1erNom 1erApe' o 'C.I'"/>
        </div>
        <div class="column-3-12">
            <a href="#" class="btn-busqueda"><i class="fas fa-search"></i></a>
        </div>
    </div>`;

/*****************************************************************************/
/************************** PLATILLA DE LA SECCION  **************************/
/*****************************************************************************/
const plantilla_control_pago_insc = `
	<div class="wh-completo">
        <div class="r-h1">
            <div id="caja_categoria" class="column-3-12 pd-ssm">
                <select id="categoria" class="wh-completo">
                    <option value="0">Categoria</option>
                </select>
            </div>
            <div id="caja_grado" class="column-2-12 pd-ssm">
                <select id="grado" class="wh-completo desactivar">
                    <option value="0">Año/Grado</option>
                </select>
            </div>
            <div id="caja_seccion" class="column-1m-12 pd-ssm">
                <select id="seccion" class="wh-completo desactivar">
                    <option value="0">Sección</option>
                </select>
            </div>
            <div class="column-1m-12">
                <a href="#" id="btn-buscar-control-inscripcion" class="btn-buscar-control btn-verde desactivar"><i class="fas fa-search"></i> Buscar</a>
            </div>
        </div>

        <div class="r-h9 pd-sm">
            <div class="wh-completo bd-1p-bln sombra-gris-caja-completa fondo-gris-azulado text-light">
                <div class="r-h1m">
                    <div class="column-3-12 texto-centrado txt-bold-men pd-btigual bd-iz-ar-ab  bd-blanco">ESTUDIANTES</div>
                    <div class="column-9-12 texto-centrado txt-bold-men pd-btigual bd-completo  bd-blanco">MONTOS DE CUPO E INSCRIPCIÓN</div>
                </div>
                <div class="r-h1 bd-ab  bd-blanco">
                    <div class="column-3-12 texto-centrado txt-bold-pq pd-btigual bd-iz  bd-blanco">Nombres y Apellidos</div>
                    <div id="seccion_id_inscripciones" class="column-9-12 texto-centrado bd-iz-de txt-bold-men  bd-blanco">
                        
                    </div>
                </div>
                <div id="listado_estudiante" class="r-h10 scrolleable">                    
                    
                </div>
            </div>
        </div>


        <div id="datos_seccion" class="r-h2 pd-ssm">
            <div class="r-h4 texto-centrado txt-bold-pq">
                MATRICULA DE <span id="cantidad_estudiantes_se" class="txt-mf20"></span> ESTUDIANTES
            </div>
            <div class="r-h4">
                <div class="column-6-12 texto-centrado txt-bold-pq">SOLVENTES ACTUALES:</div>
                <div class="column-6-12 texto-centrado txt-bold-pq">DEUDORES ACTUALES:</div>
            </div>
            <div class="r-h4">
                <div class="column-6-12 texto-centrado txt-bold-pq"><span id="solventes_estudiantes" class="txt-mf20"></span> ESTUDIANTES</div>
                <div class="column-6-12 texto-centrado txt-bold-pq"><span id="deudores_estudiantes" class="txt-mf20"></span> ESTUDIANTES</div>
            </div>
        </div> 
    </div>
`

/*****************************************************************************/
/********** FUNCION QUE CONFECCIONA A LA SECCION *****************************/
/*****************************************************************************/
function main_control_pago_inscripcion(){
    searching = 3;
    seccion_estudiantes = main_control_pago_inscripcion;
    window.document.title = 'Sistema. Control de Pago de Incripción';
	$("#titulo_seccion").html(titulo_control_pago_insc);
	$("#seccion_dinamica_principal").html(plantilla_control_pago_insc);
    $(".busqueda").addClass("busqueda_ce_insc");
    $(".btn-busqueda").addClass("btn-busqueda_ce_insc");

    $("#datos_seccion").css('display', 'none');

    quitarAdvertenciaBlur(".busqueda_ce_insc", ".caja-busqueda");
    categorias("#categoria");
    colocarMontosInscripcion();
}

/*****************************************************************************/
/************************** SECCION DE EVENTOS   *****************************/
/*****************************************************************************/
$(document).ready(function() {

	//CUANDO CLIQUEA EL BOTON DEL MENU PARA INGRESAR A LA SECCIÓN
	$(document).on("click","#control_inscripcion",function(){
		main_control_pago_inscripcion();
	});

    $(document).on("click","#btn-buscar-control-inscripcion", function(){
        let seccion = $("#seccion").val();
        listadoEstudiantesInscripcion('', 0, seccion);
    });

    $(document).on("click",".btn-busqueda_ce_insc", function(){
        let campoBuscar = $(".busqueda_ce_insc").val();
        busqueda_control(campoBuscar, listadoEstudiantesInscripcion);    
    });

    $(document).on("click",".btn-ver-factura_inscripcion",function(){
        let id_factura = parseInt($($(this)[0]).attr('id'));
        window.open("modulos/pdf/facturas_inscripcion.php?if="+id_factura,"factura_inscripcion.pdf","width=1100px, height=700px");
    });

    $(document).on("change",".per-esc-insc",function(){
        let val = parseInt($(this).val());
        colocarMontosInscripcion(val);
    });
});


function listadoEstudiantesInscripcion(filtro = '', periodo_escolar = 0, seccion = 0){
    let datos;
    if(filtro == ''){
        if(periodo_escolar > 0){
            datos = { 
                criterio : 'seccion',
                seccion_especifica : seccion,
                periodo_escolar
            };
        }else{
            datos = { 
                criterio : 'seccion',
                seccion_especifica : seccion
            };
        }
    }else if(filtro != ''){
        if(periodo_escolar > 0){
            datos = {
                criterio : 'nombre',
                busqueda : filtro,
                periodo_escolar 
            };
        }else{
            datos = {
                criterio : 'nombre',
                busqueda : filtro 
            };
        }
    }
  
    $.ajax({
        url: 'modulos/control_inscripcion.php',
        type: 'POST',
        dataType: 'json',
        data: datos
    })
    .done(function(response){
        let estudiantes = response["estudiantes"];
        let datos_seccion = response["datos_seccion"];
        
        let template = "";
        estudiantes.forEach(estudiante => {
            template += "<div class='r-h1m bd-ab bd-blanco'>";
            template += `
                <div class="datos_estudiante column-3-12 bd-iz bd-blanco">
                    <div class="contenedor_nombre_estudiante wh-completo pd-btigual-mitad scrolleable">
                        <a href="#" class="nombre_estudiante texto-centrado txt-ppq text-light" data-toggle="modal" data-target="#estudiante_${estudiante.id_estudiante}">
                            ${estudiante.nombre_estudiante}
                        </a>
                    </div>
                    <!-- The Modal -->
                    <div class="modal fade text-dark" id="estudiante_${estudiante.id_estudiante}">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <strong class="txt-mf20">${estudiante.nombre_estudiante}</strong>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div> 
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="column-6-12 r-h1m">
                                        <strong class="txt-bold-pq">C.I:</strong> 
                                        <strong class="txt-mmf20">${estudiante.cedula_estudiante}</strong>
                                    </div>
                                    <div class="column-6-12 r-h1m texto-centrado">
                                        <strong class="txt-bold-pq">Año Escolar:</strong> 
                                        <strong class="txt-mmf20">${estudiante.year_escolar_estudiante}</strong>
                                    </div>
                                    
                                    <div class="r-hm"></div>

                                    <div class="r-h1m">
                                        <strong class="txt-bold-pq">Cursando: </strong>
                                        <strong class="txt-mf20">${estudiante.seccion_especifica_estudiante}</strong>
                                    </div>

                                    <div class="r-hm"></div>

                                    <strong class="r-h1m txt-mmf20 texto-centrado">REPRESENTANTE</strong> 
    
                                    <p>
                                        <stron>${estudiante.nombre_representante}</stron> 
                                            <address>Cedula: ${estudiante.cedula_representante}</address>
                                    </p>
                                    <p>
                                        <address>Nro. Telefonico: ${estudiante.telefono_representante}</address>
                                    </p>
                                </div> 
                                <!-- Modal footer -->
                                <div class="modal-footer texto-centrado">
                                    <button type="button" id="${estudiante.tipo_estudiante}" class="btn btn-success txt-mf20 cancelar_inscripcion" data-dismiss="modal">Cancelar Inscripción</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>

                <div class="column-9-12 texto-centrado bd-iz bd-blanco">`;

                let montos_estudiante = estudiante.montos_estudiante;
                montos_estudiante.forEach(me => {
                    let facturas = me.facturas_monto;
                    if(facturas != 0){
                        template += `
                            <div class="column-1-12 bd-de bd-blanco ${me.clases_contenedor}">
                                <a href="#" class="${me.clases_boton}" data-toggle="modal" data-target="#mes_${me.id_deuda_monto}">Ver</a>
                            </div>
                            <!-- The Modal -->
                            <div class="modal fade text-dark" id="mes_${me.id_deuda_monto}">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h6 class="modal-title">Factura(s) realizada(s) en este mes:</h6>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                                
                                        <!-- Modal body -->
                                        <div class="modal-body scrolleable">
                                            <h4>Codigos de las Facturas:</h4>
                                            <p>`;
                        let tam = facturas.length;
                        for (let p = 0; p < tam; p++){
                            template += "<a href='#' id='"+facturas[p]+"' class='btn btn-outline-primary btn-ver-factura_inscripcion'>"+facturas[p]+"</a>";
                        }
                                            
                        template += `
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    }else{
                        template += `
                            <div class="column-1-12 bd-de bd-blanco ${me.clases_contenedor}">
                                ${me.clases_boton}
                            </div>
                        `;
                    }
                });
            template +="</div></div>";
        });
            
        $("#listado_estudiante").html(template);

        if(datos_seccion != 0){
            $("#datos_seccion").css('display', 'block');
            $("#cantidad_estudiantes_se").text(datos_seccion.cantidad);
            $("#deudores_estudiantes").text(datos_seccion.deudores);
            $("#solventes_estudiantes").text(datos_seccion.solventes);
        }else{
            $("#datos_seccion").css('display', 'none');
        }
    })
    .fail(function(response) {
        $("#listado_estudiante").html(`<div class="avisar_nada">${response.responseText}</div>`);
        $("#datos_seccion").css('display', 'none');
    });   
}

function colocarMontosInscripcion(periodo_escolar = 0){
    $.ajax({
        url: 'modulos/basic/extraerMontosInscripcion.php',
        type: 'POST',
        dataType: 'json',
        data: {
            periodo_escolar
        }
    })
    .done(function(response){
        let template = "";
        response.forEach(monto => {
            template += `<div class="column-1-12 pd-btigual bd-de texto-centrado txt-light-pq  bd-blanco">
                    ${monto.tipo}. ${monto.id}
                </div>`;
        });
        $("#seccion_id_inscripciones").html(template);
    })
    .fail(function(response){
        alertar(3, response.responseText);
        setTimeout(function(){
            ocultarAlerta(0.001);
        },4999);
    });
}