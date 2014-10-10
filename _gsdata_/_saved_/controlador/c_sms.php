<?php
 //Llamado a la conexion de la BD


if (!empty($_POST))
{
    echo "<script>alert('datos del formulario');</script>";
    $fechaDeEnvio = $_POST['fechaEnvio'];
    $cedulaDePersona = $_POST['cedulaPersona'];
    $telefonosAEnviar = $_POST['checksTelefonos'];
    $contenidoDelSMS = $_POST['contenidoSMS'];
    print_r($telefonosAEnviar);
    echo "<br>$fechaDeEnvio<br>$cedulaDePersona<br>$contenidoDelSMS";
    
    exit();
    
    
} 


//Comprueba que se envio variable GET
if (!empty($_GET))
{
    $i = 0;
    $Escribe = $_GET['cedula'];
    $arregloTelefonos = obtenerTelefonosPersona($Escribe);
    
    
    if ($arregloTelefonos == array())
    {
    $telefonos="";
    
    echo "
        <label>Telefonos</label><br>
        <table  width='100%'>
        <thead>
        <tr style=\"background-color:#2da5da;\">
        <th><input type = \"checkbox\" name=\"checkGlobal\"></th>
        <th>Telefonos</th>
        </tr>
        </thead>
        
        <tbody>";
    foreach ($arregloTelefonos as  $colum => $detalles)
    {
        if ($i%2 == 0)
        {
            $pintar = 'style="background-color:#f0ffff;"';
        } else
        {
            $pintar = "";
        }
    
        
 
    
    $telefonos .= '<tr align ="center" '.$pintar.'>
                <td><input name="checksTelefonos[]" id="'.$detalles['PERSONA'].'" value= "'.$detalles['PERSONA'].'" type="checkbox"></td>
                <td>'.$detalles['TLF'].'</td>
                </tr>';
    $i++;
    }
  
    echo $telefonos;
      echo"</tbody></table>";
      
    } else
    {
        
        echo "$arregloTelefonos";
    }
}




 
 
 //Obtine las Plantillas de la BD   
 function cargarPlantilas()
 {
    //Declaracion de varable global para la conexion
    global $conn;
    //Query para obtener los registros
    $sql = "Select id, texto_A, texto_b from SR_SMS_TEXTO";   
    // preparamos el statement para la consulta  
    $st = oci_parse($conn,$sql);
    //ejecutamos el query  
    oci_execute($st);
    //$filas[] = array();

    while ($fila = oci_fetch_array($st)) 
    {
        $filas[] = $fila;

    }
    unset($st);
    unset($fila);
    unset($sql);
    return $filas;
    
 }   
 
 
 //Obtiene la CANTIDAD de SMS entrantes no leidos de la BD
 function smsNoLeidos($usuario = "NULL", $idPersona = "NULL")
 {
    //Declaracion de varable global para la conexion
    global $conn;
    
    if($usuario == "NULL" && $idPersona == "NULL")
    {
        $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1";
        
    } else if ($usuario == "NULL" && $idPersona != "NULL")
    
    {
        $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1 and CEDULA = {$idPersona}";
        
    } else if ($usuario != "NULL" && $idPersona == "NULL")
    {
        
        $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1 and USUARIO = {$usuario}";
        
    } else
    {
         $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1 and USUARIO = {$usuario} or CEDULA = {$idPersona}";   
    }
    
    //Query para obtener los registros
   
    // preparamos el statement para la consulta  
    $st = oci_parse($conn,$sql);
    //ejecutamos el query  
    oci_execute($st);
    

    while ($fila = oci_fetch_array($st)) 
    {
        $contador = $fila[0];

    }
    //liberamos memoria
    unset($st);
    unset($fila);
    unset($sql);
    return $contador;
 }
 
 //Obtiene SMS de Entrada de la BD
 function obtenerSmsEntrada()
 {
    //Declaracion de varable global para la conexion
    global $conn;
    //Query para obtener los registros
    $sql = "select * from SR_SMS_ENTRADA";   
    // preparamos el statement para la consulta  
    $st = oci_parse($conn,$sql);
    //ejecutamos el query  
    oci_execute($st);
  
    while ($fila = oci_fetch_array($st)) 
    {
       $filas[] = $fila;
      

    }
    //liberamos memoria
    
    
    return $filas;
 }
 
  //Obtiene numero de telefonos de la BD
 function obtenerTelefonosPersona ($idPersona)
 {
    //Declaracion de varable global para la conexion
    global $conn;
    
    
     $sql2 = "SELECT PERSONA FROM tg_persona WHERE ID = '$idPersona'";
    $st2 = oci_parse($conn,$sql2);
    oci_execute($st2);
    //$contador = count(oci_fetch_assoc($st2));
    
    $numrows = $conex->filas($st2);
    
  
     if ($numrows == 0)
        {
    
        $filas = "La Cedula no se Encuentra Registrada";
       
        
    } else
    {
       
        
        foreach ($filas as $value)
            {
                $id = $value[0];
            }
        
   /* $fila = oci_fetch_array($st2);
    var_dump($fila);
    while ($fila = oci_fetch_array($st2)) 
    {
        echo "while";
        $id = $fila[0];

    }*/
   
 
    //Query para obtener los registro
        $sql = "select T.PERSONA, T.COD_AREA||T.TELEFONO as TLF from TG_PERSONA_TEL_ADI T WHERE T.PERSONA = '$id'";   
    // preparamos el statement para la consulta  
    $st = oci_parse($conn,$sql);
    //ejecutamos el query  
    oci_execute($st);
    //$contador = count( oci_fetch_assoc($st));
    $numrows = $conex->filas($st);
    
    if ($numrows == 0)
        {
    
        $filas = "No Hay Telefonos Registrados para este Deudor";
       
        
    } else
    {
       
        
    
        
        
        while ($fila = oci_fetch_array($st)) 
    {
        $filas[] = $fila;
        
        

    }
    
    }
    } //FIN de Primer else
    
    //return   $st.$filas.$otras;
    //liberamos memoria
    unset($st);
    unset($fila);
    unset($sql);
    return $filas;
 }

 
function busquedaFiltro($valor){
    
    echo $valor;
    
}


function cargarSmsRecibidos()
{
    
    
    
}

 
?>