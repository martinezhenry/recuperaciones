
/**
 *    
 *  Se encarga de cargar por AJAX los datos de las Exoneraciones
 * 
 *    PARAMETROS:
 *      -- cuenta = la cuenta de la desea buscar los datos
 * 
 **/
function cargarExoneraciones(cuenta)
{
    
    var tabActivo = document.getElementById('tabActivo');
    var contenedor = document.getElementById('exoneraciones-contenedor');
    var saldoActual = document.getElementById('saldoAExonerar');
    
    desactivarBntAgregar();
    var ajax = nuevoAjax();

    saldoActual.value = document.getElementById('saldoActualCuenta').value;
    tabActivo.value = "exoneraciones";


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

            contenedor.innerHTML = ajax.responseText;
            llamaTodo('exoneraciones-Tabla');
                         

        }
    }

    ajax.open("GET", "../controlador/c_exoneraciones.php?cargarExoneraciones=si&cuentaActual=" + cuenta, true);
    ajax.send();
    
}


/**
 *    
 *  Se encarga de calcular el monto a Exonerar y el total a cancelar
 * 
 **/
function calcularExoneracion()
{
    // Se capturan los elementos participanten en exoneraciones
    var saldoActual = document.getElementById('saldoAExonerar');
    var porcentajeExonerar = document.getElementById('%AExonerar');
    var montoExonerado = document.getElementById('montoExonerado');
    var montoCancelar = document.getElementById('MontoACancelar');
    
    
    var saldoActualNum = saldoActual.value;
     // Se formatea cadena a formato moneda con separador de decimales con (.)
   // saldoActualNum = (saldoActualNum).replace(/[.]/g,"");
   // saldoActualNum = (saldoActualNum).replace(/[,]/g,".");
    
    
    var pos = saldoActualNum.indexOf("$");
    if (pos == -1){
    var sep_miles = '.', sep_dec = ',';
    saldoActualNum = (saldoActualNum).replace(/[Bs]/g,"");
    saldoActualNum = (saldoActualNum).replace(/\s/g,"");
    saldoActualNum = (saldoActualNum).replace(/[.]/g,"");
    saldoActualNum = (saldoActualNum).replace(/[,]/g,".");
    } else {
    var sep_miles = ',', sep_dec = '.';
    saldoActualNum = (saldoActualNum).replace(/[$]/g,"");
    saldoActualNum = (saldoActualNum).replace(/\s/g,"");
    //saldoActual = (saldoActual).replace(/[.]/g,"");
    saldoActualNum = (saldoActualNum).replace(/[,]/g,"");
        
        
    }
    
    
    
    saldoActualNum = parseFloat(saldoActualNum);
    
    var porcentajeExonerarNum = parseFloat(porcentajeExonerar.value);
   
    var montoExoneradoNum = ((saldoActualNum*porcentajeExonerarNum)/100);
    
    // Se hace los calculos finales y se le asigna el valor al elemento con formato moneda
    montoExonerado.value = montoExoneradoNum.formatMoney(2,sep_miles, sep_dec);
    montoCancelar.value = (saldoActualNum-montoExoneradoNum).formatMoney(2,sep_miles, sep_dec);
    
    
    
}


/**
 *    
 *  Se encarga de guaradar la Exoneracion
 * 
 **/
function generarExoneracion(cuenta)
{
    
    // Se capturan los calores a guardar
    var cliente = document.getElementById('clienteActual').value;
    var cartera = document.getElementById('carteraActual').value;
    var saldoActual = document.getElementById('saldoAExonerar').value;
    var saldoExonerar = document.getElementById('montoExonerado').value;
    var saldoCancelar = document.getElementById('MontoACancelar').value;
    var tipoPago = document.getElementById('tipoPago').value;
    var cuotas = document.getElementById('cuotasExoneracion').value;
    var fechaCuotas = document.getElementById('fechaCuotasExoneracion').value;
  
    
    var ajax = nuevoAjax();

    
   


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

           alert(ajax.responseText);
           cargarExoneraciones(cuenta);
                         

        }
    }
    
    // Se envian los valores
    ajax.open("GET", "../controlador/c_exoneraciones.php?guardarExoneracion=si&cuentaActual=" + cuenta+"&cliente="+cliente+"&cartera="+cartera+"&saldoActual="+saldoActual+"&saldoExonerar="+saldoExonerar+"&saldoCancelar="+saldoCancelar+"&tipoPago="+tipoPago+"&cuotas="+cuotas+"&fechaCuotas="+fechaCuotas, true);
    ajax.send();
    
    
}

/**
 *    
 *  Se encarga de mostrar u ocultar los elementos de si el pago es fracionado o de contado
 * 
 **/
function mostrarOcultarFracciones(valor)
{
    var diving = document.getElementById('fracciones');
    
    if (valor != "0")
    {
        
        diving.style.display = "block";
    } else
    {
        diving.style.display = "none";
    }
    
}


function aprobarExoneracion(checks)
{
        var marco = false;
     
        var count =0;
      checks = document.getElementsByName(checks);
    var ajax = nuevoAjax();
    
    for (var i=0; i< checks.length;i++){
     
        if (checks[i].checked == true)
        {
            marco = true;
           var cuenta = checks[i].value;
            count++;
           
          
        }
    }
    if (marco){
    if (count == 1)
    {
         
    
   


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

           alert(ajax.responseText);
           // Se recarga la Tabla de convenios
           cargarExoneraciones(document.getElementById('cuentaActual').value);
                         

        }
    }

    ajax.open("GET", "../controlador/c_exoneraciones.php?aprobarExoneracion=si&cuenta="+cuenta, true);
    ajax.send();
    
    } else {
        
        alert('Debe Seleccionar solo una Exoneración');
        
    }
} else {
    
    alert("Debe Seleccionar la Exoneración a Aprobar");
    
}
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */