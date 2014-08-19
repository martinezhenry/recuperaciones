/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function pintarTabla(id){
    
    $(id+' tbody tr:odd').attr("class", "gradeA odd");
    $(id+' tbody tr:even').attr("class", "gradeA even");
    
    
}


function cargarPersonas(cedula, nombre, apellido, codarea, telefono, direccion){
    
    (cedula.length == 0) ? cedula = "{}":cedula = cedula;
    /*(nombre.length == 0) ? nombre = "{}":nombre = nombre;
    (apellido.length == 0) ? apellido = "{}":apellido = apellido;
    (codarea.length == 0) ? codarea = "{}":codarea = codarea;
    (telefono.length == 0) ? telefono = "{}":telefono = telefono;
    (direccion.length == 0) ? direccion = "{}":direccion = direccion;*/
   //cedula = "123";
    //alert(direccion);
    var html="";
    var parametros = {
      cargarPersonas : 1,
      cedula_v : cedula,
      nombre_v : nombre,
      apellido_v : apellido,
      codarea_v : codarea,
      telefono_v : telefono,
      direccion_v : direccion
    };
     $('#table_Localizacion tbody').html('<tr class="odd"><td class="center" colspan="'+$('#table_Localizacion thead th').length+'">Cargando...<td></tr>');
    // alert(direccion);
    $.ajax({
        
        type : 'post',
        url : '../controlador/localizacion/c_localizacion.php',
        data : parametros,
        success : function(resp){
          
            
            if (resp == 0){
                
                alert('No se obtuvieron Resultados');
                $('#table_Localizacion tbody tr').remove();
                html += '<tr class="odd"><td class="center" colspan="'+$('#table_Localizacion thead th').length+'">Sin Resultados<td></tr>';
                
            } else {
               
                var personas = JSON.parse(resp);
                
                $('#table_Localizacion tbody tr').remove();
               // $('#table_Localizacion tbody tr').remove();
               
                for (var i=0; i < personas.length; i++){
                    
                
                    
                    html += "<tr>";
                    html += "<td class='center'>"+personas[i].FUENTE+"</td>";
                    html += "<td class='center'>"+personas[i].FECHA_CARGA+"</td>";
                    html += "<td class='center'>"+personas[i].ID+"</td>";
                    html += "<td class='center'>"+personas[i].NOMBRE+"</td>";
                    html += "<td class='center'>" +personas[i].DIRECCION + "</td>";
                    html += "<td class='center'>"+personas[i].CODIGO_AREA+" "+personas[i].TELEFONO+"</td>";
                    html += "<td class='center'>"+personas[i].FECHA_NACIMIENTO+"</td>";
                    html += "<td class='center'>"+personas[i].ALTERNATIVO1+" "+personas[i].ALTERNATIVO2+" "+personas[i].ALTERNATIVO3+"</td>";
                    html += "</tr>";
                    
                    
                    
                }
                
                
            }
            
            
        }
        
        
        
    })
    .done(function(){
       $('#table_Localizacion tbody').append(html);
    
        
    }) 
    .fail(function(){
        alert('fallo al realizar carga');
     
    })
    .always(function(){
        pintarTabla('#table_Localizacion');
      
    });
    
    
    
}



$(document).ready(function(){
    
   
    if ($('#table_Localizacion').length){
      
        pintarTabla('#table_Localizacion');
        $('#loc_codarea').numeric(false);
        $('#loc_telefono').numeric(false);
        
    };
    
    
    
    
    
    
});
    
    
    
    

