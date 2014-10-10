/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function pintarTabla(){
    
    
    $('#table_Novedades tbody tr:odd').attr('class', 'gradeA odd');
    $('#table_Novedades tbody tr:even').attr('class', 'gradeA even');
    
}


function verificarNovedad(){
    
    $.ajax({
        
        type : 'post',
        url : '../controlador/novedades/c_novedades.php',
        data : { verificarNovedad:1 }
        
        
    }).done(function(resp){
       // alert(resp);
        if (resp == 1){
          //  alert('hay');
          $('#noti24').css('display','block');
          
          
        } else {
          //  alert('no hay');
          $('#noti24').css('display','none');
        }
        
    });
    
}


function marcarVisto(){
    
     
     var ids = new Array();
     
     if ($('input[name="checkNovedad"]:checked').length == 0){
         alert('Debe Seleccionar al menos una novedad');
         return false;
     };
     
     $('input[name="checkNovedad"]:checked').each(function(idx){
         
         ids.push($(this).val());
         
     });
   //  alert(ids);
    $.ajax({
        
        type : 'post',
        url : '../controlador/novedades/c_novedades.php',
        data : { marcarVisto:1, ids : ids }
        
        
    }).done(function(resp){
        //alert(resp);
        if (resp == 1){
      //    alert('bueno');
          location.reload();
         /// $('#noti24').css('display','block');
          
          
        } else {
            alert('Error Cambiando Status');
          //$('#noti24').css('display','none');
        }
        
    });
    
    
}


function mTodos(){
    //alert($('#checkToNove').is('checked'));
    
    if ($('#checkToNove').is(':checked')){
     //    alert($('.checkNovedad').length);
     
     var check = document.getElementsByName('checkNovedad');
     
     for (var i = 0; i < check.length; i++){
         check[i].checked = true;
     }
     
        ///$('input[type=checkbox]').attr('checked', true);
            
    } else{
        //$('input[type=checkbox]').attr('checked', false);
        
        
     var check = document.getElementsByName('checkNovedad');
     
     for (var i = 0; i < check.length; i++){
         check[i].checked = false;
     }
      
    }
    
}

$(document).ready(function(){
        
        pintarTabla();
        $(this).attr('title', 'Novedades');
       
        });