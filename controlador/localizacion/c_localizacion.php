<?php
//echo "aki es";
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
require_once $_SESSION['ROOT_PATH'].'/modelo/conexion.php';
require_once $_SESSION['ROOT_PATH'].'/controlador/c_autorizar.php';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);



function cargarPersonas($cedula, $nombre, $apellido, $area, $telefono, $direccion){
    
      
    ($cedula == "{}") ? '':$b_cedula = "L.ID = '$cedula'";
    ($nombre == "") ? '':$b_nombre = "UPPER(L.NOMBRE)  LIKE UPPER('%$nombre%')";
    ($apellido == "") ? '':$b_apellido = "UPPER(L.NOMBRE)  LIKE UPPER('%$apellido%')";
    ($area == "") ? '':$b_area = "L.CODIGO_AREA LIKE '%'||TO_NUMBER('$area')||'%'";
    ($telefono == "") ? '':$b_telefono = "L.TELEFONO LIKE '%'||TO_NUMBER('$telefono')||'%'";
    ($direccion == "") ? '':$b_direccion = "UPPER(L.DIRECCION) LIKE UPPER('%$direccion%')";
    
    
  
    
    
    global $conex;
    $sql = "   select NVL(L.FUENTE,' ') FUENTE, NVL(L.FECHA_CARGA,'') FECHA_CARGA,
                NVL(L.ID,' ') ID, NVL(L.NOMBRE,' ') NOMBRE,
                NVL(L.DIRECCION,' ') DIRECCION, NVL(L.CODIGO_AREA,' ') CODIGO_AREA,
                NVL(L.TELEFONO,' ') TELEFONO, NVL(L.FECHA_NACIMIENTO,'') FECHA_NACIMIENTO,
                NVL(L.ALTERNATIVO1,' ') ALTERNATIVO1, NVL(L.ALTERNATIVO2,' ') ALTERNATIVO2,
                NVL(L.ALTERNATIVO3,' ') ALTERNATIVO3 
                from cg_consolidacion l";
    
    
    if (isset($b_cedula)){
        
        if (isset($primera)){
            
            $sql .= " and ";
            $sql .= $b_cedula;
            
        } else {
        
        $sql .= " where ";
        $sql .= $b_cedula;
        $primera = 1;
        }
        
    } 
    
    if (isset($b_nombre)){
        
        if (isset($primera)){
            $sql .= " and ";
            $sql .= $b_nombre;
        } else {
            
            $sql .= " where ";
            $sql .= $b_nombre;
            $primera = 1;
            
        }
    }
    
    
    if (isset($b_apellido)){
        
        if (isset($primera)){
            $sql .= " and ";
            $sql .= $b_apellido;
        } else {
            
            $sql .= " where ";
            $sql .= $b_apellido;
            $primera = 1;
            
        }
    }
    
    
    if (isset($b_area)){
        
        if (isset($primera)){
            $sql .= " and ";
            $sql .= $b_area;
        } else {
            
            $sql .= " where ";
            $sql .= $b_area;
            $primera = 1;
            
        }
    }
    
    if (isset($b_telefono)){
        
        if (isset($primera)){
            $sql .= " and ";
            $sql .= $b_telefono;
        } else {
            
            $sql .= " where ";
            $sql .= $b_telefono;
            $primera = 1;
            
        }
    }
           
    if (isset($b_direccion)){
        
        if (isset($primera)){
            $sql .= " and ";
            $sql .= $b_direccion;
        } else {
            
            $sql .= " where ";
            $sql .= $b_direccion;
            $primera = 1;
            
        }
    }
    
    
    $st = $conex->consulta($sql);
    
    
    while ($fila = $conex->fetch_array($st)):
        
        $personas[] = $fila;
        
    endwhile;
    
    return (isset($personas)) ? $personas:false;
    
    
}


if (isset($_POST['cargarPersonas'])){

    $personas = cargarPersonas($_POST['cedula_v'], $_POST['nombre_v'], $_POST['apellido_v'], $_POST['codarea_v'], $_POST['telefono_v'], $_POST['direccion_v']);
   if (!$personas){
       
       echo '0';
       
   } else {
     
       echo json_encode($personas);
       
   }
 
   exit();
    
    
}
