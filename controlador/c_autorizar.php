<?php
@session_start();

require_once $_SESSION['ROOT_PATH'] .'/modelo/conexion.php';

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

function autorizar($id, $usuario){
    
    global $conex;
    
    $sql = "SELECT
            CASE
                WHEN (SELECT U.NIVEL FROM SR_USUARIO U WHERE U.USUARIO = '$usuario') <= (SELECT P.NIVEL FROM SR_PROCESOS P WHERE P.ID_PROCESO = '$id')
                THEN 1
            END AS AUTORIZACION
            FROM DUAL";
    $st = $conex->consulta($sql);
    
    $fila = $conex->fetch_array($st);
    
    if (empty($fila[0])){
        
        $sql = "SELECT 1 FROM SR_PERMISOS P
                WHERE P.ID_PROCESO = '$id'
                AND P.USUARIO = '$usuario'
                AND SYSDATE >= P.FECHA_INICIO
                AND SYSDATE <= P.FECHA_VENCIMIENTO";
        $st = $conex->consulta($sql);
        $fila = $conex->fetch_array($st);
        return (!empty($fila[0])) ? true : false;
       // return true;
    } else {
        
        //return (!empty($fila[0])) ? true : false;
        return true;
    }
 
   
}

?>