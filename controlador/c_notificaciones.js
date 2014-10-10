
function cargarNotificaciones(){
    
  	$('#recibidas_content').html('');//vaciamos el div donde se encuentra la tabla

	var parametros = {"cargarNoti" : 1};
	$.ajax({
		  type: "POST",
		  url: '../controlador/c_notificaciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#recibidas_content').html(result);
               
                     $('#noti-Tabla_filter').remove();
                   
		   }
  	});
    
    
    
}



function cargarDestinatarios(){
    
        $('#desti_content').html('');//vaciamos el div donde se encuentra la tabla

	var parametros = {"cargarDesti" : 1
                         };
	$.ajax({
		  type: "POST",
		  url: '../controlador/c_notificaciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#desti_content').html(result);
                  
                       $('#notiDestinatarios-Tabla_filter').remove();
                   
		   }
  	});
    
    
}


function enviarNotificacion(checks, contenido){
    
        var marco = false;
        var destinatarios = "";
        var agrega = "";
        var count = 0;
        for (var i=0; i< checks.length; i++){
            if (checks[i].checked == true){
                if (count > 0) { agrega = ","; } 
                marco = true;
            destinatarios +=  agrega+checks[i].value;
            count++;        
            }
            
        }
        
        if (marco){
	var parametros = {"enviarNoti" : 1,
                          "contenido" : contenido,
                          "destinatarios" : destinatarios
                         };
	$.ajax({
		  type: "POST",
		  url: '../controlador/c_notificaciones.php',
		  data: parametros,
		  
		  success: function(result) {
		    // $('#desti_content').html(result);
                   alert('Notificación Enviada');
                    $('#form-enviarNoti').each (function(){  this.reset();});  
                } });
        } else {
            
            alert("Debe Seleccionar un Destinatario");
            
        }
    
    
}



function cambiarStatus(id){
  
	var parametros = {"cambiarStatus" : 1,
                          "id" : id
                         };
	$.ajax({
		  type: "POST",
		  url: '../controlador/c_notificaciones.php',
		  data: parametros,
		  
		  success: function(result) {
                      
                   //   alert(result);
                      cargarNotificaciones();
                      obtenerNoLeidos();
                  
		   }
  	});
    
}


function cargarContenido(id){
    
    var content = ($('#'+id+' > td > .content').html());
    var de = ($('#'+id+' > td > .de').html());
    var fecha = ($('#'+id+' > td > .fecha').html());
    
    $('#contenidoNoti').val(
            "De: "+de+
            "\n"+
            "Fecha: "+fecha+
            "\n"+
            content);
    
    
    
}



    function obtenerNoLeidos(){
   
    	var parametros = {"contador" : 1
                         };
	$.ajax({
		  type: "POST",
		  url: '../controlador/c_notificaciones.php',
		  data: parametros,
		  
		  success: function(result) {
                      // $('#noti2').html(result);
                      if (result < "1"){
                          
                       $('#noti23').attr("class", "ocultar_noti");
                          
                      } else {
                          
                          $('#noti23').attr("class", "notificacion");
                          
                      }
                       $('#noti23').html(result);
                     
                      
                  
		   }
  	});
        
    }