<?php
@session_start();
 //Llamado a la conexion de la BD
 //include("../modelo/conexion.php");
include_once ("../modelo/conexion.php");
require_once 'c_autorizar.php';
 $conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

 //Se encarga de registrar los SMS en la BD
 function enviarSMS($parametros)
 {
    $id = 1;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return "No Autorizado!";
        
    } else {
        global $conex;
        $NumerosEnviados = "";
        $status = "V";
        $usuario = $_SESSION['USER'];
        $hoy = date("d/m/Y G:i:s");
        $idPersona = $parametros['ID_Persona'];
        $fechaDeEnvio = $parametros['fechaEnvio'];
        $cedulaDePersona = $parametros['cedula'];
        $telefonosAEnviar = explode(",",$parametros['telefonos']);
        $contenidoDelSMS = $parametros['contenidoSMS'];


        print_r($telefonosAEnviar);
        
        
        if (isset($parametros['recordatorio'])){
        // Se obtienen los valores a guardar


        $montoPromesa = $parametros['montoPromesa'];
        $fechaPromesa = $parametros['fechaPromesa'];
        $fechaPromesa = date("d/m/Y",strtotime($fechaPromesa));


           } else {

            $montoPromesa = "";
        $fechaPromesa = "";

       }




        $fechaDeEnvio = date("d/m/Y",strtotime($fechaDeEnvio));

        // Se comprueba los telefonos a enviar con la funcion comprobarTelefonos
        $telefonosProcesados = comprobarTelefonos($telefonosAEnviar, $fechaDeEnvio);

        // Se imprime mensaje en caso de que existan telefonos a los que no se enviara SMS
        if (!empty($telefonosProcesados))
        {

            echo "No se Envio SMS a estos numeros: ".implode(", ", $telefonosProcesados)." ya que se envio SMS anteriormente uno el dia de hoy.";

        }


        // Se verifica que la variable obtenida sea un arreglo
        if(is_array($telefonosProcesados)){

            // Se comparan los arreglos para descartar valores repetidos
        $telefonosAEnviar = array_diff($telefonosAEnviar, $telefonosProcesados);


        // se recorre arreglo
        foreach ($telefonosAEnviar as $value)
        {

            // query que se ejecutara
         $sql = "insert into sr_SMS (id_persona, id_gestor, cedula, monto_promesa, telefono, sms, f_envio, f_promesa, estatus, fecha, origen)
                values ('$idPersona', '$usuario', '$cedulaDePersona', '$montoPromesa', '$value', '$contenidoDelSMS', '$fechaDeEnvio', '$fechaPromesa', '$status', SYSDATE, 'M')";


         // Se ejecuta el Query
         $st = $conex -> consulta($sql);

         $NumerosEnviados .= $value.", ";

        }
        // Se imprime mesaje a mostrar al usuario
        echo "<br><br>SMS Enviado a: $NumerosEnviados";

        }  //FIN IF

       // Se destruyen variables
        unset($telefonosAEnviar);
        unset($telefonosProcesados);
        unset($cedulaDePersona);
        unset($usuario);
        unset($montoPromesa);
        unset($contenidoDelSMS);
        unset($fechaDeEnvio);
        unset($fechaPromesa);
        unset($status);


     } //FIN de FUNSION enviarSMS
 }
 
 //     ***************************     POST    **********************      **********

 
 //Verifica que variable POST no este vacia
if (!empty($_POST))
{
    
    
    //Verificamos que formulario se esta enviando
   if (isset($_POST['form_Enviar']))
   {
     
     // Llamamos a la funcion enviarSMS()
     enviarSMS($_POST);
     
      
      
    
   } else if (isset($_POST['form_Leer'])) // Se verifica que en formulario recibido se leer
   {
       echo "<script>alert('LEER');</script>";
       exit();
   }
 
    
   if (isset($_POST['responder'])){
       
       
       
       $usuario = $_SESSION['USER'];
       $cedulaDePersona = $_POST['cedula'];
       $montoPromesa = "";
       $telefono = $_POST['tlf'];
       $contenidoDelSMS = $_POST['respuesta'];
       $fechaDeEnvio = date("d/m/Y");
       $fechaPromesa = "";
       $status = "";
       $idPersona = $_POST['idPersona'];
       
               
               $sql = "insert into sr_SMS (id_persona, id_gestor, cedula, monto_promesa, telefono, sms, f_envio, f_promesa, estatus, fecha, origen)
            values ('$idPersona', '$usuario', '$cedulaDePersona', '$montoPromesa', '$telefono', '$contenidoDelSMS', '$fechaDeEnvio', '$fechaPromesa', '$status', '".date("d/m/Y")."', 'M')";
            
        $st = $conex -> consulta($sql);
        echo "Enviado";
        exit();
       
   }
    
    
} 


//     ***************************     GET    **********************      **********


//Comprueba que variable GET no este vacia
if (!empty($_GET))
{
    
    if (isset($_GET['cedula'])) // Se verifica que se reciba varible cedula por metodo GET
    {
        
        $i = 0;
        $Cedula = $_GET['cedula'];
        $tipo = $_GET['tipo'];
        
        //obtenemos los numeros de telefonos de la persona
        $arregloTelefonos = obtenerTelefonosPersona($Cedula, $tipo);


        // Se verifica que resultado obtenido sea un arreglo
      
        $telefonos="";
        // Se imprime la tabla
        echo '
            <br><br>
            
            <table  style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" class="display dataTable" id="telefonosSMS-Tabla">
            <thead>
            <tr class="center">
            <th><input onclick="activarDesactivarChecks(document.getElementsByName(\'checksTelefonos\'), document.getElementById(\'checkGlobal\'));" type = "checkbox" id="checkGlobal"></th>
            <th>Telefonos</th>
            </tr>
            </thead>

            <tbody>';
          if (is_array($arregloTelefonos))
        {
          
        foreach ($arregloTelefonos as $value)
        {
            if ($tipo == "NULL"){ $ci = buscaCedula($value['PERSONA']);} else { $ci = $_SESSION['Cedula_P']; }
          
            
       if ($i%2==0) // Vemos si 54 dividido en 2 da resto 0 si lo da
                { $par = "odd"; } //escribo Par
                else //Sino
                { $par = "even"; }

                $color = "gradeA ".$par;

        $telefonos .= '<tr class="'.$color.'">
                    <td class="center"><input name="checksTelefonos" id="'.$value['PERSONA'].'" value= "'.$value['TLF'].'" type="checkbox"></td>
                    <td>'.$value['TLF'].' </td>
                        
                    </tr>
                     
                    '; 
        $i++;
        $idpersona2 = $value['PERSONA'];

        }
            } else // En caso que el resultado obtenido no sea una arreglo
        {
            echo '<td valign="top" colspan="2" class="center" style="background-color: #9FAFD1;">No existen registros</td>';
        }

            echo $telefonos;
            echo"</tbody></table>"
            .'<input  type="hidden" value="'.$idpersona2.'" id="ID_Persona" name="ID_Persona">
                     <input hidden type="text" value="'.$ci.'" id="CEDULA_Persona" name="CEDULA_Persona">'
            ;

    
        unset($_GET);
        exit();
        
    } else if (isset($_GET["cargarSmsRecibidos"])){
        
        
             $usuario = $_SESSION['USER'];
     
     if ($_GET['filtro'] == "fecha"){
         $fechaInicio = $_GET['fechaInicio'];
         $fechaFin = $_GET['fechaFin'];
         
        $fechaInicio = date("d/m/Y",strtotime($fechaInicio));
        $fechaFin = date("d/m/Y",strtotime($fechaFin));
         
     $sql = "SELECT * FROM SR_SMS_ENTRADA
              WHERE
              USUARIO='$usuario'
              AND FECHA_ENTREGA >= '$fechaInicio' 
              AND FECHA_ENTREGA <= '$fechaFin'
              ";
     } else if ($_GET['filtro'] == "status"){
         $status = $_GET['status'];
           
            ($status == "NULL") ? $condicion = "" : $condicion = "AND STATUS = '$status'";
      $sql = "SELECT * FROM SR_SMS_ENTRADA
               WHERE
               USUARIO='$usuario'
               ".$condicion;
        
         
     } else if ($_GET['filtro'] == "ambos"){
         $fechaInicio = $_GET['fechaInicio'];
         $fechaFin = $_GET['fechaFin'];
         $status = $_GET['status'];
         $fechaInicio = date("d/m/Y",strtotime($fechaInicio));
         $fechaFin = date("d/m/Y",strtotime($fechaFin));
                   
            ($status == "NULL") ? $condicion = "" : $condicion = "AND STATUS = '$status'";
      $sql = "SELECT * FROM SR_SMS_ENTRADA
               WHERE
               USUARIO='$usuario'
               AND FECHA_ENTREGA >= '$fechaInicio'
               AND FECHA_ENTREGA <= '$fechaFin'
               ".$condicion;

        
         
     } else {
    
     $sql = "SELECT * FROM SR_SMS_ENTRADA
              WHERE
              (USUARIO='$usuario'
              AND FECHA_ENTREGA >= sysdate - to_char(sysdate, 'd')+1 
              AND FECHA_ENTREGA <= sysdate - to_char(sysdate, 'd')+7)
              OR
              (USUARIO='$usuario'
              AND STATUS = '1')   
              ";
        
     }
        
      
            $smsRecibidos = obtenerSmsEntrada($sql);
                //*****************  INICIO    ****************************
         
            echo "<table style='font-size: 11px;' cellpadding='0' cellspacing='0' border='1' class='' width='100%'><thead>
                    <th>De:</th>
                    <th>Cedula</th>
                    <th>Fecha</th>
                    
                    
                  </thead></table><div style='height: 150px; overflow: auto;'><table style='width: 100% !important; font-size: 11px;' cellpadding='0' cellspacing='0' border='1' class='display dataTable' id='SMSRecibido-Tabla' width='100%'><thead><th></th>
                    <th></th>
                    <th></th>
                   
                    
                  </thead>
                  
                  <tbody>";
            if (is_array($smsRecibidos)){
            $i=0;
            foreach ($smsRecibidos as $value) {
                     if ($i%2==0) // Vemos si 54 dividido en 2 da resto 0 si lo da
                { $par = "odd"; } //escribo Par
                else //Sino
                { $par = "even"; }
                if ($value['STATUS'] == 1){
                    
                    $color = "sinLeer";
                    $accion = "cambiarStatusSMS(this.id);";
                    
                } else {
                    
                    $color = "gradeA ".$par;
                    $accion = "";
                    
                }
           
                
                echo "<tr id='".$value['ID_SMS']."' style='cursor: pointer;' onclick='".$accion." colocarTexto(document.getElementById(\"contenidoSMS\"), document.getElementById(\"contenido".$i."\").innerHTML);' id = '".$value['ID_SMS']."' class = '".$color."'>"
                        . "<td><div id='tlf".$i."' style='height:15px; overflow: hidden;'>".$value['NUMERO_TELEFONO']."</div></td>"
                        . "<td><div id='contenido".$i."' hidden class='content' style='height:15px; overflow: hidden;'>".$value['CONTENIDO_SMS']."</div><div class='a' onclick='popupAExpediente(document.getElementById(\"personaResp".$i."\").innerHTML);' style='height:15px; overflow: hidden;'>".$value['CEDULA']."</div></td>"
                        . "<td><div class='fecha' style='height:15px; overflow: hidden;'>".$value['FECHA_ENTREGA']."</div>"
                        . ""
                        . "<div id='".$value['NUMERO_TELEFONO']."' onclick='responderSMS(".$i.");' class='a' >Responder</div><div hidden id='cedulaResp".$i."'>".$value['CEDULA']."</div><div hidden id='personaResp".$i."'>".$value['ID_PERSONA']."</div>"
                        . "</td>"
                       
                        . "</tr>";
             $i++;   
            }
            } else { echo '<tr valign="top" colspan="2" class="center" style="background-color: #9FAFD1; width: 100%"><td><center>No existen registros</center><td><td></td><td></td></tr>'; }
            
            echo "</tbody></table></div>";
        exit();
    
        
        
    } else if (isset ($_GET['buscarVaribles'])){
        
        
        $persona = $_GET['idPersona'];
        $idPlantilla = $_GET['idPlantilla'];
        
    
        
        $sql = "SELECT V.CAMPO, V.FUNCION FROM SR_VARIABLES V, SR_PLANTILLAS_VARIABLES PV WHERE PV.ID_VARIABLE = V.ID AND PV.ID_PLANTILLA = '$idPlantilla' AND STATUS = 1";
        
        $st = $conex -> consulta($sql);

        // Recorremos las filas obtenidas
        while ($value = $conex -> fetch_array($st)) 
        {
            $campos[] = $value;

        }
        
        if (isset($campos)){
            
            
            foreach ($campos as $value){
                
                
                $funcion = $value['FUNCION'];
                
                $sql = "select RECUPERACIONES_PHP.$funcion('$persona', '".$_SESSION['USER']."') from dual";
               // $sql = "select RECUPERACIONES_PHP.F_BUSCA_CUENTAS('7993577', 'T7038') from dual";
              
                //$sql = "EXEC $funcion('$persona')";
                $st = $conex -> consulta($sql);
                while ($val = $conex -> fetch_array($st)) 
            {
                $respuesta[$value['CAMPO']] = $val[0];
               
            }
 
 
               //echo $sql."<br>"; 
                
            }
            
        }
        
        
        $sql = "SELECT TEXTO_A FROM SR_PLANTILLAS WHERE ID = '$idPlantilla'";
        $st = $conex -> consulta($sql);
        $value = $conex -> fetch_array($st);
        $texto = $value[0];
       // echo $texto;
       // $texto = explode(";",$texto);
       
        foreach ($respuesta as $ind => $value) {
            
           $texto = str_replace($ind , $value , $texto);
            
        }
        $texto = str_replace(";" , "" , $texto);
        echo $texto;
        //$cuentas = BuscaCuentas($Persona);
        
    // print_r($campos);
        
        
        
    } else if (isset($_GET['contador'])){
        
        echo smsNoLeidos($_SESSION['USER'], "NULL");
        
    } else if (isset ($_GET['cargarSmsEnviados'])){
        
        
             $usuario = $_SESSION['USER'];
     
     if ($_GET['filtro'] == "fecha"){
         $fechaInicio = $_GET['fechaInicio'];
         $fechaFin = $_GET['fechaFin'];
         
        $fechaInicio = date("d/m/Y",strtotime($fechaInicio));
        $fechaFin = date("d/m/Y",strtotime($fechaFin));
         
     $sql = "SELECT * FROM SR_SMS
              WHERE
              ID_GESTOR='$usuario'
              AND FECHA >= '$fechaInicio' 
              AND FECHA <= '$fechaFin'
               ORDER BY FECHA DESC";
     } else if ($_GET['filtro'] == "status"){
         $status = $_GET['status'];
           
            ($status == "NULL") ? $condicion = "" : $condicion = "AND ESTATUS = '$status'";
      $sql = "SELECT * FROM SR_SMS
               WHERE
               ID_GESTOR='$usuario'
               ".$condicion." ORDER BY FECHA DESC";
        
         
     } else if ($_GET['filtro'] == "ambos"){
         $fechaInicio = $_GET['fechaInicio'];
         $fechaFin = $_GET['fechaFin'];
         $status = $_GET['status'];
         $fechaInicio = date("d/m/Y",strtotime($fechaInicio));
         $fechaFin = date("d/m/Y",strtotime($fechaFin));
                   
            ($status == "NULL") ? $condicion = "" : $condicion = "AND ESTATUS = '$status'";
      $sql = "SELECT * FROM SR_SMS
               WHERE
               ID_GESTOR='$usuario'
               AND FECHA >= '$fechaInicio'
               AND FECHA <= '$fechaFin'
               ".$condicion." ORDER BY FECHA DESC";

        
         
     } else {
     
     $sql = "SELECT * FROM SR_SMS
              WHERE
              (ID_GESTOR='$usuario'
              AND FECHA >= sysdate - to_char(sysdate, 'd')+1 
              AND FECHA <= sysdate - to_char(sysdate, 'd')+7)
              OR
              (ID_GESTOR='$usuario'
              AND ESTATUS = '1') ORDER BY FECHA DESC
              ";
        
     }
        
      
            $smsRecibidos = obtenerSmsEntrada($sql);
                //*****************  INICIO    ****************************
         
            echo "<table style='font-size: 11px;' cellpadding='0' cellspacing='0' border='1' class='display dataTable' width='100%'><thead>
                    <th>Para:</th>
                    <th>Cedula</th>
                    <th>Fecha</th>
                    <th></th>
                    
                  </thead></table><div style='height: 150px; overflow: auto;'><table style='font-size: 11px;' cellpadding='0' cellspacing='0' border='1' class='display dataTable' id='SMSEnviado-Tabla'><th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    
                  </thead>
                  
                  <tbody>";
            if (is_array($smsRecibidos)){
            $i=0;
            foreach ($smsRecibidos as $value) {
                     if ($i%2==0) // Vemos si 54 dividido en 2 da resto 0 si lo da
                { $par = "odd"; } //escribo Par
                else //Sino
                { $par = "even"; }
                if ($value['ESTATUS'] == "P"){
                    
                    //$color = "sinLeer";
                    $color = "gradeA ".$par;
                    $estado = "Pendiente";
                    
                } else if ($value['ESTATUS'] == "E"){
                    
                    $color = "gradeA ".$par;
                   $estado = "Enviado";
                    
                } else if ($value['ESTATUS'] == "R"){
                    
                    $color = "gradeA ".$par;
                   $estado = "Rechazado";
                    
                }
           
                
                echo "<tr id='".$value['ID']."' style='cursor: pointer;' onclick='colocarTexto(document.getElementById(\"contenidoSMS1\"), document.getElementById(\"contenido1".$i."\").innerHTML);' id = '".$value['ID']."' class = '".$color."'>"
                        . "<td><div id='tlf1".$i."' style='height:15px; overflow: hidden;'>".$value['TELEFONO']."</div></td>"
                        . "<td><div id='contenido1".$i."' hidden class='content' style='height:15px; overflow: hidden;'>".$value['SMS']."</div><div class='a' onclick='popupAExpediente(document.getElementById(\"personaResp1".$i."\").innerHTML);' style='height:15px; overflow: hidden;'>".$value['CEDULA']."</div></td>"
                        . "<td><div class='fecha' style='height:15px; overflow: hidden;'>".$value['FECHA']."</div></td>"
                        . "<td><div id='".$value['TELEFONO']."' class='a' >$estado</div><div hidden id='cedulaResp1".$i."'>".$value['CEDULA']."</div><div hidden id='personaResp1".$i."'>".$value['ID_PERSONA']."</div></td>"
                        . "</tr>";
             $i++;   
            }
            } else { echo '<tr valign="top" colspan="2" class="center" style="background-color: #9FAFD1; width: 100%"><td><center>No existen registros</center><td></tr>'; }
            
            echo "</tbody></table></div>";
        exit();
    
        
    } else if (isset ($_GET['cambiarStatus'])){
        
        
        $id = $_GET['id'];
        $sql = "UPDATE SR_SMS_ENTRADA SET STATUS = 0 WHERE ID_SMS = '$id'";
        $st = $conex->consulta($sql);
        echo $sql;
        unset($st);
        unset($sql);
        
    }
    

    
} // FIN EMPTY() GET


 
 //
 //Obtine las Plantillas de la BD   
 //
 function cargarPlantilas()
 {
     $id = 7;
     
     if (!autorizar($id, $_SESSION['USER'])){
         
         return false;
         
     } else {
        //Declaracion de varable global para la conexion

        global $conex;
        //Query para obtener los registros
        $sql = "Select id, texto_A from SR_PLANTILLAS WHERE ESTATUS = '1'";  

        // Ejecutamos Query
        $st = $conex -> consulta($sql);
        $filas="";
        // Recorremos las filas obtenidas
        while ($fila = $conex -> fetch_array($st)) 
        {
            $filas[] = $fila;

        }

        // Se destruyen variables
        unset($st);
        unset($fila);
        unset($sql);
        return $filas;
    
      }   
 
}
 
 //
 //Obtiene la CANTIDAD de SMS entrantes no leidos de la BD
 //
 function smsNoLeidos($usuario = "NULL", $idPersona = "NULL")
 {
     $id = 8;
     
     if (!autorizar($id, $_SESSION['USER'])){
         
         return false;
         
     } else {


        //Declaracion de varable global para la conexion

        global $conex;

        // Se verifica que parametos se estan enviando para asignar query segun valores
        if($usuario == "NULL" && $idPersona == "NULL")
        {
            $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1";

        } else if ($usuario == "NULL" && $idPersona != "NULL")

        {
            $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1 and CEDULA = '{$idPersona}'";

        } else if ($usuario != "NULL" && $idPersona == "NULL")
        {

            $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1 and USUARIO = '{$usuario}'";

        } else
        {
             $sql = "select count(*) from SR_SMS_ENTRADA where STATUS = 1 and USUARIO = '{$usuario}' or CEDULA = '{$idPersona}'";   
        }

       // Se ejecuta el query
        $st = $conex -> consulta($sql);


        // Recorremos las filas devueltas de la consulta
        while ($fila = $conex -> fetch_array($st)) 
        {
            $contador = $fila[0];

        }
        //liberamos memoria
        unset($st);
        unset($fila);
        unset($sql);
        return $contador;
     }
 }
 
 
 //
 //Obtiene SMS de Entrada de la BD
 //
 function obtenerSmsEntrada($sql)
 {

     $id = 9;
     
     if (!autorizar($id, $_SESSION['USER'])){
         
         return false;
     } else {

        //Declaracion de varable global para la conexion
        global $conex;


        $filas="";
        // Se ejecuta el query
        $st = $conex -> consulta($sql);

        // Obtenemos numero de filas devuletas
        $numRows = $conex->filas($st);
       // Se verifica que las filas devueltas sea mayor a 1
        if($numRows >= 1){
            // Se ejecuta el query
            $st = $conex -> consulta($sql);
        while ($fila = $conex -> fetch_array($st)) 
        {
           $filas[] = $fila;


        }

        } else // En caso de que el valor devuelto sea menor a 1
        {

            $filas = "No se Encontraron Registros";
        }


        return $filas;
     }
 }
 
 //
 //Obtiene numero de telefonos de la BD
 //
 function obtenerTelefonosPersona ($idPersona, $tipo)
 {
     
     $id = 10;
     
     if (!autorizar($id, $_SESSION['USER'])){
         
         return false;
         
     } else {
     
    //Declaracion de varable global para la conexion
    global $conex;
    $filas="";
   if ($tipo == "NULL"){
    // query de la consulta
    $sql2 = "SELECT PERSONA, ID FROM tg_persona WHERE ID = '$idPersona'";
    // Se ejecuta el query
    $st2 = $conex -> consulta($sql2);
    
    // Se obtine resultado
    $fila = $conex -> fetch_array($st2);
    
    //$filas['ci'] = $fila['ID'];
        
   
 
    //Query para obtener los registro
       $sql = " select T.PERSONA, T.COD_AREA||T.TELEFONO as TLF
                from TG_PERSONA_TEL_ADI T
                WHERE T.PERSONA = '".$fila['PERSONA']."' 
                --AND LENGTH(TO_NUMBER(F_QUITA_CARACTER_ESPECIAL_NUM(trim(T.COD_AREA||T.TELEFONO)))) = 10 
                --and substr(TO_NUMBER(F_QUITA_CARACTER_ESPECIAL_NUM(trim(T.COD_AREA||T.TELEFONO))),1,3) in ('424','426','412','414','416')";   
   } else {
       
       if (isset($_SESSION['idPersona'])) {
           
           $di = $_SESSION['idPersona'];
           
       } else {
           
           $sql2 = "SELECT PERSONA, ID FROM tg_persona WHERE ID = '$idPersona'";
    // Se ejecuta el query
    $st2 = $conex -> consulta($sql2);
    
    // Se obtine resultado
    $fila = $conex -> fetch_array($st2);
           
           $di = $fila[0];
           
       }
       
       $sql = "select T.PERSONA, T.COD_AREA||T.TELEFONO as TLF from TG_PERSONA_TEL_ADI T WHERE T.PERSONA = '".$di."'
              --AND length(TO_NUMBER(F_QUITA_CARACTER_ESPECIAL_NUM(T.COD_AREA||T.TELEFONO))) = 10 and substr(TO_NUMBER(F_QUITA_CARACTER_ESPECIAL_NUM(T.COD_AREA||T.TELEFONO)),1,3) in ('424','426','412','414','416')";   
       
   }
  
    // preparamos el statement para la consulta  
    $st = $conex -> consulta($sql);
    //$filas="";
    // Recorremos el resultado de la consulta
    while($row = $conex -> fetch_array($st)){
        
        $filas[]= $row; 
        
    } 
      unset($st);
    unset($sql);
    unset($fila);
    unset($st2);
   
    // Devolvemos las filas obtenidas
     return $filas;
    // Se destruyen variables
  

   }
 }


 //
 //Verifica que los telefonos a enviar SMS no se les haya envia un SMS anteriormente el mismo dia
 //
function comprobarTelefonos($telefonos, $fechaDeEnvio)
{
    $id = 11;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
    
        global $conex;
        $telefonosProcesados = array();


        // Se recorre los telefonos a enviar
        foreach ($telefonos as $value)
        {
            // consulta para observar si exites registro para un telefono y fecha indicada
            $sql = "select telefono from SR_SMS where Telefono = '$value' and f_envio = '".$fechaDeEnvio."'";
            // Se ejecuta el query
            $st = $conex -> consulta($sql);
            // Se verifica si hay resulta0
            if ($fila = $conex -> fetch_array($st))
            {
                //Se almacena en arreglo el telefono
                $telefonosProcesados[] = $value;
            }
        }



        // Se devuelven los valores
            return $telefonosProcesados;
    }
}


function BuscaCuentas($idPersona){
    $id = 12;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        $sql = "SELECT CUENTA FROM SR_CUENTAS WHERE PERSONA = '$idPersona'";

        $st = $conex -> consulta($sql);
            // Se verifica si hay resulta0
            if ($fila = $conex -> fetch_array($st))
            {
                //Se almacena en arreglo el telefono
                $cuentas[] = $fila;
            }




        // Se devuelven los valores
            return $cuentas;
    }
    
    
}

function buscaCedula($idPersona){
    $id = 13;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        global $conex;
        $sql = "SELECT ID FROM TG_PERSONA WHERE PERSONA = '$idPersona'";

        $st = $conex -> consulta($sql);
            // Se verifica si hay resulta0
           $fila = $conex -> fetch_array($st);
           $cedula = $fila[0];




        // Se devuelven los valores
            return $cedula;
    }
    
    
}


/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */

 
?>