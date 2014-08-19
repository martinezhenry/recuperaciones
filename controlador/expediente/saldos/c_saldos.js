/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function cargarSaldos(cliente, cartera, cuenta){
    
    var parametros = {
        
        cargarSaldos    : 1,
        cliente         : cliente,
        cartera         : cartera,
        cuenta          : cuenta
        
    };
  
    
    $('#saldos-Tabla tbody').html('<tr class="odd"><td class="center" colspan="'+$('#saldos-Tabla thead th').length+'">Cargando...<td></tr>');
    var html = "";
    $.ajax({
        
        type    : 'post',
        url     : '../controlador/expediente/saldos/c_saldos.php',
        data    : parametros,
        success : function (resp){
            
         
            
            if (resp == 0){
                
                html = '<tr class="odd"><td class="center" colspan="'+$('#saldos-Tabla thead th').length+'">Sin Resultados<td></tr>';
                
            } else {
                
                 var saldos = JSON.parse(resp);
                 
                 for (var i=0; i < saldos.length; i++){
                 
                 html += '<tr>';
                 html += '<td class="center">'+saldos[i].FECHA+'</td>';
                 html += '<td class="center">'+saldos[i].SALDO_ANTERIOR+'</td>';
                 html += '<td class="center">'+saldos[i].DIFERENCIA+'</td>';
                 html += '<td class="center">'+saldos[i].SALDO_ACTUAL+'</td>';
                 html += '</tr>';
                 
                }
            }
            
            
        }
        
        
    }).fail(function (){
        
        alert('Error al cargar los Saldos');
        
        
    }).complete(function(){
        
     $('#saldos-Tabla tbody').html(html);
     pintarTabla('#saldos-Tabla');
        
    });
    
    
    
    
    
    
}