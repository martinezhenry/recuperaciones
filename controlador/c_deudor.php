<?php
session_start();
// Llamamos al modelo de conexion
    require_once $_SESSION['ROOT_PATH']."/modelo/conexion.php";
    require_once 'c_autorizar.php';
    require_once $_SESSION['ROOT_PATH'].'/vista/moneda/moneda.php';
    ($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']); // instancia de clase oracle

/**
 *    
 *  Carga los datos del deudor de la BD
 * 
 *    PARAMETROS:
 *      -- $cedula = la cedula del deudor de la que se desea buscar los datos, puede ser de valor NULL
 *      -- $persona = el idPersona del deudor al que se desea consultar los datos, puede ser de valor NULL
 * 
 **/
function cargarDatosDeudor($cedula = "NULL", $persona = "NULL")
{
    $id = 16;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
        global $conex;
        $mostrar = "";

        if ($cedula != "NULL")
        {
            $sql = "select a.id,a.persona, a.nombre, a.tipo_persona, to_char(a.fecnac, 'DD/MM/YYYY') as fecnac from tg_persona a where a.id = '$cedula'";
        } else if ($cedula == "NULL" && $persona != "NULL")
        {
            $sql = "select id, persona, nombre, tipo_persona, to_char(fecnac,'DD/MM/YYYY') as fecnac from tg_persona where persona = '$persona'";
            $control_persona = 1;
        }
        //echo $sql;
        $st = $conex -> consulta($sql);

        $num = $conex->filas($st);
        
        if ($num == 1 || $num == 0 ) {
            $st = $conex -> consulta($sql);
        if ($fila = $conex -> fetch_array($st))
        {
           //session_start();
           $_SESSION['idPersona'] = $fila['PERSONA'];
           $nombre = $fila['NOMBRE'];
           $fechaNacimiento = $fila['FECNAC'];
           $nacionalidad = $fila['TIPO_PERSONA'];
           $cedula = $fila['ID'];
           $_SESSION['Cedula_P'] = $cedula;
          
           if (isset($control_persona)){
               $sql = "select count(a.id) from tg_persona a where a.id = '$cedula'"
                       . "AND EXISTS (SELECT b.persona FROM SR_CUENTA b WHERE a.persona = b.persona)";
               //echo "ak";
                $st = $conex -> consulta($sql);

               // $num = $conex->filas($st);
                $fila = $conex->fetch_array($st);
                
               // print_r($fila);
                if ($fila[0] > 1){
                    
                    return "1";
                    
                }
                
           }
           unset($_SESSION['ult_gestion']);
           return "$cedula*$nombre*$fechaNacimiento*$nacionalidad*".$_SESSION['idPersona'];

        } else
        {
            return "0";
        }

        } else {
            
            return "1";
            
        }
   
    }
    
    
    
    
}

/**
 *    
 *  Carga las cuentas del deudor de la BD
 * 
 *    PARAMETROS:
 *      -- $idPersona = el identificador del deudor (idPersona) del que se desea buscar los datos de las cuentas, puede ser de valor NULL
 *      -- $cuenta = la cuenta del deudor al que se desea consultar los datos de la cuenta, puede ser de valor NULL
 * 
 **/
function cargarCuentasDeudor($idPersona = "NULL", $cuenta = "NULL")
{
    $id = 17;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
        
    } else {
        global $conex;



        if($idPersona == "NULL" && $cuenta != "NULL")
        {

            $sql = " select c.nombre, L.CLIENTE,
                    L.CARTERA,t.DESCRIPCION,L.CUENTA, 
                    U.NOMBRES ||' (' || L.USUARIO_GESTOR || ')' AS USUARIO_GESTOR,
                     L.AREA_DEVOLUCION,       
                     L.SALDO_ACTUAL, TO_CHAR(L.FECHA_INGRESO, 'DD/MM/YYYY') AS FECHA_INGRESO, L.CAPITAL_VENCIDO, L.INTERESES_MORA,L.MONTO_TOTAL, l.tipo_cuenta,
                     L.USUARIO_GESTOR as GESTOR_CODIGO,
                     L.SITUACION_CUENTA
                    from sr_cuenta l, tg_cliente c, sr_tipo_credito t, SR_USUARIO U
                     where L.CLIENTE = C.CLIENTE and l.tipo_credito = t.tipo_credito
                     AND U.USUARIO = L.USUARIO_GESTOR
                      and l.cuenta = '$cuenta' ORDER BY L.AREA_DEVOLUCION DESC";
            
        } else if ($idPersona != "NULL" && $cuenta == "NULL")
        {

            $sql = " select c.nombre, L.CLIENTE,
                    L.CARTERA,t.DESCRIPCION,L.CUENTA, 
                    U.NOMBRES ||' (' || L.USUARIO_GESTOR || ')' AS USUARIO_GESTOR,
                     L.AREA_DEVOLUCION,       
                     L.SALDO_ACTUAL, TO_CHAR(L.FECHA_INGRESO, 'DD/MM/YYYY') AS FECHA_INGRESO, L.CAPITAL_VENCIDO, L.INTERESES_MORA,L.MONTO_TOTAL, l.tipo_cuenta
                     ,L.USUARIO_GESTOR as GESTOR_CODIGO,
                     L.SITUACION_CUENTA
                    from sr_cuenta l, tg_cliente c, sr_tipo_credito t, SR_USUARIO U
                     where L.CLIENTE = C.CLIENTE and l.tipo_credito = t.tipo_credito
                     AND U.USUARIO = L.USUARIO_GESTOR
                      and l.persona = '$idPersona' ORDER BY L.AREA_DEVOLUCION DESC";
            
        } else if ($idPersona != "NULL" && $cuenta != "NULL")
        {

           $sql = " select c.nombre, L.CLIENTE,
                    L.CARTERA,t.DESCRIPCION,L.CUENTA, 
                    U.NOMBRES ||' (' || L.USUARIO_GESTOR || ')' AS USUARIO_GESTOR,
                     L.AREA_DEVOLUCION,       
                     L.SALDO_ACTUAL, TO_CHAR(L.FECHA_INGRESO, 'DD/MM/YYYY') AS FECHA_INGRESO, L.CAPITAL_VENCIDO, L.INTERESES_MORA,L.MONTO_TOTAL, l.tipo_cuenta
                     ,L.USUARIO_GESTOR as GESTOR_CODIGO,
                     L.SITUACION_CUENTA
                    from sr_cuenta l, tg_cliente c, sr_tipo_credito t, SR_USUARIO U
                     where L.CLIENTE = C.CLIENTE and l.tipo_credito = t.tipo_credito
                     AND U.USUARIO = L.USUARIO_GESTOR
                     and l.persona = '$idPersona'
                     and l.cuenta = '$cuenta' ORDER BY L.AREA_DEVOLUCION DESC";
        }

           $st = $conex -> consulta($sql);

       $numrow = $conex->filas($st);

       if ($numrow > 0)
       {
           $st = $conex->consulta($sql);
           while ($fila = $conex->fetch_array($st)) 
           {
               $registros[] =  $fila;
           }

           return $registros;

       } else
       {
           return $resgistros = $e['not_tab4'];
       }
    }
   
}

/**
 *    
 *  Busca el idPersona del deudor de una cuenta en especifico
 * 
 *    PARAMETROS:
 *      -- $cuenta = la cuenta de la que se desea obteneder el identificador.
 * 
 * **/
function buscarIdPersona($cuenta, $tipo = 0)
{
    $id = 18;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        
    
        global $conex;
        $sql = "select persona from sr_cuenta where cuenta = '$cuenta'";
        $st = $conex -> consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0)
        {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) 
            {
                $id =  $fila[0];
            }

            $sql2 = "select id from tg_persona where persona = '$id'";

            $st2 = $conex -> consulta($sql2);
            $numrow2 = $conex->filas($st2);

        if ($numrow2 > 0)
        {
            $st2 = $conex->consulta($sql2);
            while ($fila = $conex->fetch_array($st2)) 
            {
                $cedula =  $fila[0];
            }
            
            if ($tipo == 0)
            return $cedula;
            else
                return $id;

        } else
        {
            echo $e['not_tab5'];
        }

    } else
        {
            echo "0";
        }   
    }
}




/**
 *    
 *  Carga las gestiones de una cuenta de la BD
 * 
 *    PARAMETROS:
 *      -- $cuenta = la cuenta de la que se desea obteneder los datos de las gestiones.
 * 
 **/
function cargarGestiones($cuenta)
{
    $id = 19;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
        global $conex;
        $sql =  "select  to_char(g.fecha_ingreso, 'DD/MM/YYYY HH24:MM:SS') as fecha_ingreso, ti.descripcion as tipo_gestion, g.telef_cod_area, g.telef_gestion, g.nombre_contacto, g.apellido_contacto, p.parentesco,  g.descripcion as observacion, to_char(g.fecha_proxima_gestion, 'DD/MM/YYYY') as fecha_proxima_gestion, g.hora_proxima_gestion, to_char(g.fecha_promesa, 'DD/MM/YYYY') as fecha_promesa, g.monto_promesa, g.gestion, U.NOMBRES|| ' ('||G.USUARIO_INGRESO ||')'  as asesor
        from SR_GESTION g, sr_cuenta c, sr_codigo_gestion ti, sr_parentesco p, sr_usuario u
        where G.CODIGO_GESTION = TI.CODIGO_GESTION 
        and G.CUENTA = C.CUENTA 
        and G.CLIENTE = C.CLIENTE
        AND G.CARTERA = C.CARTERA
        and G.GRUPO_GESTION = TI.GRUPO_GESTION
        and G.TABLA_GESTION = TI.TABLA_GESTION
        and P.ID_PAR(+) = G.ID_PARENTESCO
        and U.USUARIO = G.USUARIO_INGRESO
        and G.CUENTA = '$cuenta' and G.ELIMINAR = 'N' ORDER BY G.GESTION DESC";

        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0)
        {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) 
            {
                $gestiones[] =  $fila;
            }

            return $gestiones;
           // exit();

        } else
        {
            return $gestiones = $e['not_tab6'];
        }
    }
}


/**
 *    
 *  Carga los tipos de gestiones de la BD
 * 
 **/
function cargarTipoGestiones($cliente)
{
    $id = 20;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {

        global $conex;

        $sql = "select tabla_gestion from tg_cliente where cliente = '$cliente'";
        $st = $conex->consulta($sql);
        $fila = $conex->fetch_array($st);
        $tablaGestion = $fila[0];


            $sql = "select codigo_gestion, descripcion, tabla_gestion, grupo_gestion from sr_codigo_gestion  where status_codigo_gestion = 'A' and tabla_gestion = '$tablaGestion'  order by descripcion asc";

        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0)
        {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) 
            {
                $tipoGestiones[] =  $fila;
            }

            return $tipoGestiones;
            exit();

        } else
        {
            return $tipoGestiones = "ERROR: No existen Tipo de Gestiones";
        }
    }
}

/**
 *    
 *  Carga los parentescos de la BD
 * 
 **/
function cargarParentescos()
{
    $id = 21;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {

            global $conex;
        $sql = "select ID_PAR, PARENTESCO from SR_PARENTESCO";

        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0)
        {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) 
            {
                $parentesco[] =  $fila;
            }

            return $parentesco;
            exit();

        } else
        {
            return $parentesco = "ERROR: No existen Parentescos";
        }

    }
}


/**
 *    
 *  Carga los abonos de una cuenta de la BD
 * 
 *    PARAMETROS:
 *      -- $cuenta = la cuenta de la que se desea obteneder los datos de los abonos.
 * 
 **/
function cargarAbonos($cuenta)
{
    
    $id  = 22;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
        global $conex;
        $sql = "select a.abono, U.NOMBRES || ' (' || A.USUARIO_GESTOR||')'  AS USUARIO_GESTOR, to_char(a.fecha_deposito, 'DD/MM/YYYY') as fecha_deposito, a.monto_deposito, P.DESCRIPCION as forma_pago, to_char(a.fecha_ingreso, 'DD/MM/YYYY') as fecha_ingreso,
     B.DESCRIPCION AS BANCO, a.observacion, decode(a.status_abono,'C','CORFIMADO','NO CONFIRMADO') status_abono, a.nro_deposito, a.cuota,
     s.url
       from SR_ABONO a, tg_forma_pago p, TG_BANCO B, SR_USUARIO U, sr_soporte_abonos s
        where
        A.FORMA_PAGO = P.FORMA_PAGO(+)
        AND A.BANCO = B.BANCO(+)
        AND A.USUARIO_GESTOR = U.USUARIO
        and A.ABONO = S.ID_ABONO(+)
        and a.cuenta = '$cuenta' and a.eliminar = 'N' ORDER BY a.FECHA_INGRESO DESC";
        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0)
        {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) 
            {
                $abonos[] =  $fila;
            }

            return $abonos;
            exit();

        } else
        {
            return $abonos = $e['not_tab7'];
            exit();
        }

    }
}

/**
 *    
 *  Carga las formas de pago de la BD
 * 
 **/
function cargarFormaPago()
{
    $id = 23;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
    } else {
        global $conex;
        $sql = "select forma_pago, descripcion from tg_forma_pago";

        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $formasPago[] = $fila;
            }

            return $formasPago;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {
            return $parentesco = "ERROR: No existen forma de Pagos";
        }
    }
    
}

/**
 *    
 *  Carga los bancos de la BD
 * 
 **/
function cargarBancos()
{
    $id = 24;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        global $conex;
        $sql = "select banco, descripcion from tg_banco";

        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $bancos[] = $fila;
            }

            return $bancos;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {
            return $parentesco = "ERROR: No existen Bancos";
        }
   }
}


/**
 *    
 *  Carga las cuotas de una cuenta de la BD
 * 
 *    PARAMETROS:
 *      -- $cuenta = la cuenta de la que se desea obteneder los datos de las cuotas.
 * 
 **/
function cargarCuotas($cuenta)
{
    $id = 25;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
        global $conex;
        $sql = " select cuota, fecha_vencimiento, morosidad_dias, monto_capital, monto_interes, monto_interes_mora, monto_otros, decode(status,'P','PAGADO', 'PENDIENTE') as status, pago_abono, fecha_asignacion, fecha_ult_actualizacion
            from sr_cuota
            where cuenta = '$cuenta' ORDER BY cuota desc";


        $st = $conex->consulta($sql);
        $numrow = $conex->filas($st);

        if ($numrow > 0) {
            $st = $conex->consulta($sql);
            while ($fila = $conex->fetch_array($st)) {
                $cuotas[] = $fila;
            }

            return $cuotas;
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else {
            return $cuotas = $e['not_tab8'];
        }
    }
    
    
}



function cargarDuplicados($cedula = "NULL", $persona = "NULL"){
    
    
    if ($cedula != "NULL" and $persona == "NULL"){
    $sql = "SELECT ID AS CEDULA, F_QUITA_CARACTER_ESPECIAL(NOMBRE) AS NOMBRE, FECNAC FROM TG_PERSONA WHERE ID = '$cedula'";
    } else if ($cedula == "NULL" and $persona != "NULL"){
        $sql = "SELECT ID AS CEDULA, F_QUITA_CARACTER_ESPECIAL(NOMBRE) AS NOMBRE, FECNAC FROM TG_PERSONA WHERE ID = (SELECT ID FROM TG_PERSONA WHERE PERSONA = '$persona')";
    } else {
        $sql = "SELECT ID AS CEDULA, F_QUITA_CARACTER_ESPECIAL(NOMBRE) AS NOMBRE, FECNAC FROM TG_PERSONA WHERE ID = '$cedula'";
    }
    global $conex;
   // echo $sql;
    $st = $conex->consulta($sql);
    
    while ($fila = $conex->fetch_array($st)):
        
        $personas[] = $fila;
        
    endwhile;
    
    return (isset($personas)) ? $personas : false;
    
}


function validarCopia($cliente, $cartera, $asesor, $cuenta){
   /* $_SESSION['ult_gestion'] = array(
        
        'cliente' => '18',
        'cartera' => '507',
        'usuarioIngreso' => 'T100',
        'mPromesa' => '500',
        'cuenta' => '21144763'
        
        
    );*/
    //echo "123";
    
    if (isset($_SESSION['ult_gestion'])){
       
        for ($i=0; $i < count($_SESSION['ult_gestion']);$i++) {
            
        
        global $conex;
        
        $_SESSION['ult_gestion'][$i]['usuarioIngreso'] = ($asesor[0] != 'T' || $asesor[0] != 'C') ? $_SESSION['ult_gestion'][$i]['usuarioIngreso']:$asesor;
        
        $monto = (empty($_SESSION['ult_gestion'][$i]['mPromesa'])) ? 0:$_SESSION['ult_gestion'][$i]['mPromesa'];
        
        $sql = "SELECT 1, c.cartera FROM SR_CUENTA C WHERE C.CUENTA = '$cuenta' AND C.CLIENTE = '$cliente'
                AND C.SALDO_ACTUAL >= '".str_replace('.',',',$monto)."'";
        
        //echo $sql;
        //exit();
       // global $conex;
       // $asesor = $_SESSION['ult_gestion']['usuarioIngreso'];
       
        
        if ($cliente == $_SESSION['ult_gestion'][$i]['cliente'] 
           && $asesor == $_SESSION['ult_gestion'][$i]['usuarioIngreso']
           
           ) {
            
             
            $st = $conex->consulta($sql);
            
            if ($fila = $conex->fetch_array($st)){
                
                $resp = $fila[0];
                $_SESSION['ult_gestion'][$i]['cartera'] = $fila[1]; 
               
            }
            
             $seguir = (isset($resp)) ? 1:0;
            
             if ($seguir != "1"){
                  //echo $seguir;
                 return "0";
                 
             } 
             
             
        } else {
            return "0";
            
        }
        
        
       
    
        }
        
        return "1";
        
    } else {
        
        return "0";
        
    }
    
    
}


function guardarGestion($arr){
   // print_r($arr);
    global $e;
        // Se obtienen todos los valores pasados por el metodo GET
        $cuenta = trim($arr['Numcuenta']);
        $cartera = trim($arr['cartera']);
        $cliente = trim($arr['cliente']);
        $fechaGestion = trim($arr['fechaGestion']);
        $tipoGestion = trim($arr['tipoGestion']);
        $area = trim($arr['area']);
        $telefono = trim($arr['telefono']);
        $nombre = trim($arr['nombre']);
        $apellido = trim($arr['apellido']);
        $parentesco = trim($arr['parentesco']);
        $observaciones = trim($arr['observaciones']);
        $fProximaGestion = trim($arr['fProximaGestion']);
        $hProximaGestion = trim($arr['hProximaGestion']);
        $fPromesa = trim($arr['fPromesa']);
        $mPromesa = trim($arr['mPromesa']);
        $usuarioGestor = trim($arr['usuarioGestor']);
        $tablaGestion = trim($arr['tablaGestion']);
        $grupoGestion = trim($arr['grupoGestion']);
        $parentesco_texto = trim($arr['parentesco_texto']);
        
        
        
        
        
        if ($fProximaGestion == "") {
            $fProximaGestion = $fProximaGestion;
        } else {
            $fProximaGestion = str_replace('/', '-', $fProximaGestion);
            $fProximaGestion = date("d/m/Y",strtotime($fProximaGestion));
            // echo $fProximaGestion;
        }
        if ($fPromesa == ""){
            $fPromesa = $fPromesa;
        } else {
            $fPromesa = str_replace('/', '-', $fPromesa);
            $fPromesa = date("d/m/Y",strtotime($fPromesa));
        }
       
       $observaciones = eregi_replace("[\n]", ' ', $observaciones);
        //$fechaGestion = date("m/d/Y",strtotime($fechaGestion));
        //echo $fechaGestion;
       // exit();
        $usuarioIngreso = $_SESSION['USER'];
        $fechaGestion = date("d/m/Y H:i:s");
        global $conex;
        
        
                $sql = "Select cuenta from sr_cuenta where cliente = '$cliente' and cartera = '$cartera' and cuenta = '$cuenta' and area_devolucion is not null";
        
        $st = $conex->consulta($sql);
        
        while ($fila = $conex->fetch_array($st)){
            
            $filas[] = $fila;
            
        }
        
        if (isset($filas)){
            
            return "No se puede cargar una gestion en una cuenta que esta en area de devolucion";
            
        }
        
        
        $sql = "Select SQ_GESTION.nextval from dual";

        $st = $conex->consulta($sql);


        $value = $conex->fetch_array($st);
        $idGestion = $value[0];
        $nombre = ereg_replace("[^A-Za-z0-9]", " ", $nombre);
        $apellido = ereg_replace("[^A-Za-z0-9]", " ", $apellido);

      //  echo $usuarioIngreso;
        $mPromesa = str_replace(',', '.', $mPromesa);
       
        
        $sql2 = "insert into SR_GESTION (gestion, cliente, cartera, cuenta, usuario_gestor, tabla_gestion, grupo_gestion, codigo_gestion, descripcion, fecha_proxima_gestion, fecha_promesa, hora_promesa, monto_promesa, usuario_ingreso, fecha_ingreso, usuario_ult_mod, fecha_ult_gestion, telef_cod_area, telef_gestion, hora_proxima_gestion, nombre_contacto, status_mercantil, parentesco, apellido_contacto, abonos, eliminar, id_parentesco)
             values 
            ('$idGestion', '$cliente', '$cartera', '$cuenta', '$usuarioGestor', '$tablaGestion', '$grupoGestion', '$tipoGestion', '$observaciones', '$fProximaGestion', '$fPromesa', '', '$mPromesa', '$usuarioIngreso', SYSDATE, '$usuarioIngreso', '', '$area', '$telefono', '$hProximaGestion', '$nombre', '', '$parentesco_texto', '$apellido', '', 'N', '$parentesco')

            ";
       //
       /*
        $armar = array(
        'GESTION' => $idGestion,
        'DESCRIPCION' => $observaciones,
        'FECHA_PROXIMA_GESTION' => $fProximaGestion,
        'FECHA_PROMESA' => $fPromesa,
        'HORA_PROMESA' => '',
        'MONTO_PROMESA' => $mPromesa,
        'TELEF_COD_AREA' => $area,
        'TELEF_GESTION' => $telefono,
        'HORA_PROXIMA_GESTION' => $hProximaGestion,
        'NOMBRE_CONTACTO' => $nombre,
        'APELLIDO_CONTACTO' => $apellido,
        'ID_PARENTESCO' => $parentesco,
        'FECHA_ULT_MOD' => 'SYSDATE',
        'USUARIO_ULT_MOD' => $usuarioIngreso,
        'TABLA_GESTION' => $tablaGestion,
        'GRUPO_GESTION' => $grupoGestion,
        'CODIGO_GESTION' => $tipoGestion,
        'CUENTA' => $cuenta,
        'CLIENTE' => $cliente,
        'CARTERA' => $cartera,
        'USUARIO_INGRESO' => $usuarioIngreso,
        'USUARIO_GESTOR' => $usuarioGestor,
        'FECHA_INGRESO' => 'SYSDATE',
        'PARENTESCO' => $parentesco_texto,
        'FECHA_ULT_GESTION' => '',
        'STATUS_MERCANTIL' => '',
        'ABONOS' => '',
        'ELIMINAR' => 'N'   
                
        );
       */
        $respuesta = validacion($sql2, $tablaGestion, $grupoGestion, $tipoGestion);
        
        if ($respuesta == "" || $respuesta == 1){
    
            $_SESSION['ult_gestion'][] = array(
                
                'gestion'        => $idGestion,
                'cartera'        => $cartera,
                'cliente'        => $cliente,
                'cuenta'         => $cuenta,
                'usuarioGestor'  => $usuarioGestor,
                'tablagestion'   => $tablaGestion,
                'grupoGestion'   => $grupoGestion,
                'tipoGestion'    => $tipoGestion,
                'observaciones'  => $observaciones,
                'fProximaGestion'=> $fProximaGestion,
                'fPromesa'       => $fPromesa,
                'mPromesa'       => $mPromesa,
                'usuarioIngreso' => $usuarioIngreso,
                'fechaGestion'   => $fechaGestion,
                'area'           => $area,
                'telefono'       => $telefono,
                'hProximaGestion'=> $hProximaGestion,
                'nombre'         => $nombre,
                'apellido'       => $apellido,
                'parentesco'     => $parentesco,
                'parentesco_texto' => $parentesco_texto
                         
                
            );
            //$st = $conex->consulta($sql2);
            
            if (!empty($fProximaGestion)){
                
                $sql = "SELECT TIPO_CUENTA, PERSONA FROM SR_CUENTA WHERE CUENTA = '$cuenta'
                        AND CLIENTE = '$cliente'
                        AND CARTERA = '$cartera'
                        ";
                
                $st = $conex->consulta($sql);
                while ($fila = $conex->fetch_array($st)){
                    $tipo_cuenta = $fila[0];
                    $persona = $fila[1];
                }
                
                if (isset($tipo_cuenta, $persona)){
                    
                    $datos = array(
                
                'fecha' => $fProximaGestion,
                'usuario' => $usuarioGestor,
                'cliente' => $cliente,
                'cartera' => $cartera,
                'tipo_cuenta' => $tipo_cuenta,
                'cuenta' => $cuenta,
                'motivo' => $observaciones,
                'origen' => 1,
                'nro_gestion' => $idGestion,
                'persona' => $persona,
                'fecha_ingreso' => 'SYSDATE',
                'u_creacion' => $_SESSION['USER'],
                'fecha_promesa' => $fPromesa
               
            );
                    agendarCuenta($datos);
                    
                }
                
            }
            
            return "1";
            
           

        } else {
            
                
           
            return $respuesta;
            

        }
   
    
    
}



function copiarGestion($cliente, $cartera, $cuenta) {
    $gestiones = $_SESSION['ult_gestion'];
   for ($i=0; $i < count($gestiones);$i++) {
    $arr = array(
        
        'Numcuenta'  =>  $cuenta,
        'cartera'  =>  $cartera,
        'cliente'  =>  $cliente,
        'fechaGestion'  =>  $gestiones[$i]['fechaGestion'],
        'tipoGestion'  =>  $gestiones[$i]['tipoGestion'],
        'area'  =>  $gestiones[$i]['area'],
        'telefono'  =>  $gestiones[$i]['telefono'],
        'nombre'  =>  $gestiones[$i]['nombre'],
        'apellido'  =>  $gestiones[$i]['apellido'],
        'parentesco'  =>  $gestiones[$i]['parentesco'],
        'observaciones'  =>  $gestiones[$i]['observaciones'],
        'fProximaGestion'  =>  $gestiones[$i]['fProximaGestion'],
        'hProximaGestion'  =>  $gestiones[$i]['hProximaGestion'],
        'fPromesa'  =>  $gestiones[$i]['fPromesa'],
        'mPromesa'  =>  $gestiones[$i]['mPromesa'],
        'usuarioGestor'  => $gestiones[$i]['usuarioGestor'],
        'tablaGestion'  =>  $gestiones[$i]['tablagestion'],
        'grupoGestion'  =>  $gestiones[$i]['grupoGestion'],
        'parentesco_texto' => $gestiones[$i]['parentesco_texto']
        
    );
    
   // echo $arr['fProximaGestion'];
    $resp = guardarGestion($arr);
    
    if ($resp != "1"){
        unset($_SESSION['ult_gestion']);
        $_SESSION['ult_gestion'] = $gestiones;
        return $resp;
    } 
    
   }
    unset($_SESSION['ult_gestion']);
    $_SESSION['ult_gestion'] = $gestiones;
    return "1";
   
    
}


function agendarCuenta($datos){
    
    global $conex;
   /* $sql = "DELETE SR_AGENDA WHERE
            CUENTA = '".$datos['cuenta']."' 
            AND CLIENTE = '".$datos['cliente']."'
            AND CARTERA = '".$datos['cartera']."'
            AND TIPO_CUENTA = '".$datos['tipo_cuenta']."'
            ";*/
    
     $sql = "DELETE SR_AGENDA WHERE
            CUENTA = '".$datos['cuenta']."' 
            AND CLIENTE = '".$datos['cliente']."'
            AND CARTERA = '".$datos['cartera']."'
            ";
    
    $st = $conex->consulta($sql);
    
    $sql = "INSERT INTO SR_AGENDA
            (FECHA, USUARIO, CLIENTE,
            CARTERA, TIPO_CUENTA, CUENTA,
            MOTIVO, ORIGEN, NRO_GESTION,
            PERSONA, FECHA_INGRESO, U_CREACION,
            FECHA_PROMESA)
            VALUES
            ('".$datos['fecha']."','".$datos['usuario']."','".$datos['cliente']."',
            '".$datos['cartera']."','".$datos['tipo_cuenta']."','".$datos['cuenta']."',
            '".$datos['motivo']."','".$datos['origen']."','".$datos['nro_gestion']."',
            '".$datos['persona']."',SYSDATE,'".$datos['u_creacion']."',
            '".$datos['fecha_promesa']."')";
    $st = $conex->consulta($sql);
   
    
    
}


function modificarGestion($datos){
    
    
    $arrtipogestion = explode('.', $datos['tipogestion']);
    
    
    $sql = "UPDATE SR_GESTION SET
            FECHA_INGRESO = to_date('".$datos['fgestion']."', 'dd/mm/yyyy hh24:mi:ss'),
            DESCRIPCION = '".($datos['observaciones'])."',
            FECHA_PROXIMA_GESTION = '".$datos['fproximagestion']."',
            FECHA_PROMESA = '".$datos['fpromesa']."',
            MONTO_PROMESA = '".$datos['mpromesa']."',
            TELEF_COD_AREA = '".$datos['area']."',
            TELEF_GESTION = '".$datos['telefono']."',
            HORA_PROXIMA_GESTION = '".$datos['hproximagestion']."',
            NOMBRE_CONTACTO = '".$datos['nombre']."',
            APELLIDO_CONTACTO = '".$datos['apellido']."',
            ID_PARENTESCO = '".$datos['parentesco']."',
            FECHA_ULT_MOD = SYSDATE,
            USUARIO_ULT_MOD = '".$_SESSION['USER']."',
            TABLA_GESTION = '".$arrtipogestion[1]."',
            GRUPO_GESTION = '".$arrtipogestion[2]."',
            CODIGO_GESTION = '".trim($arrtipogestion[0])."'
            WHERE
            GESTION = '".$datos['gestion']."'
            ";
    
    $arreglo = array(
        
        'FECHA_INGRESO' => $datos['fgestion'],
        'DESCRIPCION' => $datos['observaciones'],
        'FECHA_PROXIMA_GESTION' => $datos['fproximagestion'],
        'FECHA_PROMESA' => $datos['fpromesa'],
        'MONTO_PROMESA' => $datos['mpromesa'],
        'TELEF_COD_AREA' => $datos['area'],
        'TELEF_GESTION' => $datos['telefono'],
        'HORA_PROXIMA_GESTION' => $datos['hproximagestion'],
        'NOMBRE_CONTACTO' => $datos['nombre'],
        'APELLIDO_CONTACTO' => $datos['apellido'],
        'ID_PARENTESCO' => $datos['parentesco'],
        'FECHA_ULT_MOD' => 'SYSDATE',
        'USUARIO_ULT_MOD' => trim($_SESSION['USER']),
        'TABLA_GESTION' => $arrtipogestion[1],
        'GRUPO_GESTION' => $arrtipogestion[2],
        'CODIGO_GESTION' => trim($arrtipogestion[0]),
        'CUENTA' => $datos['cuentaModif'],
        'CLIENTE' => $datos['clienteModif'],
        'CARTERA' => $datos['carteraModif'],
        'USUARIO_INGRESO' => $_SESSION['USER']
        
              
    );
    
    
    
    $respuesta = validacion($sql, $arrtipogestion[1], $arrtipogestion[2], trim($arrtipogestion[0]), $arreglo);
    
    global $conex;
    
    
   // echo 'respuesta: '.$respuesta;
    if ($respuesta == ""){
        
        $st = $conex->consulta($sql);
        echo "1";
    }else {
        
        echo $respuesta;
    }
    
    
    
    //echo $sql;
    
   return;
    
    
}


function modificarAbono($datos){
    
    
   // $arrtipogestion = explode('.', $datos['tipogestion']);
    
    
    $sql = "UPDATE SR_ABONO SET
            NUMERO_DEPOSITO = '".$datos['numdeposito']."'
            FECHA_DEPOSITO = '".$datos['fabono']."'
            MONTO_DEPOSITO = '".$datos['mabono']."'
            FORMA_PAGO = '".$datos['formapago']."'
            BANCO = '".$datos['banco']."'
            OBSERVACION = '".$datos['observacion']."'
            WHERE
            ABONO = '".$datos['abono']."'
            ";
    
    global $conex;
    
    //$st = $conex->consulta($sql);
    
   // echo $sql;
   echo "Modificacion realizada";
   return;
    
}


function validarDatos($cedula, $nacionalidad, $año, $mes, $dia, $nombre){
    
    
 if($nacionalidad == 'N'){$nacionalidad = 'V';}else{ return 0;}
    /*
    $postdata = http_build_query(
    array(
        'nacionalidad_aseg' => $nacionalidad,
        'cedula_aseg' => $cedula,
        'y' => (int) $año,
        'm' => (int) $mes,
        'd' => (int) $dia
    )
 
);*/
    
        $postdata = http_build_query(
    array(
        'nacionalidad_aseg' => trim($nacionalidad),
        'cedula_aseg' => trim((int) $cedula),
        'y' => trim((int) $año),
        'm' => trim((int) $mes),
        'd' => trim((int) $dia)
    )
 
);
 
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);
 
$context  = stream_context_create($opts);
 
$result = file_get_contents('http://www.ivss.gob.ve:28083/CuentaIndividualIntranet/CtaIndividual_PortalCTRL', false, $context);
 
$lstrNombre = substr($result,strpos($result,'Nombre y Apellido')+25,200); //Buscamos la etiqueta y usamos una tolerancia de 100 caracteres
$lstrNombre = substr($lstrNombre,strpos($lstrNombre,chr(13))+1); //Ubicamos el salto de linea y tomamos como el inicio del campo
$lstrNombre = substr($lstrNombre,1,strpos($lstrNombre,chr(13))-6); //Ubicamos el salto de linea y tomamos como el fin del campo
 
$lstrNombre = trim($lstrNombre); //Limpiamos los espacios en blanco

    if (strnatcasecmp($nombre, $lstrNombre) == 0){
        
        return 1;
        
    } else {
        
        return 0;
        
    }
 
    
    
    
}


function validarTelefono($persona, $area, $telefono){
    
    $sql = "SELECT * FROM TG_PERSONA_TEL_ADI WHERE PERSONA = '$persona' AND (COD_AREA like '%$area%' AND TELEFONO like '%$telefono%' or cod_area||telefono like '%$area.$telefono%')";
    global $conex;
    
    $st = $conex->consulta($sql);
    
    while ($fila = $conex->fetch_array($st)){
        
        $filas[] = $fila;
        
    }
    
    if (isset($filas)){
        
        return 1;
        
    } else {
        return 0;
    }
    
    
}

function guardarTelefono($persona, $area, $telefono, $usuario, $fecha){    
    $sql = "INSERT INTO TG_PERSONA_TEL_ADI D
            (PERSONA, COD_AREA, TELEFONO, FUENTE, STATUS_TELEFONO, USUARIO_INGRESO, FECHA_INGRESO, REFERENCIA)
            VALUES
            ('$persona','$area','$telefono','1','A','$usuario','$fecha', 'RECUPERACIONES')";
    global $conex;
    $st = $conex->consulta($sql);
    
    return 1;
    
}




    //==========================================================================
    //
    //                              GET
    //
    //==========================================================================

// Se verifica que se obtiene la variable $_GET
if (isset($_GET)) {
    
//echo "akiii";
    
    // Se verifica si se recibe una variable deudor por el metodo GET
    if (isset($_GET['deudor']))  //=============================================
    {
    
    $idPersona = $_GET['idPersona'];
    $persona = $_GET['persona'];
    
    
    // Llamamos a la funcion para cargar los datos del deudor
    $deudor = cargarDatosDeudor(trim($idPersona), trim($persona));
    
    echo $deudor;
    exit();
    
        // Se verifica si se recibe una variable cuenta por el metodo GET
    } else if (isset($_GET['cuenta'])) //=======================================
    {
            // Se verifica si se recibe una variable idPersona por el metodo GET
        if (isset($_GET['idPersona'])) {
            $idPersona = $_GET['idPersona'];
            $cuentas = cargarCuentasDeudor($idPersona);
            
            //Se verifica si es un arreglo la variable $cuentas
             if (is_array($cuentas))
        {
            
        
        
        echo '
                    <table style="font-size: 14px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="cuentas-Tabla" width="100%">
                        <thead>
                            
                            <tr class = "center">
                                <th onclick="if (checkTodos.checked == 1) { checkTodos.checked = 0; activarDesactivarChecks(document.getElementsByName(\'check2\'), document.getElementById(\'checkTodos\')); sumarMontos();  } else { checkTodos.checked = 1; activarDesactivarChecks(document.getElementsByName(\'check2\'), document.getElementById(\'checkTodos\')); sumarMontos();  }"><input hidden checked="checked" type="checkbox" id="checkTodos" name="checkTodos"/><b>Check</b></th>
                                <th><b>'.$e['tcnt_tab1'].'</b></th>
                                <th><b>'.$e['tcnt_tab2'].'</b></th>
                                <th><b>'.$e['tcnt_tab3'].'</b></th>
                                <th><b>'.$e['tcnt_tab4'].'</b></th>
                                <th><b>'.$e['tcnt_tab5'].'</b></th>
                             <!--   <th><b>'.$e['tcnt_tab8'].'</b></th>    -->
                                <th><b>'.$e['tcnt_tab6'].'</b></th>
                                <th><b>'.$e['tcnt_tab7'].'</b></th>
                                <th></th>
                                <th></th>
                         
                                

                                
                            </tr>
                        </thead>
                        
                        <tbody>';
        
        $i=0;
    
        foreach ($cuentas as $value)
        {
            
            $copia = validarCopia($value['CLIENTE'], $value['CARTERA'], $value['GESTOR_CODIGO'], $value['CUENTA']);
          //  echo $copia;
            $bloquear = ($copia == 1) ? "": "disabled";
            
            $num = str_replace(",", ".",$value['SALDO_ACTUAL']);
            $num = cambiarMoneda($num);
           // $num = number_format($num, 2, ",", ".");
            $i++;
            
            $checkeado = ($value['AREA_DEVOLUCION'] != '') ? '':'checked';
            $clase = 'gradeA';
            $nombreA = $value['USUARIO_GESTOR'];
            if ($value['AREA_DEVOLUCION'] != ''){
               $clase = 'area gradeA';
                if ($value['AREA_DEVOLUCION'] == '25'){
                    $clase = 'area25 gradeA';
                }
                
                $nombreA = "Area Devolucion ".$value['AREA_DEVOLUCION'];
                //
            }
         
            
            echo "

                            <tr id='".$value['CUENTA']."' class='".$clase."' style=\"cursor: pointer;\">
                                
                                <td align = 'center'><input id='".$i."' name=\"check2\" $checkeado type='checkbox' onclick=\"sumarMontos();\"/></td>
                                <td class='center' onclick='if ($(\"#AgregarActivo\").val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();'>".$value['NOMBRE']."</td>
                                <td class='center' onclick='if ($(\"#AgregarActivo\").val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();' id='cartera_".$i."'>".$value['CARTERA']."</td>
                                <td class='center' onclick='if ($(\"#AgregarActivo\").val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();'>".$value['DESCRIPCION']."</td>
                                <td class='center' onclick='if ($(\"#AgregarActivo\").val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();' id='cuenta_".$i."'>".$value['CUENTA']."</td>
                                <td class='center' onclick='if ($(\"#AgregarActivo\").val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();'>".$nombreA."</td>
                              <!--  <td class='center' onclick='cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML ); activarTabs();'>".$value['AREA_DEVOLUCION']."</td> -->
                                <td id='saldoActualT_".$i."' class='left' onclick='if ($(\'#AgregarActivo\').val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();'>".$_SESSION['simb_moneda'].' '.$num."</td>
                                <td class='center' onclick='if ($(\"#AgregarActivo\").val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();'>".$value['FECHA_INGRESO']."</td>
                                <td class='center' onclick='if ($(\"#AgregarActivo\").val() != 1) { cargarGestiones(document.getElementById(\"cuenta_".$i."\").innerHTML, document.getElementById(\"cliente_".$i."\").value, document.getElementById(\"cartera_".$i."\").innerHTML, document.getElementById(\"usuarioGestor_".$i."\").value,  document.getElementById(\"tipoCuenta_".$i."\").value, document.getElementById(\"saldoActualT_".$i."\").innerHTML); } activarTabs();'>".$value['SITUACION_CUENTA']."</td>    
                                <td align = 'center'><button type=\"button\" id=\"".$value['CLIENTE']."_".$value['CARTERA']."_".$value['CUENTA']."\" title=\"Copiar Gestión\" $bloquear onclick=\"this.disabled = true; copiarGestion(this.id, 'saldoActualT_".$i."');\" name=\"btn_Copiar\"><img width=\"20px\" heigth=\"20px\" src=\"img/ico/copy.ico\"/></button></td>
                            
                            <input id= 'usuarioGestor_".$i."' type='hidden' value= '".$value['GESTOR_CODIGO']."'>
                            <input id= \"cliente_".$i."\" type=\"hidden\" value='".$value['CLIENTE']."'>
                            <input id= \"tipoCuenta_".$i."\" type=\"hidden\" value='".$value['TIPO_CUENTA']."'>
                                
                            <label hidden id=\"TmontoVencido_".$i."\">".str_replace(",", ".", $value['CAPITAL_VENCIDO']), 2, '.', ''."</label>
                            <label hidden id='Tintereses_".$i."'>".number_format(str_replace(",", ".", $value['INTERESES_MORA']), 2, '.', '')."</label>
                            <label hidden id='TmontoTotal_".$i."'>".number_format(str_replace(",", ".",  $value['SALDO_ACTUAL']), 2, '.', '')."</label>
                                

                            </tr>
                            
                            
                         
             

                    ";
       
        }
        
         echo '</tbody>
                        
                </table>
                

                        
             <input id="carga" hidden type="text" />
               
            ';
        
   
        exit();
        
        } else
        {
        
            echo "<label class='alerta'>".$cuentas."</label>";
            
            
            echo '
 
                    <table style="font-size: 12px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="cuentas-Tabla" width="100%">
                        <thead>
                            
                            <tr>
                                <th align = \'center\'><b><input type=\'checkbox\' name=\'checkTodos\' /> Check</b></th>
                                <th><b>'.$e['tcnt_tab1'].'</b></th>
                                <th><b>'.$e['tcnt_tab2'].'</b></th>
                                <th><b>'.$e['tcnt_tab3'].'</b></th>
                                <th><b>'.$e['tcnt_tab4'].'</b></th>
                                <th><b>'.$e['tcnt_tab5'].'</b></th>
                                <th><b>'.$e['tcnt_tab8'].'</b></th>
                                <th><b>'.$e['tcnt_tab6'].'</b></th>
                                <th><b>'.$e['tcnt_tab7'].'</b></th>
                         
                                

                                
                            </tr>
                        </thead>
                        
                        <tbody>
                            
                            
                             <tr class="gradeA" id="1">

                                <td class="center"><input type="checkbox" name="check1" value="1"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td ondblclick="crearText(document.getElementById(\'cuentaText\'))"><label id="cuentaText">&nbsp</label></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align = \'center\'>&nbsp;</td>
                                

                            </tr>
                            
                            
                            <tr class="gradeA" id="1">

                               <td class="center"><input type="checkbox" name="check1" value="1"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align = \'center\'>&nbsp;</td>
                                

                            </tr>
                            
                            
                            <tr class="gradeA" id="1">

                                <td class="center"><input type="checkbox" name="check1" value="1"></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align = \'center\'>&nbsp;</td>
                                

                            </tr>
                            
                           
                            </tbody>
                        
                            </table>

	



                 ';
            
        }
            
            
          // Se verifica si se recive una variable consultaCuenta por el metodo GET
        } else if (isset($_GET['consultaCuenta'])) {
            $cuenta = $_GET['consultaCuenta'];
           
            $id = buscarIdPersona ($cuenta);
            
            echo $id;
            exit();
        }
        
         

           // Se verifica si se recive una variable gestiones por el metodo GET
    } else if (isset ($_GET['gestiones']))
    {
        $cuenta = $_GET['idCuenta'];
       // echo $cuenta;   
        $gestiones = cargarGestiones($cuenta);
       
        
        if (is_array($gestiones))
        {
    
    
    echo '


<table  style="font-size: 14px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" name="tablaGestiones" id="gestiones-Tabla" width="100%">
	<thead>
		<tr>
			<th onclick="capturarFlecha();">Check</th>
                        <th  class = "center">'.$e['tg_tab13'].'</th>
			<th  class = "center">'.$e['tg_tab1'].'</th>
			<th  class = "center">'.$e['tg_tab2'].'</th>
			<th  class = "center">'.$e['tg_tab3'].'</th>
			<th  class = "center">'.$e['tg_tab4'].'</th>
			<th  class = "center">'.$e['tg_tab5'].'</th>
                        <th  class = "center">'.$e['tg_tab6'].'</th>
                        <th  class = "center">'.$e['tg_tab7'].'</th>
                  
                        <th  class = "center">'.$e['tg_tab9'].'</th>
                        <th  class = "center">'.$e['tg_tab10'].'</th>
                        <th  class = "center">'.$e['tg_tab11'].'</th>
                        <th  class = "center">'.$e['tg_tab12'].'</th>
		</tr>
	</thead>
	<tbody>';
                 $i =0;          
                
       foreach ($gestiones as $value)
       {
           $i++;
          
	echo '	
		<tr id="'.$value['GESTION'].'"  class="gradeA" style="cursor: pointer;" onclick= "$(\'#observaciones\').val($(\'#textoObservaciones_'.$i.'\').val());">

                                             <td><input id="check1" type="checkbox" name="check1" value="'.$value['GESTION'].'"></td>
                                             <td class="center">'.$value['ASESOR'].'</td>
                                             <td class="center">'.trim($value['FECHA_INGRESO']).'</td>
                                             <td class="center">'.$value['TIPO_GESTION'].'</td>
                                             <td class="center"><div>'.$value['TELEF_COD_AREA'].'</div></td>
                                             <td class="center">'.$value['TELEF_GESTION'].'</td>
                                             <td class="center">'.$value['NOMBRE_CONTACTO'].'</td>
                                             <td class="center">'.$value['APELLIDO_CONTACTO'].'</td>
                                             <td class="center">'.$value['PARENTESCO'].'</td>
                                          <!--   <td><div id="textoObservaciones_'.$i.'" style="height:25px; overflow: hidden; width: 100px">'.$value['OBSERVACION'].'</div></td> -->
                                             
                                             <td class="center">'.$value['FECHA_PROXIMA_GESTION'].'</td>
                                             <td class="center">'.$value['HORA_PROXIMA_GESTION'].'</td>
                                             <td class="center">'.$value['FECHA_PROMESA'].'</td>
                                             <td class="center">'.$value['MONTO_PROMESA'].'</td>
                                        
                                             
                                             <input id="textoObservaciones_'.$i.'" type="hidden" value="'.htmlentities($value['OBSERVACION']).'">
                                         </tr> 
                         
	';
       }
       echo '
           
        

            </tbody>
            

            	</table>
	
            
     

		


    
         ';
       
        } else
        {
            
            echo "<label class = 'alerta'>".$gestiones."</label>";
            
            
            echo '
            
                                               <table style="font-size: 12px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" name="tablaGestiones" id="gestiones-Tabla" width="100%">
	<thead>
		<tr>
			<!--<th  class = "center">Check</th>-->
                        <th  class = "center">'.$e['tg_tab13'].'</th>
			<th  class = "center">'.$e['tg_tab1'].'</th>
			<th  class = "center">'.$e['tg_tab2'].'</th>
			<th  class = "center">'.$e['tg_tab3'].'</th>
			<th  class = "center">'.$e['tg_tab4'].'</th>
			<th  class = "center">'.$e['tg_tab5'].'</th>
                        <th  class = "center">'.$e['tg_tab6'].'</th>
                        <th  class = "center">'.$e['tg_tab7'].'</th>
                  
                        <th  class = "center">'.$e['tg_tab9'].'</th>
                        <th  class = "center">'.$e['tg_tab10'].'</th>
                        <th  class = "center">'.$e['tg_tab11'].'</th>
                        <th  class = "center">'.$e['tg_tab12'].'</th>
		</tr>
	</thead>
	<tbody>
		<tr class="gradeA" style="cursor: pointer;" onclick= "">

                                            <!-- <td class="center"><input type="checkbox" name="check1" value="1"></td> -->
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                        
                                             
                                            
                                         </tr> 
	<tr class="gradeA" style="cursor: pointer;" onclick= "">

                                             <!-- <td class="center"><input type="checkbox" name="check1" value="1"></td> -->
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                        
                                             
                                            
                                         </tr> 

       <tr class="gradeA" style="cursor: pointer;" onclick= "">

                                             <!-- <td class="center"><input type="checkbox" name="check1" value="1"></td> -->
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                        
                                             
                                            
                                         </tr> 
<tr class="gradeA" style="cursor: pointer;" onclick= "">

                                             <!-- <td class="center"><input type="checkbox" name="check1" value="1"></td> -->
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                        
                                             
                                            
                                         </tr> 
<tr class="gradeA" style="cursor: pointer;" onclick= "">

                                              <!-- <td class="center"><input type="checkbox" name="check1" value="1"></td> -->
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                             <td class="center">&nbsp;</td>
                                        
                                             
                                            
                                         </tr> 

	
            </tbody>
            

            	</table>
		



                    ';
            
            
        }
       
      
            // Se verifica si se recive una variable tipoGestiones por el metodo GET
    } else if (isset ($_GET['tipoGestiones']))
    {
        $cliente = $_GET['cliente'];
        $tipoGestiones = cargarTipoGestiones($cliente);
        $parentescos = cargarParentescos();
        
        if (is_array($tipoGestiones))
        {
            foreach ($tipoGestiones as $value)
            {
                $tipo[] = $value['CODIGO_GESTION'].".".$value['TABLA_GESTION'].".".$value['GRUPO_GESTION'].",".$value['DESCRIPCION'];
                
            }
            
           // print_r($tipo);
            $tipo = implode(";", $tipo);
           
            if (is_array($parentescos))
            {
                
                foreach ($parentescos as $fil)
                {
                    $paren[] = $fil['ID_PAR'].",".$fil['PARENTESCO'];
                }
                //Se convierte el arreglo en una cadena separada por el indicador
                $paren = implode(";", $paren);
                
            } else
            {
                $paren = $parentescos;
            }
            
            // Se concatenan las dos cadenas
            echo $tipo."*".$paren;
          
            
        } else
        {
            echo $tipoGestiones;
        }
        
        
       // Se verifica si se recibe una variable guardarGestion por el metodo GET
    } else if (isset($_GET['guardarGestion']))
    {
        //print_r($_GET);
      //  exit();
        $respuesta = guardarGestion($_GET);
        
        echo $respuesta;
        
        
  /*
        // Se obtienen todos los valores pasados por el metodo GET
        $cuenta = $_GET['Numcuenta'];
        $cartera = $_GET['cartera'];
        $cliente = $_GET['cliente'];
        $fechaGestion = $_GET['fechaGestion'];
        $tipoGestion = $_GET['tipoGestion'];
        $area = $_GET['area'];
        $telefono = $_GET['telefono'];
        $nombre = $_GET['nombre'];
        $apellido = $_GET['apellido'];
        $parentesco = $_GET['parentesco'];
        $observaciones = $_GET['observaciones'];
        $fProximaGestion = $_GET['fProximaGestion'];
        $hProximaGestion = $_GET['hProximaGestion'];
        $fPromesa = $_GET['fPromesa'];
        $mPromesa = $_GET['mPromesa'];
        $usuarioGestor = $_GET['usuarioGestor'];
        $tablaGestion = $_GET['tablaGestion'];
        $grupoGestion = $_GET['grupoGestion']; 
        
        $fProximaGestion = date("d/m/Y",strtotime($fProximaGestion));
        $fPromesa = date("d/m/Y",strtotime($fPromesa));
        //$fechaGestion = date("m/d/Y",strtotime($fechaGestion));
        //echo $fechaGestion;
       // exit();
        $usuarioIngreso = $_SESSION['USER'];
        
        global $conex;
        $sql = "Select SQ_GESTION.nextval from dual";

        $st = $conex->consulta($sql);


        $value = $conex->fetch_array($st);
        $idGestion = $value[0];
        
        $sql2 = "insert into SR_GESTION (gestion, cliente, cartera, cuenta, usuario_gestor, tabla_gestion, grupo_gestion, codigo_gestion, descripcion, fecha_proxima_gestion, fecha_promesa, hora_promesa, monto_promesa, usuario_ingreso, fecha_ingreso, usuario_ult_mod, fecha_ult_gestion, telef_cod_area, telef_gestion, hora_proxima_gestion, nombre_contacto, status_mercantil, parentesco, apellido_contacto, abonos, eliminar, id_parentesco)
                
            values 
            ('$idGestion', '$cliente', '$cartera', '$cuenta', '$usuarioGestor', '$tablaGestion', '$grupoGestion', '$tipoGestion', '$observaciones', '$fProximaGestion', '$fPromesa', '', '$mPromesa', '$usuarioIngreso', '$fechaGestion', '$usuarioIngreso', '', '$area', '$telefono', '$hProximaGestion', '$nombre', '', '', '$apellido', '', 'N', '$parentesco')

            ";
       //
 
       
        $respuesta = validacion($sql2, $tablaGestion, $grupoGestion, $tipoGestion);
        if ($respuesta == "" || $respuesta == 1){
    
            echo $e['not_tab9'];
            
            exit();

        } else {
            $_SESSION['ult_gestion'] = array(
                
                'gestion'        => $idGestion,
                'cartera'        => $cartera,
                'cliente'        => $cliente,
                'cuenta'         => $usuarioGestor,
                'tablagestion'   => $tablaGestion,
                'grupoGestion'   => $grupoGestion,
                'tipoGestion'    => $tipoGestion,
                'observaciones'  => $observaciones,
                'fProximaGestion'=> $fProximaGestion,
                'fPromesa'       => $fPromesa,
                'mPromesa'       => $mPromesa,
                'usuarioIngreso' => $usuarioIngreso,
                'fechaGestion'   => $fechaGestion,
                'area'           => $area,
                'telefono'       => $telefono,
                'hProximaGestion'=> $hProximaGestion,
                'nombre'         => $nombre,
                'apellido'       => $apellido,
                'parentesco'     => $parentesco
                
                
                
            );
            echo $respuesta;
            exit();

        }
   */
        
        // Se verifica si se recibe una variable abonos por el metodo GET
    } else if (isset($_GET['abonos']))
    {
        
        $cuenta = $_GET['cuentaActual'];
        
        $abonos = cargarAbonos($cuenta);
        
        if (is_array($abonos))
        {
            // Imprimimos la tabla de abonos
         
            echo '
            
                                           <table style="font-size: 14px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="abonos-Tabla" width="100%">
	<thead>
		<tr>
			<th>Check</th>
			<th>'.$e['ta_tab1'].'</th>
			<th>'.$e['ta_tab2'].'</th>
			<th>'.$e['ta_tab3'].'</th>
			<th>'.$e['ta_tab4'].'</th>
                        <th>'.$e['ta_tab9'].'</th>
                        <th>'.$e['ta_tab5'].'</th>
                        <th>'.$e['ta_tab6'].'</th>
                        <th>'.$e['ta_tab7'].'</th>
                        <th>'.$e['ta_tab8'].'</th>
                        <th>'.$e['ta_tab10'].'</th>
                        <th>Soporte</th>
                   
		</tr>
	</thead>
	<tbody>
        
                   ';
             $i =0;                         
       foreach ($abonos as $value)
       {
           $i++;
           
              if ($value['URL'] == ""){
                
                $url = "";
                $txt_url = "Adjuntar";
                $accion = "$('#archivo').click(); $('#btn_upload').removeAttr('hidden'); $('#archivo').removeAttr('hidden');";
             //   <input class="botonEditar" onclick="cargarSoporte();" name="enviar" type="button" value="Upload File" />
                
                
            } else {
                
                 $url = $value['URL'];
                $txt_url = "Soporte";
                $accion = "";
             //   <input class="botonEditar" onclick="cargarSoporte();" name="enviar" type="button" value="Upload File" />
                
            }
           
           echo '
      
                    	<tr class="gradeA" id="'.$value['ABONO'].'" onclick= "$(\'#observaciones\').val($(\'#observacionAbono_'.$i.'\').val());">
		<td class="center"><input type="checkbox" onclick="activarBntEliminar(document.getElementsByName(\'check-abonos\'))" name="check-abonos" id="check-abonos" value="'.$value['ABONO'].'"></td>
                        <td class="center">'.$value['ABONO'].'</td>
			<td class="center">'.$value['USUARIO_GESTOR'].'</td>
                        <td class="center">'.$value['FECHA_DEPOSITO'].'</td>
			<td class="center">'.$value['MONTO_DEPOSITO'].'</td>
                        <td class="center">'.$value['NRO_DEPOSITO'].'</td>
                        <td class="center">'.$value['CUOTA'].'</td>
			<td class="center">'.$value['FORMA_PAGO'].'</td>
			<td class="center">'.$value['FECHA_INGRESO'].'</td>
			<td class="center">'.$value['BANCO'].'</td>
			<td class="center">'.$value['STATUS_ABONO'].'</td>
                        <td class="center"><a target="_blank" onclick="'.$accion.'" href="http://'.$url.'">'.$txt_url.'</a></td>
			
			
                        <input id="observacionAbono_'.$i.'" name="observacionAbono_'.$i.'" type="hidden" value="'.$value['OBSERVACION'].'" />
		</tr>
      
                 ';
           
           
       }
            
       echo '
                    </tbody>
                    </table>
                    


			

               ';
       
       exit();
            
            
        } else
        {
            // Mostramos alerta
            echo '<label class="alerta">'.$abonos.'</label><br>
                   
                        <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="abonos-Tabla" width="100%">
	<thead>
		<tr>
			<th>Check</th>
			<th>'.$e['ta_tab1'].'</th>
			<th>'.$e['ta_tab2'].'</th>
			<th>'.$e['ta_tab3'].'</th>
			<th>'.$e['ta_tab4'].'</th>
                        <th>'.$e['ta_tab9'].'</th>
                        <th>'.$e['ta_tab5'].'</th>
                        <th>'.$e['ta_tab6'].'</th>
                        <th>'.$e['ta_tab7'].'</th>
                        <th>'.$e['ta_tab8'].'</th>
                        <th>'.$e['ta_tab10'].'</th>
                        <th>Soporte</th>
                   
		</tr>
	</thead>
	<tbody>
		<tr class="gradeA" id="1">
			<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			
             
		</tr>
		<tr class="gradeA" id="2">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
                        
               
		</tr>
                

                	<tr class="gradeA" id="3">
			<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
                        
                   
			
		</tr>
		<tr class="gradeA" id="4">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
                        
              
		</tr>
	
                	<tr class="gradeA" id="5">
		<td class="center"><input type="checkbox" name="check1" value="1"></td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td class="center">&nbsp;</td>
			<td class="center">&nbsp;</td>
                        <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
                        <td>&nbsp;</td>
                        
                  
		</tr>

	
            </tbody>
			</table>

			



                    ';
            
        }
        
       // Se verifica si se recibe una variable guardarAbonos por el metodo GET
    } else if (isset($_GET['guardarAbonos'])) //================================
    {
        // Se capturan las variables enviadas por el metodo GET
        $cuenta = $_GET['numCuenta'];
        $cliente = $_GET['cliente'];
        $cartera = $_GET['cartera'];
        $tipoCuenta = $_GET['tipoCuenta'];
        $usuarioGestor = $_GET['usuarioGestor'];
        $numeroDeposito = $_GET['numeroDeposito'];
        $fechaDeposito = $_GET['fechaDeposito'];
        $montoDeposito = $_GET['montoDeposito'];
        $formaPago = $_GET['formaPago'];
        $banco = $_GET['banco'];
        $statusAbono = $_GET['statusAbono'];
        $observacion = $_GET['observacion'];
        $fechaIngreso = $_GET['fechaIngreso'];
        $usuarioIngreso = $_SESSION['USER'];
        $cuota = $_GET['cuota'];
       
        
        if ($fechaDeposito == ""){
            
            echo "Debe ingresar la fecha del abono.";
            exit();
            
        }
        
        if ($numeroDeposito == ""){
            echo "Debe Ingresar el numero de deposito";
            exit();
            
        }
        
        if ($montoDeposito == ""){
            echo "Debe Ingresar el monto del deposito";
            exit();
            
        }
        
        if ($cuota == ""){
            echo "Debe ingresar la cuota.";
            exit();
            
            
        }
        
        if ($observacion == ""){
            echo "Debe ingresar una observacion.";
            exit();
            
            
        }
        
        if ($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C'){
           if ($usuarioGestor != $usuarioIngreso){
            echo "Solo debe ingresar abonos a las cuentas que le fueron asignadas";
            exit();
        }
        }
        
        
        
        $fechaDeposito = date("d/m/Y",strtotime($fechaDeposito));
                
                
        
        
        global $conex;
        
        
        $sql = "Select cuenta from sr_cuenta where cliente = '$cliente' and cartera = '$cartera' and cuenta = '$cuenta' and area_devolucion is not null";
        
        $st = $conex->consulta($sql);
        
        while ($fila = $conex->fetch_array($st)){
            
            $filas[] = $fila;
            
        }
        
        if (isset($filas)){
            
            echo "No se puede cargar un abono en una cuenta que esta en area de devolucion";
            exit();
        }
        
        
        
        $sql = "Select SQ_ABONO.nextval from dual";
        
      

        $st = $conex->consulta($sql);
        
        
        $value = $conex->fetch_array($st);
        $idAbono = $value[0];
        
        $idAbono = substr($idAbono, 0, 7);
        
        $sql = "select A.USUARIO_SUPERIOR as coordinador,K.USUARIO_SUPERIOR as gerente, A.GRUPO_GESTOR 
                from sr_usuario k, sr_usuario a 
                where K.USUARIO = A.USUARIO_SUPERIOR and  A.USUARIO = '$usuarioGestor'
                ";
        
        $st = $conex->consulta($sql);
        
        
        $value = $conex->fetch_array($st);
        $usuarioSupervisor = $value[0];
        $usuarioGerente = $value[1];
        $grupoGestor = $value[2];
        
        $montoDeposito = str_replace('.',',',$montoDeposito);
         
        $sql2 = "
              insert into SR_ABONO
              (abono, cliente, cartera, cuenta, tipo_cuenta, usuario_gestor, usuario_supervisor, usuario_gerente, nro_deposito, fecha_deposito, monto_deposito, forma_pago, banco, status_abono, origen_pago, fecha_pago_comi, abono_rever, flag_exoneracion, comi_vpc, comi_gestor, motivo_rever, observacion, pago_int_sa, pago_gastos_sa, otros_gastos_sa, isv_sa, usuario_ingreso, fecha_ingreso, usuario_ult_mod, fecha_ult_mod, grupo_gestor, relacion, traza, cuota, tipo_cuenta_anterior, fecha_mod_tipo_cuenta, status_pagado, fecha_pago, monto_deposito_or, comi_vpc_or, comi_gestor_or, pago_int_sa_or, pago_gastos_sa_or, otros_gastos_sa_or, isv_sa_or, observacion_ingreso, producto, eliminar)
              values ('R$idAbono', '$cliente', '$cartera', '$cuenta', '$tipoCuenta', '$usuarioGestor', '$usuarioSupervisor', '$usuarioGerente', '$numeroDeposito', '$fechaDeposito', '$montoDeposito', '$formaPago', '$banco', '$statusAbono', 'E', '', '', '', '', '', '', '$observacion', '', '', '', '', '$usuarioIngreso', '$fechaIngreso', '', '', '$grupoGestor', '', '', '$cuota', '', '', '', '', '', '', '', '', '', '', '', '', '', 'N')

            ";
       // echo $cuenta;
        $st = $conex->consulta($sql2);

        
        echo "1;".$idAbono;
        exit();
        
     // Se verifica si se recibe una variable selectAbonos por el metodo GET
    } else if (isset($_GET['selectAbonos'])) //=================================
    {
         $formaPago = cargarFormaPago();
         $arrBancos = cargarBancos();
         
          if (is_array($formaPago))
        {
            foreach ($formaPago as $value)
            {
                $forma[] = $value['FORMA_PAGO'].",".$value['DESCRIPCION'];
                
            }
            
          
            $formasPago = implode(";", $forma);
           
            if (is_array($arrBancos))
            {
                
                foreach ($arrBancos as $fil)
                {
                    $banco[] = $fil['BANCO'].",".$fil['DESCRIPCION'];
                }
                
                $bancos = implode(";", $banco);
                
            } else
            {
                $bancos = $arrBancos;
            }
            
            
            echo $formasPago."*".$bancos;
        }
        exit();
         
         
         // Se verifica si se recibe una variable eliminarAbonos por el metodo GET
    } else if (isset($_GET['eliminarAbonos'])) //===============================
    {
        //echo "akiii";
        $idAbonos = $_GET['idAbonos'];
        $arrIdAbonos = explode(",", $idAbonos);
        
        if ($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C'){
            
            echo "Accion no permitida.";
            exit();
            
        } else {
            
            foreach ($arrIdAbonos as $value)
        {
            $sql = "update SR_ABONO set ELIMINAR = 'S' where ABONO = '$value'";
            $st = $conex -> consulta($sql);
        }
        
        echo "1";
        exit();
            
        }
        
        
       
        
        
        
        // Se verifica si se recibe una variable eliminarGestiones por el metodo GET
    } else if (isset($_GET['eliminarGestiones'])) //============================
    {
        $idGestiones = $_GET['idGestiones'];
        
        $arrIdGestiones = explode(",", $idGestiones);
         if ($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C'){
             //echo "Accion no permitida";
                 foreach ($arrIdGestiones as $value)
                    {
             $sql = "SELECT * FROM SR_GESTION WHERE GESTION = '$value' AND USUARIO_GESTOR = '".$_SESSION['USER']."' AND TRUNC(FECHA_INGRESO) = TRUNC(SYSDATE)";
             
             $st = $conex->consulta($sql);
             
             while ($fila = $conex->fetch_array($st)){
                 $filas[] = $fila;
             }
             
             if (isset($filas)){
                 
              
                        $sql2 = "update SR_GESTION set ELIMINAR = 'S' where GESTION = '$value'";
                        $st2 = $conex -> consulta($sql2);
                    }
                    unset($sql);
                    unset($st);
                    
                 
             }
             echo "1";
                    exit();
             
            
         } else {
             
             foreach ($arrIdGestiones as $value)
        {
            $sql = "update SR_GESTION set ELIMINAR = 'S' where GESTION = '$value'";
            $st = $conex -> consulta($sql);
        }
        
        echo "1";
        exit();
             
         }
         
        
        
        // Se verifica si se recibe una variable cuotas por el metodo GET
    } else if (isset($_GET['cuotas'])) //=======================================
    {
        
        $cuenta = $_GET['cuentaActual'];
        $cuotas = cargarCuotas($cuenta);
        
        if (is_array($cuotas))
        {
            //Imprimimos la tabla cuotas
            echo '

                           <table style="font-size: 14px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="cuotas-Tabla" width="100%">
                                            <thead>
                                                    <tr>
                                                            <th>Checks</th>
                                                            <th>'.$e['cut_tab1'].'</th>
                                                            <th>'.$e['cut_tab2'].'</th>
                                                            <th>'.$e['cut_tab3'].'</th>
                                                            <th>'.$e['cut_tab4'].'</th>
                                                            <th>'.$e['cut_tab5'].'</th>
                                                            <th>'.$e['cut_tab6'].'</th>
                                                            <th>'.$e['cut_tab7'].'</th>
                                                          <!--  <th>'.$e['cut_tab8'].'</th> -->
                                                            <th>'.$e['cut_tab8'].'</th>
                                                            <th>'.$e['cut_tab10'].'</th>
                                                            <th>'.$e['cut_tab11'].'</th>
                                                            <th>'.$e['cut_tab12'].'</th>
                                                            <th>Status</th>    


                                                    </tr>
                                            </thead>
                                            <tbody>


                 ';
            
            foreach ($cuotas as $value)
            {
                $saldoTotal = str_replace(',','.',$value['MONTO_CAPITAL'])+str_replace(',','.',$value['MONTO_INTERES'])+str_replace(',','.',$value['MONTO_INTERES_MORA'])+str_replace(',','.',$value['MONTO_OTROS']);
                
                echo '
                        
                                 <tr class="gradeA">

                                    <td class="center"><input type="checkbox" name="check-cuotas" value="'.$value['CUOTA'].'"></td>
                                    <td>'.$value['CUOTA'].'</td>
                                    <td>'.$value['FECHA_VENCIMIENTO'].'</td>
                                    <td>'.$value['MOROSIDAD_DIAS'].'</td>
                                    <td>'.$_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$value['MONTO_CAPITAL'])).'</td>
                                    <td>'.$_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$value['MONTO_INTERES'])).'</td>
                                    <td>'.$_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$value['MONTO_INTERES_MORA'])).'</td>
                                    <td>'.$_SESSION['simb_moneda'].' '.cambiarMoneda(str_replace(',','.',$value['MONTO_OTROS'])).'</td>
                                <!--    <td>PREGUNTAR</td> -->
                                    <td class="saldoTotal_cuotas">'.$_SESSION['simb_moneda'].' '.cambiarMoneda($saldoTotal).'</td>
                                    <td>'.$value['PAGO_ABONO'].'</td>
                                    <td>'.$value['FECHA_ASIGNACION'].'</td>
                                    <td>'.$value['FECHA_ULT_ACTUALIZACION'].'</td>
                                    <td>'.$value['STATUS'].'</td>

                                 </tr>
                
                     ';
                
                
            }
            
                echo '                </tbody>
                                    </table>

                                   ';
                exit();
                
        } else
        {
            
            echo "<label class='alerta'>".$cuotas."</label>";
            
            echo '<table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="cuotas-Tabla" width="100%">
                                            <thead>
                                                    <tr>
                                                            <th>'.$e['cut_tab1'].'</th>
                                                            <th>'.$e['cut_tab2'].'</th>
                                                            <th>'.$e['cut_tab3'].'</th>
                                                            <th>'.$e['cut_tab4'].'</th>
                                                            <th>'.$e['cut_tab5'].'</th>
                                                            <th>'.$e['cut_tab6'].'</th>
                                                            <th>'.$e['cut_tab7'].'</th>
                                                      <!--      <th>'.$e['cut_tab8'].'</th> -->
                                                            <th>'.$e['cut_tab9'].'</th>
                                                            <th>'.$e['cut_tab10'].'</th>
                                                            <th>'.$e['cut_tab11'].'</th>
                                                            <th>'.$e['cut_tab12'].'</th>


                                                    </tr>
                                            </thead>
                                            <tbody>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                               <!--     <td>&nbsp;</td> -->
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                          <!--     <td>&nbsp;</td> -->
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>


                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                 <!--     <td>&nbsp;</td> -->
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>

                                            <tr class="gradeA" id="5">


                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                  <!--     <td>&nbsp;</td> -->
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>




                                            </tr>

                                            <tr class="gradeA" id="5">

                                                    <td class="center"></td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                <!--     <td>&nbsp;</td> -->
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>



                                            </tr>


                                    </tbody>
                                    </table>

                                    <table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
                                            <tfoot id="piecuotas-Tabla">
                                                    <tr>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                       <!--     <th id="selectz"></th> -->
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>
                                                            <th id="selectz"></th>

                                                    </tr>
                                            </tfoot>
                                    </table>';
            
            
            exit();
            
        }
        
    } else if (isset($_GET['cargarDuplicados'])){
       // print_r($_GET);
        if ($_GET['cedula'] == "NULL"){
            $personas = cargarDuplicados("NULL", trim($_GET['persona']));
            
        } else if ($_GET['cedula'] != "NULL"){
            $personas = cargarDuplicados(trim($_GET['cedula']), "NULL");
          ///  echo "por ceedula";
        }
        //$personas = cargarDuplicados($_GET['cedula']);
        
        echo (!$personas) ? false : json_encode($personas);
        
        
    } else if (isset ($_GET['copiarGestion'])){
        
        $arr = explode('_', $_GET['id']);
        
        
        $resp = copiarGestion($arr[0], $arr[1], $arr[2]);
        
        echo $resp;
        
    } else if (isset($_GET['validarCopia'])){
        
        
        $arr1 = explode(',', $_GET['id']);
        $salida = '';
        foreach ($arr1 as $value){
         
        $arr = explode('_', $value);
        
        $resp = validarCopia($arr[0], $arr[1], $_SESSION['USER'], $arr[2]);
       // echo "esto es lo a";
        if ($resp == "1"){
            
            $salida[] = $value;
            
        }
    }
        echo implode(',', $salida);
        
    } else if (isset($_GET['modificarGestion'])){
        
        
        
    
        
        if ($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C'){
            
           /* $sql = "SELECT 1 FROM SR_GESTION WHERE USUARIO_GESTOR = '".$_SESSION['USER']."' AND TRUNC(FECHA_INGRESO) = TRUNC(SYSDATE) and gestion = '".$_GET['gestion']."'";
            
            $st = $conex->consulta($sql);
            
            while ($fila = $conex->fetch_array($st)){
                $filas[] = $fila;
            }
            
            if (isset($filas)){
                  $respuesta = modificarGestion($_GET);
                  echo $respuesta;
                  exit();
            }*/
            
            echo "0";
            exit();
        } else {
        
        $respuesta = modificarGestion($_GET);
        
        echo $respuesta;
        exit();
        }
    } else if (isset($_GET['modificarAbono'])){
        
         if ($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C'){
         
            echo "0";
            exit();
        } else {
        
        $respuesta = modificarAbono($_GET);
        
        echo $respuesta;
        exit();
        }
        
    } else if (isset ($_POST['validarDatos'])){
        
        
        $valida = validarDatos($_POST['cedula'], $_POST['nacionalidad'], $_POST['año'], $_POST['mes'], $_POST['dia'], $_POST['nombre']);
        
        if ($valida){
            
            echo "1";
            
        } else {
            
            echo "0";
        }
        
        
        
        
    } else if (isset($_GET['validarTelefono'])){
        
        $telefono = $_GET['telefono'];
        $area = $_GET['area'];
        $persona = $_GET['persona'];
        
        $resp = validarTelefono($persona, $area, $telefono);
        
        if ($resp == 1){
            
            echo "1";
            
        } else {
            
            echo "0";
            
        }
        
        
    } else if (isset($_GET['guardarTelefono'])){
        
        $telefono = $_GET['telefono'];
        $area = $_GET['area'];
        $persona = $_GET['persona'];
        $usuario = $_SESSION['USER'];
        $fecha = date('d/m/Y');
        
        
        $resp = guardarTelefono($persona, $area, $telefono, $usuario, $fecha);
        
        if ($resp == 1){
            
            echo "1";
            
        } else {
            
            echo "0";
            
        }
        
        
    }
    
    
    
}



function validacion($sql, $tablaGestion, $grupoGestion, $codigoGestion, $armar = 0){
      $id = 26;
      
     
      if (!autorizar($id, $_SESSION['USER'])){
          return false;
          
      } else {
         
          if ($armar == 0) {
             
        preg_match('/\((.+)\)/', $sql, $campos);
        $proceso = 0;
        //echo $sql;
    $campos = explode(",", $campos[1]);
    $sql = str_replace('SYSDATE',"'D_ACTUAL'",$sql);
    preg_match('/\'(.+)\'/', trim($sql), $valores);
   // print_r(trim($valores[0]));
    $valores = str_replace("',", ";", $valores[0]);
   // print_r($valores);
    $valores = str_replace("D_ACTUAL","SYSDATE",$valores);
    $valores = str_replace("'","",$valores);
    $valores = explode(";", $valores);
    
  //  print_r($valores);
   // exit();
    for ($i=0; $i < count($campos); $i++){

        $arreglo[trim(strtoupper($campos[$i]))] = trim(str_replace('.',',',$valores[$i]));


    }
          } else {
              
              
              $arreglo = $armar;
              
              
          }
  //  echo print_r($valores);
        global $conex;
        $sql3 = "SELECT P.ID_PARAMETROS, P.CAMPO, P.VALOR, P.MENSAJE, O.SIMBOLO AS OPERADOR, P.TIPO_PROCESO, P.SUPERIOR FROM SR_PARAMETROS P, SR_OPERADORES O WHERE O.ID_OPERADOR = P.OPERADOR AND TABLA_GESTION = '$tablaGestion'
                            AND GRUPO_GESTION = '$grupoGestion'
                            AND COD_GESTION = '$codigoGestion'
                            AND STATUS = 'A'
                            UNION 
                 SELECT P.ID_PARAMETROS, P.CAMPO, P.VALOR, P.MENSAJE, O.SIMBOLO AS OPERADOR, P.TIPO_PROCESO, P.SUPERIOR FROM SR_PARAMETROS P, SR_OPERADORES O WHERE O.ID_OPERADOR = P.OPERADOR AND TABLA_GESTION = '$tablaGestion'
                            AND GRUPO_GESTION = '-1'
                            AND COD_GESTION = '-1'
                            AND NOT EXISTS(SELECT ID_PARAMETROS FROM SR_PARAMETROS WHERE COD_GESTION = '$codigoGestion' AND TABLA_GESTION = '$tablaGestion')
                            AND STATUS = 'A'
                            UNION
                 SELECT P.ID_PARAMETROS, P.CAMPO, P.VALOR, P.MENSAJE, O.SIMBOLO AS OPERADOR, P.TIPO_PROCESO, P.SUPERIOR FROM SR_PARAMETROS P, SR_OPERADORES O WHERE O.ID_OPERADOR = P.OPERADOR AND TABLA_GESTION = '-1'
                            AND GRUPO_GESTION = '-1'
                            AND COD_GESTION = '-1'
                            AND STATUS = 'A'
                ";

        $st = $conex->consulta($sql3);
        $respuesta = "";
        
        

    while ($fila = $conex->fetch_array($st)){

          
        if ($fila['TIPO_PROCESO'] == "validacion"){
         
        foreach ($arreglo as $key => $value) {
                $key = trim($key);
                $fila['CAMPO'] = trim($fila['CAMPO']);
                $value = trim($value);
                $fila['VALOR'] = trim($fila['VALOR']);
                
                 
            if (strnatcmp((strtoupper($fila['CAMPO'])), (strtoupper($key))) === 0){

                 

                //printf($date);
                
                (strnatcmp($fila['VALOR'],"SYSDATE") === 0) ? $fila['VALOR'] = date("d/m/Y") : "";
                //(strnatcmp($fila['VALOR'],"") === 0) ? $fila['VALOR'] = date("d/m/Y") : "";
                //  $fila['OPERADOR'] = ">";

                if (strnatcmp($fila['OPERADOR'],"=") === 0)  {

                         if (strnatcmp($value, $fila['VALOR']) == 0){

                            $respuesta .= " ".$fila['MENSAJE'];



                        }

                        } else if (strnatcmp($fila['OPERADOR'],">") === 0){

                           
                            if (strnatcmp($value, $fila['VALOR']) == -1 || strnatcmp($value, $fila['VALOR']) == 0){

                           $respuesta .= " ".$fila['MENSAJE'];


                        }


                        } else if (strnatcmp($fila['OPERADOR'],"<") === 0){
                            
                               
                            if (strnatcmp($value, $fila['VALOR']) == -1 ){
                             
                            $respuesta .= " ".$fila['MENSAJE'];
                            

                        }

                        } else if (strnatcmp($fila['OPERADOR'],"<>") === 0){
                           // echo $value." v ". $fila['VALOR'];
                                if (strnatcmp($value, $fila['VALOR']) !== 0){

                            $respuesta .= " ".$fila['MENSAJE'];


                        }

                        } else if (strnatcmp($fila['OPERADOR'],"<=") === 0){
                         //   echo ($value.",". $fila['VALOR']);
                           if (strnatcmp($value, $fila['VALOR']) == 1){

                            $respuesta .= " ".$fila['MENSAJE'];


                        }

                        } else if (strnatcmp($fila['OPERADOR'],">=") === 0){
                          //  echo strnatcmp($value, $fila['VALOR']);
                        if (strnatcmp($value, $fila['VALOR']) == -1){

                           $respuesta .= " ".$fila['MENSAJE'];


                        }

                        }




            } else {

              //   echo "No hay comvidencia";


            }




        }
        } else if ($fila['TIPO_PROCESO'] == "proceso"){

                foreach ($arreglo as $key => $value) {
                $key = trim($key);
                $fila['CAMPO'] = trim($fila['CAMPO']);
                $value = trim($value);
                $fila['VALOR'] = trim($fila['VALOR']);
            if (strnatcmp((strtoupper($fila['CAMPO'])), (strtoupper($key))) === 0){


            $operacion = $fila['VALOR'];
               // $operacion = "MONTO_PROMESA*2";
            foreach ($arreglo as $clave => $va) {
                 $operacion = str_replace($clave, $va, $operacion);
            }


            $sql2 = "select ".str_replace(';','',$operacion)." from dual";
           
            @$st1 = $conex->consulta($sql2);





            if (($result = $conex->fetch_array($st1)) == ""){

                echo "Error en el proceso ".$fila['ID_PARAMETROS']." en con valores (".$operacion.")";
                echo "La Gestion no ha sido Guardada.";
                exit();
            }
           //echo $sql2;


            $arreglo[$fila['CAMPO']] = $result[0];

                }

            }

            $proceso = 1;
        } else if ($fila['TIPO_PROCESO'] == "comparacion"){
            // print_r($arreglo);
                  foreach ($arreglo as $key => $value) {
                $key = trim($key);
                $fila['CAMPO'] = trim($fila['CAMPO']);
                $value = trim($value);
                $fila['VALOR'] = trim($fila['VALOR']);
            if (strnatcmp((strtoupper($fila['CAMPO'])), (strtoupper($key))) === 0){

                
            $operacion = $fila['VALOR'];
               // $operacion = "MONTO_PROMESA*2";
            foreach ($arreglo as $clave => $va) {
                 $operacion = str_replace(';'.$clave.';', ';'.$va.';', $operacion);
            }


            $sql2 = "select 1 from dual where ".str_replace(';','',$operacion);
          //  echo $sql2;
          //  echo $sql2;
          //    echo $sql2;
            
            if ($fila['SUPERIOR'] == 1){
                
                if (($_SESSION['USER'][0] == 'T' || $_SESSION['USER'][0] == 'C')){
                    
                    
                    @$st1 = $conex->consulta($sql2);



            $result = $conex->fetch_array($st1);
           // echo $sql2;
            if (($result) == ""){

                /*echo "Comparacion Error en el proceso ".$fila['ID_PARAMETROS']." en con valores (".$operacion.")";
                echo "La Gestion no ha sido Guardada.";
                exit();*/
                echo $fila['MENSAJE'];
                echo " La Gestion no ha sido Guardada.";
                
                exit();
            }
              
                    
                }
                
                
            } else {
                
                $st1 = $conex->consulta($sql2);



            $result = $conex->fetch_array($st1);
            //echo $sql2;
           // var_dump($result);
            if (($result) == false){

                /*echo "Comparacion Error en el proceso ".$fila['ID_PARAMETROS']." en con valores (".$operacion.")";
                echo "La Gestion no ha sido Guardada.";
                exit();*/
                echo $fila['MENSAJE'];
                echo " La Gestion no ha sido Guardada.";
                
                exit();
                
                
            }
            
            }
            
            
           //echo $sql2;


           // $arreglo[$fila['CAMPO']] = $result[0];
            
           /* if ($result[0] != 1){
                
                echo $fila['MENSAJE'];
                echo "La Gestion no ha sido Guardada.";
                exit();
                
            }*/

                }

            }
             $proceso = 1;
            
        }

    }
    
    if ($armar == 0){
    
    if ($proceso == 1 && $respuesta == ""){

                //$valores_cad = implode(",", $valores);
            $valores_cad = "";

            foreach ($arreglo as $k => $val) {
                $c[] = $k;
                if ($val == "SYSDATE"){
                    
                    $v[] = "".trim($val)."";
                } else {
                $v[] = "'".trim($val)."'";
                }

            }
            $campo_cad = implode(",", $c);
            $valores_cad = implode(",", $v);
            //echo $valores_cad;
           // echo "<br>campos:<br>";
           // print_r($c);
            // echo "<br>valores:<br>";
            //print_r($v);
            // echo "<br>SQL:<br>";
            $query = "insert into SR_GESTION (".$campo_cad.") values (".$valores_cad.")";
           $st = $conex->consulta($query);
           // echo $query;

    } else if ($respuesta == ""){

      $st = $conex->consulta($sql);


    }
      //  echo $query;
        return $respuesta;
      } else {
          
          return $respuesta;
          
      }

      } 
}
/*
 *  Autor: Henry Martinez
 *  Febrero 2014
 *  Copyright VPC 2014
 * 
 */

?>