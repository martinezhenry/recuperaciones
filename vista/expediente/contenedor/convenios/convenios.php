<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$V_convenios = '<div  style="height: 220px; width: 100%; overflow: auto;">
                                 
                                 <table width="100%" style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                 
                                    <tr>
                                        
                                        <td>
                                        
                                            <table align="center" style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                                
                                                <thead>
                                                
                                                    <th>{c_tab1}</th>
                                                    <th>{c_tab2}</th>
                                                    <th>{c_tab3}</th>
                                                    <th>{c_tab4}</th>
                                                   <!-- <th>Saldo Final</th> -->
                                                    <th>{c_tab5}</th>
                                                    <th>{c_tab6}</th>

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
                                                    
                                                    <th>{c_tab7}</th>
                                                    
                                                </thead>
                                                <tbody>

                                                    <td><input id="fechaPrimeraCuotasConvenio" value="'.date("Y-m-d").'" class="editar" size="12px" type="date" /></td>
                                                    <td><button onclick="guardarConvenio(cuentaActual.value);" class="botonEditar" style="width: 60px;" type="button">{c_tab8}</button>
                                                        <button hidden class="botonEditar" style="width: 60px;" id="btnAprobarConvenio" type="button" onclick="aprobarConvenio(\'check-convenios\')">{c_tab9}</button>
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
                                                        <th>{tc_tab1}</th>
                                                        <th>{tc_tab2}</th>
                                                        <th>{tc_tab3}</th>
                                                        <th>{tc_tab4}</th>
                                                        <th>{tc_tab5}</th>
                                                        <th>{tc_tab6}</th>
                                                        
                                                        

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

                                 

                                 </div>';