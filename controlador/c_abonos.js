
function cargarAbonos(cuenta)
{
    
    var tabActivo = document.getElementById('tabActivo');
    var contenedor = document.getElementById('abonos');
    var bntGuardar = document.getElementById('guardar');
    var bntAgregar = document.getElementById('agregar');
    var bntEliminar = document.getElementById('eliminar');
    var bntCancelar = document.getElementById('cancelar');
    var ajax = nuevoAjax();
    


    bntCancelar.style.display = 'none';
    bntGuardar.disabled = true;
    bntAgregar.disabled = false;
    bntEliminar.disabled = true;
    tabActivo.value = "abonos";


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

            contenedor.innerHTML = ajax.responseText;
            llamaTodo('abonos-Tabla');
                         

        }
    }

    ajax.open("GET", "../controlador/c_deudor.php?abonos=si&cuentaActual=" + cuenta, true);
    ajax.send();
    
    
}


function guardarAbonos(cuenta)
{
    
  
    //var fechaGestion = document.getElementById('numAbono').innerHTML;
    var cliente = document.getElementById('clienteActual').value;
    var cartera = document.getElementById('carteraActual').value;
    var tipoCuenta = document.getElementById('tipoCuentaActual').value;
    var usuarioGestor = document.getElementById('usuarioGestorActual').value;
    var numeroDeposito = document.getElementById('nroDepositoAbono').value;
    var fechaDeposito = document.getElementById('fechaAbono').value;
    var montoDeposito = document.getElementById('montoAbono').value;
    var formaPago = document.getElementById('formaPagoAbono').value;
    var banco = document.getElementById('bancoAbono').value;
    var statusAbono = document.getElementById('statusAbono').value;
    var observacion = document.getElementById('observaciones').value;
    var fechaIngreso = document.getElementById('fechaIngresoAbono').value;
    var cuota = document.getElementById('cuotaAbono').value;
    
    
    var contenedor = document.getElementById('guardarContenedor');
    
    var ajax = nuevoAjax();
    
    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

            //contenedor.innerHTML = ajax.responseText;
           // alert(ajax.responseText);
           
                         
                 if (ajax.responseText == 1) {
                   
                   alertify.success("Abono Guardado.");
                    cargarAbonos(cuenta);
               } else {
                   
                   alertify.error(ajax.responseText);
                   return;
               }
                         

        }
    }

    ajax.open("GET", "../controlador/c_deudor.php?guardarAbonos=si&cliente="+cliente+"&cartera="+cartera+"&tipoCuenta="+tipoCuenta+"&usuarioGestor="+usuarioGestor+"&numeroDeposito="+numeroDeposito+"&fechaDeposito="+fechaDeposito+"&montoDeposito="+montoDeposito+"&formaPago="+formaPago+"&banco="+banco+"&statusAbono="+statusAbono+"&observacion="+observacion+"&fechaIngreso="+fechaIngreso+"&cuota="+cuota+"&numCuenta="+cuenta, true);
    ajax.send();
    
}


function eliminarAbono()
{
    var idAbonos = "";
   
    var cuenta = document.getElementById('cuentaActual').value;
    var checkAbonos = document.getElementsByName('check-abonos');
    var ajax = nuevoAjax();
    
    var agrega = "";
    var count = 0;
    for (var i = 0; i < checkAbonos.length; i++)
    {
        
        if (checkAbonos[i].checked == true)
        {
            
            if (count == 0)
            {
                agrega = "";
                
            } else
            {
                agrega = ",";
            }
            
            idAbonos += agrega+checkAbonos[i].value;
            count++;
        }
        
    }
   
   
    
    ajax.onreadystatechange = function()
            {
                if (ajax.readyState == 4)
                {
                    
                 var contenedor = ajax.responseText;
                 if (contenedor == "elimino")
                 {
                    cargarAbonos(cuenta);
                    alert("Eliminado(s) con Existo");
                 }
    
                }
                
             
                              
            }
                
               ajax.open("GET", "../controlador/c_deudor.php?eliminarAbonos=si&idAbonos="+idAbonos, true);
               

               ajax.send();
               
}




function modificarAbono(){
    
   //  var checkGestiones = document.getElementsByName('check1');
     
    // alert($('#check1:checked').length);
   //  alert($('#check-abonos:checked').length);
     if ($('#check-abonos:checked').length == 0){
         alert('Debe Seleccionar un Abono');
         return false;
     } if ($('#check-abonos:checked').length > 1){
         
         alert('Solo Debe Seleccionar un Abono');
         return false;
         
         
     } else {
         
         var fila = $('#check-abonos:checked').val();
        // agregarFila('gestiones-Tabla');
         //alert($('#'+fila+' td').length);
         
         $('#'+fila+' td').each(function(idx){
         //   alert(idx);
          //  alert($(this).text()); 
            if (idx > 2){
                
              if (idx == 7){
                  //  alert('3');
                   var htmlformapago = '';
                   var htmlBanco = '';
                    var parametros = {
                        selectAbonos : 'si'
                        //cliente : $('#clienteActual').val()
                    };
                    
                    
                    
                    $.ajax({
                       
                        url : '../controlador/c_deudor.php',
                        type : 'get',
                        data : parametros,
                        success : function (resp){
                            var cadena = resp;
                  
                            
                            
                         //    var formaPagoAbono = document.createElement("select");
                  // var bancoAbono = document.createElement("select");
                   // se obtiene respuesta del servidor
                 //  var cadena = ajax.responseText;
                   // caracteristicas del elemento
                 //  formaPagoAbono.style.width = '90px';
                //   formaPagoAbono.className = "selectEditar";
                 //  formaPagoAbono.name = "formaPagoAbono";
                //   formaPagoAbono.id = "formaPagoAbono";
                   //Arreglos que contendran los valores
                   var arrGeneral = [];
                   var arrFormaPago = [];
                   var arrBanco = [];
                   var individual = [];
                   var individual2 = [];
                   
                 // Se obtiene arreglo general
                   arrGeneral = cadena.split("*");
                   
                   
                      // Se obtien arreglo de las formas de pago 
                   arrFormaPago = arrGeneral[0].split(";");
                   
                   
                   htmlformapago += '<select class="selectEditar" id="txt7" style="width:90px;">';
                   
                   // Se rrecorre el arreglo
                   for (var i = 0; i < arrFormaPago.length; i++)
                   {

                    individual = arrFormaPago[i].split(",");
                    htmlformapago += '<option value="'+individual[0]+'">'+individual[1]+'</option>';
                  // Se  le asigna una nueva opcion al elemento select
                //    formaPagoAbono.options[i] = new Option(individual[1], individual[0]);
                                        
                   }
                       htmlformapago += '</select>';     
                       
                       htmlBanco += '<select class="selectEditar" id="txt9" style="width:90px;">';
                       
                   // arreglo con los bancos
                   arrBanco = arrGeneral[1].split(";");
                       for (var i = 0; i < arrBanco.length; i++)
                   {

                    individual2 = arrBanco[i].split(",");
                    htmlBanco += '<option value="'+individual2[0]+'">'+individual2[1]+'</option>';
                  // Se  le asigna una nueva opcion al elemento select
                //   bancoAbono.options[i] = new Option(individual2[1], individual2[0]);
                                        
                   }
                           
                      htmlBanco += '</select>';   
                                        
                            //alert(element2.innerHTML);
                            
                            $('#'+fila+' td:nth-child(8)').html(htmlformapago);
                            $('#'+fila+' td:nth-child(10)').html(htmlBanco);
                        }
                        
                    });
                    
                          //cadena devuelta por el servidor
                 
                     
                 //    alert(htmltipogestion);
                    
               //  $(this).html('<input class="editar" size="6" type="text" value ="'+$(this).text()+'"/>');
                } else if (idx == 9){
                    
                    
                    
                } else if (idx == 10){
                
                $(this).html('<select id="txt'+idx+'" class="selectEditar">\n\
                <option value="C">Confirmado</option>\n\
                <option value="N">No Confirmado</option>\n\
                </select>');
                
                 } else {                
                $(this).html('<input id="txt'+idx+'" class="editar" size="6" type="text" value ="'+$(this).text()+'"/>');
            }
             
             
             }
            
             
            
            
             
         });
         
         $('#observaciones').removeAttr('readonly');
         
         $('#guardar').attr('onclick', 'guardarModificacionA()');
         $('#guardar').removeAttr('disabled');
         
     }
    
    
    
    
}


function guardarModificacionA(){
    
      if ($('#check-abonos:checked').length == 0){
         alert('Debe Seleccionar un Abono');
         return false;
     } if ($('#check-abonos:checked').length > 1){
         
         alert('Solo Debe Seleccionar un Abono');
         return false;
         
         
     } else {
    
    
     var fila = $('#check-abonos:checked').val();
     
      
      var fabono = $('#txt3').val();
      var mabono = $('#txt4').val();
      var numdeposito = $('#txt5').val();
      var cuota = $('#txt6').val();
      var formapago = $('#txt7').val();
      var fechaingreso = $('#txt8').val();
      var banco = $('#txt9').val();
      var status = $('#txt10').val();
      var observacion = $('#observaciones').val();
      
      var parametros = {
         modificarAbono : 1,
         abono : fila,
         fabono : fabono,
         mabono : mabono,
         numdeposito : numdeposito,
         cuota : cuota,
         formapago : formapago,
         fechaingreso : fechaingreso,
         banco : banco,
         status : status,
         cuota : cuota,
         observacion : observacion
     };
      
      
      $.ajax({
          
          type : 'get',
          data : parametros,
          url : '../controlador/c_deudor.php',
          success : function(resp){
              
            // alert(resp);
              
              if (resp == 0){
                  
                  alert("No esta autorizado para realizar modificaciones");
                  $('#gestiones-2').click();
                  return;
              } else {
                  
                  alert(resp);
                  $('#gestiones-2').click();
                  return;
              }
              
              
          }
          
          
      });
      
    //  alert(tipogestion);
      
     
        // agregarFila('gestiones-Tabla');
         //alert($('#'+fila+' td').length);
         /*
         $('#'+fila+' td').each(function(idx){
             
             $.ajax({
                 
                 type : 'post',
                 data : parametros,
                 url : '../controlador/c_deudor.php',
                 success : function(resp){
                     
                     
                     
                 }
                 
                 
             });
             
             
         });*/
    
   // alert('modificar');
     }
    
}

