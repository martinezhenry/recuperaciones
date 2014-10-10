<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
$uploaddir = 'C:/xampp/htdocs/recuperaciones/soporte_abonos/';
$uploadfile = $uploaddir .'soporteR'.$_GET['abono'].'.'. end(explode(".", $_FILES['archivo']['name']));

$abono = 'R'.$_GET['abono'];

if (move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadfile)) {
    
     require_once $_SESSION['ROOT_PATH']."/modelo/conexion.php";
     $conex = new oracle($_SESSION['USER'], $_SESSION['pass']); // instancia de clase oracle
     
     $sql = "insert into sr_soporte_abonos (url, id_abono) values ('localhost/recuperaciones/soporte_abonos/".'soporteR'.$_GET['abono'].'.'. end(explode(".", $_FILES['archivo']['name']))."','$abono')";
     
     $st = $conex->consulta($sql);
    
    echo "1";
} else {
    echo "0";
}
