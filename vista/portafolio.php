<?php 

if ( ($_SESSION['USER'][0] == "T") or ($_SESSION['USER'][0] == "C")) { $ind = 1; } else { $ind = 0; } 

$portafolio = '
    

			

<body class="" onload="llamaTodo(\'asignaciones-Tabla\'); cargarAsignaciones(\'NULL\', \''.$_SESSION['USER'].'\', '.$ind.', \'NULL\');  cargarAreas();">
<div id="pantalla_3">
<form id="form_envio" name="form_envio" method="post" action="?pantalla='.md5('expediente').'" class="sky-form" >
                        <input hidden id ="filtroUsado" name="filtroUsado" value="NULL" type="text"/>
                        <input hidden type="text" id="usuarioActualSeleccionado" name="usuarioActualSeleccionado" value=""/>
                        <input hidden id ="statusUsado" name="statusUsado" value="NULL" type="text"/>
                        <input hidden id ="limiteInicio" name="limiteInicio" type="text"/>
                        <input hidden id ="limiteFin" name="limiteFin" type="text"/>
                        <input hidden id ="clienteActual" type="text"/>
                       
                     <div style="overflow: auto; width: 100%;">
                      <fieldset>
                      

                            			<table>
						<tr>
							
							<td id="g">{tab1}:</td>
							<td><select style="width: 220px;"  id="ger" disabled > <option>'. $a->get_gerente('NOMBRE').' ('.$a->get_gerente('ID').')</option></select></td>
                                                        <td id="o">{tab2}:</td>
							<td><select onchange="cargarSupervisores(); reiniciaLimites(); cargarAsignaciones(\'NULL\', this.value, \'0\', \'NULL\'); reiniciaSelects([\'filtroExoneracion\', \'filtroConvenio\', \'filtroArea\']); checkArea.disabled = false; activarDesactivarSelects([\'selcCliente\'], 0);" style="width: 220px;"  id="coor" > <option>Coodinador</option></select></td>
							<td id="s">{tab3}:</td>
							<td><select onchange="cargarAsesores(); reiniciaLimites(); cargarAsignaciones(\'NULL\', this.value, \'0\', \'NULL\'); reiniciaSelects([\'filtroExoneracion\', \'filtroConvenio\', \'filtroArea\']); checkArea.disabled = false; activarDesactivarSelects([\'selcCliente\'], 0);"  style="width: 220px;" id="sup" > <option>Supervisor</option></select></td>
							<td id="a">{tab4}:</td>
							<td><select onchange="reiniciaLimites(); cargarAsignaciones(\'NULL\', this.value, this.selectedIndex, \'NULL\'); reiniciaSelects([\'filtroExoneracion\', \'filtroConvenio\', \'filtroArea\']); activarDesactivarSelects([\'selcCliente\'], 0);"  style="width: 220px;" id="ges"> <option>Asesor</option></select></td>
						</tr>

					</table>
                                        
                               <div style="height: 440px; width: 95%;">
                               <fieldset class="recuadro" style="width: 100%; border:1px solid black; background: initial;"><legend>{tab5}</legend>
                               
                                <div style="display: -webkit-inline-box;">
                                <table style="font-size: 11px;" width="700px" border="0">
                                <tr>
                                <td align="right" width="10%">
                                <label>{tab6}</label>
                                </td>
                                <td width="20%">
                                <div style="margin-right: 10;">
                                    
                                    <select style="width : 257px;" disabled id="filtroExoneracion" onchange="reiniciaLimites(); cargarAsignaciones(\'NULL\', usuarioActualSeleccionado.value, document.getElementById(\'ges\').selectedIndex, \'exoneracion\', this.value); reiniciaSelects([\'filtroConvenio\', \'filtroArea\']); if (this.selectedIndex == 0) { filtroUsado.value = \'NULL\' } else { filtroUsado.value = \'exoneracion\' } ">
                                        <option value="-1">{tab7}</option>
                                        <option value="0">{tab8}</option>
                                        <option value ="P">{tab9}</option>
                                        <option value ="A">{tab10}</option>
                                        <option value ="I">{tab11}</option>
                                    </select>
                                </div>
                                </td>
                                <td align="right" width="10%">
                                <label>{tab18}</label>
                                </td>
                                <td width="20%">
                                  <div style="margin-right: 10;">
                                   
                                    <select style="width : 257px;" disabled id="filtroConvenio" onchange="reiniciaLimites(); cargarAsignaciones(\'NULL\', usuarioActualSeleccionado.value, document.getElementById(\'ges\').selectedIndex, \'convenio\', this.value); reiniciaSelects([\'filtroExoneracion\', \'filtroArea\']); if (this.selectedIndex == 0) { filtroUsado.value = \'NULL\' } else { filtroUsado.value = \'convenio\' } "> 
                                        <option value="-1">{tab7}</option>
                                        <option value="0">{tab8}</option>
                                        <option value ="P">{tab9}</option>
                                        <option value ="A">{tab10}</option>
                                        <option value ="C">{tab11}</option>
                                    </select>
                                </div>
                                </td></tr>
                                
                                <tr>
                                <td align="right" width="10%">
                                <label>Areas</label>
                                </td>
                                <td width="20%">
                                <div id="selectArea" style="margin-right: 10;">
                                    
                                  
                                    <select style="width : 257px;" disabled id="filtroArea" onchange="reiniciaLimites(); cargarClientes(usuarioActualSeleccionado.value) cargarAsignaciones(\'NULL\', usuarioActualSeleccionado.value, document.getElementById(\'ges\').selectedIndex, \'area\', this.value); reiniciaSelects([\'filtroExoneracion\', \'filtroConvenio\']); if (this.selectedIndex == 0) { filtroUsado.value = \'NULL\' } else { filtroUsado.value = \'area\' }">
                                        <option value="0">{tab8}</option>
                                        <option value ="1">Area 1</option>
                                        <option value ="2">Area 2</option>
                                        <option value ="3">Area 3</option>
                                    </select>
                                   
                                </div>
                                </td>
                                <td align="right" width="10%">
                                <label>Clientes</label>
                                </td>
                                <td width="20%">
                                <div>
                                    
                                    <select style="width : 257px;" id="selcCliente" onchange="cargarAsignaciones(\'NULL\', usuarioActualSeleccionado.value, document.getElementById(\'ges\').selectedIndex, \'area\', document.getElementById(\'filtroArea\').value, this.value); filtroUsado = \'area\'">
                                 
                                    </select>

                                    
                                </div>
                                </td></tr>
                                </table>
                                <br>
                                <div style="margin-right: 10;">
                                
                                 <button title="Ir a Expediente" disabled id="btnVerExpediente" onclick="verExpediente();" type="button"><img width="35px" heigth="35px" src="img/ico/irExpediente.ico"/></button>
                                
                                 <button title="Limpiar Pantalla" onclick="document.getElementById(\'form_envio\').reset(); $(\'#asignaciones-Tabla\').html(\'<tr></tr>\');" type="button"><img width="35px" heigth="35px" src="img/ico/limpiar.ico"/></button>
                                 <button title="Reporte en Excel" type="button" id="btnReportePortafolio" disabled onclick="crearReportePortafolio();"><img width="35px" heigth="35px" src="img/ico/excel.ico"/></button>
                                </div>
                               </div>
                              
                               <div style="width: 100%; height: 330px; overflow: auto;" id="contenedor-TablaPortafolio">
                              
                                <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="asignaciones-Tabla">
                                             <thead>

                                                 <tr>
                                                     <th onclick="checkTodos.click();" align = \'center\'><b><input hidden type=\'checkbox\' id="checkTodos" name=\'checkTodos\' onclick="activarDesactivarChecks(document.getElementsByName(\'checkPortafolio\'), document.getElementById(this.id));"/> Check</b></th>
                                                     <th><b>{pt_tab1}</b></th>
                                                     <th><b>{pt_tab2}</b></th>
                                                     <th><b>{pt_tab3}</b></th>
                                                     <th><b>{pt_tab4}</b></th>
                                                     <th><b>{pt_tab5}</b></th>
                                                     <th><b>{pt_tab6}</b></th>
                                                     <th><b>{pt_tab7}</b></th>
                                                     <th><b>{pt_tab8}</b></th>
                                                     <th><b>{pt_tab9}</b></th>
                                                     <th><b>{pt_tab10}</b></th>



                                                 </tr>
                                             </thead>
                                             <tbody>

                            
                            <tr class="gradeA odd" id="1">

                                <td class="center"><input type="checkbox" name="check1" value="1"></td>
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
                            
                            
                            <tr class="gradeA even" id="1">

                                <td class="center"><input type="checkbox" name="check1" value="1"></td>
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
                            
                            
                            <tr class="gradeA odd" id="1">

                                <td class="center"><input type="checkbox" name="check1" value="1"></td>
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
                                                <tfoot id="pieasignaciones-Tabla">
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
                        </form>
                         <div class="progreso"></div>
                        <div style="width: 100%;">
                     
                        <div style="float: right;">
                            <label>{tab12}</label> <input size="10px" style="text-align:right;" id="cuentas" onclick="contarFilas();" type="text" disabled value =""/>
                            <label>{tab13} '.$_SESSION['simb_moneda'].'</label> <input size="15px" style="text-align:right;" id="saldos" type="text" disabled/>
                            </div>
                        </div>
                        
                        

                                 </fieldset></div>
                                 
                                
                                 
                                 <br>
                          

                                 <div>
                                 <fieldset class="recuadro" style="border:1px solid black; height: 70px; padding-top: 0; background: initial;"><legend>{tab14}</legend>
                                 
                                    <div>
                                    <table style="font-size: 13px;">
                                    <tr><td>
                                    <label>{tab15}:</label> <div style="display: inline;" id ="di"><select  style="width: 220px;" id="sel"></select></div></td>
                                   <!-- <button title="Asignar Cuentas" disabled id="btnAsignar" onclick="reasignarCuentas();" type="button"><img width="35px" heigth="35px" src="img/ico/asignar.ico"/></button> -->
                                   <td><img title="Asignar Cuentas" ';

     if($ind == 1){
                    
                    $portafolio .= 'hidden ';
                    
                } else {
                    
                    $portafolio .= '';
                }

 $portafolio .= ' id="btnAsignar" onclick="reasignarCuentas();" width="24px" heigth="24px" src="img/ico/asignar.ico"/></td>
                                        </tr></table></div>                               
                                 </fieldset>
                                 </div>
                                 
                                         <div>
                                 <fieldset class="recuadro" style="border:1px solid black; height: 70px; padding-top: 0; background: initial;"><legend>{tab16}</legend>
                                 <table style="font-size: 13px;">
                                 <tr>
                                    <td><input id="checkArea"';
                if($ind == 1){
                    
                    $portafolio .= 'disabled ';
                    
                } else {
                    
                    $portafolio .= '';
                }
                                    
        $portafolio .=  'name="checkArea" onclick="(this.checked == true) ? cargarAsignaciones(\'NULL\', usuarioActualSeleccionado.value, document.getElementById(\'ges\').selectedIndex, \'aprobarAreas\', \'NULL\') : cargarAsignaciones(\'NULL\', usuarioActualSeleccionado.value, document.getElementById(\'ges\').selectedIndex, \'NULL\'); asignarAreas(this); " type="checkbox"/>
                                    <label>{tab17}:</label> <div style="display: inline;" id ="di2"><select disabled style="width: 220px;" id="areaEnvio" name= "areaEnvio"><option></option></select></div></td>
                                  <!--  <button title="Enviar Al Area" disabled id="btnEnviarArea" onclick="enviarArea(areaEnvio.value);" type="button"><img width="35px" heigth="35px" src="img/ico/enviar.ico"/></button> -->
                                  <td>      <img title="Enviar Al Area" ';
            
             if($ind == 1){
                    
                    $portafolio .= 'hidden ';
                    
                } else {
                    
                    $portafolio .= '';
                }
        $portafolio .= ' id="btnEnviarArea" onclick="enviarArea(areaEnvio.value);" width="24px" heigth="24px" src="img/ico/enviar.ico"/></td>
                                     </tr> </table>
                                 </fieldset>
                                 </div>


                              </div>        
                                       
                            
                     </fieldset>
                     </div>
</form>
<div id="resp"></div>
<form hidden action="reportes/reporteportafolio.php" id="reporte" method="post">

<textarea></textarea>

</form>
<div id="ver"></div>
</div>
</body>

 
<link rel="stylesheet" href="css/propio.css">
<style>
.dataTables_scrollBody {
height: 200px !important;

}


.recuadro {

border-radius: 5px;


}

.ocultar > select {

display: none;

}

.hola {



}


</style>

<script src="../controlador/c_portafolio.js"></script>
<script src="../controlador/c_deudor.js"></script>




  ';


?>

                
