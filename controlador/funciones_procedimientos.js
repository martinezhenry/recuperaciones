

//
// Activa y Desactiva los checkBox pasados como parametros
// 
// PARAMETROS
// 
//     ** checkBoxes: los elementos checkbox que se cambiaran de estado
//      
//     ** accionador: elemento checkbox que indicara que accion realizar (Activar/Desactivar) 
// 
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


//
// Se encarga de generar un objeto Ajax para un intercambio dinamico.
// 
 function nuevoAjax()
{ 
   
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


//
// Toma el texto y lo coloca en el textArea indicado
// 
// PARAMETROS
// 
//     ** textArea: Elemento textArea donde se colocara el texto
//      
//     ** texto: cadena de caracteres que se colocara en el textArea 
// 
function colocarTexto(textArea, texto) {
    
  
  textArea.textContent = texto;
 
  
}

//para editar campo en una tabla
function crearText(campo)
{
    
    campo.innerHTML = "<input type='text' onkeypress='verificaEnter(this.value)'/>";
    campo.focus();
    
}

function compareDate(fecha1, fecha2) {
  var f = new Date();
  var month=new Array();
  month[0]="01";
  month[1]="02";
  month[2]="03";
  month[3]="04";
  month[4]="05";
  month[5]="06";
  month[6]="07";
  month[7]="08";
  month[8]="09";
  month[9]="10";
  month[10]="11";
  month[11]="12";
  fecha2 = fecha2 || (f.getDate() + "/" + (month[f.getMonth()]) + "/" + f.getYear());
  //alert(fecha1+' > '+fecha2);

  var fecha1 = fecha1.split('/');
  var fecha2 = fecha2.split('/');
  var respuesta = false;
  if(fecha1[2] >= fecha2[2])        // Comparamos el año
    if(fecha1[1] >= fecha2[1])      // comparamos el mes
      if(fecha1[1] == fecha2[1])    // verificamos exactamente si el mes es igual
        if(parseFloat(fecha1[0]) >= fecha2[0])  // Comparamos el dia
          return true;              // es mayor
        else
          return false;
      else
        return true;              // es menor         
    else
      return false;
  else
    return false;
}

function cambiarTitulo(titulo){
    
    
    document.title = titulo;
    
}


function pintarTabla(id){
    
    $(id+' tbody tr:odd').attr("class", "gradeA odd");
    $(id+' tbody tr:even').attr("class", "gradeA even");
    
    
}