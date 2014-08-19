/**
 *    
 *  Carga los datos de las cuotas de una cuenta de la BD por el metodo AJAX
 * 
 *    PARAMETROS:
 *      -- cuenta = la cuenta de la desea buscar los datos
 * 
 **/
function cargarCuotas(cuenta)
{
    // Se captura el elemento de tab que contiene el tab activo
    var tabActivo = document.getElementById('tabActivo');
    // Se captura al contenedor para el AJAX
    var contenedor = document.getElementById('cuotas-contenedor');
    // Se desactiva el boton agragar
    desactivarBntAgregar();

    var ajax = nuevoAjax();
    // Se establece el tab activo correspondiente
    tabActivo.value = "cuotas";


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            // Se recibe la respuesta del servidor
            contenedor.innerHTML = ajax.responseText;
            // Se inicia la tabla
            llamaTodo('cuotas-Tabla');
            $("#txt_cant_cuotas").val($("#cuotas-Tabla tbody tr").length);
            var total = 0.0;
            var sep_miles, sep_dec;
            $(".saldoTotal_cuotas").each(function(idx){
                
               
                
                var monto = $(this).html();

                
                var pos = monto.indexOf("$");
                if (pos == -1){
                sep_miles = '.', sep_dec = ',';
                monto = (monto).replace(/[Bs]/g,"");
                monto = (monto).replace(/\s/g,"");
                monto = (monto).replace(/[.]/g,"");
                monto = (monto).replace(/[,]/g,".");
                } else {
                sep_miles = ',', sep_dec = '.';
                monto = (monto).replace(/[$]/g,"");
                monto = (monto).replace(/\s/g,"");
                //saldoActual = (saldoActual).replace(/[.]/g,"");
                monto = (monto).replace(/[,]/g,"");
            }
            
            total = total+parseFloat(monto);
              
            });
            
            total = total.formatMoney(2, sep_miles, sep_dec);
            $("#txt_monto_total").val(total);
            

        }
    }
    
    // Se envian las varibles y se indica el archivo a tratar
    ajax.open("GET", "../controlador/c_deudor.php?cuotas=si&cuentaActual=" + cuenta, true);
    ajax.send();
    
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */