<?php
require_once("../controlador/c_direcciones.php");
$direccion = new C_direccion();
if (!isset($_SESSION)){
    
    session_start();
    
}

$direcciones = '
				
				<form id="form_direcciones" action="#">
                              
					<fieldset>
						<div class="row">
							<section class="col col-5">				
								<label class="label"></label>
								<div id="alert_css_dir">¿Desea guardar los cambios? <button type="button" class="confirm_button" onclick="v_direccion_enviarDatos()">Aceptar</button><button type="reset" class="cancele_button" onclick="v_direccion_cancelarAd()" >Cancelar</button></div>
								
							</section>
						</div>
						<div class="row">
							<section class="col col-6">
								<label class="select select-multiple">
									<fieldset>
										<div class = "row">
											<div id="tabla_dir">';
												if(isset($_POST['re_direccion'])){
                                                                                                  
                                                                                                       if (isset($_POST['guardar'])){
                                                                                                           $persona = $_POST['persona'];
                                                                                                           $direcciones = $_POST['direcciones'];
                                                                                                           $usuario = $_SESSION['USER'];
                                                                                                           $direccion->guardarDireccion($persona, $direcciones, $usuario);
                                                                                                          
                                                                                                        }
                                                                                                    
                                                                                                    
													$direccion->cargarTabla($_SESSION['idPersona']);//Se coloca la variable de session con el ID de la persona
                                                                                                        $datos = $direccion->obtener_direcciones($_SESSION['idPersona']);//Se coloca el id_persona que este en session
                                                                                                      
												}
												$direcciones = $direcciones.'
												<table  style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="direcciones-Tabla">
													<thead>
														<tr>
															<th width="300px">Dirección</th>
															<th width="20px">Estatus</th>
														</tr>
													</thead>
													<tbody>';
                                                                                                            
														$x = 0;
                                                                                                                    if (isset($_SESSION['idPersona'])){
														$datos = $direccion->obtener_direcciones($_SESSION['idPersona']);//Se coloca el id_persona que este en session
                                                                                                                
                                                                                                                $directions = array(); 
                                                                                                                if (is_array($datos)){
														foreach($datos as $r){
														//Por los momentos no se muestra el $r[persona]
														$sele = false; 
														if($r['STATUS_DIRECCION']=='A')
															$sele = true;

														$direcciones = $direcciones."<tr class=\"gradeA\"><td>".$r['DIRECCION']."</td><td><label class='select'><select class=\"selectEditar\" id=\"dir_select_status$x\" class='select_llamadas' disabled> "; 
														if($sele)
														$direcciones = $direcciones."<option value='A'selected>Activo</option>      <option value='I'>Inactivo</option>";
														else
														$direcciones = $direcciones."<option value='A' >Activo</option>              <option value='I' selected>Inactivo</option>";

														$direcciones = $direcciones."</select></label></td></tr>";

														//Guarda las direcciones de la persona y la combinancion del status correspondiente
														$directions[$x] = $r['DIRECCION'];
														$combinacion[$x] = $r['STATUS_DIRECCION'];
														//echo "<script>alert('valor de combinacion: ".count($combinacion)."')</script>";
														//echo "<script>alert('contador java: ".$x."')</script>"; //Creamos una array que contendra una combinacion de los estatus traidos de la base de datos
														$x++;
														}
                                                                                                             
														$direccion->set_id_person($r['PERSONA']);
                                                                                                                $direccion->set_direcciones($directions);
														$direccion->set_combina($combinacion);
                                                                                                                } //FIN IF IS_ARRAY
                                                                                                                }
													//registramos la combinacion en el objeto
													$direcciones = $direcciones.'
												</tbody></table>
                                                                                                
                                                                                                                            	<table style="display: none;" cellpadding="0" cellspacing="0" border="0" class="display" id="example0" width="100%">
                                                                                                                                    <tfoot id="piedirecciones-Tabla">
                                                                                                                                            <tr>
                                                                                                                                                    <th></th>
                                                                                                                                                    <th></th>
                                                                                                                                       
                                                                                                                                            </tr>
                                                                                                                                    </tfoot>
                                                                                                                            </table>

											</div>
										</div>
										
									</fieldset>
								</label>
							</section>
						</div> </form>
                                          <form class="sky-form">
						<button name="Enviar" type="button" onclick="mostrarAdvertenciaDirecciones()" disabled  class="button" id="direccion_guardar">Guardar</button>
                                                <button type="button" class="button" onclick="agregarDireccion(personaActual.value);" id="btnAgregarDireccion">Agregar</button>
						<button type="button" class="button" onclick="v_direccion_activarEdicion();" id="direccion_edit_reset">Editar</button>
                                                                </form>                      
                                                                                         
					</fieldset>  	
                                        



<script src="../controlador/c_direcciones.js"></script>
<script type="text/javascript">




 llamaTodo(\'direcciones-Tabla\');


</script>





				';

				if(isset($_POST['cadena_dir'])){
		  			$direccion->guardar_status($_POST['cadena_dir']);//LLamamos el metodo para guardar en la base de datos
				}
		
		echo "<script> var cantidad_dir = $x; </script>";
?>

<script type="text/javascript">

//Función para habilitar la edición de los estatus de los números de teléfonos
function v_direccion_activarEdicion() {
 	//Verificamos si el check está en cheked
	 	$('.select_llamadas').prop("disabled","");
	 	$("#direccion_guardar").removeAttr("disabled");
	 	texto = $("#direccion_edit_reset").text();
		texto = texto.replace("Editar", "Cancelar");
		$("#direccion_edit_reset").text(texto);
		$("#direccion_edit_reset").attr("onclick", "v_direccion_resetSelects()");
		
	  //Habilitamos los select 
}

function mostrarAdvertenciaDirecciones() {
	$('#alert_css_dir').show('fast');
}

//Funcion para enviar los datos modificados de los selects al archivo .php
function v_direccion_enviarDatos() {
	var contenido = "";
	
 	for (var i=0;i<(cantidad_dir);i++){
 		//alert('i= '+ i + '  cantidad_dir: ' + cantidad_dir);
 		if(i==(cantidad_dir-1)){
			contenido =  contenido + document.getElementById('dir_select_status'+i).value ;
			
 		}
		else{
			contenido =  contenido + document.getElementById('dir_select_status'+i).value + '/'   ;
					
		}
	}
       
	var parametros = {"cadena_dir" : contenido};

	$.ajax({
		  type: "POST",
		  url: 'direcciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     alert(result);
		     $('#alert_css_dir').hide('fast');
		     $('.select_llamadas').prop("disabled","disabled");
				$("#editar").removeAttr("disabled");
				$("#direccion_guardar").attr("disabled", true);
				texto = $("#direccion_edit_reset").text();
				texto = texto.replace("Cancelar", "Editar");
				$("#direccion_edit_reset").text(texto);
				$("#direccion_edit_reset").attr("onclick", "v_direccion_activarEdicion()");
				$("#direccion_edit_reset").attr("type", "button");
				$("#direccion_edit_reset").attr("class", "button");
				for (var i=0;i<(cantidad_dir);i++){
		 		var miValue = $("#dir_select_status"+i ).val();
					if(miValue == 'A') {
		    			$("#dir_select_status"+i+ " option[value="+miValue+"]").attr("selected",true);
		    			$("#dir_select_status"+i+ " option[value=I]").attr("selected",false);
		    		}
		    		else {
		    			$("#dir_select_status"+i+ " option[value="+miValue+"]").attr("selected",true);
		    			$("#dir_select_status"+i+ " option[value=A]").attr("selected",false);
		    		}
				}
				v_direccion_cargarTabla();
		   }
		  


  	});
}

function v_direccion_cargarTabla() {
	$('#tabla_dir').html('');//vaciamos el div donde se encuentra la tabla

	var parametros = {"re_direccion" : 1};
	$.ajax({
		  type: "POST",
		  url: 'direcciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#tabla_dir').html(result);
                     llamaTodo('direcciones-Tabla');
		   }
  	});
}



//Funcion para resetear los select con sus valores iniciales
function  v_direccion_resetSelects() {
	
	$('.select_llamadas').prop("disabled","disabled");
	$("#editar").removeAttr("disabled");
	$("#direccion_guardar").attr("disabled", true);
	texto = $("#direccion_edit_reset").text();
	texto = texto.replace("Cancelar", "Editar");
	$("#direccion_edit_reset").text(texto);
	$("#direccion_edit_reset").attr("onclick", "v_direccion_activarEdicion()");
		$("#direccion_edit_reset").attr("type", "reset");
		$("#direccion_edit_reset").attr("class", "button button-secondary");

  }  
			
//Funcion para cancelar por la advertencia 
function v_direccion_cancelarAd() {
	$('#alert_css_dir').hide('fast');
	for (var i=0;i<(cantidad_dir);i++){
 		var select = $('#dir_select_status'+i);
		select.val($('option:first', select).val());
	}
	$('.select_llamadas').prop("disabled","disabled");
	$("#editar").removeAttr("disabled");
	$("#direccion_guardar").attr("disabled", true);
	texto = $("#direccion_edit_reset").text();
	texto = texto.replace("Cancelar", "Editar");
	$("#direccion_edit_reset").text(texto);
	$("#direccion_edit_reset").attr("onclick", "v_direccion_activarEdicion()");
}
</script>

<style type="text/css">
 #alert_css_dir {
 	background: #2da5da;
 	border-color: #278AB6;
 	border-radius: 6px;
 	border-style: solid;
 	border-width: 2px;
 	color: white;
 	display: none;
 	padding: 3px;
 }
</style>