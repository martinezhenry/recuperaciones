<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script>
        /*var alertas = '[{"fecha":"Hoy",\n\
                       "Mensaje":"Llamar a Pedro",\n\
                       "hora": "13:00"}]';
                 var z= JSON.parse(alertas);
                 
       alert(z.fecha+" "+z.Mensaje+" "+z.hora);*/
    
        var alarmas = ['2014-04-16 17:01','2014-04-16 17:02','2014-04-16 17:03'];
        var id = new Array();
        //alert(alarmas);
        
       // var hora = new Date('2014-04-16 16:00:00');
        
        //alert(hora.getTime());
        
        function ti(){
            
            alert('ti');
            
        }
        
        for (var i=0; i< alarmas.length; i++){
            var hora = new Date(alarmas[i]);
            var h = hora.getTime();
            var f = new Date();
            f = f.getTime();
            mili = h - f;
           // alert(mili);
            
           id[i] = setTimeout(function(){ti();}, mili);
            
        }
        
        //alert(id);
        
        
        </script>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
