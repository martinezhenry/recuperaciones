<?php
session_start();
require_once "../modelo/conexion.php";
require_once 'c_autorizar.php';
($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);


/**
 *    
 *  Carga las exoneraciones de una cuenta de la BD
 * 
 *    PARAMETROS:
 *      -- $cuenta = la cuenta de la desea buscar los datos
 * 
 **/
function cargarExoneraciones($cuenta)
{
    $id = 34;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {


            global $conex;
        $sql = "select cuenta, mo_saldo_inicial, mo_castigo, mo_exonera, mo_saldo_final, fe_exonera, co_motivo_exonera, in_status, cuotas, fecha_cuotas
                 from sr_exonera_cuenta
                 where cuenta = '$cuenta' ORDER BY FE_EXONERA DESC";


        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $exoneraciones[] = $fila;
            }

            return $exoneraciones;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {
            return $exoneraciones = $e['not_tab14'];
            exit();
        }
    }
}

function inactivarExoneracion($cuenta, $cartera, $cliente){
    
    global $conex;
            $sql = "Select cuenta from sr_cuenta where cliente = '$cliente' and cartera = '$cartera' and cuenta = '$cuenta' and area_devolucion is not null";
        
        $st = $conex->consulta($sql);
        
        while ($fila = $conex->fetch_array($st)){
            
            $filas[] = $fila;
            
        }
        
        if (isset($filas)){
            
            return "No se puede cargar un abono en una cuenta que esta en area de devolucion";
            
        }
    
    
        $sql = "UPDATE SR_EXONERA_CUENTA SET IN_STATUS = 'I' WHERE CUENTA = '$cuenta' AND IN_STATUS = 'P' AND CLIENTE = '$cliente' AND CARTERA = '$cartera'"; 


        $st = $conex->consulta($sql);
    
    
    return 1;
    
    
}




    //==========================================================================
    //
    //                              GET
    //
    //==========================================================================

        // Se verifica que se recibe una varible cargarExoneraciones por el metodo GET
    if (isset($_GET['cargarExoneraciones'])) //=================================
    {
        $cuenta = $_GET['cuentaActual'];
        $exoneraciones = cargarExoneraciones($cuenta);
        
        
        if (is_array($exoneraciones))
        {
            echo '
                <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="exoneraciones-Tabla" width="100%">
                                            <thead>
                                                    <tr>
                                                            <th>Checks</th>
                                                            <th>'.$e['te_tab1'].'</th>
                                                            <th>'.$e['te_tab2'].'</th>
                                                            <th>'.$e['te_tab3'].'</th>
                                                            <th>'.$e['te_tab4'].'</th>
                                                            <th>'.$e['te_tab5'].'</th>
                                                            <th>'.$e['te_tab6'].'</th>
                                                            <th>'.$e['te_tab7'].'</th>


                                                    </tr>
                                            </thead>
                                            <tbody>';
            
            foreach ($exoneraciones as $value)
            {
                $value['FE_EXONERA'] = date("d/m/Y",strtotime($value['FE_EXONERA']));
                echo '       <tr class="gradeA" id="5">

                                                    <td class="center"><input type="checkbox" name="check-exoneraciones" value="'.$value['CUENTA'].'"></td>
                                                    <td>'.$value['MO_SALDO_INICIAL'].'</td>
                                                    <td>'.$value['MO_CASTIGO'].'</td>
                                                    <td>'.$value['MO_EXONERA'].'</td>
                                                    <td>'.$value['MO_SALDO_FINAL'].'</td>
                                                    <td>'.$value['FE_EXONERA'].'</td>
                                                    <td>'.$value['CO_MOTIVO_EXONERA'].'</td>
                                                    <td>'.$value['IN_STATUS'].'</td>
                                           </tr>';
                
            }
            
            echo '       </tbody>
                                    </table>

                                    <table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
                                            <tfoot id="pieexoneraciones-Tabla">
                                                    <tr>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>

                                                    </tr>
                                            </tfoot>
                                    </table>';
            
            
            
        } else 
        {
            
            echo "<label class=\"alerta\">".$exoneraciones."</label>";
            
            
            echo '<table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="exoneraciones-Tabla" width="100%">
                                            <thead>
                                                    <tr>
                                                            <th>Checks</th>
                                                            <th>'.$e['te_tab1'].'</th>
                                                            <th>'.$e['te_tab2'].'</th>
                                                            <th>'.$e['te_tab3'].'</th>
                                                            <th>'.$e['te_tab4'].'</th>
                                                            <th>'.$e['te_tab5'].'</th>
                                                            <th>'.$e['te_tab6'].'</th>
                                                            <th>'.$e['te_tab7'].'</th>


                                                    </tr>
                                            </thead>
                                            <tbody>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>



                                            </tr>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>



                                            </tr>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>



                                            </tr>

                                            <tr class="gradeA" id="5">


                                                    <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>



                                            </tr>

                                            <tr class="gradeA" id="5">

                                                    <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>



                                            </tr>


                                    </tbody>
                                    </table>

                                    <table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
                                            <tfoot id="pieexoneraciones-Tabla">
                                                    <tr>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>

                                                    </tr>
                                            </tfoot>
                                    </table>';
            
        }
        
        
        // Se verifica que se recibe una varible guardarExoneracion por el metodo GET
    } else if (isset ($_GET['guardarExoneracion'])) //==========================
    {
        
        global $conex;
        // Se capturan los valores a guardar
        $cuenta = $_GET['cuentaActual'];
        $cartera = $_GET['cartera'];
        $cliente = $_GET['cliente'];
        $usuario = $_SESSION['USER'];
        $saldoInicial = $_GET['saldoActual'];
        $saldoExonerar = $_GET['saldoExonerar'];
        $saldoFinal = $_GET['saldoCancelar'];
        $fecha = date("d/m/Y");
        
        $tipoPago = $_GET['tipoPago'];
        
        
                $sql = "Select cuenta from sr_cuenta where cliente = '$cliente' and cartera = '$cartera' and cuenta = '$cuenta' and area_devolucion is not null";
        
        $st = $conex->consulta($sql);
        
        while ($fila = $conex->fetch_array($st)){
            
            $filas[] = $fila;
            
        }
        
        if (isset($filas)){
            
            echo "No se puede cargar un abono en una cuenta que esta en area de devolucion";
            exit();
        }
        
        
        
        if ($tipoPago == "0")
        {
            $cuotas = 0;
            $fechaCuotas = "";
        }else
        {
        $cuotas = $_GET['cuotas'];
        $fechaCuotas = $_GET['fechaCuotas'];
        $fechaCuotas = date("d/m/Y", strtotime($fechaCuotas));
    }
        // Se eliminan los puntos (.)
        $saldoInicial = str_replace(".", "", $saldoInicial);
        $saldoInicial = trim(str_replace($_SESSION['simb_moneda'], "", $saldoInicial));
        $saldoExonerar = str_replace(".", "", $saldoExonerar);
        $saldoFinal = str_replace(".", "", $saldoFinal);
                

        
        
        $sql = "insert into sr_exonera_cuenta
               (cliente, cartera, cuenta, co_exonera, mo_saldo_inicial, mo_exonera, mo_saldo_final, fe_exonera, in_status, tx_usuario, co_motivo_exonera, tx_usuario_mod, fe_exonera_mod, mo_castigo, cuotas, fecha_cuotas)
               values ('$cliente','$cartera','$cuenta','1','$saldoInicial','$saldoExonerar','$saldoFinal','$fecha','P','$usuario','1','','','','$cuotas', '$fechaCuotas')";
      // echo $sql;
        $st = $conex->consulta($sql);
        
        echo "Se Genero";
        
    } else if (isset($_GET['aprobarExoneracion'])){ //==========================
        
              global $conex;
        $cuenta = $_GET['cuenta'];
          
    
        $sql = "UPDATE SR_EXONERA_CUENTA SET IN_STATUS = 'A' WHERE CUENTA = '$cuenta'";
        
        $st = $conex->consulta($sql);
        
        echo $e['not_tab15'];
        
    } else if (isset($_POST['inactivarExoneracion'])){
        
        
        $cuenta = $_POST['cuenta'];
        $cliente = $_POST['cliente'];
        $cartera =  $_POST['cartera'];
        
       // echo "$cartera , $cliente , $cuenta";
        
        
        
        
        $resp = inactivarExoneracion($cuenta, $cartera, $cliente);
       echo $resp;
        
        
    }
    
    


/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */



?>