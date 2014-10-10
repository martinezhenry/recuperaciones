


function consultarCuentas2(cuenta)
{
    // var contenedorCuenta = document.getElementById('cuentas');
     //var idPersona="";
     ///var persona = document.getElementById('personaActual');
    
    
   
    var parametros = {
        cuenta : 1,
        consultaCuenta : cuenta
        
        
    };
    $.ajax({
        
        type : 'get',
        data : parametros,
        url : '../controlador/c_deudor.php',
        success: function(resp){
 
          
            var idPersona = resp;
         
            if (idPersona != 0)
            {
               if (!idPersona){
                   alert("No Autorizado!");
               } else {
            consultarDeudor(idPersona,"NULL");
               }
            } else
            {
                alertify.error('No Existen Registros Para Esta Cuenta. Verifique.');
            }
            
        }
        
    });
    
    /*
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {
            alert(ajax.responseText);
            var idPersona = ajax.responseText;
           
            if (idPersona != "falso")
            {
               if (!idPersona){
                   alert("No Autorizado22!");
               } else {
            consultarDeudor(idPersona,"NULL");
               }
            } else
            {
                alert('No Existen Registros Para Esta Cuenta. Verifique.');
            }
           // cargarCuentas(idPersona);
      

        }
    }

    ajax.open("GET", "c_deudor.php?cuenta=si&consultaCuenta=" + cuenta, true);
    ajax.send();
   
    */
    
}

function verificaEnter(cuenta)
{
    if (event.keyCode == 13)
    {
       
        consultarCuentas2(cuenta);
        return false;
    }
   
}


function activarTabs(accion)
{
    var tab1 = document.getElementById('gestiones-1');
    var tabAbonos = document.getElementById('gestiones-2');
    var tab3 = document.getElementById('gestiones-3');
    //var tab4 = document.getElementById('gestiones-4');
    var tab5 = document.getElementById('gestiones-5');
    var tab6 = document.getElementById('gestiones-6');
    var tab7 = document.getElementById('gestiones-7');
    var tab8 = document.getElementById('gestiones-8');
    var tab9 = document.getElementById('gestiones-9');
    var accion2 = false || accion;
    
    tab1.disabled = accion2;
    tab3.disabled = accion2;
    tab3.disabled = accion2;
    //tab4.disabled = false;
    tab5.disabled = accion2;
    tab6.disabled = accion2;
    tab7.disabled = accion2;
    tab8.disabled = accion2;
    tab9.disabled = accion2;
    //tab9.disabled = false;
    
    tabAbonos.disabled = accion2;
}


function sumarUno(){
   
     var inicio =document.getElementById('inicioArr');
     var fin = document.getElementById('finArr');
    
  
    if (parseInt(inicio.value) < parseInt(fin.value)){
    
       inicio.value = parseInt(inicio.value)+1;
       document.getElementById('envio').click();
   
    }
}


function ArrCuentas(Persona, filtro)
{
   
    var y = [];
    y = Persona.split("*");
   
    if (filtro == "NULL"){
    consultarDeudor("NULL", y[0]);
    } else {
    consultarCuentas2(y[0]);
    //consultarDeudor("NULL", y);
    activarTabs();
    
    document.getElementById('cuentaActual').value = y;
    var tabActivo = document.getElementById('tabActivo');
   
    buscarDetalles(y);
   
    
    //document.getElementById(y[1]).click();
    
    if (filtro == "convenio"){
    //document.getElementById('gestiones-6').disabled = false;
    document.getElementById('gestiones-6').click();
    document.getElementById('btnAprobarConvenio').hidden = false;
    tabActivo.value = "convenios";
    
    //alert(document.getElementById('2005559'));
  
    } else if (filtro == "exoneracion"){
      //  document.getElementById('gestiones-7').disabled = false;
        document.getElementById('gestiones-7').click();
        tabActivo.value = "exoneraciones";
        document.getElementById('btnAprobarExoneracion').hidden = false;
    }
    
    }
    
    
    
   // alert(document.getElementById(y[1]));
    
   // cargarCuentas(idPersona);
    
}

function buscarDetalles(cuenta){
    
    
  
    var cadena = "";
    var persona = document.getElementById('personaActual');
    var cuentaActual = document.getElementById('cuentaActual');
    var clienteActual = document.getElementById('clienteActual');
    var carteraActual = document.getElementById('carteraActual');
    var tipoCuentaActual = document.getElementById('tipoCuentaActual');
    var usuarioGestorActual = document.getElementById('usuarioGestorActual');
    var saldoActualCuenta = document.getElementById('saldoActualCuenta');
    
    
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 4)
        {

            cadena = ajax.responseText;
            
            cadena = cadena.split(",");
            
            clienteActual.value = cadena[0];
            carteraActual.value = cadena[1];
            usuarioGestorActual.value = cadena[2];
            tipoCuentaActual.value = cadena[3];
            saldoActualCuenta.value = cadena[4];
          

      

        }
    }

    ajax.open("GET", "../controlador/c_cuentas.php?buscarDetallesDeCuenta=si&cuenta=" + cuenta, true);
    ajax.send();
    
    
}


function crearPDF()
{
    
    alert("CREAR PDF");
    var win = window.open("../vista/Reportes/ExoneracionBanesco.php","Cotización","width=800,height=600,top=20,left=40,scrollbars=NO,titlebar=NO,menubar=YES,toolbar=NO,directories=YES,location=YES,status=NO,resizable=NO") 
    
  
    win.window.focus();
    
}

   function Ventana_001 (URL){ 
  window.open("/Reportes/PDF_Example.php","Cotización","width=800,height=600,top=20,left=40,scrollbars=NO,titlebar=NO,menubar=YES,toolbar=NO,directories=YES,location=YES,status=NO,resizable=NO") 

}


function validarCopia(){
 
  // alert(document.getElementsByName('btn_Copiar').length);
   var id = '';
   var btns = document.getElementsByName('btn_Copiar');
  
   for(var i =0; i < btns.length; i++ ){
      //  var resp= verificarCopiar(btns[i].id);
        if (i==(btns.length-1)){
            
               id += btns[i].id;
            
        } else {
        
        id += btns[i].id+',';
    }
        btns[i].disabled = true;
    } 
        
          var parametros = {
        validarCopia : 1,
        id : id
        
    };
   
    
       
    
     $.ajax({
        
        type    : 'get',
        data    : parametros,
        url     : '../controlador/c_deudor.php',
        success : function (resp){
          //  alert(resp);
          
           // return resp;
          //    alert(resp);
           // alert(resp+" aver");
           var r = resp.split(',');    
         for (var i=0; i < r.length; i++){
        //    alert(r[i]);
           // var otra = "#"+r[i];
           // $(otra).removeAttr('disabled');
           //alert( document.getElementById('10025*5*5401393003064402'));
           var btns = document.getElementsByName('btn_Copiar');
           
              for(var l =0; l < btns.length; l++ ){
                //  alert(r[i]+" "+btns[l].id);
                  if (r[i].replace(/\s/g,'') == (btns[l].id).replace(/\s/g,'')){
                      
                      document.getElementById(btns[l].id).disabled = false;
                      
                  }
                  
              }
           
             
             
             
         }
    
         }
               
            
     
    });
        
         
    
    

}



function verificarCopiar(id){
    
     var parametros = {
        validarCopia : 1,
        id : id
        
    };
   
    
  
    
     $.ajax({
        
        type    : 'get',
        data    : parametros,
        url     : '../controlador/c_deudor.php',
        success : function (resp){
           // alert(resp);
          
            return resp;
               
            }
     
    });
    
    
}


function copiarGestion(id, idclick){
    
    
    var parametros = {
        copiarGestion : 1,
        id : id
        
    };
    
    $.ajax({
        
        type : 'get',
        data : parametros,
        url  : '../controlador/c_deudor.php',
        success : function(resp){
          //  alert('aki');
           // alert(resp);
           
             if (resp == 1) {
                   
                   alertify.success("Gestion Guardada.");
                  // cargarGestiones(cuenta, cliente, cartera, usuarioGestor, tipoCuenta, saldoActualCuenta); 
                   $('#' + idclick).click();
                   $('#'+id).removeAtrr('disabled');
               } else {
                   
                   alertify.error(resp);
                   return;
               }
            
        }
        
        
    });
    
    
}