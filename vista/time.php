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
    </head>
    <body>
        <?php
        require_once '../modelo/conexion.php';
        $sql = "select * from sr_gestion";
        $conex = new oracle("VPCTOTAL", "cable");
        
        
        $inicio = microtime(true);
        $st = $conex->consulta($sql);
        $fin = microtime(true);
        
        echo "Total ".($fin-$inicio);
        
        ?>
    </body>
</html>
