var change_form_fact = 0;
var ui_admin = 0;
var datos;
var datos_usuario = {};
var searching = 0;
var formulario = "";
var pee = 0;
var numeros_tipo_pago = 0;
var numeros_dias_mora = 1;
var seccion_estudiantes;
var total_factura_producto = 0;
var cantidad_productos_cesta = 0;
var form_prod_exitoso = 0;
var modificar_cat = 0;
var modificar_subcat = 0;

seccion_pagos = (seccion) => {
  seccion();
};

let patron_telefono = /^([0]){1}([2|4]){1}([\d]){1}([\d]){1}([-]){1}([\d]){7}$/;
let patron_rif = /^([V|E|P|G|J|C]){1}([-]){1}([0-9]){9}$/;
let patron_cedula = /^([V|E]){1}([-]){1}([0-9]){7,8}$/;
let patron_cedula_estudiante = /^([V|E|X]){1}([-]){1}([0-9]){1,9}$/;
let patron_decimales = /^([0-9]){1,9}([.]){1}([0-9]){2}$/;
let patron_correo = /^([0-9A-Za-z.-_#$*+]){5,25}([@]){1}([A-Za-z]){2,11}([.]){1}([a-z]){2,4}$/;

var f = new Date();
let yearActual = f.getFullYear();
let mesActual = f.getMonth() + 1;

const fecha_actual = f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear();

var meses = ["","Septiembre","Octubre","Noviembre","Diciembre","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto"];

function onEvents(){
  $.ajax({
    url: "modulos/eventosAutomaticos.php",
    dataType: "json"
  })
  .done(function(response){
    console.log(response);
  })
  .fail(function(response){
    console.log(response.responseText);
  });
}

function setValueSelect(SelectId, Value) {
  var SelectObject;
  SelectObject = document.getElementById(SelectId);
  for(index = 0;  index < SelectObject.length;  index++) {
    if(SelectObject[index].value == Value) SelectObject.selectedIndex = index;
  }
}

function obtenerTextoSelect(SelectId, Value) {
  var SelectObject;
  SelectObject = document.getElementById(SelectId);
  for(index = 0;  index < SelectObject.length;  index++) {
    if(SelectObject[index].value == Value) return SelectObject[index].text;
  }
}

function colocarOption(objSelector, valor, texto){
    objSelector.html("<option value='"+valor+"' selected>"+texto+"</option>");
}

//FUNCION QUE SACA EL CUADRO DE DIALOGO DE LA ADVERTENCIA DE VALIDACION.
function advertencia(padre, valor){
  $(padre).addClass('posicion_relativa');
  $("<div>", {
        'class': 'advertencia-formulario',
        'text':  valor
  }).css("display","block").appendTo(padre);
}

function advertencia_animada(padre, valor){
  $(padre).addClass('posicion_relativa');
  $("<div>", {
        'class': 'advertencia-formulario animacion_aparecer',
        'text':  valor
  }).css("display","block").appendTo(padre);
}

function quitar_advertencia(contenedor){
  $(contenedor).removeClass('posicion_relativa');
  $(".advertencia-formulario").css("display","none");
}

//CUANDO SE DESENFOCA ALGUN CAMPO DE TEXTO QUE CONTENGA LA CLASE FIELD
function quitarAdvertenciaBlur(campo, padre){
  if(typeof campo === 'object'){
    campo.on("focusout",function(){
      quitar_advertencia(padre);
      campo.css('border', '');
    });
  }else if(typeof campo === 'string'){
    $(campo).on("focusout",function(){
      quitar_advertencia(padre);
      $(campo).css('border', '');
    });
  }
}

/*
  animacion:
    0 -> sin animacion
    1 -> animada
*/
function advertenciaEnfocada(contenedor,campo,texto_advertencia,animacion=0){
  if(typeof campo === 'object'){
    quitar_advertencia(contenedor);
    if(animacion === 0){
      advertencia(contenedor,texto_advertencia);
    }else{
      advertencia_animada(contenedor, texto_advertencia);
    }
    setTimeout(function(){
        campo.focus();
    }, 1);
  }else if(typeof campo === 'string'){
    quitar_advertencia(contenedor);
    if(animacion === 0){
      advertencia(contenedor,texto_advertencia);
    }else{
      advertencia_animada(contenedor, texto_advertencia);
    }
    setTimeout(function(){
        $(campo).focus();
    }, 1);
  }
}

function seleccionar_radio(etiqueta,valor){
  var radios = $(etiqueta);
  for(var g = 0; g < radios.length; g++){
    if($(radios[g]).val() == valor){
      $(radios[g]).prop('checked',true);
    }
  }
}

function obtener_valor_radio(etiqueta){
  var radios = $(etiqueta);
  var valor;
  for(var g = 0; g < radios.length; g++){
    if($(radios[g]).prop('checked')){
      valor = $(radios[g]).val();
    }
  }
  return valor;
}

function validarRadio(etiqueta){
  var radios = $(etiqueta);
  var tam = radios.length;
  var cont = tam;
  var valor;
  for(var g = 0; g < tam; g++){
    if(!$(radios[g]).prop('checked')){
      cont--;
    }
  }
  if(cont == 0){
    return false;
  }else{
    return true;
  }
}

function alertar(advertencia, mensaje){
  let alerta;
  if(advertencia === 1){
      alerta = "alerta-exitosa";
  }else if(advertencia === 2){
      alerta = "alerta-peligro";
  }else{
      alerta = "alerta-error";
  }
  $("<div>",{
      'class' : "fondo_aviso_sobresaliente"
  }).append($("<p>",{
      'class' : "aviso_sobresaliente " + alerta + " animacion_aumentando",
      'text': mensaje
  })).appendTo("body").fadeIn('1');
}

function ocultarAlerta(segundos){
  segundos *= 1000;
  setTimeout(function(){
    $(".fondo_aviso_sobresaliente").fadeOut('1');
  },segundos);
}

function busqueda(valor, listado){  
    if(valor === ''){
        $(".busqueda").focus();
        $(".busqueda").css('border', '3px solid #e84e29');
        advertencia_animada(".caja-busqueda","Introduce solo el Primer Nombre y el Primer Apellido");
    }else{
        $(".busqueda").css('border', '3px solid #49b0f7');
        $(".advertencia-formulario").css("display","none");

        listado(valor);
    }     
}

//SI TIPO ES:
/*
  1 -> Vacio.
  2 -> Patron o Vacio.
  3 -> Fecha o Vacio.
  4 -> Longitud o Vacio.
*/
function Validaciones(tipo,campo,contenedor,texto_advertencia,patron="",long_min=6,long_max=200,maximo=0){
  $(document).on("input change",campo,function(){
    switch(tipo){
      case 1:
        if(!CampoVacio(campo)){
          advertenciaEnfocada(contenedor,campo,texto_advertencia);
        }else{
          quitar_advertencia(contenedor);
        }
      break;

      case 2:
        if(!CampoPatron(campo,patron) || !CampoVacio(campo)){
          advertenciaEnfocada(contenedor,campo,texto_advertencia);
        }else{
          quitar_advertencia(contenedor);
        }
      break;

      case 3:
        if(!CampoFechaActual(campo) || !CampoVacio(campo)){
          advertenciaEnfocada(contenedor,campo,texto_advertencia);
        }else{
          quitar_advertencia(contenedor);
        }
      break;

      case 4:
        if(!LongitudCampo(campo,long_min,long_max) || !CampoVacio(campo)){
          advertenciaEnfocada(contenedor,campo,texto_advertencia);
        }else{
          quitar_advertencia(contenedor);
        }
      break;

      case 5:
        if(!LongitudCampo(campo,long_min,long_max) || !validacionMayorQue(campo,maximo) || !CampoVacio(campo)){
          advertenciaEnfocada(contenedor,campo,texto_advertencia);
        }else{
          quitar_advertencia(contenedor);
        }
      break;

      case 6:
        if(!LongitudCampo(campo,long_min,long_max) || !validacionMayorQue(campo,maximo) || !CampoVacio(campo) || !CampoPatron(campo,patron) ){
          advertenciaEnfocada(contenedor,campo,texto_advertencia);
        }else{
          quitar_advertencia(contenedor);
        }
      break;
    }
  });
}

function ValidacionesCheck(campo,contenedor,texto_advertencia){
  $(document).on("click",campo,function(){
    if(!checkRadioBox(campo)){
      advertenciaEnfocada(contenedor,campo,texto_advertencia);
    }else{
      quitar_advertencia(contenedor);
    }
  });
}

function validacionMayorQue(campo,maximo){
  var val = parseFloat($(campo).val());
  if(val > maximo){
    return false;
  }else{
    return true;
  }
}

function PosicionCaracterCampo(campo,caracter,posicion_anterior){
  $(document).on("keyup",campo,function(e){
    let tecla = (e.keyCode ? e.keyCode : e.which);
    let valor = $(campo).val();
      if(valor.length === posicion_anterior && tecla !== 8){
         $(campo).val(valor+caracter);
      }
  });
}

function ActivarBoton(boton){
  $(boton).removeClass("desactivar");
}

function DesactivarBoton(boton){
  $(boton).addClass('desactivar');
}

function CampoVacio(campo){
  let valor;
  if(typeof campo === 'object'){
    valor = campo.val();
  }else if(typeof campo === 'string'){
    valor = $(campo).val();
  }
  if(valor === "" || valor === 0 || valor === "0"){
    return false;
  }else{
    return true;
  }
}

function CampoPatron(campo,patron){
  let valor;
  if(typeof campo === 'object'){
    valor = campo.val();
  }else if(typeof campo === 'string'){
    valor = $(campo).val();
  }
  if(!patron.test(valor)){
    return false;
  }else{
    return true;
  }
}

function CampoFechaActual(campo){
  let valor = $(campo).val();
    let valor_format = new Date(valor);

    if(valor_format > fecha_actual){
        return false;
    }else{
      return true;
    }
}

function LongitudCampo(campo, longitud_minima=6,longitud_maxima=200){
  let valor = $(campo).val();
  let tam = valor.length;
  if(tam >= longitud_minima && tam <= longitud_maxima){
    return true;
  }else{
    return false;
  }
}

function checkRadioBox(nameRadioBox) {
  return $(nameRadioBox).is(":checked") ? true : false;
}

function checkSelect(idSelect) {
  return (($(idSelect).val() !== "") || ($(idSelect).val() !== 0)) ? true : false;
}

function soloLetras(campo){
  var patron =/[A-Za-z\s]/;
  $(document).on("keypress",campo,function(e){
    var tecla = e.key;
    if (tecla=="Backspace"){
      return true;
    }
    if(!patron.test(tecla)){
      e.preventDefault();
    }
  });
}

function soloNumeros(campo){
  var patron =/^[0-9]$/;
  $(document).on("keypress",campo,function(e){
    var tecla = e.key;
    if (tecla=="Backspace"){
      return true;
    }
    if(!patron.test(tecla)){
      e.preventDefault();
    }
  });
}

function NumerosConDecimal(campo){
  var patron =/^[0-9]$/;
  $(document).on("keydown",campo,function(e){
    let valor = $(campo).val();
    let tecla = e.keyCode;
    let tecla2 = e.key;
    if(tecla2 == "."){
      if(valor.indexOf('.') == -1){
        return true;
      }else{
        e.preventDefault();
      }
    }
    if(tecla == 8){
      return true;
    }
    if(!patron.test(tecla2)){
      e.preventDefault();
    }
  });
}

function numerosDeCedula(campo){
  var patron =/^[0-9]$/;
  $(document).on("keydown",campo,function(e){
    let valor = $(campo).val();
    let tecla = e.keyCode;
    let tecla2 = e.key;
    if(tecla2 == "V" || tecla2 == "E" || tecla2 == "v" || tecla2 == "e"){
      if(valor.indexOf('E') == -1 && valor.indexOf('e') == -1 && valor.indexOf('V') == -1 && valor.indexOf('v') == -1){
        return true;
      }else{
        e.preventDefault();
      }
    }
    if(tecla == 8){
      return true;
    }
    if(!patron.test(tecla2)){
      e.preventDefault();
    }
  });
}

function numerosDeCedulaX(campo){
  var patron =/^[0-9]$/;
  $(document).on("keydown",campo,function(e){
    let valor = $(campo).val();
    let tecla = e.keyCode;
    let tecla2 = e.key;
    if(tecla2 == "V" || tecla2 == "E" || tecla2 == "v" || tecla2 == "e" || tecla2 == "x" || tecla2 == "X"){
      if(valor.indexOf('E') == -1 && valor.indexOf('e') == -1 && valor.indexOf('V') == -1 && valor.indexOf('v') == -1 && valor.indexOf('X') == -1 && valor.indexOf('x') == -1){
        return true;
      }else{
        e.preventDefault();
      }
    }
    if(tecla == 8){
      return true;
    }
    if(!patron.test(tecla2)){
      e.preventDefault();
    }
  });
}

function Decimal_Texto(valor){
    let formato_legible = new Intl.NumberFormat("de-DE").format(valor);
    if(formato_legible.split(",", 2).length == 1){
        formato_legible += ",00";
    }
    return formato_legible;
}

function Texto_Decimal(valor, vacio = ""){
    let cadena_resultante = "";
    let valor_decimal = valor.split(",",2);
    let entero = valor_decimal[0].split(".");
    let decimal = valor_decimal[1];
    for(let m=0; m<entero.length; m++){
      cadena_resultante += entero[m];
    }
    cadena_resultante += "." + decimal;

    if(parseFloat(cadena_resultante) == 0){
        return vacio;
    }else{
        return parseFloat(cadena_resultante);
    }
}

var formatNumber = {
  separador: ".", // separador para los miles
  sepDecimal: ',', // separador para los decimales
  formatear:function (num){
      num +='';
      var splitStr = num.split('.');
      var splitLeft = splitStr[0];
      var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
      var regx = /(\d+)(\d{3})/;
      while (regx.test(splitLeft)) {
        splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
      }
      return this.simbol + splitLeft +splitRight;
  },
  new:function(num, simbol){
    this.simbol = simbol ||'';
    return this.formatear(num);
  }
}
/*
  formatNumber.new(123456779.18, "$") // retorna "$123.456.779,18"
  formatNumber.new(123456779.18) // retorna "123.456.779,18"
  formatNumber.new(123456779) // retorna "$123.456.779"
*/

function mayusculasCampo(campo){
  $(document).on("keyup",campo,function(){
      $(campo).val($(campo).val().toUpperCase());
  });
}

function minusculasCampo(campo){
  $(document).on("keyup",campo,function(){
    $(campo).val($(campo).val().toLowerCase());
  });
}

function unaPalabra(campo,palabras = 1){
  let valor = $(campo).val();
  let array = valor.split(" ");
  let palabrasBlanco = 0;
  if(array.length > palabras || array.length < palabras){
    return false;
  }else{
    for(let i = 0; i < array.length; i++){
      if(array[i].length <= 2){
        palabrasBlanco++;
      }
    }
    if(palabrasBlanco == 0){
      return true;
    }else{
      return false;
    }
  }
}

function periodoActual(mesActual,yearActual){
    if(mesActual >= 7){
        return yearActual + " - " + (parseInt(yearActual)+1);
    }else{
        return (parseInt(yearActual)-1) + " - " + yearActual;
    }
}

function obtenerDatos(datos = {}, render, direccion){
  $.ajax({
      async : false,
      url: direccion,
      type: 'POST',
      dataType: "json",
      data: datos
  })
  .done(function(response){
      let opt = datos.opcion;
      if(datos.opcion == "estudiante_formulario"){
        let valor_radio = response.radios;
        if(valor_radio["cedulado"] == 0){
          render(response, function(){
            $("#caja_contenidoModiCedEst").html(`
                <div id="caja_modificarCedEst" class="column-2m-12 txt-light-pq texto-centrado">
                    Modificar Cedula?
                </div>
                <div class="column-4-12">
                    <div class="column-1m-12">
                        <input type="radio" name="modificarCedEst" id="si_modificar_ced" class="modificarCedEst option-input-completo radio" value="1">
                    </div>
                    <label class="column-1m-12 txt-light-pq" for="si_modificar_ced">Si</label>

                    <div class="column-m-12"></div>

                    <div class="column-1m-12">
                        <input type="radio" name="modificarCedEst" id="no_modificar_ced" class="modificarCedEst option-input-completo radio" value="0" checked="true">
                    </div>
                    <label class="column-1m-12 txt-light-pq" for="no_modificar_ced">No</label>
                </div>
            `);
          });
        }else{
          render(response,()=>{});
        }
      }else{
        render(response,()=>{});
      }
  })
  .fail(function(response) {
      alertar(3, response.responseText);
      setTimeout(function(){
          ocultarAlerta(0.001);
      },6999);
  });
}

function renderizarDatos(datos={}, funcion_final){
  if(!($.isEmptyObject(datos))){
    let selects = datos.selects;
    let radios = datos.radios;
    let checkboxs = datos.checkboxs;
    let textos = datos.textos;
    let informacion = datos.informacion;
    let valor_grado = 0;
    if(selects != 0){
      for(var clave in selects){
        if(clave === "grado_nuevo_ingreso"){
          valor_grado = selects[clave];
          grados_categorias("#"+clave);
          setValueSelect(clave, selects[clave]);
        }else if(clave === "seccion_nuevo_ingreso"){
          secciones_grados("#"+clave, valor_grado);
          setValueSelect(clave, selects[clave]);
        }else{
          setValueSelect(clave, selects[clave]);
        }
      }
    }
    if(radios != 0){
      for(var indice in radios){
        seleccionar_radio("."+indice, radios[indice]);
      }
    }
    if(checkboxs != 0){
      for(var llave in checkboxs){
        seleccionar_radio("."+llave, checkboxs[llave]);
      }
    }
    if(textos != 0){
      for(var key in textos){
        $("#"+key).val(textos[key]);
      }
    }
    if(informacion != 0){
      for(var k in informacion){
        $("#"+k).text(informacion[k]);
      }
    }

    funcion_final();
  }else{
    alertar(3, "¡NO SE HAN RECIBIDO DATOS!");
    setTimeout(function(){
          ocultarAlerta(0.001);
    },3999);
  }
}

function colocarMeses(select, desde, titulo){
    let meses_escritos = "<option value='0'>"+titulo+"</option>";
    for(var i = desde; i <= 12; i++){
        meses_escritos += "<option value='"+i+"'>"+meses[i]+"</option>";
    }
    $(select).html(meses_escritos);
}