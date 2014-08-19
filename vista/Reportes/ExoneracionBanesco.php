<?php
ob_end_clean();
@session_start();
                // Rutas donde tendremos la libreria y el fichero de idiomas.
             require_once('tcpdf/tcpdf.php');
             require_once '../../core/convertirdor.php';
             require_once '../moneda/moneda.php';
             //require_once 'clasePDF.php';
             
             class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = '../img/logo.jpg';
		$this->Image($image_file, 8, 8, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		//$this->Cell(100, 0, 'Exoneración', 0, false, 'C', 0, '', 0, false, 'M', 'M');
               
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
        
           
        
}

        // Obtenemos Fecha Actual
/*
            $fecha = date("d/m/Y");
            $nombre = $_POST['nombre'];
            $cedula = $_POST['cedula'];
            $porcentaje = $_POST['porcentaje'];
            $fechaPago = $_POST['fechaPago'];
            */
            
            $fecha = date("d/m/Y");
            $nombre = "Henry";
            $cedula = "124568";
            $fechaPago = "hoy";
            $monto = 2000.25;
            $porcentaje = 35;
            $tarjeta = '5401393009463053';
            $saldoActual = 7000.00;
            $montoExonerar = 2000.00;
            $montoCancelar = $saldoActual - $montoExonerar;
            $fechaPago = '28/06/2014';
            ($_SESSION['lang'] === 'es' ) ? $tipoMoneda = 'Bolivares' : $tipoMoneda = 'Dolares';
            $V=new EnLetras();



         // Crear el documento
             $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
             
             $pdf->SetCreator(PDF_CREATOR);
             $pdf->SetAuthor('Jordi Girones');
             $pdf->SetTitle('TCPDF Example 001');
             $pdf->SetSubject('TCPDF Tutorial');
             $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

             // Contenido de la cabecera
            // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
           //  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
             // Fuente de la cabecera y el pie de página
             $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
             $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

             // Márgenes
             $pdf->SetMargins(15, 30, 15);
             $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
         
             $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

             // Saltos de página automáticos.
             $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

             // Establecer el ratio para las imagenes que se puedan utilizar
             $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

             // Establecer la fuente
             $pdf->SetFont('times', '', 14);
             // add a page
            $pdf->AddPage();
             
             $subtable = '<table border="1" cellspacing="6" cellpadding="4"><tr><td>a</td><td>b</td></tr><tr><td>c</td><td>d</td></tr></table>';
             $html = '<label align="rigth">Caracas, '.$fecha.'</label><br>
<label>Banesco Banco Universal</label><br>
<label>Presente._</label>
<br><br>
<label>Yo,  <b>'.$nombre.', C.I- '.$cedula.',</b> Venezolano, por medio de la presente solicito se estudie la posibilidad de exonerar el <b>'.$V->ValorEnLetras($porcentaje, 'Por Ciento').' ('.$porcentaje.'%)</b> de la deuda que mantengo con esta Institución  Financiera que asciende 
<b> '.$V->ValorEnLetras($monto,$tipoMoneda).'('.$_SESSION['simb_moneda'].' '.$monto.')</b> a la presente fecha y comprende las obligaciones que he contraído por el uso de las TDC que ofrece el Banco. 
<br><br>
Así mismo convengo que la falta de pago, a su vencimiento, de cualquiera de las cuotas indicadas a continuación, dará derecho al Banco a declarar resuelto el presente convenio y considerar la obligación de  plazo vencido. Igualmente de ser devuelto un cheque, me comprometo a reponer el mismo antes de la facturación, consciente de que de no hacerlo perderé la exoneración
A continuación discriminaré los conceptos que conforman la deuda y el plan de pago que propongo: 

</label>
<br><br>
<label><b>Tarjeta Nro. '.$tarjeta.'</b></label>
<br><br>
<table border="1">

    <tr  BGCOLOR="#0000">
    <th><b>Saldo al</b></th>
    <th><b>Exoneracion</b></th>
    <th><b>Recuperacion</b></th>
    <th align="center" colspan="2"><b>Pagos a Efectur</b></th>
   

    </tr>
    
    <tr>
        <td>'.$fecha.'</td>
        <td>Monto</td>
        <td>Monto</td>
        <td>Monto</td>
        <td>Monto</td>
    </tr>
    
   <tr>
        <td>'.cambiarMoneda($saldoActual).'</td>
        <td>'.cambiarMoneda($montoExonerar).'</td>
        <td>'.cambiarMoneda($montoCancelar).'</td>
        <td>'.cambiarMoneda($montoCancelar).'</td>
        <td>'.$fechaPago.'</td>
    </tr>



</table>

<br><br><br><br>
<label>Sin otro particular al que hacer referencia, se despide de Uds</label>
<br><br>
<label>Atentamente,<br><br>

Firma: ______________________________     Cédula: __________________.<br>
Dirección: _______________________________________________________________ <br>
Teléfono: ______________________________________
</label>




';

             
             $pdf->writeHTML($html, true, false, true, false, 'J');
             
             

             
             $pdf->Output('example_006.pdf', 'I');

?>