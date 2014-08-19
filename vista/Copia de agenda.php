<?php
@session_start();
// variable de session para el usuario logueado
//$_SESSION['USER'] = 'S5702';// S320  GTE01 T5827 CU039	


include('../controlador/c_agenda.php');
include('functions.php');
//Obtenemos todos los datos del usuario
// Declaramos una instacia del Objeto agenda
// dependiendo del tipo de usuatio 
// al crear el objeto se cargan todos sus datos en la propiedades del objeto
$a = new C_agenda($_SESSION['USER']);

// Solicitud para verificar los asesores de un supervisor
// Devuelve una cadena de <option> con los valores
if (isset($_REQUEST['super'])) {
	$a->obtener_asesores($_REQUEST['super']);
	echo $a->get_asesores();
	exit();
}

if (isset($_REQUEST['fecha_acc'], $_REQUEST['asesor_acc'])) {
	$a->acessoDirecto($_REQUEST['fecha_acc'], $_REQUEST['asesor_acc']);
	//echo $c_agenda->get_asesores();
	exit();
}

// Solicitud para verificar las fechas que seran pintadas en el calendario
// devuelve un String con todas las fechas que hay por gestionar
if(isset($_REQUEST['mes'], $_REQUEST['asesor'])) {
	$a->obtener_resumen($_REQUEST['asesor'], $_REQUEST['mes']);
	exit();
}

// Solicitud para obtener todas las actividades de un asesor
if(isset($_REQUEST['fecha_actividad'], $_REQUEST['asesor'])){
   $a->obtener_toda_actividad($_REQUEST['fecha_actividad'], $_REQUEST['asesor']);
   exit();
}

$agenda='


			<form id="gestion_tabla_envio" method="POST" action="?pantalla=expediente"   class="sky-form" />

				<fieldset>
					<table>
						<tr>
							
							<td>Gerente/ Jefe Grupo:</td>
							<td><select style="width: 220px;"  id="ger" disabled > <option>'. $a->get_gerente('NOMBRE').' ('.$a->get_gerente('ID').')</option></select></td>
							<td>Sup:</td>
							<td><select  style="width: 220px;" id="sup" > <option>Supervisor</option></select></td>
							<td>Gestor:</td>
							<td><select  style="width: 220px;" id="ges"> <option>Asesor</option></select></td>
						</tr>

					</table>
					
				</fieldset>
				<!-- Calendario -->
				<fieldset>

							

                    <div class="row" style="width: 255px;margin: 0px 0px 0px 723px;">
                    
                        <section id="container_tabla" class="col col-6" style="margin: 0px 0px 0px -710px; margin: 0px 0px 0px -718px;width: 718px;height: 350px;">
     
                       		';
								// con este procedimiento obtenemos la tabla que maneja la info de las cuentas agendadas	                       			
                       			if(isset($_REQUEST['fecha'], $_REQUEST['asesor'])) {
                       				$a->obtener_gestion($_REQUEST['fecha'], $_REQUEST['asesor']);
                       				$a->validar_gestion($_REQUEST['fecha']);
                       				exit();
                       			}

                       		$agenda .= '


                        </section>
                   
                        <section class="col col-6" style="width: 255px;">
                        	<div  id="fecha_ultima" ></div>
                        	<div>
                            	<div style="height: 236px; width: 226px;" id="inline"></div>
                            </div>
                            <div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div>
	                        <div style="margin: 170px 0px 0px -720px;position: absolute; opacity: 0;width: 680px;" id="motivos_mostrar" >
	                        	
	                        </div>
			                 	
			                 <section >
			                 	<table >
			                 		<tr  >
			                 			<td><button style="padding: 0px 5px;min-width: 85px;height: 25px;font: 10px \'Open Sans\', Helvetica, Arial, sans-serif;" type="button" disabled id="enviar_cuentas" class="button">Chequear</button></td>
			                 			<td><button style="padding: 0px 5px;min-width: 85px;height: 25px;font: 10px \'Open Sans\', Helvetica, Arial, sans-serif;"  type="button" class="button">Enviar Expediente</button></td>
			                 		</tr>
			                 		<tr >
			                 			<td><button style="padding: 0px 5px;min-width: 85px;height: 25px;font: 10px \'Open Sans\', Helvetica, Arial, sans-serif;"  type="button" class="button">Control de Pago</button></td>	
			                 		</tr>
			     
			                 	</table>
			                 	<hr>
		                 	</section>
		                 	<div id="tabla_info" >
	                        	
	                        </div>
                        </section>

                    </div>   
                    
                </fieldset>
                <!-- Tabla de los gestiones -->
                <fieldset style=" height: 300px;">
                <div class="row" style="width: 700px; float: left">
                    <section id="resumen_portafolio" style="float: left" >
                    	<h2>Resúmen del Portafolio</h2>
                    	<hr><br>
                    	';
                       			// En este prodedimiento se carga la tabla que contiene la info del portafolio
                       			if(isset($_REQUEST['asesor2'], $_REQUEST['mes'])){
                       			
                       				$a->obtener_portafolio_resumen($_REQUEST['asesor2'], $_REQUEST['mes']);
                       				exit();
                       			}
                       		$agenda .= '
                    </section>
                   <div>
                </fieldset>
                                         
			</form>
			<form style="display: none" id="form_ocul" action="?pantalla=expediente" method="POST" >
			</form>';
?>




<style>
/*Estilo de la barra informativa encima del menu*/
.cabecera_agenda li{
	list-style: none;
	display: inline-block;
	padding: 2px;
	background: rgb(68, 170, 228);
	color: white;
	font-weight: bold;
	width: 100px;
}


</style>

<script type="text/javascript">
//Variables Globales
var cont_meses = 0;
var fecha_seleccionada = '';// SE NECESITA LA FECHA
var cadena_fecha = '';
var fecha_actual_global = '';
var ultima_fecha_gestion = '';
var ids_color = '';
	
	/**
	 * Carga las fechas con color para saber si existen
	 * cuentas agendadas
	 * @param  {mes para buscar el BD} fecha
	 * @param  {identifica si es el siguiente mes o el anterior} op
	 * @return {cadena de fechas}
	 */	
	

// funcion que se ejecuta al cargar la pagina
$( document ).ready(function() {
		// hacemos uso de php para obtener el tipo de usuario que
		// ha ingresado al sistema
		switch("<?php echo $a->get_tipo();?>"){ // obtenemos el tipo de usuario
			case 'T': 
			case 'C':	<?php 
							$valor = $a->get_asesor('ID'); // obtenemos la ID del asesor
							if(!($valor == '')){ ?>
								var identificador_asesor = "<?php echo $a->get_asesor('ID');?>";
								var nombre_asesor = "<?php echo $a->get_asesor('NOMBRE');?>";
								var ultima_fecha_gestion = "<?php echo $a->fecha_ultima_gestion;?>";
								// colocamos los valores en los select para la permisologia
								$('#ges').html('<option value="'+"<?php echo $a->get_asesor('ID');?>"+'" >'+"<?php echo $a->get_asesor('NOMBRE');?>"+" (<?php echo $a->get_asesor('ID');?>)"+'</option>');
								$('#ges').attr('disabled', '');
								$('#sup').html('<option value="'+"<?php echo $a->get_supervisor('ID');?>"+'"  >'+"<?php echo $a->get_supervisor('NOMBRE');?>"+" (<?php echo $a->get_supervisor('ID');?>)"+'</option>');
								$('#sup').attr('disabled', '');
								$('#ger').html('<option value="'+"<?php echo $a->get_gerente('ID');?>"+'"  >'+"<?php echo $a->get_gerente('NOMBRE');?>"+" (<?php echo $a->get_gerente('ID');?>)"+'</option>');
								$('#ger').attr('disabled', '');
								$('.pintar_calen').remove();$('.pintar_calen_futuro').remove();$('.pintar_calen_disabled').remove();
								var f = new Date();
								var mes = ultima_fecha_gestion.split('/');
								cont_meses = ((parseInt(mes[1]))-(parseInt(f.getMonth())+1));
								var parametros = "mes="+cont_meses+"&"+"asesor="+identificador_asesor;
								$.ajax({
									type: 'POST',
									url: 'agenda.php',
									data: parametros,
									success: function(result){
										 //alert(result);
										var apli_monto = result.split('[SEPAR]');
										fechas_agendadas = apli_monto[1].split('[SEPAR2]');
											$('#monto_apli').val(apli_monto[0]);

										ultima_fecha_gestion = fechas_agendadas[0];
										//alert(ultima_fecha_gestion);
							  			$('#inline').datepicker('setDate', ultima_fecha_gestion);
										$('#fecha_ultima').html('Fecha en atraso: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
										//alert(fechas_agendadas[1]);
										color_cal(fechas_agendadas[1]);
										var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
										//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
										recargar_portafolio( $("#ges").val());
									}
								});
							<?php } ?>
						break;
			case 'S': <?php 
							$valor = $a->get_supervisor('ID'); // Obtenemos la ID de supervisor
							if(!($valor == '')){ ?>
						var identificador_supervisor = "<?php echo $a->get_supervisor('ID');?>";
						var nombre_supervisor = "<?php echo $a->get_supervisor('NOMBRE');?>";
						// colocamos los valores en los select para la permisologia
						$('#ges').html("<?php echo $a->get_asesores();?>");
						$('#sup').html('<option value="'+"<?php echo $a->get_supervisor('ID');?>"+'" >'+"<?php echo $a->get_supervisor('NOMBRE');?>"+" (<?php echo $a->get_supervisor('ID');?>)"+'</option>');
						$('#sup').attr('disabled', '');
						$('#ger').html('<option value="'+"<?php echo $a->get_gerente('ID');?>"+'"  >'+"<?php echo $a->get_gerente('NOMBRE');?>"+" (<?php echo $a->get_gerente('ID');?>)"+'</option>');
						$('#ger').attr('disabled', '');
						//ver_asesores();
			//ver_actididades_asesores();
			//alert($("#sup").val());
			cargar_cal('A',$("#sup").val());
			recargar_portafolio( $("#sup").val());
						//ver_actididades_asesores(); //activar
						<?php } ?>
						break;
			case 'G':   <?php 
							$valor = $a->get_gerente('ID'); // Obtenemos la ID de gerente
							if(!($valor == '')){ ?>
						var identificador_gerente = "<?php echo $a->get_gerente('ID');?>";
						var nombre_gerente = "<?php echo $a->get_gerente('NOMBRE');?>";
						// colocamos los valores en los select para la permisologia
						$('#ges').html("<?php echo $a->get_asesores();?>");
						$('#sup').html("<?php echo $a->get_supervisores();?>");
						$('#ger').html('<option value="'+"<?php echo $a->get_gerente('ID');?>"+'"  >'+"<?php echo $a->get_gerente('NOMBRE');?>"+" (<?php echo $a->get_gerente('ID');?>)"+'</option>');
						$('#ger').attr('disabled', '');
						<?php } ?>
						break;
		}


		// Inline datepicker
		// Funcio para el calendario
		// estan funcion se encarga de asignar una acccion al darle
		// clic al calendario y de gestionar la informacion que sera mostrada
                
		$('#inline').datepicker({
			dateFormat: 'dd/mm/yy',
			prevText: '<i class="icon-chevron-left"></i>',
			nextText: '<i class="icon-chevron-right"></i>',
			onSelect: function( selectedDate ) {
				
				// verificamos si el valor del select de los asesore 
				// está en seleccione para mostrar los diferentes asesores
				// al supervisor logueado
				if($('#ges').val() == $('#sup').val()  || $('#ges').val() == ''){ // si no se ha seleccionado ningun asesor
					
					recargar_portafolio( $("#sup").val());
					cargar_cal('A', $("#sup").val());

					ver_actididades_asesores();
					//alert(ultima_fecha_gestion);
					validar_fecha_gestion(ultima_fecha_gestion, selectedDate);

					fecha_seleccionada = selectedDate;
				}
				else{
					// muestra la informacion de un solo asesor 
					// para ver las cuentas que posee agendadas en el mes
					recargar_portafolio( $("#ges").val(), cont_meses);
					recargar(selectedDate, $("#ges").val());// Aqui va el ID del usuario
					cargar_cal('A', $("#ges").val());
					validar_fecha_gestion(ultima_fecha_gestion, selectedDate);
					fecha_seleccionada = selectedDate;
				}
			}
		});
              
		 fecha_actual_global = $('#inline').datepicker( "getDate" ).getDate();
			fecha_actual_global = fecha_actual_global+'/'+ $('#inline').datepicker( "getDate" ).getMonth();
			fecha_actual_global = fecha_actual_global+'/'+ $('#inline').datepicker( "getDate" ).getFullYear();

		/**
		 * Valida si se puede gestionar una cuenta 
		 * @param  {[type]} fecha
		 * @return {[type]}
		 */
		function validar_fecha_gestion(fecha_ultima, fecha) {
			//alert(fecha_ultima);
			//alert(fecha);

			//var fecha1 = new Date(fecha_ultima);
			//var fecha2 = new Date(fecha);
			
			// validamos que la ultima fecha en atraso tenga prioridad 
			// sobre las demas.. forzando al usuario a gestionar esa cuenta 
			// unicamente 
			if(fecha_ultima != fecha){
				$('#enviar_cuentas').attr("disabled", 'disabled');
			}else{
				$('#enviar_cuentas').removeAttr("disabled");
				// Habilitamos el boton para enviar la informacion a la vista de expediente
			}
		}
		
		/**
		 * Evento que funciona para efectuar una accion 
		 * al cambiar el valor en el select de sup
		 * @return {[type]}
		 */
		$( "#sup" ).change(function() {
			ver_asesores();

			ver_actididades_asesores();
			//alert($("#sup").val());
			cargar_cal('A',$("#sup").val());
			alert(ultima_fecha_gestion);
			recargar_portafolio( $("#sup").val());
			alert('as');

			var parametros = "mes="+cont_meses+"&"+"asesor="+$("#sup").val();
				$.ajax({
					type: 'POST',
					url: 'agenda.php',
					data: parametros,
					success: function(result){
						 //alert(result);
						var apli_monto = result.split('[SEPAR]');
						fechas_agendadas = apli_monto[1].split('[SEPAR2]');
							$('#monto_apli').val(apli_monto[0]);

						ultima_fecha_gestion = fechas_agendadas[0];
						//alert(ultima_fecha_gestion);
						
						$('#fecha_ultima').html('Fecha en atraso: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
						//alert(fechas_agendadas[1]);
						color_cal(fechas_agendadas[1]);
						var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
						//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
					}
				});

		});
		/**
		 * Evento que se encarga de actualizar las fechas del calendario
		 * por el asesor
		 * @return {[type]}
		 */
		$( "#ges" ).change(function() {

			if($( "#ges" ).val() == $( "#sup" ).val()){
				var fecha = ultima_fecha_gestion;
				$('#inline').datepicker('setDate', fecha);
				ver_actididades_asesores();
				cargar_cal('A',$("#sup").val());
				//alert('asd');

			}
			else{
				var fecha = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
				var parametros = "fecha="+fecha+"&"+"asesor="+$( "#ges" ).val();
				
				$.ajax({
					type: 'POST',
					url: 'agenda.php',
					data: parametros,
					success: function(result){
						//alert(result);
						resultado = result.split('[SEPAR]'); // separamos la infromacion de la tabla y la fecha ultima de gestion
				  		ultima_fecha_gestion = resultado[0];
				  		//alert(ultima_fecha_gestion);
						//color_cal(result);
						var f = new Date();
						var mes = ultima_fecha_gestion.split('/');
						cont_meses = ((parseInt(mes[1]))-(parseInt(f.getMonth())+1));

			  			$('#inline').datepicker('setDate', ultima_fecha_gestion);
			  			fecha = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
			  			//alert(fecha);
			  			recargar(fecha, $("#ges").val());
			  			//alert(ultima_fecha_gestion);
			  			cargar_cal('A',$("#ges").val());
			  			//recargar_portafolio( $("#sup").val());
					}
				});
				
			}
		});



		/**
		 * Evento que funciona para enviar los checks activados a expediente
		 * @return {[type]}
		 */
		$( "#enviar_cuentas" ).click(function() {
		    enviarcuentas(document.getElementsByName('checkcuentas[]'));
		});

	});

	function cargar_cal(op, asesor) {
		if(asesor==$('#sup').val())
			asesor=$('#sup').val();
		// las letras N, P y A son valores que poseen las flechas
		// del calendario para determinar si da click en Next o Previous

		switch(op){
			// SI SE DA CLICK EN >			
			case 'N':	$('.pintar_calen').remove();
                                        $('.pintar_calen_futuro').remove();
                                        $('.pintar_calen_disabled').remove();
						cont_meses++;
						var parametros = "mes="+cont_meses+"&"+"asesor="+asesor;
						$.ajax({
							type: 'POST',
							url: 'agenda.php',
							data: parametros,
							success: function(result){
									 //alert(result);
								var apli_monto = result.split('[SEPAR]');
								fechas_agendadas = apli_monto[1].split('[SEPAR2]');
									$('#monto_apli').val(apli_monto[0]);

								ultima_fecha_gestion = fechas_agendadas[0];
								$('#fecha_ultima').html('Fecha en atraso: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
								//alert(fechas_agendadas[1]);
								color_cal(fechas_agendadas[1]);
								var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
								//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
							}
						});
					  	//alert(cont_meses);
						break;
			// SI SE DA CLICK EN <			
			case 'P':	$('.pintar_calen').remove();$('.pintar_calen_futuro').remove();$('.pintar_calen_disabled').remove();
						cont_meses--;
						var parametros = "mes="+cont_meses+"&"+"asesor="+asesor;
						$.ajax({
							type: 'POST',
							url: 'agenda.php',
							data: parametros,
							success: function(result){
								 //alert(result);
								var apli_monto = result.split('[SEPAR]');
								fechas_agendadas = apli_monto[1].split('[SEPAR2]');
									$('#monto_apli').val(apli_monto[0]);

								ultima_fecha_gestion = fechas_agendadas[0];
								$('#fecha_ultima').html('Fecha en atraso: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
								//alert(fechas_agendadas[1]);
								color_cal(fechas_agendadas[1]);
								var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
								//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
							}
						});
					  	//alert(cont_meses);
						break;
			// SI ELEGIMOS UNA FECHA DEL CALENDARIO
			// LLAMA A LA FUNCION REMOVE PARA ELIMINAR TODOS LOS COLORES 
			// PARA LUEGO COLOREAR DE NUEVO 
			case 'A':	$('.pintar_calen').remove();$('.pintar_calen_futuro').remove();$('.pintar_calen_disabled').remove();
						var parametros = "mes="+cont_meses+"&"+"asesor="+asesor;
						$.ajax({
							type: 'POST',
							url: 'agenda.php',
							data: parametros,
							success: function(result){
								 //alert(result);
								var apli_monto = result.split('[SEPAR]');
								fechas_agendadas = apli_monto[1].split('[SEPAR2]');
									$('#monto_apli').val(apli_monto[0]);

								ultima_fecha_gestion = fechas_agendadas[0];
								//alert(ultima_fecha_gestion);
								
								$('#fecha_ultima').html('Fecha en atraso: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
								//alert(fechas_agendadas[1]);
								color_cal(fechas_agendadas[1]);
								var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
								//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
							}
						});
					  	//alert(cont_meses);
						break;
		}



	}
	/**
	 * Funcion para colorear el calendario
	 * @param  {string} cadena_fechas
	 * @return {crea varios div en las fechas con cuentas}
	 */
	function color_cal(cadena_fechas) {
		/**
		 * Partimos la cadena para obtener las fechas en un arreglo
		 */
		var bool = false;
		// bUSCAMOS SI LA Cadena posee un simbolo % que usamos como separador
		// vease archivo c_agenda 587 para mayor detalle
		// si lo posee bool sera true
		if(cadena_fechas.indexOf('%') != -1){
			bool = true;
		}

		var f = new Date();
		// Obtenemos la fecha actual formada con la variable f
		var fecha_act 		= (f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
		// separamos las cadenas con el separador %
		var fechas_arr 		= cadena_fechas.split('%');
		// obtenemos las fechas que poseen cuentas agendadas 
		var arr_fechas 		= fechas_arr[0].split('||');
		// En caso de que existan fechas festivas en el mes
		// colocamos las fechas en la variable fechas_feriados
		if(bool)
			var fechas_feriados = fechas_arr[1].split('||');
		// Variable para formar el id para identificar en el calendario la fecha
		var fecha_selected = ''; 
		var fecha_sel_cad = '';
		ids_color = '';

		// se forman los ID de los identificadores de las fechas del calendario para
		// luego buscar y colorear la fecha en la cual existen cuentas agendadas
	
		for(x=0;x<(arr_fechas.length)-1;x++){

			fecha_selected = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+arr_fechas[x][5]+'20'+arr_fechas[x][6]+arr_fechas[x][7];
			if(fecha_selected[0]==0 || fecha_selected[3]==0){
				if(fecha_selected[0]==0 && fecha_selected[3]==0) {
					fecha_selected = 'f_'+arr_fechas[x][1]+((arr_fechas[x][4])-1)+'20'+arr_fechas[x][6]+arr_fechas[x][7]+'_f'; //Formamos el ID 
				}
				else {
					if(fecha_selected[0]==0)
						fecha_selected = 'f_'+arr_fechas[x][1]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+'20'+arr_fechas[x][6]+arr_fechas[x][7]+'_f';//Formamos el ID 
					else
						fecha_selected = 'f_'+arr_fechas[x][0]+arr_fechas[x][1]+((arr_fechas[x][4])-1)+'20'+arr_fechas[x][6]+arr_fechas[x][7]+'_f';//Formamos el ID 
				}
			}
			else {
				fecha_selected = 'f_'+arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+'20'+arr_fechas[x][6]+arr_fechas[x][7]+'_f';//Formamos el ID
			}
			

			//alert(arr_fechas[x]+' > '+'10/03/14');
			
			//alert(fecha_selected); //Depuracion
			//fecha_sel_cad = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+arr_fechas[x][5]+'20'+arr_fechas[x][6]+arr_fechas[x][7];
			/**
			 * Proceso para crear el div con el color para pintar en 
			 * el calendario
			 */
			var nuevo = document.createElement('div');
			var padre = document.getElementById(fecha_selected);
			ids_color = ids_color+fecha_selected+'||';

var fecha = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+arr_fechas[x][4]+arr_fechas[x][5]+'1'+arr_fechas[x][6]+arr_fechas[x][7];
			if(compareDate(fecha))
				nuevo.className = 'pintar_calen_futuro';
			else
				 nuevo.className = 'pintar_calen';//Colocamos la clase con el color y margin
			nuevo.name = 'fecha_agendada';
			padre.appendChild(nuevo); //se implementa el div en el calendario
		}
		// Si existen fechas festivas entramos en el if
		// para formar las ID que identifican las fechas en el calendario
		if(bool)
			// Repetimos el mismo proceso que usamos para crear los ID de la fechas de 
			// Cuentas agendadas
			for(x=0;x<(fechas_feriados.length)-1;x++){
				fecha_selected = fechas_feriados[x][0]+fechas_feriados[x][1]+fechas_feriados[x][2]+fechas_feriados[x][3]+((fechas_feriados[x][4])-1)+fechas_feriados[x][5]+'20'+fechas_feriados[x][6]+fechas_feriados[x][7];
				if(fecha_selected[0]==0 || fecha_selected[3]==0){
					if(fecha_selected[0]==0 && fecha_selected[3]==0) {
						fecha_selected = 'f_'+fechas_feriados[x][1]+((fechas_feriados[x][4])-1)+'20'+fechas_feriados[x][6]+fechas_feriados[x][7]+'_f'; //Formamos el ID 
					}
					else {
						if(fecha_selected[0]==0)
							fecha_selected = 'f_'+fechas_feriados[x][1]+fechas_feriados[x][3]+((fechas_feriados[x][4])-1)+'20'+fechas_feriados[x][6]+fechas_feriados[x][7]+'_f';//Formamos el ID 
						else
							fecha_selected = 'f_'+fechas_feriados[x][0]+fechas_feriados[x][1]+((fechas_feriados[x][4])-1)+'20'+fechas_feriados[x][6]+fechas_feriados[x][7]+'_f';//Formamos el ID 
					}
				}
				else {
					fecha_selected = 'f_'+fechas_feriados[x][0]+fechas_feriados[x][1]+fechas_feriados[x][3]+((fechas_feriados[x][4])-1)+'20'+fechas_feriados[x][6]+fechas_feriados[x][7]+'_f';//Formamos el ID
				}
				//alert(fecha_selected);
				//alert(fecha_selected); //Depuracion
				//fecha_sel_cad = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+arr_fechas[x][5]+'20'+arr_fechas[x][6]+arr_fechas[x][7];
				/**
				 * Proceso para crear el div con el color para pintar en 
				 * el calendario las fechas festivas
				 */
				var nuevo = document.createElement('div');
				var padre = document.getElementById(fecha_selected);
				$('#'+fecha_selected).css('pointer-events', 'none'); // Funciona para deshabilitar los dias feriados
				nuevo.className = 'pintar_calen_disabled'; //Colocamos la clase con el color y margin
				nuevo.name = 'fecha_agendada';
				padre.appendChild(nuevo); //se implementa el div en el calendario
			}
	}
	/**
	 * Esta funcion se encarga de listar las actividades de los asesores
	 * a el supervisor logueado
	 * @return {[type]}
	 */
	function ver_actididades_asesores() {
		var id_supervisor = $('#sup').val();
		var fecha = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
		//alert(fecha);
		var parametros = "fecha_actividad="+fecha+"&"+"asesor="+id_supervisor;
		var contenido;
		$.ajax({
			
			type: "POST",
			url: 'agenda.php',
			data: parametros,
		  	success: function(result){
		  	//alert(ultima_fecha_gestion);
		  		// Sustituimos el contenido de container_tabla con el resultado
		  		var valores = result.split('[SEPAR2]');
		  		$('#container_tabla').html(valores[0]);
		  		
		  		$('#motivos_mostrar').html(valores[1]);
		  		$('#tabla_info').html(valores[2]);
		  		
		  		llamaTodo('Tabla_actividades');
		  	}
		});
	}

	/**
	 * Esta Función se encarga de plasmar las cuentas agendadas
	 * por los asesores
	 * @return {código completo de la tabla para luego reemplazar con 
	 * el ID }
	 */
	function ver_asesores() {

		var id_supervisor = $('#sup').val();
		//alert(id_supervisor);
		var parametro = "super="+id_supervisor;

		$.ajax({
			
			type: "POST",
			url: 'agenda.php',
			data: parametro,
		  	success: function(result){
		  		$('#ges').html(result);
		  	}
		});
		
		
	}

	
	 //Guardamos la ultima fecha que posee gestion
	/**
	 * Esta funcion se encarga de mostrar la tabla con toda la informacion
	 * obtenida de la consulta a la base de datos. 
	 * dependiendo de la fecha y el codigo del asesor buscamos la informacion y 
	 * la reeplazamos con la nueva informacion obtenida
	 *
	 * Recibe un cadena con la informacion del asesor y la ultima fecha
	 * que posee para gestionar
	 * @param  {String} fecha
	 * @param  {String} asesor
	 * @return {Codigo de la tabla con la informacion del asesor}
	 */	
	function recargar(fecha, asesor) {
		// premaramos una cadena para enviar la informacion
		// a php
		var parametros = "fecha="+fecha+"&"+"asesor="+asesor;
		var contenido;
		$.ajax({
			type: "POST",
		  	url: 'agenda.php',
		  	data: parametros, // enviamos la cadena que formamos con los parametros
		  	success: function(result){
		  		//alert(result);
		  		resultado = result.split('[SEPAR]'); // separamos la infromacion de la tabla y la fecha ultima de gestion
		  		contenido = resultado[1].split('[SEPAR2]');
		  		$('#container_tabla').html(contenido[0]);
		  		$('#motivos_mostrar').html(contenido[1]);
		  		$('#tabla_info').html(contenido[2]);
		  		ultima_fecha_gestion = resultado[0];
		  		llamaTodo('Tabla_actividades');
		  	}
		});
	}


	function posicionarCal(fecha, asesor) {

		var parametros = "fecha_acc="+fecha+"&"+"asesor_acc="+asesor;

		$.ajax({
			type: "POST",
		  	url: 'agenda.php',
		  	data: parametros,// enviamos la informacion
		  	success: function(result){

		  		var contenido = result.split(',');
		  		var datos = '';
		  		for(x=0;x<(contenido.length)-1;x++){
		  			datos = datos + '<input checked type="checkbox" name="checkcuentas[]" value="'+contenido[x]+'">';
		  		}
		  		//datos = datos + '<button style="padding: 0px 10px;height: 40px;font: 13px/21px \'Open Sans\', Helvetica, Arial, sans-serif;" type="button" disabled id="enviar_cuentas" class="button">Chequear</button>';
		  		$('#form_ocul').html(datos);
		  		var data = document.getElementsByName('checkcuentas[]');


		  		var count = 0;
				
				

				 for (var i=0; i < data.length; i++) {
		           	if (data[i].checked) {    
		               CUENTAS[count] = data[i].value;
		               count++;
		            }
		         }
		         //alert(CUENTAS.join());
		         if(count>0)
		         	$('#form_ocul').submit();
		         else
		         	alert('No existen cuentas seleccionadas para gestionar');


		  	}


		});

		//$('#form_ocul').submit();


		

	}


	/**
	 * Esta funcion se encarga de cargar el portafolio
	 * con la informacion obtenida de la consulta a la base de datos
	 * para mostrar un resumen de la cartera que le fue asignada 
	 * a un asesor
	 * 
	 * @param  {String} fecha
	 * @param  {String} asesor
	 * @return {Codigo de la tabla con la informacion resultante de la consulta}
	 */
	function recargar_portafolio( asesor, mes) {
		// creamos la cadena con los nombres y valores de la informacion que se
		// enviaran a la base de datos para su consulta
		var parametros = "asesor2="+asesor+"&"+"mes="+cont_meses;

		$.ajax({
			type: "POST",
		  	url: 'agenda.php',
		  	data: parametros,// enviamos la informacion
		  	success: function(result){
		  		//alert(result);
		  		var contenido = result.split('[SEPAR]');
		  		//alert(contenido[1]);
		  		//alert(contenido[0]);
		  		$('#resumen_portafolio').html('<h2>Resúmen del Portafolio</h2><hr><br>'+contenido[1]);
		  		$('#monto_apli').html(contenido[0]);

		  		llamaTodo('Tabla_resumen');//poner el ID de la tabla de resumén
		  	}
		});

		
	}

	

	
	var Ultimo_motivo_mostrado = 0
	/**
	 * Esta funcion se encarga de mostrar los motivos de cada 
	 * caso agendado en una fecha determinada por el asesor 
	 * 
	 * @param  {String} motiv
	 */
	function ver_motivo(motiv){
		//alert(Ultimo_motivo_mostrado);
		$('#motivos_mostrar').css('opacity', '1');
		$('#identi').css('opacity', '1');

		var motivo = '#motivo_'+motiv;
		//alert(motivo);
		//alert(motivo);
		// Verificamos el ultimo motivo para ocultarlo
		$(Ultimo_motivo_mostrado).css('display', 'none');
		// mostramos el nuevo motivo
		$(motivo).css('display', 'block');
		// ahora nuestro ultimo motivo sera el nuevo
		Ultimo_motivo_mostrado = '#motivo_'+motiv;
	}

	/**
	 * Esta funcion se encarga de seleccionar los check que 
	 * posee la tabla para poder enviar todos a expediente
	 * 
	 * @param  {[type]} checksTodos
	 * @return {[type]}
	 */
	function enviarcuentas(checksTodos) {
		//alert(checksTodos);
		var count = 0;
		
		

		 for (var i=0; i < checksTodos.length; i++) {
           	if (checksTodos[i].checked) {    
               CUENTAS[count] = checksTodos[i].value;
               //alert(CUENTAS[count]);
               count++;
            }
         }
         //alert(CUENTAS.join());
         if(count>0)
         	$('#gestion_tabla_envio').submit();
         else
         	alert('No existen cuentas seleccionadas para gestionar');
	}




</script>

<style>

/* Estilo para el boton chequear cuando esta deshabilitado*/
#enviar_cuentas:disabled{
	background: gray;
}

/* Estilo para marcar la fecha actual en el calendario*/
.ui-state-highlight {
	background: green !important;
	color: white      !important;
}
.ui-datepicker-inline{
	width: 100%;
}
/*Estilo para los select de la tabla*/
#last_pos_table select {
	width: 80px;
}

/*Estilo para colorear los fines de semana en el calendario*/ 
.ui-datepicker-week-end{
	background: rgb(210, 210, 210);
}
/*Estilo para colocar el tamño de la tabla */
.dataTables_scrollBody {
	height: 305px !important;
}
/*Estilo para definir el tamaño de la tabla*/
#resumen_portafolio .dataTables_scrollBody {
	height: 150px !important;
}
.ui-datepicker-inline  {
	height: auto;
}
/*Estilo asigna un color dependiendo de la fecha que se obtuvo 
en la consulta a la base de datos y pintar la fecha*/
._retraso {
	background: red !important;
}
._futuro {
	background: blue;
}
/**
 * Quita el evento de los fines de semanas
 */
._noevent {
	pointer-events: none;
}
/**
 * Pinta en el calendario las fechas donde se existen cuentas por
 * gestionar
 */
.pintar_calen {
	opacity: 0.4;
	background: red;
	height: 27px;
	position: absolute;
	width: 31px;
	margin: -27px 0px 0px 0px;
}

.pintar_calen_futuro{
	opacity: 0.4;
	background: rgb(0, 117, 243);
	height: 27px;
	position: absolute;
	width: 31px;
	margin: -27px 0px 0px 0px;
}
/*Para pintar las fechas que no estan disponibles*/
.pintar_calen_disabled {
	opacity: 0.4;
	background: rgb(143, 143, 143);
	height: 27px;
	position: absolute;
	width: 31px;
	margin: -27px 0px 0px 0px;
	pointer-events:none;
}
.ui-state-active {
	background: rgba(0, 0, 0, 0.6) !important;
	color: white !important;
}
#Tabla_resumen_filter {
	display: none;
}

#motivos_mostrar{
	height: auto;
width: 239.5px;
min-height: 20px;
background: rgb(255, 255, 255);
color: rgb(133, 133, 133);
border-width: 1.7px;
border-color: rgb(230, 230, 230);
border-style: solid;
padding: 0px 0px 0px 10px;
box-shadow:  0px 8px 10px rgba(0, 30, 58, 0.22);
}
</style>