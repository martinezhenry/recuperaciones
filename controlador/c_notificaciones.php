<?php
session_start();
include_once ("../modelo/conexion.php");
require_once 'c_autorizar.php';

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

function cargarNotificaciones(){
    $id = 39;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        global $conex;
        $usuario = $_SESSION['USER'];

        $sql = "SELECT N.CONTENIDO, N.FECHA, N.ID_GESTOR, U.NOMBRES, N.STATUS, N.ID_NOTIFICACION
                FROM SR_NOTIFICACIONES N, SR_USUARIO U
                WHERE (ID_GESTOR_DESTINO = '$usuario'
                AND U.USUARIO = N.ID_GESTOR
                AND N.FECHA >= sysdate - to_char(sysdate, 'd')+1 AND N.FECHA <= sysdate - to_char(sysdate, 'd')+7)
                OR
                (ID_GESTOR_DESTINO = 'GTE01'
                AND U.USUARIO = N.ID_GESTOR
                AND N.STATUS = 0)
                ORDER BY FECHA DESC";

        $st = $conex -> consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $notificaciones[] = $fila;
            }

            return $notificaciones;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {

            return false;

        }

    }
}


function enviarNotificacion($user_destino, $contenido){
    $id = 40;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
    global $conex;
    $usuario = $_SESSION['USER'];
    
    
   // $sql = "Select SQ_NOTIFICACIONES.nextval from dual";
    
   // $st = $conex -> consulta($sql);
   // $fila = $conex->fetch_array($st);
    // Se le asigna el valor del arreglo a una variable
   // $idNoti = $fila[0];
    
    $sql = "INSERT INTO SR_NOTIFICACIONES (CONTENIDO, FECHA, ID_GESTOR, ID_GESTOR_DESTINO)
            VALUES ('$contenido', SYSDATE, '$usuario', '$user_destino') ";
     if ($st = $conex -> consulta($sql))
     {
         return true;
     } else {
         return false;
     }
     
    }
    
    
}



function obtenerNoLeidos(){
    $id = 41;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
    global $conex;
    $usuario = $_SESSION['USER'];
    $sql = "SELECT COUNT(ID_NOTIFICACION) FROM SR_NOTIFICACIONES WHERE ID_GESTOR_DESTINO = '$usuario' AND STATUS = '0'";
    
    $st = $conex -> consulta($sql);
    $fila = $conex->fetch_array($st);
    $cantidad = $fila[0];
    return $cantidad;
    }
}

function cargarDestinatarios($usuario){
      $id = 42;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
    global $conex;
    switch($usuario[0]){
        
         /* T o C */                      
        case 'T': 
        case 'C': 
                   
                  $sql = "SELECT  A.USUARIO, A.NOMBRES 
                            FROM SR_USUARIO A
                            WHERE A.USUARIO in(SELECT U.USUARIO_SUPERIOR FROM SR_USUARIO U WHERE U.USUARIO ='$usuario' AND U.STATUS_USUARIO = 'A')";
                  $st = $conex -> consulta($sql);
                  //$fila = $conex->fetch_array($st);
                    
                      while ($fila = $conex->fetch_array($st)) {
                        $supervisor[] = $fila;
                     }
                     
                     foreach ($supervisor as $value) {
                         
                     
                  $sql ="SELECT USUARIO, NOMBRES
                            FROM SR_USUARIO 
                            WHERE USUARIO_SUPERIOR = '".$value['USUARIO']."' 
                            AND STATUS_USUARIO = 'A'";
                   
                     }
                  $st = $conex -> consulta($sql);
                  //$fila = $conex->fetch_array($st);
                  $destinatarios[] = $supervisor[0];
                 
                     while ($fila = $conex->fetch_array($st)) {
                        $destinatarios[] = $fila;
                     }
                  
                  return $destinatarios;
                  break;
                      
                    
        /* S  */
        case 'S': 
            
                $sql = "SELECT  A.USUARIO, A.NOMBRES 
                        FROM SR_USUARIO A
                        WHERE A.USUARIO in(SELECT U.USUARIO_SUPERIOR FROM SR_USUARIO U WHERE U.USUARIO ='$usuario' AND U.STATUS_USUARIO = 'A')";
                  $st = $conex -> consulta($sql);
                 // $fila = $conex->fetch_array($st);
                    
                      while ($fila = $conex->fetch_array($st)) {
                        $gerente[] = $fila;
                     }
                    
                         
                     
                $sql = "SELECT USUARIO, NOMBRES
                        FROM SR_USUARIO 
                        WHERE USUARIO_SUPERIOR = '$usuario' 
                        AND STATUS_USUARIO = 'A'";
                    
                  $st = $conex -> consulta($sql);
                  $fila = $conex->fetch_array($st);
                  
                  $destinatarios[] = $gerente[0];
                  while ($fila = $conex->fetch_array($st)) {
                        $destinatarios[] = $fila;
                  }
                  return $destinatarios;
                  break;
            
        
        /* GTE */     
        case 'G':
            
                $sql = "SELECT USUARIO, NOMBRES
                        FROM SR_USUARIO 
                        WHERE USUARIO_SUPERIOR = '$usuario' 
                        AND STATUS_USUARIO = 'A'";
                  
                  $st = $conex -> consulta($sql);
                  //$fila = $conex->fetch_array($st);
                  
                 
                  while ($fila = $conex->fetch_array($st)) {
                        $destinatarios[] = $fila;
                  }
                  return (isset($destinatarios)) ? $destinatarios:false;
                  break;
        
        
    }
   
    
    
    }
    
}

function cambiarStatus($id_noti){
         $id = 43;
         
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
       
    global $conex;
    $sql = "update sr_notificaciones set status = 1 where id_notificacion = $id_noti";
  //   echo "$id";
    $st = $conex -> consulta($sql);
    exit();
    }
    
}


if (isset($_POST['cargarNoti'])){
    
    $notificaciones = cargarNotificaciones();
    
    if (!$notificaciones){
        
        echo "<div style='width: 100%; text-align:center; margin: 0 auto;'>No Existes Notificaciones</div>";
        
    } else {
        
     
            
            
            echo "<table style='font-size: 11px;' cellpadding='0' cellspacing='0' border='1' class='display dataTable' width='100%'><thead>
                    <th>De:</th>
                    <th>Contenido</th>
                    <th>Fecha</th>
                    
                  </thead></table><div style='height: 150px; overflow: auto;'><table style='font-size: 11px;' cellpadding='0' cellspacing='0' border='1' class='display dataTable' id='noti-Tabla' width='100%'><th></th>
                    <th></th>
                    <th></th>
                    
                  </thead>
                  
                  <tbody>";
            $i=0;
            foreach ($notificaciones as $value) {
                     if ($i%2==0) // Vemos si 54 dividido en 2 da resto 0 si lo da
                { $par = "odd"; } //escribo Par
                else //Sino
                { $par = "even"; }
                if ($value['STATUS'] == 0){
                    
                    $color = "sinLeer";
                    $accion = "cambiarStatus(this.id);";
                    
                } else {
                    
                    $color = "gradeA ".$par;
                    $accion = "";
                    
                }
           
                
                echo "<tr style='cursor: pointer;' onclick='".$accion." cargarContenido(id);' id = '".$value['ID_NOTIFICACION']."' class = '".$color."'><td><div class='de' style='height:15px; overflow: hidden;'>".$value['NOMBRES']." (".$value['ID_GESTOR'].")</div></td><td><div class='content' style='height:15px; overflow: hidden;'>".$value['CONTENIDO']."</div></td><td><div class='fecha' style='height:15px; overflow: hidden;'>".$value['FECHA']."</div></td></tr>";
             $i++;   
            }
            
            
            echo "</tbody></table></div>";
        exit();
    }
    
    
} else if (isset ($_POST['cargarDesti'])){
    
    $usuario = $_SESSION['USER'];
    $destinatarios = cargarDestinatarios($usuario);
    
    
    
            
            
            echo "<table style='font-size: 11px;' cellpadding='0' cellspacing='0' border='0' class='display dataTable'  width='100%'><thead>
                    <th width='20px'>Check</th>
                    <th>Para:</th>
                    
                    
                    
                  </thead></table><div style='height: 150px; overflow: auto;'><table style='font-size: 11px;' cellpadding='0' cellspacing='0' border='1' class='display dataTable' id='notiDestinatarios-Tabla' width='100%'><th></th>
                    <th></th>
                    <th></th>
                    
                  </thead>
                  <tbody>";
            $i=0;
           if (!$destinatarios){
               
               echo "<tr><td class='center' colspan='3'>No Exites Resultados</td></tr>";
           } else {
            foreach ($destinatarios as $value) {
                
                if ($i%2==0) // Vemos si 54 dividido en 2 da resto 0 si lo da
                { $par = "odd"; } //escribo Par
                else //Sino
                { $par = "even"; }
                
                echo "<tr class='gradeA ".$par."'><td><input name='checkDesti' type='checkbox' value='".$value['USUARIO']."' /></td><td>".$value['NOMBRES']." (".$value['USUARIO'].")</td></tr>";
                $i++;
            }
           }
            
            echo "</tbody></table>";
            
    
        
        exit();
    
    
} else if (isset ($_POST['enviarNoti'])){
    
    $destinatarios = $_POST['destinatarios'];
    $contenido = $_POST['contenido'];
    $error = false;
    $destinatarios = explode(",", $destinatarios);
    
    foreach ($destinatarios as $value) {
        
        $error = enviarNotificacion($value, $contenido);
        
    }
    
    return $error;
    
    
} else if (isset ($_POST['cambiarStatus'])){
    
    $id = $_POST['id'];
    cambiarStatus($id);

    
} else if (isset ($_POST['contador'])){
    
    echo obtenerNoLeidos();
    
}
?>