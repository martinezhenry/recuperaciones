/**
 *    
 *  Carga los datos de la BD que contiene los tipos de aviso
 *  en un select por el metodo AJAX
 * 
 * 
 **/
function cargarTipoAviso()
{
  
    // Se carga el contenedor a manejar
    var contenedor = document.getElementById('contenedor-selectTipoAviso');
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            // Se recibe la respuesta del servidor
            contenedor.innerHTML = ajax.responseText;
            
      

        }
    }
    // Se indica archivo a tratar en el servidor y varibles
    ajax.open("GET", "../controlador/c_telegramas.php?aviso=si", true);
    ajax.send();
    
}

/**
 *    
 *  Carga los datos de la BD que contiene las direcciones del deudor
 *  en un select por el metodo AJAX
 *  
 *      PARAMETROS
 *      --idPersona = identificador del deudor al que se desea realizar la busqueda
 * 
 * 
 **/
function cargarDirecciones(idPersona)
{
     // Se carga el contenedor a manejar
    var contenedor = document.getElementById('contenedor-selectDireccion');
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            // Se recibe la respuesta del servidor
            contenedor.innerHTML = ajax.responseText;
            
      

        }
    }
    // Se indica archivo a tratar en el servidor y varibles
    ajax.open("GET", "../controlador/c_telegramas.php?cargarDirecciones=si&idPersona="+idPersona, true);
    ajax.send();
}

/**
 *    
 *  Carga los datos de la BD que contiene los telegramas del deudor
 *  en una tabla por el metodo AJAX
 *  
 *      PARAMETROS
 *      --idPersona = identificador del deudor al que se desea realizar la busqueda
 * 
 * 
 **/
function cargarTelegramas(personaActual)
{
    // Se carga el contenedor a manejar
    var contenedor = document.getElementById('contenedor-tablaTelegramas');
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            // Se recibe la respuesta del servidor
            contenedor.innerHTML = ajax.responseText;
            // Se inicia la tabla
            llamaTodo('telegramas-Tabla');
            // Se desactiva el boton agregar
            desactivarBntAgregar();
            
      

        }
    }
    // Se indica archivo a tratar en el servidor y varibles
    ajax.open("GET", "../controlador/c_telegramas.php?cargarTelegramas=si&persona="+personaActual, true);
    ajax.send();
}

/**
 *    
 *  Guarda los telegramas que se desean enviar
 *  en una tabla de la BD por el metodo AJAX
 *  
 *      PARAMETROS
 *      --cuenta = identificador de la cuenta a la que se le enviara el telegrama
 * 
 * 
 **/
function enviarTelegrama(cuenta)
{
    // Se cargan los datos a guardar
    var tipoAviso = document.getElementById('tipoAvisos');
    var textoAviso = document.getElementById('tipoAvisoTexto');
    var idDireccion = document.getElementById('direccionesTelegramas');

    var personaActual = document.getElementById('personaActual');
    
    // Se valida que haya seleccionado un tipo de aviso
    if (tipoAviso.selectedIndex == 0)
    {
        // Mensaje por si no ha seleccionado un tipo de aviso
        alert('Seleccione el Aviso');
        
    } else // En caso de que si selecciono un tipo de aviso
    {
       

        
        var ajax = nuevoAjax();


        ajax.onreadystatechange = function()
        {
            if (ajax.readyState == 4)
            {
                // Se muestra mensaje de respuesta del servidor
               alert(ajax.responseText);
               // Se recarga la tabla de telegramas
               cargarTelegramas(personaActual.value);



            }
        }

        // Se indica archivo a tratar en el servidor y varibles
        ajax.open("GET", "../controlador/c_telegramas.php?enviar=si&tipoAviso="+tipoAviso.value+"&persona="+personaActual.value+"&idDireccion="+idDireccion.value, true);
        ajax.send();
        
    }
        // Se inician los elemento a su estado original
        textoAviso.textContent = "";
        tipoAviso.selectedIndex = 0;
        idDireccion.selectedIndex =  0;
}


/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */