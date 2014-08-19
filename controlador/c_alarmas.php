<?php
session_start();
require_once '../modelo/conexion.php';
require_once 'c_autorizar.php';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

function cargarAlarmas(){
    $id = 3;
   
    if (!autorizar($id, $_SESSION['USER'])){
        
        return "";
        
        
    } else {
    $sql = "SELECT A.ID_ALARMA, A.CONTENIDO, TO_CHAR(A.FECHA,'IYYY-mm-dd hh24:mi:ss') AS MOMENTO
            FROM SR_ALARMAS A WHERE TRUNC(A.FECHA) = TRUNC(SYSDATE) AND STATUS = '1' AND USUARIO = '".$_SESSION['USER']."'";
    $alarmas="";
    global $conex;
    $st = $conex->consulta($sql);
    
    while ($fila = $conex->fetch_array($st)){
        
        $alarmas[] = $fila;
        
    }
    
    unset($sql, $conex, $st);
    return $alarmas;
    } //endif autorizacion
    
}

function ingresarAlarma($contenido, $momento){
    
    $id = 2;
    
    if (!autorizar($id, $_SESSION['USER'])){
        
        //echo "No Autorizado!";
        return 0;
        
        
    } else {
    
        global $conex;


        if ($momento == ""){
            return 2;


        } else {
        $momento = date("d/m/Y H:i:s",strtotime($momento));
        $sql = "INSERT INTO SR_ALARMAS (CONTENIDO, FECHA, USUARIO) VALUES ('$contenido', TO_DATE('$momento', 'dd/mm/yyyy hh24:mi:ss'), '".$_SESSION['USER']."')";
        @$st = $conex->consulta($sql);
       
        if ($st[0] != "O"){

        return 1;
        }
        else{
        return 3;
        }

        }
    } // endif autorizacion 
    
}


function cambiarstatus($id_a){
    $id = 5;
    
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
    $sql = "UPDATE SR_ALARMAS SET STATUS = '0' WHERE ID_ALARMA = '$id_a'";
    global $conex;
    
    $st = $conex->consulta($sql);
    return true;
    }
        
    
}

function alarmasProgramasdas(){
    $id = 4;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return Array(["CONTENIDO" => "No Autorizado!", "FECHA" => ""]);
        
    } else {
    $sql = "SELECT A.ID_ALARMA, A.CONTENIDO, TO_CHAR(A.FECHA, 'DD/MM/YYYY HH24:MI:SS') AS FECHA FROM SR_ALARMAS A WHERE A.USUARIO = '".$_SESSION['USER']."' AND A.STATUS = '1'";
    global $conex;
    $st = $conex->consulta($sql);
    $alarmas="";
    while ($fila = $conex->fetch_array($st)){
        
        $alarmas[] = $fila;
        
    }
    unset($st, $sql);
    return $alarmas;
    } //endif autorizacion
    
}

function eliminarAlarma($id_a){
    $id=6;
    
    if (!autorizar($id, $_SESSION['USER'])){
     
        return false;
        
    } else {
        global $conex;
        $sql = "UPDATE SR_ALARMAS SET STATUS = '0' WHERE ID_ALARMA = '$id_a'";
        @$st = $conex->consulta($sql);
        if ($st[0] != "O"){

        return true;
        }
        else{
        return false;
        }
    }
   
}
    
    


if (isset($_POST['cargarAlarmas'])){
    
    
    $alarmas = cargarAlarmas();
    
    echo json_encode($alarmas);
    exit();
    
    
} else if (isset($_POST['ingresarAlarma'])){
    
    $r = ingresarAlarma($_POST['contenido'], $_POST['momento']);
    
    switch($r){
        
        case 0: echo "false*No Autorizado!";
            break;
        case 1: echo "true*Alarma Creada";
            break;
        case 2: echo "false*Fecha u hora Invalida!";
            break;
        case 3: echo "false*Error Al Insertar en la BD!";
            break;
        default : echo "fasle*Error al Crear la Alarma";
            break;
    }
    

    
    
    
} else if (isset($_POST['cambiarStatus'])){
    
    echo cambiarstatus($_POST['id']);
    exit();
} else if ($_POST['alarmasProgramadas']){
    
    $alarmas = alarmasProgramasdas();
    
     echo json_encode($alarmas);
     exit();
    
    
} else if (isset($_POST['eliminarAlarma'])){
    
    echo eliminarAlarma($_POST['id']);
    exit();
    
}
    
    


?>
