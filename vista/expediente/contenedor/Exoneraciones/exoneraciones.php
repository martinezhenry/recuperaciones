<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$V_exoneraciones = '<div  style="height: 220px; width: 100%; overflow: auto;">
                                 
                                 <table width="100%" border="2" style="font-size: 13px;">
                                 
                                         <tr>
                                    <td>
                                    <table style="font: 13px/1.55 \'Open Sans\', Helvetica, Arial, sans-serif; color: #666;">
                                    <tr align="center">
                                        <td><label>{e_tab1}:</label></td>
                                        <td><label>{e_tab2}:</label></td>
                                        <td><label>{e_tab3}:</label></td>
                                        <td><label>{e_tab4}:</label></td>
                                        <td><label>Fecha de Pago:</label></td>
                                        <td><label>{e_tab5}:</label></td>
                                        
                                    </tr><tr align="center">
                                         <td><input id="saldoAExonerar" size="12px" type="text" disabled class="editar"/></td>
                                         <td><input id="%AExonerar" size="12px" type="text" maxlength="3" onkeyup="calcularExoneracion();"  class="editar"/></td>
                                         <td><input id="montoExonerado" size="12px" type="text" disabled  class="editar"/></td>
                                         <td><input id="MontoACancelar" size="12px" type="text" disabled class="editar"/></td>
                                         <td><input id="FechaPago" size="12px" type="date" value="'.date("Y-m-d").'" class="editar"/></td>
                                        
                                        <td><select id="tipoPago" onchange="mostrarOcultarFracciones(this.selectedIndex);" style="width: 100px;" class="selectEditar">
                                            <option value="0">Decontada</option>
                                            <option value="1">Fracionada</option>
                                        </select></td>
                                        <td><div style="display: none;" id="fracciones">
                                        <label>{e_tab6}:</label> <input size="8px" value="1" id="cuotasExoneracion" type="text" class="editar"/>
                                        <label>{e_tab7}:</label> <input value="'.date("Y-m-d").'" style="width: 130px;" id="fechaCuotasExoneracion" type="date" class="editar"/>
                                        </div>
                                       
                                        </td>
                                        <td><button class="botonEditar" style="width: 60px;" type="button" onclick="generarExoneracion(cuentaActual.value);">{e_tab8}</button>
                                        <button hidden class="botonEditar" style="width: 60px;" id="btnAprobarExoneracion" type="button" onclick="aprobarExoneracion(\'check-exoneraciones\')">{e_tab9}</button>
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
                                                            <th>{te_tab1}</th>
                                                            <th>{te_tab2}</th>
                                                            <th>{te_tab3}</th>
                                                            <th>{te_tab4}</th>
                                                            <th>{te_tab5}</th>
                                                            <th>{te_tab6}</th>
                                                            <th>{te_tab7}</th>


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
                                        <button type="button" class="botonEditar" style="width: 60px;">{e_tab10}</button>
                                        <button type="button" class="botonEditar" style="width: 60px;">{e_tab11}</button>
                                    </div> -->
                                


                                 </div>
';