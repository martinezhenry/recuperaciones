<?php
$conn = oci_connect('VPCTOTAL','cable', '192.20.15.77/vpc'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
if (!$conn) {
    $e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>