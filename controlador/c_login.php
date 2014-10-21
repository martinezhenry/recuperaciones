<?php
@session_start();

 
require_once '../conf.conf';
require_once $_SESSION['ROOT_PATH']."/modelo/conexion.php";

if (isset($_POST)){
    
    $usuario = $_POST['username'];
    $password = $_POST['password'];
    $conex = new oracle($usuario,$password);
    
        $paso = $conex->get_paso();
   //  echo $paso. "  este es paso" ;
    if ($paso == "1"){
        
        
        $_SESSION['USER'] = $usuario;
        $_SESSION['pass'] = $password;
       require_once 'c_autorizar.php';
        /*$sql = "SELECT NOMBRES FROM SR_USUARIO WHERE USUARIO = '$usuario'";
        $st = $conex->consulta($sql);
        $fila = $conex->fetch_array($st);*/
        $_SESSION['nombre_user'] = buscarNombreUser($usuario, $conex);
        
        /*    
        $postdata = http_build_query(
        array(
            'datos' => 1,
            'h' => trim(date('d/m/Y H:m:s')),
            'u' => trim($usuario),
            'i' => trim($conex->get_ip_conex())
            )
 
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
 
        $context  = stream_context_create($opts);
 
        $seguir = file_get_contents('http://192.20.15.17/correo/prueba.php', false, $context);

*/
 
        
        //$_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
        $_SESSION['lang'] = $_POST['lang'];
        ($_SESSION['lang'] === 'es') ? $_SESSION['simb_moneda'] = 'Bs' : $_SESSION['simb_moneda'] = '$';
        header("Location: ../vista?".md5('unem'));
        exit();
        //$conex->disconnect();
    } else {
        
       if ($paso == "01017"){
           
                header("Location: ../vista/login.php?alert=".md5("invalid"));
            exit();
       } else if ($paso == "28000"){
            
              header("Location: ../vista/login.php?alert=".md5("block"));
            exit();
       } else {
           
           echo "<h2 style='color:red;text-align:center'>ERROR EN LA CONEXION. ".oci_error()['message']."</h2>";
           
       }
       
        
    }
    
    
    /*
        if (!$err){
        
        //echo "Conectado";
        session_start();
        $_SESSION['USER'] = "T7032";
        header("Location: ../vista");
        exit();
    } else {
        
        if ($err == 1017){
            
            header("Location: ../vista/login.php?nopass=".md5("1"));
            exit();
            
        } else if ($err == 28000) {
            
            
            header("Location: ../vista/login.php?block=".md5("1"));
            exit();
            
            
        }
        
    }
    
            */
    
    
}


function buscarNombreUser($usuario, $conex){
   
    $id = 38;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
    $sql = "SELECT NOMBRES FROM SR_USUARIO WHERE USUARIO = '$usuario'";
    $st = $conex->consulta($sql);
    $fila = $conex->fetch_array($st);
    return $fila[0];
    }
    
}



?>