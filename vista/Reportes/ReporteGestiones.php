<?php
@session_start();
require_once $_SESSION['ROOT_PATH'] ."/controlador/c_deudor.php";

ob_end_clean();


// Rutas donde tendremos la libreria y el fichero de idiomas.

             require_once('tcpdf/tcpdf.php'); 
             require_once 'estructuraReporte.php';
             //require_once 'clasePDF.php';
             


        // Obtenemos Fecha Actual
/*
            $fecha = date("d/m/Y");
            $nombre = $_POST['nombre'];
            $cedula = $_POST['cedula'];
            $porcentaje = $_POST['porcentaje'];
            $fechaPago = $_POST['fechaPago'];
            */
            setlocale(LC_TIME, 'es_VE', 'es_VE.utf-8', 'es_VE.utf8');
            date_default_timezone_set("America/Caracas");
            $fecha = date("d/m/Y h:i:s a");
            $fecha = strtotime ( '+30 minute' , strtotime ( $fecha ) ) ;
            $fecha = date ( 'd/m/Y h:i:s a' , $fecha );
            
            $cuenta = $_GET['c'];
            
            $asesor = $_SESSION['nombre_user']." (".$_SESSION['USER'].")";
            
            $gestiones = cargarGestiones($cuenta);
       //     $sql = "select descripcion from sr_gestion where cuenta = '$cuenta'";
          //  $st = $conex ->consulta($sql);
            
     
         //   print_r($gestiones);
           
          //  $gestiones = array();
            
            $idPersona = buscarIdPersona($cuenta);
        
            $conex = new oracle($_SESSION['USER'], $_SESSION['pass']);
            
            $sql = "select id, persona, nombre, tipo_persona, fecnac from tg_persona where persona = '$idPersona'";
            
            $st = $conex ->consulta($sql);
            
            $deudor = $conex->fetch_array($st);
            
            
            
            $sql = "select direccion from tg_persona_dir_adi where status_direccion = 'A' and persona = '$idPersona'";
            
            $st = $conex ->consulta($sql);
            
            $direcion = $conex->fetch_array($st);
      
            
            $cuentas = cargarCuentasDeudor("NULL", $cuenta);
            
                     
            
            



         // Crear el documento
             $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
             
             $pdf->SetCreator(PDF_CREATOR);
             $pdf->SetAuthor('VPC');
             //$pdf->SetTitle('TCPDF Example 001');
             //$pdf->SetSubject('TCPDF Tutorial');
             //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

             // Contenido de la cabecera
            // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
           //  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
             // Fuente de la cabecera y el pie de página
             $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
             $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

             // Márgenes
             $pdf->SetMargins(15, 40, 15);
             $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
         
             $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

             // Saltos de página automáticos.
             $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

             // Establecer el ratio para las imagenes que se puedan utilizar
             $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

             // Establecer la fuente
             $pdf->SetFont('times', '', 14);
             // add a page
            $pdf->AddPage('L');
           
          
             $html = '<label align="rigth">Caracas, '.$fecha.'</label><br>
<label><b>
GERENCIA DE RECUPERACIONES </b></label>

<br><br>

<table border="1" align="center"><tr bgcolor="#67ADE7"><th><h1>Gestion Diaria</h1></th></tr></table>

<br><br>

<table border="1">
    <tr><td>  <b>Asesor:</b> '.$asesor.'<br>
    <b>Cliente:</b> '.$cuentas[0]['NOMBRE'].'<br>
    <b>Deudor:</b>  '.$deudor['NOMBRE'].' <b>C.I:</b> '.$deudor['ID'].' <br>
    <b>Cuenta: </b>'.$cuenta.'<br>
    <b>Dirección:</b> '.$direcion['DIRECCION'].'</td></tr>
    
</table>

<br><br>

<table style="font-size: 15px;"   width="100%;" frame="void" rules="rows" border="0"   cellpadding="1" cellspacing="0">

<thead>
<tr style="background-color: #67ADE7;" align="center">
    <th style="border-bottom: 1.5px ridge black;" width="110"><b>Fecha Gestión</b></th>
    <th style="border-bottom: 1.5px ridge black;" width="50"><b>Área</b></th>
    <th style="border-bottom: 1.5px ridge black;" width="80"><b>Telefono</b></th>
    <th style="border-bottom: 1.5px ridge black;" width="590"><b>Gestión</b></th>
    <th style="border-bottom: 1.5px ridge black;" width="120"><b>Promesa de Pago</b></th>
</tr>
</thead>
<tbody>
';
         
             
             $i =0;
             
             if (is_array($gestiones)){
             
             foreach ($gestiones as $value) {
                // ($i%2 == 0) ? $color = "background-color: #DDF0FF" : $color = "background-color: #EEF5FF";
                 $html .= '
                         
                          
                            <tr>
                                <td style="border-bottom: 1px ridge black;" width="110">'.$value['FECHA_INGRESO'].'</td>
                                <td style="border-bottom: 1px ridge black;" width="50">'.$value['TELEF_COD_AREA'].'</td>
                                <td style="border-bottom: 1px ridge black;" width="80">'.$value['TELEF_GESTION'].'</td>
                                <td style="border-bottom: 1px ridge black;" width="610"><label>'.$value['TIPO_GESTION']." || ".$value['OBSERVACION'].'</label></td>
                                <td style="border-bottom: 1px ridge black;" width="100">'.$value['FECHA_PROMESA'].'</td>
                            </tr>

                          ';
                 
                 $i++;
             }
             
                 
                 
                 
             } else {
                 
                 
                   $html .= '
                         
                          
                            <tr>
                                <td style="border-bottom: 1px ridge black;" width="110"></td>
                                <td style="border-bottom: 1px ridge black;" width="50"></td>
                                <td style="border-bottom: 1px ridge black;" width="80"></td>
                                <td style="border-bottom: 1px ridge black;" width="600"></td>
                                <td style="border-bottom: 1px ridge black;" width="110"></td>
                            </tr>

                          ';
             }
             
             
 $html .= '
     
</tbody>
</table>



';


             
          //   $html = '<label>Hola</label>';

             
             $pdf->writeHTML($html, true, false, true, false, 'J');
             
             

             
             $pdf->Output('ReporteGestiones.pdf', 'I');

?>