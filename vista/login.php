<!DOCTYPE html> 
<html>
	<head>
		<title>grupovpc</title>
		
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
		
		<link rel="stylesheet" href="css/demo.css" />
		<link rel="stylesheet" href="css/sky-forms.css" />
		<!--[if lt IE 9]>
			<link rel="stylesheet" href="css/sky-forms-ie8.css">
		<![endif]-->
		
		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
		<!--[if lt IE 10]>
			<script src="js/jquery.placeholder.min.js"></script>
		<![endif]-->		
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script src="js/sky-forms-ie8.js"></script>
		<![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <link rel="icon" type="image/ico" href="http://localhost/recuperaciones/vista/img/favicon.ico">
        </head>
	
	<body class="bg-blue">
             
		<div class="body body-s" style="margin-top: 13%;">
                   
                    <form action="../controlador/c_login.php" method="post" id="sky-form" class="sky-form" />
				 
                            
				<fieldset>	
                                    <div for="password" style="display: <?php if (isset($_GET['alert'])) echo "block;"; else echo "none;"; ?>;
margin-top: 6px;
padding: 0 1px;
font-style: normal;
font-size: 11px;
line-height: 15px;
color: #ee9393;"><?php if ((isset($_GET['alert'])) && ($_GET['alert'] == md5("invalid"))) echo "Usuario o Clave Invalidos. Verficique e intente de nuevo."; else if ((isset($_GET['alert'])) && ($_GET['alert'] == md5("block"))) echo "Usuario Bloqueado. Contacte con el Administrador."; else "otro"; ?></div>
					<section>
						<div class="row">
							<label class="label col col-4">Usuario</label>
							<div class="col col-8">
								<label class="input">
									<i class="icon-append icon-user"></i>
                                                                        <input type="text" onkeyup="this.value = (this.value).toUpperCase();" name="username" />
								</label>
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-4">Password</label>
							<div class="col col-8">
								<label class="input">
									<i class="icon-append icon-lock"></i>
                                                                        <input type="password" name="password" />
								</label>
								<div class="note"><a href="#">¿Olvido su contraseña de aceso?</a></div>
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<div class="col col-4">Idioma</div>
                                                        <div class="col col-8">
                                                            <select id='lang' name='lang'>
                                                                <option value="es">Español (Spanish)</option>
                                                                <option value="en">Inglés</option>
                                                            </select>
                                                            
                                                            
                                                        </div>
                                                        
						</div>
					</section>
				</fieldset>
				<footer>
					<button type="submit" class="button">Log in</button>
					<a href="#" class="button button-secondary">Register</a>
				</footer>
			</form>			
		</div>
		
		<script type="text/javascript">
			$(function()
			{
				// Validation
				$("#sky-form").validate(
				{					
					// Rules for form validation
					rules:
					{
						username:
						{
							required: true
							//email: true
						},
						password:
						{
							required: true,
							minlength: 3,
							maxlength: 20
						}
					},
										
					// Messages for form validation
					messages:
					{
						username:
						{
							required: 'Por favor ingrese su usuario'
							//email: 'Por favor ingrese un email valido'
						},
						password:
						{
							required: 'Por favor introdusca su contraseña'
						}
                                            
                                                
					},					
					
					// Do not change code below
					errorPlacement: function(error, element)
					{
						error.insertAfter(element.parent());
					}
				});
                                
                                
                                $('#paso').validate(
                                        {
                                    
                                    rules:
					{
						nologin:
						{
							required: true
							//email: true
						}
					
					},
										
					// Messages for form validation
					messages:
					{
						nologin:
						{
							required: 'Por favor ingrese su usuario'
							//email: 'Por favor ingrese un email valido'
						}
                                            
                                                
					},					
					
					// Do not change code below
					errorPlacement: function(error, element)
					{
						error.insertAfter(element.parent());
					}
                                    
                                    
                                });
                                
			});			
		</script>
	</body>
</html>