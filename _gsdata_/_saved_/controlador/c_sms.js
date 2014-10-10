
function colocarTexto(textArea, texto) {
    
  textArea.textContent = texto;    
  
}


function enviarSMS() {
 
 alert("SMS Enviado.");
  
  
}



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

       
    
}
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
    
    
    function enviarDatos(valor, jugador)
    {
        
    //Recogemos los valores introducimos en los campos de texto
               
 
         //Aquí será donde se mostrará el resultado
       // jugador = document.getElementById('div_Tabla1');
 var toma ="";
        //instanciamos el objetoAjax
         var ajax = nuevoAjax();
 
        //Abrimos una conexión AJAX pasando como parámetros el método de envío, y el archivo que realizará las operaciones deseadas
        ajax.open("GET", "../controlador/c_sms.php", true);
 
        //cuando el objeto XMLHttpRequest cambia de estado, la función se inicia
        ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
                toma = (ajax.responseText) 
            }
        } 
 
        //Llamamos al método setRequestHeader indicando que los datos a enviarse están codificados como un formulario. 
        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
 
        //enviamos las variables a 'consulta.php' 
        ajax.send("&filtro="+valor);
        
        jugador.innerHTML = toma;
 alert(valor);  
    }
    
    


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
    var todosloschecks = document.getElementsByName('checksTelefonos[]');

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

        formulario.todosTelefonos.focus();
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
  
  
  function buscarTelefonos(idPersona)
{
    
    var Escribe = document.getElementById('Escribe');
    
    
    var ajax = nuevoAjax();
    
    
    
     ajax.onreadystatechange = function() {
 
             //Cuando se completa la petición, mostrará los resultados 
            if (ajax.readyState == 4){
 
                //El método responseText() contiene el texto de nuestro 'consultar.php'. Por ejemplo, cualquier texto que mostremos por un 'echo'
                Escribe.innerHTML = ajax.responseText;
            }
        } 
        ajax.open("GET", "../controlador/c_sms.php?cedula="+idPersona, true);
        ajax.send();
    
    
}
            
            
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

