<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


error_reporting(E_ALL);
ini_set("display_errors", 1);
require("PHPMailer-master/class.phpmailer.php");
require("PHPMailer-master/class.smtp.php");

//Especificamos los datos y configuraciÃ³n del servidor
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
//$mail->SMTPSecure = "ssl";
$mail->Host = "www.grupovpc.net";
$mail->Port = 25;

//Nos autenticamos con nuestras credenciales en el servidor de correo Gmail
$mail->Username = "noreply@grupovpc.net";
$mail->Password = "vpc*12345";

//Agregamos la informaciÃ³n que el correo requiere
$mail->From = "noreply@grupovpc.net";
$mail->FromName = "vpc";
$mail->Subject = "Monitoreo del Sistema Recuperaciones";
$mail->AltBody = "Monitoreo";
$mail->MsgHTML($cuerpo);
//$mail->AddAttachment("adjunto.txt");
$mail->AddAddress("hmartinez@grupovpc.com", "Henry");
$mail->IsHTML(true);

//Enviamos el correo electrÃ³nico
 if(!$mail->Send()) {
echo "Error: " . $mail->ErrorInfo;
} else {
echo "Mensaje enviado!";}
?>
