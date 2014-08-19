<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@session_start();
define ('DOLAR', '6.30');
function cambiarMoneda($moneda)
{
    (empty($moneda)) ? $moneda = 0.0:$moneda = $moneda;
    if ($_SESSION['lang'] === 'es'){
        
        $_SESSION['simb_moneda'] = 'Bs';
        
        $moneda = number_format($moneda, 2, ",", ".");
        return $moneda;
    } else {
       ///$moneda = str_replace(',', '.', $moneda);
         
        $_SESSION['simb_moneda'] = '$';
        
       $resultado = $moneda / DOLAR;
       $resultado = number_format($resultado, 2, ".", ",");
       return $resultado;
       
    }
   // $DOLAR = 6.30;
   // $resultado = $moneda * DOLAR;

    ///return $resultado;
    
}
