<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
require_once $_SESSION['ROOT_PATH']."/modelo/conexion.php";
($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']); // instancia de clase oracle
function cargarDatos($sql){
    
    global $conex;
    
    $st = $conex->consulta($sql);
    
    while($fila = $conex->fetch_array($st)){
        
        $filas[] = $fila;
        
    }
    
    return (isset($filas)) ? $filas:0;
    
    
    
}



if (isset($_POST['cargarDatos'])){
    
    
    $sql = "select p.persona, F.DESCRIPCION as fuente, p.id as cedula, P.NOMBRE, d.direccion, t.cod_area||t.telefono as telefono, p.fecnac from tg_persona p, tg_fuente f, tg_persona_dir_adi d, tg_persona_tel_adi t where f.fuente = p.fuente and d.persona(+) = p.persona
and p.persona = t.persona(+) ";
    
    if (!empty($_POST['cedula'])){
        
        $sql .= " and p.id='".$_POST['cedula']."'";
        
    }
    
    if (!empty($_POST['direccion'])){
        
        $sql .= " and upper(d.direccion) like upper('%".$_POST['direccion']."%')";
        
    }
    
    if (!empty($_POST['codarea'])){
        
        $sql .= " and t.cod_area = '".$_POST['codarea']."'";
        
    }
    
    
    if (!empty($_POST['telefono'])){
        
        $sql .= " and t.telefono = '".$_POST['telefono']."'";
        
    }
    
    
    if (!empty($_POST['nombre'])){
        
        $sql .= " and upper(p.nombre) like upper('%".$_POST['nombre']."%')";
        
    }
    
    
     if (!empty($_POST['apellido'])){
        
        $sql .= " and upper(p.nombre) like upper('%".$_POST['apellido']."%')";
        
    }
    
    $resp = cargarDatos($sql);
    
    if ($resp != 0){
        
        echo json_encode($resp);
    } else {
        echo "0";
    }
      
}