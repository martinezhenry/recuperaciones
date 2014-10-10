

//
//Toma el texto de parametro y lo coloca en el objeto de parametro
//


function cargarEmail(persona){
    
   // alert(persona);
    
    var parametros = {
      cargarEmail : 1,
      persona : persona
    };
    var html = "";
    $.ajax({
        
        type : 'post',
        url : '../controlador/c_email.php',
        data : parametros,
        success : function(resp){
              
              
              if (resp == "0"){
                  html= "<tr class='odd'><td class='dataTables_empty' colspan='3'>No Existen Emails</td></tr>";
                
              } else {
            var emails = JSON.parse(resp);
            
       
            
            for (var i=0; i < emails.length; i++){
                
                
                if (emails[i].STATUS_EMAIL == 'A'){
                    var otro_option = "<option value='I'>Inactivo</option>";
                } else {
                    var otro_option = "<option value='A'>Activo</option>";
                }
                
                html += "<tr id='"+emails[i].ID_EMAIL+"'>";
                html += "<td class='center'>"+emails[i].DIRECCION+"</td>";
                html += "<td class='center'>"+emails[i].PARENTESCO+"</td>";
                html += "<td class='center'><select class='selectEditar' disabled><option value='"+emails[i].STATUS_EMAIL+"'>"+emails[i].STATUS_DESC+"</option>"+otro_option+"</select></td>";
                html += "</tr>";
            }
            
            
              }
        }
        
    }).complete(function(){
     
    $('#tabla-emails tbody').html(html);
   // alert(html);
        
    });
    
    
    
}



function guardarEmails(persona){
    //alert(persona);
    var respuesta = confirm ('Esta Seguro de Guardar Los Cambios?');
    var ids = new Array();
    var nuevos = new Array();
    $('#tabla-emails tbody tr').each(function(idx){
        
        if ($(this).attr('id') == 'nueva'){
            
            if ($(this).find('input').val() == ""){
                alertify.error("La direccion no puede ser vacia");
                $(this).find('input').focus();
                nuevos = null;
                return;
            }
            
           if (ValidaEmail($(this).find('input').val()) == false){
               
               alertify.error("Correo No Valido");
                $(this).find('input').focus();
                nuevos = null;
                return;
               
           }
            
            nuevos.push(new Array ($(this).find('input').val(), $(this).find('select:enabled').val()));
           
        } else {
            if($(this).find('select:enabled').length){
            ids.push(new Array($(this).attr('id'), $(this).find('select:enabled').val()));
        }
        }
        
    });
    
    if (nuevos == null){
        return false; 
    }
    
    if (respuesta){
       
        var parametros = {
            guardarEmails : 1,
            ids : ids,
            nuevos : nuevos,
            persona : persona
            
            
        };
        $.ajax({
            
            type : 'post',
            url : '../controlador/c_email.php',
            data : parametros,
            success : function (resp){
                alert(resp);
                if (resp == "1"){
                    alertify.success("Cambios Guardados");
                    $('#sky-tab1-4').click();
                }
                else
                    alertify.error("Error al realizar los cambios");
                
            }
            
            
        });
        
        
        
    } else {
        
        return;
    }
    
}



function editarEmail(){
    $('#tabla-emails select').removeAttr('disabled');
    $('#buttonGuardarEmail').removeAttr('disabled');
    
}


function agregarEmail(){
    
    
    $('#tabla-emails tbody').prepend("<tr id='nueva'><td class='center'><input class='editar' style='width:100%;' type='text'/></td><td class='center'><select class='selectEditar' id='parentescos_email'></select></td><td class='center'><select class='selectEditar'><option value='A'>Activo</option></select></td></tr>");
    
    $.ajax({
        
        type : 'post',
        url : '../controlador/c_email.php',
        data : {cargarParentesco:1},
        success : function(resp){
            
            if (resp == "0"){
                return;
            }
            
            var arr = JSON.parse(resp);
            for (var i=0;i<arr.length;i++){
            $('#parentescos_email').append("<option value='"+arr[i].ID_PAR+"'>"+arr[i].PARENTESCO+"</option>");
        }
            
            
        }
        
    });
    
    $('#buttonGuardarEmail').removeAttr('disabled');
    
}



function enviarEmail() {
 
 alert("Email Enviado.");
  
}


/*
function activarDesactivarChecks(checkBoxes, accionador)
{   

    var accion = 0;
    var todosloscheck = checkBoxes;
   
    if(accionador.checked == 1)
    {
        accion = 1;
        
    } else 
    {
        accion = 0;

    }
    
    for (var i = 0; i < todosloscheck.length; i++) 
    {
        todosloscheck[i].checked = accion;
    }

       
    
}*/
/*
function contenido(contenido)
{
    
    var textArea = document.getElementById("reg_textarea2");
    textArea.textContent = contenido;
}*/

function filtroBuscar(valor)
{

    alert(valor);

}

   function nuevoAjax()
{ 
   /* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
   lo que se puede copiar tal como esta aqui */
   var xmlhttp=false; 
   try 
   { 
       // Creacion del objeto AJAX para navegadores no IE
       xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
   }
   catch(e)
   { 
       try
       { 
           // Creacion del objet AJAX para IE 
           xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
       } 
       catch(E) { xmlhttp=false; }
   }
   if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); } 

   return xmlhttp; 
}
    
    

/*
function Validaciones() 
{
    
this.soloLetras = function soloLetras()
{
    if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
    event.returnValue = false;
};

this.soloNumeros = function SoloNumeros() 
{
    if ((event.keyCode < 48) || (event.keyCode > 57)) 
    event.returnValue = false;
};

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
  */
  
  function buscarCorreos(idPersona)
{
    var Escribe = document.getElementById('Escribe2');
    
    
    var ajax = nuevoAjax();
    
    
    
     ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
                Escribe.innerHTML = ajax.responseText;
            }
        } 
        ajax.open("GET", "../controlador/c_email.php?cedula="+idPersona, true);
        ajax.send();
}

/*
function enviarSMS(formulario)
{
    var Escribe = document.getElementById('respuesta');
    
    var ajax = nuevoAjax();
    
    ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
               Escribe.innerHTML = ajax.responseText;
            }
        } 
         var telefonos = [];
        for (var i=0; i < formulario.checksTelefonos.length; i++)
        {
            if (formulario.checksTelefonos[i].checked == 1)
            telefonos[i] = formulario.checksTelefonos[i].value;
        }
       
        
        
        ajax.open("POST", "../controlador/c_email.php", true);
        
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
       
        ajax.send("form_Enviar=si&cedula="+formulario.cedulaPersona.value+"&fechaEnvio="+formulario.fechaEnvio.value+"&fechaPromesa="+formulario.fechaPromesa.value+"&montoPromesa="+formulario.montoPromesa.value+"&contenidoSMS="+formulario.contenidoSMS.textContent+"&telefonos="+telefonos+"&ID_Persona="+formulario.ID_Persona[0].value);
       
}

*/
/*
function leerSMS(formulario)
{
    
}

      */      
            
            /*
            function tomarTelefonos()
{
    var lista = document.getElementsByName('checksTelefonos');
  alert(lista);
    for (var i=0;i<lista.length;i++) {
              if (lista[i].checked == true ) {
                 var grupos = lista[i].value;
                 alert(grupos);
                 
                 }
              }
  
    
    
}*/

