<?php
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$correo = $_POST['correo'];
$datos = $_POST['datos'];
$observaciones = $_POST['observaciones'];
ob_end_clean();
                // Rutas donde tendremos la libreria y el fichero de idiomas.
             require_once('tcpdf/tcpdf.php'); 
             //require_once 'clasePDF.php';
             
             class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = '../img/logo.jpg';
		$this->Image($image_file, 10, 10, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
               
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

             // Crear el documento
             $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

             // Información referente al PDF
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
             $pdf->SetMargins(10, 40, 10);
             $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
         
             $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

             // Saltos de página automáticos.
             $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

             // Establecer el ratio para las imagenes que se puedan utilizar
             $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

             // Establecer la fuente
             $pdf->SetFont('times', 'BI', 16);

             // Añadir página
             $pdf->AddPage();
               $ruta_img = "";
             // Escribir una línea con el método CELL
             $pdf->Cell(80, 12, "Nopmbre: {$nombre}", 1, 1, 'C');
             $pdf->Cell(80, 12, "Apellido: {$apellido}", 1, 1, 'C');
             $pdf->Cell(80, 12, "Correo: {$correo}", 1, 1, 'C');
             $pdf->Cell(80, 12, "Datos: {$datos}", 1, 1, 'C');
             $pdf->Cell(80, 12, "Observaciones: {$observaciones}", 1, 1,'C');
             $pdf->Cell(80, 12, "Regresar", 1, 1,'C',0,"localhost/crearpdf_pract/index.php");
             //$pdf->Image(PDF_HEADER_LOGO, 1, 1.5, 3, 3, '', '', '', true, 600, '',false,false,1);
             
             

             // ---------------------------------------------------------
             $pdf->AddPage();
             //Cerramos y damos salida al fichero PDF
             $pdf->Output('example_001.pdf', 'I');
  ?>