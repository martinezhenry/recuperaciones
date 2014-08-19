<?php
//Se verifica si existe una varible de Session para "idPersona"
if (isset($_SESSION['idPersona']))
{
$idPersona = $_SESSION['idPersona'];
} else
{
    $idPersona = "NULL";
}

   //Llamadda al controlador
include("../controlador/c_sms.php");

//Varible para imprimir los telefonos
$telefonos = "";
//Variable para imprimir las plantillas de los SMS
$plantillas ="";
//Varible para almacenar los telefonos devueltos de BD
$filas = cargarPlantilas();
//print_r($filas);

//Verifica que $filas sea un arreglo
if (is_array($filas))
{
    $plantillas= "<option selected disabled>Seleccione Plantilla...</option>";
            
    foreach ($filas as  $colum => $value) {

        $ver = $value[1];
        
        $plantillas .= "<option value='".$value[0]."'>".$ver."</option>";

    }
    
} else
{
    $plantillas .= "<option selected disabled>".$filas."</option>";
}


$tabla = "";

if (isset($_SESSION['idPersona']))
{
 
    $tipoBusca = "'NULL', 'NULL', 'NULL', '1', '".$_SESSION["idPersona"]."'";
} else
{
    $tipoBusca = "'NULL', 'NULL', 'NULL', '1', 'NULL'";
}
/*
if (is_array($registros))
{
$i=0;

$cabecera = '               <tr>
                                 <th width="10"><input onclick="activarDesactivarChecks(document.getElementsByName(\'checks\'), document.getElementById(\'todos\'));" id="todos" name="todos" type="checkbox" value="Todos"></th>
                                 <!--  <th width="50%">Id</th> -->
                                 <th width="100">Fecha</th>
                                 <th width="100">Numero</th>
                                 <th width="550px">Contenido</th>
                                 <th width="50">Estado</th>
                            </tr>';


    foreach ($registros as  $colum => $value) {
    
    if ($value['STATUS'] == 1)
    {
        $value['STATUS'] = "Sin Leer";
    }else
    {
        $value['STATUS'] = "Leido";
    }
    

    
  //colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML); cursos: pointer;
   $tabla .= '<tr class="gradeA">'
           . '<td><input value="'.$value['ID_PERSONA'].'" name="checks" type="checkbox"></td>'
           . '<!-- <td>'.$value['ID_SMS'].'</td> -->'
           . '<td><a href="javascript: colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML);"><div style="border: 1px solid;">'.$value['FECHA_ENVIO'].'</div></a></td>'
           . '<td><a href="javascript: colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML);">'.$value['NUMERO_TELEFONO'].'</a></td>'
           . '<td style="width: 250;" id="contenido'.$i.'">'.$value['CONTENIDO_SMS'].$i.'</td>'
           . '<td><a href="javascript: colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML);">'.$value['STATUS'].'</a></td>'
           . '<tr>';
   $i++;
}
unset($i);

} else
{
    $cabecera = "";
    $tabla = $registros;
}
 */



//$fecha= date("d\/m\/Y");


$sms='


    
    <script>
        var valida = new Validaciones();
    </script> 
    


                                            	<!-- tabs -->
                                <div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-icons">
                                
                                

				<input onclick="'; if (isset($_SESSION['idPersona'])) $sms .= "buscarTelefonos('SESSION', 'HAY');"; else $sms .= ""; $sms .= '" type="radio" name="sky-tabs"  id="sms1" class="sky-tab-content-1">
				<label for="sms1"><span><span><i class="fa fa-pencil"></i>Redactar</span></span></label>
				
				<input onclick="cargarTabla(\'NULL\', \'NULL\', \'NULL\', \'NULL\', \'NULL\');" type="radio" name="sky-tabs" id="sms2" class="sky-tab-content-2">
				<label for="sms2"><span><span><i class="fa fa-file-text-o"></i>Recibidos</span></span></label>
                                
                                <input type="radio" name="sky-tabs"  id="sms3" class="sky-tab-content-3">
				<label for="sms3"><span><span><i class="fa fa-file-text-o"></i>Enviados</span></span></label>
								
				<ul>
					<li class="sky-tab-content-1">					
						
							<form name="form_Enviar" onsubmit="" action="#" method="POST">	
                                                        <input type="hidden" name="form_Enviar">
							<fieldset>				
								<div class="row">
									<section class="col col-6">
                                                                        
										<label class="input">
											
                                                                                        
                                                                                        <label>Fecha de Envio:</label>
                                                                                        <div data-date-format="dd-mm-yyyy">
                                                                                            <input name="fechaEnvio" value="'.date('Y-m-d').'" type="date">
                                                                                       
                                                                                        </div>
                                                                                        
                                                                        </section>
                                                                        <section class="col col-6">
                                                                                <label class="input">
                                                                                <label>Cedula:</label>
                                                                            
                                                                                <input id="cedulaPersona" name="cedulaPersona" type="text" onkeypress="valida.soloNumeros();" maxlength = "8" placeholder="Cedula de la Persona">
                                                                                <button type="button" onclick="if (valida.Vacio(cedulaPersona.value)){ buscarTelefonos(cedulaPersona.value, \'NULL\'); document.getElementById(\'Enviar_sms\').disabled = false; } else { alert(\'Debe Ingresar La Cedula o Verifique que este Correcta\');}" class="button">Telefonos</button>
                                                                             
                                                                                </label>

                                                                             

                     
									</section>
                                                                        
								</div>
                                                                <div class="row">
                                                                    <section class="col col-6">
                                                                    
                                                                    <label class="input">
                                                                      <label>Fecha de la Promesa:</label>
                                                                      <input id="fechaPromesa" name="fechaPromesa" type="date" value="'.date("Y-m-d").'">
                                                                    </label>
                                                                    </section>
                                                                    
                                                                    <section class="col col-6">
                                                                    <label class="input">
                                                                      <label>Monto de la Promesa:</label>
                                                                      <input id="montoPromesa" name="montoPromesa" type="text" value="">
                                                                    </label>
                                                                    
                                                                    </section>
                                                                    
                                                                          <div id="Escribe">
                                                                                </div>

                                                                </div>
                                                           
								
                                                                <div class="row">
                                                                    <section class="col col-6">
                                                                    <label class="select">
                                                                         <label>Plantilla:</label>
                                                                         <div>
                                                                         <select style="width: 180px;" onchange="buscaVariables(this.value, document.getElementById(\'ID_Persona\').value); colocarTexto(document.getElementById(\'smsContenido\'), document.getElementById(\'reg_select1\').options[document.getElementById(\'reg_select1\').options.selectedIndex].text);" id="reg_select1" class="form-control">
                                                                         
                                                                         '
                                                                         .$plantillas.
                                                                         '</select></div>
                                                                    </label>
                                                                    </section>
                                                                        <section class="col col-6">
                                                                    <label class="select">
                                                                         <label>Tipo Gestion:</label>
                                                                         <div>
                                                                         <select id="tipoGestionS">
                                                                            <option>Gestion Banco</option>
                                                                            <option>Gestion VPC</option>
                                                                         </select></div>
                                                                    </label>
                                                                    </section>
                                                                </div>
                                                                <div class="row">
                                                                    <section>
                                                                            <label class="textarea">
                                                                                    <i class="fa fa-append fa-comment"></i>
                                                                                    <textarea name="smsContenido" id="smsContenido" rows="4" readonly="readonly" placeholder="Contenido del SMS"></textarea>
                                                                            </label>
                                                                    </section>
								</div>
                                                                <div id = "respuesta"></div>
								<button name="Enviar_sms" id="Enviar_sms" type="button" onclick="enviarSMS(document.form_Enviar);" class="button">Enviar Mensaje</button>
							</fieldset>						
						 </form>
						
					</li>
					
					<li class="sky-tab-content-2">
						<form action="#" name="form_Leer" >
                                                <input type="hidden" name="form_Leer">
                                                            <fieldset>					
								<div class="row">
									<section class="col col-6">
                                                                            <label class="input">
                                                                                 <label>Fecha de Inicio:</label>
                                                                                 <div data-date-format="dd-mm-yyyy">
                                                                                     <input name="fechaInicio" id="fechaInicio" placeholder="dd-mm-yyyy" type="date">
                                                                                 </div>
                                                                                        
                                                                            </label>
                                                                        </section>
                                                                        <section class="col col-6">
                                                                            <label class="input">
                                                                                <label>Fecha de Fin:</label>
                                                                                <div  data-date-format="dd-mm-yyyy" >
                                                                                    <input name="fechaFin" id="fechaFin" placeholder="dd-mm-yyyy" type="date">
                                                                                </div>
                                                                                 <button type="button" name="fecha" onclick="if (valida.Vacio(fechaInicio.value) && valida.Vacio(fechaFin.value)){ if (fechaInicio.value > fechaFin.value){ alert(\'La Fecha De Inicio Debe Ser Menor o Igual Que La Final\'); } else { cargarTabla(fechaInicio.value, fechaFin.value, \'NULL\', \'fecha\'); } } else { alert(\'Debe Ingresar Ambas Fechas\'); }" class="button">Buscar</button>
                                                                            </label>
                                                			</section>
								</div>
								
                                                                    <section>
                                                                   <table>
                                                                   <tr><td>
                                                                            <label class="select">
                                                                                    
                                                                                    <select class="selectEditar" name="selectStatus" style="width:250px border: 1px solid;" id="reg_select2">
														
                                                                                        <option value="NULL">Todos</option> 
                                                                                        <option value ="0">Leidos</option> 
                                                                                        <option value ="1">No Leidos</option> 

                                                                                    </select>
                                                                            </label>
                                                                    </td><td>
                                                                        <label class="input">
                                                                        <div align="left">
                                                                            <button type="button" name="status" onclick="cargarTabla(\'NULL\', \'NULL\', selectStatus.value, \'status\');" class="button">Buscar</button>
                                                                        </div>
                                                                            
                                                                        </label>
                                                                        <td>
                                                                        <label class="input">
                                                                        <div align="left">
                                                                            <button type="button" name="ambos" onclick="if (valida.Vacio(fechaInicio.value) && valida.Vacio(fechaFin.value)){ if (fechaInicio.value > fechaFin.value){ alert(\'La Fecha De Inicio Debe Ser Menor o Igual Que La Final\'); } else { cargarTabla(fechaInicio.value, fechaFin.value, selectStatus.value, \'ambos\'); } } else { alert(\'Debe Ingresar Ambas Fechas\'); }" class="button">Buscar Ambos</button>
                                                                        </div>
                                                                        </label>
                                                                        </td>
                                                                        </td>
                                                                        </tr>
                                                                  </table>
                                                                  </section>
                                                                 <div id="zx" class="row">
                                                                  

                                                                        
                                                                    
                                                                        <div id="Tabla">
                                                                        </div>  
                                                                        <div style="display: none; left: 0; position: absolute; top: 0; width: 100%; z-index: 1001;" id="responder_texto">
                                                                        <div class="content-popup" style="width:250px; min-height:100px">
                                                                         <input id="tlf" hidden type="text" value=""/>
                                                                         <input id="cedula" hidden type="text" value=""/>
                                                                         <input id="idPersona" hidden type="text" value=""/>
                                                                        <label class="textarea">
                                                                        <div class="close"><a href="#" id="close2" onclick="$(\'#responder_texto\').fadeOut(\'slow\');
        $(\'.popup-overlay\').fadeOut(\'slow\'); $(\'#result\').html(\'\');
        return false;"><img style="margin-top: -10px; margin-right: -16;" src="img/close.png"/></a></div>
                                                                        <label id="para">Para: 132131</label><textarea id="respuestaSMS" rows="2" placeholder="Contenido del SMS"></textarea></label><div style="float: left;" id="result"></div><center><button style="padding: 0px 5px;
min-width: 100px;
height: 25px;
font: 10px \'Open Sans\', Helvetica, Arial, sans-serif;
border-style: outset;
border-width: 3px;
border-color: rgb(209, 209, 209);" type="button" class="button" onclick="responderEnviar();">Enviar</button></center></div></div>
			
								</div>

								
                                                                <div class="row">
                                                                    <section><br>
                                                                            <label class="textarea">
                                                                            
                                                                                    <i class="fa fa-append fa-comment"></i>
                                                                                    <textarea id="contenidoSMS" rows="4" readonly="readonly" placeholder="Contenido del SMS"></textarea>
                                                                            </label>
                                                                    </section>
								</div>
								<div id = "seleccionados"></div>
							<!--	<button type="button" onclick="verExpediente(document.form_Leer);" class="button">Ver Expediente</button> -->
							</fieldset>						
						
						</form>
					</li>
                                        
                                        
                                        <li class="sky-tab-content-3">
						<form>
                                               
                                                            <fieldset>					
								<div class="row">
									<section class="col col-6">
                                                                            <label class="input">
                                                                                 <label>Fecha de Inicio:</label>
                                                                                 <div data-date-format="dd-mm-yyyy">
                                                                                     <input name="fechaInicio1" id="fechaInicio1" placeholder="dd-mm-yyyy" type="date">
                                                                                 </div>
                                                                                        
                                                                            </label>
                                                                        </section>
                                                                        <section class="col col-6">
                                                                            <label class="input">
                                                                                <label>Fecha de Fin:</label>
                                                                                <div  data-date-format="dd-mm-yyyy" >
                                                                                    <input name="fechaFin1" id="fechaFin1" placeholder="dd-mm-yyyy" type="date">
                                                                                </div>
                                                                                 <button type="button" name="fecha" onclick="if (valida.Vacio(fechaInicio1.value) && valida.Vacio(fechaFin1.value)){ if (fechaInicio1.value > fechaFin1.value){ alert(\'La Fecha De Inicio Debe Ser Menor o Igual Que La Final\'); } else { cargarEnviados(fechaInicio1.value, fechaFin1.value, \'NULL\', \'fecha\'); } } else { alert(\'Debe Ingresar Ambas Fechas\'); }" class="button">Buscar</button>
                                                                            </label>
                                                			</section>
								</div>
								
                                                                    <section>
                                                                   <table>
                                                                   <tr><td>
                                                                            <label class="select">
                                                                                    
                                                                                    <select class="selectEditar" name="selectStatus1" style="width:250px border: 1px solid;" id="reg_select2">
														
                                                                                        <option value="NULL">Todos</option> 
                                                                                        <option value ="P">Pendientes</option> 
                                                                                        <option value ="E">Enviados</option> 
                                                                                        <option value ="R">Rechazados</option> 

                                                                                    </select>
                                                                            </label>
                                                                    </td><td>
                                                                        <label class="input">
                                                                        <div align="left">
                                                                            <button type="button" name="status" onclick="cargarEnviados(\'NULL\', \'NULL\', selectStatus1.value, \'status\');" class="button">Buscar</button>
                                                                        </div>
                                                                            
                                                                        </label>
                                                                        <td>
                                                                        <label class="input">
                                                                        <div align="left">
                                                                            <button type="button" name="ambos" onclick="if (valida.Vacio(fechaInicio1.value) && valida.Vacio(fechaFin1.value)){ if (fechaInicio1.value > fechaFin1.value){ alert(\'La Fecha De Inicio Debe Ser Menor o Igual Que La Final\'); } else { cargarEnviados(fechaInicio1.value, fechaFin1.value, selectStatus1.value, \'ambos\'); } } else { alert(\'Debe Ingresar Ambas Fechas\'); }" class="button">Buscar Ambos</button>
                                                                        </div>
                                                                        </label>
                                                                        </td>
                                                                        </td>
                                                                        </tr>
                                                                  </table>
                                                                  </section>
                                                                 <div id="zx" class="row">
                                                                  

                                                                        
                                                                    
                                                                        <div id="Tabla_SMS_Enviados">
                                                                        </div>  
                                                                        

								
                                                                <div class="row">
                                                                    <section><br>
                                                                            <label class="textarea">
                                                                            
                                                                                    <i class="fa fa-append fa-comment"></i>
                                                                                    <textarea id="contenidoSMS1" rows="4" readonly="readonly" placeholder="Contenido del SMS"></textarea>
                                                                            </label>
                                                                    </section>
								</div>
								
							<!--	<button type="button" onclick="verExpediente(document.form_Leer);" class="button">Ver Expediente</button> -->
							</fieldset>						
						
						</form>
					</li>


					
												
				</ul>
			</div>
			<!--/ tabs -->';


?>
<style>

.a:hover{ 
    
color: blue; 
text-decoration: underline;
} 
</style>
