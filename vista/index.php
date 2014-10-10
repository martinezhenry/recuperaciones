<?php
session_start();
//$_SESSION['USER'] = "GTE01";
if (!isset($_SESSION['USER'])){
    
    header("Location: login.php");
    exit();
}


$_SESSION['raiz'] = "c:/Recuperaciones";
($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';
//$_SESSION['nombre_user'] = "Julio Rodriguez";
//conexion con oracle
//include_once ("../modelo/conexion.php");
  //configuraciones del sistema

 
  //llamado de ccs
  require_once 'principal/ccs.php';
  //llamados a js
  require_once '/principal/js.php';
  //
  require_once 'conf.php';
  //menu
  include("/principal/menu.php");
  //Pantalla Expediente
  include "expediente/expediente.php";
  //Pantalla Agenda
  include "agenda.php";
  //Pantalla Portafolio

  include "portafolio.php";
  //Pantalla Directorios
  include "directorios.php";
  
    //Pantalla Busqueda Avanzada
  include "busquedaAvanzada/busquedaAvanzada.php";
  
    //Pantalla Novedades
  include "novedades/novedades.php";
  
  //Pantalla Localozacion
  require_once $_SESSION['ROOT_PATH'].'/vista/localizacion/localizacion.php';
  
  
  
  require_once '../modelo/conexion.php';
  ($_SESSION['lang'] === 'es') ? require_once 'language/es.conf' : require_once 'language/en.conf';
  
 
?>
<!DOCTYPE html> 
<html>
	<head>
  
            <script>
            $(document).ready(function(){
                    
               $("#alarmas").bind("contextmenu", function(e){
                  $("#d_alarmas").css({'display':'block', 'left':e.pageX, 'top':e.pageY});
                  //alert('derecho');
                  return false;
                });
                
                
                $(document).click(function(e){
                    if(e.button == 0){
                      $("#d_alarmas").css("display", "none");
                    }
                });
                
                //si pulsamos escape, el menú desaparecerá
                $(document).keydown(function(e){
                    if(e.keyCode == 27){
                          $("#d_alarmas").css("display", "none");
                    }
                });

                cargarAlarmas();
                
                
            jQuery.fn.reset = function () {
            $(this).each (function() { this.reset(); });
            }
                
            });
         
            
            </script>
            
            
            <style>
                
                .skin0
                {
                    display: none;
                    position:absolute;
                    border:1.5px solid black;
                    background-color:menu;
                    font-family:Verdana;
                    line-height:20px;
                    cursor:default;
                    height:auto;
                    z-index: 1000;
                }
                .menuitems
                {
                font-size: 12px;
                FONT-FAMILY:Verdana;
                padding-right:20px;
                z-index: 1000;
                }

            </style>

            
		<title>grupovpc</title>
                <link rel="icon" type="image/ico" href="http://localhost/recuperaciones/vista/img/favicon.ico">
		<!--[if lt IE 9]>   
			<link rel="stylesheet" href="css/sky-forms-ie8.css">
		<![endif]-->
		
		
		<!--[if lt IE 10]>
			<script src="js/jquery.placeholder.min.js"></script>
		<![endif]-->		
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script src="js/sky-forms-ie8.js"></script>
		<![endif]-->
                <script src="../controlador/c_alarmas.js"></script>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 user-scalable=no" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
        
        <body class="bg-blue">
            <!-- Menu derecho Alarmas -->
            <div id="d_alarmas" class="skin0" style="display:hidden"> 
            <div class="menuitems" onclick="alarmasProgramadas();" onmouseover="this.style.background='highlight';this.style.color='white'" onmouseout="this.style.background='menu';this.style.color='black'">Programadas</div>

           
            </div>
            
            
            
            <noscript> <!--<meta http-equiv="Refresh" content="5;url=login.php">-->
            
            <div style="width: 100%">
                         <div id="popup" style="">
                        <div style="padding: 40px;" class="content-popup">
                            <form action="login.php" method="post">
                                <center><h1>
                                        ¡Su navegador no soporta Javascript!.. Active el uso de Javascript en su navegador para poder ingresar.</h1><br/> 
                           <img width="100px" height="100px" src="img/error.png" /><br><br>
                          
                           <button>Aceptar</button>
                          
                          
                          </center>
                            </form>
     
            </div>
        </div>
            
            
            
            </noscript>
            
            <div style="width: 100%">
                         <div id="popup" style="display: none;">
                        <div style="padding: 0px;" class="content-popup">
  
                     <form action="" id="formCambioClave" class="sky-form" />
                        <div class="close"><a href="#" id="close"><img style="margin-top: 10px;" src="img/close.png"/></a></div>
                        <header><h2>Cambio de Contraseña</h2></header>
                       
				<fieldset>

                                    	<section>
                                                                                <div id="aviso" style="display: block;
margin-top: 6px;
padding: 0 1px;
font-style: normal;
font-size: 11px;
line-height: 15px;
color: #ee9393;
"></div>
						<div class="row">
							<label class="label col col-4">Clave Actual</label>
							<div class="col col-8">
								<label class="input">
									<i class="icon-append icon-lock"></i>
									<input type="password" name="cActual" />
								</label>
							</div>
						</div>
					</section>
					<section>
						<div class="row">
							<label class="label col col-4">Nueva Clave</label>
							<div class="col col-8">
								<label class="input">
									<i class="icon-append icon-lock"></i>
									<input type="password" name="cNueva" />
								</label>
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-4">Confirme Su Nueva Calve</label>
							<div class="col col-8">
								<label class="input">
									<i class="icon-append icon-lock"></i>
									<input type="password" name="cConfirmacion" />
								</label>
								
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<div class="col col-4"></div>
							<div class="col col-8"></div>
						</div>
					</section>
				</fieldset>
				<footer> 
                                    <button type="button" onclick="cambioClave(cActual.value, cNueva.value, cConfirmacion.value); $('#formCambioClave').reset();" class="button">Aceptar</button>
					<a href="#" onclick="formCambioClave.reset(); document.getElementById('close').click();" class="button button-secondary">Cancelar</a>
				</footer>
			</form>	
     
    </div>
</div>
            
            
          <?php 
          require_once 'principal/pestana.php';
          
          foreach ($pes as $key => $value) {
                    $pestaña = str_replace('{'.$key.'}', $value, $pestaña);
                   }
                
                echo $pestaña;
     
          ?>
                
                
            <div class="body">
                     
		<?php 
                
                    
                   foreach ($m as $key => $value) {
                    $menu = str_replace('{'.$key.'}', $value, $menu);
                   }                
               echo $menu;
                
                ?> 		
		</div>
		<div style="width: 100% !important;" class="body">			
		       <!--                    <input hidden type="text" value="P_agenda" id="pantallaActual"/>
                    <button onclick="document.getElementById(pantallaActual.value).hidden = true; document.getElementById('P_agenda').hidden = false; pantallaActual.value = 'P_agenda';">agenda</button>
                    <button onclick="document.getElementById(pantallaActual.value).hidden = true; document.getElementById('P_expediente').hidden = false; pantallaActual.value = 'P_expediente';">expediente</button>
                    <button onclick="document.getElementById(pantallaActual.value).hidden = true; document.getElementById('P_portafolio').hidden = false; pantallaActual.value = 'P_portafolio';">portafolio</button>
                    <button onclick="document.getElementById(pantallaActual.value).hidden = true; document.getElementById('P_directorios').hidden = false; pantallaActual.value = 'P_directorios';">directorios</button>
          
                    <div id="P_agenda"> <?php //echo $agenda; ?> </div>
                    <div hidden id="P_expediente"> <?php //echo $expediente; ?> </div>
                    <div hidden id="P_portafolio"> <?php //echo $portafolio; ?> </div>
                    <div hidden id="P_directorios"> <?php //echo $directorios; ?> </div> -->
			<!-- Red color scheme -->
                        
                        <?php 
                        
                        if (isset($_GET['pantalla']))
                        {
                           
                            if ($_GET['pantalla'] == md5("expediente"))
                            {
                                foreach ($e as $key => $value) {
                    $expediente = str_replace('{'.$key.'}', $value, $expediente);
                   }    
                                echo $expediente;
                                
                            } else if ($_GET['pantalla'] == md5("portafolio"))
                            {
                                
                                foreach ($po as $key => $value) {
                                $portafolio = str_replace('{'.$key.'}', $value, $portafolio);
                              
                               }
                                
                                echo $portafolio;
                                
                            } else if ($_GET['pantalla'] == md5("agenda"))
                            {
                                foreach ($ag as $key => $value) {
                                $agenda = str_replace('{'.$key.'}', $value, $agenda);
                              
                               }
                               
                                                           
                               echo $agenda;
                            } else if ($_GET['pantalla'] == md5("directorios"))
                            {
                                echo $directorios;
                            } else if ($_GET['pantalla'] == md5("localizacion")){
                                
                                 foreach ($loc as $key => $value) {
                                $localizacion = str_replace('{'.$key.'}', $value, $localizacion);
                              
                               }
                               
                                                           
                               echo $localizacion;
                                
                                
                            } else if ($_GET['pantalla'] == md5("busqueda")){
                                
                             //    foreach ($loc as $key => $value) {
                               // $busqueda = str_replace('{'.$key.'}', $value, $busqueda);
                              
                              // }
                               
                                                           
                               echo $busqueda;
                                
                                
                            } else if ($_GET['pantalla'] == md5("novedades")){
                                
                                        
                               echo $novedades;
                                
                                
                            }
                            
                        } else 
                        {
                            
                               foreach ($ag as $key => $value) {
                                $agenda = str_replace('{'.$key.'}', $value, $agenda);
                              
                               }
                               
                                                           
                               echo $agenda;
                        }
                        
                        ?>
                        
                        
                        	<!--/ Red color scheme -->	
                        
		</div>
            

            </div>
             

            
            
            <script src="js/jquery.jclock-1.2.0.js" type="text/javascript"></script>
            <script type="text/javascript">
function iniciaReloj(){
    $('#re').jclock();;
}

iniciaReloj();
</script>



<script type="text/javascript">

function cambioClave(clave, newClave, confClave){
    
  
    var contenedor = document.getElementById('aviso');
    if (newClave === confClave){
        
    var ajax = nuevoAjax();

    
    ajax.onreadystatechange = function()
    {
   
        if (ajax.readyState == 4)
        {

          contenedor.innerHTML = ajax.responseText;
                 
          
        }
    }
    
 
    ajax.open("GET", "../controlador/c_cambiaClave.php?clave="+clave+"&nueva="+newClave, true);
    ajax.send();

        
    } else {
        
        contenedor.innerHTML = "Los Datos No Coinciden Verifique";
        
    }
    
}

$('#open').click(function(){
        $('#popup').fadeIn('slow');
        $('.popup-overlay').fadeIn('slow');
        $('.popup-overlay').height($(window).height());
        return false;
    });
    
    $('#close').click(function(){
        $('#aviso').html("");
        $('#popup').fadeOut('slow');
        $('.popup-overlay').fadeOut('slow');
        return false;
    });
    
    
    
    
    
	function datos(){
				//un prompt
				alertify.prompt("Crear Alerta, introduce el mensaje, fecha y hora del aviso:", function (e, sms, fecha, hora) {
                                 
                                
                                    
					if (e){
                                            
                                             ingresarAlarma(sms, fecha, hora);
                                             cargarAlarmas();
                                              
						
					}else{
						//alertify.error("Has pulsado '" + alertify.labels.cancel + "'");
					}
                                        
                                   
                                        
                                        
                                       
                                        
                                   
                                    
                                        //alert('otra');
                                        
				});
				return false;
			}
                        
                        
                        
      	function alerta(sms, id){
				//un alert
				alertify.alert("<b>Recordatorio:</b> "+sms, function () {
					
				});
                                
                            }
                        
                        
                       


</script>


<style>

#popup {
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 1001;
}

.content-popup {
    margin:0px auto;
    margin-top:120px;
    position:relative;
    padding:10px;
    width:500px;
    min-height:200px;
    border-radius:4px;
    background-color:#FFFFFF;
    box-shadow: 0 2px 5px #666666;
}

.content-popup h2 {
    color:#48484B;
    border-bottom: 1px solid #48484B;
    margin-top: 0;
    padding-bottom: 4px;
}

.popup-overlay {
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 999;
    display:none;
    background-color: #777777;
    cursor: pointer;
    opacity: 0.7;
}

.close {
    position: absolute;
    right: 15px;
}


</style>

	</body>
</html>
