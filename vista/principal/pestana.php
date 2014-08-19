<?php @session_start();
(oracle::$ser != '192.20.15.77/vpc') ? $c= "background: red;": $c = "";
(isset($_GET[md5('unem')])) ? $vistaCerrar = "" : $vistaCerrar= "display: none;";

           $pestaña = '<div style="overflow: auto;">
       <div id="pestaña" class="sky-form" style="
                background: rgba(255, 255, 255, 0.9);
                padding: 5px;
                border-radius: 11px 11px 0px 0px;
                width: 680px; display: inline-block;
              
                 float: right;"><div style="float: left; display: inline-flex;"><div id="re"></div>
               <div>&nbsp;<?php echo date("d/m/Y"); ?></div></div><div style="float: right; display: inline-flex;">{pes_saludo}:&nbsp;<a href="#" id="open">'.$_SESSION['nombre_user'].' ('.$_SESSION['USER'].')</a>&nbsp;
                   
               <div style="padding-left:5px; border-left: 2px solid #D2D2D2;">    
                   <div><img id="alarmas" style="cursor: pointer;" onclick="datos();" width="15px" height="15px" src="img/ico/alarma.ico"/>&nbsp;</div>
               </div>
                   <!-- Separador -->
               <div style="padding-left:5px; border-left: 2px solid #D2D2D2;">
                   
           
                   <button class="button" style="margin: 0px 0 0 0px !important; padding: 0 5px; font: 300 12px \'Open Sans\', Helvetica, Arial, sans-serif; height: 22px; border: 2px solid blue;
                         border-radius: 5px; '.$c.' '.$vistaCerrar.'" type="button" onclick="location.href=\'../controlador/cerrarSesion.php\'">{pes_logout}</button></div></div>
           
       </div></div>';
                   
                   ?>    