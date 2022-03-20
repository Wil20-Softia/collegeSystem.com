var inscribir;

function logout(){
  $.ajax({
      url: "modulos/logout.php",
      type: "GET",
      beforeSend: function(){
        $(".cargando").css('display', 'block');
      }
    }).done(function(response){
        console.log(response);
        session_abierta = 0;
        main_login();
    }).always(function(){
      $(".cargando").css('display', 'none');
  });
}

function logout_closed(){
  if(session_abierta == 1){
    var formData = new FormData();
    formData.append( 'chao', 'hola' );
    navigator.sendBeacon("modulos/logout.php", formData );
  }else{
    return ;
  }
}

function pagos_estudiante_mensualidad(tipo_estudiante, contenedor, cantidad = 0, periodo_escolar = 0, dias_mora = []){
    let supremo = [];
    let datos = {};
    datos = {
      "tipo_estudiante" : tipo_estudiante,
      "cantidad" : cantidad,
      "periodo_escolar" : periodo_escolar
    }
    supremo.push(datos);
    if(dias_mora.length == 0){
      supremo.push("nada");
    }else{
      supremo.push(dias_mora);
    }
    $.ajax({
        async: false,
        url: "modulos/basic/obtenerDeudaMensualidad.php",
        type: 'POST',
        dataType: 'json',
        data: {superdata : JSON.stringify(supremo)}
    })
    .done(function(response){
        $("#monto_mora").val("");
        $("#subtotal").val("");
        $("#cantidad_factura").val("");
        let template = "";
        let meses = response[0];
        let totales = response[1];
        if(response.advertencia != 2){
              meses.forEach(datos =>{
                  template += `
                      <div class="r-h1 bd-ab bd-blanco">
                          <div class="column-1m-12 bd-iz bd-de bd-blanco txt-mmf20 desactivar">
                      `;
                      if(datos.activar == 1){
                          template += `<input id="${datos.id_mes}" type="checkbox" checked="true" class="option-input-completo"/>`;
                      }else{
                          template += `<input id="${datos.id_mes}" type="checkbox" class="option-input-completo"/>`;
                      }
                            
                      template+=`</div>
                          <div class="column-1m-12 ${datos.color_mes} texto-centrado txt-light-pq bd-de bd-blanco">
                            ${datos.nombre_mes}
                          </div>
                          <div class="column-2m-12 bd-de bd-blanco">
                              <input type="text" value="${Decimal_Texto(datos.cancelado)}" class="cancelado txt-uf20 texto-derecho bd-1p-ngr wh-completo desactivar" puntero="${datos.id_mes}"/>
                          </div>
                          <div class="column-2m-12 bd-de bd-blanco">
                              <input type="text" value="${Decimal_Texto(datos.abonado)}" class="abonado txt-uf20 texto-derecho bd-1p-ngr wh-completo desactivar" puntero="${datos.id_mes}"/>
                          </div>
                          <div class="column-2m-12 bd-de bd-blanco">
                              <input type="text" value="${Decimal_Texto(datos.diferencia)}" class="diferencia txt-uf20 texto-derecho bd-1p-ngr wh-completo desactivar" puntero="${datos.id_mes}"/>
                          </div>
                          `;
                          if(cantidad == 0){
                            if(datos.mostrar_dias == 1){
                              template += `
                                <div numero="${numeros_dias_mora}" id="caja_dias_mora" class="column-1m-12">
                                  <input type="text" class="dias_mora txt-light-pq texto-centrado bd-1p-ngr wh-completo" puntero="${datos.id_mes}"/>
                                </div>`;
                              numeros_dias_mora++;
                            }else{
                              template += `
                                <div class="column-1m-12">
                                </div>`;
                            }
                          }else if(cantidad > 0){
                            if(datos.mostrar_dias == 1){
                              template += `
                                  <div numero="${numeros_dias_mora}" id="caja_dias_mora" class="column-1m-12">
                                    <input type="text" class="dias_mora txt-light-pq texto-centrado bd-1p-ngr wh-completo" value="${datos.valor_dia}" puntero="${datos.id_mes}"/>
                                  </div>`;
                              numeros_dias_mora++;
                            }else{
                              template += `
                                <div class="column-1m-12">
                                </div>`;
                            }
                          }

                        template += `</div>`;
              });
              $(contenedor).html(template);
              if(totales != 0){
                $("#monto_mora").val(Decimal_Texto(totales.total_mora));
                $("#subtotal").val(Decimal_Texto(totales.subtotal));
                $("#cantidad_factura").val(Decimal_Texto(totales.total));
                $("#cajon_diferencia").html(`
                  <div class="column-8-12 txt-arial-light-pq pd-btigual-mitad texto-derecho">
                    Diferencia o Resto:
                  </div>
                  <div id="diferencia_factura" class="column-3-12 txt-arial-light-pq pd-btigual-mitad texto-centrado">
                    ${Decimal_Texto(totales.resto)}
                  </div>`);
              }else{
                $("#cajon_diferencia").html("");
              }
        }else{
            alertar(response.advertencia, response.mensaje);
            setTimeout(function(){
              seccion_pagos(seccion_estudiantes);
              ocultarAlerta(0.001);
            },6999);
        }
    })
    .fail(function(response){
        alertar(3, response.responseText);
        setTimeout(function(){
            ocultarAlerta(0.001);
        },6999);
    });
}

function pagos_estudiante_inscripcion(tipo_estudiante, contenedor, cantidad = 0, periodo_escolar = 0){
    $.ajax({
        async: false,
        url: "modulos/basic/obtenerDeudaInscripcion.php",
        type: 'POST',
        dataType: 'json',
        data: {
            tipo_estudiante,
            cantidad,
            periodo_escolar
        }
    })
    .done(function(response){
        let template = "";
        if(response.advertencia != 2){
              response.forEach(datos =>{
                  inscribir = datos.inscribir;
                  template += `
                      <div class="r-h1m bd-ab bd-blanco">
                          <div class="column-1m-12 bd-iz bd-de bd-blanco txt-bold-men desactivar">
                      `;
                      if(datos.activar == 1){
                          template += `<input id="${datos.id_inscripcion}" type="checkbox" checked="true" class="option-input-completo"/>`
                      }else{
                          template += `<input id="${datos.id_inscripcion}" type="checkbox" class="option-input-completo"/>`
                      }
                            
                      template+=`</div>
                          <div class="column-3-12 ${datos.color_inscripcion} txt-bold-pq pd-btigual texto-centrado bd-de bd-blanco">${datos.nombre_inscripcion}</div>

                          <div class="column-2m-12 bd-de bd-blanco">
                              <input type="text" value="${Decimal_Texto(datos.cancelado)}" class="cancelado txt-uf20 texto-derecho bd-1p-ngr wh-completo desactivar" puntero="${datos.id_inscripcion}"/>
                          </div>

                          <div class="column-2m-12 bd-de bd-blanco">
                              <input type="text" value="${Decimal_Texto(datos.abonado)}" class="abonado txt-uf20 texto-derecho bd-1p-ngr wh-completo desactivar" puntero="${datos.id_inscripcion}"/>
                          </div>

                          <div class="column-2m-12">
                              <input type="text" value="${Decimal_Texto(datos.diferencia)}" class="diferencia txt-uf20 texto-derecho bd-1p-ngr wh-completo desactivar" puntero="${datos.id_inscripcion}"/>
                          </div>
                      </div>
                  `;
              });
              $(contenedor).html(template);
              if(inscribir == 1){
                  $("#contenedor_grado_seccion").css('display', 'block');
                  grados_estudiante("#grado_estudiante", tipo_estudiante);
              }else{
                  $("#contenedor_grado_seccion").css('display', 'none');
              }
        }else{
            alertar(response.advertencia, response.mensaje);
            setTimeout(function(){
              ocultarAlerta(0.001);
              if(formulario = "insc"){
                main_facturacion_inscripcion();
              }else{
                seccion_pagos(seccion_estudiantes);
              }
            },6999);
        }
    })
    .fail(function(response){
        alertar(3, response.responseText);
        setTimeout(function(){
            ocultarAlerta(0.001);
            seccion_pagos(seccion_estudiantes);
        },6999);
    });
}

function busqueda_control(busqueda, listado, periodo_escolar = 0){  
    if(busqueda === ''){
        quitar_advertencia(".caja-busqueda");
        $(".busqueda").focus();
        $(".busqueda").css('border', '3px solid #e84e29');
        advertencia_animada(".caja-busqueda","Introduce el Primer Nombre y el Primer Apellido o Cedula con la (V- o E-) al principio");
    }else{
        $(".busqueda").css('border', '3px solid #49b0f7');
        quitar_advertencia(".caja-busqueda");

        listado(busqueda, periodo_escolar);
    }     
}

function categorias(select_categoria){
  $.ajax({
    async: false,
    url: "modulos/basic/categorias.php",
    type: "GET",
    dataType : "json"
  })
  .done(function(response){
    let opcion = "<option value=0>Categoria</option>";;
    response.forEach(categoria => {
      opcion += "<option value="+categoria.id+">"+categoria.nombre+"</option>";
    });
    $(select_categoria).html(opcion);
  })
  .fail(function(response){
    $(select_categoria).html("<option value='0'>Categoria</option>");
  });
}

function categorias_producto(select_categoria){
  $.ajax({
    async: false,
    url: "modulos/producto/obtener_categorias.php",
    dataType : "json"
  })
  .done(function(response){
    let opcion = "<option value='0'>Categoria</option>";
    response.forEach(categoria => {
      opcion += "<option value='"+categoria.id+"'>"+categoria.nombre+"</option>";
    });
    $(select_categoria).html(opcion);
  })
  .fail(function(response){
    $(select_categoria).html("<option value='0'>Categoria</option>");
  });
}

function tipos_pago(select_tp){
  $.ajax({
    async: false,
    url: "modulos/basic/tipo_pago.php",
    type: "GET",
    dataType : "json"
  })
  .done(function(response){
    let opcion = "<option value='0'>Tipo</option>";
    response.forEach(tp => {
      opcion += "<option value="+tp.id+">"+tp.nombre+"</option>";
    });
    $(select_tp).html(opcion);
  })
  .fail(function(response){
    $(select_tp).html("<option value='0'>Tipo</option>");
  });
}

function grados_estudiante(select_grado, id_estudiante){
  $.ajax({
    async: false,
    url: "modulos/basic/grado_seccion_estudiante.php",
    type: "POST",
    dataType: "json",
    data: {id_estudiante}
  })
  .done(function(response){
    let opcion = "<option value=0>Grado/Año</option>";
    response.forEach(grado => {
      opcion += "<option value="+grado.id+">"+grado.nombre+"</option>";
    });
    $(select_grado).html(opcion);
  })
  .fail(function(response){
    alertar(3, response.responseText);
    setTimeout(function(){
        ocultarAlerta(0.001);
    },6999);
  });
}

function grados_categorias(select_grado, categoria = 0){
  $.ajax({
    async: false,
    url: "modulos/basic/grados_categoria.php",
    type: "GET",
    dataType: "json",
    data: {categoria},
    success: function(response){
      let opcion;
      if(categoria > 0){
        if(categoria ==  1){
          opcion = "<option value=0>Grado</option>";
        }else if(categoria ==  2){
          opcion = "<option value=0>Año</option>";
        }
      }else{
        opcion = "<option value=0>Año/Grado</option>";
      }
      response.forEach(grado => {
        opcion += "<option value="+grado.id+">"+grado.nombre+"</option>";
      });
      $(select_grado).html(opcion);
    }
  });
}

function secciones_grados(select_seccion, g){
  $.ajax({
    async: false,
    url: "modulos/basic/secciones_grados.php",
    type: "GET",
    dataType: "json",
    data: {grado : g}
  })
  .done(function(response){
    let opcion = "<option value=0>Sección</option>";
    response.forEach(seccion => {
      opcion += "<option value="+seccion.id+">"+seccion.nombre+"</option>";
    });
    $(select_seccion).html(opcion);
  })
  .fail(function(response){
    $(select_seccion).html("<option value=0>"+response.responseText+"</option>");
  });
}

function subcategorias_productos(select_subcategoria, c){
  $.ajax({
    async: false,
    url: "modulos/producto/obtener_subcategorias.php",
    type: "GET",
    dataType : "json",
    data: {categoria : c},
    success: function(response){
      let opcion = "<option value='0'>Subcategoria</option>";
      response.forEach(sub => {
        opcion += "<option value='"+sub.id+"'>"+sub.nombre+"</option>";
      });
      $(select_subcategoria).html(opcion);
    }
  });
}

function secciones(select_seccion){
  $.ajax({
    async: false,
    url: "modulos/basic/secciones.php",
    type: "GET",
    success: function(response){
      let secciones = JSON.parse(response);
      let opcion = "<option value=0>Sección</option>";
      secciones.forEach(seccion => {
        opcion += "<option value="+seccion.id+">"+seccion.nombre+"</option>";
      });
      $(select_seccion).html(opcion);
    }
  });
}

function mesesInscripcion(select,mesActual){
    let mostrar = "<option value='0'>Mes de Inscripción</option>"
    if(mesActual >= 7 && mesActual <= 10){
        mostrar += "<option value='1'>Septiembre</option>";
        $(select).html(mostrar);
    }else if(mesActual == 6){
        $(select).html(mostrar);
        $(select).addClass('desactivar');
    }else{
        /*if(mesActual == 11){
            mesActual = 3;
        }else if(mesActual == 12){
            mesActual = 4;
        }else{
            mesActual = mesActual + 4;
        }*/
        for(var i = 1; i <= 9; i++){
            mostrar += "<option value='"+i+"'>"+meses[i]+"</option>";
        }

        $(select).html(mostrar);
    }
}