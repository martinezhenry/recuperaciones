<?php
/*session_start();
echo $_SESSION['idPersona'];
*/
require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/direcciones.php';

// Arreglo de los idPersona que se reciben de la pantalla Agenda
//$arrCuentas = array(23000002, 21389672, 21389669, 5812);
$filtro = "";
if (isset($_POST['checkcuentas']) || isset($_POST['checkPortafolio']) || isset($_GET['smsper']) ){
    
    if (isset($_POST['checkcuentas'])) {
        $arrCuentas = $_POST['checkcuentas'];
        
        $filtro = "NULL";
    }
    else  if (isset($_POST['checkPortafolio'])) {
        $arrCuentas = $_POST['checkPortafolio'];
        $filtro = $_POST['filtroUsado'];
        ($filtro != "NULL") ? $l = 1 : $l = 0;
        
      
              foreach ($arrCuentas as $valor){
        
            $arr[] = explode("*", $valor);
            
            //echo $valor;
                    
        }
        $i=0;
        foreach ($arr as $per)
        {
            
            $personas[] = $per[$l];
           //$d = ($per[0]."_".$i);
           // ==$cuen[$d] = $per[1];
            $i++;
        }
        $personas = array_unique($personas);
        sort($personas);
        //sort($cuen);
        $arrCuentas = $personas;
        //echo "<br>".count($personas);
        //$arrCuentasPort = "";
        //$pestaña = $_POST['pestaña'];
        
    } else if (isset($_GET['smsper'])){
        
        
         $arrCuentas[] = $_GET['smsper'];
         $filtro = "NULL";
         
    } 
    



} else {
     
  $arrCuentas = array();
  $cuen = array();
     
 }
 


// Se verifica que el arreglo de los idPersona no esten vacios
if (empty($arrCuentas))
{
    $ejecuta = "";
} else
{
    // Ejecutamos el evento click del boton envio
    

        $ejecuta = "document.getElementById('envio').click();";

}


if (isset($_GET['brapida'])){
        
          $r_cedula = $_GET['brapida'];
        $ejecuta = "$('#btn_buscarCi').click();";
   
} else {
    
     $r_cedula = "";
     
}


// Variable que contiene toda la vista del cuerpo de la pantalla expediente
$expediente = '


 <body class="bg-blue" onload="llamaTodo(\'cuentas-Tabla\'); llamaTodo(\'gestiones-Tabla\'); llamaTodo(\'abonos-Tabla\'); '.$ejecuta.'" >
<div id= "pantalla_2">
 
<form hidden>


';


// Se recorre arreglo de los idPersona enviados de la pantalla agenda
for ($i=0; $i < count($arrCuentas); $i++)
{
    // Se crean input para cada idPersona
    $expediente .= "<input id='arr_".($i+1)."' type=\"text\" value='".$arrCuentas[$i]."'/>";
    
}

// Se recorre arreglo de los idPersona enviados de la pantalla agenda
/*
foreach ($cuen as $key => $value) {

    // Se crean input para cada idPersona
    $expediente .= "<input id='".$key."' type=\"text\" value='".$value."'/>";
  
}*/

$expediente .= '</form>
<div class="typography">
										
                                                                <form class="sky-form" style ="box-shadow: 0 0 0px;"/>                                                                                
                                                               
                                                                                <fieldset style="padding: 10px 0px 0px;">
                                    
                                    
                                    
                                    <div class="row">
                                    <div>
                                        <button disabled id="btn_validar" style="margin-left: 0%; float: left; margin-top: 0.3%;" type="button"><img style="margin:-4px;" title="Validar" width="25px" heigth="25px" src="img/ico/validar.ico"/></button>
                                       <!-- <button disabled id="btn_ver_datos" style="margin-left: -6%; float: left; margin-top: 1%;" type="button" ><img title="Ver Datos" width="25px" heigth="25px" src="img/ico/ver.ico"/></button> -->
                                        <a disabled id="btn_ver_datos" style="margin-left: 0%; float: left; margin-top: 0%;" target="_blank" href="http://www.ivss.gob.ve"><img title="Ver Datos" width="25px" heigth="25px" src="img/ico/ver.ico"/></a>
                                        </div>
                                        <section style="margin-bottom: -5px;" class="col col-0">
                                        
                                            <div style="margin-right: 10px;"> 

                                               
                                                <label class="radio" style="margin-right: 17px; line-height: 40px; margin-right: -27px;"><input type="radio" name="radio-inline" checked=""><i style="top: 12px;"></i><span id="nacionalidadDeudor">V</span></label>
                                            </div>
                                        </section>
                                        <section class="col col-3" style="width:200px;">
                                          
                                            <label class="input" >
                                                <input type="text" value="'.$r_cedula.'" placeholder="{dt1_ci}" onkeypress="checkKey(this.value, event);" id="cedulaDeudor" style="width: 100%;" name="cedulaDeudor" /></label>
                                            <button style="margin: -38px 0 0 -1px; padding: 0 0px; height: 34px;" type="button" onclick="consultarDeudor(document.getElementById(\'cedulaDeudor\').value, \'NULL\');" name="campo" id="btn_buscarCi" class="button"><img src="icons/icono_lupa.gif"></button>
                                            <img hidden id = "check_valida" src="img/ico/check.ico" width="20px" height="20px"/>



                                        </section>
                                        <section class="col col-5" style="width: 450px;">

                                            
                                            <label class="input" >

                                                <input type="text" id="nombreDeudor" placeholder="{dt2_name}" disabled/> </label>


                                        </section>
                                        <section class="col col-3" style="width: 200px;">

                                            <label class="input" >
                                                <input type="text" placeholder="{dt3_nac}" id="fechaNacDeudor"  disabled  />
                                            </label>




                                        </section>
                                        
                                        <button title="Guardar" id="guardar" disabled onclick="if (tabActivo.value == \'gestiones\'){ guardarGestion(document.getElementById(\'cuentaActual\').value); } else if (tabActivo.value == \'abonos\') { guardarAbonos(cuentaActual.value); }" type="button"><img width="30px" heigth="30px" src="img/ico/guardar.ico"/></button>
                                    <button title="Agregar" id= "agregar" onclick= "agregarFila(tabActivo.value+\'-Tabla\')" disabled type="button"><img width="30px" heigth="30px" src="img/ico/agregar.ico"/></button>
                                    <button title="Modificar" id= "modificar" disabled onclick="if (tabActivo.value == \'gestiones\'){ modificarGestion(); } else if (tabActivo.value == \'abonos\'){ modificarAbono(); }" disabled type="button"><img width="30px" heigth="30px" src="img/ico/modificar.ico"/></button>
                                    <button title="Eliminar" id= "eliminar" disabled onclick="if (tabActivo.value == \'gestiones\'){ eliminarGestion(); } else if (tabActivo.value == \'abonos\'){ eliminarAbono(); } else if (tabActivo.value == \'exoneraciones\'){ inactivarExoneracion($(\'#cuentaActual\').val(), $(\'#clienteActual\').val(), $(\'#carteraActual\').val());  }" disabled type="button"><img width="30px" heigth="30px" src="img/ico/eliminar.ico"/></button>
                                    <button title="Cancelar" id= "cancelar" onclick="if (tabActivo.value == \'gestiones\'){ cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value); } else if (tabActivo.value == \'abonos\'){ cargarAbonos(cuentaActual.value); }" hidden type="button"><img width="30px" heigth="30px" src="img/black-cancel.png"/></button>
                                   <!-- <button title="Limpiar Pantalla" onclick="location.reload();" type="button"><img width="30px" heigth="30px" src="img/ico/limpiar.ico"/></button> -->
                                    <button title="Reporte Gestiones" disabled id="btnReporteGestion" onclick="crearReporteGestiones(cuentaActual.value);" type="button"><img width="30px" heigth="30px" src="img/ico/pdf.ico"/></button>


                                   </div>
                                          
                                    
                                          
                                    </fieldset>
                           
                                        </form>


									</div>
    
<div style="background: rgba(255,255,255,.9);" class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-slide-top sky-tabs-response-to-stack">
							<input type="radio" name="sky-tabs-1" checked id="sky-tab1-1" class="sky-tab-content-1">
							<label class="pequeño" for="sky-tab1-1"><span><span>Cuentas</span></span></label>
							
							<input disabled onclick="v_llamada_cargarTabla();" type="radio" name="sky-tabs-1" id="sky-tab-1-2" class="sky-tab-content-2">
							<label class="pequeño" for="sky-tab-1-2"><span><span>{d_tab2}</span></span></label>
							
							<input disabled onclick="v_direccion_cargarTabla();" type="radio" name="sky-tabs-1" id="sky-tab1-3" class="sky-tab-content-3">
							<label class="pequeño" for="sky-tab1-3"><span><span>{d_tab3}</span></span></label>
                                                        
                                                        <input disabled onclick="cargarEmail(personaActual.value);" type="radio" name="sky-tabs-1" id="sky-tab1-4" class="sky-tab-content-4">
							<label class="pequeño" for="sky-tab1-4"><span><span>Email</span></span></label>
							
							<ul>
								<li style="height: 265px; background: inherit;" class="sky-tab-content-1">';
									
                                                                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/cuentas/cuentas.php';
 
                                                                $expediente .= $V_cuentas;

								$expediente .= '</li>
								<li style="height: 265px; background: inherit;" class="sky-tab-content-2">
									    
                                                                '.$llamadas.'
                                                                    
								</li>
								<li style="height: 265px; background: inherit;" class="sky-tab-content-3">
                                                                '.$direcciones.'</li>
                                                                    
                                                                <li style="height: 265px; background: inherit;" class="sky-tab-content-4">
                                                                
                                                                '.$email.'

								</li>
							</ul>
                                                       
						</div>';
                                                


$expediente .=
'<div style="background: rgba(255,255,255,.9);" class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-slide-top sky-tabs-response-to-stack">
							
                                                        <input disabled onclick="if ($(\'#AgregarActivo\').val() != 1)cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value, saldoActualCuenta.value); document.getElementById(\'tabActivo\').value = \'gestiones\'; " type="radio" name="sky-tabs-2" checked id="gestiones-1" class="sky-tab-content-1">
							<label class="pequeño" for="gestiones-1"><span><span style="padding: 0 14px;">{g_tab1}</span></span></label>
							
							<input disabled onclick="if ($(\'#AgregarActivo\').val() != 1){ cargarAbonos(cuentaActual.value); } document.getElementById(\'tabActivo\').value = \'abonos\';" type="radio" name="sky-tabs-2" id="gestiones-2" class="sky-tab-content-2">
							<label class="pequeño" for="gestiones-2"><span><span style="padding: 0 14px;">{g_tab2}</span></span></label>
							
							<input onclick="cargarAsignaciones(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-3" class="sky-tab-content-3">
							<label class="pequeño" for="gestiones-3"><span><span style="padding: 0 14px;">{g_tab3}</span></span></label>
                                                        
                                                      <!--  <input onclick="cargarObservaciones(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-4" class="sky-tab-content-4">
							<label class="pequeño" for="gestiones-4"><span><span style="padding: 0 14px;">Observaciones</span></span></label> -->
                                                        
                                                        <input onclick="cargarTipoAviso(); cargarDirecciones(personaActual.value); cargarTelegramas(personaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-5" class="sky-tab-content-5">
							<label class="pequeño" for="gestiones-5"><span><span style="padding: 0 14px;">{g_tab4}</span></span></label>
                                                        
                                                        <input onclick="cargarConvenios(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-6" class="sky-tab-content-6">
							<label class="pequeño" for="gestiones-6"><span><span style="padding: 0 14px;">{g_tab5}</span></span></label>
                                                        
                                                        <input onclick="cargarExoneraciones(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-7" class="sky-tab-content-7">
							<label class="pequeño" for="gestiones-7"><span><span style="padding: 0 14px;">{g_tab6}</span></span></label>
                                                        
                                                        <input onclick="cargarCuotas(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-8" class="sky-tab-content-8">
							<label class="pequeño" for="gestiones-8"><span><span style="padding: 0 14px;">{g_tab7}</span></span></label>
                                                        
                                                        <input disabled onclick="cargarSaldos(clienteActual.value, carteraActual.value, cuentaActual.value);" type="radio" name="sky-tabs-2" id="gestiones-9" class="sky-tab-content-9">
							<label class="pequeño" for="gestiones-9"><span><span style="padding: 0 14px;">Movimiento Saldos</span></span></label>

                                                        <ul>
             			 <li class="sky-tab-content-1">';

                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/gestiones/gestiones.php';
                $expediente .= $V_gestiones;
                        
                $expediente .= '</li>
                                
                                <li class="sky-tab-content-2">';
                
                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/abonos/abonos.php';
                $expediente .= $V_abonos;
                        
                $expediente .= '</li>
                                
                                <li class="sky-tab-content-3">';
                
                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/asignaciones/asignaciones.php';
                $expediente .= $V_asignaciones;
                        
                $expediente .= '</li>
                                

                                <li class="sky-tab-content-4">
                                 <div  style="height: 220px; width: 100%; overflow: auto;">
                                    
                                    <textarea id="observacionInterna" style="width: 100%; height: 100px;" class="editar" id="observacionInterna rows="10" readonly="" placeholder="Observaciones Internas"></textarea>
                                    <br><br>
                                    <textarea id="observacionExterna" style="width: 100%; height: 100px;" class="editar" id="observacionExterna rows="10" readonly="" placeholder="Observaciones Externas"></textarea>
                                 

                                 </div>

                                </li>
                                
                                 <li class="sky-tab-content-5">';
                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/Telegramas/telegramas.php';
                $expediente .= $V_telegramas;
                        
                $expediente .= '</li>

                                
                                <li class="sky-tab-content-6">';
                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/convenios/convenios.php';
                $expediente .= $V_convenios;
                        
                $expediente .= '</li>



                                <li class="sky-tab-content-7">';
                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/exoneraciones/exoneraciones.php';
                $expediente .= $V_exoneraciones;
                        
                $expediente .= '</li>
                                <li class="sky-tab-content-8">';
                
                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/cuotas/cuotas.php';
                $expediente .= $V_cuotas;
                        
                $expediente .= '</li>
                                <li class="sky-tab-content-9">';
                require_once $_SESSION['ROOT_PATH'].'/vista/expediente/contenedor/saldos/saldos.php';
                $expediente .= $V_saldos;
                    

                                $expediente .= '</li>



                        </ul>

               </div>
               
                         
                </form>
                
<form class="sky-form">
                    <hr align="left" noshade="noshade" size="2" width="100%" />         
                            <fieldset>
                                <section class="col col-8">
                                    
                                        
                                        <label class="textarea">

                                          
                                            <textarea tabindex="11" style="font-size: 14px; line-height: 1" id="observaciones" name = "observaciones" rows="4" readonly="readonly" placeholder="{g_obs}"></textarea>
                                        </label>
                                    
                                </section>
                                <section class="col col-6">
                                 <!--   <button title="Guardar" id="guardar" disabled onclick="if (tabActivo.value == \'gestiones\'){ guardarGestion(document.getElementById(\'cuentaActual\').value); } else if (tabActivo.value == \'abonos\') { guardarAbonos(cuentaActual.value); }" type="button"><img width="30px" heigth="30px" src="img/ico/guardar.ico"/></button> -->
                                 <!--   <button title="Agregar" id= "agregar" onclick= "agregarFila(tabActivo.value+\'-Tabla\')" disabled type="button"><img width="30px" heigth="30px" src="img/ico/agregar.ico"/></button> -->
                                 <!--   <button title="Modificar" id= "modificar" disabled onclick="if (tabActivo.value == \'gestiones\'){ modificarGestion(); } else if (tabActivo.value == \'abonos\'){ modificarAbono(); }" disabled type="button"><img width="30px" heigth="30px" src="img/ico/modificar.ico"/></button> -->
                                 <!--   <button title="Eliminar" id= "eliminar" disabled onclick="if (tabActivo.value == \'gestiones\'){ eliminarGestion(); } else if (tabActivo.value == \'abonos\'){ eliminarAbono(); }" disabled type="button"><img width="30px" heigth="30px" src="img/ico/eliminar.ico"/></button> -->
                                  <!--  <button title="Cancelar" id= "cancelar" onclick="if (tabActivo.value == \'gestiones\'){ cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value); } else if (tabActivo.value == \'abonos\'){ cargarAbonos(cuentaActual.value); }" hidden type="button"><img width="30px" heigth="30px" src="img/black-cancel.png"/></button> -->
                                   <!-- <button title="Limpiar Pantalla" onclick="location.reload();" type="button"><img width="30px" heigth="30px" src="img/ico/limpiar.ico"/></button> -->
                                  <!--  <button title="Reporte Gestiones" disabled id="btnReporteGestion" onclick="crearReporteGestiones(cuentaActual.value);" type="button"><img width="30px" heigth="30px" src="img/ico/pdf.ico"/></button> -->
                                    <input hidden name="cuentaActual" id="cuentaActual" type="text" value="">
                                    <input hidden id="clienteActual" type="text" value="">
                                    <input hidden id="carteraActual" type="text" value="">
                                    <input hidden id="usuarioGestorActual" type="text" value="">
                                    <input hidden name="tabActivo" id="tabActivo" type="text" value="gestiones">
                                    <input hidden name="tipoCuentaActual" id="tipoCuentaActual" type="text" value="">
                                    <input hidden name="personaActual" id="personaActual" type="text" value="">
                                    <input hidden name="saldoActualCuenta" id="saldoActualCuenta" type="text" value=""/>
                                    <input hidden id="AgregarActivo" type="text" value="0"/>

                                </section>
                                
                                <div id = "guardarContenedor"></div>
                            </fieldset>
                            
                        </form>
                        

                        <div id="flotante"></div>
                        
</div>
<div id="verError"></div>
</body>

<link rel="stylesheet" href="css/propio.css">

<style>


.dataTables_filter {

display: none;

}


</style>

<script> cambiarTitulo("Expediente"); </script>

';
 
        
$a_js = array(
            '../controlador/c_deudor.js',
            '../controlador/c_cuenta.js',
            '../controlador/c_gestiones.js',
            '../controlador/c_abonos.js',
            '../controlador/c_cuotas.js',
            '../controlador/c_exoneraciones.js',
            '../controlador/c_convenios.js',
            '../controlador/c_observaciones.js',
            '../controlador/c_telegramas.js',
            '../controlador/c_asignaciones.js',
            '../controlador/c_email.js',
            '../controlador/expediente/saldos/c_saldos.js'
     );
 
     foreach ($a_js as $key => $value) {
         
         
         $js_a = file_get_contents($value);
          $expediente .= '<script>'.$js_a.'</script>';
    
}
 
 /*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */
                 
                 

?>
