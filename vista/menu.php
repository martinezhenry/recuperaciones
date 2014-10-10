<?php
//conexion con oracle
  //include("../modelo/conexion.php");
/*
  include("sms.php");
  include("llamadas.php");
  include("C:\xampp\htdocs\recuperaciones\vista\expediente\contenedor\emails\email.php");

	$id = 'menu1';
if(isset($_GET['pantalla'])){
	switch($_GET['pantalla']) {
		case 'agenda': $id = 'menu1';break;
		case 'expediente': $id = 'menu2';break;
		case 'portafolio': $id = 'menu3';break;
		case 'directorios': $id = 'menu4';break;
                case 'directorios': $id = 'menu5';break;
	}
}

$menu= '
<input hidden id="pantallaVisible" type="number" min="1" max="4" value="1"/>    
<ul class="sky-mega-menu sky-mega-menu-anim-flip sky-mega-menu-response-to-icons">
				<!-- home -->
				<!-- <li>
					<a href="#"><i class="fa fa-single fa-home"></i></a>
				</li>-->
				<!--/ home -->
				
				<!-- about -->
				<li aria-haspopup="true">
					<a ';
if($id == 'menu1')
	$menu .= 'style= "background: #2da5da;color: #fff;"  href="?pantalla=agenda" onclick= "location.reload();"><i class="fa fa-star"></i>AGENDA</a>';
else
	$menu .= 'href="?pantalla=agenda" onclick= "location.reload();"><i class="fa fa-star"></i>AGENDA</a>';
					
                                       $menu .= '<!--<a href="#"><i class="fa fa-star"></i>AGENDA<i class="fa fa-indicator fa-chevron-down"></i></a>
					<div class="grid-container3">
						<ul>
							<li><a href="#"><i class="fa fa-globe"></i>Mission</a></li>
							<li aria-haspopup="true">
								<a href="#"><i class="fa fa-group"></i><i class="fa fa-indicator fa-chevron-right"></i>Our Team</a>
								<div class="grid-container3">
									<ul>
										<li aria-haspopup="true">
											<a href="#"><i class="fa fa-male"></i><i class="fa fa-indicator fa-chevron-right"></i>Markus Fisher</a>
											<div class="grid-container3">
												<ul>
													<li><a href="#"><i class="fa fa-leaf"></i>About</a></li>
													<li><a href="#"><i class="fa fa-tasks"></i>Skills</a></li>
													<li><a href="#"><i class="fa fa-comments"></i>Contacts</a></li>
												</ul>
											</div>
										</li>
										<li aria-haspopup="true">
											<a href="#"><i class="fa fa-female"></i><i class="fa fa-indicator fa-chevron-right"></i>Leyla Sparks</a>
											<div class="grid-container3">
												<ul>
													<li><a href="#"><i class="fa fa-leaf"></i>About</a></li>
													<li><a href="#"><i class="fa fa-tasks"></i>Skills</a></li>
													<li><a href="#"><i class="fa fa-comments"></i>Contacts</a></li>
												</ul>
											</div>
										</li>
										<li aria-haspopup="true">
											<a href="#"><i class="fa fa-male"></i><i class="fa fa-indicator fa-chevron-right"></i>Gleb Ismailov</a>
											<div class="grid-container3">
												<ul>
													<li><a href="#"><i class="fa fa-leaf"></i>About</a></li>
													<li><a href="#"><i class="fa fa-tasks"></i>Skills</a></li>
													<li><a href="#"><i class="fa fa-comments"></i>Contacts</a></li>
												</ul>
											</div>
										</li>
										<li><a href="#"><i class="fa fa-female"></i>Viktoria Gibbers</a>
									</li>
									</ul>
								</div>
							</li>
							<li><a href="#"><i class="fa fa-trophy"></i>Rewards</a></a></li>
							<li><a href="#"><i class="fa fa-certificate"></i>Certificates</a></a></li>
						</ul>
					</div>-->
				</li>
				<!--/ about -->
				
				<!-- news -->
				<li aria-haspopup="true">
                                    <a ';
if($id == 'menu2')
	$menu .= 'style= "background: #2da5da;color: #fff;"  href="?pantalla=expediente" onclick= "location.reload();"><i class="fa fa-bullhorn"></i>EXPEDIENTE</a>';
else
	$menu .= 'href="?pantalla=expediente" onclick= "location.reload();"><i class="fa fa-bullhorn"></i>EXPEDIENTE</a>';

                                   
					$menu .= '<!--<a href="#"><i class="fa fa-bullhorn"></i>EXPEDIENTE<i class="fa fa-indicator fa-chevron-down"></i></a>
					<div class="grid-container3">
						<ul>
							<li><a href="#"><i class="fa fa-check"></i>Company</a></li>
							<li><a href="#"><i class="fa fa-check"></i>Products</a></li>
							<li><a href="#"><i class="fa fa-check"></i>Specials</a></li>
						</ul>
					</div>
				</li>-->
				<!--/ news -->
				
				<!-- portfolio -->
				<li aria-haspopup="true">
                                    <a ';
if($id == 'menu3')
	$menu .= 'style= "background: #2da5da;color: #fff;"  href="?pantalla=portafolio" onclick= "location.reload();"><i class="fa fa-briefcase"></i>Portfolio</a>';
else
	$menu .= 'href="?pantalla=portafolio" onclick= "location.reload();"><i class="fa fa-briefcase"></i>Portfolio</a>';

                                    
					$menu .= '<!--<a href="#"><i class="fa fa-briefcase"></i>Portfolio<i class="fa fa-indicator fa-chevron-down"></i></a>
					<div class="grid-container3">
						<ul>
							<li><a href="#"><i class="fa fa-lemon-o"></i>Logos</a></li>
							<li><a href="#"><i class="fa fa-globe"></i>Websites</a></li>
							<li><a href="#"><i class="fa fa-th-large"></i>Branding</a></li>
							<li><a href="#"><i class="fa fa-picture-o"></i>Illustrations</a></li>
						</ul>
					</div>-->
				</li>
				<!--/ portfolio -->
				
				<!-- blog -->
				<li>
					<a ';
if($id == 'menu5')
	$menu .= 'style= "background: #2da5da;color: #fff;"  href="?pantalla=expediente" onclick= "location.reload();"><i class="fa fa-bullhorn"></i>EXPEDIENTE</a>';
else
	$menu .= 'href="?pantalla=expediente" onclick= "location.reload();"><i class="fa fa-bullhorn"></i>EXPEDIENTE</a>';

                                   
					$menu .= '<!--<a href="#"><i class="fa fa-bullhorn"></i>EXPEDIENTE<i class="fa fa-indicator fa-chevron-down"></i></a>
					<div class="grid-container3">
						<ul>
							<li><a href="#"><i class="fa fa-check"></i>Company</a></li>
							<li><a href="#"><i class="fa fa-check"></i>Products</a></li>
							<li><a href="#"><i class="fa fa-check"></i>Specials</a></li>
						</ul>
					</div>
				</li>-->
				<!--/ news -->
				
				<!-- portfolio -->
				<li aria-haspopup="true">
                                    <a ';
if($id == 'menu4')
	$menu .= 'style= "background: #2da5da;color: #fff;"  href="?pantalla=directorios" onclick= "location.reload();"><i class="fa fa-edit"></i>DIRECTORIOS</a>';
else
	$menu .= 'href="?pantalla=directorios" onclick= "location.reload();"><i class="fa fa-edit"></i>DIRECTORIOS</a>';

	$menu .= '

				</li>
            <li aria-haspopup="true">
            &nbsp<img style="margin-top: 13;" src="img/ico/page.ico" width="15px" heigth="15px"/>&nbsp
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
            <li aria-haspopup="true">
            &nbsp<img style="margin-top: 13;" src="img/ico/config.ico" width="15px" heigth="15px"/>&nbsp
                <div class="grid-container3">
                    <div class="">
                    <a target="_blank" onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="validaciones.php"><i class="fa fa-edit"></i>Validaciones</a>
                    </div>
                    <div class="">
                    <a target="_blank" onmouseover="this.style.background=\'#2da5da\';this.style.color=\'white\'" onmouseout="this.style.background=\'initial\';this.style.color=\'#666\'" href="plantillassms.php"><i class="fa fa-edit"></i>Plantillas SMS</a>
                    </div>
                    
                </div>
            </li>

                                                 <!-- NOTIFICACIONES -->
                                
                    
 
				<!-- contacts -->
                                <!-- contacts form -->
				<li aria-haspopup="true" class="right">
					<a onmouseover="'; if (isset($_SESSION['idPersona'])) $menu .= "buscarTelefonos('SESSION', 'HAY');"; else $menu .= ""; $menu .= '" href="#_">
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

				<li class="right">
					<img onclick="(document.getElementById(\'pantallaVisible\').value > 1) ? document.getElementById(\'pantallaVisible\').value = parseInt(document.getElementById(\'pantallaVisible\').value)-1 : \'\'; cambiarPantalla(document.getElementById(\'pantallaVisible\').value, \'-\');" style="cursor: pointer; margin-bottom: -12;" src="img/iconoFlecha-Izquierda.png" WIDTH=40 HEIGHT=40>
                                        <img style="cursor: pointer; margin-bottom: -12;" src="img/iconoFlecha-Derecha.png" WIDTH=40 HEIGHT=40>
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
<script>
    $(document).ready(function (){ $('#noti').load('notificaciones.php'); setInterval('obtenerNoLeidos(); contadorSMSRecibios()',60000); $("#li-noti").hover(function() {
 cargarNotificaciones();
});

obtenerNoLeidos();
contadorSMSRecibios();

 });




</script>

*/