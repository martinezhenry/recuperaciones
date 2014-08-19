<?php
@session_start();
require_once "../modelo/conexion.php";
require_once "tamDir.php";
require_once 'c_agenda.php';
require_once 'c_autorizar.php';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

function obtenerDatosDirectorio($usuario){
    $id = 31;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
                 
    } else {
        global $conex;

        $sql = "SELECT TAMA_DIRECTORIO, DIRECTORIO FROM SR_USUARIO WHERE USUARIO = '$usuario'";

        $st = $conex->consulta($sql);


         $numrow = $conex->filas($st);
   
        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $datos = $fila;
            }

            return $datos;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {
    //echo "porakiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii";        
    // En caso de que no hayan resultados
            return $datos = "No existen Registros para Este Usuario";
            exit();
        }
    }
    
    
    
}


function tamañoDirectorio($usuario){
    $id = 32;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        global $conex;
        $sql = "SELECT TAMA_DIRECTORIO FROM SR_USUARIO WHERE USUARIO = '$usuario'";


                $st = $conex->consulta($sql);


        $numrow = $conex->filas($st);

       if ($numrow > 0) {
           $st = $conex->consulta($sql);
           while ($fila = $conex->fetch_array($st)) {
               $datos = $fila;
           }

           return $datos;
           unset($output);
           unset($sql);
           unset($st);
           exit();
       } else { // En caso de que no hayan resultados
           return $datos = "No existen Registros para Este Usuario";
           exit();
       }

    }
    
    
    
}


function guardarDirectorio($directorio_a_guardar, $usuario){
    $id = 33;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        
   
    
    global $conex;
    $sql = "UPDATE SR_USUARIO SET DIRECTORIO = '$directorio_a_guardar' WHERE USUARIO = '$usuario'";
    
    $st = $conex->consulta($sql);
    return true;
    }
    
    
}




if (isset($_GET['Directorio'])){
    
    $usuario = $_GET['usuario'];
    
    $datos =  obtenerDatosDirectorio($usuario);
    
     if (is_array($datos) ){
         
         $directorio = $datos[1];
         $tamañoMaximo = $datos[0];

         if (empty($directorio))
         {
             $ruta = $_SESSION['raiz'];
             $permitir = true;
             
             $carpeta = $usuario;
             $a = new C_agenda($usuario);
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
         
         //echo $directorio;
         
         echo $ruta.",".$permitir;
         
     }
    
   
    exit();
    
} else if (isset($_GET['tamañoDirectorio'])){
    
    $usuario = $_GET['usuario'];
    $directorio = $_GET['directorio'];
    
     
    
    $tamaño = tamañoDirectorio($usuario);
    
    if (is_array($tamaño)) {
        
        $tamañoMaximo = $tamaño[0];
        
    $tamañoActual = MeDir($directorio);
         $tamañoActual = ($tamañoActual/1024);
        if ($tamañoActual >= $tamañoMaximo){
             
             $permitir = "true";
             
         } else {
             
             $permitir = "false";
             
         }
         
         echo $permitir;
         exit();
         
    } else {
        
        
        echo "false";
        exit();
                
    }
    
    
}


?>