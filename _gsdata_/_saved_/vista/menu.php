<?php
//conexion con oracle
  include("../modelo/conexion.php");
  include("sms.php");
  include("llamadas.php");
  include("email.php");

$menu= '<ul class="sky-mega-menu sky-mega-menu-anim-flip sky-mega-menu-response-to-icons">
				<!-- home -->
				<!-- <li>
					<a href="#"><i class="fa fa-single fa-home"></i></a>
				</li>-->
				<!--/ home -->
				
				<!-- about -->
				<li aria-haspopup="true">
					<a href="http://localhost/recuperaciones/examples/agenda.html" ><i class="fa fa-star"></i>AGENDA</a>
                                        <!--<a href="#"><i class="fa fa-star"></i>AGENDA<i class="fa fa-indicator fa-chevron-down"></i></a>
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
                                    <a href="http://localhost/recuperaciones/examples/expediente.html?#_"><i class="fa fa-bullhorn"></i>EXPEDIENTE</a>
					<!--<a href="#"><i class="fa fa-bullhorn"></i>EXPEDIENTE<i class="fa fa-indicator fa-chevron-down"></i></a>
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
                                    <a href="#"><i class="fa fa-briefcase"></i>Portfolio</a>
					<!--<a href="#"><i class="fa fa-briefcase"></i>Portfolio<i class="fa fa-indicator fa-chevron-down"></i></a>
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
					<a href="#"><i class="fa fa-edit"></i>DIRECTORIOS</a>
				</li>
				<!-- contacts -->
                                <!-- contacts form -->
				<li aria-haspopup="true" class="right">
					<a href="#_"><i class="fa fa-envelope-o"></i>SMS</a>
                                        <div class="grid-container6">
                                            '.$sms.'
                                        </div>
                                 </li> 
                                      <!-- contacts form -->
				<li aria-haspopup="true" class="right">
					<a href="#_"><i class="fa fa-headphones"></i>LLAMADAS</a>
                                        <div class="grid-container6">
                                            '.$llamadas.'
                                        </div>
                                 </li>
                                      <!-- contacts form -->
				<li aria-haspopup="true" class="right">
					<a href="#_"><i class="fa fa-inbox"></i>EMAIL</a>
                                        <div class="grid-container6">
                                            '.$email.'
                                        </div>
                                 </li> 
				<li class="right">
					<a href="#">SALIR</a>
				</li>
				<!--/ contacts -->
			</ul>
			<!--/ mega menu -->'
        ?>