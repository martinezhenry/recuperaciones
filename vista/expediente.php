<?php
/*session_start();
echo $_SESSION['idPersona'];
*/
require_once 'direcciones.php';
// Arreglo de los idPersona que se reciben de la pantalla Agenda
//$arrCuentas = array(23000002, 21389672, 21389669, 5812);
$filtro = "";
if (isset($_POST['checkcuentas']) || isset($_POST['checkPortafolio']) || isset($_GET['smsper'])){
    
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


<div style="background: rgba(255,255,255,.9);" class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-slide-top sky-tabs-response-to-stack">
							<input type="radio" name="sky-tabs-1" checked id="sky-tab1-1" class="sky-tab-content-1">
							<label class="pequeño" for="sky-tab1-1"><span><span>Datos Del Deudor</span></span></label>
							
							<input disabled onclick="v_llamada_cargarTabla();" type="radio" name="sky-tabs-1" id="sky-tab-1-2" class="sky-tab-content-2">
							<label class="pequeño" for="sky-tab-1-2"><span><span>Llamadas</span></span></label>
							
							<input disabled onclick="v_direccion_cargarTabla();" type="radio" name="sky-tabs-1" id="sky-tab1-3" class="sky-tab-content-3">
							<label class="pequeño" for="sky-tab1-3"><span><span>Direcciones</span></span></label>
							
							<ul>
								<li style="height: 50px; background: inherit;" class="sky-tab-content-1">
									<div class="typography">
										
                                                                <form class="sky-form" style ="box-shadow: 0 0 0px;"/>                                                                                
                                                               
                                                                                <fieldset style="padding: 5px 30px 11px; background: initial;">
                                    
                                    
                                    
                                    <div class="row">
                                        <section style="margin-bottom: -5px;" class="col col-0">

                                            <div style="margin-right: 10px;"> 

                                               
                                                <label class="radio" style="margin-right: 17px; line-height: 40px; margin-right: -27px;"><input type="radio" name="radio-inline" checked=""><i style="top: 12px;"></i><span id="nacionalidadDeudor">V</span></label>
                                            </div>
                                        </section>
                                        <section class="col col-3">
                                          
                                            <label class="input" >
                                                <input type="text" placeholder="Cedula/RIF" onkeypress="checkKey(this.value, event);" maxlength="8" id="cedulaDeudor" style="width: 83%;" name="cedulaDeudor" /></label>
                                            <button style="margin: -38px 0 0 -1px; padding: 0 0px; height: 34px;" type="button" onclick="consultarDeudor(cedulaDeudor.value);" name="campo" class="button"><img src="icons/icono_lupa.gif"></button>



                                        </section>
                                        <section class="col col-5">

                                            
                                            <label class="input" >

                                                <input type="text" id="nombreDeudor" placeholder="Nombre" disabled/> </label>


                                        </section>
                                        <section class="col col-3">

                                            <label class="input" >
                                                <input type="text" placeholder="Fecha de Nacimiento" id="fechaNacDeudor"  disabled  />
                                            </label>




                                        </section>


                                   </div>
                                          
                                    
                                          
                                    </fieldset>
                           
                                        </form>


									</div>
								</li>
								<li style="height: 200px; background: inherit;" class="sky-tab-content-2">
									    
                                                                '.$llamadas.'
                                                                    
								</li>
								<li style="height: 200px; background: inherit;" class="sky-tab-content-3">
                                                                '.$direcciones.'</li>
							</ul>
                                                       
						</div>
                                                

<form class="sky-form">
                         <hr align="left" noshade="noshade" size="2" width="100%" />
                         <div style="overflow: auto;">
                      <fieldset style= "padding: 10px 30px 5px;">
                        <section>	
                                       
                         
                            <div style="width: 981px; height: 220px; overflow: auto; outline: none;" id="cuentas">
                                
                                
            <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="cuentas-Tabla">
                        <thead>
                            
                            <tr>
                                <th align = \'center\'><b><input type=\'checkbox\' name=\'checkTodos\' /> Check</b></th>
                                <th><b>Cliente</b></th>
                                <th><b>Cartera</b></th>
                                <th><b>Tipo de Credito</b></th>
                                <th><b>Cuenta</b></th>
                                <th><b>Asesor</b></th>
                                <th><b>Saldo Actual</b></th>
                                <th><b>Fecha de Ingreso</b></th>
                         
                                

                                
                            </tr>
                        </thead>
                        
                        <tbody>
                            
                            
                             <tr class="gradeA" id="1">

                                <td class="center"><input type="checkbox" name="check1" value="1"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td ondblclick="crearText(document.getElementById(\'cuentaText\'))"><label id="cuentaText">&nbsp</label></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align = \'center\'>&nbsp;</td>
                                

                            </tr>
                            
                            
                            <tr class="gradeA" id="1">

                               <td class="center"><input type="checkbox" name="check1" value="1"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align = \'center\'>&nbsp;</td>
                                

                            </tr>
                            
                            
                            <tr class="gradeA" id="1">

                                <td class="center"><input type="checkbox" name="check1" value="1"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align = \'center\'>&nbsp;</td>
                                

                            </tr>
                            
                           
                            </tbody>
                        
                            </table>

	<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
				<tfoot id="piecuentas-Tablas">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
                                                <th></th>
                                                <th></th>
					</tr>
				</tfoot>
			</table>

                                
                                
                            </div>
                        
                        </section>
                        
                        <section>
                        
<div style="float: left;">
<img style="cursor: pointer; margin-bottom: -4;" src="img/iconoFlecha-Izquierda.PNG" WIDTH=20 HEIGHT=20 onclick="javascript: if (document.getElementById(\'inicioArr\').value > \'1\'){ document.getElementById(\'inicioArr\').value = parseInt(document.getElementById(\'inicioArr\').value)-1; document.getElementById(\'envio\').click(); }">
<button hidden type="button" id="envio" onclick="ArrCuentas(document.getElementById(\'arr_\'+document.getElementById(\'inicioArr\').value).value, \''.$filtro.'\');">Enviar</button>
<input autofocus onchange="javascript: if (parseInt(this.value) > parseInt(finArr.value)){ alert(\'Numero Maximo permitido \'+finArr.value); this.value = finArr.value; } else document.getElementById(\'envio\').click();" id="inicioArr" type="number" min="1" max="'; 
(count($arrCuentas) == 0) ? $expediente .= count($arrCuentas)+1 : $expediente .= count($arrCuentas);
$expediente .= '" value="1"/> de 
<input readonly="readonly" id="finArr" type="number" value="';

(count($arrCuentas) == 0) ? $expediente .= count($arrCuentas)+1 : $expediente .= count($arrCuentas);

//if (count($arrCuentas) == 0) $expediente .= count($arrCuentas)+1; else $expediente .= count($arrCuentas); 


$expediente .='" min="1" max="115"/>
<img style="cursor: pointer; margin-bottom: -4;" src="img/iconoFlecha-Derecha.PNG" WIDTH=20 HEIGHT=20 onclick="javascript: sumarUno();">    
</div>

                        
                            <div class="row" align="right" id="estadisticas">
                            
                            <label>Monto Vencido:</label>
                            <input style=\'text-align:right;\' disabled="" type="text" id="montoVencido" name="montoVencido" value="0">
                            <label>Intereses:</label>
                            <input style=\'text-align:right;\' disabled="" type="text" id = "intereses" name="intereses" value="0">
                            <label>Monto Total:</label>
                            <input style=\'text-align:right;\' disabled="" type="text" id="montoTotal" name="montoTotal" value="0">
                            
                            
                        </div>
                        </section>
                     </fieldset>
                     </div>
                      <hr align="left" noshade="noshade" size="2" width="100%" />
                        </form>
                        



<div style="background: rgba(255,255,255,.9);" class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-slide-top sky-tabs-response-to-stack">
							
                                                        <input disabled onclick="cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value, saldoActualCuenta.value);" type="radio" name="sky-tabs-2" checked id="gestiones-1" class="sky-tab-content-1">
							<label class="pequeño" for="gestiones-1"><span><span style="padding: 0 14px;">Gestiones</span></span></label>
							
							<input disabled onclick="cargarAbonos(cuentaActual.value);" type="radio" name="sky-tabs-2" id="gestiones-2" class="sky-tab-content-2">
							<label class="pequeño" for="gestiones-2"><span><span style="padding: 0 14px;">Abonos</span></span></label>
							
							<input onclick="cargarAsignaciones(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-3" class="sky-tab-content-3">
							<label class="pequeño" for="gestiones-3"><span><span style="padding: 0 14px;">Asignaciones</span></span></label>
                                                        
                                                      <!--  <input onclick="cargarObservaciones(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-4" class="sky-tab-content-4">
							<label class="pequeño" for="gestiones-4"><span><span style="padding: 0 14px;">Observaciones</span></span></label> -->
                                                        
                                                        <input onclick="cargarTipoAviso(); cargarDirecciones(personaActual.value); cargarTelegramas(personaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-5" class="sky-tab-content-5">
							<label class="pequeño" for="gestiones-5"><span><span style="padding: 0 14px;">Telegramas</span></span></label>
                                                        
                                                        <input onclick="cargarConvenios(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-6" class="sky-tab-content-6">
							<label class="pequeño" for="gestiones-6"><span><span style="padding: 0 14px;">Convenios</span></span></label>
                                                        
                                                        <input onclick="cargarExoneraciones(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-7" class="sky-tab-content-7">
							<label class="pequeño" for="gestiones-7"><span><span style="padding: 0 14px;">Exoneraciones</span></span></label>
                                                        
                                                        <input onclick="cargarCuotas(cuentaActual.value);" disabled type="radio" name="sky-tabs-2" id="gestiones-8" class="sky-tab-content-8">
							<label class="pequeño" for="gestiones-8"><span><span style="padding: 0 14px;">Cuotas</span></span></label>
                                                        
                                                       <!-- <input onclick="" disabled type="radio" name="sky-tabs-2" id="gestiones-9" class="sky-tab-content-9">
							<label class="pequeño" for="gestiones-9"><span><span style="padding: 0 14px;">Movimiento Saldos</span></span></label> -->

                                                        <ul>
								<li class="sky-tab-content-1">

                       
                           
                           
                             <div style="width: 981px; height: 220px; overflow: auto; outline: none;" id="gestiones">
                      

     <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="gestiones-Tabla" width="100%">
	<thead>
		<tr>
			<th>Check</th>
			<th>Fecha de Gestion</th>
			<th>Tipo de Gestion</th>
			<th>Area</th>
			<th>Telefono</th>
			<th>Nombre</th>
                        <th>Apellido</th>
                        <th>Parentesco</th>
                        <th>Observaciones</th>
                        <th>Fecha Proxima Gestion</th>
                        <th>Hora Proxima Gestion</th>
                        <th>Fecha de Promesa</th>
                        <th>Monto de Promesa</th>
		</tr>
	</thead>
	<tbody>
		<tr class="gradeA" id="1">
			<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			
		</tr>
		<tr class="gradeA" id="2">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
                

                	<tr class="gradeA" id="1">
			<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			
		</tr>
		<tr class="gradeA" id="2">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	
                	<tr class="gradeA" id="2">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

	
            </tbody>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
				<tfoot id="piegestiones-Tabla">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
                                                <th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
                                                <th></th>
					</tr>
				</tfoot>
			</table>
                                
              
                             </div>
                        
      
                    <button title="Reporte Gestiones" disabled id="btnReporteGestion" onclick="crearReporteGestiones(cuentaActual.value);" type="button"><img width="35px" heigth="35px" src="img/ico/pdf.ico"/></button>
                                </li>
                                
                                <li class="sky-tab-content-2">
                            <div style="height: 220px; width: 100%; overflow: auto;" id = "abonos">
                                <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="abonos-Tabla" width="100%">
	<thead>
		<tr>
			<th>Check</th>
			<th>Numero Abono</th>
			<th>Nombre Asesor</th>
			<th>Fecha Abono</th>
			<th>Monto Abonado</th>
			<th>Cuota</th>
                        <th>Forma de Pago</th>
                        <th>Fecha de Ingreso</th>
                        <th>Banco</th>
                        <th>Observacion</th>
                        <th>Status</th>
                   
		</tr>
	</thead>
	<tbody>
		<tr class="gradeA" id="1">
			<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
             
		</tr>
		<tr class="gradeA" id="2">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
               
		</tr>
                

                	<tr class="gradeA" id="3">
			<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                   
			
		</tr>
		<tr class="gradeA" id="4">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
              
		</tr>
	
                	<tr class="gradeA" id="5">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                  
		</tr>

	
            </tbody>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
				<tfoot id="pieabonos-Tabla">
					<tr>
                                                <th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
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
			</table>

                                </div>

                                </li>
                                
                                <li class="sky-tab-content-3">
                                <div  style="height: 220px; width: 100%; overflow: auto;">
                               <div style= "float: left; text-align: left;">
                                  
                               <table style="font-size: 11px;" cellspacing="10">
                               <tr align="right">
                               <td>
            <label>Cliente:</label> <input id="clienteAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Cartera:</label> <input id="carteraAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Tipo de Cuenta:</label> <input id="tipoCuentaAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Ciudad:</label> <input id="ciudadAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Saldo Inicial:</label> <input id="saldoInicialAsignacion" type="text" disabled class="editar"/><br>
                                  
            <label>Saldo Anterior:</label> <input id="saldoAnteriorAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Saldo Actual:</label> <input id="saldoActualAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha de Vencimiento:</label> <input id="fechaVencimientoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Area de Devolucion:</label> <input id="areaDevolucionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha de Devolucion:</label> <input id="fechaDevolucionAsignacion" type="text" disabled class="editar"/><br>
                                  </td>
                                  <td>
            <label>Devolucion:</label> <input id="devolucionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Usuario Asesor:</label> <input id="usuarioGestorAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha Asig. al Asesor:</label> <input id="fechaAsignadaAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha Primera Asig.:</label> <input id="fechaPrimeraAsignacionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Estatus de Cuenta:</label> <input id="statusCuentaAsignacion" type="text" disabled class="editar"/><br>
                                  

            <label>Tipo de Credito:</label> <input id="tipoCreditoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Sticker:</label> <input id="stickerAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Gestionada:</label> <input id="gestionadaAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Observacion Interna:</label> <input id="observacionInternaAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Observacion Externa:</label> <input id="observacionExternaAsignacion" type="text" disabled class="editar"/><br>
                                  </td>
                                  <td>
            <label>Fecha de Ingreso:</label> <input id="fechaIngresoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha de Ult. Gest:</label> <input id="fechaUltimaGestionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Tipo Cuenta Cliente:</label> <input id="tipoCuentaClienteAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Tipo Asignacion:</label> <input id="tipoAsignacionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha Asignacion:</label> <input id="fechaAsignacionAsignacion" type="text" disabled class="editar"/><br>
                                  

            <label>Intereses de Mora:</label> <input id="interesesMoraAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Monto Total:</label> <input id="montoTotalAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Capital Vencido:</label> <input id="capitalVencidoAsignacion" type="text" disabled class="editar"/><br>
                                  
                                  </td>
                                  <td style="width: 110px">
                                    <label>Otros:</label>
                                    <textarea rows="11" class="editar"></textarea>

                                  </td>
                                  </tr>
                                  </table>
                                  </div>
                                  

                                 <!--  <label>Fecha de Ingreso: </label> <input type="text" class="editar"/>
                                   
                                   
                                    <table>
                                    <tr><td>
                                    
                                    <table CELLSPACING="5" style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                    
                                        <tr>
                                        <td align="right">
                                        <label>Tipo de Cuenta:</label>
                                        </td>
                                        <td>
                                        <input size="8px" class="editar" type="text" /> <input size="38%" class="editar" type="text" /></td>
                                        </td>
                                        </tr>
                                        
                                        <tr>
                                        <td align="right">
                                        <label>Saldo Inicial:</label>
                                        </td>
                                        <td>
                                        <input class="editar" type="text"/> <----- <input class="editar" type="text"/>
                                        </td>
                                        </tr>

                                        <tr>
                                        <td align="right">
                                        <label>Saldo Anterior:</label>
                                        </td>
                                        <td>
                                        <input class="editar" type="text" /> <----- <input class="editar" type="text" />
                                        </td>
                                        </tr>
                                        
                                        <tr>
                                        <td align="right">
                                        <label>Saldo Actual:</label>
                                        </td>
                                        <td>
                                        <input class="editar" type="text" /> <----- <input class="editar" type="text" />
                                        </td>
                                        </tr>
                                  
                                    </table>
                                    </td><td>


                                         <table CELLSPACING="5" style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                    
                                        <tr>
                                        <td align="right">
                                        <label>Comision:</label> <input size="10px" type="text" class="editar"/>
                                        </td>
                                    
                                        </tr>
                                        
                                        <tr>
                                        <td>
                                        <button class="botonEditar" type="button">Cambiar Saldo Inicial</button>
                                        </td>
                                
                                        </tr>

                                        <tr>
                                        <td>
                                        <button class="botonEditar" type="button">Cambiar Saldo Anterior</button>
                                        </td>
                                
                                        </tr>
                                        
                                        <tr>
                                        <td>
                                        <button class="botonEditar" type="button">Cambiar Saldo Actual</button>
                                        </td>
                             
                                        </tr>
                                  
                                    </table>



                                    </td>
                                    <td>
                                    
                                    <table CELLSPACING="5" style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                    
                                    <tr align="right">
                                    <td>
                                        <label>Saldo Actual:</label> <input class="editar" type="text" />
                                    </td>
                                    </tr>
                                    
                                    <tr align="right">
                                    <td>
                                        <label>Intereses_o_t:</label> <input class="editar" type="text" />
                                    </td>
                                    </tr>
                                    
                                    <tr align="right">
                                    <td>
                                        <label>Intereses_Mora:</label> <input class="editar" type="text" />
                                    </td>
                                    </tr>
                                    
                                    <tr align="right">
                                    <td>
                                        <label>Seguro:</label> <input class="editar" type="text" />
                                    </td>
                                    </tr>
                                    
                                    <tr align="right">
                                    <td>
                                        <label>Capital Vencido:</label> <input class="editar" type="text" />
                                    </td>
                                    </tr>
                                    
                                    <tr align="right">
                                    <td>
                                        <label>Monto Total:</label> <input class="editar" type="text" />
                                    </td>
                                    </tr>

                                    </table

                                    </td>



                                    </tr></table> -->
                             

                                </div>
                                </li>
                                

                                <li class="sky-tab-content-4">
                                 <div  style="height: 220px; width: 100%; overflow: auto;">
                                    
                                    <textarea id="observacionInterna" style="width: 100%; height: 100px;" class="editar" id="observacionInterna rows="10" readonly="" placeholder="Observaciones Internas"></textarea>
                                    <br><br>
                                    <textarea id="observacionExterna" style="width: 100%; height: 100px;" class="editar" id="observacionExterna rows="10" readonly="" placeholder="Observaciones Externas"></textarea>
                                 

                                 </div>

                                </li>
                                
                                 <li class="sky-tab-content-5">
                                 <div  style="height: 220px; width: 100%; overflow: auto;">
                                 
                                 <label>Tipo de Aviso</label>
                                 <div style="display: flex;">
                                 <div>
                                 <div id="contenedor-selectTipoAviso">
                                 <select class="selectEditar">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                 </select>
                                 </div>
                                 <label>Direccion</label>
                                 <div id="contenedor-selectDireccion">
                                 <select class="selectEditar">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                 </select>
                                 </div>
                                 </div>&nbsp;&nbsp;&nbsp;
                                 <textarea style="width: 60%; height: 50px;" class="editar" id="tipoAvisoTexto" rows="2" readonly="" placeholder="Tipo Aviso"></textarea>&nbsp;
                                 <button onclick="enviarTelegrama(cuentaActual.value);" type="button" class="botonEditar" style="width: 100px;">Enviar</button>
                                 </div>
                                 <br>
                                 
                                 <div id="contenedor-tablaTelegramas">

                                  <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="telegramas-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>Impreso</th>
                                                        <th>Nº Telegrama</th>
                                                        <th>Fecha de Envio</th>
                                                        <th>Tipo de Aviso</th>
                                                        

                                                </tr>
                                        </thead>
                                        <tbody>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                 <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                               
		
                  
                                        </tr>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        

                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>

	
                                </tbody>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
				<tfoot id="pietelegramas-Tabla">
					<tr>
                                                <th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
				
					</tr>
				</tfoot>
			</table>
                            </div>
                                 </div>

                                </li>
                                
                                  <li class="sky-tab-content-6">
                                 <div  style="height: 220px; width: 100%; overflow: auto;">
                                 
                                 <table width="100%" style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                 
                                    <tr>
                                        
                                        <td>
                                        
                                            <table align="center" style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                                
                                                <thead>
                                                
                                                    <th>Saldo Actual</th>
                                                    <th>Saldo Castigado</th>
                                                    <th>% Inicial</th>
                                                    <th>Monto Inicial</th>
                                                   <!-- <th>Saldo Final</th> -->
                                                    <th>Cuotas</th>
                                                    <th>Monto por Cuotas</th>

                                                </thead>
                                                <tbody>
                                                
                                                    <td><input disabled id="saldoActualConvenio" size="12px" class="editar" type="text"/></td>
                                                    <td><input size="12px" class="editar" value="0" type="text"/></td>
                                                    <td><input id="porcentajeInicialConvenio" onkeyup="calcularMontoInicial(); calcularMontoPorCuotas();" size="12px" placeholder="0" class="editar" type="text"/></td>
                                                    <td><input disabled id="montoInicialConvenio" size="12px" value="0" class="editar" type="text"/></td>
                                                 <!--   <td><input size="12px" value="0" class="editar" type="text"/></td> -->
                                                    <td><input id="cuotasConvenio" onkeyup="calcularMontoPorCuotas();" size="12px" value="1" class="editar" type="text"/></td>
                                                    <td><input disabled id="montoPorCuotasConvenio" size="12px" value="0" class="editar" type="text"/></td>

                                                </tbody>
                                            
                                            </table>

                                        </td>
                                        


                                        <td VALIGN="BOTTOM">

                                            <table style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                            
                                                <thead>
                                                    
                                                    <th>Fecha 1ra Cuota</th>
                                                    
                                                </thead>
                                                <tbody>

                                                    <td><input id="fechaPrimeraCuotasConvenio" value="'.date("Y-m-d").'" class="editar" size="12px" type="date" /></td>
                                                    <td><button onclick="guardarConvenio(cuentaActual.value);" class="botonEditar" style="width: 60px;" type="button">Generar</button>
                                                        <button hidden class="botonEditar" style="width: 60px;" id="btnAprobarConvenio" type="button" onclick="aprobarConvenio(\'check-convenios\')">Aprobar</button>
                                                    </td>
                                                </tbody>

                                            </table>

                                        </td>

                                    </tr>
                                    <tr>
                                    
                                        <td colspan="3">
                                        <div id="contenedor-tablaConvenios" style="height: 100%; width: 100%; overflow: auto;">
                                        <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="convenios-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>Checks</th>
                                                        <th>Monto Actual</th>
                                                        <th>% Inicial</th>
                                                        <th>Monto de Inicial</th>
                                                        <th>Cuotas</th>
                                                        <th>Monto por Cuota</th>
                                                        <th>Fecha Convenio</th>
                                                        
                                                        

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
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
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
			</table>
                        </div>

                                        </td>
                                  

                                            </tr>
                                            
                                                
                                                
                                                
                                            </table>

                                 

                                 </div>

                                </li>

                                  <li class="sky-tab-content-7">
                                 <div  style="height: 220px; width: 100%; overflow: auto;">
                                 
                                 <table width="100%" border="2" style="font-size: 13px;">
                                 
                                         <tr>
                                    <td>
                                    <table style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                    <tr align="center">
                                        <td><label>Saldo Actual:</label></td>
                                        <td><label>% a Exonerar:</label></td>
                                        <td><label>Monto a Exonerar:</label></td>
                                        <td><label>Monto a Cancelar:</label></td>
                                        <td><label>Cancelación:</label></td>
                                        
                                    </tr><tr align="center">
                                         <td><input id="saldoAExonerar" size="12px" type="text" disabled class="editar"/></td>
                                         <td><input id="%AExonerar" size="12px" type="text" maxlength="3" onkeyup="calcularExoneracion();"  class="editar"/></td>
                                         <td><input id="montoExonerado" size="12px" type="text" disabled  class="editar"/></td>
                                         <td><input id="MontoACancelar" size="12px" type="text" disabled class="editar"/></td>
                                        
                                        <td><select id="tipoPago" onchange="mostrarOcultarFracciones(this.selectedIndex);" style="width: 100px;" class="selectEditar">
                                            <option value="0">Decontada</option>
                                            <option value="1">Fracionada</option>
                                        </select></td>
                                        <td><div style="display: none;" id="fracciones">
                                        <label>Nº Cuotas:</label> <input size="8px" value="1" id="cuotasExoneracion" type="text" class="editar"/>
                                        <label>Fecha Cuota:</label> <input value="'.date("Y-m-d").'" style="width: 130px;" id="fechaCuotasExoneracion" type="date" class="editar"/>
                                        </div>
                                       
                                        </td>
                                        <td><button class="botonEditar" style="width: 60px;" type="button" onclick="generarExoneracion(cuentaActual.value);">Generar</button>
                                        <button hidden class="botonEditar" style="width: 60px;" id="btnAprobarExoneracion" type="button" onclick="aprobarExoneracion(\'check-exoneraciones\')">Aprobar</button>
                                        </td>
                                        
                                    
                                    </table>
                                    </td>
                                    </tr>

                                 <tr><td>
                                 <div id="exoneraciones-contenedor">
                                        <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="exoneraciones-Tabla" width="100%">
                                            <thead>
                                                    <tr>
                                                            <th>Checks</th>
                                                            <th>Saldo Inicial</th>
                                                            <th>Monto Castigado</th>
                                                            <th>Monto a Exonerar</th>
                                                            <th>Saldo Final</th>
                                                            <th>Fecha</th>
                                                            <th>Motivo de Exoneracion</th>
                                                            <th>Estado</th>


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
                                    </table>
                                    </div>
                                    </td>
                                    </tr>
                            
                                    </table>
                                        
                                    
                                    <br>
                                   <!-- <div align="right">
                                        <button type="button" class="botonEditar" style="width: 60px;">Eliminar</button>
                                        <button type="button" class="botonEditar" style="width: 60px;">Reporte</button>
                                    </div> -->
                                


                                 </div>

                                </li>
                                
                                  <li class="sky-tab-content-8">
                                 <div  style="height: 220px; width: 100%; overflow: auto;">
                                 
                                 <div align="right">
                                 <label>Mostrar:</label>
                                 <select class="selectEditar">

                                    <option>Todos</option>
                                    <option>1</option>
                                    <option>2</option>

                                 </select>
                                
                                
                                 
                                    <label>Cuotas Mostradas:</label> <input type="text" class="editar" style="width: 40px;"/>
                                    <label>Monto Total:</label> <input type="text" class="editar" style="width: 40px;"/>

                                 
                                 </div>
                                 
                                 <div id="cuotas-contenedor">
                                 
                                    <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="cuotas-Tabla" width="100%">
                                            <thead>
                                                    <tr>
                                                            <th>Cuota</th>
                                                            <th>Fecha Vencimiento</th>
                                                            <th>Dias de Morosidad</th>
                                                            <th>Monto Capital</th>
                                                            <th>Monto Interes</th>
                                                            <th>Monto Interes Mora</th>
                                                            <th>Monto Otros</th>
                                                            <th>Monto Cuota</th>
                                                            <th>Status</th>
                                                            <th>Pago Abono</th>
                                                            <th>Fecha Asignacion</th>
                                                            <th>Ultima Actualizacion</th>


                                                    </tr>
                                            </thead>
                                            <tbody>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>

                                            <tr class="gradeA" id="5">


                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>

                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
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
                                            <tfoot id="piecuotas-Tabla">
                                                    <tr>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
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
                                    </table>
                                    
                                    </div>

                                 </div>

                                </li>

                                  <li class="sky-tab-content-9">
                                 <div  style="height: 220px; width: 100%; overflow: auto;">

                                    
                                      <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="saldos-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>Checks</th>
                                                        <th>Fecha</th>
                                                        <th>Saldo Anterior</th>
                                                        <th>Diferencia</th>
                                                        <th>Saldo Actual</th>
                                                        

                                                </tr>
                                        </thead>
                                        <tbody>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                 <td class="center"><input type="checkbox" name="check-saldos" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                               <td class="center"><input type="checkbox" name="check-saldos" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                               
		
                  
                                        </tr>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                               <td class="center"><input type="checkbox" name="check-saldos" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        

                                                  <td class="center"><input type="checkbox" name="check-saldos" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        
                                                
                                                <td class="center"><input type="checkbox" name="check-saldos" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
		
                  
                                        </tr>

	
                                </tbody>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
				<tfoot id="piesaldos-Tabla">
					<tr>
                                                <th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
                                                <th id="selectz"></th>
                                                
				
					</tr>
				</tfoot>
			</table>


                                 </div>

                                </li>



                        </ul>

               </div>
               
                         
                </form>
                
<form class="sky-form">
                    <hr align="left" noshade="noshade" size="2" width="100%" />         
                            <fieldset>
                                <section class="col col-8">
                                    
                                        
                                        <label class="textarea">

                                          
                                            <textarea id="observaciones" name = "observaciones" rows="2" readonly="readonly" placeholder="Observaciones"></textarea>
                                        </label>
                                    
                                </section>
                                <section class="col col-6">
                                    <button title="Guardar" id="guardar" disabled onclick="if (tabActivo.value == \'gestiones\'){ guardarGestion(cuentaActual.value); } else if (tabActivo.value == \'abonos\') { guardarAbonos(cuentaActual.value); }" type="button"><img width="35px" heigth="35px" src="img/ico/guardar.ico"/></button>
                                    <button title="Agregar" id= "agregar" onclick= "agregarFila(tabActivo.value+\'-Tabla\')" disabled type="button"><img width="35px" heigth="35px" src="img/ico/agregar.ico"/></button>
                                    <button title="Eliminar" id= "eliminar" disabled onclick="if (tabActivo.value == \'gestiones\'){ eliminarGestion(); } else if (tabActivo.value == \'abonos\'){ eliminarAbono(); }" disabled type="button"><img width="35px" heigth="35px" src="img/ico/eliminar.ico"/></button>
                                    <button title="Cancelar" id= "cancelar" onclick="if (tabActivo.value == \'gestiones\'){ cargarGestiones(cuentaActual.value, clienteActual.value, carteraActual.value, usuarioGestorActual.value, tipoCuentaActual.value); } else if (tabActivo.value == \'abonos\'){ cargarAbonos(cuentaActual.value); }" hidden type="button"><img width="35px" heigth="35px" src="img/cancelar.ico"/></button>
                                    <button title="Limpiar Pantalla" onclick="location.reload();" type="button"><img width="35px" heigth="35px" src="img/ico/limpiar.ico"/></button>
                                    <input hidden name="cuentaActual" id="cuentaActual" type="text" value="">
                                    <input hidden id="clienteActual" type="text" value="">
                                    <input hidden id="carteraActual" type="text" value="">
                                    <input hidden id="usuarioGestorActual" type="text" value="">
                                    <input hidden name="tabActivo" id="tabActivo" type="text" value="">
                                    <input hidden name="tipoCuentaActual" id="tipoCuentaActual" type="text" value="">
                                    <input hidden name="personaActual" id="personaActual" type="text" value="">
                                    <input hidden name="saldoActualCuenta" id="saldoActualCuenta" type="text" value=""/>

                                </section>
                                
                                <div id = "guardarContenedor"></div>
                            </fieldset>
                            
                        </form>
                        

                        <div id="flotante"></div>
</div>
</body>

<link rel="stylesheet" href="css/propio.css">

<style>


.dataTables_filter {

display: none;

}


</style>

<script src="../controlador/c_deudor.js"></script>

<script src="../controlador/c_gestiones.js"></script>
<script src="../controlador/c_abonos.js"></script>
<script src="../controlador/c_cuotas.js"></script>
<script src="../controlador/c_exoneraciones.js"></script>
<script src="../controlador/c_convenios.js"></script>
<script src="../controlador/c_observaciones.js"></script>
<script src="../controlador/c_telegramas.js"></script>
<script src="../controlador/c_asignaciones.js"></script>
<script src="../controlador/c_cuenta.js"></script>
  ';
    

 
 /*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */
                 
                 

?>
