<?php

    include 'conexion.php';
    
    $conex = new oracle("T7032","t70");
    print_r($conex);
    //$err = $conex->conectar("T7032", "t7032");
    /*
        if ($err == 1017){
            
            echo "Usuario o Calve Invalidos Verifique!";
            
        } else if ($err == 28000){
            
            echo "Usuario Bloqueado!";
            
        } else {
            echo $err;
        }*/
    $paso = $conex->get_paso();
    if ($paso == 1){
        
        echo "Conectado";
        //$conex->disconnect();
    } else {
        
       if ($paso == "01017"){
           
            echo "Usuario o Calve Invalidos Verifique!";
       } else if ($paso == "28000"){
            
            echo "Usuario Bloqueado!";
       }
       
        
    }
    


?>