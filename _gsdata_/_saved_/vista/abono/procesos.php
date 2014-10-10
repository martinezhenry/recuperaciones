<?PHP
       //Incluuimos la conexion con oracle 
	include("conexion.php");
        require_once 'Classes/PHPExcel/IOFactory.php';
        require_once 'Classes/PHPExcel.php';
        require_once("Classes/PHPExcel/Writer/Excel2007.php");
        require_once ("Classes/PHPExcel/Cell/AdvancedValueBinder.php"); 
	  

   //PROCESO DE FACTURACION------------------------------------------------------------------------------  

       	//Donde se guardaran la imagenes 
	$rutaEnServidor = 'archivos';
	//OBTENEMOS EL NOMBRE REAL DEL ARCHIVO 
	$nombreArchivo = $_FILES['archivo']['name'];
	//Ruta temporal del archivo
	$rutaTemporal = $_FILES['archivo']['tmp_name'];
	//Ruta de destino del archivo 
	$rutaDestino=$rutaEnServidor.'/'.$nombreArchivo;
	//Movemos el archivo cargado haciala ruta establecida
	move_uploaded_file($rutaTemporal,$rutaDestino); 
        //cargamos el archivo que deseamos leer
        $objPHPExcel = PHPExcel_IOFactory::load('archivos/'.$nombreArchivo);
        //obtenemos los datos de la hoja activa (la primera)
        $objHoja=$objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        //declaramos las varibles
        $cont=0;
        $incidencia=0;
        
        //recorremos el excel
        for($i = 2; $i<=count($objHoja); $i++){
            //validamos que los campos no sean nulos
            IF ($objHoja [$i]['A']== NULL ){
                //cambia estatus si es nulo
                $incidencia=1;  
                //eliminos lo que secargo en la tabla
                $sql = oci_parse($conn,"delete from FACTURACION");
                            oci_execute($sql);
                 echo '<script language="javascript" type="text/javascript">
			alert("ERROR DEBE INGRESAR FACTURA");
			location.href = "http://'.$_SERVER['HTTP_HOST'].'/abono/paquetes.php";
                        </script>';
            }ELSE{
                //valido que el para saber si son cunetas o cedulas
                        //Insertamos las cuentas que se van a trasladar
                        
              
                //formato de fechA
                $date = substr($objHoja[$i]['J'],3,2).'/'.substr($objHoja[$i]['J'],0,2).'/'.substr($objHoja[$i]['J'],6,2);
                //$fecha = $date->format('d-m-Y');
                
               // echo $date.'<br>';
               // $date = new DateTime($objHoja[$i]['J']);
               //$fecha = $date->format('Y/m/d');
               
              $query = "INSERT INTO FACTURACION SELECT '".trim($objHoja [$i]['A'])."','".strtoupper(trim($objHoja [$i]['B']))."','".strtoupper(trim($objHoja [$i]['C']))."','".strtoupper(trim($objHoja [$i]['D']))."','".strtoupper(trim($objHoja [$i]['E']))."','".strtoupper(trim($objHoja [$i]['F']))."','".strtoupper(trim($objHoja [$i]['G']))."','".strtoupper(trim($objHoja [$i]['H']))."','".strtoupper(trim($objHoja [$i]['I']))."', TO_DATE('".$date."') FROM DUAL";              
              //  echo $query.'<br>';
              $sql = oci_parse($conn,$query);      
                  if (oci_execute($sql)){   
                            $cont++;  
                             /*echo '<script language="javascript" type="text/javascript">
                            alert("Carga Procesada con Exito");
                            location.href = "http://'.$_SERVER['HTTP_HOST'].'/abono/paquetes.php";
                            </script>';*/
                        }else{
                            $incidencia=1;
                            $sql = oci_parse($conn,"delete from FACTURACION");
                             oci_execute($sql);
                             echo '<script language="javascript" type="text/javascript">
                            alert("Error en el traslado de cuentas");
                            location.href = "http://'.$_SERVER['HTTP_HOST'].'/abono/paquetes.php";
                            </script>';
                        } 
                }
            
            }
     //PROCESO DE EXPORTACION ABONOS--------------------------------------------------------------------
     
         $desde=$_POST['desde'];
         $hasta=$_POST['hasta'];
            // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("Reporte facturacion")
            ->setSubject("Reporte facturacion")
            ->setDescription("Reporte facturacion")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("Reporte facturacion");
            
     /*       $sql = "SELECT 
        B.FACTURA //,
       // B.MES,
       // A.USUARIO_SUPERVISOR, 
       //INITCAP(S.NOMBRES) NOMBRE_SUPERVISOR, 
       // A.USUARIO_GESTOR, 
      //  INITCAP(G.NOMBRES) NOMBRE_GESTOR,  
     //  G.TIPO_COD, 
     //  INITCAP(P.NOMBRE) NOMBRE_DEUDOR,
    //   P.ID AS CEDULA, 
     //  B.CLIENTE, 
     //  INITCAP(CL.NOMBRE)  NOMBRE_CLIENTE, 
     //  B.PRODUCTO,
      // B.CUENTA,
     //  B.CONTRATO, 
     // SUM(NVL(REPLACE(B.MON_DEP,'.',','),0)) TOTAL_COBRADO, 
     //  REPLACE(B.PORC_FAC,',','.')  PORC_COMI,
     //  SUM(NVL(REPLACE(B.MON_DEP,'.',','),0)) *REPLACE( B.PORC_FAC,'.',',')  MONTO_FACTURACION,
     //  TO_DATE(B.FECHA_PAGO)FECHA_PAGO, 
      // TO_DATE(A.FECHA_INGRESO)FECHA_INGRESO
    FROM         
      // TG_PERSONA P, 
     //  SR_ABONO A, 
       FACTURACION B
     // SR_CUENTA C, 
     // TG_CLIENTE CL, 
     //  SR_USUARIO S, 
     //  SR_USUARIO G
    WHERE   (b.fecha_pago) >= $desde
     and (b.fecha_pago) <= $hasta;
  //  AND      A.STATUS_ABONO = 'C' 
  //  AND      A.CUENTA(+) = B.CUENTA
  //  AND      A.CARTERA = C.CARTERA
  //  AND      A.CLIENTE  = C.CLIENTE
 //  AND      A.CUENTA  = C.CUENTA
  //  AND      C.PERSONA = P.PERSONA
  //  AND      S.USUARIO = A.USUARIO_SUPERVISOR
  //  AND      G.USUARIO = A.USUARIO_GESTOR
  //  AND     CL.NOMBRE = B.CLIENTE
     GROUP BY   B.FACTURA, B.FECHA_PAGO, A.USUARIO_SUPERVISOR, S.NOMBRES, A.USUARIO_GESTOR, G.NOMBRES,
  //          G.TIPO_COD,  A.FECHA_INGRESO,
  //           B.CLIENTE, CL.NOMBRE,  B.MON_DEP,   
  //           P.NOMBRE,P.ID, B.PRODUCTO, B.CUENTA, B.CONTRATO, B.PORC_FAC, B.MES";  */
            // sql preparado  
$sql = "SELECT
     A.FACTURA,
       A.USUARIO_SUPERVISOR, 
       INITCAP(S.NOMBRES) NOMBRE_SUPERVISOR, 
       A.USUARIO_GESTOR, 
       INITCAP(G.NOMBRES) NOMBRE_GESTOR,  
       G.TIPO_COD, 
        P.ID AS CEDULA, 
       INITCAP(CL.NOMBRE)  NOMBRE_CLIENTE, 
       CASE WHEN C.CLIENTE = 136 AND C.OBSERVACION_INTERNA = 'TDC' THEN 'BBVA'||' '||INITCAP(C.OBSERVACION_INTERNA)||' '||SUBSTR(INITCAP(CA.DESCRIPCION),17,11)
              WHEN C.CLIENTE = 136 AND C.OBSERVACION_INTERNA = 'CONSUMO' THEN 'BBVA'||' '||INITCAP(C.OBSERVACION_INTERNA)||' '||SUBSTR(INITCAP(CA.DESCRIPCION),18,11)
              WHEN C.CLIENTE = 136 AND C.OBSERVACION_INTERNA = 'SOBREGIRO' THEN 'BBVA'||' '||INITCAP(C.OBSERVACION_INTERNA)||' '||SUBSTR(INITCAP(CA.DESCRIPCION),17,11)
              WHEN C.CLIENTE = 136 AND C.OBSERVACION_INTERNA = 'AUTO' THEN 'BBVA'||' '||INITCAP(C.OBSERVACION_INTERNA)||' '||SUBSTR(INITCAP(CA.DESCRIPCION),18,11)
              WHEN C.CLIENTE = 136 AND C.OBSERVACION_INTERNA = 'MICROCREDITO' THEN 'BBVA'||' '||INITCAP(C.OBSERVACION_INTERNA)||' '||SUBSTR(INITCAP(CA.DESCRIPCION),18,11)
              ELSE INITCAP(CA.DESCRIPCION)
              END DESCRIPCION_CARTERA,
               
           CASE 
             -- PROVINCIAL
             WHEN C.CLIENTE = 136 AND C.CARTERA = 511 AND C.OBSERVACION_INTERNA = '' THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 511 AND C.OBSERVACION_INTERNA IS NULL THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 511 AND C.OBSERVACION_INTERNA = 'SOBREGIRO' THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 511 AND C.OBSERVACION_INTERNA = 'TDC' THEN 'TDC'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 511 AND C.OBSERVACION_INTERNA = 'CONSUMO' THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 511 AND C.OBSERVACION_INTERNA = 'AUTO' THEN 'AUTO'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 511 AND C.OBSERVACION_INTERNA = 'MICROCREDITO' THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 512 AND C.OBSERVACION_INTERNA = '' THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 512 AND C.OBSERVACION_INTERNA IS NULL THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 512 AND C.OBSERVACION_INTERNA = 'SOBREGIRO' THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 512 AND C.OBSERVACION_INTERNA = 'TDC' THEN 'TDC'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 512 AND C.OBSERVACION_INTERNA = 'CONSUMO' THEN 'OTROS'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 512 AND C.OBSERVACION_INTERNA = 'AUTO' THEN 'AUTO'
             WHEN C.CLIENTE = 136 AND C.CARTERA = 512 AND C.OBSERVACION_INTERNA = 'MICROCREDITO' THEN 'OTROS'
             WHEN CA.PRODUCTO = 'GIR-FAC' OR CA.PRODUCTO = 'PAG' OR CA.PRODUCTO = 'PCRE' THEN 'OTROS' 
              ELSE
             CA.PRODUCTO
           END  AS PRODUCTO,
         TO_CHAR(C.CUENTA)||' ',
        TO_CHAR(C.CONTRATO)||' ',
       SUM (NVL(MONTO_DEPOSITO,0)) TOTAL_COBRADO,
       NVL(a.Comi_vpc,0) PORCENTAJE_COMISION,
       NVL(REPLACE(a.COMi_VPC,'.',','),0) *  SUM (NVL(MONTO_DEPOSITO,0)) COMISION,
       TO_CHAR(A.FECHA_DEPOSITO,'DD/MM/YYYY')  FECHA_DEPOSITO_ABONO, 
       TO_CHAR(A.FECHA_INGRESO,'DD/MM/YYYY') FECHA_INGRESO
       
    FROM         
       TG_PERSONA P, 
       SR_TIPO_CUENTA T,
       SR_CUENTA C, 
       SR_ABONO A, 
       SR_CARTERA CA, 
       SR_COMI_GR_GESTOR CG, 
       TG_CLIENTE CL, 
       SR_USUARIO S, 
       SR_USUARIO G, 
       TG_REGION R,
       SR_GRUPO_GESTOR SGG 
      -- FACTURACION F
    WHERE     A.FECHA_INGRESO BETWEEN TO_DATE('".$desde."') AND TO_DATE('".$hasta."')+0.9999
    AND       A.STATUS_ABONO = 'C'  
    AND       C.CLIENTE = A.CLIENTE
    AND       C.CARTERA = A.CARTERA
    AND       C.CUENTA = A.CUENTA
    AND       CG.CLIENTE(+) = A.CLIENTE
    AND       CG.CARTERA(+) = A.CARTERA
    AND       CG.TIPO_CUENTA(+) = A.TIPO_CUENTA
    AND       CG.GRUPO_GESTOR(+) = A.GRUPO_GESTOR
    AND       CA.CLIENTE = A.CLIENTE
    AND           CA.CARTERA = A.CARTERA
    AND           CL.CLIENTE = C.CLIENTE
    AND       R.REGION = S.REGION
    AND       S.USUARIO = A.USUARIO_SUPERVISOR
    AND           G.USUARIO = A.USUARIO_GESTOR
    AND           C.PERSONA = P.PERSONA
    AND       G.GRUPO_GESTOR = SGG.GRUPO_GESTOR
    AND       T.CLIENTE = C.CLIENTE
    AND       T.CARTERA = C.CARTERA
    AND       T.TIPO_CUENTA = C.TIPO_CUENTA
    --AND       A.CUENTA = F.CUENTA
     GROUP BY S.REGION, R.DESCRIPCION, A.USUARIO_SUPERVISOR, S.NOMBRES, A.USUARIO_GESTOR, G.NOMBRES,
             G.CATEGORIA, G.TIPO_COD,C.OBSERVACION_INTERNA,
             CA.DESCRIPCION, A.FECHA_DEPOSITO,A.FECHA_INGRESO,
             C.CLIENTE, CL.NOMBRE, C.CARTERA,   C.SALDO_INICIAL,   
             P.NOMBRE,P.ID, CA.PRODUCTO, C.CUENTA,C.CONTRATO,  CG.COMISION,
             A.TIPO_CUENTA, SGG.DESCRIPCION,A.FACTURA,a.COMi_VPC "; 
 
// preparamos el statement para la consulta  

$st = oci_parse($conn, $sql);  

//ejecutamos el query  
oci_execute($st);  
$cont=0; 

 $objPHPExcel->setActiveSheetIndex(0);
// mostramos los resultados  
  //  print_r(oci_fetch_array($st));
while ($value = oci_fetch_array($st)) { 
            // Agregar Informacion
         // echo $fila[6].'<br>';
  $cont=$cont+1;
   IF ($cont == 1){ 
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'factura');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'usuario_supervisor');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'nombre_supervisor');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'usuario_gestor');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'nombre_gestor');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'tipo_cod');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'cedula');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'nombre_deudor');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'cliente');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'producto');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'cuenta');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'contrato');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'total_cobrado');
            $objPHPExcel->getActiveSheet()->SetCellValue('N1', 'porc_comi');
            $objPHPExcel->getActiveSheet()->SetCellValue('O1', 'monto_facturacion');
            $objPHPExcel->getActiveSheet()->SetCellValue('P1', 'fecha_deposito');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q1', 'fecha_ingreso');
 
            
     }ELSE{
            
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $cont);
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $value[0]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $value[1]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $value[2]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $value[3]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $value[4]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $value[5]);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $value[6]); 
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, $value[7]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, $value[8]);
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $value[9]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $value[10]);
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $value[11]);
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont,str_replace(',','.',$value[12]));
            $objPHPExcel->getActiveSheet()->SetCellValue('N'.$cont,str_replace(',','.',$value[13]));
            $objPHPExcel->getActiveSheet()->SetCellValue('O'.$cont,str_replace(',','.',$value[14]));
            $objPHPExcel->getActiveSheet()->SetCellValue('P'.$cont,$value[15]);   
            $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$cont,$value[16]);
      
          

    }
}
            // Renombrar Hoja*/
           $objPHPExcel->getActiveSheet()->setTitle('Facturacion');

            // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
            $objPHPExcel->setActiveSheetIndex(0);

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_Facturacion_'.str_replace('/','-',$_POST['desde']).'_'.str_replace('/','-',$_POST['hasta']).'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
      //print_r($sql);
        //FIN DEL PROCESO DE EXPORTACION ABONOS--------------------------------------------------------------------- 
              ?>
            