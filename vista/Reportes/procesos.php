<?PHP
       //Incluuimos la conexion con oracle 
	include("conexion.php");
        require_once 'Classes/PHPExcel/IOFactory.php';
        require_once 'Classes/PHPExcel.php';
        require_once("Classes/PHPExcel/Writer/Excel2007.php");
        require_once ("Classes/PHPExcel/Cell/AdvancedValueBinder.php"); 
	  
//PROCESO DE PORTAFOLIO------------------------------------------------------------------------------
     if($_POST['tipo']=='portafolio'){     
        if ($_POST['cliente'] == '0' ){
             // ECHO $_POST['cliente'];
             $sql = oci_parse($conn,"BEGIN VPCTOTAL.P_GENERA_PORTAFOLIO_ACTIVO();end;");
          }else{
               //ECHO $_POST['cliente'];
            $sql = oci_parse($conn,"BEGIN VPCTOTAL.P_GENERA_PORTAFOLIO_ACTIVO(:cliente);end;");  
            oci_bind_by_name($sql, ":cliente",$_POST['cliente']);    
          }
	  if (oci_execute($sql)){
              echo '<script language="javascript" type="text/javascript">
			alert("Genaracion exitosa");
			location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
		</script>';
          }
     }
   //FIN DEL PROCESO DE PORTAFOLIO ---------------------------------------------------------------------
   //
   //PROCESO DE TRASLADO------------------------------------------------------------------------------  
   if($_POST['tipo']=='traslado'){
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
            IF ($objHoja [$i]['A']== NULL && $objHoja [$i]['B']== NULL ||$objHoja [$i]['C'] == NULL ){
                //cambia estatus si es nulo
                $incidencia=1;  
                //eliminos lo que secargo en la tabla
                $sql = oci_parse($conn,"delete from TRASLADO_CUENTAS_PHP");
                            oci_execute($sql);
                 echo '<script language="javascript" type="text/javascript">
			alert("ERROR DEBE INGRESAR CEDULAS O CUENTAS Y USUARIO");
			location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                        </script>';
            }ELSE{
                //valido que el para saber si son cunetas o cedulas
                IF ($objHoja [$i]['A']== NULL){
                    $TIPO ='CUENTAS';                     
                       //Insertamos las cuentas que se van a trasladar
                    $sql = oci_parse($conn, "INSERT INTO TRASLADO_CUENTAS_PHP SELECT '','".trim($objHoja [$i]['B'])."','".strtoupper(trim($objHoja [$i]['C']))."' FROM DUAL");      
                        if (oci_execute($sql)){   
                            $cont++;   
                        }else{
                            $incidencia=1;
                            $sql = oci_parse($conn,"delete from TRASLADO_CUENTAS_PHP");
                             oci_execute($sql);
                             echo '<script language="javascript" type="text/javascript">
                            alert("Error en el traslado de cuentas");
                            location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                            </script>';
                        }                
                }ELSE {
                    $TIPO ='CEDULAS';
                    $sql = oci_parse($conn, "INSERT INTO TRASLADO_CUENTAS_PHP SELECT '".TRIM($objHoja [$i]['A'])."','','".strtoupper(trim($objHoja [$i]['C']))."' FROM DUAL");      
                    if (oci_execute($sql)){
                         $cont++;   
                        }else{
                            $incidencia=1;
                            $sql = oci_parse($conn,"delete from TRASLADO_CUENTAS_PHP");
                            oci_execute($sql);
                            echo '<script language="javascript" type="text/javascript">
                              alert("Error en el traslado de cedulas");
                              location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                            </script>';
                    }                
                }
            }
        }
           if ($incidencia != 1){
            $sql = oci_parse($conn,"BEGIN VPCTOTAL.P_TRASLADO_CUENTAS_PHP(".$_POST['cliente'].",'".$TIPO."');end;");
           }
            if (oci_execute($sql)){
             //procesamos registros
                  $sql = oci_parse($conn,"delete from TRASLADO_CUENTAS_PHP");
                  oci_execute($sql);
                  echo '<script language="javascript" type="text/javascript">
                            alert("Registro procesados ('.$cont.')");
                            location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                          </script>';
            }else{
                $sql = oci_parse($conn,"delete from TRASLADO_CUENTAS_PHP");
                 oci_execute($sql);
                 echo '<script language="javascript" type="text/javascript">
                           alert("Error en el paquete'.$_POST['cliente'].');
                           location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                         </script>';
            }
       //header ("Location: http://".$_SERVER['HTTP_HOST']."/oracle/mostrar.php?cliente=".$_POST['cliente']."&archivo=".$nombreArchivo);
   } 
   //FIN DEL PROCESO DE TRASLADO ---------------------------------------------------------------------  
   
   //PROCESO DE EXPORTACION ABONOS--------------------------------------------------------------------
      if($_POST['tipo']=='r_abono'){
         $desde=$_POST['desde'];
         $hasta=$_POST['hasta'];
            // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("Reporte abono")
            ->setSubject("Reporte abono")
            ->setDescription("Reporte abono")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("Reporte abono");
            
            // sql preparado  
$sql = "select   a.nombre as cliente, 
           to_char(G.ID) as cedula,
           b.contrato,
           b.cartera as codigo_cartera, 
           c.descripcion as cartera,
           d.cuenta,
           to_char(d.fecha_ingreso,'dd/mm/yyyy HH24:MI:SS'),
           to_char(d.fecha_deposito,'dd/mm/yyyy HH24:MI:SS'),
           d.monto_deposito,
           d.usuario_gestor, 
           e.nombres as gestor,
           e.usuario_superior,
           f.nombres as supervisor,
           d.status_abono 
from    tg_cliente a, sr_cuenta b, sr_cartera c, sr_abono d, sr_usuario e, sr_usuario f, tg_persona g
where  a.cliente = b.cliente
and b.cliente = c.cliente
and d.usuario_gestor = e.usuario
and b.persona = g.persona
and e.usuario_superior = f.usuario
and b.cartera = c.cartera
and trunc(d.fecha_ingreso) >= '$desde'
and trunc(d.fecha_ingreso) <= '$hasta' 
and b.cliente = d.cliente
and b.cartera = d.cartera
and b.cuenta = d.cuenta";  
  
// preparamos el statement para la consulta  
$st = oci_parse($conn, $sql);  

// ejecutamos el query  
oci_execute($st);  
$cont=0; 

 $objPHPExcel->setActiveSheetIndex(0);
// mostramos los resultados  
   // print_r(oci_fetch_array($st));
while ($value = oci_fetch_array($st)) { 
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
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $value[0]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $value[1]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $value[2].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $value[3]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $value[4]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $value[5].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $value[6]); 
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, $value[7]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, str_replace(',','.',$value[8]));
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $value[9]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $value[10]);
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $value[11]);
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, $value[12]);
            $objPHPExcel->getActiveSheet()->SetCellValue('N'.$cont, $value[13]);
    }
}
            // Renombrar Hoja
           $objPHPExcel->getActiveSheet()->setTitle('Abono');

            // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
            $objPHPExcel->setActiveSheetIndex(0);

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_abono_'.str_replace('/','-',$_POST['desde']).'_'.str_replace('/','-',$_POST['hasta']).'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
      }
        //FIN DEL PROCESO DE EXPORTACION ABONOS---------------------------------------------------------------------  
      
      //PROCESO DE EXPORTACION CIERRE PROVINCIAL--------------------------------------------------------------------
      
      if($_POST['tipo']=='c_provincial'){
         
         $desde = $_POST['desde'];
         $hasta = $_POST['hasta'];
         
         // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("Reporte abono Provincial")
            ->setSubject("Reporte abono Provincial")
            ->setDescription("Reporte abono Provincial")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("Reporte abono Provincial");
         
            // sql preparado  
$sql = "select c.id as cedula, 
         c.nombre, 
         a.contrato, 
         a.OBSERVACION_interna, 
         a.saldo_actual, 
         b.MONTO_DEPOSITO, 
         b.FECHA_DEPOSITO, 
         b.fecha_ingreso, 
         d.descripcion as FORMA_PAGO,
         B.OBSERVACION_INGRESO,
         b.status_Abono, 
         e.usuario as usuario_asesor,
         e.nombres as asesor
from sr_cuenta a, sr_abono b, tg_persona c, TG_FORMA_PAGO d, sr_usuario e
where a.cliente = 136
and a.cuenta = b.cuenta 
and a.cartera = b.cartera
and a.cliente = b.cliente
and a.persona = c.persona
and B.FORMA_PAGO = d.FORMA_PAGO(+)
and b.usuario_gestor = e.usuario
and status_abono = 'N'
and b.fecha_deposito BETWEEN '$desde' AND '$hasta'";  
  
// preparamos el statement para la consulta  
$st = oci_parse($conn, $sql);  

// ejecutamos el query  
oci_execute($st);  


$cont=0; 

 $objPHPExcel->setActiveSheetIndex(0);

 //varificamos el primer excel con los datos totales
 
 //INICIO DEL IF PAGOS TOTALES --------------------------------------------------------------------------------------
 if($st){
     
while ($value = oci_fetch_array($st)) { 
 
     //Recorre el contador
  $cont=$cont+1;
   
        //comparamos si contador en igual a 1 es la cabezera del excel sino el contenido
   IF ($cont == 1){ 
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'CEDULA');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'NOMBRE');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CONTRATO');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', strtoupper('OBSERVACION_interna'));
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', strtoupper('saldo_actual'));
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'MONTO_DEPOSITO');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'FECHA_DEPOSITO');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', strtoupper('fecha_ingreso'));
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'FORMA_PAGO');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'OBSERVACION_INGRESO');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', strtoupper('status_Abono'));
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', strtoupper('usuario_asesor'));
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', strtoupper('asesor'));
            
     }ELSE{
            
        //$objPHPExcel->getActiveSheet()->SetCellValue("A".$cont, $cont);
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $value[0]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $value[1]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $value[2].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $value[3].' ');
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $value[4]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $value[5]);
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $value[6]); 
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, $value[7]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, str_replace(',','.',$value[8]));
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $value[9]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $value[10]);
            $objPHPExcel->getActiveSheet()->SetCellValue('L'.$cont, $value[11]);
            $objPHPExcel->getActiveSheet()->SetCellValue('M'.$cont, $value[12]);
    }
}

//liberamos la memoria de oracle
oci_free_statement($st);


            // Renombrar Hoja
            $objPHPExcel->getActiveSheet()->setTitle('Pagos');
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
            $pagos = 'BBVA_PAGOS_VPC_'.str_replace('/','-',$_POST['desde']).'_'.str_replace('/','-',$_POST['hasta']).'.xlsx';
            $objWriter->save($pagos);
  
 }
 
 //FIN IF PAGOS TOTALES ------------------------------------------------------------------------
 
 
 
 //PAGOS TOTALES
 $sql_02 =" SELECT replace(replace(a.NOMBRE_TITULAR,chr(09),''),chr(13),'') as nombre_titular,
    a.cedula,
    c.CONTRATO, 
    a.NUMERO_TARJETA,
    to_char(b.FECHA_DEPOSITO,'DD/MM/YYYY') as FECHA_DEPOSITO,
    b.MONTO_DEPOSITO,
    'GRUPO VPC S.A.' as NOMBRE_RECUPERADORA,
    'RE10' as CODIGO_DE_AGENCIA,
    to_char(a.FECHA_ASIGNACION,'DD/MM/YYYY') as FECHA_ASIGNACION, 
    to_char(b.FECHA_INGRESO,'DD/MM/YYYY') as FECHA_GESTION,
    to_char(a.FECHA_INCUMPLIMIENTO,'DD/MM/YYYY') as FECHA_INCUMPLIMIENTO, 
    decode(a.fecha_castigo, TO_DATE('01/01/0001','DD/MM/YYYY'),null, to_char(a.fecha_castigo,'dd/mm/yyyy')) as FECHA_CASTIGO,
    a.codigo_producto, 
    a.codigo_sub_producto, 
    decode(a.FECHA_CASTIGO,TO_DATE('01/01/0001','DD/MM/YYYY'),'VENCIDO','CASTIGO') situacion_contrato, 
    case 
      when FECHA_CASTIGO = TO_DATE('01/01/0001','DD/MM/YYYY') then 'MORA NORMAL VENCIDO' 
      when FECHA_CASTIGO <= TO_DATE('31/12/2006','DD/MM/YYYY')  THEN 'MORA ANTIGUA'
      when FECHA_CASTIGO > TO_DATE('31/12/2006','DD/MM/YYYY')  THEN 'MORA NORMAL CASTIGO'
      else 'MORA NORMAL VENCIDO'
    end Tipo_Gestion,
    decode(c.tipo_cuenta_cliente,'1','TDC','CXC') as tipo_cuenta,
    c.PERSONA
  FROM  SR_V_CUENTAS_BBVA a, SR_ABONO b, SR_CUENTA c
  WHERE b.FECHA_DEPOSITO BETWEEN '$desde' AND '$hasta'
  AND     b.CLIENTE = 136
  --
  AND     a.CUENTA(+)  = c.CUENTA
  AND     a.CONTRATO(+) = c.CONTRATO
  --
  AND     b.CLIENTE = c.CLIENTE
  AND     b.CUENTA = c.CUENTA
  AND     b.CARTERA = c.CARTERA
  AND    substr(c.CONTRATO,9,2) = '50'";
 
 // preparamos el statement para la consulta  
$st_02 = oci_parse($conn, $sql_02);  

// ejecutamos el query  
oci_execute($st_02);


 //INICIO DEL IF VALIDACION POR PRODUCTO --------------------------------------------------------------------------------------
    
    //TDC--------------------------
    //
    // Crea un nuevo objeto PHPExcel para tdc
            $objPHPExcel_2 = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel_2->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("TDC")
            ->setSubject("Reporte abono Provincial")
            ->setDescription("Reporte abono Provincial TDC")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("TDC");
      //
      $objPHPExcel_2->setActiveSheetIndex(0);
            //
         
      
      //CXC-----------------------------------   
      //
    // Crea un nuevo objeto PHPExcel para tdc
            $objPHPExcel_3 = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel_3->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("Reporte abono Provincial CXC")
            ->setSubject("Reporte abono Provincial")
            ->setDescription("Reporte abono Provincial CXC")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("cXC");
      //
      $objPHPExcel_3->setActiveSheetIndex(0);
            //
             
      //CONTADOR PARA TDC 
      $contador = 0;
      //CONTADOR PARA CXC
      $cxc = 0;
     
//recorremos los resultados para TDC
while ($value = oci_fetch_array($st_02)) { 
    
    
    //Recorre el contador
            $contador = $contador+1;
            
       //comparamos si contador en igual a 1 es la cabezera del excel sino el contenido
   IF ($contador == 1){ 
            $objPHPExcel_2->getActiveSheet()->SetCellValue('A1', 'NOMBRE');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('B1', 'CEDULA');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('C1', 'CONTRATO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('D1', 'TARJETA');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('E1', 'FECHA_ABONO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('F1', 'MONTO_ABONO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('G1', 'NOMBRE_RECUPERADORA');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('H1', 'CODIGO_DE_AGENCIA');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('I1', 'FECHA_ASIGNACION');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('J1', 'FECHA_GESTION');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('K1', 'FECHA_IMPAGO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('L1', 'FECHA_CASTIGO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('M1', 'CODIGO_PRODUCTO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('N1', 'CODIGO_SUBPRODUCTO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('O1', 'SITUACION_CONTRATO');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('P1', 'TIPO_GESTION');
            
     }ELSE{
            
        //$objPHPExcel->getActiveSheet()->SetCellValue("A".$cont, $cont);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('A'.$contador, $value[0]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('B'.$contador, $value[1]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('C'.$contador, $value[2].' ');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('D'.$contador, $value[3].' ');
            $objPHPExcel_2->getActiveSheet()->SetCellValue('E'.$contador, $value[4]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('F'.$contador, $value[5]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('G'.$contador, $value[6]); 
            $objPHPExcel_2->getActiveSheet()->SetCellValue('H'.$contador, $value[7]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('I'.$contador, str_replace(',','.',$value[8]));
            $objPHPExcel_2->getActiveSheet()->SetCellValue('J'.$contador, $value[9]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('K'.$contador, $value[10]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('L'.$contador, $value[11]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('M'.$contador, $value[12]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('N'.$contador, $value[13]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('O'.$contador, $value[14]);
            $objPHPExcel_2->getActiveSheet()->SetCellValue('P'.$contador, $value[15]);
     }
           
}
//FIN DEL WHILE DE TDC 
//
//liberamos la memoria de oracle
oci_free_statement($st_02);

//creamos del excel de TDC---------------
 $objWriter_02 = new PHPExcel_Writer_Excel2007($objPHPExcel_2);
 $tdc = 'BBVA_PAGOS_VPC_TDC_'.str_replace('/','-',$_POST['desde']).'_'.str_replace('/','-',$_POST['hasta']).'.xlsx';
 $objWriter_02->save($tdc);
//
////
//
//
//
//FIN DEL TDC-----------------------------


//
//
//CXC-----------------------------
//
//PAGOS TOTALES
 $sql_03 ="SELECT replace(replace(a.NOMBRE_TITULAR,chr(09),''),chr(13),'') as nombre_titular,
    a.cedula,
    c.CONTRATO, 
    a.NUMERO_TARJETA,
    to_char(b.FECHA_DEPOSITO,'DD/MM/YYYY') as FECHA_DEPOSITO,
    b.MONTO_DEPOSITO,
    'GRUPO VPC S.A.' as NOMBRE_RECUPERADORA,
    'RE10' as CODIGO_DE_AGENCIA,
    to_char(a.FECHA_ASIGNACION,'DD/MM/YYYY') as FECHA_ASIGNACION, 
    to_char(b.FECHA_INGRESO,'DD/MM/YYYY') as FECHA_GESTION,
    to_char(a.FECHA_INCUMPLIMIENTO,'DD/MM/YYYY') as FECHA_INCUMPLIMIENTO, 
    decode(a.fecha_castigo, TO_DATE('01/01/0001','DD/MM/YYYY'),null, to_char(a.fecha_castigo,'dd/mm/yyyy')) as FECHA_CASTIGO,
    a.codigo_producto, 
    a.codigo_sub_producto, 
    decode(a.FECHA_CASTIGO,TO_DATE('01/01/0001','DD/MM/YYYY'),'VENCIDO','CASTIGO') situacion_contrato, 
    case 
      when FECHA_CASTIGO = TO_DATE('01/01/0001','DD/MM/YYYY') then 'MORA NORMAL VENCIDO' 
      when FECHA_CASTIGO <= TO_DATE('31/12/2006','DD/MM/YYYY')  THEN 'MORA ANTIGUA'
      when FECHA_CASTIGO > TO_DATE('31/12/2006','DD/MM/YYYY')  THEN 'MORA NORMAL CASTIGO'
      else 'MORA NORMAL VENCIDO'
    end Tipo_Gestion,
    decode(c.tipo_cuenta_cliente,'1','TDC','CXC') as tipo_cuenta,
    c.PERSONA
  FROM  SR_V_CUENTAS_BBVA a, SR_ABONO b, SR_CUENTA c
  WHERE b.FECHA_DEPOSITO BETWEEN '01/03/2014' AND '31/03/2014'
  AND     b.CLIENTE = 136
  --
  AND     a.CUENTA(+)  = c.CUENTA
  AND     a.CONTRATO(+) = c.CONTRATO
  --
  AND     b.CLIENTE = c.CLIENTE
  AND     b.CUENTA = c.CUENTA
  AND     b.CARTERA = c.CARTERA
 and substr(c.CONTRATO,9,2) <> '50'";
 
 // preparamos el statement para la consulta  
$st_03 = oci_parse($conn, $sql_03);  

// ejecutamos el query  
oci_execute($st_03);
//
//recorremos los resultados para TDC
while ($value = oci_fetch_array($st_03)) { 
    
    
    $cxc = $cxc +1;
    
       //comparamos si contador en igual a 1 es la cabezera del excel sino el contenido
   IF ($cxc == 1){ 
            $objPHPExcel_3->getActiveSheet()->SetCellValue('A1', 'NOMBRE');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('B1', 'CEDULA');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('C1', 'CONTRATO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('D1', 'TARJETA');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('E1', 'FECHA_ABONO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('F1', 'MONTO_ABONO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('G1', 'NOMBRE_RECUPERADORA');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('H1', 'CODIGO_DE_AGENCIA');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('I1', 'FECHA_ASIGNACION');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('J1', 'FECHA_GESTION');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('K1', 'FECHA_IMPAGO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('L1', 'FECHA_CASTIGO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('M1', 'CODIGO_PRODUCTO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('N1', 'CODIGO_SUBPRODUCTO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('O1', 'SITUACION_CONTRATO');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('P1', 'TIPO_GESTION');
            
     }ELSE{
            
      
            $objPHPExcel_3->getActiveSheet()->SetCellValue('A'.$cxc, $value[0]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('B'.$cxc, $value[1]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('C'.$cxc, $value[2].' ');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('D'.$cxc, $value[3].' ');
            $objPHPExcel_3->getActiveSheet()->SetCellValue('E'.$cxc, $value[4]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('F'.$cxc, $value[5]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('G'.$cxc, $value[6]); 
            $objPHPExcel_3->getActiveSheet()->SetCellValue('H'.$cxc, $value[7]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('I'.$cxc, str_replace(',','.',$value[8]));
            $objPHPExcel_3->getActiveSheet()->SetCellValue('J'.$cxc, $value[9]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('K'.$cxc, $value[10]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('L'.$cxc, $value[11]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('M'.$cxc, $value[12]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('N'.$cxc, $value[13]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('O'.$cxc, $value[14]);
            $objPHPExcel_3->getActiveSheet()->SetCellValue('P'.$cxc, $value[15]);
    }
         
    
}

oci_free_statement($st_03); //liberamos la memoria de oracle

//creamos del excel de TDC---------------
 $objWriter_03 = new PHPExcel_Writer_Excel2007($objPHPExcel_3);
 $cxc = 'BBVA_PAGOS_VPC_CXC_'.str_replace('/','-',$_POST['desde']).'_'.str_replace('/','-',$_POST['hasta']).'.xlsx';
 $objWriter_03->save($cxc);

//
//CXC FIN--------------------------

//INCLUIMOS EL ARCHIVOS PARA REALIZAR LAS DESCARGAS Y COMPRESION EN ZIP 
require_once('descarga.php'); 

      }
      
      //FIN DEL PROCESO DE EXPORTACION CIERRE PROVINCIAL---------------------------------------------------------------------
      //
   
      //
      //
      
   //PROCESO DE EXPORTACION Reporte Gestiones por Cuentas--------------------------------------------------------------------
      if($_POST['tipo']=='r_g_cuentas'){
         $cliente=$_POST['cliente'];
            // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("Reporte gestion por cuenta")
            ->setSubject("Reporte gestion por cuenta")
            ->setDescription("Reporte gestion por cuenta")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("Reporte gestion por cuenta");
            
            // sql preparado  
            $sql = "select b.nombre as cliente,
                     e.id as cedula,
                     a.cuenta, 
                     d.cartera as codigo_cartera,
                     d.descripcion as cartera,
                     to_char(a.fecha_ingreso,'dd/mm/yyyy HH24:MI:SS'),
                     a.area_devolucion,
                     a.saldo_actual,
                     count(*) as cantidad
            from sr_cuenta a, tg_cliente b, sr_gestion c, sr_cartera d, tg_persona e
            where a.cliente = b.cliente
            and a.cliente = c.cliente
            and a.cartera = c.cartera
            and a.cuenta = c.cuenta
            and c.fecha_ingreso >= a.fecha_ingreso
            and a.status_cuenta = 'A'
            and a.cliente = '$cliente'
            and a.cliente = d.cliente
            and a.cartera = d.cartera
            and a.persona = e.persona 
            group by b.nombre, e.id, a.cuenta, d.cartera, d.descripcion, a.fecha_ingreso, a.area_devolucion, a.saldo_actual
            union all
            select b.nombre as cliente,
                     e.id as cedula,
                     a.cuenta, 
                     d.cartera as codigo_cartera,
                     d.descripcion as cartera,
                     to_char(a.fecha_ingreso,'dd/mm/yyyy HH24:MI:SS'),
                     a.area_devolucion,
                     a.saldo_actual,
                     0 as cantidad
            from sr_cuenta a, tg_cliente b, sr_cartera d, tg_persona e
            where a.cliente = b.cliente
            and a.status_cuenta = 'A'
            and a.cliente = '$cliente'
            and a.cliente = d.cliente
            and a.cartera = d.cartera
            and a.persona = e.persona 
            and not exists (select f.cuenta from sr_gestion f where a.cliente = f.cliente and a.cartera = f.cartera and a.cuenta = f.cuenta and f.fecha_ingreso >= a.fecha_ingreso)";  

            // preparamos el statement para la consulta  
            $st = oci_parse($conn, $sql);  

            // ejecutamos el query  
            oci_execute($st);  
            $cont=0; 

             $objPHPExcel->setActiveSheetIndex(0);
            // mostramos los resultados  
               // print_r(oci_fetch_array($st));
            while ($value = oci_fetch_array($st)) { 
                        // Agregar Informacion
                 //       echo $fila[6].'<br>';
              $cont=$cont+1;
               IF ($cont == 1){ 
                        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'cliente');
                        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'cedula');
                        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'cuenta');
                        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'codigo_cartera');
                        $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'cartera');
                        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'fecha_ingreso');
                        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'area_devolucion');
                        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'saldo_actual');
                        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'cantidad');   
                 }ELSE{

                    //$objPHPExcel->getActiveSheet()->SetCellValue("A".$cont, $cont);
                        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $value[0]);
                        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $value[1]);
                        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $value[2].' ');
                        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $value[3]);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $value[4]);
                        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, $value[5]);
                        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $value[6]); 
                        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, str_replace(',','.',$value[7]));
                        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, $value[8]);
                }
            }
                        // Renombrar Hoja
           $objPHPExcel->getActiveSheet()->setTitle('Gestiones_cuentas');

            // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
            $objPHPExcel->setActiveSheetIndex(0);

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_gestiones_cuentas_'.$cliente);
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
      }
        //FIN DEL Reporte Gestiones por Cuentas---------------------------------------------------------------------
      
         //PROCESO DE Hora de primera gestion--------------------------------------------------------------------
      if($_POST['tipo']=='r_primera_hora'){
         $desde=$_POST['desde'];
         $hasta=$_POST['hasta'];
            // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("Hora de primera gestion")
            ->setSubject("Hora de primera gestion")
            ->setDescription("Hora de primera gestion")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("Hora de primera gestion");
            
            // sql preparado  
$sql = "select a.nombres as gestor, 
         d.id as cedula,
         to_char(b.fecha_ingreso,'dd/mm/yyyy HH24:MI:SS'),
         f.nombres,
         e.descripcion as gestion_vpc
from sr_usuario a, sr_gestion b, sr_cuenta c, tg_persona d, sr_codigo_gestion e, sr_usuario f
where a.usuario = b.usuario_gestor
and b.cuenta = c.cuenta
and b.cartera = c.cartera
and b.cliente = c.cliente
and c.persona = d.persona
and a.usuario_superior = f.usuario
and b.codigo_gestion = e.codigo_gestion
and trunc(b.fecha_ingreso) >= '$desde'
and trunc(b.fecha_ingreso) <= '$hasta'
order by b.fecha_ingreso";  
  
// preparamos el statement para la consulta  
$st = oci_parse($conn, $sql);  

// ejecutamos el query  
oci_execute($st);  
$cont=0; 

 $objPHPExcel->setActiveSheetIndex(0);
// mostramos los resultados  
   // print_r(oci_fetch_array($st));
while ($value = oci_fetch_array($st)) { 
            // Agregar Informacion
     //       echo $fila[6].'<br>';
  $cont=$cont+1;
   IF ($cont == 1){ 
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'gestor');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'cedula');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'fecha_ingreso');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'nombres');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'gestion_vpc');
            
     }ELSE{
            
        //$objPHPExcel->getActiveSheet()->SetCellValue("A".$cont, $cont);
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, utf8_encode($value[0]));
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $value[1]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, $value[2]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, $value[3]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $value[4]);
    }
}
            // Renombrar Hoja
           $objPHPExcel->getActiveSheet()->setTitle('Hora de primera gestion');

            // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
            $objPHPExcel->setActiveSheetIndex(0);

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_Hora_primera gestion_'.str_replace('/','-',$_POST['desde']).'_'.str_replace('/','-',$_POST['hasta']).'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
      }
        //FIN DEL PROCESO DE EXPORTACION ABONOS--------------------------------------------------------------------- 
        //
         //PROCESO DE INVENTARIO DE PORTAFOLIO-------------------------------------------------------------------
      if($_POST['tipo']=='inven_portafolio'){

            // Crea un nuevo objeto PHPExcel
            $objPHPExcel = new PHPExcel();

            // Establecer propiedades
            $objPHPExcel->getProperties()
            ->setCreator("Cattivo")
            ->setLastModifiedBy("Cattivo")
            ->setTitle("Hora de primera gestion")
            ->setSubject("Hora de primera gestion")
            ->setDescription("Hora de primera gestion")
            ->setKeywords("Excel Office 2007 openxml php")
            ->setCategory("Inventario de portafolio");

                            // sql preparado  
                $sql = "select b.CLIENTE,
                    b.nombre Cliente, 
                    a.usuario_gestor,
                    d.nombres,
                    d.CATEGORIA,
                    (select nombres from sr_usuario where usuario = d.USUARIO_SUPERIOR) Supervisor,
                    d.TIPO_COD,
                    c.DESCRIPCION Cartera, 
                    sum(a.SALDO_ACTUAL) Total_Saldo, 
                    count(distinct(a.persona)) Cedula, 
                    count(a.cuenta) as cuentas
                from sr_cuenta a, tg_cliente b, sr_cartera c, sr_usuario d
                where a.cliente = b.cliente
                and a.usuario_gestor = d.usuario
                and a.status_cuenta = 'A'
                and a.AREA_DEVOLUCION is null
                and a.cartera = c.cartera
                and a.cliente = c.cliente
                group by b.cliente,b.nombre, a.usuario_gestor,d.nombres,d.CATEGORIA,d.USUARIO_SUPERIOR,c.DESCRIPCION,d.TIPO_COD
                order by nombre";  

                // preparamos el statement para la consulta  
                $st = oci_parse($conn, $sql);  

                // ejecutamos el query  
                oci_execute($st);  
                $cont=0; 

                 $objPHPExcel->setActiveSheetIndex(0);
                // mostramos los resultados  
                   // print_r(oci_fetch_array($st));
                while ($value = oci_fetch_array($st)) { 
                            // Agregar Informacion
                     //       echo $fila[6].'<br>';
                  $cont=$cont+1;
   IF ($cont == 1){ 
            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'cliente');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'nombre cliente');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'usuario gestor');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'nombres');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'categoria');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'supervisor');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'tipo codigo');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Cartera');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Total Saldo');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Cedula');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'cuentas');
      
            
     }ELSE{
            
        //$objPHPExcel->getActiveSheet()->SetCellValue("A".$cont, $cont);
               $objPHPExcel->getActiveSheet()->SetCellValue('A'.$cont, $value[0]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$cont, $value[1]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$cont, utf8_encode($value[2]));
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$cont, utf8_encode($value[3]));
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$cont, $value[4]);
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$cont, utf8_encode($value[5]));
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$cont, $value[6]); 
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$cont, $value[7]);
            $objPHPExcel->getActiveSheet()->SetCellValue('I'.$cont, str_replace(',','.',$value[8]));
            $objPHPExcel->getActiveSheet()->SetCellValue('J'.$cont, $value[9]);
            $objPHPExcel->getActiveSheet()->SetCellValue('K'.$cont, $value[10]);
    }
}
            // Renombrar Hoja
           $objPHPExcel->getActiveSheet()->setTitle('Inventario de portafolio');

            // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
            $objPHPExcel->setActiveSheetIndex(0);

            // Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Inventario de portafolio"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
      }
        //FIN DEL PROCESO INVENTARIO DE PORTAFOLIO--------------------------------------------------------------------- 
      
        //PROCESO DE SMS------------------------------------------------------------------------------  
   if($_POST['tipo']=='sms'){
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
            IF ($objHoja [$i]['A']== NULL){
                //cambia estatus si es nulo
                $incidencia=1;  
                //eliminos lo que secargo en la tabla
                  echo '<script language="javascript" type="text/javascript">
			alert("ERROR DEBE INGRESAR CEDULAS EN EL ARCHIVO, DE ENVIARON ('.$cont.') SMS");
			location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                        </script>';
            }ELSE{
               $sql = oci_parse($conn,"BEGIN VPCTOTAL.P_ENVIO_SMS_CAM('".$objHoja [$i]['A']."','".$_POST['cliente']."',:sms); end;");
               oci_bind_by_name($sql, ":sms", $num);
                if (oci_execute($sql)){
             //procesamos registros
                //$num = oci_execute($sql);
                //var_dump($num);   
                 $cont = $cont+$num;
                 }else{
                    echo '<script language="javascript" type="text/javascript">
                           alert("Error en el paquete);
                           location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                         </script>';  
                 }
            }
        }
           echo '<script language="javascript" type="text/javascript">
                            alert("Se estan enviando ('.$cont.')");
                            location.href = "http://'.$_SERVER['HTTP_HOST'].'/oracle/paquetes.php";
                          </script>';
    }
        
   //FIN DEL PROCESO DE SMS ---------------------------------------------------------------------  
?>