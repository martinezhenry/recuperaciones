<?php
//session_start();
//$usuario = $_SESSION['usuario'];
include("../controlador/c_sms.php");

$telefonos = "";



$plantillas ="";
$filas = cargarPlantilas();
//print_r($filas);

foreach ($filas as  $colum => $detalles) {
    
    $ver = $detalles[1].$detalles[2];
    $plantillas .= "<option>".$ver."</option>";
   
}
//implode($value)

$tabla = "";
$registros = obtenerSmsEntrada();
$i=0;
foreach ($registros as  $colum => $detalles) {
    
    if ($detalles['STATUS'] == 1)
    {
        $detalles['STATUS'] = "Sin Leer";
    }else
    {
        $detalles['STATUS'] = "Leido";
    }
    
    if ($i%2 == 0)
    {
        $pintar = 'style="background-color:#f0ffff;';
    }else
    {
        $pintar = "";
    }
    
  //colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML); cursos: pointer;
   $tabla .= '<tr '.$pintar.' width: 25;">'
           . '<td  border: 1px solid;><input name="checks" type="checkbox"></td>'
           . '<!-- <td>'.$detalles['ID'].'</td> -->'
           . '<td><a href="javascript: colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML);"><div style="border: 1px solid;">'.$detalles['FECHA_ENVIO'].'</div></a></td>'
           . '<td><a href="javascript: colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML);">'.$detalles['NUMERO_TELEFONO'].'</a></td>'
           . '<td style="width: 250;" id="contenido'.$i.'">'.$detalles['CONTENIDO_SMS'].$i.'</td>'
           . '<td><a href="javascript: colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML);">'.$detalles['STATUS'].'</a></td>'
           . '<tr>';
   $i++;
}
unset($i);
 

//cantidad de SMS entrantes
$entrantesSMS = smsNoLeidos();
//echo $entrantesSMS;
//Ocultar Notificacion
if($entrantesSMS == 0)
{
    $mostrarNotificacion = "Style=\"Display:none;\"";
}else
{
    $mostrarNotificacion = "";
}

//$fecha= date("d\/m\/Y");


$sms='


    <script src="../controlador/c_sms.js"></script>
    <script>
        var valida = new Validaciones();
    </script> 
    


                                            	<!-- tabs -->
                                <div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-icons">
				<input type="radio" name="sky-tabs"  id="sms1" class="sky-tab-content-1">
				<label for="sms1"><span><span><i class="fa fa-pencil"></i>Redactar</span></span></label>
				
				<input type="radio" name="sky-tabs" id="sms2" class="sky-tab-content-2">
				<label for="sms2"><span><span><i class="fa fa-file-text-o"></i>Recibidos</span></span></label>
				
								
				<ul>
					<li class="sky-tab-content-1">					
						
							<form name="form" id="form" onsubmit="return valida.VacioFormulario(this);" action="../controlador/c_sms.php" method="POST">	
							<fieldset>				
								<div class="row">
									<section>
                                                                        
										<label class="input">
											
                                                                                        
                                                                                        <label>Fecha de Envio:</label>
                                                                                        <div data-date-format="dd-mm-yyyy">
                                                                                            <input name="fechaEnvio" maxlength="10" type="date">
                                                                                       
                                                                                        </div>
                     
									</section>
								</div>
                                                                <div class="row">
                                                                    <section>
                                                                        <label class="input">
                                                                        <label>Cedula:</label>
                                                                        
                                                                            <input name="cedulaPersona" type="text" onkeypress="valida.soloNumeros();" maxlength = "8" placeholder="Cedula de la Persona"><br>
                                                                            <button type="button" onclick="if (valida.Vacio(cedulaPersona.value) && cedulaPersona.value.length >= 8){ buscarTelefonos(cedulaPersona.value); document.form.Enviar.disabled = false; } else { alert(\'Debe Ingresar La Cedula o Verifique que este Correcta\');}" class="button">buscar</button>
                                                                            <br>
                                                                            </label>
                                                                            <label>Telefonos</label><br>
                                                                            <div id="Escribe">
                                                                            </div>
                                                                        
                                                                    </section>
                                                                </div>
								
                                                                <div class="row">
                                                                    <section>
                                                                    <label class="select">
                                                                         <label>Plantilla:</label>
                                                                         <div>
                                                                         <select onchange="colocarTexto(document.getElementById(\'smsContenido\'), document.getElementById(\'reg_select1\').options[document.getElementById(\'reg_select1\').options.selectedIndex].text);" id="reg_select1" class="form-control">
                                                                         <option selected disabled>Seleccione Plantilla...</option>'
                                                                         .$plantillas.
                                                                         '</select></div>
                                                                    </label>
                                                                    </section>
                                                                </div>
                                                                <div class="row">
                                                                    <section>
                                                                            <label class="textarea">
                                                                                    <i class="fa fa-append fa-comment"></i>
                                                                                    <textarea name="contenidoSMS" id="smsContenido" rows="4" readonly="readonly" placeholder="Contenido del SMS"></textarea>
                                                                            </label>
                                                                    </section>
								</div>
								<button name="Enviar" type="submit" disabled onclick="tomarTelefonos();" class="button">Enviar Mensaje</button>
							</fieldset>						
						 </form>
						
					</li>
					
					<li class="sky-tab-content-2">
						<form action="#">							<fieldset>					
								<div class="row">
									<section class="col col-6">
                                                                            <label class="input">
                                                                                 <label>Fecha de Inicio:</label>
                                                                                 <div data-date-format="dd-mm-yyyy">
                                                                                     <input placeholder="dd-mm-yyyy" type="date">
                                                                                 </div>
                                                                                        
                                                                            </label>
                                                                        </section>
                                                                        <section class="col col-6">
                                                                            <label class="input">
                                                                                <label>Fecha de Fin:</label>
                                                                                <div  data-date-format="dd-mm-yyyy" >
                                                                                    <input placeholder="dd-mm-yyyy" type="date">
                                                                                </div>
                                                                            </label>
                                                			</section>
								</div>
								
                                                                    <section>
                                                                   <table>
                                                                   <tr><td>
                                                                            <label class="select">
                                                                                    
                                                                                    <select style="width:250px border: 1px solid;" id="reg_select2">
														
                                                                                        <option>Todos</option> 
                                                                                        <option>Leidos</option> 
                                                                                        <option>No Leidos</option> 

                                                                                    </select>
                                                                            </label>
                                                                    </td><td>
                                                                        <label class="input">
                                                                        <div align="left">
                                                                            <button type="button" onclick= "" class="button">Buscar</button>
                                                                        </div>
                                                                        </label>
                                                                        </td>
                                                                        </tr>
                                                                  </table>
                                                                  </section>
                                                                 <div class="row">
                                                                  

                                                                        
                                                                       <!-- <table  border="1" cellpadding="0" cellspacing="0" width="100%"> -->
                                                                        <div>
                                                                        <table border= "2">
                                                                        
                                                                            <thead>
                                                                           
                                                                                        <tr style="background-color:#2da5da; border: 1px solid;">
                                                                                        <th width="10"><input onclick="activarDesactivarChecks(document.getElementsByName(\'checks\'), document.getElementById(\'todos\'));" id="todos" name="todos" type="checkbox" value="Todos"></th>
                                                                                  <!--  <th width="50%">Id</th> -->
                                                                                        <th width="100">Fecha</th>
                                                                                        <th width="100">Numero</th>
                                                                                        <th width="550px">Contenido</th>
                                                                                        <th width="50">Estado</th>



                                                                                    </tr>
                                                                                    
                                                                            </thead>
                                                                         
                                                                         
                                                                        
                                                                            
                                                                            <tbody>
                                                                              
                                                                              
                                                                            ' 
                                                                           
                                                                            

                                                                                
                                                                          .$tabla.


                                                                            '
                                                                                </tbody>
                                                                          

                                                                        </table>
                                                                        </div>  
                                                                        
			
								</div>

								
                                                                <div class="row">
                                                                    <section><br>
                                                                            <label class="textarea">
                                                                            
                                                                                    <i class="fa fa-append fa-comment"></i>
                                                                                    <textarea id="contenidoSMS" rows="4" readonly="readonly" placeholder="Contenido del SMS"></textarea>
                                                                            </label>
                                                                    </section>
								</div>
								
								<button type="submit" class="button">Ver Expediente</button>
							</fieldset>						
						
						</form>
					</li>
					
												
				</ul>
			</div>
			<!--/ tabs -->';
?>