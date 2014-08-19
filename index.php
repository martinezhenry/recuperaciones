<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!empty($_SERVER['HTTP_CLIENT_IP']))
$ip = $_SERVER['HTTP_CLIENT_IP'];

if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

$ip = $_SERVER['REMOTE_ADDR'];

if ($ip == "127.0.0.1"){
@session_start();
$_SESSION['USER'] = 'GTE08';
$_SESSION['pass'] = 'GTE08';
$_SESSION['nombre_user'] = 'DENNIS GOMEZ';
$_SESSION['lang'] = 'es';
($_SESSION['lang'] === 'es') ? $_SESSION['simb_moneda'] = 'Bs' : $_SESSION['simb_moneda'] = '$';
$_SESSION['ROOT_PATH'] = 'C:/xampp/htdocs/recuperaciones';
header('location:vista/?'.md5('unem'));
} else 
header('location:vista/?'.md5('unem'));