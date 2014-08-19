<?php
session_start();
require_once '../modelo/conexion.php';
require_once 'c_autorizar.php';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);



function cargarVariables(){
    $id = 52;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
    $sql = "SELECT ID, CAMPO FROM SR_VARIABLES WHERE STATUS = '1' ORDER BY CAMPO";
    global $conex;
   // $variables="";
    
    $st = $conex->consulta($sql);
    while ($fila = $conex->fetch_array($st)){
        
        $variables[] = $fila;
        
    }

   return $variables;
    }
    
}

function guardarPlantilla($plantilla, $variables){
    $id = 53;
       if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
    global $conex;
    $sql = "Select SR_PLANTILLAS_SEQ.nextval from dual";
    $st = $conex->consulta($sql);
    $fila = $conex->fetch_array($st);
    $id_plantilla = $fila[0];
    
    
    $sql = "INSERT INTO SR_PLANTILLAS (ID, TEXTO_A, ESTATUS) VALUES ('$id_plantilla', '$plantilla', '1')";
    $st = $conex->consulta($sql);
    
    foreach ($variables as $value) {
    $sql = "INSERT INTO SR_PLANTILLAS_VARIABLES (ID_PLANTILLA, ID_VARIABLE) VALUES ('$id_plantilla', '$value')";    
    $st = $conex->consulta($sql);
    }
    return true;
    }
    
}


function cargarPlantillas(){
        $id = 54;
       if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
    global $conex;
    $sql = "SELECT ID, TEXTO_A, DECODE(ESTATUS,'1','ACTIVADA','DESACTIVADA') AS ESTATUS FROM SR_PLANTILLAS ORDER BY ID";
    $st = $conex->consulta($sql);
    while ($fila = $conex->fetch_array($st)){
        
        $plantillas[] = $fila;
        
    }
    return $plantillas;
    
    }
    
}


function eliminarPlantilla($id_plantilla){
            $id = 55;
       if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {

    global $conex;
    $sql = "DELETE SR_PLANTILLAS WHERE ID = '$id_plantilla'";
    $st = $conex->consulta($sql);
    return true;
}
    
}


function cambiarStatus($id_plantilla, $accion){
                $id = 56;
       if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {

    global $conex;
    $sql = "UPDATE SR_PLANTILLAS SET ESTATUS = '$accion' WHERE ID = '$id_plantilla'";
    $st = $conex->consulta($sql);
    return true;
    }
    
}

if(isset($_POST['guardarPlantilla'])){
    
    $r = guardarPlantilla($_POST['plantilla'], json_decode($_POST['id_variables']));
    
    if ($r){
        
        echo "Guardada";
        exit();
        
    }
    
    
} else if (isset($_POST['cargarPlantillas'])){
    
    $plantillas = cargarPlantillas();
    
    echo json_encode($plantillas);
    exit();
        
        
    
    
} else if (isset ($_POST['eliminarPlantilla'])){
    
    echo eliminarPlantilla($_POST['id_plantilla']);
    exit();
    
} else if (isset ($_POST['cambiarStatus'])){
    
    echo cambiarStatus($_POST['id_plantilla'], $_POST['accion']);
    exit;
    
}






?>