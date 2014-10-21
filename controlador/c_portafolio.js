
function cargarAsignaciones(agrega,usuario, identificador, filtro, status, cliente)
{
   // alert(filtro);
    start_progreso();
    var contenedor = document.getElementById('contenedor-TablaPortafolio');
    var usuarioActual = document.getElementById('usuarioActualSeleccionado');
  
    var cantidadElementos = document.getElementById('ges').length;
    (cantidadElementos === 1) ? identificador++ : identificador = identificador;
    //alert(cantidadElementos);
    $('#btnReportePortafolio').removeAttr("disabled");

    
    add_progreso(10);
    var tabla = document.getElementById("asignaciones-Tabla");

    document.getElementById('statusUsado').value = status;
    document.getElementById('clienteActual').value = cliente;
    usuarioActual.value = usuario;
    activarDesactivarSelects(['filtroExoneracion', 'filtroConvenio', 'filtroArea'], 1);
    activarDesactivarBotones(['btnVerExpediente']);
   var url  = "";
    
    add_progreso(15);
    
        if (status) {
     
        if (status != "-1"){
             
         url = "../controlador/c_portafolio.php?cargarAsignaciones=si&usuario=" + usuario + "&identificador=" + identificador + "&filtro="+filtro+"&status="+status+"&agrega="+agrega+"&cliente="+cliente;
     } else {
         
         filtro = "NULL";
         url = "../controlador/c_portafolio.php?cargarAsignaciones=si&usuario=" + usuario + "&identificador=" + identificador +"&agrega="+agrega+"&cliente="+cliente+"&filtro="+filtro;
     }
         
    } else {
  
         url = "../controlador/c_portafolio.php?cargarAsignaciones=si&usuario=" + usuario + "&identificador=" + identificador + "&filtro="+filtro+"&agrega="+agrega+"&cliente="+cliente;
        
    }
    
    $.ajax({
        
        type : 'get',
        url : url,
        success : function(resp){
            //alert(resp);
             contenedor.innerHTML = resp;
            llamaTodo('asignaciones-Tabla');
         
             stop_progreso(); 
             
        }
        
    }).done(function(){
        
          posicionScroll();
                 $('.dataTables_scrollHeadInner table thead tr th:first').off('click');
   
                 duplicar();
                 
             // CUENTA Y MUESTRA LA CANTIDAD DE CUENTAS TOTALES
             contarCuentas();
             // SUMA Y MUESTRA LA CANTIDAD DE SALDOS TOTALES
             sumarSaldos();
        
    });
    
    /*
        var ajax = nuevoAjax();

    ajax.onreadystatechange = function()
    {
         if (agrega == "NULL") {
             
        if (ajax.readyState == 1)
        {
            add_progreso(25);
            
            contenedor.innerHTML = "Cargando...";
           // jQuery("#contenedor-TablaPortafolio").html("<img alt='cargando' src='img/ajax-loader.gif' />");
           
        }
        
        if (ajax.readyState == 2)
        {
            add_progreso(50);
            contenedor.innerHTML = "Cargando...2";
           //contenedor.innerHTML = "Cargando...";
           // jQuery("#contenedor-TablaPortafolio").html("<img alt='cargando' src='img/ajax-loader.gif' />");
           
        }
        
        if (ajax.readyState == 3)
        {
            add_progreso(75);
            contenedor.innerHTML = "Cargando...3";
            //contenedor.innerHTML = "Cargando...";
           // jQuery("#contenedor-TablaPortafolio").html("<img alt='cargando' src='img/ajax-loader.gif' />");
           
        }
        
        if (ajax.readyState == 4)
        {
           add_progreso(90);
        //  alert(ajax.responseText);
            contenedor.innerHTML = ajax.responseText;
            llamaTodo('asignaciones-Tabla');
         
             stop_progreso(); 
        }
        
        } 
              
    
                 posicionScroll();
                 $('.dataTables_scrollHeadInner table thead tr th:first').off('click');
   
                 duplicar();
                 
             // CUENTA Y MUESTRA LA CANTIDAD DE CUENTAS TOTALES
             contarCuentas();
             // SUMA Y MUESTRA LA CANTIDAD DE SALDOS TOTALES
             sumarSaldos();
             

    }  
   
   
    if (status) {
     
        if (status != "-1"){
             
         ajax.open("GET", "../controlador/c_portafolio.php?cargarAsignaciones=si&usuario=" + usuario + "&identificador=" + identificador + "&filtro="+filtro+"&status="+status+"&agrega="+agrega+"&cliente="+cliente, true);
     } else {
         
         filtro = "NULL";
         ajax.open("GET", "../controlador/c_portafolio.php?cargarAsignaciones=si&usuario=" + usuario + "&identificador=" + identificador +"&agrega="+agrega+"&cliente="+cliente+"&filtro="+filtro, true);
     }
         
    } else {
  
         ajax.open("GET", "../controlador/c_portafolio.php?cargarAsignaciones=si&usuario=" + usuario + "&identificador=" + identificador + "&filtro="+filtro+"&agrega="+agrega+"&cliente="+cliente, true);
        
    }

   
    ajax.send();
    
*/
    
    
}

function reiniciaLimites(){
    
    document.getElementById('limiteInicio').value = "";
    document.getElementById('limiteFin').value = "";
    
}

   
  obtenerFilas = function obtenerFilas(id){
   
    var filas = $(id+' >tbody >tr').length;
    //alert(filas);
    return filas;
   };
   
  
  
    


function capturaInfo(){
    
    var texto = $('#asignaciones-Tabla_info').html();
    texto = texto.substring(6, 7);
   // texto = texto.renplaceAll(, "");
    
    
    
    
    alert(texto);
    
}

function duplicar(){
  var clon = $('#ges').clone(); 
//$(\'#td5\').append(clon); 
$('#di').html("");
clon.attr("id","asesorAsignar");
clon.removeAttr("onchange");
clon.attr("onchange","if (this.selectedIndex == 0) { activarDesactivarBotones(['btnAsignar'], 1); } else { activarDesactivarBotones(['btnAsignar'], 0); }");

$('#di').append(clon);

}


function cargarAsesores()
{
    ver_asesores();
   
   
    
}


function cargarSupervisores()
{
    ver_supervisores();
   
   
    
}


function cargarAreas(){
    
    var contenedor = document.getElementById('selectArea');
    
    
    var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 1)
        {
            contenedor.innerHTML = "Cargando...";
           // jQuery("#contenedor-TablaPortafolio").html("<img alt='cargando' src='img/ajax-loader.gif' />");
           
        }
        if (ajax.readyState == 4)
        {

            contenedor.innerHTML = ajax.responseText;
           
          
        }
    }
    
 
    ajax.open("GET", "../controlador/c_portafolio.php?cargarAreas=si", true);
    ajax.send();
    
}

function reasignarCuentas()
{
    
    var checks = document.getElementsByName('checkPortafolio[]');
    var asesor = document.getElementById('asesorAsignar').value;
    var usuario = document.getElementById('usuarioActualSeleccionado').value;
    var identificador = document.getElementById('ges').selectedIndex;
    var filtro = document.getElementById('filtroUsado').value;
    var marco = 0;
    var count = 0;
    var agrega = "";
    var cuentas = "";
   
    for (var i = 0; i < checks.length; i++) {
        if (checks[i].checked == true)
        {
            marco = 1;
        if (count > 0){
                  agrega = ",";
              }
              
              var y = [];
              y = (checks[i].value).split("*");
              //Se asignan los telefonos a enviar en una varible
              cuentas = cuentas+agrega+y[0];
              count++;
                
                
        }
    }
    
    if (marco)
    {

        var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
   
        if (ajax.readyState == 4)
        {

           alert(ajax.responseText);
           
           alert($('#clienteActual').val());
           cargarAsignaciones('NULL',usuario,  identificador, filtro, $('#statusUsado').val(), $('#clienteActual').val());
           
          
        }
    }
    
 
    ajax.open("GET", "../controlador/c_portafolio.php?reasignarCuentas=si&traslados="+cuentas+"&usuarioTraslado="+asesor, true);
    ajax.send();


   } else
        alert('Debe seleccionar una cuenta');
    
    

    
    
    
}

   
function reiniciaSelects(arrSelectsId){
    
    for (var i=0; i < arrSelectsId.length; i++)
    {
        
        document.getElementById(arrSelectsId[i]).selectedIndex = 0;
        
        
    }
    
}


function activarDesactivarBotones(arrBotonesId, action)
{      
        for (var i=0; i < arrBotonesId.length; i++)
    {
         
        document.getElementById(arrBotonesId[i]).disabled = action;
        
        
    }
}


function verExpediente(){
   
    var checks = document.getElementsByName('checkPortafolio[]');
  
    var formulario = document.getElementById('form_envio');
    
 
    
    		var count = 0;
		var telefonos = '';
		var agrega = '';

		 for (var i=0; i < checks.length; i++) {
           	if (checks[i].checked) {    
              // CUENTAS[count] = checks[i].value;
               count++;
            }
         }
         //alert(CUENTAS.join());
         if(count>0){
             
              window.open('?pantalla=7e0c6ba9785691fecdb2418d503d2aab','myWin','toolbars=0, width=950,height=600,fullscreen=1'); 
              formulario.target='myWin';
              formulario.submit();
         
            }
         else
         	alert('No existen seleccionadas');
    
    
}

function activarDesactivarSelects(selects, accion){
    
    for (var i=0; i < selects.length; i++){
        
        if (accion == true)
        { 
            document.getElementById(selects[i]).disabled = false;
            
        } else {
            
            document.getElementById(selects[i]).disabled = true; 
        }
        
    }
    
}


function enviarArea(area){
    
    
    var checks = document.getElementsByName('checkPortafolio[]');
   

    
    
   // var asesor = document.getElementById('asesorAsignar').value;
    var marco = 0;
    var count = 0;
    var agrega = "";
    var cuentas = "";
   
    for (var i = 0; i < checks.length; i++) {
        if (checks[i].checked == true)
        {
            marco = 1;
        if (count > 0){
                  agrega = ",";
              }
              
              var y = [];
              y = (checks[i].value).split("*");
              //Se asignan los telefonos a enviar en una varible
              cuentas = cuentas+agrega+y[1];
              count++;
                
                
        }
    }
    
    if (marco)
    {
        if (area == -1){
            
            alert('Seleccione el area a enviar');
            
        } else {

        var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
   
        if (ajax.readyState == 4)
        {

           alert(ajax.responseText);
          cargarAsignaciones('NULL', document.getElementById('usuarioActualSeleccionado').value, document.getElementById('ges').selectedIndex, 'aprobarAreas', 'NULL')
           
          
        }
    }
    
 
    ajax.open("GET", "../controlador/c_portafolio.php?asignarArea=si&cuentas="+cuentas+"&area="+area, true);
        ajax.send();

        }

   } else
        alert('Debe seleccionar una cuenta');
    
    
    
}


function asignarAreas(check){
    
 var clon = $('#filtroArea').clone();
    var marco = 0;
    var count = 0;
    var agrega = "";
    var cuentas = "";
 clon.removeAttr("onchange");
 clon.attr("id", "areaEnvio");
 clon.attr("name", "areaEnvio");
 //clon.attr("onchange", "");
 //clon.removeAttr("option");
 clon.find("option[value='0']").remove(); 
 $('#di2').html(clon);
 
        if (check.checked == true){
           
        activarDesactivarSelects(['areaEnvio'], 1);
        activarDesactivarBotones(['btnEnviarArea'], 0);
        activarDesactivarBotones(['btnVerExpediente'], 1);
        activarDesactivarSelects(['filtroArea', 'filtroConvenio', 'filtroExoneracion', 'ges', 'sup', 'ger', 'asesorAsignar'], 0);
    

        
    } else {
        
        activarDesactivarSelects(['areaEnvio'], 0);
        activarDesactivarBotones(['btnEnviarArea'], 1);
        activarDesactivarBotones(['btnVerExpediente'], 0);
        activarDesactivarSelects(['filtroArea', 'filtroConvenio', 'filtroExoneracion', 'ges', 'sup', 'ger', 'asesorAsignar'], 1);
        
        
    } 
   

        
 
    
    
}


function expedienteDblClick(id){
    
     var check = document.getElementById(id);
     var checks = document.getElementsByName('checkPortafolio[]');

      for (var i=0; i < checks.length; i++) {
           	if (checks[i].checked) {    
              // CUENTAS[count] = checks[i].value;
               checks[i].checked = false;
              
            }
         }
   
    check.checked = true;
    verExpediente();
    check.checked = false;

  
    
    
}

function cargarClientes(usuario){
    
    
    $.ajax({
        
        type: 'get',
        data : {usuario : usuario, cargarClientes : 1 },
        url : '../controlador/c_portafolio.php',
        success : function (resp){
        // alert(resp);
            $('#selcCliente').html(resp);
            
        }
        
        
    });
    
}


function crearReportePortafolio(){
    
    var usuario = document.getElementById('usuarioActualSeleccionado').value;
    var iden = document.getElementById('ges').selectedIndex;
    var identificador;
    var filtro = document.getElementById('filtroUsado').value;
    var status = document.getElementById('statusUsado').value;
    var cliente = document.getElementById('selcCliente').value;
    
  
  
    ($('#ges').is(':hidden')) ? identificador = 1 : identificador = iden;

   // if (!$('#noA').length){
    var parametros = {
        
       usuario          :   usuario,
       filtro           :   filtro,
       identificador    :   identificador,
       cliente          :   cliente,
       status           :   status
        
        
        
    };

    
    $.ajax({
             
             type    : 'post',
             data    : parametros,
             url     : '../controlador/reportes/excel/c_portafolio.php',
             success : function(result){
                    
               //$('#resp').html(result);
            
               $('#reporte > textarea').attr("name","arreglo");
               $('#reporte > textarea').val((result.replace(/s/g,'')));
               $('#reporte').submit();
               $('#reporte > textarea').val("");
              // window.location.assign("reportes/reporteportafolio.php?arreglo="+result);
               
             }
             
             
         });
         
         
         

//} else {
    
  //  alert("No Autorizado!");
//}
    
    
       // Externo.setAttribute('target', '_blank');
   //  window.open("../Vista/Reportes/reportePortafolio.php?excel=1","_blank");
       
       
      /* 	var parametros = {"excel" : 1};
	$.ajax({
		  type: "POST",
		  url: 'reportes/reportePortafolio.php',
		  data: parametros,
		  
		  success: function(result) {
		    // $('#tabla_dir').html(result);
                     //llamaTodo('direcciones-Tabla');
		   }
  	});*/

    
    
}


function posicionScroll(){
    
    $('.dataTables_scrollBody').scroll(function(){
        var tam_tabla = $('#asignaciones-Tabla').height();
        var tam_scroll = $('.dataTables_scrollBody').height();
    if ($('.dataTables_scrollBody').scrollTop() > (tam_tabla - tam_scroll)-200){
        
        agregarFila();
     // El scroll ha llegado al final de la página
    }					
   });
    
    
}


function agregarFila(){
  //  alert('s');
      // alert($('#asignaciones-Tabla').height());
      /*
        $.ajax({
            
            
            type : 'post',
            data : {agregarFilas : 1 },
            url  : '../controlador/c_portafolio.php',
            success : function(resp){
              // $('#resp').html(resp);
               var cuentas;
              
               if (resp.length === 1) { 
                 //$("#asignaciones-Tabla tbody").append("<tr class='odd'><td valign='top' colspan='12' class='dataTables_empty'>No Existen Registros</td></tr>");
                
                 return;
               } else { cuentas = JSON.parse(resp); };
               var html = "";
             
                for (var i=0; i < cuentas.length; i++){
                html += "<tr class='gradeA'><td align = \'center\'><input type='checkbox' name='checkPortafolio[]' id='"+cuentas[i].PERSONA+"*"+cuentas[i].CUENTA+"' value='"+cuentas[i].PERSONA+"*"+cuentas[i].CUENTA+"' /></td>";
                html += "<td><a href='#' onclick='expedienteDblClick(\""+cuentas[i].PERSONA+"*"+cuentas[i].CUENTA+"\")'>"+cuentas[i].CEDULA+"</td>";
                html += "<td>"+cuentas[i].NOMBRE+"</td>";
                html += "<td>"+cuentas[i].CUENTA+"</td>";
                html += "<td>"+cuentas[i].CLIENTE+"</td>";
                html += "<td>"+cuentas[i].PRODUCTO+"</td>";
                html += "<td>"+cuentas[i].SITUACION_CUENTA+"</td>";
                html += "<td id='saldo_' class='suma'>"+cuentas[i].SALDO_ACTUAL+"</td>";
                html += "<td></td>";
                html += "<td>"+cuentas[i].ASESOR+"</td>";
                html += "<td>"+cuentas[i].FECHA_ASIGNACION+"</td>";
                html += "<td>"+cuentas[i].TIPO_GESTION+"</td>";
                html += "</tr>";
               
                
                    
            }
            
             $("#asignaciones-Tabla tbody").append(html);
                $('#asignaciones-Tabla tbody tr:odd').attr("class", "gradeA odd");
                $('#asignaciones-Tabla tbody tr:even').attr("class", "gradeA even");
         
            }
            
            
        });
        
    
    */
}

function busquedaPorFiltros(){
    
    
    $('#filtros_sql select').each(function(index){
        
        
        var parametros = { busquedaPorFiltros : 1,
                           id : $(this).attr("id"),
                           valor : $(this).val()
                           
                     };
     $.ajax({
            
            type    : 'post',
            data    : parametros,
            url     : '../controlador/c_portafolio.php',
            success : function(resp){
                
              
   
                
            }
            
            
            
        });
        
       
        
        
    });
    
     $("#asignaciones-Tabla tbody").html("");
     agregarFila();
    

    
}



function contarCuentas(){
    
    
    $.ajax({
        
        type    :   'post',
        data    :   { contarCuentas : 1 },
        url     :   '../controlador/c_portafolio.php',
        success :   function(resp){
       
        $('#cuentas').val(resp);
        $('#asignaciones-Tabla_info').html("Filas 1 de "+$('#cuentas').val());
            
        }
        
    });
    
    
    
}


function sumarSaldos(){
    
    
    $.ajax({
        
        type    :   'post',
        data    :   { sumarSaldos : 1 },
        url     :   '../controlador/c_portafolio.php',
        success :   function(resp){
            
         $('#saldos').val(resp);
            
        }
        
    });
    
    
    
}


function start_progreso(){
   
    $('.progreso').css({
        
        'font-size' : '15px',
        'background-repeat': 'no-repeat',
        'background-position': '90% 50%',
        'height': '470px'
    });

    $('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>Procesando</div>');
    //$('.progreso').html('<div style="margin: 0% 0%;"><img src="img/proceso.gif"></div>');
}

function add_progreso(val){

    $('.progreso').css({
        
        'font-size' : '15px',
        'background-repeat': 'no-repeat',
        'background-position': '90% 50%',
        'height': '470px'
    });

    $('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>'+val+'%</div>');
    
}

function stop_progreso(){
    $('.progreso').css({
        
        'font-size' : '15px',
        'background-repeat': 'no-repeat',
        'background-position': '90% 50%',
        'height': '470px'
    });
    $('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>Procesado (OK)<br>Por favor espere...</div>');
    $('.progreso').css({
    'background': 'none',
    'background-image': 'none',
    'background-repeat': 'none',
    'background-position': '0',
    'height': 'auto'
    });
    
    $('.progreso').html("");
}
