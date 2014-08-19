<?php 
require_once "../modelo/conexion.php";
if (isset($_GET)){
session_start();
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);



    $clave = $_GET['clave'];
    $nueva = $_GET['nueva'];
    
    if ($_SESSION['pass'] === $clave)
    {
        
        /*$c = $conex->get_conexion();
       (oci_password_change($c, $_SESSION['USER'], $clave, $nueva)) ?  "Cambio de Clave Exitoso" : "Los Datos No Coinciden Verifique";
        $conex->disconnect();*/
        
        $sql = "alter user ".$_SESSION['USER']." identified by \"$nueva\"";
        $st = $conex->consulta($sql);
        oci_free_statement($st);
        $sql = "UPDATE SR_USUARIO SET PASSWORD = '$nueva' WHERE USUARIO = '".$_SESSION['USER']."'";
        $st = $conex->consulta($sql);
        oci_free_statement($st);
        unset($sql);
        
        //$st = $conex->
        echo "Cambio de Clave Exitoso";
        
    } else{
        
        echo "Los Datos No Coinciden Verifique";
    }
    
    
    
    
    
}


?>