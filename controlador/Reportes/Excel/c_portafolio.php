<?php
@session_start();
require_once ($_SESSION['ROOT_PATH']."/controlador/c_portafolio.php"); 
 
// VARIABLES GET'S RECIBIDAS
    $usuario = $_POST['usuario'];
    $filtro = $_POST['filtro'];
    $tipo = $_POST['identificador'];

 
   if ($tipo != "0") // ================== ASESOR ==========================
        {
       
           if ($filtro == "NULL"){
                
           
            $sql = "
                        select  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         GES.FECHA_PROMESA,
                         GES.FECHA_PROXIMA_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = CLI.CLIENTE
                         
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION IS NULL
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR = '$usuario'
                      ";
            
            } else if ($filtro == "convenio"){
                
                $status = $_POST['status'];
               
                if ($status == "0"){
                    
                    $sql = "
                                select  CU.PERSONA, 
                                PE.ID as cedula, 
                                PE.NOMBRE, 
                                CU.CUENTA, 
                                CLI.NOMBRE as cliente,
                                case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                                decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                                ELSE
                                decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                                END AS  SITUACION_CUENTA ,
                                CASE
                                WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                                'TDC'
                                ELSE
                                'CXC'
                                END AS PRODUCTO ,     
                                 CU.SALDO_ACTUAL,
                                 CU.SALDO_INICIAL ,
                                 CU.USUARIO_GESTOR as asesor,
                                 TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                                 CODGES.DESCRIPCION AS TIPO_GESTION,
                                 ROWNUM NUMFILA
                                 from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                                 where CU.CARTERA = CA.CARTERA 
                                 and CU.CLIENTE = CLI.CLIENTE
                                 and CU.USUARIO_GESTOR = US.USUARIO 
                                 and CU.PERSONA = PE.PERSONA 
                                 and CA.CLIENTE = GES.CLIENTE
                                 and CU.CARTERA = GES.CARTERA
                                 and CU.CLIENTE = GES.CLIENTE
                                 and CU.CUENTA = GES.CUENTA
                                 and GES.GESTION = (select max(G.GESTION) 
                                                               from SR_GESTION g 
                                                               where CU.CARTERA = G.CARTERA
                                                               and CU.CLIENTE = G.CLIENTE
                                                               and CU.CUENTA = G.CUENTA
                                                               and G.TABLA_GESTION <> 0 )
                                 and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                                 and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                                 and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                                 AND CU.AREA_DEVOLUCION IS NULL
                                 AND EXISTS(SELECT CO.CONVENIO
                                                     FROM SR_CONVENIO CO
                                                     WHERE CU.CUENTA = CO.CUENTA
                                                      AND CU.CARTERA = CO.CARTERA
                                                      AND CU.CLIENTE = CO.CLIENTE
                                                      ) 
                                 AND CU.STATUS_CUENTA = 'A'
                                 and CU.USUARIO_GESTOR = '$usuario'";
                       
                    
                } else {
            //$usuario = $_GET['usuario'];
            
            $sql = "
               
                select  CU.PERSONA, 
                PE.ID as cedula, 
                PE.NOMBRE, 
                CU.CUENTA, 
                CLI.NOMBRE as cliente,
                case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                ELSE
                decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                END AS  SITUACION_CUENTA ,
                CASE
                WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                'TDC'
                ELSE
                'CXC'
                END AS PRODUCTO ,     
                 CU.SALDO_ACTUAL,
                 CU.SALDO_INICIAL ,
                 CU.USUARIO_GESTOR as asesor,
                 TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                 CODGES.DESCRIPCION AS TIPO_GESTION,
                 ROWNUM NUMFILA
                 from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                 where CU.CARTERA = CA.CARTERA 
                 and CU.CLIENTE = CLI.CLIENTE
                 and CU.USUARIO_GESTOR = US.USUARIO 
                 and CU.PERSONA = PE.PERSONA 
                 and CA.CLIENTE = GES.CLIENTE
                 and CU.CARTERA = GES.CARTERA
                 and CU.CLIENTE = GES.CLIENTE
                 and CU.CUENTA = GES.CUENTA
                 and GES.GESTION = (select max(G.GESTION) 
                                               from SR_GESTION g 
                                               where CU.CARTERA = G.CARTERA
                                               and CU.CLIENTE = G.CLIENTE
                                               and CU.CUENTA = G.CUENTA
                                               and G.TABLA_GESTION <> 0 )
                 and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                 and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                 and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                 AND CU.AREA_DEVOLUCION IS NULL
                 AND EXISTS(SELECT CO.CONVENIO
                                     FROM SR_CONVENIO CO
                                     WHERE CU.CUENTA = CO.CUENTA
                                      AND CU.CARTERA = CO.CARTERA
                                      AND CU.CLIENTE = CO.CLIENTE
                                      AND CO.CONV_STATUS = '$status') 
                 AND CU.STATUS_CUENTA = 'A'
                 and CU.USUARIO_GESTOR = '$usuario'
                ";
                }
                
            } else if ($filtro == "exoneracion"){
                
                $status = $_POST['status'];
                
                if ($status == "0"){
                    
                                $sql = "
                        SELECT  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = GES.CLIENTE
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION IS NULL
                         AND EXISTS(SELECT EX.CUENTA
                                             FROM SR_EXONERA_CUENTA EX
                                             WHERE CU.CUENTA = EX.CUENTA
                                             AND CU.CARTERA = EX.CARTERA
                                             AND CU.CLIENTE = EX.CLIENTE
                                             ) 
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR = '$usuario'
                       ";
                    
                    
                } else {
                
                $sql = "
                        SELECT  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = GES.CLIENTE
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION IS NULL
                         AND EXISTS(SELECT EX.CUENTA
                                             FROM SR_EXONERA_CUENTA EX
                                             WHERE CU.CUENTA = EX.CUENTA
                                             AND CU.CARTERA = EX.CARTERA
                                             AND CU.CLIENTE = EX.CLIENTE
                                              AND EX.IN_STATUS = '$status') 
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR = '$usuario'
                       ";
                
            } 
            
            
        } else if ($filtro == "area"){
       
              
                $status = $_POST['status'];
                $cliente = $_POST['cliente'];
                
                 if (!fechaAgenda($usuario, $cliente)){
                    
                    $validaFecha = 0;
                    
                } else {
                    
                    $validaFecha = 1;
                    
                }
            
             
            if ($status == "0"){
                
                $sql = "SELECT  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = GES.CLIENTE
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION IS NOT NULL
                         AND CU.AREA_DEVOLUCION <> 25
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.CLIENTE = '$cliente'"; 
                
            } else {
                
                     $sql = "
                        select  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = GES.CLIENTE
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION = '$status'
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.AREA_DEVOLUCION <> 25
                         and CU.CLIENTE = '$cliente'
                         ";
                
                
            }
            
            
        } else if ($filtro == "aprobarAreas"){
            
            $sql = "SELECT  CU.PERSONA, 
            PE.ID as cedula, 
            PE.NOMBRE, 
            CU.CUENTA, 
            CLI.NOMBRE as cliente,
            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
            decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
            ELSE
            decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
            END AS  SITUACION_CUENTA ,
            CASE
            WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
            'TDC'
            ELSE
            'CXC'
            END AS PRODUCTO ,     
            CU.SALDO_ACTUAL,
            CU.SALDO_INICIAL ,
            CU.USUARIO_GESTOR as asesor,
            TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
            CODGES.DESCRIPCION AS TIPO_GESTION
            from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca,  sr_codigo_gestion codges, SR_GESTION ges
            where CU.CARTERA = CA.CARTERA 
            and CU.CLIENTE = CLI.CLIENTE
            and CU.PERSONA = PE.PERSONA 
            and CA.CLIENTE = GES.CLIENTE
            and CU.CARTERA = GES.CARTERA
            and CU.CLIENTE = GES.CLIENTE
            and CU.CUENTA = GES.CUENTA
            and GES.GESTION = (select max(G.GESTION) 
                                         from SR_GESTION g 
                                         where CU.CARTERA = G.CARTERA
                                         and CU.CLIENTE = G.CLIENTE
                                         and CU.CUENTA = G.CUENTA
                                         and G.TABLA_GESTION <> 0 )
            and GES.TABLA_GESTION = CODGES.TABLA_GESTION
            and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
            and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
            and CODGES.AREA = 1
            AND CU.AREA_DEVOLUCION IS NULL
            AND CU.STATUS_CUENTA = 'A'
            and CU.USUARIO_GESTOR = '$usuario'";
            
            
            
        }
        
        
        $muestraBegin = "<!-- ";
            $muestraEnd = " -->";
        
        } else //========================= SUPERVISOR =============================
        {
            
           
                 if ($filtro == "NULL"){
                
                $sql = "
                        select  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = CLI.CLIENTE
                        
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION IS NULL
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.USUARIO_SUPERIOR = '$usuario' )
                         ";
            
          
                
            } else if ($filtro == "convenio"){
                
                $status = $_POST['status'];
                
                if ($status == "0"){
                    
                    $sql= "
                            select  CU.PERSONA, 
                            PE.ID as cedula, 
                            PE.NOMBRE, 
                            CU.CUENTA, 
                            CLI.NOMBRE as cliente,
                            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                            decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                            ELSE
                            decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                            END AS  SITUACION_CUENTA ,
                            CASE
                            WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                            'TDC'
                            ELSE
                            'CXC'
                            END AS PRODUCTO ,     
                             CU.SALDO_ACTUAL,
                             CU.SALDO_INICIAL ,
                             CU.USUARIO_GESTOR as asesor,
                             TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                             CODGES.DESCRIPCION AS TIPO_GESTION,
                             ROWNUM NUMFILA
                             from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                             where CU.CARTERA = CA.CARTERA 
                             and CU.CLIENTE = CLI.CLIENTE
                             and CU.USUARIO_GESTOR = US.USUARIO 
                             and CU.PERSONA = PE.PERSONA 
                             and CA.CLIENTE = GES.CLIENTE
                             and CU.CARTERA = GES.CARTERA
                             and CU.CLIENTE = GES.CLIENTE
                             and CU.CUENTA = GES.CUENTA
                             and GES.GESTION = (select max(G.GESTION) 
                                                           from SR_GESTION g 
                                                           where CU.CARTERA = G.CARTERA
                                                           and CU.CLIENTE = G.CLIENTE
                                                           and CU.CUENTA = G.CUENTA
                                                           and G.TABLA_GESTION <> 0 )
                             and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                             and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                             and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                             AND CU.AREA_DEVOLUCION IS NULL
                             AND EXISTS(SELECT CO.CONVENIO
                                                 FROM SR_CONVENIO CO
                                                 WHERE CU.CUENTA = CO.CUENTA
                                                  AND CU.CARTERA = CO.CARTERA
                                                  AND CU.CLIENTE = CO.CLIENTE
                                                  ) 
                             AND CU.STATUS_CUENTA = 'A'
                             and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                            ";    
                } else {
                
                $sql = "
                        SELECT  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = GES.CLIENTE
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION IS NULL
                         AND EXISTS(SELECT CO.CONVENIO
                                             FROM SR_CONVENIO CO
                                             WHERE CU.CUENTA = CO.CUENTA
                                              AND CU.CARTERA = CO.CARTERA
                                              AND CU.CLIENTE = CO.CLIENTE
                                              AND CO.CONV_STATUS = '$status') 
                         AND CU.STATUS_CUENTA = 'A'
                         and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                        "; 
                }
                
                
            } else if ($filtro == "exoneracion") {
                
                $status = $_POST['status'];
                
                if ($status == "0"){
                    
                                    $sql="
                            SELECT  CU.PERSONA, 
                            PE.ID as cedula, 
                            PE.NOMBRE, 
                            CU.CUENTA, 
                            CLI.NOMBRE as cliente,
                            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                            decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                            ELSE
                            decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                            END AS  SITUACION_CUENTA ,
                            CASE
                            WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                            'TDC'
                            ELSE
                            'CXC'
                            END AS PRODUCTO ,     
                             CU.SALDO_ACTUAL,
                             CU.SALDO_INICIAL ,
                             CU.USUARIO_GESTOR as asesor,
                             TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                             CODGES.DESCRIPCION AS TIPO_GESTION,
                             ROWNUM NUMFILA
                             from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                             where CU.CARTERA = CA.CARTERA 
                             and CU.CLIENTE = CLI.CLIENTE
                             and CU.USUARIO_GESTOR = US.USUARIO 
                             and CU.PERSONA = PE.PERSONA 
                             and CA.CLIENTE = GES.CLIENTE
                             and CU.CARTERA = GES.CARTERA
                             and CU.CLIENTE = GES.CLIENTE
                             and CU.CUENTA = GES.CUENTA
                             and GES.GESTION = (select max(G.GESTION) 
                                                           from SR_GESTION g 
                                                           where CU.CARTERA = G.CARTERA
                                                           and CU.CLIENTE = G.CLIENTE
                                                           and CU.CUENTA = G.CUENTA
                                                           and G.TABLA_GESTION <> 0 )
                             and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                             and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                             and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                             AND CU.AREA_DEVOLUCION IS NULL
                             AND EXISTS(SELECT EX.CUENTA
                                                 FROM SR_EXONERA_CUENTA EX
                                                 WHERE CU.CUENTA = EX.CUENTA
                                                 AND CU.CARTERA = EX.CARTERA
                                                 AND CU.CLIENTE = EX.CLIENTE
                                                 ) 
                             AND CU.STATUS_CUENTA = 'A'
                             and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                           "; 
                
                } else {
                
                $sql = "
                            SELECT  CU.PERSONA, 
                            PE.ID as cedula, 
                            PE.NOMBRE, 
                            CU.CUENTA, 
                            CLI.NOMBRE as cliente,
                            case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                            decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                            ELSE
                            decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                            END AS  SITUACION_CUENTA ,
                            CASE
                            WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                            'TDC'
                            ELSE
                            'CXC'
                            END AS PRODUCTO ,     
                             CU.SALDO_ACTUAL,
                             CU.SALDO_INICIAL ,
                             CU.USUARIO_GESTOR as asesor,
                             TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                             CODGES.DESCRIPCION AS TIPO_GESTION,
                             ROWNUM NUMFILA
                             from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                             where CU.CARTERA = CA.CARTERA 
                             and CU.CLIENTE = CLI.CLIENTE
                             and CU.USUARIO_GESTOR = US.USUARIO 
                             and CU.PERSONA = PE.PERSONA 
                             and CA.CLIENTE = GES.CLIENTE
                             and CU.CARTERA = GES.CARTERA
                             and CU.CLIENTE = GES.CLIENTE
                             and CU.CUENTA = GES.CUENTA
                             and GES.GESTION = (select max(G.GESTION) 
                                                           from SR_GESTION g 
                                                           where CU.CARTERA = G.CARTERA
                                                           and CU.CLIENTE = G.CLIENTE
                                                           and CU.CUENTA = G.CUENTA
                                                           and G.TABLA_GESTION <> 0 )
                             and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                             and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                             and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                             AND CU.AREA_DEVOLUCION IS NULL
                             AND EXISTS(SELECT EX.CUENTA
                                                 FROM SR_EXONERA_CUENTA EX
                                                 WHERE CU.CUENTA = EX.CUENTA
                                                 AND CU.CARTERA = EX.CARTERA
                                                 AND CU.CLIENTE = EX.CLIENTE
                                                  AND EX.IN_STATUS = '$status') 
                             AND CU.STATUS_CUENTA = 'A'
                             and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.STATUS_USUARIO = 'A' AND L.USUARIO_SUPERIOR = '$usuario' )
                             "; 
                
                
            } 
            
    
            
            
            } else if ($filtro == "area"){
                
           
                $status = $_POST['status'];
                $cliente = $_POST['cliente'];
                
               $validaFecha = 1;
                
    
            if ($status == "0"){
                // BUSQUEDA DE TODAS LAS AREAS POR CLIENTE
                $sql = "SELECT  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = GES.CLIENTE
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION IS NOT NULL
                         AND CU.AREA_DEVOLUCION <> 25
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.CLIENTE = '$cliente'"; 
                
            } else {
            $sql= "
                        select  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                         CU.SALDO_ACTUAL,
                         CU.SALDO_INICIAL ,
                         CU.USUARIO_GESTOR as asesor,
                         TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                         CODGES.DESCRIPCION AS TIPO_GESTION,
                         ROWNUM NUMFILA
                         from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca, sr_usuario us, sr_codigo_gestion codges, SR_GESTION ges
                         where CU.CARTERA = CA.CARTERA 
                         and CU.CLIENTE = CLI.CLIENTE
                         and CU.USUARIO_GESTOR = US.USUARIO 
                         and CU.PERSONA = PE.PERSONA 
                         and CA.CLIENTE = GES.CLIENTE
                         and CU.CARTERA = GES.CARTERA
                         and CU.CLIENTE = GES.CLIENTE
                         and CU.CUENTA = GES.CUENTA
                         and GES.GESTION = (select max(G.GESTION) 
                                                       from SR_GESTION g 
                                                       where CU.CARTERA = G.CARTERA
                                                       and CU.CLIENTE = G.CLIENTE
                                                       and CU.CUENTA = G.CUENTA
                                                       and G.TABLA_GESTION <> 0 )
                         and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                         and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                         and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                         AND CU.AREA_DEVOLUCION = '$status'
                         AND CU.STATUS_CUENTA = 'A'
                         AND CU.AREA_DEVOLUCION <> 25
                         AND CU.CLIENTE = '$cliente'
                         "; 
            
        }
                
                
                
            } else if ($filtro == "aprobarAreas"){
                
                $sql = "SELECT  CU.PERSONA, 
                        PE.ID as cedula, 
                        PE.NOMBRE, 
                        CU.CUENTA, 
                        CLI.NOMBRE as cliente,
                        case WHEN decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.') = '' THEN
                        decode(CU.SALDO_ACTUAL,CU.SALDO_INICIAL ,'CAST.','VENC.')
                        ELSE
                        decode(CU.SITUACION_CUENTA,'C','CAST.','VENC.')
                        END AS  SITUACION_CUENTA ,
                        CASE
                        WHEN TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 5 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 4 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,1)) = 3 OR TO_NUMBER(SUBSTR(CU.CUENTA,1,2)) = 3 THEN
                        'TDC'
                        ELSE
                        'CXC'
                        END AS PRODUCTO ,     
                        CU.SALDO_ACTUAL,
                        CU.SALDO_INICIAL ,
                        CU.USUARIO_GESTOR as asesor,
                        TO_CHAR(CU.FECHA_ASIG_GESTOR, 'dd/mm/yyyy') as fecha_asignacion,
                        CODGES.DESCRIPCION AS TIPO_GESTION
                        from sr_cuenta cu, tg_persona pe, tg_cliente cli, sr_cartera ca,  sr_codigo_gestion codges, SR_GESTION ges
                        where CU.CARTERA = CA.CARTERA 
                        and CU.CLIENTE = CLI.CLIENTE
                        and CU.PERSONA = PE.PERSONA 
                        and CA.CLIENTE = GES.CLIENTE
                        and CU.CARTERA = GES.CARTERA
                        and CU.CLIENTE = GES.CLIENTE
                        and CU.CUENTA = GES.CUENTA
                        and GES.GESTION = (select max(G.GESTION) 
                                                     from SR_GESTION g 
                                                     where CU.CARTERA = G.CARTERA
                                                     and CU.CLIENTE = G.CLIENTE
                                                     and CU.CUENTA = G.CUENTA
                                                     and G.TABLA_GESTION <> 0 )
                        and GES.TABLA_GESTION = CODGES.TABLA_GESTION
                        and GES.CODIGO_GESTION = CODGES.CODIGO_GESTION
                        and GES.GRUPO_GESTION = CODGES.GRUPO_GESTION
                        and CODGES.AREA = 1
                        AND CU.AREA_DEVOLUCION IS NULL
                        AND CU.STATUS_CUENTA = 'A'
                        and CU.USUARIO_GESTOR IN (SELECT USUARIO FROM SR_USUARIO L WHERE L.USUARIO_SUPERIOR = '$usuario' )";
                
            }
            
                      $muestraBegin = "";
            $muestraEnd = "";
            
        }

   $cuentasAsignadas = cargarCuentasAsiganadas($usuario, $sql);     

    if (is_array($cuentasAsignadas)){
       
        echo json_encode($cuentasAsignadas);
        
    } else {
        
        
        echo "no es arreglo : ".$cuentasAsignadas;
        
    }
    exit();
    






?>