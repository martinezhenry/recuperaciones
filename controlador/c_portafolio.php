<?php
@session_start();
define ('RANGO_BUSQUEDA', 20);
($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';
require_once $_SESSION['ROOT_PATH']."/modelo/conexion.php";
require_once 'c_autorizar.php';
 require_once $_SESSION['ROOT_PATH'].'/vista/moneda/moneda.php';

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);

function cargarCuentasAsiganadas ($usuario, $sql)
{
    $id = 44;
    global $po;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
        global $conex;

        $st = $conex->consulta($sql);


        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $cuentasAsignadas[] = $fila;
            }
            $_SESSION['maximo'] = false;
            return $cuentasAsignadas;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else { // En caso de que no hayan resultados
            return $cuentasAsignadas = $po['not_tab1'];
            exit();
        }
    }
}


function reasignarCuentas($usuarioSolicitante, $traslados, $usuarioTraslado){
    $id = 45;
    global $po;
    if (!autorizar($id, $_SESSION['USER'])){
        echo "no autorizado";
        return false;
    } else {
        global $conex;
        $sql = "DELETE FROM  SR_TRASLADO_CUENTAS
                WHERE ESTATUS IS NULL
                AND USUARIO_INGRESO = '$usuarioSolicitante'";

        $st = $conex->consulta($sql);

        $sql = "SELECT SQ_TRASLADO_CUENTAS.NEXTVAL FROM DUAL";

        $st = $conex->consulta($sql);

        $secuencia = $conex ->fetch_array($st);

        $secuencia = $secuencia[0];


        foreach ($traslados as $value){



        $sql = "INSERT INTO SR_TRASLADO_CUENTAS
                  SELECT  
                  P.ID AS CEDULA,
                  L.CUENTA,
                  '$usuarioTraslado' AS USUARIO,
                  NULL,
                  SYSDATE,
                  '$usuarioSolicitante' AS COORDINADOR,
                  $secuencia AS SECUENCIA
                  FROM SR_CUENTA L , 
                  TG_PERSONA P 
                  WHERE L.PERSONA = P.PERSONA 
                  AND P.PERSONA = '$value'";

        $st = $conex->consulta($sql);
        
       // echo $sql;
        }

        // LLAMADO DEL PAQUETE;
        $sql = "BEGIN VPCTOTAL.P_TRASLADO_CUENTAS_NEW; end;";
        $st = $conex->consulta($sql);
        echo $po['not_tab2'];
        exit();
    
    }
}


function cargarAreas(){
        $id = 46;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
        $sql= "SELECT A.AREA_DEVOLUCION, A.DESCRIPCION
                FROM SR_AREA_DEVOLUCION A, SR_CUENTA C
                 WHERE C.AREA_DEVOLUCION = A.AREA_DEVOLUCION AND A.AREA_DEVOLUCION <> 25
                 GROUP BY A.DESCRIPCION, A.AREA_DEVOLUCION
                 ORDER BY A.DESCRIPCION";

        global $conex;

        $st = $conex->consulta($sql);


        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $areasDevolucion[] = $fila;
            }

            return $areasDevolucion;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else { // En caso de que no hayan resultados
            return $areasDevolucion = " ERROR: No existen Registros!";
            exit();
    }
    }
}


function enviarArea($cuenta, $area){
    $id = 47;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
    global $conex;
    $sql = "UPDATE SR_CUENTA CU SET CU.AREA_DEVOLUCION = '$area' WHERE CU.CUENTA = '$cuenta'";
    $st = $conex->consulta($sql);
    return true;
    }
    
}



function obtenerCuentasAreas($usuario){
    
        $id = 48;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {

        global $conex;

        $st = $conex->consulta($sql);


     $numrow = $conex->filas($st);

     if ($numrow > 0) {
         $st = $conex->consulta($sql);
         while ($fila = $conex->fetch_array($st)) {
             $cuentas[] = $fila;
         }

         return $cuentas;
         unset($output);
         unset($sql);
         unset($st);
         exit();
     } else { // En caso de que no hayan resultados
         return $cuentas = " ERROR: No existen Registros!";
         exit();
     }

    }
    
}


function cargarClientes ($usuario){
    
    $sql = "SELECT AC.CLIENTE, CLI.NOMBRE FROM SR_CUENTA AC, TG_CLIENTE CLI WHERE AC.USUARIO_GESTOR = '$usuario'
            AND CLI.CLIENTE = AC.CLIENTE
            GROUP BY AC.CLIENTE, CLI.NOMBRE";
    
    global $conex;

        $st = $conex->consulta($sql);


     $numrow = $conex->filas($st);

     if ($numrow > 0) {
         $st = $conex->consulta($sql);
         while ($fila = $conex->fetch_array($st)) {
             $clientes[] = $fila;
         }
         unset($output);
         unset($sql);
         unset($st);
         return $clientes;

     } else { // En caso de que no hayan resultados
         return $clientes = " ERROR: No existen Clientes!";
       
     }
    
    
}



function fechaAgenda($usuario, $cliente){
    
    $sql = "SELECT MIN(TRUNC(A.FECHA)) AS FECHA
            FROM SR_AGENDA A 
            WHERE A.USUARIO = '$usuario'
            AND A.CLIENTE = '$cliente'";
    
     global $conex;

        $st = $conex->consulta($sql);

        $fila = $conex->fetch_array($st);
          $fecha = $fila[0]; 
         unset($sql);
         unset($st);
         
          if (!empty($fecha)) {
              
              return (strtotime(date("d-m-Y", strtotime($fecha))) < strtotime(date("d-m-Y"))) ? false : true;
              
          }
              return true;
          
         
   
    
}


function cargarFiltroscuentas($campos){
    
    
    $sql = $_SESSION['sql_portafolio'];
    
    $sql = str_replace('{CAMPOS}', $campos, $sql);
    $sql = str_replace('{SQLINICIO}', 1, $sql);
    $sql = str_replace('{SQLFIN}', 'NUMFILA', $sql);
    $sql = str_replace('{F_CLIENTE}','',$sql);
    $sql = str_replace('{F_PRODUCTO}','',$sql);
    $sql = str_replace('{F_SITUACION_CUENTA}','',$sql);
    $sql = str_replace('{F_TIPO_GESTION}','',$sql);
    $sql = str_replace('{F_FECHA_ASIGNACION}','',$sql);
    $sql = str_replace('{F_FECHA_PROX_GESTION}','',$sql);
    $resp = false;
            global $conex;
            $sql .= " order by ".str_replace('DISTINCT','',$campos); 
        $st = $conex->consulta($sql);

         while ($fila = $conex->fetch_array($st)){
         $resp[] = $fila; 
         }
         unset($sql);
         unset($st);
         return $resp;
    
}


function contarCuentas(){
    
    $sql = $_SESSION['sql_portafolio'];
    $sql = str_replace('{CAMPOS}', 'COUNT(CUENTA)', $sql);
    $sql = str_replace('{SQLINICIO}', 1, $sql);
    $sql = str_replace('{SQLFIN}', 'NUMFILA', $sql);
    $sql = str_replace('{F_CLIENTE}','',$sql);
    $sql = str_replace('{F_PRODUCTO}','',$sql);
    $sql = str_replace('{F_SITUACION_CUENTA}','',$sql);
    $sql = str_replace('{F_TIPO_GESTION}','',$sql);
    $sql = str_replace('{F_FECHA_ASIGNACION}','',$sql);
    $sql = str_replace('{F_FECHA_PROX_GESTION}','',$sql);
    global $conex;

        $st = $conex->consulta($sql);

         while ($fila = $conex->fetch_array($st)){
         $resp = $fila[0];
         }
         unset($sql);
         unset($st);
         return $resp;
    
    
    
}


function sumarSaldos(){
    
    $sql = $_SESSION['sql_portafolio'];
    $sql = str_replace('{CAMPOS}', 'SUM(SALDO_ACTUAL)', $sql);
    $sql = str_replace('{SQLINICIO}', 1, $sql);
    $sql = str_replace('{SQLFIN}', 'NUMFILA', $sql);
    $sql = str_replace('{F_CLIENTE}','',$sql);
    $sql = str_replace('{F_PRODUCTO}','',$sql);
    $sql = str_replace('{F_SITUACION_CUENTA}','',$sql);
    $sql = str_replace('{F_TIPO_GESTION}','',$sql);
    $sql = str_replace('{F_FECHA_ASIGNACION}','',$sql);
    $sql = str_replace('{F_FECHA_PROX_GESTION}','',$sql);
    global $conex;

        $st = $conex->consulta($sql);

         while ($fila = $conex->fetch_array($st)){
         $resp = $fila[0];
         }
         unset($sql);
         unset($st);
         return $resp;
    
    
    
}




if (isset($_GET['cargarAsignaciones']))
{   
    $campos = "PERSONA, CEDULA, NOMBRE, CUENTA, CLIENTE, SITUACION_CUENTA, PRODUCTO, SALDO_ACTUAL, SALDO_INICIAL, ASESOR, FECHA_ASIGNACION, TIPO_GESTION, FECHA_PROXIMA_GESTION";
    $usuario = $_GET['usuario'];
    $filtro = $_GET['filtro'];
   // $inicio = $_GET['Inicio'];
   // $fin = $_GET['Fin'];
    $agrega = $_GET['agrega'];
    $tipo = $_GET['identificador'];
    $validaFecha;
    
    $_SESSION['sql_inicio'] = 0;
    $_SESSION['sql_fin'] = RANGO_BUSQUEDA;
    
  //  echo "cargarAsig";
   // exit();
  
    if ($tipo != "0") // ================== ASESOR ==========================
        {
       
           if ($filtro == "NULL"){
                
           
            $sql = "SELECT {CAMPOS}
                        FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
           AND CU.STATUS_CUENTA = 'A' 
           and CU.USUARIO_GESTOR = '$usuario' 
                        AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
      --  AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
        --WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 
                        
                      ";
            
            } else if ($filtro == "convenio"){
                
                $status = $_GET['status'];
               
                if ($status == "0"){
                    
                    $sql = "  SELECT {CAMPOS}
                        FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                                 AND EXISTS(SELECT CO.CONVENIO
                                                     FROM SR_CONVENIO CO
                                                     WHERE CU.CUENTA = CO.CUENTA
                                                      AND CU.CARTERA = CO.CARTERA
                                                      AND CU.CLIENTE = CO.CLIENTE
                                                      ) 
                                 AND CU.STATUS_CUENTA = 'A'
                                 and CU.USUARIO_GESTOR = '$usuario'
                                 AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
      --  AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
       -- WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 

                                    ";
                       
                    
                } else {
            //$usuario = $_GET['usuario'];
            
            $sql = "SELECT {CAMPOS}
                                    FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
           AND EXISTS(SELECT CO.CONVENIO
                                     FROM SR_CONVENIO CO
                                     WHERE CU.CUENTA = CO.CUENTA
                                      AND CU.CARTERA = CO.CARTERA
                                      AND CU.CLIENTE = CO.CLIENTE
                                      AND CO.CONV_STATUS = '$status') 
                 AND CU.STATUS_CUENTA = 'A'
                 and CU.USUARIO_GESTOR = '$usuario'
                 AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
      --  AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
       -- WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 
                
                ";
                }
                
            } else if ($filtro == "exoneracion"){
                
                $status = $_GET['status'];
                
                if ($status == "0"){
                    
                                $sql = "SELECT {CAMPOS}
                                   FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                         AND EXISTS(SELECT EX.CUENTA
                                             FROM SR_EXONERA_CUENTA EX
                                             WHERE CU.CUENTA = EX.CUENTA
                                             AND CU.CARTERA = EX.CARTERA
                                             AND CU.CLIENTE = EX.CLIENTE
                                             ) 
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR = '$usuario'
                       AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
       -- AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
        --WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 
                         
                       ";
                    
                    
                } else {
                
                $sql = "SELECT {CAMPOS}
                        FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                         AND EXISTS(SELECT EX.CUENTA
                                             FROM SR_EXONERA_CUENTA EX
                                             WHERE CU.CUENTA = EX.CUENTA
                                             AND CU.CARTERA = EX.CARTERA
                                             AND CU.CLIENTE = EX.CLIENTE
                                              AND EX.IN_STATUS = '$status') 
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR = '$usuario'
                         AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
       -- AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
        --WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 
                         
                       ";
                
            } 
            
            
        } else if ($filtro == "area"){
       
              
                $status = $_GET['status'];
                $cliente = $_GET['cliente'];
                
                 if (!fechaAgenda($usuario, $cliente)){
                    
                    $validaFecha = 0;
                    
                } else {
                    
                    $validaFecha = 1;
                    
                }
            
             
            if ($status == "0"){
                
                $sql = "SELECT {CAMPOS}
                                       FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NOT NULL
                         AND CU.AREA_DEVOLUCION <> 25
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.CLIENTE = '$cliente'
                         AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
      --  AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
        --WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 
                         
                        "; 
                
            } else {
                
                     $sql = "SELECT {CAMPOS}
                                          FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
                         AND CU.AREA_DEVOLUCION = '$status'
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.AREA_DEVOLUCION <> 25
                         and CU.CLIENTE = '$cliente'
                        AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
      --  AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
       -- WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 
                         
                         ";
                
                
            }
            
            
        } else if ($filtro == "aprobarAreas"){
            
            $sql = "SELECT {CAMPOS}
                                FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
            AND CU.STATUS_CUENTA = 'A'
            and CU.USUARIO_GESTOR = '$usuario'
           AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
     --   AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
       -- WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN} 
                         ";
            
            
            
        }
        
        
        $muestraBegin = "";
            $muestraEnd = "";
        
        } else //========================= SUPERVISOR =============================
        {
            
           
                 if ($filtro == "NULL"){
                
                $sql = "SELECT {CAMPOS}
                                        FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.USUARIO_SUPERIOR = '$usuario' )
                         AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
     --   AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
        --WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                         
                         ";
            
          
                
            } else if ($filtro == "convenio"){
                
                $status = $_GET['status'];
                
                if ($status == "0"){
                    
                    $sql= "SELECT {CAMPOS}
                                          FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                             AND EXISTS(SELECT CO.CONVENIO
                                                 FROM SR_CONVENIO CO
                                                 WHERE CU.CUENTA = CO.CUENTA
                                                  AND CU.CARTERA = CO.CARTERA
                                                  AND CU.CLIENTE = CO.CLIENTE
                                                  ) 
                             AND CU.STATUS_CUENTA = 'A'
                             and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                            AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
     --   AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
       -- WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                            ";    
                } else {
                
                $sql = "SELECT {CAMPOS}
                                    FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                         AND EXISTS(SELECT CO.CONVENIO
                                             FROM SR_CONVENIO CO
                                             WHERE CU.CUENTA = CO.CUENTA
                                              AND CU.CARTERA = CO.CARTERA
                                              AND CU.CLIENTE = CO.CLIENTE
                                              AND CO.CONV_STATUS = '$status') 
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                       AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
     --   AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
      --  WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                        "; 
                }
                
                
            } else if ($filtro == "exoneracion") {
                
                $status = $_GET['status'];
                
                if ($status == "0"){
                    
                                    $sql="SELECT {CAMPOS}
                                           FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                             AND EXISTS(SELECT EX.CUENTA
                                                 FROM SR_EXONERA_CUENTA EX
                                                 WHERE CU.CUENTA = EX.CUENTA
                                                 AND CU.CARTERA = EX.CARTERA
                                                 AND CU.CLIENTE = EX.CLIENTE
                                                 ) 
                             AND CU.STATUS_CUENTA = 'A'
                             and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                             AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
     --   AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
       -- WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                           "; 
                
                } else {
                
                $sql = "SELECT {CAMPOS}
                                         FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
           AND CU.AREA_DEVOLUCION IS NULL
                             AND EXISTS(SELECT EX.CUENTA
                                                 FROM SR_EXONERA_CUENTA EX
                                                 WHERE CU.CUENTA = EX.CUENTA
                                                 AND CU.CARTERA = EX.CARTERA
                                                 AND CU.CLIENTE = EX.CLIENTE
                                                  AND EX.IN_STATUS = '$status') 
                             AND CU.STATUS_CUENTA = 'A'
                             and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                             AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
     --   AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
      --  WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                         
         "; 
                
                
            } 
            
    
            
            
            } else if ($filtro == "area"){
                
           
                $status = $_GET['status'];
                $cliente = $_GET['cliente'];
                
               $validaFecha = 1;
                
    
            if ($status == "0"){
                // BUSQUEDA DE TODAS LAS AREAS POR CLIENTE
                $sql = "SELECT {CAMPOS}
                                       FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
                         AND CU.AREA_DEVOLUCION IS NOT NULL
                         AND CU.AREA_DEVOLUCION <> 25
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.CLIENTE = '$cliente'
                        AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
      --  AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
        --WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                         
                         "; 
                
            } else {
            $sql= "SELECT {CAMPOS}
                                    FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
                         AND CU.AREA_DEVOLUCION = '$status'
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.AREA_DEVOLUCION <> 25
                         AND CU.CLIENTE = '$cliente'
                        AND  CLI.NOMBRE LIKE '%{F_CLIENTE}%'
                        AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%{F_PRODUCTO}%'
                        AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%{F_SITUACION_CUENTA}%'
                        AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%{F_FECHA_ASIGNACION}%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
        AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%{F_TIPO_GESTION}%'
     --   AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%{F_FECHA_PROX_GESTION}%'
        ) TL 
        --WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                         
                         "; 
            
        }
                
                
                
            } else if ($filtro == "aprobarAreas"){
                
                $sql = "SELECT {CAMPOS}
                                      FROM ( 
 SELECT p.PERSONA, p.CEDULA, p.NOMBRE, p.CUENTA,p.CLIENTE, p.SITUACION_CUENTA, p.PRODUCTO, p.SALDO_ACTUAL, p.SALDO_INICIAL, p.ASESOR, p.FECHA_ASIGNACION,
 CASE
 WHEN trunc(Q.FECHA_INGRESO) > trunc(P.FECHA_ASIGNACION) THEN DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION)
 ELSE 'SIN GESTION (ASIGNACION)'
 END TIPO_GESTION,
 rownum NUMFILA, Q.FECHA_PROXIMA_GESTION from            
(SELECT  l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION,max(K.GESTION ) as gestion
FROM ( 
select CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA,
            CU.CLIENTE as client,
            CU.CARTERA, 
            CLI.NOMBRE as CLIENTE, 
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') 
            ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END AS SITUACION_CUENTA, 
            CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03') THEN 'TDC' ELSE 'CXC' END AS PRODUCTO , 
            CU.SALDO_ACTUAL, CU.SALDO_INICIAL , CU.USUARIO_GESTOR as asesor, 
            CU.FECHA_ASIG_GESTOR as fecha_asignacion 
            from sr_cuenta cu, 
            tg_persona pe, 
            tg_cliente cli, 
            sr_cartera ca, 
            sr_usuario us
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE 
            and CU.USUARIO_GESTOR = US.USUARIO 
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = CLI.CLIENTE 
                        AND CU.AREA_DEVOLUCION IS NULL
                        AND CU.STATUS_CUENTA = 'A'
                        and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.USUARIO_SUPERIOR = 'S5702' )
                --         AND  CLI.NOMBRE LIKE '%%'
              --          AND CASE WHEN (SUBSTR(TRIM(CU.CUENTA),1,1)) IN ('5','4','3') OR (SUBSTR(TRIM(CU.CUENTA),1,2)) IN ('03')  THEN 'TDC' ELSE 'CXC' END LIKE '%%'
            --            AND case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.') ELSE decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') END LIKE '%%'
          --              AND TO_CHAR(CU.FECHA_ASIG_GESTOR, 'DD/MM/YYYY') LIKE '%%'
                        ) l
           LEFT  JOIN sr_gestion k
          on   K.CUENTA = l.cuenta
          and K.CLIENTE = l.client
          and K.CARTERA = l.CARTERA
          and K.TABLA_GESTION <> 0
        group by l.PERSONA, l.CEDULA, l.NOMBRE, l.CUENTA, l.CLIENTE, l.SITUACION_CUENTA, l.PRODUCTO, l.SALDO_ACTUAL, l.SALDO_INICIAL, l.ASESOR, l.FECHA_ASIGNACION) p, 
        sr_gestion q,
        sr_codigo_gestion y
        where 
        Y.CODIGO_GESTION (+) = Q.CODIGO_GESTION
        and Y.GRUPO_GESTION (+) = Q.GRUPO_GESTION
        and Y.TABLA_GESTION (+) = Q.TABLA_GESTION
        and Q.GESTION (+)= p.gestion
       -- AND DECODE(Y.DESCRIPCION, '','SIN GESTION',Y.DESCRIPCION) LIKE '%%'
        --AND TO_CHAR(Q.FECHA_PROXIMA_GESTION, 'DD/MM/YYYY') LIKE '%%'
        ) TL 
       -- WHERE TL.NUMFILA BETWEEN '{SQLINICIO}' AND {SQLFIN}
                        ";
                
            }
            
                      $muestraBegin = "";
            $muestraEnd = "";
            
        }
            
        $_SESSION['sql_portafolio'] = $sql;
        $sql = str_replace('{CAMPOS}',$campos,$sql);
        $sql = str_replace('{SQLINICIO}',$_SESSION['sql_inicio'],$sql);
        $sql = str_replace('{SQLFIN}',$_SESSION['sql_fin'],$sql);
        
        $_SESSION['F_CLIENTE']  = '';
        $_SESSION['F_PRODUCTO'] = '';
        $_SESSION['F_SITUACION_CUENTA'] = '';
        $_SESSION['F_TIPO_GESTION'] = '';
        $_SESSION['F_FECHA_ASIGNACION'] = '';
        $_SESSION['F_FECHA_PROX_GESTION'] = '';
        
        $sql = str_replace('{F_CLIENTE}',$_SESSION['F_CLIENTE'],$sql);
        $sql = str_replace('{F_PRODUCTO}',$_SESSION['F_PRODUCTO'],$sql);
        $sql = str_replace('{F_SITUACION_CUENTA}',$_SESSION['F_SITUACION_CUENTA'],$sql);
        $sql = str_replace('{F_TIPO_GESTION}',$_SESSION['F_TIPO_GESTION'],$sql);
        $sql = str_replace('{F_FECHA_ASIGNACION}',$_SESSION['F_FECHA_ASIGNACION'],$sql);
        $sql = str_replace('{F_FECHA_PROX_GESTION}',$_SESSION['F_FECHA_PROX_GESTION'],$sql);
        
        
   
        if (isset($validaFecha)){
            if (!$validaFecha){
            //$cuentasAsignadas = cargarCuentasAsiganadas($usuario, $sql);
               $cuentasAsignadas = "Debe gestionar las cuentas que se encuentran en retraso";
                
            } else {

                $cuentasAsignadas = cargarCuentasAsiganadas($usuario, $sql);

            }
        } else {
            
           $cuentasAsignadas = cargarCuentasAsiganadas($usuario, $sql);
            
        }
        
      
        
  if ($agrega == "NULL")
  {
      $arrfiltros = array('cliente' => 'DISTINCT ·CLIENTE·',
                        'producto' => 'DISTINCT ·PRODUCTO·',
                        'situacion_cuenta' => 'DISTINCT ·SITUACION_CUENTA·',
                        'tipo_gestion' => 'DISTINCT ·TIPO_GESTION·',
                        'fecha_asignacion' => 'DISTINCT TO_CHAR(·FECHA_ASIGNACION·,\'DD/MM/YYYY\')',
                        'fecha_prox_gestion' => 'DISTINCT TO_CHAR(·FECHA_PROXIMA_GESTION·,\'DD/MM/YYYY\')'
          );
      
      $arrEtiqueta = array(
            'cliente' => 'Seleccione Cliente',
            'producto' => 'Seleccione Producto',
            'situacion_cuenta' => 'Seleccione Mora',
            'tipo_gestion' => 'Seleccione Tipo de Gestion',
            'fecha_asignacion' => 'Seleccione Fecha de Asignación',
            'fecha_prox_gestion' => 'Seleccione Fecha de Proxima Gestion'
          
          
      );
      /*
       if (is_array($cuentasAsignadas)){
           
      echo '<div id="filtros_sql">';
     
      foreach ($arrfiltros as $key => $value){
          $resp = cargarFiltroscuentas($value);
          if (is_array($resp)){
              echo '<select style="width : 180px;" onchange="busquedaPorFiltros();" id="'.$key.'" name="'.$key.'">';
              echo '<option value="">'.$arrEtiqueta[$key].'</option>';
              
            //  asort($resp);
              
              foreach ($resp as $value) {
                  echo '<option value="'.$value[0].'">'.$value[0].'</option>';
              }
              echo '</select>';
          }
          
      }
      
    
    echo '
         </div>';

           
      }
      */
    
   
    
    echo '<table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="asignaciones-Tabla">
                        <thead>
                            
                            <tr>
                                <th id ="0t" onclick="checkTodos.click();" align = \'center\'><b><input hidden type=\'checkbox\' id="checkTodos" name=\'checkTodos\' onclick="activarDesactivarChecks(document.getElementsByName(\'checkPortafolio[]\'), document.getElementById(this.id));"/> Check</b></th>
                                <th id ="1t">'.$po['pt_tab1'].'</th>
                                <th id ="2t">'.$po['pt_tab2'].'</th>
                                <th id ="3t">'.$po['pt_tab3'].'</th>
                                <th id ="4t">'.$po['pt_tab4'].'</th>
                                <th id ="5t">'.$po['pt_tab5'].'</th>
                                <th id ="6t">'.$po['pt_tab6'].'</th>
                                <th id ="7t">'.$po['pt_tab7'].'</th>
                                <th id ="8t">'.$po['pt_tab8'].'</th>
            '.$muestraBegin.'   <th id ="9t">'.$po['pt_tab9'].'</th>          '.$muestraEnd.'
                                <th id ="10t">'.$po['pt_tab10'].'</th>
                                <th id ="11t">'.$po['pt_tab11'].'</th>
                               
                           
                                
                            </tr>
                        </thead>
                        <tbody>';
                        $i=0;
                        if (is_array($cuentasAsignadas)){
                            
                        foreach ($cuentasAsignadas as $detalles) {
                            
                            
                            
                            $num = $detalles['SALDO_ACTUAL'];
                            //$num = str_replace(",",".", $num);
                            //$num = number_format($num, 2, ',', '.');
                            echo '<tr class="gradeA">

                                <td class="center"><input type="checkbox" name="checkPortafolio[]" id="'.$detalles['PERSONA']."*".$detalles['CUENTA'].'" value="'.$detalles['PERSONA']."*".$detalles['CUENTA'].'"></td>
                                <td><a href="#" onclick="expedienteDblClick(\''.$detalles['PERSONA']."*".$detalles['CUENTA'].'\')">'.$detalles['CEDULA'].'</a></td>
                                <td>'.$detalles['NOMBRE'].'</td>
                                <td>'.$detalles['CUENTA'].'</td>
                                <td>'.$detalles['CLIENTE'].'</td>
                                <td>'.$detalles['PRODUCTO'].'</td>
                                <td>'.$detalles['SITUACION_CUENTA'].'</td>
                                <td id="saldo_'.$i.'" class="suma">'.$_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$num)).'</td>
                                <td></td>
        '.$muestraBegin.'       <td>'.$detalles['ASESOR'].'</td>               '.$muestraEnd.'
                                <td>'.$detalles['FECHA_ASIGNACION'].'</td>    
                                <td>'.$detalles['TIPO_GESTION'].'</td>
                     
                                

                                    </tr>'; $i++;
                        } 
                        
                        } else {
                           
                            if (!$cuentasAsignadas){
                                echo '<label id="noA" class="alerta">No Autorizado!</label>';
                            } else {
                                
                                echo '<label id="noA" class="alerta">'.$cuentasAsignadas.'</label>';
                                
                            }
                            
                        }
 
                            echo '</tbody>
                                			
                        
                            </table>
                            

                            ';
                            exit();
                            
  } 
  
  
  
  
    
    
} else if (isset($_GET['cargarAreas'])){
    
    $areasDevolucion = cargarAreas();
    //print_r($areasDevolucion);
    echo '
       
        <select style="width : 257px;" id="filtroArea" onchange="cargarClientes(usuarioActualSeleccionado.value); reiniciaLimites(); reiniciaSelects([\'filtroExoneracion\', \'filtroConvenio\']); if (this.selectedIndex == 0) { activarDesactivarBotones([\'btnEnviarArea\'], \'true\'); activarDesactivarSelects([\'selcCliente\'], 0); cargarAsignaciones(\'NULL\', usuarioActualSeleccionado.value, document.getElementById(\'ges\').selectedIndex, \'area\', this.value); } else { activarDesactivarBotones([\'btnEnviarArea\']); activarDesactivarSelects([\'selcCliente\'], 1); } if (this.selectedIndex == 0) { filtroUsado.value = \'NULL\' } else { filtroUsado.value = \'area\' } document.getElementById(\'statusUsado\').value=this.value">
    
        <option value="-1">'.$po['tab7'].'...</option>             
        <option value="0">'.$po['tab8'].'</option>';
        
        foreach ($areasDevolucion as $detalles)
        {
         echo '<option value ="'.$detalles['AREA_DEVOLUCION'].'">'.$detalles['DESCRIPCION'].'</option>';
        }
                                               
        echo  '</select>';
    
} else if (isset($_GET['reasignarCuentas'])){
    
    $traslados = $_GET['traslados'];
    
    $traslados = explode(",", $traslados);
    $traslados = array_unique($traslados);
    
    //print_r($traslados);
    
    $usuarioTraslado = $_GET['usuarioTraslado'];
    //session_start();
    $usuarioSolicitante = $_SESSION['USER'];
    //$usuarioSolicitante = "GTE01";
    reasignarCuentas($usuarioSolicitante, $traslados, $usuarioTraslado);
    
   
    
    
        // $sql = oci_parse($conn,"BEGIN VPCTOTAL.P_TRASLADO_CUENTAS_PHP; end;");
    
    
    
} else if (isset($_GET['asignarArea'])){
    
    $cuentas = $_GET['cuentas'];
    
    $cuentas = explode(",", $cuentas);
    $area = $_GET['area'];
    
    foreach ($cuentas as $value) {
        
        enviarArea($value, $area);
        
    }
    
    
    echo $po['not_tab3'];
     
    
    
} else if (isset($_GET['cargarClientes'])){
    
    
   $clientes = cargarClientes($_GET['usuario']);
   
   if (is_array($clientes)){
                  echo "<option selected disabled value=''>Seleccione...</option>";
       foreach ($clientes as $value) {
           
           echo "<option value='".$value['CLIENTE']."'>".$value['NOMBRE']."</option>";
           
       }
       
   } else {
       
       echo "<option>".$clientes."</option>";
       
   }
   exit();
   
    
} else if (isset($_POST['agregarFilas'])){
    
    $campos = "PERSONA, CEDULA, NOMBRE, CUENTA, CLIENTE, SITUACION_CUENTA, PRODUCTO, SALDO_ACTUAL, SALDO_INICIAL, ASESOR, FECHA_ASIGNACION, TIPO_GESTION, '' AS FECHA_PROXIMA_GESTION";
    $_SESSION['sql_inicio'] = ($_SESSION['sql_fin'])+1;
    $final = (($_SESSION['sql_fin']))+RANGO_BUSQUEDA;
    $_SESSION['sql_fin'] = $final;
    $sql = str_replace('{CAMPOS}',$campos,$_SESSION['sql_portafolio']);
    $sql = str_replace('{SQLINICIO}',$_SESSION['sql_inicio'],$sql);
    $sql = str_replace('{SQLFIN}',$_SESSION['sql_fin'],$sql);
    $sql = str_replace('{F_CLIENTE}',$_SESSION['F_CLIENTE'],$sql);
    $sql = str_replace('{F_PRODUCTO}',$_SESSION['F_PRODUCTO'],$sql);
    $sql = str_replace('{F_SITUACION_CUENTA}',$_SESSION['F_SITUACION_CUENTA'],$sql);
    $sql = str_replace('{F_TIPO_GESTION}',$_SESSION['F_TIPO_GESTION'],$sql);
    $sql = str_replace('{F_FECHA_ASIGNACION}',$_SESSION['F_FECHA_ASIGNACION'],$sql);
    $sql = str_replace('{F_FECHA_PROX_GESTION}',$_SESSION['F_FECHA_PROX_GESTION'],$sql);
    
   // $cuentasAsignadas = cargarCuentasAsiganadas('', $sql);
   // echo json_encode($cuentasAsignadas);
    (!$_SESSION['maximo']) ? $cuentasAsignadas = cargarCuentasAsiganadas('', $sql) : exit();
      // echo $_SESSION['sql_portafolio'];
     if (is_array($cuentasAsignadas)) {
         
         foreach($cuentasAsignadas as $key => $value) {
             
             $cuentasAsignadas[$key]['SALDO_ACTUAL'] = $_SESSION['simb_moneda']." ".cambiarMoneda(str_replace(',','.',$value['SALDO_ACTUAL']));
             
         }
         
         $resp = json_encode($cuentasAsignadas);
                
     } else {
         
          $_SESSION['maximo'] = true;
         
     }
    // echo $sql;
     echo (!$_SESSION['maximo']) ? $resp : "";
    exit();
   
    
} else if (isset($_POST['busquedaPorFiltros'])){
    
    //$campos = "PERSONA, CEDULA, NOMBRE, CUENTA, CLIENTE, SITUACION_CUENTA, PRODUCTO, SALDO_ACTUAL, SALDO_INICIAL, ASESOR, FECHA_ASIGNACION, TIPO_GESTION";
    //$_SESSION['sql_inicio'] = 1;
    $final = 0;
    $_SESSION['sql_fin'] = $final;
    $_SESSION['F_CLIENTE']  = ($_POST['id'] == 'cliente') ? $_POST['valor'] : $_SESSION['F_CLIENTE']; 
    $_SESSION['F_PRODUCTO'] = ($_POST['id'] == 'producto') ? $_POST['valor'] : $_SESSION['F_PRODUCTO']; 
    $_SESSION['F_SITUACION_CUENTA'] = ($_POST['id'] == 'situacion_cuenta') ? $_POST['valor'] : $_SESSION['F_SITUACION_CUENTA'];
    $_SESSION['F_TIPO_GESTION'] = ($_POST['id'] == 'tipo_gestion') ? $_POST['valor'] : $_SESSION['F_TIPO_GESTION'];
    $_SESSION['F_FECHA_ASIGNACION'] = ($_POST['id'] == 'fecha_asignacion') ? $_POST['valor'] : $_SESSION['F_FECHA_ASIGNACION'];
    $_SESSION['F_FECHA_PROX_GESTION'] = ($_POST['id'] == 'fecha_prox_gestion') ? $_POST['valor'] : $_SESSION['F_FECHA_PROX_GESTION'];
    $_SESSION['maximo'] = false;
    echo $_SESSION['F_CLIENTE'];
    exit();
    //$sql = str_replace('{CAMPOS}',$campos,$_SESSION['sql_portafolio']);
    //$sql = str_replace('{SQLINICIO}',$_SESSION['sql_inicio'],$sql);
    //$sql = str_replace('{SQLFIN}',$_SESSION['sql_fin'],$sql);
    //$cuentasAsignadas = cargarCuentasAsiganadas('', $sql);
    
} else if(isset ($_POST['contarCuentas'])) {
    
    $cantidad = contarCuentas();
    echo $cantidad;
    
} else if(isset ($_POST['sumarSaldos'])) {
    
    $cantidad = sumarSaldos();
    if (empty($cantidad)) $cantidad = 0.00;
    $cantidad = str_replace(',', '.', $cantidad);
    
    echo cambiarMoneda($cantidad);
    
}




?>