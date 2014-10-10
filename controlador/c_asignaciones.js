
/**
 *    
 *  Carga los datos para el resumen de una cuenta de la BD
 * 
 *    PARAMETROS:
 *      -- cuenta = la cuenta de la desea buscar los datos
 * 
 **/
function cargarAsignaciones(cuenta)
{
    // Se capturan los elementos donde se mostraran los datos
    var cliente = document.getElementById('clienteAsignacion');
    var cartera = document.getElementById('carteraAsignacion');
    var tipoCuenta = document.getElementById('tipoCuentaAsignacion');
    var ciudad = document.getElementById('ciudadAsignacion');
    var saldoInicialAsignacion = document.getElementById('saldoInicialAsignacion');
    var saldoAnterior = document.getElementById('saldoAnteriorAsignacion');
    var saldoActual = document.getElementById('saldoActualAsignacion');
    var fechaVencimiento = document.getElementById('fechaVencimientoAsignacion');
   // var areaDevolucion = document.getElementById('areaDevolucionAsignacion');
   // var fechaDevolucion = document.getElementById('fechaDevolucionAsignacion');
    var Devolucion = document.getElementById('devolucionAsignacion');
    var usuarioGestor = document.getElementById('usuarioGestorAsignacion');
    var fechaAsignada = document.getElementById('fechaAsignadaAsignacion');
    var fechaPrimeraAsignacion = document.getElementById('fechaPrimeraAsignacionAsignacion');
    var statusCuenta = document.getElementById('statusCuentaAsignacion');
    var tipoCredito = document.getElementById('tipoCreditoAsignacion');
  //  var sticker = document.getElementById('stickerAsignacion');
    var gestionada = document.getElementById('gestionadaAsignacion');
    var observacionInterna = document.getElementById('observacionInternaAsignacion');
    var observacionExterna = document.getElementById('observacionExternaAsignacion');
    var fechaIngreso = document.getElementById('fechaIngresoAsignacion');
    var fechaUltimaGestion = document.getElementById('fechaUltimaGestionAsignacion');
    var tipoCuentaCliente = document.getElementById('tipoCuentaClienteAsignacion');
    var tipoAsignacion = document.getElementById('tipoAsignacionAsignacion');
    var fechaAsignacion = document.getElementById('fechaAsignacionAsignacion');
    var interesesMora = document.getElementById('interesesMoraAsignacion');
   // var montoTotal = document.getElementById('montoTotalAsignacion');
    var capitalVencido = document.getElementById('capitalVencidoAsignacion');
    // nuevas
    var fechaCastigo = document.getElementById('fechaCastigoAsignacion');
    var fechaLiquidacion = document.getElementById('fechaLiquidacionAsignacion');
    var diasMora = document.getElementById('diasMoraAsignacion');
    var fechaUltPago = document.getElementById('fechaUltPagoAsignacion');
    var montoUltPago = document.getElementById('montoUltPagoAsignacion');
    var capital = document.getElementById('capitalAsignacion');
    var intereses = document.getElementById('interesesNormalesAsignacion');
    
    
    
    // Se desactiva el boton agregar
    desactivarBntAgregar();
    
    var ajax = nuevoAjax();



    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

            var cadena = ajax.responseText;
          //alert(cadena);
            var y = [];
            y = cadena.split("*");
           
            if (y.length == 0){
                
                return;
                
            } else {
            
            // Se convierte la cadena recibida en un arreglo
            
            
            // Se le asignan los valores a cada elemento
            cliente.value = y[0];
            cartera.value = y[1];
            tipoCuenta.value = y[2];
            ciudad.value = y[3];
            saldoInicialAsignacion.value = y[4];
            saldoAnterior.value = y[5];
            saldoActual.value = y[6];
            fechaVencimiento.value = y[7];
     //       areaDevolucion.value = y[8];
       //     fechaDevolucion.value = y[9];
            Devolucion.value = y[10];
            usuarioGestor.value = y[11];
            fechaAsignada.value = y[12];
            fechaPrimeraAsignacion.value = y[13];
            statusCuenta.value = y[14];
            tipoCredito.value = y[15];
           // sticker.value = y[16];
            gestionada.value = y[17];
            observacionInterna.value = y[18];
            observacionExterna.value = y[19];
            fechaIngreso.value = y[20];
            fechaUltimaGestion.value = y[21];
            tipoCuentaCliente.value = y[22];
            tipoAsignacion.value = y[23];
            fechaAsignacion.value = y[24];
            interesesMora.value = y[31];
          //  montoTotal.value = y[26];
            fechaCastigo.value = y[28];
            fechaLiquidacion.value = y[29];
            diasMora.value = y[27];
            fechaUltPago.value = y[37];
            montoUltPago.value = y[36];
            capital.value = y[34];
            capitalVencido.value = y[35];
            intereses.value = y[30];
           // alert(y.length);
        }

        }
    }

    ajax.open("GET", "../controlador/c_asignaciones.php?cargarAsiganaciones=si&cuentaActual=" + cuenta, true);
    ajax.send();
    
    
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */

