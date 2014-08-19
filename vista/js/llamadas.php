<?php
require_once("../controlador/c_llamada.php");
$llamada = new C_llamada();
//Verificamos las acciones del usuario



$llamadas ='
			<!-- Formulario de llamadas -->
			<div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-icons">
				<input type="radio" name="sky-tabs" id="llamadas1" class="sky-tab-content-1">
				<label for="llamadas1"><span><span><i class="fa fa-bolt"></i>LLAMAR</span></span></label>
				<ul>
					<li class="sky-tab-content-1">					
						<form id="form_llamadas" action="#">
							<fieldset>					
								<div class="row">
									<section class="col col-5">				
										<label class="label"></label>
										<label class="toggle" ><label class="toggle state-success"><input id="editar" type="checkbox" name="checkbox-toggle"  onclick="verifica()"><i></i>Editar</label></label>
										<div id="alert_css">¿Desea guardar los cambios? <button type="button" class="confirm_button" onclick="respuesta()">Aceptar</button><button type="button" class="cancele_button" onclick="cancelar_edit()" >Cancelar</button></div>
										<div class="note note-success">Telèfonos asociados.</div>
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
							<!-- <table  border="1" cellpadding="0" cellspacing="0" width="100%"> -->
							<div>
								<table border="2">
									<thead>
										<tr style="background-color:#2da5da; border: 1px solid;">
										<th width="100">Código</th>
										<th width="100">Teléfono</th>
										<th width="20px">Estatus</th>
										</tr>
									</thead>
									<tbody>
										
								';
                                
                                $datos = $llamada->obtener_telefonos('525852');//Se coloca el id_persona que este en session
                                $x = 0;
                                $combinacion = array();
                                $numeros = array();
                                foreach($datos as $r){
                                	//Por los momentos no se muestra el $r[persona]
                                	$sele = false; 
                                	if($r['STATUS_TELEFONO']=='A')
                                		$sele = true;

                                	$llamadas = $llamadas."<tr style=\"background-color:#f0ffff; width: 25;\"><td>".$r['COD_AREA']."</td><td>".$r['TELEFONO']."</td><td><label class='select'><select id=\"select_status$x\" class='select_llamadas' disabled> "; 
                                	if($sele)
                                		$llamadas = $llamadas."<option selected>A</option><option>I</option>";
                                	else
                                		$llamadas = $llamadas."<option>A</option><option selected>I</option>";

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
                                $llamada->set_numeros($numeros);
                                $llamada->set_combina($combinacion);//registramos la combinacion en el objeto
		                           
	                                    
$llamadas = $llamadas.'
		                            </table>    
							</div>  
						</div>
					</fieldset>						
				</form>
		                                </table>
			                        </label>
								</section>
				                </div>
							</fieldset>						
						</form>
						
					</li>
				</ul>
			</div>
		<!--/ fin formulario de llamadas -->';
		if(isset($_POST['cadena'])){
		  $llamada->guardar_status($_POST['cadena']);//LLamamos el metodo para guardar en la base de datos
		}
		echo "<script> var cantidad = $x; </script>";


?>
                     
<script type="text/javascript">

//Función para habilitar la edición de los estatus de los números de teléfonos
function verifica () {
 	if($("#editar").is(':checked'))//Verificamos si el check está en cheked
	 	$('.select_llamadas').prop("disabled","");  //Habilitamos los select 
    else{
    	$('#alert_css').show('fast');
    		$('.select_llamadas').prop("disabled","disabled");
    	  //Deshabilitamos los select's
	}
}

function respuesta() {
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
     alert(result);
   }
  
});

	
    
    
			
	
} 

function cancelar_edit() {
	$('#alert_css').hide('fast');
	$('.select_llamadas').prop("disabled","");
	$("#editar").prop("checked", true);

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