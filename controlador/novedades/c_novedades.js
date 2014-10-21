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
        var di = ($(this).val()).split(';')[0];
         ids.push(di);
         
     });
 
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

function enviarExpedi(){
    
    var checks = document.getElementsByName('checkNovedad');
  
   
    
 
    
    		var count = 0;
		var ids = new Array();

		 for (var i=0; i < checks.length; i++) {
           	if (checks[i].checked) {    
              // CUENTAS[count] = checks[i].value;
               count++;
            }
         }
         //alert(CUENTAS.join());
         if(count>0){
             
             var html = "";
          //  $("<form id='form_novedad'></form>");
             html = "<form hidden id='formNovedad' method='post' action='?pantalla=7e0c6ba9785691fecdb2418d503d2aab'>";
            $('input[name="checkNovedad"]:checked').each(function(idx){
            var di = ($(this).val()).split(';')[1];
            html += "<input type='checkbox' name='checkcuentas[]' checked value='"+di+"' />";
         
            });
            
            
            html += "</from>";
             
             $('body').append(html);
             
            // $('#formNovedad').submit();
            var formulario = document.getElementById('formNovedad');
            // 
         //    alert(formulario);
             
              window.open('?pantalla=7e0c6ba9785691fecdb2418d503d2aab','myWin','toolbars=0, width=950,height=600,fullscreen=1'); 
              formulario.target='myWin';
              formulario.submit();
         
            }
         else
         	alert('No existen seleccionadas');
    
    
}

$(document).ready(function(){
        
        pintarTabla();
        //$(this).attr('title', 'Novedades');
       
        });