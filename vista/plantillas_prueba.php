<?php 
include '../controlador/c_plantilla.php';
?>


<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
    </head>
    <body>
      
       
        
        <?php
        $texto = "señor:;nombre;se le recuerda compromiso;fecha;de saldo;monto";
         $palabras = devuelvePalabras($texto, ";");
         
         foreach ($palabras as $value) {
             
             ?><label>Palabra: <?php echo $value; ?> </label><br><?php
             
         }
         
        ?>
        
    </body>
    
</html>