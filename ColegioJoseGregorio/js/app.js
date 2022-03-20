  var t = null;
  var session_abierta, milisegundos;
 
  //ESCUCHADOR DE ENVENTO PARA CUANDO SE CIERRA LA VENTANA O SE RECARGA LA PAGINA
  window.addEventListener('unload', logout_closed, false);

  //FUNCION PRINCIPAL POR LA QUE COMIENZA EL SISTEMA CON EL CONTROL DE USUARIO
  $.ajax({
        async: false,
        url: 'modulos/index.php',
        dataType: 'json',
        type: 'GET'
  })
  .done(function(response){
      session_abierta = 1;
      if(response.tipo == 1){
        main_home_root(response.id, response.nombre, response.logo, response.ultima_sesion, response.identificador);
      }else{
        main_home(response.id,response.nombre, response.logo, response.ultima_sesion, response.identificador);
      }
  })
  .fail(function(response){
      session_abierta = 0;
      main_login();
  });

//EVENTOS QUE SON GLOBALES O SON MUY SENCILLOS PARA CREAR UNA SECCION
//PARA ELLOS, YA QUE SOLO EJECUTAN UNAS POCAS LINEAS
$(document).ready(function() {
    $(document).on("click","#logout",function(e){
          e.preventDefault();
          logout();
    });

    $(document).on('click', '#respaldar', function(event) {
      event.preventDefault();
      location.href="config/backups.php";
    });

    // Escuchamos el evento 'change' del input donde cargamos el archivo
    // Al la etiqueta label le colocamos el nombre del archivo a subir.
    $(document).on("change","#file_restaurar",function(){
      $('#inputval').text(this.files[0].name);
    });

    //Evento que al hacer click suber el archivo al servidor.
    $(document).on("click","#btn-subir-restauracion",function(e){
      e.preventDefault();
      var formulario = document.getElementById("formulario_restaurar");
      var formData = new FormData(formulario);
      $.ajax({
        url: "config/restablecer.php",
        type: "POST",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function(){
          $("#formulario_restaurar").trigger("reset");
          $("#btn-cerrar").trigger('click');
          $(".cargando").css('display', 'block');
        }
      })
      .done(function(response){
        alertar(response.advertencia, response.mensaje);
        setTimeout(function(){
            ocultarAlerta(0.001);
            location.reload();
        },3999);
      })
      .fail(function(response) {
          alertar(3, response.responseText);
          setTimeout(function(){
              ocultarAlerta(0.001);
          },9999);
       })
      .always(function(){
        $(".cargando").css('display', 'none');
      });
    });

    $(document).on("click",".deshabilitar_estudiante",function(){
        var cedula_estudiante = $($(this)[0]).attr('id');
        var tipo_estudiante = parseInt($($(this)[0]).attr('te'));
        $.ajax({
        	async: false,
          	url: 'modulos/basic/estudiante_retirar.php',
          	type: 'GET',
          	dataType: 'json',
          	data: {
            	cedula: cedula_estudiante,
            	tipo_estudiante: tipo_estudiante
          	},
          	beforeSend: () => {
            	$(".close").trigger('click');
            	$(".cargando").css('display', 'block');
          	}
        })
        .done(function(response) {
          	alertar(response.advertencia, response.mensaje);
          	setTimeout(function(){
              	ocultarAlerta(0.001);
            	seccion_pagos(seccion_estudiantes);
          	},5999);
        })
        .fail(function(response) {
          	alertar(3, response.responseText);
          	setTimeout(function(){
            	ocultarAlerta(0.001);
          	},9999);
        })
        .always(function() {
         	$(".cargando").css('display', 'none');
        });
    });

    $(document).on('click', '#lista_morosos', function(event) {
      event.preventDefault();
      window.open("modulos/pdf/listado_morosos.php","Morosos.pdf","width=900px, height=600px");
    });
});

function contadorInactividad(minutos) {
    milisegundos = minutos * 60000;
    t = setTimeout(logout,milisegundos);
}

window.onload = window.onblur = window.onmousemove = window.onkeypress = function() {
    if(t && session_abierta === 1){
        clearTimeout(t);
    }
    if(session_abierta === 1){
        contadorInactividad(20);
    }
}