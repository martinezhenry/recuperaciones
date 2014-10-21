
/**
 *    
 *  Carga las gestiones de una cuenta de la BD por ajax
 * 
 *    PARAMETROS:
 *      -- cuenta = la cuenta de la que desea buscar los datos
 *      -- cliente = la cliente de la cuenta
 *      -- cartera = la cartera de la cuenta
 *      -- usuarioGestor = usuario al cuenta
 *      -- tipoCuenta = el tipo de cuenta de la cuenta
 *      -- saldoActualT = saldo actual que posee la cuenta
 * 
 **/
function cargarGestiones(cuenta, cliente, cartera, usuarioGestor, tipoCuenta, saldoActualT)
{
    //Se camturan los elementos participantes
    var tabActivo = document.getElementById('tabActivo');
    var gestiones = document.getElementById('gestiones');
    var cuentaActual = document.getElementById('cuentaActual');
    var clienteActual = document.getElementById('clienteActual');
    var carteraActual = document.getElementById('carteraActual');
    var tipoCuentaActual = document.getElementById('tipoCuentaActual');
    var usuarioGestorActual = document.getElementById('usuarioGestorActual');
    var saldoActualCuenta = document.getElementById('saldoActualCuenta');
   
    var bntGuardar = document.getElementById('guardar');
    var bntAgregar = document.getElementById('agregar');
    var bntEliminar = document.getElementById('eliminar');
    var bntModificar = document.getElementById('modificar');
    var bntCancelar = document.getElementById('cancelar');
    var observaciones = document.getElementById('observaciones');
   // alert('ges');
  // tabActivo.value = "gestiones";
    var check = document.getElementsByName('check');
    //Se activan y desactivan los botones segun funcion
    bntCancelar.style.display = 'none';
    bntGuardar.disabled = true;
    bntAgregar.disabled = false;
    bntEliminar.disabled = false;
    bntModificar.disabled = false;
    $('#btnReporteGestion').removeAttr("disabled");
    
   
    //$("input[name='sky-tabs-2']:checked").click();
    //Se le asignan los valores de cuenta actual a los elementos pertenecientes
    
    cuentaActual.value= cuenta;
    clienteActual.value = cliente;
    carteraActual.value = cartera;
    usuarioGestorActual.value = usuarioGestor;
    tipoCuentaActual.value = tipoCuenta;
    saldoActualCuenta.value = saldoActualT;
   

    // Se colocar cuadro de observaciones vacio y solo lectura
    observaciones.readOnly= true;
    observaciones.textContent = "";
  //  alert(cuenta);
   // gestiones.innerHTML = cuenta;
           if (($("input[name='sky-tabs-2']:checked").attr('id')).replace('gestiones-','') !== "1"){
        
  //   alert($("input[name='sky-tabs-2']:checked").attr('id'));
     $("input[name='sky-tabs-2']:checked").click();
       return;
        
    } 
    
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
         //   alert('gestiones');
            gestiones.innerHTML = ajax.responseText;
          //  $('#cuentas-Tabla tbody tr td').attr('onclick', 'cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value, saldoActualCuenta.value);');
            llamaTodo('gestiones-Tabla');
         //   $('#AgregarActivo').val('0');
          

        }
    }

    ajax.open("GET", "../controlador/c_deudor.php?gestiones=si&idCuenta=" + cuenta, true);
    ajax.send();
    
    
    
}




/**
 *    
 *  Activa el elemento de observaciones para su modificacion
 * 
 **/
 function activarArea()
 {
    // Se captura el elemento
    var observaciones = document.getElementById('observaciones');
    
    observaciones.readOnly= false;
    observaciones.textContent = "";
   
 }

/**
 *    
 *  Genera una nueva fila en una tabla con campos para ser almacenados en esta
 * 
 *    PARAMETROS:
 *      -- tableID = identificador de la tabla al la que se desa agregar la fila
 * 
 **/
        function agregarFila(tableID) {
            // Se captura la tabla a participar
               var table = document.getElementById(tableID);
               var cliente = document.getElementById('clienteActual').value;
              
               // Se capturan los otros elemntos participantes
                var bntGuardar = document.getElementById('guardar');
                var bntAgregar = document.getElementById('agregar');
                var bntCancelar = document.getElementById('cancelar');
             //   alert(tableID);
               // $('#gestiones-1').attr('onclick', '$("#guardar").removeAttr("disabled")');
                
                //$('#cuentas-Tabla tbody tr td').off('click','**');
                $('#AgregarActivo').val('1');
               
                // Se activan o desactivan botones de acuerdo a funcion
                 bntAgregar.disabled = true;
                 bntGuardar.disabled = false;
                 bntCancelar.style.display='inline';
                // Variable para la fecha local
               var fecha = new Date();
               // Se verifica que identificador se envio
               if (tableID == "gestiones-Tabla")
               {
                //   alert('ges');
                   //se vrea variable de nueva fila
               var row = table.insertRow(1);
               // Se activa el elemento de observaciones
                activarArea();
               //Se le asigan una clase a la fila
               row.className = "gradeX";
               row.id= 'nueva';
               // Se crean la celda
               var cell0 = row.insertCell(0);
               //Sele asigna una clase a la celda
                cell0.className = "pb";
                // Se le asiga contenido a la celda
                cell0.appendChild(document.createTextNode(''));
                
                
                //====================================================
                
                var cell1 = row.insertCell(1);
               //Sele asigna una clase a la celda
                cell1.className = "pb";
                // Se le asiga contenido a la celda
                cell1.appendChild(document.createTextNode(''));
                
                //====================================================
                
                
//================
                // Se cre a la celda
               var cell2 = row.insertCell(2);
               // Se le coloca identificador a la celda
               cell2.id = "fGestion";
               // Se obtiene el mes actual
               var mesActual = (fecha.getMonth() + 1);
               if (mesActual < 10)
               {
                   mesActual = "0" + mesActual;
               } else
               {
                   mesActual = mesActual;
               }
               
               var diaActual = (fecha.getDate());
               
                      if (diaActual < 10)
               {
                   diaActual = "0" + diaActual;
               } else
               {
                   diaActual = diaActual;
               }
               
               // Variable que contiene la fecha
               var element1 = diaActual + "/" + (mesActual) + "/" + fecha.getFullYear();
        
               // Se le asigna la varible a la celda
               cell2.appendChild(document.createTextNode(element1));
//==================
               
                
                var ajax = nuevoAjax();
               
              // var cadena = "";

            ajax.onreadystatechange = function()
            {
                if (ajax.readyState == 4)
                {
                    //cadena devuelta por el servidor
                   var cadena = ajax.responseText;
                  //Variables tipo arreglo que contendran el contenido
                   var arrGeneral = [];
                   var arrTipoGestion = [];
                   var arrParentescos = [];
                   var individual = [];
                   var individual2 = [];
                   // se crea la celda
                   var cell3 = row.insertCell(3);
                   
                         
                // Se crea un elemento select
                 var element3 = document.createElement("select");
                 
                
            
                   // Se crea una elemento select
                   var element2 = document.createElement("select");
                   // Se le asigna un identificador al elemento
                   element2.id = "tipoGestion";
                   // Se le establece un estilo al elemento
                   element2.style.width= '90px';
                   // Seles da una clase a los elemntos
                   element2.className = "selectEditar";
                   element3.className = "selectEditar";
                   element2.setAttribute("tabindex", "1");
                  // element2.focus();
                   element3.setAttribute("tabindex", "6");
                    //Se crea arreglo del la cadena
                   arrGeneral = cadena.split("*");
                   
                   
                       //Se crea una arreglo para los valores del tipo de gestion 
                   arrTipoGestion = arrGeneral[0].split(";");
                   // Se recorre el arreglo creado
                   for (var i = 0; i < arrTipoGestion.length; i++)
                {
                    // Se crea un arreglo para los tipos de gestion de forma individual
                    individual = arrTipoGestion[i].split(",");
                  
                  //Se le asigna un nuevo registro al elemnto select con el valor del tipo de gestion individual
                    element2.options[i] = new Option(individual[1], individual[0]);
                    
                        }
                        // se crea un arreglo para los parentescos
                  arrParentescos = arrGeneral[1].split(";");
                  
                  
                  // Se recorre el arreglo
                    for (var i=0; i < arrParentescos.length; i++)
                    {
                        //Se cre arreglo con el contenido individual
                         individual2 = arrParentescos[i].split(",");
                         //se le asugna una nueva opcion al elemento select creado
                    element3.options[i] = new Option(individual2[1], individual2[0]);

                    }
                    //se le da una clase a la celda
                     cell3.className = "pb";
                     // Se le estable un elemnto a la celda
                     cell3.appendChild(element2);
                      
                      
                        //=============
                        
                        // Se crea una celda
               var cell4 = row.insertCell(4);
               
                // se crea un nuevo elemento input
                var area = document.createElement("input");
                // Se le dan caracteristicas al elemento
               area.type = "text";
               area.name = "area";
               area.id = "area";
               area.size = 3;
               area.className = "editar";
               area.setAttribute("onkeydown", "muestraInformacion(event, 5);");
               area.setAttribute("maxlength", "3");
               area.setAttribute("tabindex", "2");
               cell4.className = "pb";
               cell4.appendChild(area);
               
                
                
                //=============
                
                //Se crea una celda
                var cell5 = row.insertCell(5);
              // se crea un nuevo elemento input
                var telefono = document.createElement("input");
                    
                 // Se le dan caracteristicas al elemento
               telefono.type = "text";
               telefono.name = "telefono";
               telefono.id = "telefono";
               telefono.size = 8;
               telefono.className = "editar";
               telefono.setAttribute("onkeydown", "muestraInformacion(event, 6);");
               telefono.setAttribute("maxlength", "11");
               telefono.setAttribute("tabindex", "3");
               cell5.className = "pb";
               cell5.appendChild(telefono);
               
                //==============
                //Se crea una celda
                var cell6 = row.insertCell(6);
                
                  // se crea un nuevo elemento input
                var nombre = document.createElement("input");
                 // Se le dan caracteristicas al elemento
               nombre.type = "text";
               nombre.name = "nombre";
               nombre.id = "nombre";
               nombre.size = 10;
               nombre.className = "editar";
               nombre.setAttribute("onkeydown", "muestraInformacion(event, 7);");
               nombre.setAttribute("tabindex", "4");
               cell6.className = "pb";
               cell6.appendChild(nombre);
               
                //================
                 //Se crea una celda
                var cell7 = row.insertCell(7);
                // se crea un nuevo elemento input
                var apellido = document.createElement("input");
                // Se le dan caracteristicas al elemento
               apellido.type = "text";
               apellido.name = "apellido";
               apellido.id = "apellido";
               apellido.size = 10;
               apellido.className = "editar";
               apellido.setAttribute("onkeydown", "muestraInformacion(event, 8);");
               apellido.setAttribute("tabindex", "5");
               cell7.className = "pb";
               cell7.appendChild(apellido);
               
                
                //==============
             //Se crea una celda                
             var cell8 = row.insertCell(8);
             // Se le dan caracteristicas al elemento
             element3.id = "parentesco";
             element3.style.width= '90px';
             cell8.className = "pb";
            // cell8.setAttribute("onkeydown", "muestraInformacion(event, 9);");
             //Se le asigna elemento a la celda
             cell8.appendChild(element3);
               
               
               //=============
               var cell9 = row.insertCell(9);
                
                  
                var fProximaGestion = document.createElement("input");

               fProximaGestion.type = "date";
               fProximaGestion.name = "fProximaGestion";
               fProximaGestion.id = "fProximaGestion";
               fProximaGestion.style.width = "125px";
               fProximaGestion.className = "editar";
               
              // fProximaGestion.setAttribute("onkeydown", "muestraInformacion(event, 10);");
               fProximaGestion.setAttribute("tabindex", "7");
               
               cell9.className = "pb";
               cell9.appendChild(fProximaGestion);
               
                
                //=============
                var cell10 = row.insertCell(10);
                  
                var hProximaGestion = document.createElement("input");

               hProximaGestion.type = "time";
               hProximaGestion.name = "hProximagestion";
               hProximaGestion.id = "hProximaGestion";
               hProximaGestion.size = 6;
               hProximaGestion.className = "editar";
             //  hProximaGestion.setAttribute("onkeydown", "muestraInformacion(event, 11);");
               hProximaGestion.setAttribute("tabindex", "8");
             //  hProximaGestion.setAttribute("maxlength", "5");
               cell10.className = "pb";
               cell10.appendChild(hProximaGestion);
               
                //==============
                var cell11 = row.insertCell(11);
                
                  
               var fPromesa = document.createElement("input");

               fPromesa.type = "date";
               fPromesa.name = "fPromesa";
               fPromesa.id = "fPromesa";
               fPromesa.style.width = "125px";
               fPromesa.className = "editar";
               fPromesa.setAttribute("tabindex", "9");
               cell11.className = "pb";
               cell11.appendChild(fPromesa);
               
                //================
                var cell12 = row.insertCell(12);
                
                  
               var mPromesa = document.createElement("input");

               mPromesa.type = "text";
               mPromesa.name = "mPromesa";
               mPromesa.id = "mPromesa";
               mPromesa.size = 6;
               mPromesa.className = "editar";
               mPromesa.setAttribute("onkeydown", "muestraInformacion(event, 13);");
               mPromesa.setAttribute("tabindex", "10");
               cell12.className = "pb";
               cell12.appendChild(mPromesa);

                      
                      
                       $('#guardar').attr('onclick', 'guardarGestion()');
                       $('#telefono').numeric(false);
                       $('#area').numeric(false);
                       $('#mPromesa').numeric('.');
                       $('#hProximaGestion').numeric(':');
                    //   alert($('#'+tableID+' .row_selected').text());
                       
                       $('#nueva').insertBefore(('#'+tableID+' .row_selected'));
                       $('#tipoGestion').focus();
     
                }
                
             
                              
                }
                
               ajax.open("GET", "../controlador/c_deudor.php?tipoGestiones=si&cliente="+cliente, true);
               ajax.send();
               
               //Se verifica el identificador de la tabla
           } else if (tableID == "abonos-Tabla")
           {
              // Se crea una fila en la tabla
               var row = table.insertRow(1);
               // se activa el area de observaciones
               activarArea();
               // Se le asigan una clase a la fila
               row.className = "gradeX";
                
               //Se crea celda
               var cell0 = row.insertCell(0);
               //Se le asiga clase a la celda
               cell0.className = "pb";
               //se agrega elemnto a la celda
               cell0.appendChild(document.createTextNode(''));
               
               // Se crea la celda
               var cell1 = row.insertCell(1);
               // Se crea elemento input
               var numAbono = document.createElement("input");
               //Caracteristicas del elemento
               numAbono.type = "text";
               numAbono.name = "numAbono";
               numAbono.id = "numAbono";
               numAbono.disabled = true;
               numAbono.size = 12;
              // calse de la celda
               cell1.className = "pb";
               // se carga el elemento en la celda
               cell1.appendChild(numAbono);
               
               // Se repiten los mismos procedimientos
               var cell2 = row.insertCell(2);
               var nombreAsesor = document.createElement("input");
               var usuarioGestorActual = document.getElementById("usuarioGestorActual");

               nombreAsesor.type = "text";
               nombreAsesor.name = "nombreAsesor";
               nombreAsesor.id = "nombreAsesor";
               nombreAsesor.size = 12;
               nombreAsesor.disabled = true;
               nombreAsesor.value = usuarioGestorActual.value;
               nombreAsesor.className = "editar";
               cell2.className = "pb";
               cell2.appendChild(nombreAsesor);
               
               
               var cell3 = row.insertCell(3);
               var fechaAbono = document.createElement("input");

               fechaAbono.type = "date";
               fechaAbono.name = "fechaAbono";
               fechaAbono.id = "fechaAbono";
               fechaAbono.style.width = "128px";
               fechaAbono.size = 6;
               fechaAbono.className = "editar";
               cell3.className = "pb";
               cell3.appendChild(fechaAbono);
               
               var cell4 = row.insertCell(4);
               var montoAbono = document.createElement("input");

               montoAbono.type = "text";
               montoAbono.name = "montoAbono";
               montoAbono.id = "montoAbono";
               montoAbono.size = 8;
               montoAbono.className = "editar";
                
               cell4.className = "pb";
               cell4.appendChild(montoAbono);
               
               
                var cell5 = row.insertCell(5);
               var nroDepositoAbono = document.createElement("input");

               nroDepositoAbono.type = "text";
               nroDepositoAbono.name = "nroDepositoAbono";
               nroDepositoAbono.id = "nroDepositoAbono";
               nroDepositoAbono.size = 12;
               nroDepositoAbono.className = "editar";
               cell5.className = "pb";
               cell5.appendChild(nroDepositoAbono);
               
               
               var cell6 = row.insertCell(6);
               var cuotaAbono = document.createElement("input");

               cuotaAbono.type = "text";
               cuotaAbono.name = "cuotaAbono";
               cuotaAbono.id = "cuotaAbono";
               cuotaAbono.size = 4;
               cuotaAbono.className = "editar";
               cell6.className = "pb";
               cell6.appendChild(cuotaAbono);
               
               
               var cell7 = row.insertCell(7);
              
               var ajax = nuevoAjax();
               
         
               
            ajax.onreadystatechange = function()
            {
                
                if (ajax.readyState == 4)
                {
                    //Se crean elemntos select
                   var formaPagoAbono = document.createElement("select");
                   var bancoAbono = document.createElement("select");
                   // se obtiene respuesta del servidor
                   var cadena = ajax.responseText;
                   // caracteristicas del elemento
                   formaPagoAbono.style.width = '90px';
                   formaPagoAbono.className = "selectEditar";
                   formaPagoAbono.name = "formaPagoAbono";
                   formaPagoAbono.id = "formaPagoAbono";
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
                   // Se rrecorre el arreglo
                   for (var i = 0; i < arrFormaPago.length; i++)
                   {

                    individual = arrFormaPago[i].split(",");
                  // Se  le asigna una nueva opcion al elemento select
                    formaPagoAbono.options[i] = new Option(individual[1], individual[0]);
                                        
                   }
                   // arreglo con los bancos
                   arrBanco = arrGeneral[1].split(";");
                       for (var i = 0; i < arrBanco.length; i++)
                   {

                    individual2 = arrBanco[i].split(",");
                  // Se  le asigna una nueva opcion al elemento select
                    bancoAbono.options[i] = new Option(individual2[1], individual2[0]);
                                        
                   }
                   
                   //clase de la celda
                    cell7.className = "pb";
                    // Se ingresa elemento ewn la celda
                    cell7.appendChild(formaPagoAbono);
                    
                    
                    //Se repiten mismos pasos por celda
                    var cell8 = row.insertCell(8);
                    var fechaIngresoAbono = document.createElement("input");

                         var mesActual = (fecha.getMonth() + 1);
                    if (mesActual < 10)
                    {
                        mesActual = "0" + mesActual;
                    } else
                    {
                        mesActual = mesActual;
                    }


                    fechaIngresoAbono.type = "text";
                    fechaIngresoAbono.name = "fechaIngresoAbono";
                    fechaIngresoAbono.id = "fechaIngresoAbono";
                    fechaIngresoAbono.value = fecha.getDate() + "/" + (mesActual) + "/" + fecha.getFullYear();
                    fechaIngresoAbono.disabled = true;
                    fechaIngresoAbono.size = 12;
                    fechaIngresoAbono.className = "editar";
                    cell8.className = "pb";
                    cell8.appendChild(fechaIngresoAbono);


                    var cell9 = row.insertCell(9);


                    
                    bancoAbono.name = "bancoAbono";
                    bancoAbono.id = "bancoAbono";
                    bancoAbono.style.width = "90px";
                  
                    bancoAbono.className = "selectEditar";
                    cell9.className = "pb";
                    cell9.appendChild(bancoAbono);

                    var cell10 = row.insertCell(10);
                    var statusAbono = document.createElement("input");

                    statusAbono.type = "text";
                    statusAbono.name = "statusAbono";
                    statusAbono.id = "statusAbono";
                    statusAbono.value = "N";
                    statusAbono.disabled = true;
                    statusAbono.size = 6;
                    statusAbono.className = "editar";
                    cell10.className = "pb";
                    cell10.appendChild(statusAbono);
                    
                          var cell11 = row.insertCell(11);
                    var file = document.createElement("input");

                    file.type = "file";
                    file.name = "archivo";
                    file.id = "archivo";
                    //file.textContent = "Adjuntar";
                   // file.setAttribute('href','#');
                    file.setAttribute('style',"width: 86;");
                    //file.disabled = true;
                    file.size = 6;
                    file.className = "editar";
                    cell11.className = "pb";
                    cell11.appendChild(file);
                    
              
               $('#guardar').attr('onclick', 'guardarAbonos($(\'#cuentaActual\').val())');
               $('#montoAbono').numeric('.');
               $('#cuotaAbono').numeric(false);
               $('#nroDepositoAbono').numeric(false);
               $('#nueva').insertBefore(('#'+tableID+' .row_selected'));
                }
            }
            
               ajax.open("GET", "../controlador/c_deudor.php?selectAbonos=si&cartera="+$('#carteraActual').val()+"&cliente="+$('#clienteActual').val(), true);
               ajax.send();
               
               
               
           }
                
                
            }
            
            

/**
 *    
 *  Se encarga de guardar las gestiones por metodo ajax
 * 
 *    PARAMETROS:
 *      -- cuenta = identificador de la cuenta que se desea guardar
 * 
 **/
function guardarGestion(cuenta)
{
    //alert(cuenta);
  // se obtienen los valores que se desean guardar
    var fechaGestion = document.getElementById('fGestion').innerHTML;
    var concatenacion = document.getElementById('tipoGestion').value;
    var areaTelefono = document.getElementById('area').value;
    var telefono = document.getElementById('telefono').value;
    var nombre = document.getElementById('nombre').value;
    var apellido = document.getElementById('apellido').value;
    var parentesco = document.getElementById('parentesco').value;
    var observaciones = document.getElementById('observaciones').value;
    var fProximaGestion = document.getElementById('fProximaGestion').value;
    var hProximaGestion = document.getElementById('hProximaGestion').value;
    var fPromesa = document.getElementById('fPromesa').value;
    var mPromesa = document.getElementById('mPromesa').value;
    var cliente = document.getElementById('clienteActual').value;
    var cartera = document.getElementById('carteraActual').value;
    var usuarioGestor = document.getElementById('usuarioGestorActual').value;
    var tipoCuenta = document.getElementById('tipoCuentaActual').value;
    var saldoActualCuenta = document.getElementById('saldoActualCuenta').value;
    var error =  document.getElementById('verError');
    var parentesco_texto = $('#parentesco option:selected').text();
    //alert(parentesco_texto);
    
  //  exit();
    cuenta = $('#cuentaActual').val();
    
    
   // arreglo que tiene los tipos de gestion, tabla gestion, y grupo gestion
    var arrConcatenacion = [];
    arrConcatenacion = concatenacion.split(".");
    
    var tipoGestion = arrConcatenacion[0];
    var tablaGestion = arrConcatenacion[1];
    var grupoGestion = arrConcatenacion[2];
    // Contenedor a utilizar por ajax
    var contenedor = document.getElementById('guardarContenedor');
   
 //  alert("../controlador/c_deudor.php?guardarGestion=si&Numcuenta="+cuenta+"&fechaGestion="+fechaGestion+"&tipoGestion="+tipoGestion+"&tablaGestion="+tablaGestion+"&grupoGestion="+grupoGestion+"&area="+areaTelefono+"&telefono="+telefono+"&nombre="+nombre+"&apellido="+apellido+"&parentesco="+parentesco+"&observaciones="+observaciones+"&fProximaGestion="+fProximaGestion+"&hProximaGestion="+hProximaGestion+"&fPromesa="+fPromesa+"&mPromesa="+mPromesa+"&cliente="+cliente+"&cartera="+cartera+"&usuarioGestor="+usuarioGestor);
    
    
   var parametros = {
       guardarGestion : 'si',
       Numcuenta : cuenta,
       fechaGestion : fechaGestion,
       tipoGestion : tipoGestion,
       tablaGestion : tablaGestion,
       grupoGestion : grupoGestion,
       area : areaTelefono,
       telefono : telefono,
       nombre : nombre,
       apellido : apellido,
       parentesco : parentesco,
       observaciones : observaciones,
       fProximaGestion : fProximaGestion,
       hProximaGestion : hProximaGestion,
       fPromesa : fPromesa,
       mPromesa : mPromesa ,
       cliente : cliente,
       cartera : cartera,
       usuarioGestor : usuarioGestor,
       parentesco_texto : parentesco_texto
   };
   
   
   $.ajax({
       
       url : '../controlador/c_deudor.php',
       type : 'get',
       data : { validarTelefono : 1, area : areaTelefono, telefono: telefono, persona : $('#personaActual').val() }
      
       
   }).done(function (resp){
       
       
               //   alert(resp);
           
           if (resp == 1){
               
              
               
           } else {
               
               if (confirm('Deseas  guardar este número como  numero de contacto del deudor? El mismo se guardara en la ficha de teléfonos del deudor')){
                   
                   $.ajax({
                       
                        url : '../controlador/c_deudor.php',
                        type : 'get',
                        data : {guardarTelefono : 1, area : areaTelefono, telefono: telefono, persona : $('#personaActual').val()}
                       
                       
                   }).done(function(resp2){
                       
                     //  alert(resp);
                       if (resp2 != 1){
                           
                            alertify.error("Error al registrar telefono del contacto."); 
                           return 0;
                       }
                       
                       
                   });
                   
               }
               
           }
      
   $.ajax({
         
         type : 'get',
         data : parametros,
         url : '../controlador/c_deudor.php',
         success : function(resp){
             
                                // Respuesta del servidor
                // contenedor.innerHTML = ajax.responseText;
              //  error.innerHTML= ajax.responseText;
            //   alert(resp);
               
               if (resp == 1) {
                   
                   alertify.success("Gestion Guardada.");
                  // validarCopia();
                   $('#AgregarActivo').val('0');
                    $('#gestiones-1').click();
                //  cargarCuentas($('#personaActual').val());
                //   cargarGestiones(cuenta, cliente, cartera, usuarioGestor, tipoCuenta, saldoActualCuenta);
                  // $('#cuentas-Tabla tbody tr td').attr('onclick', 'cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value, saldoActualCuenta.value);');
                //   $('#gestiones-1').attr('onclick', 'cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value, saldoActualCuenta.value);');
               } else {
                   
                   alertify.error(resp);
                   return;
               }
               
                //alertify.success(ajax.responseText);
                
               // $('#verError').html(ajax.responseText);
                 // Se recarga tabla de gestiones
                 
             
         }
            
            
    }).done(function(){
        
        
       validarCopia();
        
        
    }); 
   
   
   });
       
   
   
    /*
    var ajax = nuevoAjax();
    
    ajax.onreadystatechange = function()
            {
                if (ajax.readyState == 4)
                {
                    // Respuesta del servidor
                // contenedor.innerHTML = ajax.responseText;
              //  error.innerHTML= ajax.responseText;
               alert(ajax.responseText);
                //alertify.success(ajax.responseText);
                
               // $('#verError').html(ajax.responseText);
                 // Se recarga tabla de gestiones
                 cargarGestiones(cuenta, cliente, cartera, usuarioGestor, tipoCuenta, saldoActualCuenta);
                 
    
                }
                
             
                              
            }
                
               ajax.open("GET", "../controlador/c_deudor.php?guardarGestion=si&Numcuenta="+cuenta+"&fechaGestion="+fechaGestion+"&tipoGestion="+tipoGestion+"&tablaGestion="+tablaGestion+"&grupoGestion="+grupoGestion+"&area="+areaTelefono+"&telefono="+telefono+"&nombre="+nombre+"&apellido="+apellido+"&parentesco="+parentesco+"&observaciones="+observaciones+"&fProximaGestion="+fProximaGestion+"&hProximaGestion="+hProximaGestion+"&fPromesa="+fPromesa+"&mPromesa="+mPromesa+"&cliente="+cliente+"&cartera="+cartera+"&usuarioGestor="+usuarioGestor, true);
               

               ajax.send();*/
               
             
    
}


/**
 *    
 *  Se encarga de eliminar una gestion por medio de ajax
 * 
 **/
function eliminarGestion()
{
    // Valores a utilizar
    var idGestiones = "";
    var cliente = document.getElementById('clienteActual').value;
    var cartera = document.getElementById('carteraActual').value;
    var usuarioGestor = document.getElementById('usuarioGestorActual').value;
    var cuenta = document.getElementById('cuentaActual').value;
    var tipoCuenta = document.getElementById('tipoCuentaActual').value;
    var saldoActualCuenta = document.getElementById('saldoActualCuenta').value;
    var checkGestiones = document.getElementsByName('check1');
    var ajax = nuevoAjax();
    
    var agrega = "";
    var count = 0;
    // Se comprueba que check estan activos para eliminar
    for (var i = 0; i < checkGestiones.length; i++)
    {
        
        if (checkGestiones[i].checked == true)
        {
            
            if (count == 0)
            {
                agrega = "";
                
            } else
            {
                agrega = ",";
            }
            
            idGestiones += agrega+checkGestiones[i].value;
            count++;
        }
        
    }
   
   if (count == 0){
       
      // alert('Debe Seleccionar al Menos una Gestion a Eliminar');
       alertify.error('Debe Seleccionar al Menos una Gestion a Eliminar');
       return false;
   }
    
    ajax.onreadystatechange = function()
            {
                if (ajax.readyState == 4)
                {
                    // Respuesta del servidor
                 var contenedor = ajax.responseText;
               //  alert(contenedor);
                 // se verifica respuesta
                 if (contenedor == 1)
                 {
                     //alert("Eliminado(s) con Existo");
                     alertify.success('Eliminado(s) con Existo');
                     // Se recarga tabla
                   cargarGestiones(cuenta, cliente, cartera, usuarioGestor, tipoCuenta, saldoActualCuenta);
                    // Se muestra mensaje al usuario
                    
                 } else {
                     
                        alertify.error('Error no esperado: '+contador);
                     
                     
                 }
    
                }
                
             
                              
            }
                // Se envia los valores
               ajax.open("GET", "../controlador/c_deudor.php?eliminarGestiones=si&idGestiones="+idGestiones, true);
               

               ajax.send();
               
    
    
    
}

function modificarGestion(){
    
   //  var checkGestiones = document.getElementsByName('check1');
     
    // alert($('#check1:checked').length);
     
     if ($('#check1:checked').length == 0){
         //alert('Debe Seleccionar una cuenta');
         alertify.error('Debe Seleccionar una cuenta');
         return false;
     } if ($('#check1:checked').length > 1){
         
        // alert('Solo Debe Seleccionar una cuenta');
         alertify.error('Solo Debe Seleccionar una cuenta');
         return false;
         
         
     } else {
         
         var fila = $('#check1:checked').val();
        // agregarFila('gestiones-Tabla');
         //alert($('#'+fila+' td').length);
         
         $('#'+fila+' td').each(function(idx){
         //   alert(idx);
          //  alert($(this).text()); 
            if (idx > 1){
                
                if (idx == 3){
                  //  alert('3');
                   var htmltipogestion = '';
                   var htmlparentesco = '';
                    var parametros = {
                        tipoGestiones : 'si',
                        cliente : $('#clienteActual').val()
                    };
                    
                    
                    
                    $.ajax({
                       
                        url : '../controlador/c_deudor.php',
                        type : 'get',
                        data : parametros,
                        success : function (resp){
                            var cadena = resp;
                  //Variables tipo arreglo que contendran el contenido
                   var arrGeneral = [];
                   var arrTipoGestion = [];
                   var arrParentescos = [];
                   var individual = [];
                   var individual2 = [];
                   // se crea la celda
             
                   
                         
                // Se crea un elemento select
               //  var element3 = document.createElement("select");
                 
                
            
                   // Se crea una elemento select
                 //  var element2 = document.createElement("select");
                   // Se le asigna un identificador al elemento
               //    element2.id = "tipoGestion";
                   // Se le establece un estilo al elemento
                 //  element2.style.width= '90px';
                   // Seles da una clase a los elemntos
                 //  element2.className = "selectEditar";
                  // element3.className = "selectEditar";
                    //Se crea arreglo del la cadena
                   arrGeneral = cadena.split("*");
                   
                   
                       //Se crea una arreglo para los valores del tipo de gestion 
                   arrTipoGestion = arrGeneral[0].split(";");
                    htmltipogestion += '<select class="selectEditar" id="txt3" style="width:90px;">';
                   // Se recorre el arreglo creado
                   for (var i = 0; i < arrTipoGestion.length; i++)
                {
                    // Se crea un arreglo para los tipos de gestion de forma individual
                    individual = arrTipoGestion[i].split(",");
                    htmltipogestion += '<option value="'+individual[0]+'">'+individual[1]+'</option>';
                  //Se le asigna un nuevo registro al elemnto select con el valor del tipo de gestion individual
                   // element2.options[i] = new Option(individual[1], individual[0]);
                    
                        }
                        
                        htmltipogestion += '</select>';
                        
                        
                         
                         
                       // se crea un arreglo para los parentescos
                  arrParentescos = arrGeneral[1].split(";");
                  
                 htmlparentesco += '<select class="selectEditar" id="txt8" style="width:90px;">';
                  // Se recorre el arreglo
                    for (var i=0; i < arrParentescos.length; i++)
                    {
                        //Se cre arreglo con el contenido individual
                         individual2 = arrParentescos[i].split(",");
                         htmlparentesco += '<option value="'+individual2[0]+'">'+individual2[1]+'</option>';
                         //se le asugna una nueva opcion al elemento select creado
                  //  element3.options[i] = new Option(individual2[1], individual2[0]);

                    }
                           htmlparentesco += '</select>';
                            //alert(element2.innerHTML);
                            
                            $('#'+fila+' td:nth-child(4)').html(htmltipogestion);
                            $('#'+fila+' td:nth-child(9)').html(htmlparentesco);
                        }
                        
                    });
                    
                          //cadena devuelta por el servidor
                 
                     
                 //    alert(htmltipogestion);
                    
               //  $(this).html('<input class="editar" size="6" type="text" value ="'+$(this).text()+'"/>');
                } if (idx == 8){
                    
                    
                    
                }else {                
                $(this).html('<input id="txt'+idx+'" class="editar" size="6" type="text" value ="'+$(this).text()+'"/>');
            }
            
             }
            
            
             
         });
         
         $('#observaciones').removeAttr('readonly');
         
         $('#guardar').attr('onclick', 'guardarModificacion()');
         $('#guardar').removeAttr('disabled');
         
     }
    
    
    
    
}


function guardarModificacion(){
    
      if ($('#check1:checked').length == 0){
        // alert('Debe Seleccionar una gestion');
          alertify.error('Debe Seleccionar una gestion');
         return false;
     } if ($('#check1:checked').length > 1){
         
         //alert('Solo Debe Seleccionar una gestion');
         alertify.error('Solo Debe Seleccionar una gestion');
         return false;
         
         
     } else {
    
    
     var fila = $('#check1:checked').val();
     
      
      var fgestion = $('#txt2').val();
      var tipogestion = $('#txt3').val();
      var area = $('#txt4').val();
      var telefono = $('#txt5').val();
      var nombre = $('#txt6').val();
      var apellido = $('#txt7').val();
      var parentesco = $('#txt8').val();
      var fproximagestion = $('#txt9').val();
      var hproximagestion = $('#txt10').val();
      var fpromesa = $('#txt11').val();
      var mpromesa = $('#txt12').val();
      var observaciones = $('#observaciones').val();
      var cuenta = $('#cuentaActual').val();
      var cliente =  $('#clienteActual').val();
      var cartera = $('#carteraActual').val();
   //   alert(cuenta);
      var parametros = {
         modificarGestion : 1,
         gestion : fila,
         fgestion : fgestion,
         tipogestion : tipogestion,
         area : area,
         telefono : telefono,
         nombre : nombre,
         apellido : apellido,
         parentesco : parentesco,
         fproximagestion : fproximagestion,
         hproximagestion : hproximagestion,
         fpromesa : fpromesa,
         mpromesa : mpromesa,
         observaciones : observaciones,
        cuentaModif : cuenta,
        clienteModif : cliente,
        carteraModif : cartera
         
         
     };
      
      
      $.ajax({
          
          type : 'get',
          data : parametros,
          url : '../controlador/c_deudor.php',
          success : function(resp){
              
          //   alert(resp);
              
              if (resp == 0){
                  
                  //alert("No esta autorizado para realizar modificaciones");
                  alertify.error('No esta autorizado para realizar modificaciones');
                  $('#gestiones-1').attr('onclick', 'cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value, saldoActualCuenta.value);');
                  $('#gestiones-1').click();
                 // return;
              } else if(resp==1)
              {
                  alertify.success("Modificacion Realizada");
                  $('#gestiones-1').attr('onclick', 'cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value, saldoActualCuenta.value);');
                  $('#gestiones-1').click();
                 // return;
              } else {
                  
                 alertify.error(resp);
                  
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



/**
 *    
 *  Activa el boton de eliminar
 * 
 *    PARAMETROS:
 *      -- checks = elemntos checks que se recorreran para su verificacion
 * 
 **/
function activarBntEliminar(checks)
{   
    // SE OBTIENE EL BOTON
    var bntEliminar = document.getElementById('eliminar');
    // variable que indica la accion
    var activa = false;
    
    // Se recorren los checks
    for (var i=0; i < checks.length; i++)
    {
        // Se verifica que este activo
         if (checks[i].checked == true)
        {
            // Se le asigna valor a la accion
            activa = true;
        }
    }
    // Se verifica la accion a tratar
    if (activa)
    {
        // Se activa boton
        bntEliminar.disabled = false;
    } else 
    {
        // se desactiva boton
        bntEliminar.disabled = true;
    }
    
}

/**
 *    
 *  desactiva el botn agregar
 *  
 **/
function desactivarBntAgregar()
{
    // se obtiene el boton
    var bntAgregar = document.getElementById('agregar');
    // Se desactiva boton
    bntAgregar.disabled = true;
}



function crearReporteGestiones(cuenta){
    
    
       // Externo.setAttribute('target', '_blank');
       window.open("../Vista/Reportes/ReporteGestiones.php?c="+cuenta,"_blank");

    
    
}


function muestraInformacion(evento, posicion) {
 
 
    var tecla=(document.all) ? evento.keyCode : evento.which; 
   
 if (tecla === 38 && evento.ctrlKey){
     
     //alert($('#gestiones-Tabla tbody tr:nth-child(2) td:nth-child('+posicion+')').text());
    // alert((evento.target).id);
     //$('#'+(evento.target).id).val($('#gestiones-Tabla tbody tr:nth-child(2) td:nth-child('+posicion+')').text());
     
     $('#'+(evento.target).id).val($('#gestiones-Tabla .row_selected td:nth-child('+posicion+')').text());
     
     //alert($('#gestiones-Tabla .row_selected td:nth-child('+posicion+')').text());
 
}

if (tecla === 78 && evento.ctrlKey){
    
    alert('hacer');
    
}

 //  alert(mensaje);
}


function mostrarFile() {
    
   $('#archivo').click();
  
    $('#archivo').removeAttr('hidden');
    
}


 
        /*
       $(document).keypress(function(event){
          
           if ($('#dataTables_scroll').focusin())
               alert('focus');
           else
               alert('no focus');
           
       });
        */
    


/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */