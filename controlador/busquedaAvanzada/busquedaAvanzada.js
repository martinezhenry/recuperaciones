/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function cargarDatos(cedula, nombre, apellido, codarea, telefono, direccion){
    
    var parametros = {
        
        cargarDatos : 1,
        cedula : cedula,
        nombre : nombre,
        apellido : apellido,
        codarea : codarea,
        telefono : telefono,
        direccion : direccion
        
        
    };
    var html = "";
    $.ajax({
        
        type : 'post',
        url : '../controlador/busquedaAvanzada/busquedaAvanzada.php',
        data : parametros,
        success : function(resp){
            
          //  alert(resp);
            
            if (resp != 0){
            
            var arr = JSON.parse(resp);
            for (var i=0; i<arr.length;i++){
                  
            html += "<tr>";
            html += "<td class='center'>"+arr[i].FUENTE+"</td>";
            html += "<td class='center'>"+arr[i].CEDULA+"</td>";
            html += "<td class='center'>"+arr[i].NOMBRE+"</td>";
            html += "<td class='center'>"+arr[i].DIRECCION+"</td>";
            html += "<td class='center'>"+arr[i].TELEFONO+"</td>";
            html += "<td class='center'>"+arr[i].FECNAC+"</td>";
            html += "</tr>";
            
            }
            
        } else {
            
            html = "<tr class = 'odd'><td colspan=6 class='dataTables_empty'>No Existen Resgitros</td></tr>";
            
        }
        
        }
      
        
    }).complete(function (){
    //    alert(html);
        $('#table_busqueda tbody').html(html);
        $('#table_busqueda tbody tr:odd').attr("class", "gradeA odd");
    $('#table_busqueda tbody tr:even').attr("class", "gradeA even");
    });
    
    

    
}

    $(document).ready(function(){
       
        $('#bus_codarea').numeric(false);
        $('#bus_telefono').numeric(false);
        
        $(this).attr('title','Busqueda Avanzada');
        
    });