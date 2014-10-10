<?php
@session_start();
// variable de session para el usuario logueado
//$_SESSION['USER'] = 'GTE01';// S320  GTE01 T5827 CU039	


include($_SESSION['ROOT_PATH'].'/controlador/c_agenda.php');
require_once ('functions.php');
//Obtenemos todos los datos del usuario
// Declaramos una instacia del Objeto agenda
// dependiendo del tipo de usuatio 
// al crear el objeto se cargan todos sus datos en la propiedades del objeto
$a = new C_agenda($_SESSION['USER']);


if (isset($_POST['validarFecha'])){
    
    echo $a->validar_gestion($_POST['fecha_ultima'], $_POST['fecha']);
    exit();
    
}


// Solicitud para verificar los asesores de un supervisor
// Devuelve una cadena de <option> con los valores
if (isset($_REQUEST['super'])) {
	$a->obtener_asesores($_REQUEST['super']);
	echo $a->get_asesores();
	exit();
}

if (isset($_REQUEST['coor'])) {
   	$a->obtener_supervisores($_REQUEST['coor']);
	echo $a->get_supervisores();
	exit();
}



if (isset($_REQUEST['fecha_acc'], $_REQUEST['asesor_acc'])) {
	$a->acessoDirecto($_REQUEST['fecha_acc'], $_REQUEST['asesor_acc']);
	//echo $c_agenda->get_asesores();
	exit();
}

// Solicitud para verificar las fechas que seran pintadas en el calendario
// devuelve un String con todas las fechas que hay por gestionar
if(isset($_REQUEST['mes'], $_REQUEST['asesor'], $_REQUEST['p'], $_REQUEST['ulti'])) {
	$a->obtener_resumen($_REQUEST['asesor'], $_REQUEST['mes'], $_REQUEST['p'], $_REQUEST['ulti']);
	exit();
}

// Solicitud para obtener todas las actividades de un asesor
if(isset($_REQUEST['fecha_actividad'], $_REQUEST['asesor'])){
   $a->obtener_toda_actividad($_REQUEST['fecha_actividad'], $_REQUEST['asesor']);
   exit();
}

$agenda='
    
   

            <div>
             <div id=\'popControlPago\' style="display: none; left: 0; position: absolute; top: 0; width: 100%; z-index: 1001;">
                <div style="padding: 0px;" class="content-popup">
                  <div class="close"><a href="#" id="closePopControlPago"><img style="margin-top: 10px;" src="img/close.png"/></a></div>
                  <form style="heigth: 100%;" class="sky-form">
                  <fieldset>
                  <label class="input">
                    Desde:
                    <input id="fi" value="'.date('Y-m-01').'" type="date" />
                        <br>
                         </label>
                         <label class="input">
                    Hasta:
                    <input id="ff" value="'.date('Y-m-d').'" type="date" />
                    <input style="display:none;" type="text" name="usuario_selec" id="usuario_selec" value="'.$_SESSION['USER'].'"/>
                    <input style="display:none;" type="text" name="ase" id="ase" value="'.$_SESSION['nombre_user'].'"/>
                  <br>
                  <a href="#" onclick="window.open(\'../Vista/Reportes/ReporteControlPagos.php?fi=\'+$(\'#fi\').val()+\'&ff=\'+$(\'#ff\').val()+\'&us=\'+$(\'#usuario_selec\').val()+\'&ase=\'+$(\'#ase\').val(),\'_blank\'); $(\'#closePopControlPago\').click();">Crear Reporte</a>
                  <br><br>
                  
                  </fieldset>
                  </form>
                

                </div>
            </div>
            </div>





<form id="gestion_tabla_envio" name="gestion_tabla_envio" method="POST" action="?pantalla='.md5('expediente').'"   class="sky-form" />
            <div style="overflow: auto; width: 100%">
            
	<fieldset>
		<table style="width: 100%;">
			<tr>	
				
                <td id="o">{tab2}:</td>
				<td><select style="width: 220px;"  id="coor" > <option>Coodinador</option></select></td>
				<td id="s">{tab3}:</td>
				<td><select  style="width: 220px;" id="sup" > <option>Supervisor</option></select></td>
				<td id="a">{tab4}:</td>
				<td><select  style="width: 220px;" id="ges"> <option>Asesor</option></select></td>
			</tr>
		</table>
		
	</fieldset>

<fieldset>
	<div id="cont_tabla" style="" class="col col-8">
		
		<div id="container_tabla" style="width: 100%;height: 370px;">
       		';
				// con este procedimiento obtenemos la tabla que maneja la info de las cuentas agendadas	                       			
       			if(isset($_REQUEST['fecha'], $_REQUEST['asesor'])) {              
       				$a->obtener_gestion($_REQUEST['fecha'], $_REQUEST['asesor']);
       				$a->validar_gestion($_REQUEST['fecha'], date ("d/m/Y"));    
       				exit();
       			}
       		$agenda .= '
        </div>
        <div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div>
            <div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" >
        </div>
        <div class="progreso"></div>
	</div>
	<div style="" class="col col-7">
		
		<div style="width: 255px;">
        	<div  id="fecha_ultima" ></div>
        	<div>
            	<div style="height: 236px; width: 226px;" id="inline"></div>
            </div>
            
           
             	
             <section >
             	<table >
             		<tr  >
             			';
                // {cambio} luis peña 16/04/2014
                //<td><button title="Ver Expediente"  type="button" disabled id="enviar_cuentas"><img width="35px" heigth="35px" src="img/ico/irExpediente.ico"/></button></td>
                   $agenda .= '   <td><button title="Ver Expediente"  type="button" id="enviar_cuentas"><img width="35px" heigth="35px" src="img/ico/irExpediente.ico"/></button></td>
             			<td><button title="Reporte Control de Pago" onclick="crearReporteControlPago();" type="button"><img width="35px" heigth="35px" src="img/ico/pdf.ico"/></button></td>
             		</tr>
             	</table>
             	<hr>
         	</section>
                       
         	<div id="tabla_info" >
            	
            </div>
        </div>

	</div>

</fieldset>
<fieldset>
	<div style=" height: 370px;" class="col col-8">
		<section id="resumen_portafolio"  >
        	
        	<br>
        	';
	   			// En este prodedimiento se carga la tabla que contiene la info del portafolio
	   			if(isset($_REQUEST['asesor2'], $_REQUEST['mes'])){
	   			
	   				$a->obtener_portafolio_resumen($_REQUEST['asesor2'], $_REQUEST['mes']);
	   				exit();
	   			}
	   		$agenda .= '
        <div style="margin: 0px" class="progreso"></div>
        </section>
		
	</div>
	<div style=" height: 370px;" class="col col-6">
		<div id="resumen_estadi" ><div style="margin: 0px" class="progreso"></div></div>

	</div>

</fieldset>

                    
</form>
          
<form style="display: none" id="form_ocul" action="?pantalla='.md5('expediente').'" method="POST" >
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
var fecha_ulti = true;
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
   
    if ($('#inline').length){
        cambiarTitulo('Agenda');
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
                                $('#a').attr('hidden', '');
                                $('#ges').attr('hidden', '');
								$('#sup').html('<option value="'+"<?php echo $a->get_supervisor('ID');?>"+'"  >'+"<?php echo $a->get_supervisor('NOMBRE');?>"+" (<?php echo $a->get_supervisor('ID');?>)"+'</option>');
							        $('#sup').attr('disabled', '');
                                $('#sup').attr('hidden', '');
                                $('#s').attr('hidden', '');
                                $('#coor').html('<option value="'+"<?php echo $a->get_coordinador('ID');?>"+'"  >'+"<?php echo $a->get_coordinador('NOMBRE');?>"+" (<?php echo $a->get_coordinador('ID');?>)"+'</option>');
								$('#coor').attr('disabled', '');
                                $('#coor').attr('hidden', '');
                                $('#o').attr('hidden', '');
								$('#ger').html('<option value="'+"<?php echo $a->get_gerente('ID');?>"+'"  >'+"<?php echo $a->get_gerente('NOMBRE');?>"+" (<?php echo $a->get_gerente('ID');?>)"+'</option>');
								$('#ger').attr('disabled', '');
                                $('#ger').attr('hidden', '');
                                $('#g').attr('hidden', '');
								$('.pintar_calen').remove();$('.pintar_calen_futuro').remove();$('.pintar_calen_disabled').remove();
								var f = new Date();
								var mes = ultima_fecha_gestion.split('/');
								cont_meses = 0;
								var parametros = "mes="+cont_meses+"&"+"asesor="+identificador_asesor+"&"+"p=A&ulti="+fecha_ulti;
								$.ajax({
									type:'POST',
									url: 'agenda.php',
									data: parametros,
									success: function(result){
										// alert(result);
										//alert(ultima_fecha_gestion);
                                                                                 cont_meses = result.split('()')[0];
										var apli_monto = result.split('[SEPAR]');
										fechas_agendadas = apli_monto[1].split('[SEPAR2]');
                                                                                //alert(apli_monto[0]);
                                                                                //apli_monto = apli_monto[0].split('()')[1];
											$('#monto_apli').val(apli_monto[0].split('()')[1]);

										ultima_fecha_gestion = fechas_agendadas[0];
										//alert(ultima_fecha_gestion);
										if(fecha_ulti){
											$('#inline').datepicker('setDate', ultima_fecha_gestion);

											fecha_ulti= false;
										}
							  			
										$('#fecha_ultima').html('<?php echo $ag['tab7'] ?>: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
										//alert(fechas_agendadas[1]);
										var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
										recargar_portafolio($("#ges").val());
										recargar(ultima_fecha_gestion, $("#ges").val());
										//color_cal(fechas_agendadas[1]);
										cargar_cal('A', $("#ges").val());
										//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);

									}
								});
							<?php } ?>
						break;
			case 'S': <?php 
							$valor = $a->get_supervisor('ID'); // Obtenemos la ID de supervisor
							if(!($valor == '')){ ?>
						var identificador_supervisor = "<?php echo $a->get_supervisor('ID');?>";
						var nombre_supervisor = "<?php echo $a->get_supervisor('NOMBRE');?>";
						//var ultima_fecha_gestion = "<?php echo $a->fecha_ultima_gestion;?>";
						// colocamos los valores en los select para la permisologia
						$('#ges').html("<?php echo $a->get_asesores();?>");
						$('#sup').html('<option value="'+"<?php echo $a->get_supervisor('ID');?>"+'" >'+"<?php echo $a->get_supervisor('NOMBRE');?>"+" (<?php echo $a->get_supervisor('ID');?>)"+'</option>');
						$('#sup').attr('disabled', '');
                                                $('#sup').attr('hidden', '');
                                                $('#coor').html('<option value="'+"<?php echo $a->get_coordinador('ID');?>"+'"  >'+"<?php echo $a->get_coordinador('NOMBRE');?>"+" (<?php echo $a->get_coordinador('ID');?>)"+'</option>');
                                                $('#coor').attr('disabled', '');
                                                $('#coor').attr('hidden', '');
                                                $('#o').attr('hidden', '');
						$('#ger').html('<option value="'+"<?php echo $a->get_gerente('ID');?>"+'"  >'+"<?php echo $a->get_gerente('NOMBRE');?>"+" (<?php echo $a->get_gerente('ID');?>)"+'</option>');
						$('#ger').attr('disabled', '');
                                                $('#ger').attr('hidden', '');
                                                $('#g').attr('hidden', '');
						//ver_asesores();
			//ver_actididades_asesores();
			//alert($("#sup").val());
			cargar_cal('A',$("#sup").val());
			recargar_portafolio( $("#sup").val());
						//ver_actididades_asesores(); //activar
						<?php } ?>
						break;
                                                
                                                
                        case 'O':   <?php 
                                $valor = $a->get_coordinador('ID'); // Obtenemos la ID de coordinador
                                if(!($valor == '')){ ?>
                                var identificador_coodinador = "<?php echo $a->get_coordinador('ID');?>";
                                var nombre_coordinador = "<?php echo $a->get_coordinador('NOMBRE');?>";
                                // colocamos los valores en los select para la permisologia
                                $('#ges').html("<?php echo $a->get_asesores();?>");
                                $('#sup').html("<?php echo $a->get_supervisores();?>");
                                $('#coor').html('<option value="'+"<?php echo $a->get_coordinador('ID');?>"+'"  >'+"<?php echo $a->get_coordinador('NOMBRE');?>"+" (<?php echo $a->get_coordinador('ID');?>)"+'</option>');
                                $('#coor').attr('disabled', '');
                                $('#coor').attr('hidden', '');
                                $('#ger').html('<option value="'+"<?php echo $a->get_gerente('ID');?>"+'"  >'+"<?php echo $a->get_gerente('NOMBRE');?>"+" (<?php echo $a->get_gerente('ID');?>)"+'</option>');
                                $('#ger').attr('disabled', '');
                                $('#ger').attr('hidden', '');
                                $('#g').attr('hidden', '');
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
                                                $('#coor').html("<?php echo $a->get_coordinadores();?>");
						$('#ger').html('<option value="'+"<?php echo $a->get_gerente('ID');?>"+'"  >'+"<?php echo $a->get_gerente('NOMBRE');?>"+" (<?php echo $a->get_gerente('ID');?>)"+'</option>');
						$('#ger').attr('disabled', '');
                                                $('#ger').attr('hidden', '');
                                                $('#g').attr('hidden', '');
						<?php } ?>
						break;
		}


		// Inline datepicker
		// Funcion para el calendario
		// estan funcion se encarga de asignar una acccion al darle
		// clic al calendario y de gestionar la informacion que sera mostrada


		// CLICK CALENDARIO
		$('#inline').datepicker({
			dateFormat: 'dd/mm/yy',
			prevText: '<i class="icon-chevron-left"></i>',
			nextText: '<i class="icon-chevron-right"></i>',
			onSelect: function( selectedDate ) {
			//	alert('aki');
				// verificamos si el valor del select de los asesore 
				// estÃ¡ en seleccione para mostrar los diferentes asesores
				// al supervisor logueado
				if($('#ges').val() == $('#sup').val()  || $('#ges').val() == ''){ // si no se ha seleccionado ningun asesor
					
					validar_fecha_gestion(ultima_fecha_gestion, selectedDate);
					fecha_seleccionada = selectedDate;
					//alert('No existe asesor seleccionado');
					// PAra ver las actividades de los cliente
                    //alert($("#sup").val());
                    $('#cont_tabla').css({
		  				'background': 'none',
		  				'background-image': 'none',
						'background-repeat': 'none',
						'background-position': '0',
						'height': 'auto'
		  			});

		  			
		  			
		  			$('#cont_tabla').html('<div id="container_tabla" style="width: 100%;height: 370px;"></div><div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div><div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" ></div><div class="progreso"></div>');
                    
                    cargar_cal('A',$("#sup").val());
                   //alert('ptra');
                    $('#resumen_estadi > .progreso').css({
                    	'background': 'none',
		  				'background-image': 'none',
						'background-repeat': 'none',
						'background-position': '0',
						'height': 'auto',
						'display': 'none'
                    });
					ver_actididades_asesores();

                    recargar_portafolio( $("#sup").val());
                   
                    //=========================================

					// recargar_portafolio( $("#sup").val());

					// ver_actididades_asesores();
					// //alert(ultima_fecha_gestion);
					// cargar_cal('A', $("#sup").val());

				}
				else{
					// muestra la informacion de un solo asesor 
					// para ver las cuentas que posee agendadas en el mes
					//alert(selectedDate);
					
					// alert('ban1');
					$('#cont_tabla').css({
	  				
	  				'font-size' : '15px',
					'background-repeat': 'no-repeat',
					'background-position': '90% 50%',
					'height': '470px'
	  			});

	  			$('#cont_tabla').html('<div id="container_tabla" style="width: 100%;height: 370px;"></div><div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div><div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" ></div><div class="progreso"></div>');
					recargar(selectedDate, $("#ges").val());// Aqui va el ID del usuario
					// alert('ban2');
					cargar_cal('A', $("#ges").val());
					recargar_portafolio( $("#ges").val(), cont_meses);

// 					var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
// 					recargar_portafolio($("#ges").val());
// 					recargar(ultima_fecha_gestion, $("#ges").val());
// 					//color_cal(fechas_agendadas[1]);
// 					cargar_cal('A', $("#ges").val());
// 					//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
// // alert('ban3');

// 					validar_fecha_gestion(ultima_fecha_gestion, selectedDate);
// 					// alert('ban4');
// 					fecha_seleccionada = selectedDate;
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
			//if(fecha_ultima != fecha){
                             //{cambio} luis peña 16/04/2014
				//$('#enviar_cuentas').attr("disabled", 'disabled');
			//}else{
			//	$('#enviar_cuentas').removeAttr("disabled");
			//	// Habilitamos el boton para enviar la informacion a la vista de expediente
			//}
                        
                        
                        //[HENRY]
                        var parametros= { "validarFecha" : 1,
                                          "fecha_ultima" : fecha_ultima,
                                          "fecha"        : fecha
                        };
                          $.ajax({
                                type: 'POST',
                                url: 'agenda.php',
                                data: parametros,
                                success: function(result){
                                  //  alert(result);
                                    			//alert(fecha_ultima);
			//alert(fecha);

			//var fecha1 = new Date(fecha_ultima);
			//var fecha2 = new Date(fecha);
			
			// validamos que la ultima fecha en atraso tenga prioridad 
			// sobre las demas.. forzando al usuario a gestionar esa cuenta 
			// unicamente 
			if(result === '0'){
                            alert('no gestiona');
                             //{cambio} luis peña 16/04/2014
				//$('#enviar_cuentas').attr("disabled", 'disabled');
			}else{
                            
                            if (result === '-1'){
                                
                                alert('<?php echo $ag['tab6'] ?>');
                                
                            } else {
                            
				$('#enviar_cuentas').removeAttr("disabled");
                                //alert('gestiona');
				// Habilitamos el boton para enviar la informacion a la vista de expediente
                            }
			}
                                }
                                
                            });
                        //[FIN]
                        
                        
                        
		}
		
		/**
		 * Evento que funciona para efectuar una accion 
		 * al cambiar el valor en el select de sup
		 * @return {[type]}
		 */
		$( "#sup" ).change(function() {
                    
                     $('#usuario_selec').val($(this).val());
                    $('#ase').val($('#sup option:selected').text());
                    
            if ($("#sup").val()!= ' '){
            	fecha_ulti = true;
            	cont_meses = 0;
            	ver_asesores();
            	//alert('entrando change#sup con Sup');
                cargar_cal('A',$("#sup").val());
                //ver_actididades_asesores();
                //alert($("#sup").val());
                recargar_portafolio( $("#sup").val());
                //cargar_cal('L',$("#sup").val());
                $('#cont_tabla').html('<div id="container_tabla" style="width: 100%;height: 370px;"></div><div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div><div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" ></div><div class="progreso"></div>');
                $('#resumen_estadi').html('<div style="margin: 0px" class="progreso"></div>');
                $('#resumen_portafolio').html('<div style="margin: 0px" class="progreso"></div>');

            }else{
            
            	cont_meses = 0;
            	fecha_ulti = true;
				//alert('entrando change#sup Sin Sup');
                ver_supervisores();
                $('#cont_tabla').css({
		  				'background': 'none',
		  				'background-image': 'none',
						'background-repeat': 'none',
						'background-position': '0',
						'height': 'auto'
		  		});

		  			
	  			$('#cont_tabla').html('<div id="container_tabla" style="width: 100%;height: 370px;"></div><div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div><div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" ></div><div class="progreso"></div>');
				
				$('#sup').html("");
				$("#resumen_portafolio").html('');
                $("#tabla_info").html('');
                $("#fecha_ultima").html('');
                $("#container_tabla").html('');
                $("#resumen_estadi").html('');
				$('#inline').datepicker('setDate', $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val());
            };
		});
                         
        /**
		 * Evento que funciona para efectuar una accion 
		 * al cambiar el valor en el select de coor
		 * @return {[type]}
		 */
		$( "#coor" ).change(function() {
                    $('#usuario_selec').val($(this).val());
                    $('#ase').val($('#coor option:selected').text());
			if($('#coor').val() == ' '){
				//alert('reset');
				cont_meses = 0;
            	fecha_ulti = true;

            	//alert('Si hay');
		  			$('#cont_tabla').css({
		  				'background': 'none',
		  				'background-image': 'none',
						'background-repeat': 'none',
						'background-position': '0',
						'height': 'auto'
		  			});

		  			
		  			$('#cont_tabla').html('<div id="container_tabla" style="width: 100%;height: 370px;"></div><div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div><div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" ></div><div class="progreso"></div>');
					$('#ges').html("");
					$('#sup').html("");
					$("#resumen_portafolio").html('');
                $("#tabla_info").html('');
                $("#fecha_ultima").html('');
                $("#container_tabla").html('');
                $("#resumen_estadi").html('');
					$('#inline').datepicker('setDate', $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val());
											
				// ver_asesores();
            	//alert('entrando change#sup con Sup');
                // ver_actididades_asesores();
                //alert($("#sup").val());
                //cargar_cal('L',$("#sup").val());
                // cargar_cal('A',$("#sup").val());
                // recargar_portafolio( $("#sup").val());

			}else{
				ver_supervisores();

			}


                       // ver_actididades_asesores();
			//alert($("#sup").val());
			//cargar_cal('A',$("#coor").val());
			//recargar_portafolio( $("#coor").val());

		});
                
		/**
		 * Evento que se encarga de actualizar las fechas del calendario
		 * por el asesor
		 * @return {[type]}
		 */
		$( "#ges" ).change(function() {
                         $('#usuario_selec').val($(this).val());
                       $('#ase').val($('#ges option:selected').text());
			if($( "#ges" ).val() == $( "#sup" ).val()){
				// var fecha = ultima_fecha_gestion;
				// $('#inline').datepicker('setDate', fecha);
				// ver_actididades_asesores();
				// cargar_cal('A',$("#sup").val());
				// //alert('asd');
				// 
				//alert('igual');
				cont_meses = 0;
            	fecha_ulti = true;

				ver_asesores();
            	//alert('entrando change#sup con Sup');
                cargar_cal('A',$("#sup").val());
                recargar_portafolio( $("#sup").val());
                //ver_actididades_asesores();
                //alert($("#sup").val());
                //cargar_cal('L',$("#sup").val());

			}
			else{
				//alert('cambio asesor');
				cont_meses = 0;
            	fecha_ulti = true;
				var fecha = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
				var parametros = "mes="+cont_meses+"&"+"asesor="+$( "#ges" ).val()+"&"+"p=A&ulti="+fecha_ulti;
				// var parametros = "mes="+cont_meses+"&"+"asesor="+$( "#ges" ).val()+"&"+"p=A&ulti="+fecha_ulti;
				//alert(parametros);
				$.ajax({
					type: 'POST',
					url: 'agenda.php',
					data: parametros,
					success: function(result){
						start_progreso();
						$('#container_tabla').html('');
						//alert(result);
					// 	resultado = result.split('[SEPAR]'); // separamos la infromacion de la tabla y la fecha ultima de gestion
				 //  		ultima_fecha_gestion = resultado[0];
				 //  		//alert(ultima_fecha_gestion);
					// 	//color_cal(result);
					// 	var f = new Date();
					// 	var mes = ultima_fecha_gestion.split('/');
					// 	//cont_meses = ((parseInt(mes[1]))-(parseInt(f.getMonth())+1));
			  // 			$('#inline').datepicker('setDate', ultima_fecha_gestion);
			  // 			fecha = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
			  // 			//alert(fecha);
			  // 			recargar(fecha, $("#ges").val());
			
			  // 			//alert(ultima_fecha_gestion);
			  // 			cargar_cal('A',$("#ges").val());

			  // // 			var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
					// // recargar_portafolio($("#ges").val());
					// // recargar(ultima_fecha_gestion, $("#ges").val());
					// // //color_cal(fechas_agendadas[1]);
					// // cargar_cal('A', $("#ges").val());
			  // 			//recargar_portafolio( $("#sup").val());
			  // 			//
			  // 			//
			  // 			//
			  			cont_meses = result.split('()')[0];
						//alert(cont_meses+'A');
						var apli_monto = result.split('()')[1].split('[SEPAR]');
						fechas_agendadas = apli_monto[1].split('[SEPAR2]'); // Array
                                                       // alert('aki  ');
                                                      // alert(apli_monto[0]);
						$('#monto_apli').val(apli_monto[0]);
						
						// Fechas agendadas para luego pintar en el calendario
						//alert(fechas_agendadas[1]);
						//
						
						ultima_fecha_gestion = fechas_agendadas[0]; 
						// alert(ultima_fecha_gestion);
						//alert(ultima_fecha_gestion);
						if(fecha_ulti){
							$('#inline').datepicker('setDate', ultima_fecha_gestion);
							fecha_ulti= false;
						}
						// alert('ban5');
						$('#fecha_ultima').html('<?php echo $ag['tab7'] ?>: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
						// alert('Fechas para pintar: '+fechas_agendadas[1]);
						color_cal(fechas_agendadas[1]);

						recargar(ultima_fecha_gestion, $("#ges").val());
						// alert(ultima_fecha_gestion);
						// alert(fechas_agendadas[1]);
						///var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
						//cargar_cal('A', $("#ges").val());
						//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
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
                
                    $('#closePopControlPago').click(function(){
                    $('#popControlPago').fadeOut('slow');
                    $('.popup-overlay').fadeOut('slow');
                    return false;
                });
                
                }
                

	});

	function cargar_cal(op, asesor) {
       // alert('cargar_cal');
      //  $('#inline').css('pointer-events', 'none');
		if(asesor==$('#sup').val())
			asesor=$('#sup').val();
		// las letras N, P y A son valores que poseen las flechas
		// del calendario para determinar si da click en Next o Previous

		switch(op){
			// SI SE DA CLICK EN >			
			case 'N':	$('.pintar_calen').remove();
                                        $('.pintar_calen_futuro').remove();
                                        $('.pintar_calen_disabled').remove();
						// cont_meses++;
						// cont_meses++;
                                                //alert('AKI');
						var parametros = "mes="+cont_meses+"&"+"asesor="+asesor+"&"+"p=N&ulti="+fecha_ulti;
						$.ajax({
							type: 'POST',
							url: 'agenda.php',
							data: parametros,
							success: function(result){
									// alert(result);
									 cont_meses = result.split('()')[0];
								//alert(cont_meses+'N');
								var apli_monto = result.split('()')[1].split('[SEPAR]');
								fechas_agendadas = apli_monto[1].split('[SEPAR2]');
								// alert(apli_monto[0]);
									$('#monto_apli').val(apli_monto[0]);

								ultima_fecha_gestion = fechas_agendadas[0];
								$('#fecha_ultima').html('<?php echo $ag['tab7'] ?>: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
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
						//cont_meses--;
						var parametros = "mes="+cont_meses+"&"+"asesor="+asesor+"&"+"p=P&ulti="+fecha_ulti;
						$.ajax({
							type: 'POST',
							url: 'agenda.php',
							data: parametros,
							success: function(result){
								//alert(result);
								cont_meses = result.split('()')[0];
								//alert(cont_meses+'P');
								var apli_monto = result.split('()')[1].split('[SEPAR]');
								fechas_agendadas = apli_monto[1].split('[SEPAR2]');
								// alert(apli_monto[0]);
									$('#monto_apli').val(apli_monto[0]);

								ultima_fecha_gestion = fechas_agendadas[0];
								$('#fecha_ultima').html('<?php echo $ag['tab7'] ?>: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
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
						//alert(cont_meses);

						var parametros = "mes="+cont_meses+"&"+"asesor="+asesor+"&"+"p=A&ulti="+fecha_ulti;
						$.ajax({
							type: 'POST',
							url: 'agenda.php',
							data: parametros,
							beforeSend: function(){
						     start_progreso();
						   }
						}).done(function(result) {
		  					add_progreso(10);
                                                    //    alert(result);
						cont_meses = result.split('()')[0];
						//alert(cont_meses+'A');
						var apli_monto = result.split('()')[1].split('[SEPAR]');
						fechas_agendadas = apli_monto[1].split('[SEPAR2]'); // Array
                                                       // alert('aki  ');
						$('#monto_apli').val(apli_monto[0]);
						
						//Fechas agendadas para luego pintar en el calendario
						//alert(fechas_agendadas[1]);
						//
						
						//$('#cont_tabla').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>75%</div>');
						add_progreso(17);
						ultima_fecha_gestion = fechas_agendadas[0]; 
						// alert(ultima_fecha_gestion);
						//alert(ultima_fecha_gestion);
						if(fecha_ulti){
							//alert(ultima_fecha_gestion);
							$('#inline').datepicker('setDate', ultima_fecha_gestion);
							fecha_ulti= false;
							if($('#ges').val()== $('#sup').val())
								ver_actididades_asesores();
							else
								recargar(ultima_fecha_gestion, $("#ges").val());
						}
						// alert('ban5');
						$('#fecha_ultima').html('<?php echo $ag['tab7'] ?>: <a onclick="posicionarCal(\''+ultima_fecha_gestion+'\', \''+$('#ges').val()+'\')" href="#">'+ultima_fecha_gestion+'</a>');
						// alert('Fechas para pintar: '+fechas_agendadas[1]);
						//alert(fechas_agendadas[1]);
						color_cal(fechas_agendadas[1]);
						///var fecha_sele = $('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val();
						//validar_fecha_gestion(ultima_fecha_gestion, fecha_sele);
						
                                               //     alert('qq');
					      // progressLabel.text('Procesando...');
					      // var valores = $.parseJSON(result);
					      // var total = valores.length;
					      // total = total/100;
					      // var cont = 0;
					      // var x = 0;

					      // for (var i = valores.length - 1; i >= 0; i--) {
					      //   contenido += 'contenido['+i+']='+valores[i]+';<br>';

					      //   if(cont==total){
					      //     x++;
					      //     //alert(cont);
					      //     progressbar.progressbar( "option", "value", x);
					      //     cont = 0;
					      //   }
					      //   cont++;
					      // }
					      // contenido += '</div>';
                                               
					    })
					    .always(function(result) {
					    //$('#cont_tabla').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>100%</div>');
					    stop_progreso();
                                          //   $('#inline').css('pointer-events', 'all');
					    //alert('Completo');
					      // progressLabel.text('Completo');
					      // progressbar.progressbar( "option", "value", 100 );
					      // $('#datos').html(contenido);
					      //alert(valores.length);
					    });
					  	//alert(cont_meses);
						break;
                      //LA OPCION l ES PARA LIMPIAR EL CALENDARIO                          
                       case 'L':	$('.pintar_calen').remove();$('.pintar_calen_futuro').remove();$('.pintar_calen_disabled').remove();
					  	//alert(cont_meses);
						break;
		}
              

	}
	/**
	 * Funcion para colorear el calendario
	 * @param  {string} cadena_fechas
	 * @return {crea varios div en las fechas con cuentas}
	 */
	/**
	 * Esta funcion se encarga de listar las actividades de los asesores
	 * a el supervisor logueado
	 * @return {[type]}
	 */
	function ver_actididades_asesores() {
	  			//$('#cont_tabla').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><br>25%</div>');
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
		  		//alert(result);
		  	//alert(ultima_fecha_gestion);
		  		// Sustituimos el contenido de container_tabla con el resultado


		  		var valores = result.split('[SEPAR2]');

		  		//alert(valores[0].length);

				if(valores[0].length==54){
		  			//alert('No hay');
		  			$('#cont_tabla').html('<div style="margin: 29% 36%;">No existen cuentas agendadas para: <br> '+$('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val()+' </div>');

		  		}else{
		  			//alert('Si hay colocamos lo nuevo');
		  			$('.progreso').css({
		  				'background': 'none',
		  				'background-image': 'none',
						'background-repeat': 'none',
						'background-position': '0',
						'height': 'auto'
		  			});

		  			
		  			$('#cont_tabla').html('<div id="container_tabla" style="width: 100%;height: 370px;"></div><div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div><div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" ></div>');
			  		//alert(valores[0]);
			  		$('#container_tabla').html(valores[0]);
			  		$('#motivos_mostrar').html(valores[1]);
			  		$('#tabla_info').html(valores[2]);
			  		
			  		llamaTodo('Tabla_actividades');
		  		}
		  	}
		});
	}

	/**
	 * Esta FunciÃ³n se encarga de plasmar las cuentas agendadas
	 * por los asesores
	 * @return {cÃ³digo completo de la tabla para luego reemplazar con 
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
        
        
        	/**
	 * Esta FunciÃ³n se encarga de plasmar las cuentas agendadas
	 * por los asesores
	 * @return {cÃ³digo completo de la tabla para luego reemplazar con 
	 * el ID }
	 */
	function ver_supervisores() {

		var id_gerente = $('#coor').val();
		//alert(id_supervisor);
		var parametro = "coor="+id_gerente;

		$.ajax({
			
			type: "POST",
			url: 'agenda.php',
			data: parametro,
		  	success: function(result){
		  		$('#sup').html(result);
                $('#ges').html("");
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
		// alert(fecha);
		// alert(asesor);
		var parametros = "fecha="+fecha+"&"+"asesor="+asesor;
		var contenido;
		$.ajax({
			type: "POST",
		  	url: 'agenda.php',
		  	data: parametros,
		  	beforeSend: function(){
		  		start_progreso();
		  		// alert('25');
		  		stop_progreso();
		  		//alert('recargar antes');
		  		//$('#cont_tabla').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><br>503%</div>');
		  	}// enviamos la cadena que formamos con los parametros
		}).done(function(result){
               // alert(result);
			// add_progreso(30);
			//alert('recargar done');
			resultado = result.split('[SEPAR]'); // separamos la infromacion de la tabla y la fecha ultima de gestion
		  		contenido = resultado[1].split('[SEPAR2]');

		  		//alert(contenido[0]);
		  		if($('#ges').val() != $('#sup').val()){
			  		if(contenido[0]=='No existen cuentas agendadas. Notificar al supervisor'){
			  			//alert('No hay');
			  			$('#cont_tabla').css({
			  				
			  				'font-size' : '15px',
							'background-repeat': 'no-repeat',
							'background-position': '90% 50%',
							'height': '470px'
			  			});

			  			$('#cont_tabla').html('<div style="margin: 29% 36%;">No existen cuentas agendadas para: <br> '+$('#inline').datepicker({ dateFormat: 'dd/mm/yy' }).val()+' </div>');

			  		}else{
			  			//alert('Si hay');
			  			$('#cont_tabla').css({
			  				'background': 'none',
			  				'background-image': 'none',
							'background-repeat': 'none',
							'background-position': '0',
							'height': 'auto'
			  			});

			  			$('#cont_tabla').html('<div id="container_tabla" style="width: 100%;height: 370px;"></div><div id="identi" style="opacity: 0;width: 0;height: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);position: absolute;margin: 152px 0px 0px -719px;"></div><div style="font-size: 11px; opacity: 0;border-left: 12px solid transparent;border-right: 12px solid transparent;border-bottom: 20px solid rgb(255, 255, 255);margin-top: 25px; width: 700px;" id="motivos_mostrar" ></div><div class="progreso"></div>');
				  		//alert(contenido[0]);
				  		$('#container_tabla').html(contenido[0]);
				  		// add_progreso(32);
				  		$('#motivos_mostrar').html(contenido[1]);
				  		// add_progreso(34);
				  		$('#tabla_info').html(contenido[2]);
				  		// add_progreso(36);
				  		ultima_fecha_gestion = resultado[0];
				  		// add_progreso(38);
				  		llamaTodo('Tabla_actividades');
				  		 //stop_progreso();
				  		$('.progreso').css({
							'display': 'none'
						});

			  		}
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
		         if(count>0) {
                             win = window.open('?pantalla=7e0c6ba9785691fecdb2418d503d2aab','myWin2','toolbars=0'); 
                                 $('#form_ocul').attr('target','myWin2');
		         	$('#form_ocul').submit();
                            }
		         else
		         	alert('<?php echo $ag['tab8'] ?>');


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
		  		var estadi = contenido[1].split('[SEPAR2]');

		  		
		  		$('#resumen_portafolio').html('<h2> <?php echo $ag['tab5'] ?> </h2><hr><br>'+estadi[0]);
 
		  		$('#resumen_estadi').html('<h2> <?php echo $ag['tab5'] ?> </h2><hr><br>'+estadi[1]+'');
		  		$('#monto_apli').html(contenido[0]);

		  		llamaTodo('Tabla_resumen');//poner el ID de la tabla de resumÃ©n
		  	}
		});

		
	}

	

	
	var Ultimo_motivo_mostrado = 0;
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
         {
            win = window.open('?pantalla=7e0c6ba9785691fecdb2418d503d2aab','myWin','toolbars=0'); 
             $('#gestion_tabla_envio').attr('target','myWin');
         	$('#gestion_tabla_envio').submit();
            }
         else
         	alert('<?php echo $ag['tab8'] ?>');
	}



function crearReporteControlPago(){
        $('#popControlPago').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
        return false;
    // window.open("../Vista/Reportes/ReporteControlPagos.php","_blank");
    
}



	function color_cal(cadena_fechas) {
		/**
		 * Partimos la cadena para obtener las fechas en un arreglo
		 */
               
                // para validar que traiga al menos una fecha se cuenta la cantidad de caracteres sea mayor a 11
		if(cadena_fechas.length>11){
			recargar_portafolio( $("#ges").val(), cont_meses);
			
               // alert(cadena_fechas);

			
		// alert('que pa:'+cadena_fechas);
		var bool = false;
		// bUSCAMOS SI LA Cadena posee un simbolo % que usamos como separador
		// vease archivo c_agenda linea 1200 y algo para mayor detalle
		// si lo posee, bool, sera true
                // esto es para las fecha feriadas
		if(cadena_fechas.indexOf('%') != -1){
			bool = true;
		}



		var f = new Date();
		// Obtenemos la fecha actual formada con la variable f
		var fecha_act 		= (f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
		// separamos las cadenas con el separador %
		var fechas_arr 		= cadena_fechas.split('%');

		//alert(fechas_arr[0]);

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

			//alert(arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+arr_fechas[x][5]+'20'+arr_fechas[x][6]+arr_fechas[x][7]);

			//fecha_selected = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+arr_fechas[x][5]+'20'+arr_fechas[x][6]+arr_fechas[x][7];
                        fecha_selected = arr_fechas[x];
                        
                       // alert(fecha_selected);
                        
                        var dia, mes, ano;
                        
                        var fecha_sel_arr = fecha_selected.split('/');
                        
                        dia = fecha_sel_arr[0];
                        mes = fecha_sel_arr[1];
                        ano = fecha_sel_arr[2];
                        
                        fecha_selected = 'f_'+parseInt(dia)+(parseInt(mes)-1)+ano+'_f'; //Formamos el ID 
                        
                        //fecha_selected = fecha_selected.replace('/','');
                        
                        //alert(parseInt(dia));
                        
			//alert('1_'+fecha_selected);
                        /*
			if(fecha_selected[0]==0 || fecha_selected[3]==0){
				if(fecha_selected[0]==0 && fecha_selected[3]==0) {
					fecha_selected = 'f_'+arr_fechas[x][1]+((arr_fechas[x][4])-1)+arr_fechas[x][6]+arr_fechas[x][7]+arr_fechas[x][8]+arr_fechas[x][9]+'_f'; //Formamos el ID 
					//alert('2_'+fecha_selected);
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
			*/
			//alert('#'+fecha_selected);

			//alert(arr_fechas[x]+' > '+'10/03/14');
			
			//alert(fecha_selected); //Depuracion
			//fecha_sel_cad = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+arr_fechas[x][5]+'20'+arr_fechas[x][6]+arr_fechas[x][7];
			/**
			 * Proceso para crear el div con el color para pintar en 
			 * el calendario
			 */
			
			var nuevo = document.createElement('div');
			var padre = document.getElementById(fecha_selected);


			//alert(fecha_selected);
			ids_color = ids_color+fecha_selected+'||';

			//alert(ids_color);

			var fecha = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+arr_fechas[x][4]+arr_fechas[x][5]+'1'+arr_fechas[x][6]+arr_fechas[x][7];
			//alert(fecha);
		
				
			
				

				if(compareDate(fecha)){
					$('#'+fecha_selected).css({'background': 'rgba(5, 128, 197, 0.61)', 'box-shadow':' 0px 0px 5px black inset;'});
					$('#'+fecha_selected + ' > a').css({'color': 'white'});
				}
					//nuevo.className = 'pintar_calen_futuro';
				else{
					$('#'+fecha_selected).css({'background': 'rgba(197, 5, 43, 0.61)', 'box-shadow':' 0px 0px 5px black inset;'});
					$('#'+fecha_selected + ' > a').css({'color': 'white'});
				}
			
			// nuevo.className = 'pintar_calen';//Colocamos la clase con el color y margin
			//nuevo.name = 'fecha_agendada';

			//$('#'+fecha_selected).append(nuevo);
			//padre.appendChild(nuevo); //se implementa el div en el calendario
		}
		// Si existen fechas festivas entramos en el if
		// para formar las ID que identifican las fechas en el calendario
		if(bool)
			// Repetimos el mismo proceso que usamos para crear los ID de la fechas de 
			// Cuentas agendadas
			for(x=0;x<(fechas_feriados.length)-1;x++){
				/*fecha_selected = fechas_feriados[x][0]+fechas_feriados[x][1]+fechas_feriados[x][2]+fechas_feriados[x][3]+((fechas_feriados[x][4])-1)+fechas_feriados[x][5]+'20'+fechas_feriados[x][6]+fechas_feriados[x][7];
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
				}*/
                              //  alert(fechas_feriados[x]);
                        var fecha_sel_arr2 = fechas_feriados[x].split('/');
                        var dia2, mes2, ano2;
                        dia2 = fecha_sel_arr2[0];
                        mes2 = fecha_sel_arr2[1];
                        ano2 = fecha_sel_arr2[2];
                      
                        fecha_selected = 'f_'+parseInt(dia2)+(parseInt(mes2)-1)+ano2+'_f'; //Formamos el ID 
        
				//alert(fecha_selected);
				//alert(fecha_selected); //Depuracion
				//fecha_sel_cad = arr_fechas[x][0]+arr_fechas[x][1]+arr_fechas[x][2]+arr_fechas[x][3]+((arr_fechas[x][4])-1)+arr_fechas[x][5]+'20'+arr_fechas[x][6]+arr_fechas[x][7];
				/**
				 * Proceso para crear el div con el color para pintar en 
				 * el calendario las fechas festivas
				 */
				var nuevo = document.createElement('div');
				var padre = document.getElementById(fecha_selected);
				//$('#'+fecha_selected).css('pointer-events', 'none'); // Funciona para deshabilitar los dias feriados
				if(compareDate(fecha)){
				$('#'+fecha_selected).css({'background': 'rgb(218, 149, 4)', 'box-shadow':' 0px 0px 5px black inset;'});
				$('#'+fecha_selected + ' > a').css({'color': 'white'});
			}
				//nuevo.className = 'pintar_calen_futuro';
			else{
				$('#'+fecha_selected).css({'background': 'rgb(218, 149, 4)', 'box-shadow':' 0px 0px 5px black inset;'});
				$('#'+fecha_selected + ' > a').css({'color': 'white'});
			}//se implementa el div en el calendario
			}
		} // No ha mostrado ninguna respuesta
		else
		{
			$("#resumen_portafolio").html('');
	        $("#tabla_info").html('');
	        $("#fecha_ultima").html('');
	        $("#resumen_estadi").html('');
		}
	}




////////////////////////////
//FUNCIONES PARA LA CARGA //
//                        //
//                        //
//                        //
////////////////////////////


function start_progreso(){
	$('.progreso').css({
		
		'font-size' : '15px',
		'background-repeat': 'no-repeat',
		'background-position': '90% 50%',
		'height': '470px',
		'display': 'inline-block'
	});

	$('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>Procesando ()</div>');
	//$('.progreso').html('<div style="margin: 0% 0%;"><img src="img/proceso.gif"></div>');
}

function add_progreso(val){
	$('.progreso').css({
		
		'font-size' : '15px',
		'background-repeat': 'no-repeat',
		'background-position': '90% 50%',
		'height': '470px'
	});

	$('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>'+val+'%</div>');
	
}

function stop_progreso(){
	$('.progreso').css({
		
		'font-size' : '15px',
		'background-repeat': 'no-repeat',
		'background-position': '90% 50%',
		'height': '470px'
	});
	$('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>Procesado (OK)<br>Por favor espere...</div>');
	$('.progreso').css({
	'background': 'none',
	'background-image': 'none',
	'background-repeat': 'none',
	'background-position': '0',
	'height': 'auto'
	});
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
/*Estilo para colocar el tamÃ±o de la tabla */
.dataTables_scrollBody {
	height: 305px !important;
}
/*Estilo para definir el tamaÃ±o de la tabla*/
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


.progreso{
	/*background: rgb(210,210,210);*/
	margin: -58% 0%;
	width: 100%;
	height: 370px;
	display: inline-block;
}
</style>




