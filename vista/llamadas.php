<?php

require_once("../controlador/c_llamada.php");
//include("direcciones.php");
$llamada = new C_llamada();

//Verificamos las acciones del usuario



$llamadas ='
			
								
						<form id="form_llamadas" action="#">
							<fieldset>					
								<div class="row">
									<section class="col col-5">				
										<label class="label"></label>
										<div id="alert_css">¿Desea guardar los cambios? <button type="button" class="confirm_button" onclick="v_llamada_enviarDatos()">Aceptar</button><button type="reset" class="cancele_button" onclick="v_llamada_cancelarAd()" >Cancelar</button></div>
										
									</section>
								</div>
								<div class="row">
									<section class="col col-6">
			                        	<label class="select select-multiple">
		                                <form action="#">
											<fieldset>					
											<div class="row">
												<section class="col col-6"style="display: none;">
												<label class="input">
												<label>Fecha de Inicio:</label>
												<div data-date-format="dd-mm-yyyy">
												<input placeholder="dd-mm-yyyy" type="date">
											</div>

										</label>
									</section>
									<section class="col col-6"style="display: none;">
									<label class="input">
									<label>Fecha de Fin:</label>
									<div data-date-format="dd-mm-yyyy">
									<input placeholder="dd-mm-yyyy" type="date">
									</div>
									</label>
								</section>
								</div>
							<div class="row">
							
							<div style="width: 400px; margin: 0px auto;" id="tabla_ll">
							';

							if(isset($_POST['recargar'])){
							  	$llamada->cargarTabla($_SESSION['idPersona']);//LLamamos el metodo para guardar en la base de datos
							}

							$llamadas = $llamadas.'
								<table style="font-size: 11px; width: 10%" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="telefonos-Tabla">
									<thead>
										<tr>
										<th width="20px">Código</th>
										<th width="20px">Teléfono</th>
										<th width="20px">Estatus</th>
										</tr>
									</thead>
									<tbody>
										
								';
					  $x = 0;			
                                if (isset($_SESSION['idPersona'])){
                                $datos = $llamada->obtener_telefonos($_SESSION['idPersona']);//Se coloca el id_persona que este en session
                              
                                $combinacion = array();
                                $numeros = array();
                                if (is_array($datos)){
                                    
                           
                                foreach($datos as $r){
                                	//Por los momentos no se muestra el $r[persona]
                                	$sele = false; 
                                	if($r['STATUS_TELEFONO']=='A')
                                		$sele = true;

                                	$llamadas = $llamadas."<tr class=\"gradeA\"><td>".$r['COD_AREA']."</td><td width='200px'>".$r['TELEFONO']."</td><td width='20px'><label class='select'><select id=\"select_status$x\" class='select_llamadas' disabled> "; 
                                	if($sele)
                                		$llamadas = $llamadas."<option value='A'selected>Activo</option>      <option value='I'>Inactivo</option>";
                                	else
                                		$llamadas = $llamadas."<option value='A' >Activo</option>              <option value='I' selected>Inactivo</option>";

                                	$llamadas = $llamadas."</select></label></td></tr>";
               						//Guardamos el ID de la persona
               						if($r['COD_AREA']==NULL)
               							$numeros[$x] = '&%'.$r['TELEFONO'].'/';
               						else
               							$numeros[$x] = $r['COD_AREA'].'%'.$r['TELEFONO'];//Guardamos los numeros telefonicos de la persona.
               						$combinacion[$x] = $r['STATUS_TELEFONO']; //Creamos una array que contendra una combinacion de los estatus traidos de la base de datos
               						$x++;
                                }
                              
                                $llamada->set_id_person($r['PERSONA']);
                                  }
                                $llamada->set_numeros($numeros);
                                $llamada->set_combina($combinacion);
                                }
                               //registramos la combinacion en el objeto
		                                  
$llamadas = $llamadas.'                     
    
                                            </tbody>
                                        </table>    
                                            
                                            	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example0" width="100%">
				<tfoot id="pietelefonos-Tabla">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
                                                <th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>


							</div>  
						</div>
					</fieldset>						
				</form>
		                            
			                        </label>
								</section>
				                </div>
                                                <form class="sky-form">
						<button name="Enviar" type="button" onclick="mostrarAdvertencia()" disabled  class="button" id="llamada_guardar">Guardar</button>
						<button type="button" class="button" onclick="v_llamada_activarEdicion();" id="llamada_edit_reset">Editar</button>
                                               
</form>
							</fieldset>					
						</form>
						
		
	';

		if(isset($_POST['cadena'])){
		  	$llamada->guardar_status($_POST['cadena']);//LLamamos el metodo para guardar en la base de datos
		}
		
		echo "<script> var cantidad = $x; </script>";


?>
                     
<script type="text/javascript">

//Función para habilitar la edición de los estatus de los números de teléfonos
function v_llamada_activarEdicion() {
 	//Verificamos si el check está en cheked
	 	$('.select_llamadas').prop("disabled","");
	 	$("#llamada_guardar").removeAttr("disabled");
	 	texto = $("#llamada_edit_reset").text();
		texto = texto.replace("Editar", "Cancelar");
		$("#llamada_edit_reset").text(texto);
		$("#llamada_edit_reset").attr("onclick", "v_llamada_resetSelects()");
		

	  //Habilitamos los select 
}

function mostrarAdvertencia() {
	$('#alert_css').show('fast');
}

//Funcion para enviar los datos modificados de los selects al archivo .php
function v_llamada_enviarDatos() {
	var contenido = "";
	
 	for (var i=0;i<(cantidad);i++){
 		//alert('i= '+ i + '  cantidad: ' + cantidad);
 		if(i==(cantidad-1)){
			contenido =  contenido + document.getElementById('select_status'+i).value ;
			
 		}
		else{
			contenido =  contenido + document.getElementById('select_status'+i).value + '/'   ;
				
		}
	}

	var parametros = {"cadena" : contenido};

	$.ajax({
		  type: "POST",
		  url: 'llamadas.php',
		  data: parametros,
		  
		  success: function(result) {
		     //alert(result);   VERIFICAR PORQUE TRAE OTRO TEXTO
		     $('#alert_css').hide('fast');
		     $('.select_llamadas').prop("disabled","disabled");
				$("#editar").removeAttr("disabled");
				$("#llamada_guardar").attr("disabled", true);
				texto = $("#llamada_edit_reset").text();
				texto = texto.replace("Cancelar", "Editar");
				$("#llamada_edit_reset").text(texto);
				$("#llamada_edit_reset").attr("onclick", "v_llamada_activarEdicion()");
				$("#llamada_edit_reset").attr("type", "button");
				$("#llamada_edit_reset").attr("class", "button");
				for (var i=0;i<(cantidad);i++){
		 		var miValue = $("#select_status"+i ).val();
					if(miValue == 'A') {
		    			$("#select_status"+i+ " option[value="+miValue+"]").attr("selected",true);
		    			$("#select_status"+i+ " option[value=I]").attr("selected",false);
		    		}
		    		else {
		    			$("#select_status"+i+ " option[value="+miValue+"]").attr("selected",true);
		    			$("#select_status"+i+ " option[value=A]").attr("selected",false);
		    		}
				}
				v_llamada_cargarTabla();
		   }
		  


  	});
}

function v_llamada_cargarTabla() {
	$('#tabla_ll').html('');//vaciamos el div donde se encuentra la tabla

	var parametros = {"recargar" : 1};
	$.ajax({
		  type: "POST",
		  url: 'llamadas.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#tabla_ll').html(result);
                     llamaTodo('llamadas-Tabla');
		   }
  	});
}



//Funcion para resetear los select con sus valores iniciales
function  v_llamada_resetSelects() {
	
	$('.select_llamadas').prop("disabled","disabled");
	$("#editar").removeAttr("disabled");
	$("#llamada_guardar").attr("disabled", true);
	texto = $("#llamada_edit_reset").text();
	texto = texto.replace("Cancelar", "Editar");
	$("#llamada_edit_reset").text(texto);
	$("#llamada_edit_reset").attr("onclick", "v_llamada_activarEdicion()");
		$("#llamada_edit_reset").attr("type", "reset");
		$("#llamada_edit_reset").attr("class", "button button-secondary");

  }  
			
//Funcion para cancelar por la advertencia 
function v_llamada_cancelarAd() {
	$('#alert_css').hide('fast');
	for (var i=0;i<(cantidad);i++){
 		var select = $('#select_status'+i);
		select.val($('option:first', select).val());
	}
	$('.select_llamadas').prop("disabled","disabled");
	$("#editar").removeAttr("disabled");
	$("#llamada_guardar").attr("disabled", true);
	texto = $("#llamada_edit_reset").text();
	texto = texto.replace("Cancelar", "Editar");
	$("#llamada_edit_reset").text(texto);
	$("#llamada_edit_reset").attr("onclick", "v_llamada_activarEdicion()");
}
</script>

 <style type="text/css">
 #alert_css {
 	background: #2da5da;
 	border-color: #278AB6;
 	border-radius: 6px;
 	border-style: solid;
 	border-width: 2px;
 	color: white;
 	display: none;
 	padding: 3px;
 }
 .confirm_button {
	background: rgb(0, 167, 0);
	border-color: green;
	border-radius: 4px;
	border-style: solid;
	border-width: 1px;
	color: white;
	cursor: pointer;
	padding: 3px;
}

.cancele_button {
	background: rgb(167, 0, 0);
	border-color: maroon;
	border-radius: 4px;
	border-style: solid;
	border-width: 1px;
	color: white;
	cursor: pointer;
	margin-left: 4px;
	padding: 3px;
}
 </style>