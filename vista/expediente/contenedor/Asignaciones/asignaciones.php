<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$V_asignaciones = '                  <div  style="height: 220px; width: 100%; overflow: auto;">
                               <div style= "float: left; text-align: left;">
                                  
                               <table style="font-size: 11px;" cellspacing="10">
                               <tr align="right">
                               <td>
            <label>{a_tab1}:</label> <input style="font-size: 11px;" id="clienteAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab2}:</label> <input style="font-size: 11px;" id="carteraAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab3}:</label> <input style="font-size: 11px;" id="tipoCuentaAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab4}:</label> <input style="font-size: 11px;" id="ciudadAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab5}:</label> <input style="font-size: 11px;" id="saldoInicialAsignacion" type="text" disabled class="editar"/><br>
                                  
            <label>{a_tab6}:</label> <input style="font-size: 11px;" id="saldoAnteriorAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab7}:</label> <input style="font-size: 11px;" id="saldoActualAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha Castigo:</label> <input style="font-size: 11px;" id="fechaCastigoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha Liquidacion:</label> <input style="font-size: 11px;" id="fechaLiquidacionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha Vencimiento:</label> <input style="font-size: 11px;" id="fechaVencimientoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Días Mora:</label> <input style="font-size: 11px;" id="diasMoraAsignacion" type="text" disabled class="editar"/><br>
                                  </td>
                                  <td>
            <label>{a_tab11}:</label> <input style="font-size: 11px;" id="devolucionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab12}:</label> <input style="font-size: 11px;" id="usuarioGestorAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab13}:</label> <input style="font-size: 11px;" id="fechaAsignadaAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab14}:</label> <input style="font-size: 11px;" id="fechaPrimeraAsignacionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab15}:</label> <input style="font-size: 11px;" id="statusCuentaAsignacion" type="text" disabled class="editar"/><br>
                                  

            <label>{a_tab16}:</label> <input style="font-size: 11px;" id="tipoCreditoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Fecha Ult. Pago:</label> <input style="font-size: 11px;" id="fechaUltPagoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Monto Ult. Pago:</label> <input style="font-size: 11px;" id="montoUltPagoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Gestionada:</label> <input style="font-size: 11px;" id="gestionadaAsignacion" type="text" disabled class="editar"/><br>
                                <!--  <label>{a_tab19}:</label> <input style="font-size: 11px;" id="observacionInternaAsignacion" type="text" disabled class="editar"/><br> -->
                                  <label>{a_tab20}:</label> <input style="font-size: 11px;" id="observacionExternaAsignacion" type="text" disabled class="editar"/><br>
                                  </td>
                                  <td>
            <label>{a_tab21}:</label> <input style="font-size: 11px;" id="fechaIngresoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab22}:</label> <input style="font-size: 11px;" id="fechaUltimaGestionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab23}:</label> <input style="font-size: 11px;" id="tipoCuentaClienteAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab24}:</label> <input style="font-size: 11px;" id="tipoAsignacionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>{a_tab25}:</label> <input style="font-size: 11px;" id="fechaAsignacionAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Capital:</label> <input style="font-size: 11px;" id="capitalAsignacion" type="text" disabled class="editar"/><br> 
                                  <label>{a_tab28}:</label> <input style="font-size: 11px;" id="capitalVencidoAsignacion" type="text" disabled class="editar"/><br>
                                  <label>Intereses:</label> <input style="font-size: 11px;" id="interesesNormalesAsignacion" type="text" disabled class="editar"/><br>
            <label>Mora:</label> <input style="font-size: 11px;" id="interesesMoraAsignacion" type="text" disabled class="editar"/><br>
                                
                                  
                                  
                                  </td>
                                  <td style="width: 110px">
                                    <label>{a_tab29}:</label>
                                    <textarea readonly id="observacionInternaAsignacion" style="font-size: 11px;" rows="11" class="editar"></textarea>

                                  </td>
                                  </tr>
                                  </table>
                                  </div>
                                  

                                 <!--  <label>Fecha de Ingreso: </label> <input style="font-size: 11px;" type="text" class="editar"/>
                                   
                                   
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
                             

                                </div>';