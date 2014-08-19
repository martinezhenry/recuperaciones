<?php
session_start();
require_once "../modelo/conexion.php";
require_once 'c_autorizar.php';
($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);


/**
 *    
 *  Carga los convenios de una cuenta de la BD
 * 
 *    PARAMETROS:
 *      -- $cuenta = la cuenta de la desea buscar los datos del convenio
 * 
 **/
function cargarConvenios($cuenta)
{
    $id = 15;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
        global $conex;
        $sql = " select convenio, conv_cuotas, conv_saldo_actual, conv_porc_inicial, conv_monto_inicial, fecha_ingreso  
        from sr_convenio
        where cuenta = '$cuenta' ORDER BY FECHA_INGRESO DESC";


        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $convenios[] = $fila;
            }

            return $convenios;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {
            return $convenios = $e['not_tab12'];
        }
    }
}


    //==========================================================================
    //
    //                              GET
    //
    //==========================================================================

 // Se verifica que se recibe la variable cargarConvenios por el metodo GET
if (isset($_GET['cargarConvenios'])) //=========================================
{
    $cuenta = $_GET['cuenta'];
    
    $convenios = cargarConvenios($cuenta);
    
    if (is_array($convenios))
    {
        
        
        echo '<table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="convenios-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>Checks</th>
                                                        <th>'.$e['tc_tab1'].'</th>
                                                        <th>'.$e['tc_tab2'].'</th>
                                                        <th>'.$e['tc_tab3'].'</th>
                                                        <th>'.$e['tc_tab4'].'</th>
                                                        <th>'.$e['tc_tab5'].'</th>
                                                        <th>'.$e['tc_tab6'].'</th>
                                                        
                                                        

                                                </tr>
                                        </thead>
                                        <tbody>';
       
        foreach ($convenios as $value) {
            
            $saldoActual = $value['CONV_SALDO_ACTUAL'];
            $montoInicial = $value['CONV_MONTO_INICIAL'];
            $saldoActual = str_replace(",", ".", $saldoActual);
            $montoInicial = str_replace(",", ".", $montoInicial);
            $cuotas = $value['CONV_CUOTAS'];
            $montoCuotas = ($saldoActual-$montoInicial)/$cuotas;
            $montoCuotas = round($montoCuotas, 2);
            $montoCuotas = str_replace(".", ",",$montoCuotas);
            $value['FECHA_INGRESO'] = date("d/m/Y",strtotime($value['FECHA_INGRESO']));
            
            
        
                                      echo '  <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-convenios" value="'.$value['CONVENIO'].'"></td>
                                                <td>'.$value['CONV_SALDO_ACTUAL'].'</td>
                                                <td>'.$value['CONV_PORC_INICIAL'].'</td>
                                                <td>'.$value['CONV_MONTO_INICIAL'].'</td>
                                                <td>'.$value['CONV_CUOTAS'].'</td>
                                                <td>'.$montoCuotas.'</td>
                                                <td>'.$value['FECHA_INGRESO'].'</td>
                                                
		
                  
                                        </tr>';
                                        
        }
                        
           echo ' </tbody>
			</table>

			<table cellpadding="0" border="0" class="display dataTable" width="100%">
				<tfoot id="pieconvenios-Tabla">
					<tr>
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
        echo "<label class='alerta'>$convenios</label>";
        
        echo '<table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="convenios-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>Checks</th>
                                                        <th>'.$e['tc_tab1'].'</th>
                                                        <th>'.$e['tc_tab2'].'</th>
                                                        <th>'.$e['tc_tab3'].'</th>
                                                        <th>'.$e['tc_tab4'].'</th>
                                                        <th>'.$e['tc_tab5'].'</th>
                                                        <th>'.$e['tc_tab6'].'</th>
                                                        
                                                        

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
                                                
		
                  
                                        </tr>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
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
                                                
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        

                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-convenios" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>

	
                                </tbody>
			</table>

			<table cellpadding="0" border="0" class="display dataTable" width="100%">
				<tfoot id="pieconvenios-Tabla">
					<tr>
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
    
     // Se verifica que se recibe la variable guardarConvenio por el metodo GET
} else if (isset ($_GET['guardarConvenio'])) //=================================
{
    
    
        global $conex;
        $cuenta = $_GET['cuentaActual'];
        $cartera = $_GET['cartera'];
        $cliente = $_GET['cliente'];
        $usuario = "session";
        $saldoActual = $_GET['saldoActual'];
        $porcentajeInicial = $_GET['porcentajeInicial'];
        $montoInicial = $_GET['montoInicial'];
        $cuotas = $_GET['cuotas'];
        $usuario = "session";
        $fecha = date("d/m/Y");

        
        $sql = " Select SQ_CONVENIO.nextval from dual";
        $st = $conex->consulta($sql);
        $value = $conex->fetch_array($st);
        $convenio = $value[0];
        
        $saldoActual = str_replace(".", "", $saldoActual);
        $montoInicial = str_replace(".", "", $montoInicial);
    
        $sql = " insert into sr_convenio (convenio, cliente, cartera, cuenta, conv_saldo_actual, conv_flag_porc_monto, conv_porc_inicial, conv_monto_inicial, conv_cuotas, conv_status, usuario_ingreso, fecha_ingreso, usuario_ult_mod, fecha_ult_mod, status_monto_inicial, conv_monto_final, fecha_fin, conv_mo_castigo)
                 values ('$convenio','$cliente','$cartera','$cuenta','$saldoActual','S','$porcentajeInicial','$montoInicial','$cuotas','P','$usuario','$fecha','','','','','','')";
        
        $st = $conex->consulta($sql);
        
        echo "Se Genero";
    
} else if (isset($_GET['aprobarConvenio'])){ //================================================================
    
            global $conex;
        $convenio = $_GET['convenio'];
          
    
        $sql = "UPDATE SR_CONVENIO SET CONV_STATUS = 'A' WHERE CONVENIO = '$convenio'";
        
        $st = $conex->consulta($sql);
        
        echo $e['not_tab13'];
    
    
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */


?>