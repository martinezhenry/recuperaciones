<?php 
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');
$arreglo = json_decode($_POST['arreglo'], true);
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';
// Crea un nuevo objeto PHPExcel
    
           $objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

            
         // Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->SetCellValue('B2', 'CEDULA')
            ->SetCellValue('C2', 'NOMBRE')
            ->SetCellValue('D2', 'CUENTA')
            ->SetCellValue('E2', 'CLIENTE')
            ->SetCellValue('F2', 'PRODUCTO')
            ->SetCellValue('G2', 'MORA')
            ->SetCellValue('H2', 'SALDO')
            ->SetCellValue('I2', 'Nº DE ASIGNACION')
            ->SetCellValue('J2', 'ASESOR')
            ->SetCellValue('K2', 'FECHA ASIGNACION')
            ->SetCellValue('L2', 'TIPO DE GESTION');

    $fila = 3;
   
    foreach ($arreglo as $key => $value) {
        
       $objPHPExcel->setActiveSheetIndex(0)
            ->SetCellValue('B'.$fila, $value['CEDULA'])
            ->SetCellValue('C'.$fila, $value['NOMBRE'])
            ->SetCellValue('D'.$fila, ' '.$value['CUENTA'])
            ->SetCellValue('E'.$fila, $value['CLIENTE'])
            ->SetCellValue('F'.$fila, $value['PRODUCTO'])
            ->SetCellValue('G'.$fila, $value['SITUACION_CUENTA'])
            ->SetCellValue('H'.$fila, str_replace(',','.',$value['SALDO_ACTUAL']))
            ->SetCellValue('I'.$fila, '')
            ->SetCellValue('J'.$fila, $value['ASESOR'])
            ->SetCellValue('K'.$fila, $value['FECHA_ASIGNACION'])
            ->SetCellValue('L'.$fila, $value['TIPO_GESTION']);
        $fila++;
                

        
        
        
}
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel -> getActiveSheet ()-> getStyle ( 
    'B2:L2' 
    )-> getBorders ()-> getAllBorders ()-> setBorderStyle ( PHPExcel_Style_Border :: BORDER_MEDIUM );

$styleArray = array(
    'font' => array(
        'bold' => true
    )
);
$objPHPExcel->getActiveSheet()->getStyle('A2:L2')->applyFromArray($styleArray);


            $objPHPExcel -> getActiveSheet ()-> getStyle ( 
    'B3:L'.$fila 
)-> getBorders ()-> getAllBorders ()-> setBorderStyle ( PHPExcel_Style_Border :: BORDER_THIN );
            
            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.($fila+2), 'TOTAL');
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.($fila+2), '=SUM(H2:H'.$fila.')');
            

// ejecutamos el query  


         //   $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'gestor');
        //    $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'usuario_superior');
       //     $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'supervisor');
       //     $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'status_abono');
          /*  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            
            
*/
/*
$objPHPExcel -> getActiveSheet ()-> getStyle ( 
    'A2:K2' 
    )-> getBorders ()-> getAllBorders ()-> setBorderStyle ( PHPExcel_Style_Border :: BORDER_MEDIUM );


$styleArray = array(
    'font' => array(
        'bold' => true
    )
);
$objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($styleArray);
            foreach ($cuentasAsignadas as $value) {
               $cont++; 
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $value['CEDULA']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $value['NOMBRE']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $value['CUENTA'].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $value['CLIENTE']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $value['PRODUCTO']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $value['SITUACION_CUENTA'].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, str_replace(",", ".", $value['SALDO_ACTUAL'])); 
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, ' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, $value['ASESOR']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $value['FECHA_ASIGNACION']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $value['TIPO_GESTION']);
            
      //      $objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $value[11]);
      //      $objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, $value[12]);
       //     $objPHPExcel->getActiveSheet()->SetCellValue('N'.$cont, $value[13]);
                
                
            }
            
            
            $objPHPExcel -> getActiveSheet ()-> getStyle ( 
    'A3:K'.$cont 
)-> getBorders ()-> getAllBorders ()-> setBorderStyle ( PHPExcel_Style_Border :: BORDER_THIN );
            
            
            
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.($cont+2), 'TOTAL');
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.($cont+2), '=SUM(G2:G'.$cont.')');
            
// mostramos los resultados  
   // print_r(oci_fetch_array($st));/*
            /*
while ($fila = oci_fetch_array($st)) { 
            // Agregar Informacion
     //       echo $fila[6].'<br>';
  $cont=$cont+1;
   IF ($cont == 1){ 
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'cliente');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'cedula');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'contrato');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'codigo_cartera');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'cartera');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'cuenta');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'fecha_ingreso');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'fecha_deposito');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'monto_deposito');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'usuario_gestor');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'gestor');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'usuario_superior');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'supervisor');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'status_abono');
            
     }ELSE{
            
        //$objPHPExcel->getActiveSheet()->SetCellValue("A".$cont, $cont);
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $fila[0]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $fila[1]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $fila[2].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $fila[3]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $fila[4]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $fila[5].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $fila[6]); 
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, $fila[7]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, str_replace(',','.',$fila[8]));
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $fila[9]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $fila[10]);
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $fila[11]);
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, $fila[12]);
            $objPHPExcel->getActiveSheet()->SetCellValue('N'.$cont, $fila[13]);
    }
}*/
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Portafolio');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte Portafolio.xlsx"');
header('Cache-Control: max-age=0');

// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
          


?>