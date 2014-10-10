<?php
session_start();
require_once "../modelo/conexion.php";
require_once 'c_autorizar.php';

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

/**
 *    
 *  Carga las asignaciones de una cuenta de la BD
 * 
 *    PARAMETROS:
 *      -- $cuenta = la cuenta de la desea buscar los datos
 * 
 **/
function cargarAsignaciones($cuenta)
{
    $id = 14;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
        global $conex;
        $sql = "select distinct ci.nombre as cliente,
                      ca.descripcion as cartera,
                      cu.cuenta, 
                      ti.descripcion as tipo_cuenta,
                      cu.persona, 
                      re.descripcion as ciudad, 
                      cu.saldo_inicial, 
                      cu.saldo_anterior, 
                      cu.saldo_actual,
                      cu.fecha_vencimiento,  
                    --  ar.descripcion as area_asignacion, 
                      ad.descripcion as area_devolucion, 
                      cu.fecha_devolucion, cu.devolucion, 
                      cu.usuario_gestor, cu.fecha_asig_gestor, 
                      cu.fecha_primera_asig, cu.status_cuenta, 
                      cu.fecha_cambio_status, 
                   --   ab.monto_deposito as monto_ult_abono, 
                   --   ab.fecha_deposito as fecha_ult_deposito, 
                      ce.descripcion as tipo_credito, 
                      cu.sticker, cu.gestionada, 
                      cu.observacion_interna, 
                      cu.observacion_externa, 
                      cu.fecha_ingreso, 
                      cu.fecha_ult_gestion, 
                      cu.tipo_cuenta_cliente, 
                      cu.tipo_asig, 
                      cu.fecha_asignacion,
                      cu.intereses_mora, 
                      cu.monto_total, 
                      cu.capital_vencido,
                      CDE.DIAS_MORA,
                      CDE.FECHA_CASTIGO,
                      CDE.FECHA_LIQUIDACION,
                      CDE.SALDO_INTERES,
                      CDE.SALDO_MORA,
                      CDE.FECHA_ULTIMO_PAGO,
                      CDE.MONTO_ULTIMO_PAGO,
                      CDE.SALDO_CAPITAL,
                      CDE.SALDO_CAPITAL_VENCIDO
                   from sr_cuenta cu, 
                     tg_cliente ci, 
                     sr_cartera ca, 
                     sr_tipo_cuenta ti, 
                     tg_region re, 
                     --sr_area_asignacion ar, 
                     sr_area_devolucion ad, 
                     --SR_ABONO ab, 
                     sr_tipo_credito ce,
                     sr_cuenta_detalle cde
                   where -- CU.AREA_ASIGNACION = AR.AREA_ASIGNACION
                     CU.AREA_DEVOLUCION = AD.AREA_DEVOLUCION(+)
                    and CU.CARTERA = CA.CARTERA 
                    and CU.CLIENTE = CI.CLIENTE 
                    --and CU.CODIGO_ULT_ABONO = AB.ABONO 
                    and CU.REGION = RE.REGION 
                    and CU.TIPO_CREDITO = CE.TIPO_CREDITO 
                    and CU.TIPO_CUENTA =  TI.TIPO_CUENTA
                    and TI.CARTERA = CU.CARTERA
                    and CA.CLIENTE = CI.CLIENTE
                    and TI.CLIENTE = CI.CLIENTE
                    and cu.cuenta = cde.cuenta(+)
                    and cu.cliente = cde.cliente(+)
                    and cu.cartera = cde.cartera(+)
                    and cu.cuenta = '$cuenta'";

        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $asignaciones[] = $fila;
            }

            return $asignaciones;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {
            return $asiganaciones = "No existen Registros para Esta Cuenta";
            exit();
        }
    }
}


    //==========================================================================
    //
    //                              GET
    //
    //==========================================================================

// Se verifica que se recibe una varible cargarAsignaciones por el metodo GET
if (isset($_GET['cargarAsiganaciones']))
{   
    $cuenta = $_GET['cuentaActual'];
    // Se llam la funcion cargarAsignaciones()
    $asiganacion = cargarAsignaciones($cuenta);
    
    // Se comprueba que el resultado devuelto sea un arreglo
    if (is_array($asiganacion))
    {
        // Se recorre el arreglo
      //  print_r(count($asiganacion[0]));
        foreach ($asiganacion as $value) {
            // Se imprimen los valores en una cadena
            echo $cadena = $value[0]."*".$value[1]."*".$value[3]."*".$value[5]."*".$value[6]."*".$value[7]."*".$value[8]."*".$value[9]."*".$value[10]."*".$value[11]."*".$value[12]."*".$value[13]."*".$value[14]."*".$value[15]."*".$value[16]."*".$value[18]."*".$value[19]."*".$value[20]."*".$value[21]."*".$value[22]."*".$value[23]."*".$value[24]."*".$value[25]."*".$value[26]."*".$value['FECHA_ASIGNACION']."*".$value[28]."*".$value[29]."*".$value['DIAS_MORA']."*".$value['FECHA_CASTIGO']."*".$value['FECHA_LIQUIDACION']."*".$value['SALDO_INTERES']."*".$value['SALDO_MORA']."*".$value[35]."*".$value[36]."*".$value['SALDO_CAPITAL']."*".$value['SALDO_CAPITAL_VENCIDO']."*".$value['MONTO_ULTIMO_PAGO']."*".$value['FECHA_ULTIMO_PAGO'];
            
        }
    } else {
        
        echo "<label class='alerta'>ERROR: ".$asiganacion."</label>";
        
    }
    
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */


?>