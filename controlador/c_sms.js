/**
 *    
 *  Se encarga de validar que no existan valores vacios 
 *  para el formulario de envio de SMS
 *  
 **/
function Validaciones() 
{
    // Verifica que el valor ingresado sea solo letras
this.soloLetras = function soloLetras()
{
    if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
    event.returnValue = false;
};
// Verifica que el valor ingresado sea solo numeros
this.soloNumeros = function SoloNumeros() 
{
    if ((event.keyCode < 48) || (event.keyCode > 57)) 
    event.returnValue = false;
};
// Verifica que los valores del formulario no esten vacios
this.VacioFormulario = function VacioFormulario(formulario)
  {
    var marco = false;
    var todosloschecks = document.getElementsByName('checksTelefonos');
    
  
    for (var i = 0; i < todosloschecks.length; i++)
    {

       if (todosloschecks[i].checked) 
       {
           
           marco = 1;
           
  
       } 
    
    }
    
    if(formulario.fechaEnvio.value.length==0)
    { 
        formulario.fechaEnvio.focus();    // Damos el foco al control
        alert('No has Establecido la Fecha de Envio'); //Mostramos el mensaje
        return false; //devolvemos el foco
  
    } else if(formulario.cedulaPersona.value.length==0) 
    {
        //¿Tiene 0 caracteres?
        formulario.cedulaPersona.focus();    // Damos el foco al control
        alert('No has Establecido la Cedula de la Persona'); //Mostramos el mensaje
        return false; //devolvemos el foco
    
    } else if(!marco) { //¿Tiene 0 caracteres?
        
        formulario.checkGlobal.focus();
        alert('Debes marcar un Telefono'); //Mostramos el mensaje
        return false; //devolvemos el foco
          
    } else if(formulario.contenidoSMS.value.length==0) 
    { 
        //¿Tiene 0 caracteres?
        formulario.contenidoSMS.focus();    // Damos el foco al control
        alert('Debes Elegir un Mensaje'); //Mostramos el mensaje
        return false; //devolvemos el foco

    } else
    {
        return true; //Si ha llegado hasta aquí, es que todo es correcto    
    }
      
       
 }
 // Verifica que el valor indicado como parametro no este vacio
 this.Vacio = function Vacio(texto)
 {
     if (texto == "")
     {
         
         return false;
     } else
     {
         
         return true;
        
     }
     
 }
        
 
  };     
  
 /**
 *    
 *  Se encarga de buscar los telefonos registrados para un deudor por el metodo AJAX
 *  
 *  PARAMETRO
 *  -- idPersona = identificador del deudor al que se desea realizar la busqueda
 *  
 **/
  function buscarTelefonos(idPersona, tipo)
{   
    // Se obtiene el contenedor
    var Escribe = document.getElementById('Escribe');
    

    
    
    var ajax = nuevoAjax();
    
    
    
     ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
                Escribe.innerHTML = ajax.responseText;
                
                document.getElementById('reg_select1').selectedIndex = 0;
                $('#smsContenido').val("");
                $('#cedulaPersona').val($('#CEDULA_Persona').val());
                
        
                //llamaTodo('telefonosSMS-Tabla');
            }
        
     } 
        
        
        
        ajax.open("GET", "../controlador/c_sms.php?cedula="+idPersona+"&tipo="+tipo, true);
        ajax.send();
    
    
}

/**
 *    
 *  Envia los valores del SMS a enviar al servidor por metodo AJAX para ser procesados por este
 *  
 *  PARAMETROS
 *  -- formulario = formulario que contiene los numeros de telefonos
 *  
 **/
function enviarSMS(formulario)
{
 
    // Se obtiene contenedor
    var Escribe = document.getElementById('respuesta');
        
   

    var ajax = nuevoAjax();
    
    ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
               Escribe.innerHTML = ajax.responseText;
            }
        } 
         var telefonos = "";
         var agrega = "";
         var count= 0;
        if (typeof(formulario.checksTelefonos.length) == "undefined" ) {
            
             telefonos = formulario.checksTelefonos.value;
            
        } else {
       // Recorre los checks del formulario para verificar cuales entan activos
        for (var i=0; i < formulario.checksTelefonos.length; i++)
        {
            
          
                if (formulario.checksTelefonos[i].checked)
                {    
                    
                    if (count > 0){
                        agrega = ",";
                    }
                    //Se asignan los telefonos a enviar en una varible
                    telefonos = telefonos+agrega+formulario.checksTelefonos[i].value;
                count++;
                
                }
            
        }
        
    }
        
       
        ajax.open("POST", "../controlador/c_sms.php", true);
        
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
       if (document.getElementById('reg_select1').value == 2 )
        {
            ajax.send("form_Enviar=si&recordatorio=1&cedula="+formulario.cedulaPersona.value+"&fechaEnvio="+formulario.fechaEnvio.value+"&fechaPromesa="+formulario.fechaPromesa.value+"&montoPromesa="+formulario.montoPromesa.value+"&contenidoSMS="+formulario.smsContenido.value+"&telefonos="+telefonos+"&ID_Persona="+formulario.ID_Persona.value);
        } else {
            
            ajax.send("form_Enviar=si&cedula="+formulario.cedulaPersona.value+"&fechaEnvio="+formulario.fechaEnvio.value+"&contenidoSMS="+formulario.smsContenido.value+"&telefonos="+telefonos+"&ID_Persona="+formulario.ID_Persona.value);
        }
      
}



//
// Se encarga de recargar la tabla segun los filtros establecidos por metodo ajax
//
function cargarTabla(fechaInicio, fechaFin, status, filtro)
{  
    var Tabla = document.getElementById('Tabla');
      
    var ajax = nuevoAjax();
    
    ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
               Tabla.innerHTML = ajax.responseText;
               //llamaTodo('smsRecibidos-tabla');
            }
        } 
        
       /* // Se verifican los parametros para establecer que varibles enviar al servidor
        if (idPersona != "NULL")
        {
            
                if (accionador == "fecha")
            {
                ajax.open("GET", "../controlador/c_sms.php?"+accionador+"=si&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&idPersona="+idPersona, true);
            } else if (accionador == "status")
            {

                ajax.open("GET", "../controlador/c_sms.php?"+accionador+"=si&condicion="+select+"&idPersona"+idPersona, true);

            } else
            {
                 ajax.open("GET", "../controlador/c_sms.php?"+accionador+"=si&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&condicion="+select+"&idPersona="+idPersona, true);
            }
            
        } else
        {
            
                if (accionador == "fecha")
            {
                ajax.open("GET", "../controlador/c_sms.php?"+accionador+"=si&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, true);
            } else if (accionador == "status")
            {

                ajax.open("GET", "../controlador/c_sms.php?"+accionador+"=si&condicion="+select, true);

            } else
            {
                 ajax.open("GET", "../controlador/c_sms.php?"+accionador+"=si&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&condicion="+select, true);
            }
        
        }
      */
    if (filtro == "fecha"){
        
         ajax.open("GET", "../controlador/c_sms.php?cargarSmsRecibidos=si&filtro="+filtro+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, true);
    } else if (filtro == "ambos"){
        
          ajax.open("GET", "../controlador/c_sms.php?cargarSmsRecibidos=si&filtro="+filtro+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&status="+status, true);
        
    } else if (filtro == "status"){
        
          ajax.open("GET", "../controlador/c_sms.php?cargarSmsRecibidos=si&filtro="+filtro+"&status="+status, true);
        
    }  else {
    ajax.open("GET", "../controlador/c_sms.php?cargarSmsRecibidos=si&filtro="+filtro, true);
    }
    ajax.send();
    
    
}


//
// Se encarga de exportar los telefonos seleccionados para ver el expediente
//
function verExpediente(formulario)
{
    
    
     var Escribe = document.getElementById('seleccionados');
        
   
         var idSeleccionados = "";
         var agrega = "";
         var count= 0;
        
       
        for (var i=0; i < formulario.checks.length; i++)
        {
            
          
                if (formulario.checks[i].checked)
                {    
                    
                    if (count > 0){
                        agrega = ",";
                    }
                    idSeleccionados = idSeleccionados+agrega+formulario.checks[i].value;
                count++;
                
                }
            
        }
        
        Escribe.innerHTML = "Seleccionastes estos ID: "+idSeleccionados;
       

    
    
    
}


function responderSMS(fila){
    
   document.getElementById('para').innerHTML = "Para: "+document.getElementById('tlf'+fila).innerHTML;
   document.getElementById('tlf').value = document.getElementById('tlf'+fila).innerHTML;
   document.getElementById('cedula').value = document.getElementById('cedulaResp'+fila).innerHTML;
   document.getElementById('idPersona').value = document.getElementById('personaResp'+fila).innerHTML;
        $('#responder_texto').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($('#zx').height());
        return false;
 
   
    
}


function responderEnviar(){
    
        var tlf = document.getElementById('tlf').value;
        var cedula = document.getElementById('cedula').value;
        var persona = document.getElementById('idPersona').value;
        var respuesta = document.getElementById('respuestaSMS').value;
  
	var parametros = {"responder" : 1,
                           "cedula" : cedula,
                           "idPersona" : persona,
                           "respuesta" : respuesta,
                           "tlf" : tlf
                       };
	$.ajax({
		  type: "POST",
		  url: '../controlador/c_sms.php',
		  data: parametros,
		  
		  success: function(result) {
                          $('#result').html(result);
		          $('#respuestaSMS').val("");
                          //$('#responder_texto').fadeOut('slow');
                          //$('.popup-overlay').fadeOut('slow');
        
		   }
  	});
    
}



function buscaVariables(idPlantilla, idPersona){

    var parametros = {"idPlantilla" : idPlantilla,
                           "idPersona" : idPersona,
                           "buscarVaribles" : 1
                           };
	$.ajax({
		  type: "GET",
		  url: '../controlador/c_sms.php',
		  data: parametros,
		  
		  success: function(result) {
                          var cadena = result;
		          //$('#respuestaSMS').val("");
                          //$('#responder_texto').fadeOut('slow');
                          //$('.popup-overlay').fadeOut('slow');
                          $('#smsContenido').val(cadena);
                          if (idPlantilla == 2){
                              
                              var str = $('#smsContenido').val();
                              var ms = Date.parse($('#fechaPromesa').val());
                              var fecha = new Date(ms);
                              //alert(fecha.getDate()+1+"/"+(fecha.getMonth()+1));
                              $('#smsContenido').val(str.replace("MONTO_PROMESA",document.getElementById('montoPromesa').value));
                              str = $('#smsContenido').val();
                              $('#smsContenido').val(str.replace("F_PROMESA",(fecha.getDate()+1)+"/"+(fecha.getMonth()+1)));
                          }
		   }
  	});
    
}


function popupAExpediente(id){
    
    
    win = window.open('?pantalla=7e0c6ba9785691fecdb2418d503d2aab&smsper='+id,null,'toolbars=0');
    
    
}

function contadorSMSRecibios(){
    
        var parametros = {"contador" : 1
                           };
	$.ajax({
		  type: "GET",
		  url: '../controlador/c_sms.php',
		  data: parametros,
		  
		  success: function(result) {
                         
		          
                          
                          if (result < "1"){
                          
                       $('#countSMS').attr("class", "ocultar_noti");
                          
                      } else {
                          
                          $('#countSMS').attr("class", "notificacion");
                          
                      }
                       $('#countSMS').html(result);
                          
		   }
  	});
    
}


function cargarEnviados(fechaInicio, fechaFin, status, filtro)
{
    
    
    var Tabla = document.getElementById('Tabla_SMS_Enviados');
      
    var ajax = nuevoAjax();
    
    ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
               Tabla.innerHTML = ajax.responseText;
               //llamaTodo('smsRecibidos-tabla');
            }
        } 
        
    
    if (filtro == "fecha"){
        
         ajax.open("GET", "../controlador/c_sms.php?cargarSmsEnviados=si&filtro="+filtro+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, true);
    } else if (filtro == "ambos"){
        
          ajax.open("GET", "../controlador/c_sms.php?cargarSmsEnviados=si&filtro="+filtro+"&fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&status="+status, true);
        
    } else if (filtro == "status"){
        
          ajax.open("GET", "../controlador/c_sms.php?cargarSmsEnviados=si&filtro="+filtro+"&status="+status, true);
        
    }  else {
    ajax.open("GET", "../controlador/c_sms.php?cargarSmsEnviados=si&filtro="+filtro, true);
    }
    ajax.send();
    
    
}


function cambiarStatusSMS(id){
  
	var parametros = {"cambiarStatus" : 1,
                          "id" : id
                         };
	$.ajax({
		  type: "GET",
		  url: '../controlador/c_sms.php',
		  data: parametros,
		  
		  success: function(result) {
                     // cargarNotificaciones();
                     $('#'+id).attr("class", "");
                     var filas = $('#SMSRecibido-Tabla > tbody > tr').length;
                     //alert(result);
                   /*  for (var i=0; i < filas; i++){
                         
                            
                       
                         
                     }*/
                     
                     $('#SMSRecibido-Tabla > tbody > tr').each(function (index){
                         
                        //alert(); 
                              if (index%2 == 0) // Vemos si 54 dividido en 2 da resto 0 si lo da
                                { var par = "odd"; } //escribo Par
                                 else //Sino
                                { var par = "even"; }
                                //alert(par);
                                if ($(this).attr("id") == id){  $('#'+id).attr("class", "gradeA "+par); }
                                
                                
                                
                     });
                      contadorSMSRecibios();
                  
		   }
  	});
    
}


/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */
    
            


