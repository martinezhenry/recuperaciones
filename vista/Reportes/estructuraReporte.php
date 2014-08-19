<?php 


        class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = '../img/logo.jpg';
		$this->Image($image_file, 8, 8, 40, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 10);
		// Title
		$this->Cell(0, 0, 'GRUPO V.P.C. VENEZOLANA PROTECTORA DE CREDITO C. A.', 0, false, 'R', 0, '', 0, false, 'M', 'M');
              //  $this->writeHTML("<label align='rigth'>GRUPO V.P.C. VENEZOLANA PROTECTORA DE CREDITO C. A.</label>", true, false, true, false, '');
               
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
                             
               
	}
        
           
        
}


?>