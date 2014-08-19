

<?php
//session_start();
require_once '../controlador/c_directorios.php';
require_once '../controlador/c_agenda.php';
$usuario = $_SESSION['USER'];
$datos = obtenerDatosDirectorio($usuario);

//echo $usuario;

$a = new C_agenda($usuario);
?>

<script>
    $(document).ready(function(){
switch("<?php echo $a->get_tipo();?>"){ // obtenemos el tipo de usuario
			case 'T': 
			case 'C':	<?php 
							$valor = $a->get_asesor('ID'); // obtenemos la ID del asesor
							if(!($valor == '')){ ?>
							
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
								
							<?php } ?>
						break;
			case 'S': <?php 
							$valor = $a->get_supervisor('ID'); // Obtenemos la ID de supervisor
							if(!($valor == '')){ ?>
						
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
			
						<?php } ?>
						break;
                                                
                                                
                        case 'O':   <?php 
                                        $valor = $a->get_coordinador('ID'); // Obtenemos la ID de coordinador
                                        if(!($valor == '')){ ?>
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
                
                });
</script>

<?php



    if (!$datos){
        
        $permitir = false;
        $ruta = "";
        $root_carga = "error/UNAUTHORIZED.html";
        
        ?>
        <script>
          $(this).ready(function(){
          if (typeof bloquearSelects == 'function') {

            bloquearSelects();

            }

            
          });
        </script>
<?php
        
    } else {
        
        
        
        //=========================================
        
        
        
        //=========================================
     if (is_array($datos) ){
         
         $directorio = $datos[1];
         $tamañoMaximo = $datos[0];
                                  //$ruta = str_replace("C:", "", $pasada);
               
      
           
               
         
      
         
         if (empty($directorio))
         {
             $ruta = $_SESSION['raiz'];
             $permitir = true;
             
             $carpeta = $usuario;
             //$a = new C_agenda($usuario);
             switch ($usuario[0]){
              
                 
                 case 'G':
                      //  echo "gerente";
                        $directorio_a_guardar = "/$carpeta"; 
                        break;
                 
                 case 'O':
                    // echo "coordinador";
                     $a->get_gerente('ID');
                     $directorio_a_guardar = "/".$a->get_gerente('ID')."/$carpeta";
                     break;
                 
                 case 'S':
                     //echo "super";
                     $directorio_a_guardar = "/".$a->get_gerente('ID')."/".$a->get_coordinador('ID')."/$carpeta";
                     break;
                 
                 case 'T':
                 case 'C':
                    // echo "asesor";
                     $directorio_a_guardar = "/".$a->get_gerente('ID')."/".$a->get_coordinador('ID')."/".$a->get_supervisor('ID')."/$carpeta";
                     break;
                 
                 
                 
                 
             }
             $ruta .= $directorio_a_guardar;
         
             unset($a);
             
             guardarDirectorio($directorio_a_guardar, $usuario);
             
            
                if(!file_exists($ruta))
                {
                   
                 mkdir ($ruta, 0777, true);
             
                }
             
           
             
         } else {
            $ruta = $_SESSION['raiz'].$directorio;
                 if(!file_exists($ruta))
                {
                   
                 mkdir ($ruta, 0777, true);
             
                }
         $tamañoActual = MeDir($ruta);
          $tamañoActual = ($tamañoActual/1024);
        if ($tamañoActual >= $tamañoMaximo){
             
             $permitir = "true";
             
         } else {
             
             $permitir = "false";
             
         }
         
         }
         
         
     }
     
     $root_carga = '../elfinder/otro.php?pasada='.$ruta.'&permitir='.$permitir.'&usuario='.$usuario;
     
    }
 //  $ruta = "Y:/Recuperaciones/GTE01";
$directorios = '
    
    <script src="../controlador/c_portafolio.js"></script>
    <script src="../controlador/c_directorios.js"></script>
    
		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
    
        <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" media="screen" href="../elfinder/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../elfinder/css/theme.css">

    <!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="../elfinder/js/elfinder.min.js"></script>
				
				<script type="text/javascript" charset="utf-8">
                                var elf;
                                
                         
                               function cargar(carpeta){
			
                       
                        $("#elfinder").html("");
                        $("#elfinder").removeAttr("class");
                        

                       elf = $(\'#elfinder\').elfinder({
					url : \'../elfinder/php/connector.php?carpeta='.$usuario.'/\'+carpeta  // connector URL (REQUIRED)
					// lang: \'ru\',             // language (OPTIONAL)
				}).elfinder(\'instance\'); 
		 
                }


function nuevoRegistro(usuario){

window.location="?pantalla=directorios&carpeta="+usuario;


}

		</script>
			<body onload="">	
                    <form class="sky-form">
                    <div style="overflow: auto;">
				<fieldset>
                                
                                	<table>

                                                <tr>
							
							<td id="g">Gerente:</td>
							<td><select style="width: 220px;"  id="ger" disabled > <option>'. $a->get_gerente('NOMBRE').' ('.$a->get_gerente('ID').')</option></select></td>
                                                        <td id="o">Coor:</td>
							<td><select onchange="cargarSupervisores(); Directorio.recarga(this.value);" style="width: 220px;"  id="coor" > <option>Coodinador</option></select></td>
							<td id="s">Sup:</td>
							<td><select onchange="cargarAsesores(); Directorio.recarga(this.value);"  style="width: 220px;" id="sup" > <option>Supervisor</option></select></td>
							<td id="a">Asesor:</td>
							<td><select onchange="Directorio.recarga(this.value);"  style="width: 220px;" id="ges"> <option>Asesor</option></select></td>
						</tr>

					</table>

                     <!-- <div style="height: 400;" id="elfinder"></div> -->
                    
                         <iframe src="'.$root_carga.'" width="100%" height="70%" frameborder="0" scrolling="yes" id="iframe"></iframe>
                   <!--  <object id ="objeto" type="text/html" data="" width="100%" height="100%"> </object>  -->
                </fieldset>
                </div>
                </form>
             
                <div id="d"></div>
               
<script>
    var Directorio = new Directorio();
</script>
</body>






';


        
?>