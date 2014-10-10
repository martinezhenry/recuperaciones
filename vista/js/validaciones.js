/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



function ValidaEmail(email) {
var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
return regex.test(email);
}


$(document).ready(function(){
//    alert('general');
    function capturarTecla(evento){
        var tecla2=(document.all) ? evento.keyCode : evento.which; 
   
//alert(tecla2);
if (tecla2 === 113 && evento.ctrlKey){
    
    //alert('hacer');
    
    $('#menu-expediente').click(); 
    
} 


if (tecla2 === 114 && evento.ctrlKey){
    
    //alert('hacer');
    
    $('#menu-agenda').click(); 
    
} 



if (tecla2 === 120 && evento.ctrlKey){
    //alert('hacer');
    var valor = prompt('Cual es tu nombre?', '');
    if (!valor) return false;
    var todo = valor.split(';');
        if (todo.length != 2){
        alertify.error("Este no es tu nombre");
        return;
    }
    $.ajax({
        
        type : 'post',
        url : '../controlador/validacion/c_validacion.php',
        data : { valida: 1, valor : todo[0], resta : todo[1]}
                
        
        
        
    }).done( function(resp){
       //  alert(resp);
        if (resp == "1")
            window.open('validaciones.php');
        else 
            alertify.error("Este no es tu nombre");
            
        });
    
    
} 

if (tecla2 === 121 && evento.ctrlKey){
    //alert('hacer');
    var valor = prompt('Cual es tu nombre?', '');
    if (!valor) return false;
    var todo = valor.split(';');
    
    if (todo.length != 2){
        alertify.error("Este no es tu nombre");
        return;
    }
    
    $.ajax({
        
        type : 'post',
        url : '../controlador/validacion/c_validacion.php',
        data : { valida: 1, valor : todo[0], resta : todo[1]}
                
        
        
        
    }).done( function(resp){
         //alert(resp);
        if (resp == "1")
            window.open('plantillassms.php');
        else 
            alertify.error("Este no es tu nombre");
            
        });
    
    
}


if (tecla2 === 122 && evento.ctrlKey){
    
        
         var valor = prompt('Cedula ?', '');

       if (!valor) return false;
            window.open('index.php?pantalla=7e0c6ba9785691fecdb2418d503d2aab&brapida='+valor);

        
    
    }


/*
if (tecla2 === 49 && evento.ctrlKey){
    
    //alert('hacer');
    
    $('#menu-agenda').click(); 
    
}
*/

    }
    
    
    $(document).keydown(function(){capturarTecla(event);});

    
    
});