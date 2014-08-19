<?php
session_start();
require_once "../modelo/conexion.php";

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

/**
 *    
 *  Conecta con la BD para obtener los registros de las observaciones 
 *  
 *  PARAMETROS
 *  -- $cuenta = la cuenta a la que se le desea obtener los datos
 * 
 **/
function cargarObservaciones($cuenta)
{
    
    global $conex;
    
    $sql = "select observacion_interna, observacion_externa from sr_cuenta where cuenta = '$cuenta'";
    
    $st = $conex->consulta($sql);
    // Se ebtienen el numero de filas devueltas
    $numrow = $conex->filas($st);

    if ($numrow > 0) {
        $st = $conex->consulta($sql);
        while ($fila = $conex->fetch_array($st)) {
            $observaciones[] = $fila;
        }

        return $observaciones;
        unset($output);
        unset($sql);
        unset($st);
        exit();
    } else { // En caso de que no hayan resultados
        return $observaciones = "No existen Observaciones para Esta Cuenta";
        exit();
    }
    
    
}


    //==========================================================================
    //
    //                                GET
    //
    //==========================================================================

    // Se verifica que se envie la varible observaciones por GET
if (isset($_GET['observaciones'])) //===========================================
{
    $cuenta = $_GET['cuentaActual'];
    // Se llama a la funcion para obtener las observaciones
    $observaciones = cargarObservaciones($cuenta);
    // Se verifica que el valor devuelto sea un arreglo
    if (is_array($observaciones))
    {   
        // Se imprime cadena con los valores
        echo $observaciones[0]['OBSERVACION_INTERNA']."*".$observaciones[0]['OBSERVACION_EXTERNA'];
        exit();
    } else // En caso de que no sea un arreglo
    {
        echo $observaciones;
        exit();
    }
    
    
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */



?>