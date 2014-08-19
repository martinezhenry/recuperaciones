<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
require_once $_SESSION['ROOT_PATH'].'/modelo/conexion.php';
require_once $_SESSION['ROOT_PATH'].'/controlador/c_autorizar.php';
require_once $_SESSION['ROOT_PATH'].'/vista/moneda/moneda.php';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

function cargarSaldos($cliente, $cartera, $cuenta){
    
    $sql = "SELECT TO_CHAR(S.FECHA,'DD/MM/YYYY') AS FECHA, S.SALDO_ANTERIOR, S.DIFERENCIA, S.SALDO_ACTUAL FROM SR_ESTADO_DE_CUENTA S
            WHERE S.CLIENTE = '$cliente' 
               AND S.CARTERA = '$cartera'
               AND S.CUENTA = '$cuenta' ORDER BY S.FECHA DESC";
    
    global $conex;
    
    $st = $conex->consulta($sql);
    
    while ($fila = $conex->fetch_array($st)){
        
        $fila['SALDO_ANTERIOR'] = $_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$fila['SALDO_ANTERIOR']));
        $fila['DIFERENCIA'] = $_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$fila['DIFERENCIA']));
        $fila['SALDO_ACTUAL'] = $_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$fila['SALDO_ACTUAL']));
        $saldos[] = $fila;        
        
    }
    
    return (isset($saldos)) ? $saldos:0;
    

}



if (isset($_POST['cargarSaldos'])){
    
    
    $saldos = cargarSaldos($_POST['cliente'], $_POST['cartera'], $_POST['cuenta']);
    
    if ($saldos == 0){
        echo 0;
    } else {
        echo json_encode($saldos);
    }
    exit();
    
}