
         <?php
                
                if (isset($_GET['pasada']))
                {
                    
                    $pasada = $_GET['pasada'];
                    $permitir = $_GET['permitir'];
                    $usuario = $_GET['usuario'];
                    //$tamaño = true;
                    
                }
  
        
                
                ?>
                

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/theme.css">
                <script> 
                    
                    var tam = "<?php echo $permitir; ?>";
                
                    var usuario = "<?php echo $usuario; ?>";
                    
                     var directorio = "<?php echo $pasada; ?>";
                  
                </script>
                <script type="text/javascript" src="../controlador/funciones_procedimientos.js"></script>
		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script type="text/javascript" src="js/i18n/elfinder.ru.js"></script>

		<!-- elFinder initialization (REQUIRED) -->
                
       
		<script type="text/javascript" charset="utf-8">
                    
                    
                    
			$().ready(function() {
                                                        hola(window.usuario, window.directorio);

				var elf = $('#elfinder').elfinder({
					url : 'php/connector.php?carpeta=<?php echo $pasada ?>'  // connector URL (REQUIRED)
					// lang: 'ru',             // language (OPTIONAL)
				}).elfinder('instance');
			});
                        
                        
                        
                        function llama(){
                            var contenedor = document.getElementById('elfinder');
                             var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 1)
        {
            contenedor.innerHTML = "Cargando...";
           // jQuery("#contenedor-TablaPortafolio").html("<img alt='cargando' src='img/ajax-loader.gif' />");
           
        }
        if (ajax.readyState == 4)
        {

            contenedor.innerHTML = ajax.responseText;
            //llamaTodo('asignaciones-Tabla');
          

        }
    }

    ajax.open("GET", "php/connector.php", true);
    ajax.send();
                            
                            
                        }
                        
                        
		</script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
