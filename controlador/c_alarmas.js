
var idTimeOut = new Array();
function cargarAlarmas(){
    
    var parametros = { 'cargarAlarmas':1 };
    $.ajax({
            
            
        type: 'post',
        url: '../controlador/c_alarmas.php',
        data: parametros,
        success: function (result){
      
       var alarmas = JSON.parse(result);
       
       for (var i=0; i< idTimeOut.length; i++){
          
           clearTimeout(idTimeOut[i]);
           
       }
    
        
        for (var i=0; i< alarmas.length; i++){
            var hora = new Date(alarmas[i].MOMENTO);
            var h = hora.getTime();
            var f = new Date();
            f = f.getTime();
            var mili = h - f;
           // alert(mili);
            //alert(alarmas[i].CONTENIDO);
            var con = alarmas[i].CONTENIDO;
            var id_a = alarmas[i].ID_ALARMA;
           idTimeOut[i] = setTimeout("alerta('"+con+"', '"+id_a+"'); cambiarStatus_alarma('"+id_a+"')", mili);
            
        }
        /*

            */
        }
            
            
            
            
    });
    
}


function ingresarAlarma(sms, fecha, hora){
     var r,
         moment = fecha + " " + hora;
    
    

    
    
    
    if (fecha != "" && hora != "" && sms != ""){
    
    var actual = new Date();
    var horaFin = "17:00";
    var horaInicio = "08:00";
    var fe = new Date(moment);
    var limiteHoraI = new Date(fecha+" "+horaInicio);
    var limiteHoraF = new Date(fecha+" "+horaFin);
    
    if (fe.getTime() < actual.getTime()){
        
        alertify.error("La fecha Debe Ser y hora debe ser Mayor que la actual");
        
    } else if (fe.getTime() < limiteHoraI.getTime() || fe.getTime() > limiteHoraF.getTime()) {
        
        alertify.error("La alerta debe ser en horario laboral");
        
        
    } else{
    
    moment = moment.replace(/-/g, "/");
        var parametros = {
                        ingresarAlarma : 1,
                        contenido      : sms,
                        momento        : moment
        
    };
        $.ajax({

            type   : "post",
            url    : "../controlador/c_alarmas.php",
            data   : parametros, 
            success: function(result){
                var datos = result.split("*");
                r = datos[0];

                if (r === "true"){

                    alertify.success(datos[1]);

                } else {

                    alertify.error(datos[1]);

                }


            } 

        });
    
    }
} else {
    
    alertify.error("Debe llenar Todos los Campos");
    
    
}
    
    
}




 function cambiarStatus_alarma(id_a){
    
    
    var parametros = {
                        cambiarStatus : 1,
                        id            : id_a
                        
        
    };
    $.ajax({
        
        type   : "post",
        url    : "../controlador/c_alarmas.php",
        data   : parametros, 
        success: function(result){
            
         
           //alert(result);
           
            
        } 
        
    });
    
    
}



function alarmasProgramadas(){

            
          
            var parametros = { 'alarmasProgramadas':1 };
    $.ajax({
            
            
        type: 'post',
        url: '../controlador/c_alarmas.php',
        data: parametros,
        success: function (result){
       
         var alarmas = JSON.parse(result);
         
         var html = "<table width='100%'><tr><th style='border-bottom: 2 solid black; padding: 5px;'>Mensaje</th><th style='border-bottom: 2 solid black; padding: 5px;'>Fecha y hora</b></th></tr>";
         
         for (var i=0; i< alarmas.length;i++)
         {
             var fila = "f_"+i;
            html += "<tr id='f_"+i+"'>\n\
            <td width='60%' style='border-bottom: 2 solid black; padding: 5px;'>\n\
            "+ alarmas[i].CONTENIDO + "\
            </td>\n\
            <td width='40%' style='border-bottom: 2 solid black; padding: 5px;'>\n\
            " + alarmas[i].FECHA +"<span style='float: right;'>\n\
            <img id='"+alarmas[i].ID_ALARMA+"' title='Eliminar' onclick='eliminarAlarma(this.id, \"#"+fila+"\");' style='border-radius: 50px; padding: 2px; border: 2 solid gray;' onmouseover=\"this.style.border=' 2 solid black';\" onmouseout=\"this.style.border='2 solid gray';\" src='img/close.png' width='10px' heigth='10px'/></span>\n\
            </td></tr>";
         } 
         
         
         html += "<table>";
          
         //$('#v_alarmas').html(html);
         alertify.alert(html, function () {
					
				});
            
        } 
        
    });
    
    
    
}


function eliminarAlarma(id_a, fila){
    
        
    var parametros = {
                        eliminarAlarma : 1,
                        id            : id_a
                        
        
    };
    
    
    $.ajax({
        
        type   : "post",
        url    : "../controlador/c_alarmas.php",
        data   : parametros, 
        success: function(result){
            
          
            if (result){
                
                $(fila).remove();
                 cargarAlarmas();
                
            }
           
            
        } 
        
    });
    
}


