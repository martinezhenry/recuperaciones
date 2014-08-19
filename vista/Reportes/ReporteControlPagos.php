<?php
@session_start();
require_once $_SESSION['ROOT_PATH'] ."/modelo/conexion.php";
session_start();
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
            $fecha = date("d/m/Y");
            $fechaFin = $_GET['ff'];
           // $fecha = strtotime ( '' , strtotime ( $fecha ) ) ;
      //      $fecha = date ( 'd/m/Y h:i:s a' , $fecha );
            $fechaInicio = $_GET['fi'];
            $usuario = $_GET['us'];
            $asesor = $_GET['ase'];
            $fechaInicio = date("d/m/Y", strtotime($fechaInicio));
            $fechaFin = date("d/m/Y",  strtotime($fechaFin));
           
            
          //  $asesor = " (".$usuario.")";
            
            $sql = "SELECT CL.NOMBRE CLIENTE, P.NOMBRE DEUDOR, C.CUENTA, A.FECHA_DEPOSITO, 
                    DECODE(A.STATUS_ABONO, 'C', A.MONTO_DEPOSITO, '') PAGO_CONFIRMADO,
                    DECODE(A.STATUS_ABONO, 'N', A.MONTO_DEPOSITO, '') PAGO_NO_CONFIRMADO,
                    A.FECHA_INGRESO, A.USUARIO_INGRESO 
                     FROM SR_CUENTA C,  SR_ABONO A, TG_CLIENTE CL, TG_PERSONA P 
                     WHERE C.CUENTA = A.CUENTA
                     AND C.CLIENTE = CL.CLIENTE
                     AND A.CLIENTE = C.CLIENTE
                     AND A.CLIENTE = CL.CLIENTE
                     AND P.PERSONA = C.PERSONA
                     AND A.USUARIO_GESTOR = '".$usuario."'
                     AND  A.FECHA_INGRESO BETWEEN '$fechaInicio' AND '".$fechaFin."'
                     ORDER BY A.FECHA_INGRESO";
            
            $conex = new oracle($_SESSION['USER'], $_SESSION['pass']);
            $st = $conex ->consulta($sql);
            
            while ($value = $conex->fetch_array($st)){
                
                $registros[] = $value;
                
            } 
    
  



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
             $pdf->SetMargins(10, 40, 10);
             $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
         
             $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

             // Saltos de página automáticos.
             $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

             // Establecer el ratio para las imagenes que se puedan utilizar
             $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

             // Establecer la fuente
             $pdf->SetFont('times', '', 12);
             // add a page
            $pdf->AddPage('L');
           
            
             $html = '<label align="rigth">Caracas, '.$fecha.'</label><br>
<label><b>
GERENCIA DE RECUPERACIONES </b></label>

<br><br>

<table border="1" align="center"><tr bgcolor="#67ADE7"><th><h1>Resumen de Control de Pagos</h1></th></tr></table>

<br><br>

<table border="1">
    <tr><td>  <b>Asesor: </b>'.$asesor.'<br>
    <b>Desde: </b> '.$fechaInicio.' <b>Hasta: </b>'.$fechaFin.' <br>
    </td></tr>
    
</table>

<br><br>

<table style="font-size: 11px;"   width="100%;" frame="void" rules="rows" border="1"   cellpadding="1" cellspacing="0">

<thead>
<tr style="background-color: #67ADE7;" valign="middle" align="center">
    <th height= "25px"; width="18%"><b>Cliente</b></th>
    <th width="27%"><b>Deudor</b></th>
    <th width="12%"><b>Cuenta</b></th>
    <th width="8%"><b>Fecha de Pago</b></th>
    <th width="11%"><b>Pago no Confirmado</b></th>
    <th width="9%"><b>Pago Confirmado</b></th>
    <th width="7%"><b>Fecha Ingreso</b></th>
    <th width="8%"><b>Usuario Ingreso</b></th>
</tr>
</thead>
<tbody>
';
             $totalNoConfirmado = 0;
             $totalConfirmado = 0;
             if (isset($registros)){
             foreach ($registros as $value) {
                 
                 $noConfirmado = $value['PAGO_NO_CONFIRMADO'];
                 $confirmado = $value['PAGO_CONFIRMADO'];
                 $noConfirmado = str_replace(",", ".", $noConfirmado);
                 $totalNoConfirmado += $noConfirmado;
                 $confirmado = str_replace(",", ".", $confirmado);
                 $totalConfirmado += $confirmado;
                 //echo $noConfirmado;
                 //exit();
                 ($noConfirmado != "") ? $noConfirmado = number_format($noConfirmado, 2, ',', '.') : $noConfirmado = "";
                 ($confirmado != "") ? $confirmado = number_format($confirmado, 2, ',', '.') : $confirmado = "";
                 
                 $html .= '
                     
                        <tr>
                            <td width="18%">'.$value['CLIENTE'].'</td>
                            <td width="27%">'.$value['DEUDOR'].'</td>
                            <td width="12%">'.$value['CUENTA'].'</td>
                            <td width="8%" align="rigth">'.$value['FECHA_DEPOSITO'].'</td>
                            <td width="11%" align="rigth">'.$noConfirmado.'</td>
                            <td width="9%" align="rigth">'.$confirmado.'</td>
                            <td width="7%" align="rigth">'.$value['FECHA_INGRESO'].'</td>
                            <td width="8%" align="rigth">'.$value['USUARIO_INGRESO'].'</td>
                                        
                        </tr>

                      ';
                 
                 
}
             } else{
                 
                 
                 $registros = null;
                 
             }
    $total = $totalNoConfirmado+$totalConfirmado;
    ($totalNoConfirmado != "") ? $totalNoConfirmado = number_format($totalNoConfirmado, 2, ',', '.') : $totalNoConfirmado = "";         
    ($totalConfirmado != "") ? $totalConfirmado = number_format($totalConfirmado, 2, ',', '.') : $totalConfirmado = "";         
    ($total != "") ? $total = number_format($total, 2, ',', '.') : $total = "";         
             
 $html .= '

</tbody>
</table>
<br><br>
<table style="font-size: 12px; border: 1px solid black;"   width="100%;" frame="void" rules="rows" border="0"   cellpadding="1" cellspacing="0">
    
  <tr>
    <td width="34%"><b>Nº de Pagos:</b> '.count($registros).'</td>
    <td width="33%" align="rigth"><b>Sub. Totales:</b></td>
    <td width="9%" align="rigth">'.$totalNoConfirmado.'</td>
    <td width="9%" align="rigth">'.$totalConfirmado.'</td>
    <td width="15%"></td>
  </tr>
  <tr>
    <td align="rigth" colspan="3"><b>Total: </b></td>
    <td align="rigth">'.$total.'</td>
    <td></td>
  </tr>
  </table>


';

            
             $pdf->writeHTML($html, true, false, true, false, 'J');
             
             

             
             $pdf->Output('ControlDePagos.pdf', 'I');

?>