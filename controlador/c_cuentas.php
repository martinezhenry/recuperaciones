<?php
session_start();
require_once "../modelo/conexion.php";

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);


function validarCopiaGestion(){
    
    
    
    
}


if (isset($_GET['buscarDetallesDeCuenta'])){
    
    global $conex;
    $cuenta = $_GET['cuenta'];
    $sql = "SELECT CU.CLIENTE, CU.CARTERA, CU.USUARIO_GESTOR, CU.TIPO_CUENTA, CU.SALDO_ACTUAL
            FROM sr_cuenta cu
            WHERE CU.CUENTA = '$cuenta'";
    
    $st = $conex->consulta($sql);
    $detalles = $conex ->fetch_array($st);
   
    
   
        
        $detalle = $detalles['CLIENTE'].",".$detalles['CARTERA'].",".$detalles['USUARIO_GESTOR'].",".$detalles['TIPO_CUENTA'].",".$detalles['SALDO_ACTUAL'];
        
   
    echo $detalle;
    exit();
}





?>
