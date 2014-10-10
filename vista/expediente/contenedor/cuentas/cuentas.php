<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$V_cuentas = ' 
<form onsubmit="return false;">
                         <hr align="left" noshade="noshade" size="2" width="100%" />
                        <div style="width:100%; overflow: auto;">
                      <fieldset style= "padding: 10px 30px 5px;">
                      
                        <section>	
                                       
                        
                            <div style="width: 100%; height: 200px; overflow: auto; outline: none;" id="cuentas">
                                
                                
            <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="cuentas-Tabla">
                        <thead>
                            
                            <tr>
                                <th align = \'center\'><b><input type=\'checkbox\' name=\'checkTodos\' /> Check</b></th>
                                <th><b>{tcnt_tab1}</b></th>
                                <th><b>{tcnt_tab2}</b></th>
                                <th><b>{tcnt_tab3}</b></th>
                                <th><b>{tcnt_tab4}</b></th>
                                <th><b>{tcnt_tab5}</b></th>
                                <th><b>{tcnt_tab6}</b></th>
                                <th><b>{tcnt_tab7}</b></th>
                                <th></th>
                         
                                

                                
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
                                <td align = \'center\'><button type="button" id="" class=".btnCopiar"><img width="20px" heigth="20px" src="img/ico/copy.ico"/><button></td>
                                

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
                                <td align = \'center\'><button type="button" id="" class=".btnCopiar"><img width="20px" heigth="20px" src="img/ico/copy.ico"/><button></td>
                                

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
                                <td align = \'center\'><button type="button" id="" class=".btnCopiar"><img width="20px" heigth="20px" src="img/ico/copy.ico"/><button></td>
                                

                            </tr>
                            
                           
                            </tbody>
                        
                            </table>

	

                                
                                
                            </div>
                         
                       
                    
                  
                        
<div style="float: left;">
<img style="cursor: pointer; margin-bottom: -4;" src="img/iconoFlecha-Izquierda.PNG" WIDTH=20 HEIGHT=20 onclick="javascript: if (document.getElementById(\'inicioArr\').value > \'1\'){ document.getElementById(\'inicioArr\').value = parseInt(document.getElementById(\'inicioArr\').value)-1; document.getElementById(\'envio\').click(); }">
<button hidden type="button" id="envio" onclick="ArrCuentas(document.getElementById(\'arr_\'+document.getElementById(\'inicioArr\').value).value, \''.$filtro.'\');">Enviar</button>
<input class="editar" autofocus onchange="javascript: if (parseInt(this.value) > parseInt(finArr.value)){ alert(\'Numero Maximo permitido \'+finArr.value); this.value = finArr.value; } else document.getElementById(\'envio\').click();" id="inicioArr" type="number" min="1" max="'; 
(count($arrCuentas) == 0) ? $V_cuentas .= count($arrCuentas)+1 : $V_cuentas .= count($arrCuentas);
$V_cuentas .= '" value="1"/> {c_of} 
<input class="editar" readonly="readonly" id="finArr" type="number" value="';

(count($arrCuentas) == 0) ? $V_cuentas .= count($arrCuentas)+1 : $V_cuentas .= count($arrCuentas);

//if (count($arrCuentas) == 0) $expediente .= count($arrCuentas)+1; else $expediente .= count($arrCuentas); 


$V_cuentas .='" min="1" max="115"/>
<img style="cursor: pointer; margin-bottom: -4;" src="img/iconoFlecha-Derecha.PNG" WIDTH=20 HEIGHT=20 onclick="javascript: sumarUno();">    

</div>
<div style="float: left; margin-left: 2px"><button style="margin-bottom: -4;" title="Limpiar Pantalla" onclick="location.reload();" type="button"><img width="20px;" heigth="20px" src="img/ico/limpiar.ico"/></button></div>

                        
                            <div class="row" align="right" id="estadisticas">
                            
                            <label>{c_montV}:</label>
                            <input class="editar" style=\'text-align:right;\' disabled="" type="text" id="montoVencido" name="montoVencido" value="0">
                            <label>{c_int}:</label>
                            <input class="editar" style=\'text-align:right;\' disabled="" type="text" id = "intereses" name="intereses" value="0">
                            <label>{c_montT}:</label>
                            <input class="editar" style=\'text-align:right;\' disabled="" type="text" id="montoTotal" name="montoTotal" value="0">
                            
                            
                        </div>
                        
    
                        </section>
                       
                     </fieldset>
                      </div> 
                      <hr align="left" noshade="noshade" size="2" width="100%" />
                     </form>
                       
                        

';