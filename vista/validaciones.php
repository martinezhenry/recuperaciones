<?php
@session_start();
require_once $_SESSION['ROOT_PATH']."/modelo/conexion.php";
$conex = oci_connect("VPCTOTAL", "0r4c13lo6", '192.20.15.77/vpc'); 

if (isset($_POST['cargarTipoGestiones'])){
    
    $tipoGestiones = cargarTipoGestiones($_POST['cliente']);
    
    echo '<select id="tipoGestiones" onchange="$(\'#tablaSel\').removeAttr(\'disabled\');"><option  selected disabled>Seleccione...</option>';
    if (isset($tipoGestiones)){
        
         echo '<option value="'.$_POST['cliente'].'*-1*-1">GENERAL</option>';
        
    foreach ($tipoGestiones as $value) {
        
        echo '<option value="'.$value['TABLA_GESTION'].'*'.$value['CODIGO_GESTION'].'*'.$value['GRUPO_GESTION'].'">'.$value['DESCRIPCION'].'</option>';
        
    }
    
    }
    
    echo '</select>';
    exit();
} else if (isset ($_POST['traerCampoTabla'])){
    
    
    $cam = traerCampoTabla($_POST['tabla']);
  
    echo '<select id="camposT"><option  selected disabled>Seleccione...</option>';
    if (isset($cam)){
    foreach ($cam as $value) {
        
        echo '<option value="'.$value['Campo'].'">'.$value['ALIAS'].'</option>';
        
    }
    
    }
    
    echo '</select>';
    exit();
    
    
} else if (isset ($_POST['guardarValidacion'])){
    
    
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];
    $mensaje = $_POST['mensaje'];
    $tablaGestion = $_POST['tablaGestion'];
    $grupoGestion = $_POST['grupoGestion'];
    $codigoGestion = $_POST['codigoGestion'];
    $operador = $_POST['operador'];
    $tipoProceso = $_POST['tipoProceso'];
    
   
    $sql = "INSERT INTO SR_PARAMETROS (CAMPO, VALOR, OPERADOR, TABLA_GESTION, GRUPO_GESTION, COD_GESTION, MENSAJE, TIPO_PROCESO)
            VALUES
            ('$campo', :valor, '$operador', '$tablaGestion', '$grupoGestion', '$codigoGestion', '$mensaje', '$tipoProceso')
            ";
  
  //  $conex = oci_connect("VPCTOTAL", "cable", '192.20.15.29/vpc2'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
            $campos = "";
    		$query = oci_parse($conex,$sql);
                
                    oci_bind_by_name($query, ':valor', $valor, 350);
                    
                    
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
                } else {
                    
                            echo "Validación Guardada";
                }
    
  
    
    exit();
    
} else if (isset ($_GET['eliminar'])){
    
    
    $sql = "DELETE SR_PARAMETROS WHERE ID_PARAMETROS = '".$_GET['eliminar']."'";
      //  $conex = oci_connect("VPCTOTAL", "cable", '192.20.15.29/vpc2'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
            $campos = "";
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
                }
                header("location:validaciones.php");
                exit();
    
} else if (isset($_POST['cargarCampos'])){
    
    
                       
                $campos = cargarCampos($_POST['tabla']);

               echo '<select id="campoSel" disabled onchange="$(\'#operadores\').removeAttr(\'disabled\'); $(\'#operadores\').prop(\'selectedIndex\', \'0\'); $(\'#operadores\').change();" id="campos">
                    <option selected disabled>Seleccione...</option>';

                     foreach ($campos as $value) { 
                    echo '<option value="'.$value['CAMPO'].'*'.$value['TABLA'].'">'.$value['ALIAS'].'</option>';

                    }

               echo '</select>';
               exit();
    
}


?>

<html>
    
    <head>
        <?php //llamado de ccs
  require_once 'ccs.php';
  //llamados a js
  require_once 'js.php';
  ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    
    
    <script>
    
    function cargarTipoGestiones(cliente){
        
        
       	var parametros = {"cargarTipoGestiones" : 1,
                           "cliente" : cliente
                          
                       };
	$.ajax({
		  type: "POST",
		  url: 'validaciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#tipoGes').html(result);
                     
                     
		   }
  	});
        
    }
    
        function cargarCampos(tabla){
        
        
       	var parametros = {"cargarCampos" : 1,
                           "tabla" : tabla
                          
                       };
	$.ajax({
		  type: "POST",
		  url: 'validaciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#campos').html(result);
                     $('#campoSel').removeAttr("disabled");
                     
		   }
  	});
        
    }
    
    
    function proceso(tabla){
      
        $('#valor').attr("disabled", "disabled");
        $('#mensajeError').attr("disabled", "disabled");
        $('#operaciones').css("display", "block");
               	var parametros = {"traerCampoTabla" : 1,
                           "tabla" : tabla
                          
                       };
	$.ajax({
		  type: "POST",
		  url: 'validaciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#camposTabla').html(result);
                     
		   }
  	});
        
    }
    
    
    
    function guardarValidacion(){
        
        var operador = $('#operadores').val();
        var tipoGestiones = $('#tipoGestiones').val();
        tipoGestiones = tipoGestiones.split("*");
        var tablaGestion = tipoGestiones[0];
        var codigoGestion = tipoGestiones[1];
        var grupoGestion = tipoGestiones[2];
        var campo = $('#campoSel').val();
        campo = campo.split("*");
        
        if (operador == 41){
        
        var parametros = {"guardarValidacion" : 1,
           "valor" : $('#txtOperacion').val(),
           "operador" : operador,
           "tipoProceso"  : 'proceso',
           "mensaje" : $('#mensaje').val(),
           "campo" : campo[0],
           "tablaGestion" : tablaGestion,
           "codigoGestion" : codigoGestion,
           "grupoGestion"  : grupoGestion
       };
            
            
            
        }else if ( operador == 42){
            
            var parametros = {"guardarValidacion" : 1,
           "valor" : $('#txtOperacion').val(),
           "operador" : operador,
           "tipoProceso"  : 'comparacion',
           "mensaje" : $('#mensaje').val(),
           "campo" : campo[0],
           "tablaGestion" : tablaGestion,
           "codigoGestion" : codigoGestion,
           "grupoGestion"  : grupoGestion
       };
            
            
        } else {
           var parametros = {"guardarValidacion" : 1,
           "valor" : $('#valor').val(),
           "operador" : operador,
           "tipoProceso"  : 'validacion',
           "mensaje" : $('#mensajeError').val(),
           "campo" : campo[0],
           "tablaGestion" : tablaGestion,
           "codigoGestion" : codigoGestion,
           "grupoGestion"  : grupoGestion
       };
            
        }
        

	$.ajax({
		  type: "POST",
		  url: 'validaciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     alert(result);
                     location.reload();
                     
		   }
  	});
        
    }
    
    $(document).ready(function(){
        
     llamaTodo('validaciones-Tabla');   
        
        
    });
    </script>
    
    
    </head>
    
    <body class="bg-blue">
        
        <div>
            <form class="sky-form">
                
                <fieldset>
                    <label>Cliente</label>
                    <div>
                    <select id="clientes" style="width: 300px;" onchange="cargarTipoGestiones(this.value);">
                        <option  selected disabled>Seleccione...</option>
                        <option value="-1">General</option>
                        <?php
                        $clientes = cargarClientes();
                        
                         
                        foreach ($clientes as $value) {
    
                        ?>
                        
                       <option value="<?php echo $value['TABLA_GESTION']; ?>"><?php echo $value['NOMBRE']; ?></option>
                        
                        <?php } ?>
                        
                        
                        
                    </select>
                        </div>
                    <br>
                    
     
                        <label>Tipo Gestion</label>
                        <div id="tipoGes">
                            <select disabled="">
                                
                                <option>Seleccione...</option>
                                <option>Gestiones</option>
                                <option>Operadores</option>
                            </select>
                        </div>
     
                        <br>
                                              <label>Tablas</label>
                        <div id="tablas">
                            <select id='tablaSel' disabled onchange="cargarCampos(this.value);">
                                
                                <option>Seleccione...</option>
                                <?php 
                                $tablas = cargarTablas();
                                foreach ($tablas as $value) {
                                ?>
                                <option value="<?php echo $value['TABLA'] ?>"><?php echo $value['TABLA'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
     
                        <br>
                        
                        
                    <label>Campos</label>
                    <div id='campos'>
                        <select disabled>
                            <option>Seleccione...</option>
                        </select>
                    </div>
                    <br>
                        
                    <label>Operadores</label>
                  <?php  
                  $operadores = cargarOperadores();
                   ?>
                    <div>
                        <select onchange="if (this.value == 41 || this.value == 42) { proceso($('#campoSel').val()); } else { $('#valor').removeAttr('disabled'); $('#mensajeError').removeAttr('disabled'); $('#operaciones').css('display', 'none'); }" id="operadores" disabled="">
                        <option selected disabled>Seleccione...</option>
                          
                        <?php foreach ($operadores as $value) {
                                               
                                            ?>
                        <option value="<?php echo $value['ID_OPERADOR']; ?>"><?php echo $value['SIMBOLO']; ?></option>
                        
                        <?php } ?>
                       
                    </select>
                    </div><br>
                    
                    <div style="display: none;" id="operaciones">
                        
                        <textarea name="txtOperacion" id="txtOperacion" style="height: 50px; width: 500px"></textarea> <textarea style="height: 50px; width: 400px" id="mensaje" placeholder="Observaciones"></textarea> <br>
                        <button onclick="$('#txtOperacion').val($('#txtOperacion').val()+this.value); $('#txtOperacion').focus();" style="width: 25px; height: 25px;" type="button" value="+">+</button>
                        <button onclick="$('#txtOperacion').val($('#txtOperacion').val()+this.value); $('#txtOperacion').focus();" style="width: 25px; height: 25px;" type="button" value="-">-</button>
                        <button onclick="$('#txtOperacion').val($('#txtOperacion').val()+this.value); $('#txtOperacion').focus();" style="width: 25px; height: 25px;" type="button" value="/">/</button>
                        <button onclick="$('#txtOperacion').val($('#txtOperacion').val()+this.value); $('#txtOperacion').focus();" style="width: 25px; height: 25px;" type="button" value="*">*</button>
                        
                        <br>
                        <label>Columnas</label>
                        <div id="camposTabla"></div> <a href="#" onclick="$('#txtOperacion').val($('#txtOperacion').val()+';'+$('#camposT').val()+';'); ">Agregar Campo</a>
                        
                    </div>
                    
                    <br>
                    
 
                    <label>Valor</label>
                    <div>
                     <input name="valor" id="valor" type="text"/>
                     <input name="valor" id="mensajeError" placeholder="Mensaje de Error" type="text"/>
                    </div>
                        
                    <br>
                    
                    <label><a href="#" onclick="guardarValidacion();">Agregar Validación</a></label>
                     
                </fieldset>
                <hr>
                <fieldset>
                    <label><h2>Validaciones y Procesos Actuales</h2></label>
                    <div id='tablaValidaciones'>
                        
                        <?php 
                        $tabla = cargarValidaciones();
                        
                        echo $tabla;
                        ?>
                        
                        
                    </div>
                    
                </fieldset>
                
                
            </form>
               
            
            
        </div>
        
        
    </body>
    
</html>


<?php

  
            


function cargarOperadores(){
    
    $sql = "SELECT * FROM SR_OPERADORES";
    global $conex;
    //  $conex = oci_connect("VPCTOTAL", "0r4c13lo6", '192.20.15.77/vpc'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
    
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
		}
    //$st = $conex->consulta($sql);
    $operadores="";
    while ($fila = oci_fetch_array($query))
    {
        
        $operadores[] = $fila;
        
        
    }
   
       oci_close($conex);
    return $operadores;
          
    
    
    
}


function cargarClientes(){
    
    $sql = "SELECT * FROM TG_CLIENTE WHERE STATUS_CLIENTE = 'A' AND DEPARTAMENTO = 'R' ORDER BY NOMBRE";
    global $conex;
     // $conex = oci_connect("VPCTOTAL", "cable", '192.20.15.29/vpc2'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
    
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
		}
    //$st = $conex->consulta($sql);
    
    while ($fila = oci_fetch_array($query))
    {
        
        $clientes[] = $fila;
        
        
    }
   
       oci_close($conex);
    return $clientes;
          
    
    
    
}



function cargarTipoGestiones($cliente){
    
        $sql = "SELECT GRUPO_GESTION, CODIGO_GESTION, DESCRIPCION, TABLA_GESTION FROM SR_CODIGO_GESTION
                WHERE TABLA_GESTION = '$cliente' AND STATUS_CODIGO_GESTION = 'A' ORDER BY DESCRIPCION";
        global $conex;
     // $conex = oci_connect("VPCTOTAL", "cable", '192.20.15.29/vpc2'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
            $tipoGestiones = "";
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
		}
    //$st = $conex->consulta($sql);
    
    while ($fila = oci_fetch_array($query))
    {
        
        $tipoGestiones[] = $fila;
        
        
    }
   
       oci_close($conex);
    return $tipoGestiones;
    
    
}


function cargarCampos($tabla){
    
            $sql = "SELECT CAMPO, ALIAS, TABLA FROM SR_ALIAS_CAMPOS WHERE TABLA = '$tabla'";
        global $conex;
    //  $conex = oci_connect("VPCTOTAL", "0r4c13lo6", '192.20.15.77/vpc'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
            $campos = "";
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
		}
    //$st = $conex->consulta($sql);
    
    while ($fila = oci_fetch_array($query))
    {
        
        $campos[] = $fila;
        
        
    }
   
       oci_close($conex);
    return $campos;
    
    
    
}

function traerCampoTabla($tabla){
    
   $tabla = explode("*", $tabla);
   
    $sql = "select campo \"Campo\", alias
            from sr_alias_campos
            where tabla = '$tabla[1]'";
    
   
        global $conex;
      //$conex = oci_connect("VPCTOTAL", "0r4c13lo6", '192.20.15.77/vpc'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
            $campos = "";
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
		}
    //$st = $conex->consulta($sql);
    
    while ($fila = oci_fetch_array($query))
    {
        
        $campos[] = $fila;
        
        
    }
   
       oci_close($conex);
       
    return $campos;
    
    
    
}


function cargarValidaciones(){
    
    
    $sql = "SELECT P.ID_PARAMETROS, P.CAMPO, P.VALOR, P.MENSAJE, P.TABLA_GESTION, P.COD_GESTION, P.GRUPO_GESTION, P.TIPO_PROCESO, O.SIMBOLO FROM SR_PARAMETROS P, SR_OPERADORES O WHERE O.ID_OPERADOR = P.OPERADOR";
    global $conex;
        //  $conex = oci_connect("VPCTOTAL", "0r4c13lo6", '192.20.15.77/vpc'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
          

            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            }
 
           
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
		}
    //$st = $conex->consulta($sql);
    echo "<table style=\"font-size: 11px;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"display dataTable\" id=\"validaciones-Tabla\">
         <thead>
            <th>Campo</th>
            <th>Operador</th>
            <th>Valor</th>
            <th>Mensaje</th>
            <th>Tabla Gestion</th>
            <th>Grupo Gestion</th>
            <th>Codigo Gestion</th>
            <th>Tipo Proceso</th>
          
            <th></th>
         </thead>
         <tbody>

         ";
    while ($fila = oci_fetch_array($query))
    {
        
        echo "<tr class='gradeA' id='".$fila['ID_PARAMETROS']."'>
               <td>".$fila['CAMPO']."</td>
               <td>".$fila['SIMBOLO']."</td>
               <td>".$fila['VALOR']."</td>
               <td>".$fila['MENSAJE']."</td>
               <td>".$fila['TABLA_GESTION']."</td>
               <td>".$fila['GRUPO_GESTION']."</td>
               <td>".$fila['COD_GESTION']."</td>
               <td>".$fila['TIPO_PROCESO']."</td>
               <td><a href='validaciones.php?eliminar=".$fila['ID_PARAMETROS']."'>Eliminar</a></td>
             </tr>
             ";
        
        
        
    }
   
       oci_close($conex);
       
    return;
    
}


function cargarTablas(){
    
    $sql = "SELECT TABLA FROM SR_ALIAS_CAMPOS GROUP BY TABLA";
    
     global $conex;
   //   $conex = oci_connect("VPCTOTAL", "0r4c13lo6", '192.20.15.77/vpc'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$conex) {
            $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
          
            } 
 
            $tablas = "";
    		$query = oci_parse($conex,$sql);
		//Ejecutamos la sentencia en oracle 
               
		$resultado = oci_execute($query);
		if(!$resultado){
		echo 'Oracle 10G Error: '.oci_error()['code'];
	
		}
    //$st = $conex->consulta($sql);
    
    while ($fila = oci_fetch_array($query))
    {
        
        $tablas[] = $fila;
        
        
    }
   
       oci_close($conex);
       
    return $tablas;
    
    
}
oci_close($conex);
?>