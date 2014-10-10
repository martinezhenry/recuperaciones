<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
// Llamamos al modelo de conexion
    require_once $_SESSION['ROOT_PATH']."/modelo/conexion.php";
    require_once $_SESSION['ROOT_PATH'].'/controlador/c_autorizar.php';
    require_once $_SESSION['ROOT_PATH'].'/vista/moneda/moneda.php';
    ($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']); // instancia de clase oracle


function consultarNovedad(){
    /*
     if ($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C'){
          $sql = "SELECT * FROM SR_NOVEDADES WHERE USUARIO_GESTOR = '".$_SESSION['USER']."'";
     } else if ($_SESSION['USER'][0] == 'S') {
         $sql = "SELECT * FROM SR_NOVEDADES WHERE USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO WHERE USUARIO_SUPERIOR = '".$_SESSION['USER']."'";
     } else {
         $sql = "SELECT * FROM SR_NOVEDADES";
     }
    */
    $sql = "SELECT * FROM SR_NOVEDADES WHERE USUARIO_GESTOR = '".$_SESSION['USER']."' order by fecha_ingreso desc";
    
    global $conex;
    
    $st = $conex->consulta($sql);
    
    while ($fila = $conex->fetch_array($st)){
        
        $filas[] = $fila;
        
    }
    
    return (isset($filas)) ? $filas:false;
    
}



function verificarNovedad(){
    /*
     if ($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C'){
          $sql = "SELECT * FROM SR_NOVEDADES WHERE USUARIO_GESTOR = '".$_SESSION['USER']."'";
     } else if ($_SESSION['USER'][0] == 'S') {
         $sql = "SELECT * FROM SR_NOVEDADES WHERE USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO WHERE USUARIO_SUPERIOR = '".$_SESSION['USER']."'";
     } else {
         $sql = "SELECT * FROM SR_NOVEDADES";
     }
    */
    $sql = "SELECT id_novedades FROM SR_NOVEDADES WHERE USUARIO_GESTOR = '".$_SESSION['USER']."' and status = '0'";
    
    global $conex;
    
    $st = $conex->consulta($sql);
    
    while ($fila = $conex->fetch_array($st)){
        
        $filas[] = $fila;
        
    }
    
    return (isset($filas)) ? $filas:false;
    
}


function cambiarStatus($ids){
    
    // stastus = 0  -- No Visto
    // stastus = 1  -- Visto

    if (count($ids)==0){
        
        return -1;
        
    } else {
        
        foreach ($ids as $value) {
            
            $sql = "UPDATE SR_NOVEDADES SET STATUS='1' WHERE ID_NOVEDAD = '$value'";
            global $conex;
            
            $st = $conex->consulta($sql);
            
        }
        return 1;
        
    }
    
    
}

if (isset($_POST['verificarNovedad'])){
    
    $resp = verificarNovedad();
    
    if (!$resp){
        echo "0";
    } else {
        echo "1";
    }
    
    exit();
    
} else if (isset($_POST['marcarVisto'])){
    
    $ids = $_POST['ids'];
    
    //print_r($ids);
    $resp = cambiarStatus($ids);
    
    if ($resp == -1){
        
        echo "-1";
        
    } else {
        
        echo "1";
        
    }
}