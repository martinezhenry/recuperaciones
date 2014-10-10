<?php
//session_start();
 //Llamado a la conexion de la BD
@session_start();
include_once ("../modelo/conexion.php");
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);//Objeto de la clase oracle


function cargarEmail($persona){
    
    
    $sql = "SELECT a.direccion, a.status_email, decode(a.status_email, 'A', 'Activo', 'Inactivo') as status_desc, a.id_email, B.PARENTESCO
            FROM tg_persona_email a, sr_parentesco b where A.ID_PARENTESCO = B.ID_PAR and a.persona = '$persona' order by status_email";
    global $conex;
    $st = $conex->consulta($sql);
    
    while ($fila = $conex->fetch_array($st)) {
        
        $filas[] = $fila;
        
        
    }
    
    return (isset($filas)) ? $filas:false;
    
    
}


function guardarEmails($persona, $modif, $new){
    
    global $conex;
    
 if ($modif != null){
    foreach ($modif as $value) {
        
        $sql = "update tg_persona_email set status_email = '".$value[1]."' where id_email = '".$value[0]."'";
        $st = $conex->consulta($sql);
        
    }
 }
  if ($new != null){
    foreach ($new as $value) {
        if (empty($value)){
            return 0;
        }
        $sql = "INSERT INTO tg_persona_email (DIRECCION, STATUS_EMAIL, PERSONA, id_parentesco) VALUES ('$value[0]', 'A', '$persona', '$value[1]')";
       // echo $sql;
        $st = $conex->consulta($sql);
    }
  }
    return 1;
}


function cargarParentesco(){
    
    $sql = "SELECT * FROM SR_PARENTESCO";
    global $conex;
    $st=$conex->consulta($sql);
    while($fila = $conex->fetch_array($st)){
        $filas[] = $fila;
    }
    
    return (isset($filas)) ? $filas:0;
}


if (isset($_POST['cargarEmail'])){
    
    $emails = cargarEmail($_POST['persona']);
    
    
    if (!$emails) {
        
        echo "0";
        
    } else {
        
        echo json_encode($emails);
       
    }
    exit();
    
} else if (isset($_POST['guardarEmails'])){
    
    $editar = (isset($_POST['ids'])) ? $_POST['ids']:null;
    $new = (isset($_POST['nuevos'])) ? $_POST['nuevos']:null;
   $resp = guardarEmails($_POST['persona'], $editar, $new);
    if ($resp)
        echo "1";
    else
        echo "0";
    exit();
} else if (isset($_POST['cargarParentesco'])){
    
    $resp = cargarParentesco();
    if ($resp != 0){
        echo json_encode($resp);
    } else {
        echo "0";
    }

}

    

?>