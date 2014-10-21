
//
// Busca en la BD los datos de la Persona y los refleja en un contenedor
// 
// PARAMETRO
// 
//     ** identificador: codigo(C.I) que se empleara en la busqueda de la Persona en la BD
//     

function consultarDeudor(identificador, Persona)
{
    
   
     if(identificador == "")
        {
            alert("{not_tab1}");
            
        } else
        {
    
        var Nombre = document.getElementById('nombreDeudor');
        var Fecha = document.getElementById('fechaNacDeudor');
        var Nacionalidad = document.getElementById('nacionalidadDeudor');
        var Cedula = document.getElementById('cedulaDeudor');
        var cadena = "";
        var personaActual = document.getElementById('personaActual');
        
        
        document.getElementById('gestiones-1').checked = true;
        activarTabs(true);
        $('#gestiones-Tabla > tbody').remove();
        document.getElementById('sky-tab-1-2').disabled = false;
        document.getElementById('sky-tab1-3').disabled = false;
        document.getElementById('sky-tab1-4').disabled = false;
        
        var y = [];
     
        var ajax = nuevoAjax();

        ajax.onreadystatechange = function() {

         
            if (ajax.readyState == 4) {

            
                cadena = ajax.responseText;
                //alert(cadena);
                // alert(cadena);
                if (cadena == 1) {
                   // alert(Persona);
                   cargarDuplicados(identificador, Persona);
            
                   return;
                   
                } else if (cadena == 0)
                {
                    //alert("{not_tab2}");
                    alertify.error("{not_tab2}");
                   

                } else
                {
                    if (!cadena){
                        alert("{not_tab3}");
                    } else {
                    y = cadena.split("*");
                    
                    Cedula.value = y[0];
                    Nombre.value = y[1];
                    Fecha.value = y[2];
                    Nacionalidad.innerHTML = y[3];
                    
                    personaActual.value = y[4];
                    
                    var arrFechNac = y[2].split('/');
                    $('#btn_validar').attr('onclick', 'validarDatos(\''+y[0].replace(/(^\s*)|(\s*$)/g,"")+'\', \''+y[3]+'\', \''+arrFechNac[2]+'\', \''+arrFechNac[1]+'\', \''+arrFechNac[0]+'\', \''+y[1]+'\');');
                    //$('#btn_validar').attr('onclick', 'validarDatos(\'6277248\', \'N\', \'1968\', \'04\', \'17\', \'SEIJAS GARCIA LUIS ENRIQUE\');');
                    $('#btn_validar').removeAttr('disabled');
                     $('#check_valida').attr('hidden', 'hidden');
                    
                    cargarCuentas(y[4]);
                    
                    

                    document.getElementById('sky-tab1-1').click();
                }
                   
                   
                }
            }
        }

        ajax.open("GET", "../controlador/c_deudor.php?deudor=si&idPersona=" + identificador+"&persona="+Persona, true);


        ajax.send();

    }





}

//
// Busca en la BD los datos de las Cuentas de una Persona y los refleja en un contenedor
// 
// PARAMETRO
// 
//     ** idPersona: codigo que se empleara en la busqueda de la Persona en la BD
//  
function cargarCuentas(idPersona)
{
    
    var contenedor = document.getElementById('cuentas');
    var estadisticas = document.getElementById('estadisticas');

    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            
            contenedor.innerHTML = ajax.responseText;
              //alert($(cuentaId+" > input[type=checkbox]").name);
             // activarDesactivarChecks(document.getElementsByName('check'), document.getElementById('checkTodos'));
              sumarMontos();
              
            //  alert($('#cuentas-Tabla tbody tr:first td:nth-child(2)').text());
              $('#cuentas-Tabla tbody tr:first td:nth-child(2)').click();
              $('#cuentas-Tabla tbody tr:first').attr('class', 'gradeA odd row_selected');
              llamaTodo('cuentas-Tabla');
      

        }   
        
    
        
    };

    ajax.open("GET", "../controlador/c_deudor.php?cuenta=si&idPersona=" + idPersona, true);
    ajax.send();
   
    
   

}

//
// Evita ingresar caracteres distintos de numeros y ejecuenta funcion al presionar (ENTER)
// 
// PARAMETROS
// 
//     ** identificador: codigo(C.I) que se empleara en la busqueda de la Persona en la BD
//     ** key: evento que generado
//  
function checkKey(identificador, key)
{
    
    var unicode;
    if (((key.keyCode < 48) || (key.keyCode > 57)) && key.keyCode != 8)
    {
        if (key.keyCode == 13 || key.keyCode == 9)
        {

            consultarDeudor(identificador, "NULL");
        } else
        {

            key.returnValue = false;
        }
    }
}


//
// Suma los montos del area de estadisticas respectivo a las cuentas
// 
function sumarMontos()
{
  
    var montoVencido = document.getElementById("montoVencido");
    var montoIntereses = document.getElementById("intereses");
    var montoTotal = document.getElementById("montoTotal");

    
    
      montoVencido.value = "0";
      montoIntereses.value = "0";
      montoTotal.value = "0";
    
    var checkTotal = document.getElementsByName('check2');
    
     
     for (var i=0; i < checkTotal.length; i++)
                {
                    
                    if (checkTotal[i].checked)
                    {
                        
                        var id1 = "TmontoVencido_"+checkTotal[i].id;
                        var id2 = "Tintereses_"+checkTotal[i].id;
                        var id3 = "TmontoTotal_"+checkTotal[i].id;
                           
                        if (document.getElementById(id1).innerHTML == "")
                        {
                            var campo1 = 0;
                            
                            
                        } 
                        if (document.getElementById(id2).innerHTML == "")
                        {
                            var campo2 = 0;
                           
                            
                        } 
                        if (document.getElementById(id3).innerHTML == "")
                        {
                            var campo3 = 0;
                            
                            
                        } 
                        
                        if (parseFloat(campo1) != 0)
                        {
                            var campo1 = parseFloat(document.getElementById(id1).innerHTML);
                        }
                        
                        if (parseFloat(campo2) != 0){
                           var campo2 = parseFloat(document.getElementById(id2).innerHTML);
                        }
                        
                        if ( parseFloat(campo3) != 0)
                        {
                             var campo3 = parseFloat(document.getElementById(id3).innerHTML);
                        }
                        
                        
                        
                   
                      
                        
                        
                        montoVencido.value = (parseFloat(montoVencido.value)+campo1);
                        montoIntereses.value = (parseFloat(montoIntereses.value)+campo2);
                        montoTotal.value = (parseFloat(montoTotal.value)+campo3);
                        //alert((parseFloat(montoTotal.value)+campo3));
                    }
               }
                    
               
 montoTotal.value = (parseFloat(montoTotal.value)).formatMoney(2,".", ",");
 montoVencido.value = (parseFloat(montoVencido.value)).formatMoney(2,".", ",");  
 montoIntereses.value = (parseFloat(montoIntereses.value)).formatMoney(2,".", ",");  
  
}


function cargarDuplicados(cedula, persona){
   
 //   alert(persona);
    $.ajax({
        
      type : 'get'  ,
      data : {cargarDuplicados : 1, cedula : cedula, persona : persona},
      url : '../controlador/c_deudor.php',
      success : function(resp){
          // alert(resp);
          if (resp != 0) {
          
              var arr = JSON.parse(resp);
              var html = "<h2>Persona Duplicada</h2><br>";
              html += "<div><table  cellpadding='2'><tr><td width='15%'><b>Cedula</b></td><td width='60%'><b>Nombre</b></td><td width='25%'><b>Fecha Nac</b></td></tr>";
              for (var i = 0; i < arr.length; i++){
                html += "<tr>";
                html += "<td>"+arr[i].CEDULA+"</td><td>"+arr[i].NOMBRE+"</td><td>"+arr[i].FECNAC+"</td>";
                html += "</tr>";
              }
              
              html += "</table><br>Debe Consolidar Primero para Gestionar esta Cedula</div>";
              
                     
              alertify.alert(html, function () {

              });
                                
              
              return;

            } else {

                return false;

            }
          
      }
        
    });
    
    
}


function validarDatos(cedula, nacionalidad, año, mes, dia, nombre){
    
    if (nacionalidad != 'N'){  
        $('#check_valida').attr('src', 'img/ico/NO_check.ico');
        $('#check_valida').css({'float': 'right', 'margin-top': '-34', 'margin-right': '-25'});
        $('#check_valida').removeAttr('hidden');
        $('#check_valida').attr('title', 'No Validado');
        return;
    }
    $('#check_valida').attr('src', 'img/spinner-mini.gif');
    $('#check_valida').css({'float': 'right', 'margin-top': '-34', 'margin-right': '-25'});
    $('#check_valida').removeAttr('hidden');
    $('#check_valida').attr('title', 'Cargando...');
    var parametros = {
        validarDatos : 1,
        cedula : cedula,
        nombre : nombre,
        año : año,
        mes : mes,
        dia : dia,
        nacionalidad : nacionalidad  
    };
   // alert(dia);
    $.ajax({
        
        type : 'post',
        data : parametros,
        url : '../controlador/c_deudor.php',
        success: function(resp){
          //  alert(resp);
            if (resp == 0){
                
                $('#check_valida').attr('src', 'img/ico/NO_check.ico');
                $('#check_valida').attr('title', 'No Validado');
               // alert('if');
            } else {
                
                $('#check_valida').attr('src', 'img/ico/check.ico');
                $('#check_valida').attr('title', 'Validado');
                $('#btn_ver_datos').removeAttr('disabled');
                $('#btn_ver_datos').attr('href', 'ivssConsulta.php?nac='+nacionalidad+'&ce='+cedula+'&y='+año+'&m='+mes+'&d='+dia);
                //$('btn_ver_datos').attr('onclick', 'verDatosExternos()');
             //   alert('else');
            }
            
            $('#check_valida').css({'float': 'right', 'margin-top': '-34', 'margin-right': '-25'});
            $('#check_valida').removeAttr('hidden');
            
        }
        
    });
    
    
    
   // $('#check_valida').css();
    //$('#check_valida').css();
    

   // alert();
}




Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
   var n = this,
   decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
   decSeparator = decSeparator == undefined ? "," : decSeparator,
   thouSeparator = thouSeparator == undefined ? "." : thouSeparator,
   sign = n < 0 ? "-" : "",
   i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
   j = (j = i.length) > 3 ? j % 3 : 0;
   return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
}

