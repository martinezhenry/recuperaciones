
/**
 *    
 *  Se encarga de cargar por AJAX los datos de los Convenios
 * 
 *    PARAMETROS:
 *      -- cuenta = la cuenta de la desea buscar los datos del convenio
 * 
 **/
function cargarConvenios(cuenta)
{
    // Se capturan los elementos participantes
    var saldoActual = document.getElementById('saldoActualConvenio');
    var contenedor = document.getElementById('contenedor-tablaConvenios');
    
    //se le asigana el valor al campo de saldoactual y se le da Formato moneda
    saldoActual.value = document.getElementById('saldoActualCuenta').value;
    // Se desactiva el Boton de agregar
    desactivarBntAgregar();
    
    
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

            contenedor.innerHTML = ajax.responseText;
            // Se ejecuta la funcion que da formato a la tabla
            llamaTodo('convenios-Tabla');
          
      

        }
    }

    ajax.open("GET", "../controlador/c_convenios.php?cargarConvenios=si&cuenta=" + cuenta, true);
    ajax.send();
    
    
    
}

/**
 *    
 *  Calcula los el monto inicial a cancelar por un deudor de acuerdo al % ingresado
 * 
 **/
function calcularMontoInicial()
{
    // Capturamos saldo actual de la cuenta
    var saldoActual = document.getElementById('saldoActualConvenio').value;
    
    // Se formatea cadena a formato moneda con separador de decimales con (.)
    var pos = saldoActual.indexOf("$");
    if (pos == -1){
    var sep_miles = '.', sep_dec = ',';
    saldoActual = (saldoActual).replace(/[Bs]/g,"");
    saldoActual = (saldoActual).replace(/\s/g,"");
    saldoActual = (saldoActual).replace(/[.]/g,"");
    saldoActual = (saldoActual).replace(/[,]/g,".");
    } else {
    var sep_miles = ',', sep_dec = '.';
    saldoActual = (saldoActual).replace(/[$]/g,"");
    saldoActual = (saldoActual).replace(/\s/g,"");
    //saldoActual = (saldoActual).replace(/[.]/g,"");
    saldoActual = (saldoActual).replace(/[,]/g,"");
        
        
    }
    
    // Capturamos el porcentaje de descuento
    var porcentajeInicial = document.getElementById('porcentajeInicialConvenio').value;
    // Se obtiene el elemento que contendra el valor del monto de inicial
    var montoInicial = document.getElementById('montoInicialConvenio');
    // Se calcula el monto de inicial que corresponde
    montoInicial.value = ((parseFloat(saldoActual)*parseFloat(porcentajeInicial))/100).formatMoney(2,sep_miles,sep_dec);
}


/**
 *    
 *  calcula el monto a cancelar por cuota de un convenio
 * 
 * 
 **/
function calcularMontoPorCuotas()
{
    var cuotas = document.getElementById('cuotasConvenio').value;
    var montoPorCuotas = document.getElementById('montoPorCuotasConvenio');
    var saldoActual = document.getElementById('saldoActualConvenio').value;
    var montoInicial = document.getElementById('montoInicialConvenio').value;

    // Se formatea cadena a formato moneda con separador de decimales con (.)
   var pos = saldoActual.indexOf("$");
    if (pos == -1){
    var sep_miles = '.', sep_dec = ',';
    saldoActual = (saldoActual).replace(/[Bs]/g,"");
    saldoActual = (saldoActual).replace(/\s/g,"");
    saldoActual = (saldoActual).replace(/[.]/g,"");
    saldoActual = (saldoActual).replace(/[,]/g,".");
    } else {
    var sep_miles = ',', sep_dec = '.';
    saldoActual = (saldoActual).replace(/[$]/g,"");
    saldoActual = (saldoActual).replace(/\s/g,"");
    //saldoActual = (saldoActual).replace(/[.]/g,"");
    saldoActual = (saldoActual).replace(/[,]/g,"");
        
        
    }
    // Se formatea cadena a formato moneda con separador de decimales con (.)
   // montoInicial = (montoInicial).replace(/[.]/g,"");
    //montoInicial = (montoInicial).replace(/[,]/g,".");
    
   // var pos = montoInicial.indexOf("$");
    if (pos == -1){
    var sep_miles = '.', sep_dec = ',';
    montoInicial = (montoInicial).replace(/[Bs]/g,"");
    montoInicial = (montoInicial).replace(/\s/g,"");
    montoInicial = (montoInicial).replace(/[.]/g,"");
    montoInicial = (montoInicial).replace(/[,]/g,".");
    } else {
    var sep_miles = ',', sep_dec = '.';
    montoInicial = (montoInicial).replace(/[$]/g,"");
    montoInicial = (montoInicial).replace(/\s/g,"");
    //saldoActual = (saldoActual).replace(/[.]/g,"");
    montoInicial = (montoInicial).replace(/[,]/g,"");
        
        
    }
    
    montoPorCuotas.value = ((parseFloat(saldoActual)-parseFloat(montoInicial))/parseInt(cuotas)).formatMoney(2,sep_miles,sep_dec);
}

/**
 *    
 *  Se encarga de guardar los datos ingresados del convenio por AJAX
 * 
 * 
 **/
function guardarConvenio(cuenta)
{
    
    // Se capturan los datos que se desean guardar
    var cliente = document.getElementById('clienteActual').value;
    var cartera = document.getElementById('carteraActual').value;
    var saldoActual = document.getElementById('saldoActualConvenio').value;
    var porcentajeInicial = document.getElementById('porcentajeInicialConvenio').value;
    var montoInicial = document.getElementById('montoInicialConvenio').value;
    var cuotas = document.getElementById('cuotasConvenio').value;

    
    var ajax = nuevoAjax();

    
   


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

           alert(ajax.responseText);
           // Se recarga la Tabla de convenios
           cargarConvenios(cuenta);
                         

        }
    }

    ajax.open("GET", "../controlador/c_convenios.php?guardarConvenio=si&cuentaActual="+cuenta+"&cliente="+cliente+"&cartera="+cartera+"&saldoActual="+saldoActual+"&porcentajeInicial="+porcentajeInicial+"&montoInicial="+montoInicial+"&cuotas="+cuotas, true);
    ajax.send();
    
    
}


function aprobarConvenio(checks){
    
    var marco = false;
     
      
      checks = document.getElementsByName(checks);
    var ajax = nuevoAjax();
    
    for (var i=0; i< checks.length;i++){
     
        if (checks[i].checked == true)
        {
            marco = true;
            var convenio = checks[i].value;
          
        }
    }
   
   if (marco){

    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

           alert(ajax.responseText);
           // Se recarga la Tabla de convenios
           cargarConvenios(document.getElementById('cuentaActual').value);
                         

        }
    }

    ajax.open("GET", "../controlador/c_convenios.php?aprobarConvenio=si&convenio="+convenio, true);
    ajax.send();
    
    } else {
        
        alert("Debe Seleccionar el Convenio a Aprobar");
        
        
    }
    
    
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */