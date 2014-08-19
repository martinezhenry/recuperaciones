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

        $ver = $value[1].$value[2];
        
        $plantillas .= "<option>".$ver."</option>";

    }
    
} else
{
    $plantillas .= "<option selected disabled>".$filas."</option>";
}


$tabla = "";

if (isset($_SESSION['idPersona']))
{
 
    $registros = obtenerSmsEntrada("NULL", "NULL", "NULL", "1", $_SESSION['idPersona']);
} else
{
    $registros = obtenerSmsEntrada("NULL", "NULL", "NULL", "1");
}

if (is_array($registros))
{
$i=0;

$cabecera = '               <tr style="background-color:#2da5da; border: 1px solid;">
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
    
    if ($i%2 == 0)
    {
        $pintar = 'style="background-color:#f0ffff;';
    }else
    {
        $pintar = "";
    }
    
  //colocarTexto(document.getElementById(\'contenidoSMS\'), document.getElementById(\'contenido'.$i.'\').innerHTML); cursos: pointer;
   $tabla .= '<tr '.$pintar.' width: 25;">'
           . '<td  border: 1px solid;><input value="'.$value['ID_PERSONA'].'" name="checks" type="checkbox"></td>'
           . '<!-- <td>'.$value['ID'].'</td> -->'
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
						
							<form name="form_Enviar" onsubmit="" action="#" method="POST">	
                                                        <input type="hidden" name="form_Enviar">
							<fieldset>				
								<div class="row">
									<section class="col col-6">
                                                                        
										<label class="input">
											
                                                                                        
                                                                                        <label>Fecha de Envio:</label>
                                                                                        <div data-date-format="dd-mm-yyyy">
                                                                                            <input name="fechaEnvio" maxlength="10" type="date">
                                                                                       
                                                                                        </div>
                                                                                        
                                                                        </section>
                                                                        <section class="col col-6">
                                                                                <label class="input">
                                                                                <label>Cedula:</label>
                                                                            
                                                                                <input name="cedulaPersona" type="text" onkeypress="valida.soloNumeros();" maxlength = "8" placeholder="Cedula de la Persona">
                                                                                <button type="button" onclick="if (valida.Vacio(cedulaPersona.value)){ buscarTelefonos(cedulaPersona.value); document.form_Enviar.Enviar.disabled = false; } else { alert(\'Debe Ingresar La Cedula o Verifique que este Correcta\');}" class="button">Telefonos</button>
                                                                             
                                                                                </label>

                                                                             

                     
									</section>
                                                                        
								</div>
                                                                <div class="row">
                                                                    <section class="col col-6">
                                                                    
                                                                    <label class="input">
                                                                      <label>Fecha de la Promesa:</label>
                                                                      <input name="fechaPromesa" type="text" value="'.date("d/m/Y").'" disabled="disabled">
                                                                    </label>
                                                                    </section>
                                                                    
                                                                    <section class="col col-6">
                                                                    <label class="input">
                                                                      <label>Monto de la Promesa:</label>
                                                                      <input name="montoPromesa" type="text" value="15000" disabled="disabled">
                                                                    </label>
                                                                    
                                                                    </section>
                                                                    
                                                                     <div id="Escribe">
                                                                                </div>

                                                                </div>
								
                                                                <div class="row">
                                                                    <section>
                                                                    <label class="select">
                                                                         <label>Plantilla:</label>
                                                                         <div>
                                                                         <select onchange="colocarTexto(document.getElementById(\'smsContenido\'), document.getElementById(\'reg_select1\').options[document.getElementById(\'reg_select1\').options.selectedIndex].text);" id="reg_select1" class="form-control">
                                                                         '
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
                                                                <div id = "respuesta"></div>
								<button name="Enviar" type="button" onclick="if (valida.VacioFormulario(document.form_Enviar)) enviarSMS(document.form_Enviar);" disabled class="button">Enviar Mensaje</button>
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
                                                                                 <button type="button" name="fecha" onclick="if (valida.Vacio(fechaInicio.value) && valida.Vacio(fechaFin.value)){ if (fechaInicio.value > fechaFin.value){ alert(\'La Fecha De Inicio Debe Ser Menor o Igual Que La Final\'); } else { cargarTabla(fechaInicio.value, fechaFin.value, fecha.name, selectStatus.value, \''.$idPersona.'\'); } } else { alert(\'Debe Ingresar Ambas Fechas\'); }" class="button">Buscar</button>
                                                                            </label>
                                                			</section>
								</div>
								
                                                                    <section>
                                                                   <table>
                                                                   <tr><td>
                                                                            <label class="select">
                                                                                    
                                                                                    <select name="selectStatus" style="width:250px border: 1px solid;" id="reg_select2">
														
                                                                                        <option value="NULL">Todos</option> 
                                                                                        <option value ="0">Leidos</option> 
                                                                                        <option value ="1">No Leidos</option> 

                                                                                    </select>
                                                                            </label>
                                                                    </td><td>
                                                                        <label class="input">
                                                                        <div align="left">
                                                                            <button type="button" name="status" onclick="cargarTabla(fechaInicio.value, fechaFin.value, status.name, selectStatus.value, \''.$idPersona.'\');" class="button">Buscar</button>
                                                                        </div>
                                                                            
                                                                        </label>
                                                                        <td>
                                                                        <label class="input">
                                                                        <div align="left">
                                                                            <button type="button" name="ambos" onclick="if (valida.Vacio(fechaInicio.value) && valida.Vacio(fechaFin.value)){ if (fechaInicio.value > fechaFin.value){ alert(\'La Fecha De Inicio Debe Ser Menor o Igual Que La Final\'); } else { cargarTabla(fechaInicio.value, fechaFin.value, ambos.name, selectStatus.value, \''.$idPersona.'\'); } } else { alert(\'Debe Ingresar Ambas Fechas\'); }" class="button">Buscar Ambos</button>
                                                                        </div>
                                                                        </label>
                                                                        </td>
                                                                        </td>
                                                                        </tr>
                                                                  </table>
                                                                  </section>
                                                                 <div class="row">
                                                                  

                                                                        
                                                                       <!-- <table  border="1" cellpadding="0" cellspacing="0" width="100%"> -->
                                                                        <div id="Tabla" style="overflow: auto; wigth: 100%; heigth: 200px;">
                                                                        <table border= "2">
                                                                        
                                                                            <thead>
                                                                           
                                                                                '.$cabecera.'
                                                                                    
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
								<div id = "seleccionados"></div>
								<button type="button" onclick="verExpediente(document.form_Leer);" class="button">Ver Expediente</button>
							</fieldset>						
						
						</form>
					</li>
					
												
				</ul>
			</div>
			<!--/ tabs -->';
?>