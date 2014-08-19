<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$V_telegramas = '<div  style="height: 220px; width: 100%; overflow: auto;">
                                 
                                 <label>{t_tab1}</label>
                                 <div style="display: flex;">
                                 <div>
                                 <div id="contenedor-selectTipoAviso">
                                 <select class="selectEditar">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                 </select>
                                 </div>
                                 <label>{t_tab2}</label>
                                 <div id="contenedor-selectDireccion">
                                 <select class="selectEditar">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                 </select>
                                 </div>
                                 </div>&nbsp;&nbsp;&nbsp;
                                 <textarea style="width: 60%; height: 50px;" class="editar" id="tipoAvisoTexto" rows="2" readonly="" placeholder="{t_tab3}"></textarea>&nbsp;
                                 <button onclick="enviarTelegrama(cuentaActual.value);" type="button" class="botonEditar" style="width: 100px;">{t_tab4}</button>
                                 </div>
                                 <br>
                                 
                                 <div id="contenedor-tablaTelegramas">

                                  <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="telegramas-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>{tt_tab1}</th>
                                                        <th>{tt_tab2}</th>
                                                        <th>{tt_tab3}</th>
                                                        <th>{tt_tab4}</th>
                                                        

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
                                 </div>';