<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$_SESSION['ROOT_URL'] = str_replace($_SERVER['REQUEST_URI'], '',$_SERVER['DOCUMENT_ROOT']);

