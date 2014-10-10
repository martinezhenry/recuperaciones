<?php

    $_GET['nac'] = ($_GET['nac'] == 'N') ? 'V':'';
    
    if ($_GET['nac']!= ''){
        
       
    
  $postdata = http_build_query(
    array(
        'nacionalidad_aseg' => trim($_GET['nac']),
        'cedula_aseg' => trim((int) $_GET['ce']),
        'y' => trim((int) $_GET['y']),
        'm' => trim((int) $_GET['m']),
        'd' => trim((int) $_GET['d'])
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
 
$result = file_get_contents('http://www.ivss.gob.ve:28083/CuentaIndividualIntranet/CtaIndividual_PortalCTRL', false, $context);

       // $result = str_replace('Bderecho', 'otrafun', $result);





   //     $result = str_replace('document.onclick=reEnable', '', $result);
     //   $result = str_replace('document.onmousedown=disableselect', '', $result);
        $result = str_replace('document.onselectstart=new Function ("return false")', '', $result);
  $result = str_replace('<img src= "servicios1/cta_individual/superior2.png"  width="706" height="80" alt="cuentaindividual" >', '', $result);
echo $result;

    } 