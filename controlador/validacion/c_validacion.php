<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    $navegador = get_browser(null, true);
    
    function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = '';
        if(preg_match('/MSIE/i',$u_agent))
        {
            $ub = "Internet Explorer";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $ub = "Mozilla Firefox";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $ub = "Apple Safari";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $ub = "Google Chrome";
        }
        elseif(preg_match('/Flock/i',$u_agent))
         {
            $ub = "Flock";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $ub = "Netscape";
        }
        return $ub;
    }
    
    if (isset($_POST['valida'])){
       // echo "eeet";
        $dia = date("d");
        $mes = date("m");
        $hora = date("H");
        $ano = date("y");
        $minuto = date("i");
        $valor = $_POST["valor"];
        $resta = $_POST["resta"];
        
        $clave = (($dia-2) + ($mes+$resta) + ($ano+2)) + 30;
        
        if ((Float)$valor == (Float)$clave || $valor == ($dia*10-$resta)){
            
           echo "1";
        } else 
           echo "0";
        
        
        
    }
    