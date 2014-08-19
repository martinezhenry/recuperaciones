/**
 *    
 *  Carga las observaciones de una cuenta por metodo AJAX
 *  
 *  PARAMETROS
 *  -- cuenta = cuenta a la que se desea buscar en la BD
 *  
 **/
function cargarObservaciones(cuenta)
{
    //Elementos que contendrar los valores obtenidos
    var observacionInterna = document.getElementById('observacionInterna');
    var observacionExterna = document.getElementById('observacionExterna');
    //Se desactiva el boton agregar
    desactivarBntAgregar();
    var ajax = nuevoAjax();

    


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            // Cadena devuelta con los datos por el servidor
            var cadena = ajax.responseText;
            // Variable de arreglo
            var arrObservacion = [];
            // Se convirerte la cadena a un arreglo
            arrObservacion = cadena.split("*");
            
            // Se le asignan los valores a los elementos
            observacionInterna.value = arrObservacion[0];
            observacionExterna.value = arrObservacion[1];
            
                         

        }
    }

    ajax.open("GET", "../controlador/c_observaciones.php?observaciones=si&cuentaActual=" + cuenta, true);
    ajax.send();
}
/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */