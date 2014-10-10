<?php
//conexion con oracle
  //include("../modelo/conexion.php");
  include("sms.php");
  include($_SESSION['ROOT_PATH']."/vista/expediente/contenedor/llamadas.php");
  include($_SESSION['ROOT_PATH']."/vista/expediente/contenedor/emails/email.php");

	$id = 'menu1';
if(isset($_GET['pantalla'])){
	switch($_GET['pantalla']) {
		case md5('agenda'): $id = 'menu1';break;
		case md5('expediente'): $id = 'menu2';break;
		case md5('portafolio'): $id = 'menu3';break;
		case md5('directorios'): $id = 'menu4';break;
	}
}
 $vista_boton = (isset($_GET[md5('unem')])) ? "" : "display: none;";

$menu= '
<input hidden id="pantallaVisible" type="number" min="1" max="4" value="1"/>    
<ul class="sky-mega-menu sky-mega-menu-anim-flip sky-mega-menu-response-to-icons">';
				
			          
		
                // INICIO MENU PORTAFOLIO                        
                                   $menu .= '<!-- portfolio -->
				<li aria-haspopup="true" style="'.$vista_boton.'">
                                <a ';
if($id == 'menu3')
	$menu .= 'style= "background: #2da5da;color: #fff;"  href="?pantalla='.md5('portafolio').'&'.md5('unem').'" onclick= "location.reload();"><i class="fa fa-briefcase"></i>{tab3}</a>';
else
	$menu .= 'href="?pantalla='.md5('portafolio').'&'.md5('unem').'" onclick= "location.reload();"><i class="fa fa-briefcase"></i>{tab3}</a>';

                                    // FIN MENU PORTAFOLIO

               // INICIO MENU DIRECTORIO

					$menu .= '
				<li style="'.$vista_boton.'">
					<a ';
if($id == 'menu4')
	$menu .= 'style= "background: #2da5da;color: #fff;"  href="?pantalla='.md5('directorios').'&'.md5('unem').'" onclick= "location.reload();"><i class="fa fa-edit"></i>{tab4}</a>';
else
	$menu .= 'href="?pantalla='.md5('directorios').'&'.md5('unem').'" onclick= "location.reload();"><i class="fa fa-edit"></i>{tab4}</a>';


                                // FIN MENU DIRECTORIO


            // INICIO MENU AGENDA

	$menu .= '

				</li>';
                                
$menu .=	'<li aria-haspopup="true" style="'.$vista_boton.'">
					<a id="menu-agenda"';
if($id == 'menu1')
	$menu .= 'style= "background: #2da5da;color: #fff; padding: 0;"  href="#" onclick= "abrirVentana(\'?pantalla='.md5('agenda').'\', \'Agenda\');">&nbsp<img title="Agenda" src="img/ico/agenda.ico" width="15px" heigth="15px"/>&nbsp</a>';
else
	$menu .= 'href="#" style="padding: 0;" onclick= "abrirVentana(\'?pantalla='.md5('agenda').'\', \'Agenda\');">&nbsp<img title="Agenda" src="img/ico/agenda.ico" width="15px" heigth="15px"/>&nbsp</a>';
				

$menu .= '

				</li>';
                            // FIN MENU AGENDA

            // INICIO MENU EXPEDIENTE

                                       $menu .= '
				<li aria-haspopup="true" style="'.$vista_boton.'">
                                    <a id="menu-expediente" ';
if($id == 'menu2')
	$menu .= 'style= "background: #2da5da;color: #fff; padding: 0;"  href="#" onclick= "abrirVentana(\'?pantalla='.md5('expediente').'\', \'Expediente\');"><i class="fa fa-bullhorn"></i>&nbsp;</a>';
else
	$menu .= 'href="#" style="padding: 0;" onclick= "abrirVentana(\'?pantalla='.md5('expediente').'\', \'Expediente\');">&nbsp<img title="Expediente" src="img/ico/page.ico" width="15px" heigth="15px"/>&nbsp</a>';

                         $menu .= '

				</li>';
                         // FIN MENU EXPEDIENTE
                         
                         
                         
            // INICIO MENU LOCALIZACION

                                       $menu .= '
				<li aria-haspopup="true" style="'.$vista_boton.'">
                                    <a ';
if($id == 'menu2')
	$menu .= 'style= "background: #2da5da;color: #fff; padding: 0;"  href="#" onclick= "abrirVentana(\'?pantalla='.md5('localizacion').'\', \'Localizacion\');"><img title="Localizacion"  src="img/ico/localizacion.ico" width="15px" heigth="15px"/>&nbsp;</a>';
else
	$menu .= 'href="#" style="padding: 0;" onclick= "abrirVentana(\'?pantalla='.md5('localizacion').'\', \'Localizacion\');">&nbsp<img title="Localizacion"  src="img/ico/localizacion.ico" width="15px" heigth="15px"/>&nbsp</a>';

                         $menu .= '

				</li>';
                         // FIN MENU LOCALIZACION
                         

    $menu .=     '   <li aria-haspopup="true" style="'.$vista_boton.'">
            &nbsp<img  src="img/ico/imprimir.ico" width="15px" heigth="15px"/>&nbsp
                <div class="grid-container3">
                    <div class="">
                    <a onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="#"><i class="fa fa-edit"></i>Link1</a>
                    </div>
                    <div class="">
                    <a onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="#"><i class="fa fa-edit"></i>Link2</a>
                    </div>
                    <div class="">
                    <a onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="#"><i class="fa fa-edit"></i>Link3</a>
                    </div>
                    <div class="">
                    <a onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="#"><i class="fa fa-edit"></i>Link4</a>
                    </div>
                    <div class="">
                    <a onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="#"><i class="fa fa-edit"></i>Link5</a>
                    </div>
                </div>
            </li>
         <!--   <li aria-haspopup="true" style="'.$vista_boton.'">
            &nbsp<img src="img/ico/config.ico" width="15px" heigth="15px"/>&nbsp
                <div class="grid-container3">
                    <div class="">
                    <a target="_blank" onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="validaciones.php"><i class="fa fa-edit"></i>{tab5}</a>
                    </div>
                    <div class="">
                    <a target="_blank" onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="plantillassms.php"><i class="fa fa-edit"></i>{tab6}</a>
                    </div>
                    
                </div>
            </li> -->';
            // INICIO MENU LOCALIZACION

                                       $menu .= '
				<li aria-haspopup="true" style="'.$vista_boton.'">
                                    <a ';
if($id == 'menu5')
	$menu .= 'style= "background: #2da5da;color: #fff; padding: 0;"  href="#" onclick= "abrirVentana(\'?pantalla='.md5('busqueda').'\');"><img title="Busqueda Avanzada" src="img/ico/busqueda.ico" width="20px" heigth="20px"/>&nbsp;</a>';
else
	$menu .= 'href="#" style="padding: 0;" onclick= "abrirVentana(\'?pantalla='.md5('busqueda').'\');">&nbsp<img title="Busqueda Avanzada"  src="img/ico/busqueda.ico" width="20px" heigth="20px"/>&nbsp</a>';

                         $menu .= '

				</li>';
                         // FIN MENU LOCALIZACION
                         

                       $menu .='                          <!-- NOTIFICACIONES -->
                                
                    
 
				<!-- contacts -->
                                <!-- contacts form -->
				<li aria-haspopup="true" class="right">
					<a onmouseover="'; $menu .= "buscarTelefonos('SESSION', 'HAY');"; $menu .= '" onclick="mantenerCapa();" href="#_">
                                        <span id="countSMS" class="notificacion">8
                                        </span>
                                        <i class="fa fa-envelope-o"></i>SMS</a>
                                        <div class="grid-container6">
                                            '.$sms.'
                                        </div>
                                 </li> 
                
                                      <!-- contacts form -->
			 <!--	<li aria-haspopup="true" class="right">
					<a href="#_">
                                        <span class="notificacion">8
                                        </span>
                                        <i class="fa fa-envelope-o"></i>EMAIL</a>
                                        <div class="grid-container6">
                                            '.$email.'
                                        </div>
                                 </li> 
                                 -->
                                             <!-- contacts form -->
				<li  aria-haspopup="true" class="right">
					<a id="li-noti" href="#_">
                                        <span id="noti23" class="notificacion">8
                                        </span>
                                        <i class="fa fa-envelope-o"></i>!</a>
                                        
                                        <div id = "noti" class="grid-container6">
                                          
                                        </div>
                                 </li>
                                 

                                <!-- END NOTIFICACIONES -->
                                
				<li aria-haspopup="true" class="right">
					<a href="?pantalla='.md5('novedades').'" target="_blank">
                                        <span id="noti24" class="notificacion">!
                                        </span>
                                        &nbsp*</a>
                                        
                                 </li> 
				<!--/ contacts -->
			</ul>
			<!--/ mega menu -->'
        ?>



<style>
    
    .notificacion {
        
        display: block;
        position: absolute;
        top: 8px;
        right: 8px;
        background: #cb1111;
        border: 1px solid #9f0c0c;
        padding: 0 4px;
        font-size: 11px;
        line-height: 14px;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        
        
    }
    
    .ocultar_noti {
        
        display: none;
       
    }
    
    
    
</style>
<script src="../controlador/c_notificaciones.js"></script>
<script src="../controlador/c_sms.js"></script>
<script src="../controlador/novedades/c_novedades.js"></script>
<script>
    $(document).ready(function (){ $('#noti').load('notificaciones.php'); setInterval('obtenerNoLeidos(); contadorSMSRecibios(); verificarNovedad();',60000); $("#li-noti").hover(function() {
 cargarNotificaciones();

});

obtenerNoLeidos();
contadorSMSRecibios();
 verificarNovedad();
 });
 var win = Array();
 var win_num = 0;
 
 function mantenerCapa(){
     
    // $('#capaSMS').css('display', 'none');
     
 }
 
function abrirVentana(url, nombre){
    var valores = "width=950,height=600,fullscreen=1";
  //  var win;
    
 //  alert(win);
 

   /*
        
        if (typeof(win[win_num]) != "undefined"){
            
            if (win[win_num].name == nombre){
                
                win[win_num].focus();
                
            } else {
               win_num = win_num+1;
               win[win_num] = window.open(url,nombre,valores);
               win.title = nombre;
                
            }
            
        } else {
            
            win[win_num] = window.open(url,nombre,valores);
            win.title = nombre;
            
        }
        */
       // alert(win.name);
        
   
    
    ///win.moveTo(0,0);
    //win.resizeTo(screen.availWidth, screen.availHeight);


//doc.close(); 
     win = window.open(url,null,valores);
     win.title = nombre;
}


</script>

