<?php
//session_start();
 //Llamado a la conexion de la BD
include_once ("../modelo/conexion.php");
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);//Objeto de la clase oracle


//Comprueba que variable GET no este vacia
if (!isset($_GET))
{
    $i = 0;
    $Escribe = $_GET['cedula'];

    //obtenemos los numeros de telefonos de la persona
    $arregloTelefonos = obtenerCorreosPersona($Escribe);
    
    if (is_array($arregloTelefonos))
    {
    $telefonos="";
    
    echo '
        <br><br>
        <table  width=\'100%\'>
        <thead>
        <tr style="background-color:#2da5da;">
        <th><input onclick="activarDesactivarChecks(document.getElementsByName(\'checksTelefonos\'), document.getElementById(\'checkGlobal\'));" type = "checkbox" id="checkGlobal"></th>
        <th>Correos</th>
        </tr>
        </thead>
        
        <tbody>';
    foreach ($arregloTelefonos as $value)
    {
        if ($i%2 == 0)
        {
            $pintar = 'style="background-color:#f0ffff;"';
        } else
        {
            $pintar = "";
        }
        
      
           
    $telefonos .= '<tr align ="center" '.$pintar.'>
                <td><input name="checksTelefonos" id="'.$value['EMAIL'].'" value= "'.$value['EMAIL'].'" type="checkbox"></td>
                <td>'.$value['EMAIL'].'</td>
                </tr>'; 
    $i++;
        
    }
        echo $telefonos;
        echo"</tbody></table>";
      
    } else
    {
        echo "$arregloTelefonos";
    }
    unset($_GET);
    exit();
}


 
 //
 //Obtine las Plantillas de la BD   
 //
 function cargarPlantilas1()
 {
    //Declaracion de varable global para la conexion
    global $conn;
    global $conex;
    //Query para obtener los registros
    $sql = "Select id, texto_A, texto_b from SR_SMS_TEXTO";  
    
    
    $st = $conex -> consulta($sql);
    // preparamos el statement para la consulta  
    //$st = oci_parse($conn,$sql);
    //ejecutamos el query  
    //oci_execute($st);
    //$filas[] = array();
 
    
   // if ($numrows > 0)
   // {
        
    while ($fila = $conex -> fetch_array($st)) 
    {
        $filas[] = $fila;

    }
    
    /*} else
    {

        $filas = "No Existen Datos de Plantillas";
        
    }*/
    
    unset($st);
    unset($fila);
    unset($sql);
    return $filas;
    
 }   
 
 //
 //Obtiene la CANTIDAD de SMS entrantes no leidos de la BD
 //
 
 
 //
 //Obtiene los correos de la persona
 //
 function obtenerCorreosPersona($idPersona)
 {
    //Declaracion de varable global para la conexion
    global $conn;
    global $conex;
    
    
    $sql2 = "SELECT PERSONA FROM tg_persona WHERE ID = '$idPersona'";
    
    $st2 = $conex -> consulta($sql2);
    
    //$st2 = oci_parse($conn,$sql2);
    //oci_execute($st2);
    //$contador = count(oci_fetch_assoc($st2));
    
        
   // $numrows = filas($st2, $filas);
    
      //  $filas = "La Cedula no se Encuentra Registrada";
       
       /*
        foreach ($filas as $value)
            {
                $id = $value[0];
            }
        */
      $fila = $conex -> fetch_array($st2);
      
    //Query para obtener los registro
    $sql = "select E.EMAIL from SR_EMAIL E WHERE E.ID_PERSONA = '".$fila['PERSONA']."'";   
    // preparamos el statement para la consulta  
    $st = $conex -> consulta($sql);
    //ejecutamos el query  
    //oci_execute($st);
    //$contador = count( oci_fetch_assoc($st));
    
    //$numrows = filas($st,$filas);
    $filas = array();
    while($row = $conex -> fetch_array($st)){
        
        $filas[]= $row; 
        
    } 
   /* 
    if (count($filas) == 0){
        echo $filas = "No Hay Correo electronicos para esta persona";
        exit();
    }
   */
     return $filas;
 
    unset($st);
    unset($sql);
    unset($fila);
    unset($st2);
    //unset($numrows);
   
    //return   $st.$filas.$otras;
    //liberamos memoria
   
 }

?>