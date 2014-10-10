
<?php

include('../controlador/c_email.php');


$email = ' 
       <div>
       <div style="height:130px;overflow:auto;">
            <table style="font-size: 11px; margin-left: 0px; width: 100%;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="tabla-emails">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Parentesco</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
            <tbody></tbody>
            </table>
            </div>
            <form class="sky-form">
            <button name="Enviar" type="button" onclick="guardarEmails(personaActual.value);" disabled class="button" id="buttonGuardarEmail">Guardar</button>
            <button name="Enviar" type="button" onclick="agregarEmail();" class="button"  id="">Agregar</button>
            <button name="Enviar" type="button" onclick="editarEmail();" class="button" >Editar</button>
            </form>
            </div>
         ';

/*$email='<script src="../controlador/c_email.js"></script>
<script>
        var valida = new Validaciones();
    </script>
                                            	<!-- tabs -->
            <div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-icons">
			<input type="radio" name="sky-tabs" id="sky-tab3" class="sky-tab-content-3">
			<label for="sky-tab3"><span><span><i class="fa fa-cogs"></i>EMAIL</span></span></label>
				<ul>
					<li class="sky-tab-content-3">
						<form name="form_Enviar" onsubmit="" action="#" method="GET">	
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
										<button type="button" onclick="if (valida.Vacio(cedulaPersona.value)){ buscarCorreos(cedulaPersona.value); } else { alert(\'Debe Ingresar La Cedula o Verifique que este Correcta\');}" class="button">Correos</button>
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
									<div id="Escribe2">
									
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
										'</select>
									</div>
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
								<button name="Enviar_Email" type="button" onclick="" disabled class="button">Enviar Email</button>
							</fieldset>						
						</form>
					</li>
				</ul>
			</div>
			<!--/ tabs -->';*/

//$email= "";

?>
                        