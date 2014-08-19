<!DOCTYPE html> 
<html>
	<head>
		<title>Sky Forms Pro</title>
		
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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
	
	<body class="bg-blue">
		<div class="body body-s">		
			<form action="" id="sky-form" class="sky-form" />
				<header>Cambio de Contraseña</header>
				
				<fieldset>
                                    	<section>
						<div class="row">
							<label class="label col col-4">Clave Actual</label>
							<div class="col col-8">
								<label class="input">
									<i class="icon-append icon-user"></i>
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
									<i class="icon-append icon-user"></i>
									<input type="password" name="cNueva" />
								</label>
							</div>
						</div>
					</section>
					
					<section>
						<div class="row">
							<label class="label col col-4">Confirme su Calve</label>
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
                                                cActual:
						{
							required: true,
                                                        minlength: 3,
							maxlength: 20
							//email: true
						},
						cNueva:
						{
							required: true,
                                                        minlength: 3,
							maxlength: 20
							//email: true
						},
						cConfirmacion:
						{
							required: true,
							minlength: 3,
							maxlength: 20
						}
					},
										
					// Messages for form validation
					messages:
					{
						cActual:
						{
							required: 'Por favor introdusca su contraseña'
							//email: 'Por favor ingrese un email valido'
						},
						cNueva:
						{
							required: 'Por favor introdusca su contraseña Nueva'
						},
                                                cConfirmacion:
						{
							required: 'Repita su nueva contraseña'
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