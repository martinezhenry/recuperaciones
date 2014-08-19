<?php
/////////////////////////////
//Grupo VPC Copyrigth 2014 //
/////////////////////////////
//session_start();
require_once("../modelo/conexion.php"); // Clase para la conexion

/**
 * Clase para el manejo de la vista de 
 * agenda.
 *
 * Desarrollado por: Luis Peña
 * Fecha: 26/02/2014
 * Ultima modificación: 07/03/2014
 *
 * Metodos: 22
 */
class C_agenda extends Oracle {

  private $conn;                      // Objeto para la base de datos
  private $tipo_usuario;              // 0:(T/CU) 1:(S) 2:(GTE)
  private $asesor     = array();      // Datos Individuales de asesor     || ['Nombre'=> $valor, 'ID' => $valor]
  private $supervisor = array();      // Datos del supervisor       || ['Nombre'=> $valor, 'ID' => $valor]
  private $gerente    = array();      // Datos del gerente        || ['Nombre'=> $valor, 'ID' => $valor]
  private $asesores;                  // Cadena para obtener todos los asesores de un supervisor
  private $supervisores;              // Cadena para obtener todos los supervisores de un gerente
  /**
   * Cabeceras para las tablas 
   * cuando se incluye Chequear es para los checks  
   */
  private $cabecera                = array(' ', 'Cédula', 'Nombre','Cliente', 'Cantidad cuentas','Status');
  private $cabecera_toda_actividad = array(' ', 'Cédula', 'Nombre','Cliente', 'Cantidad cuentas','Status');
  private $cabecera_portafolio     = array('Cliente', 'Producto', 'Cuentas', 'Deudores','Saldo');
  public  $fecha_ultima_gestion;   // Almacena la ultima fecha en la cual el usuario posee actividad
  public  $cuentas_atraso;
  public  $cuentas_gestionadas;
  public  $casos_gestionados;
  public  $monto_aplicado;
  public  $casos_nuevos; 
  //=========CONSTRUCTOR=========
  /**
   * Constructor de la clase
   * @param String $Identificador
   */
  public function __construct($Identificador) {
      $this->conn = new Oracle($_SESSION['USER'], $_SESSION['pass']);      // Instaciamos de la clase Oracle para la base de datos
      switch($Identificador[0]){       // Obtenemos la primera letra del usuario para identificar
                                       // al Usuario
        /* T o C */                      
        case 'T': 
        case 'C': 
                  $this->obtener_supervisor($Identificador);                     // Obtenemos el supervisor para el T
                  $this->obtener_gerente   ($this->get_supervisor('ID'));        // Obtenemos el gerente del supervisor
                  $this->asesor['ID']    = $Identificador;                       // Guardamos el ID del usuario T 0 CU
                  $this->asesor['NOMBRE']= $this->get_name_by_ID($Identificador);// Guardamos el Nombre del usuario
                  // Realizamos esta consulta para obtener la fecha ultima de gestion 
                  // para luego marcarla con Jquery en el calendario
                  $sql2 = "SELECT DISTINCT FECHA FROM SR_AGENDA WHERE  
                          USUARIO = '$Identificador' AND
                          ROWNUM = 1";
                  $result2 = $this->conn->fetch_array_matriz($this->conn->consulta($sql2));
                  $this->fecha_ultima_gestion = $result2[0]['FECHA'];
                  break;
        /* S  */
        case 'S': $this->obtener_asesores($Identificador);                           // Obtenemos todos los asesores del supervisor
                  $this->obtener_gerente ($Identificador);                           // Obtenemos la informacion del superior
                  $this->asesor['ID']    = '';                                       // Queda vacio debido a que no es un Usuario T o C
                  $this->asesor['NOMBRE']= '';                                       // Queda vacio debido a que no es un Usuario T o C
                  $this->supervisor['ID']    = $Identificador;                       // Guardamos el ID del Supervisor
                  $this->supervisor['NOMBRE']= $this->get_name_by_ID($Identificador);// Guardamos el NOMBRE del Supervisor
                  break;
        /* GTE */     
        case 'G': $this->obtener_supervisores($Identificador);                    // Obtenemos el supervisor para el T
                  $this->asesor['ID']    = '';                                    // Queda vacio debido a que no es un Usuario T o C
                  $this->asesor['NOMBRE']= '';                                    // Queda vacio debido a que no es un Usuario T o C
                  $this->supervisor['ID']    = '';                                // Queda vacio debido a que no es un Usuario supervisor
                  $this->supervisor['NOMBRE']= '';                                // Queda vacio debido a que no es un Usuario supervisor
                  $this->gerente['ID']    = $Identificador;                       // Guardamos la información del gerente
                  $this->gerente['NOMBRE']= $this->get_name_by_ID($Identificador);// Guardamos la información del gerente
                  break;
      }
      //Guardamos el tipo de usuario si es T, C, S o G
      $this->tipo_usuario = $Identificador[0];
    }

  //=====MÉTODOS Get´s DE SALIDA=====

  public function get_asesor      ($pos) {return $this->asesor[$pos];     }// Método para obtener la información del usuario asesor
  public function get_supervisor  ($pos) {return $this->supervisor[$pos]; }// Método para obtener la información del usuario supervisor
  public function get_gerente     ($pos) {return $this->gerente[$pos];    }// Método para obtener la información del usuario gerente
  public function get_supervisores()     {return $this->supervisores;     }// Método para obtener todos los asesores bajo el cargo de un S
  public function get_asesores    ()     {return $this->asesores;}// Método para obtener todos los asesores bajo el cargo de un G
  public function get_tipo        ()     {return $this->tipo_usuario;}// Método para obtener el tipo de usuario

  //===========================================================================================================================
  //============MÉTODOS DE LA CLASE============================================================================================
  //===========================================================================================================================
  

  /**
   * Obtenemos los datos del supervisor
   * @param  String $ID
   * @return Llena las propiedades del Objeto
   */
  private function obtener_supervisor($ID) {
    $sql = "SELECT  A.USUARIO, A.NOMBRES 
            FROM SR_USUARIO A
            WHERE A.USUARIO in(SELECT U.USUARIO_SUPERIOR FROM SR_USUARIO U WHERE U.USUARIO ='$ID' AND U.STATUS_USUARIO = 'A')";
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    foreach($result as $valor){   // Guardamos los datos obtenidos de la base de datos
      $this->supervisor['ID']     = $valor['USUARIO'];
      $this->supervisor['NOMBRE'] = $valor['NOMBRES'];
    }
  }
  //===========================================================================================================================
  

  /**
   * Obtenemos los datos del gerente
   * @param  String $ID
   * @return Llena las propiedades del Objeto
   */
  private function obtener_gerente($ID) {
    $sql = "SELECT  A.USUARIO, A.NOMBRES 
            FROM SR_USUARIO A
            WHERE A.USUARIO in(SELECT U.USUARIO_SUPERIOR FROM SR_USUARIO U WHERE U.USUARIO ='$ID' AND U.STATUS_USUARIO = 'A')";
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    foreach($result as $valor){ // Guardamos los datos obtenidos de la base de datos
      $this->gerente['ID']     = $valor['USUARIO'];
      $this->gerente['NOMBRE'] = $valor['NOMBRES'];
    }
  }
  //===========================================================================================================================
  

  /**
   * Obtenemos una cadena con los asesores asignados a un supervisor
   * @param  string $supervisor
   * @return Obtenemos una cadena con los asesores asignados a un supervisor
   * cuya cadena esta en formato HTML y cada valor esta dentro de diversos <option>VALOR<option>. 
   */
  public function obtener_asesores($supervisor='') {
    if($supervisor==''){ // Si el parametro del método esta vacio muestra un option seleccione
      $contenido = "<option selected disabled value='seleccione' >Seleccione</option>"; 
      $this->asesores = $contenido;
    }
    else {
      // Seleccionamos los asesores asignados al supervisor y creamos una cadena con los
      // valores obtenidos.
      $sql = "SELECT USUARIO, NOMBRES
              FROM SR_USUARIO 
              WHERE USUARIO_SUPERIOR = '$supervisor' 
              AND STATUS_USUARIO = 'A'";
      $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));   // Buscamos en base de dato
      $contenido = "<option selected value='$supervisor' >Seleccione</option>";  // Colocamos esta opcion de primero
      foreach($result as $valor){                                               // Formamos la cadena con los valores
        $contenido .= "<option value='$valor[USUARIO]'>$valor[NOMBRES] ($valor[USUARIO])</option>";
      }
      // Asignamos los valores a la variable de la clase
      $this->asesores = $contenido;
    }
  }
  //===========================================================================================================================


  /**
   * Obtenemos los supervisores asignados a un gerente
   * @param  String $gerente
   * @return Obtenemos una cadena de options en formato HTML para luego 
   * mostrar en la vista
   */
  private function obtener_supervisores($gerente) {
   // Query para buscar los supervisores
    $sql = "SELECT USUARIO, NOMBRES
            FROM SR_USUARIO 
            WHERE USUARIO_SUPERIOR = '$gerente' 
            AND STATUS_USUARIO = 'A'";
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    $contenido = "<option selected disabled value='seleccione' >Seleccione</option>";// Colocamos esta opcion de primero
    foreach($result as $valor){
      $contenido .= "<option value='$valor[USUARIO]'>$valor[NOMBRES]  ($valor[USUARIO])</option>";// Formamos la cadena con los valores
    }
    // Asignamos el valor a la variable de la clase
    $this->supervisores = $contenido;
  }
  //===========================================================================================================================


  /**
   * Obtenemos todas las cuentas que estan agendadas para un determinado asesor dependiendo del supervisor
   * @param  String fecha que se busca $fecha
   * @param  String código del supervisor $supervisor
   * @return Imprime el resultado para que posteriormente sea procesado por Jquery
   */
  public function obtener_toda_actividad($fecha, $supervisor) {
   // Query para obtener la informacion
   // Buscamos la ultima fecha en la que sxisten cuentas agendadas
    $sql6 = " SELECT MIN(TRUNC(A.FECHA)) AS FECHA
            FROM SR_AGENDA A 
            WHERE A.USUARIO IN  (
                SELECT U.USUARIO FROM SR_USUARIO U WHERE U.USUARIO_SUPERIOR = '$supervisor' AND U.STATUS_USUARIO = 'A'
             )";
    $result6 =  $this->conn->fetch_array_matriz($this->conn->consulta($sql6));
    $fecha = $result6[0]['FECHA'];
    $sql = "SELECT P.PERSONA AS CEDULA,P.NOMBRE, C.NOMBRE as CLIENTE, COUNT(A.CUENTA) CANTIDAD_CUENTA, A.MOTIVO
            FROM SR_AGENDA A, TG_CLIENTE C, TG_PERSONA P
            WHERE A.USUARIO in(SELECT U.USUARIO FROM SR_USUARIO U WHERE U.USUARIO_SUPERIOR ='$supervisor' AND U.STATUS_USUARIO = 'A')
            AND A.CLIENTE = C.CLIENTE
            AND A.PERSONA = P.PERSONA
            AND A.FECHA = '$fecha'
            GROUP BY P.PERSONA,P.NOMBRE, C.NOMBRE, A.MOTIVO";
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));  // Ejecutamos el query
    // Si existen resultados entramos en el IF
    // para las gestiones realizadas 
    $sql2 = "   SELECT COUNT(*) as cuenta FROM SR_GESTION K WHERE K.USUARIO_GESTOR in (
    select U.USUARIO from sr_usuario U
    where U.USUARIO_SUPERIOR = '$supervisor' and U.STATUS_USUARIO = 'A'
    ) AND TRUNC(K.FECHA_INGRESO) BETWEEN '$fecha' and '$fecha'";
    $result2 =  $this->conn->fetch_array_matriz($this->conn->consulta($sql2));
      // Formamos el recuadro que informacion que se encuentra debajo del calendario
    $sql3 = "SELECT  COUNT(DISTINCT L.PERSONA) AS CEDULA FROM SR_CUENTA L WHERE EXISTS( 
        SELECT M.CUENTA FROM SR_GESTION M WHERE M.CUENTA = L.CUENTA AND M.CARTERA = L.CARTERA AND M.CLIENTE = L.CLIENTE
        AND TRUNC(M.FECHA_INGRESO) BETWEEN '$fecha' AND '$fecha' AND M.USUARIO_GESTOR IN (SELECT U.USUARIO FROM SR_USUARIO U
        WHERE U.USUARIO_SUPERIOR = '$supervisor' AND U.STATUS_USUARIO = 'A'
        )
        )";
    $result3 =  $this->conn->fetch_array_matriz($this->conn->consulta($sql3));
    $sql4 = " SELECT  COUNT(E.ID) AS NUEVOS
        FROM SR_CUENTA A,  TG_PERSONA E
        WHERE A.STATUS_CUENTA = 'A'
        AND A.PERSONA = E.PERSONA 
        AND NOT EXISTS (SELECT F.CUENTA FROM SR_GESTION F WHERE A.CLIENTE = F.CLIENTE AND A.CARTERA = F.CARTERA AND A.CUENTA = F.CUENTA AND F.FECHA_INGRESO >= A.FECHA_INGRESO)
        AND A.USUARIO_GESTOR in (
        select U.USUARIO from sr_usuario U
        where U.USUARIO_SUPERIOR = '$supervisor' and U.STATUS_USUARIO = 'A'
        )";
    $result4 =  $this->conn->fetch_array_matriz($this->conn->consulta($sql4));
    $sql5 = " SELECT SUM(COUNT(DISTINCT A.PERSONA)) AS CEDULAS
            FROM SR_AGENDA A, SR_USUARIO C 
            WHERE A.USUARIO in (        
            select U.USUARIO from sr_usuario U
            where U.USUARIO_SUPERIOR = '$supervisor' and U.STATUS_USUARIO = 'A') AND 
            A.USUARIO = C.USUARIO AND 
            TRUNC(A.FECHA) < '".date('d/m/Y')."' AND 
            C.STATUS_USUARIO = 'A'
            GROUP BY A.PERSONA ";
     $result5 =  $this->conn->fetch_array_matriz($this->conn->consulta($sql5));       
      $datos = '';
      $datos = '<section class="col col-6" style="width: 250px;" >
              <table >
              <tr>
              <td style="font-size: 12px;" >General:</td>
            </tr>
                <tr>
                  <td style="font-size: 11px;" >Cuentas en atraso:</td>
                  <td><input disabled type="text" value="'.$result5[0]['CEDULAS'].'" style="width: 100px"/></td>
                </tr>
                <tr>
                  <td style="font-size: 11px;" >Casos Nuevos:</td>
                  <td><input disabled type="text" value="'.$result4[0]['NUEVOS'].'" style="width: 100px"/></td>
                </tr>
              <td style="font-size: 12px;" >Al Día:</td>
                <tr>
                  <td style="font-size: 11px;" >Deudores en agenda:</td>
                  <td><input disabled type="text" value="'.$this->contar_deudores($result).'" style="width: 100px"/></td>
                </tr>
                <tr>
                  <td style="font-size: 11px;" >Gestiones Realizadas:</td>
                  <td><input disabled type="text" value="'.$result2[0]['CUENTA'].'" style="width: 100px"/></td>
                </tr>
                <tr>
            </tr>
                <tr>
                  <td style="font-size: 11px;" >Gestiones por Casos:</td>
                  <td><input disabled type="text" value="'.$result3[0]['CEDULA'].'" style="width: 100px"/></td>
                </tr>
              </table>
            </section>';
      // Llamamos a la función getTableInfo que se encuentra en el archivo functions.php
      // diseñada para obtenemos funciones genericas
      // Para mas Documentación acerca de la funcion verificar el archivo.
      if(isset($result)){
      echo '<div> <input style="opacity: 1;margin: 40px 0px 0px 13px;;position: absolute;z-index: 1;cursor: pointer;" onclick="activarDesactivarChecks(document.getElementsByName(\'checkcuentas[]\'), document.getElementById(\'selecttodos\'));" type="checkbox" id="selecttodos"> </div>';
      // Declaramos las variables usadas en la funcion 
      $ID           = "Tabla_actividades";
      $cabecera     = $this->cabecera;
      $contenido    = $result;
      $int_cabecera = 5;
      $checks       = true;
      $selects      = true;
      // Código de la funcion con la modificacion del boton
      $tabla = '  <table id="'.$ID.'" cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
          <thead>
            <tr>
              ';
              foreach($cabecera as $head){
                $tabla .= "<th style='font-weight: normal;' >".$head."</th>";
              }
              $tabla .= '
            </tr>
          </thead>
          <tbody id="tabla_gestion" >

          ';  $cont = 1;$motivos = '';
              foreach($contenido as $indice => $valor) {
                $tabla .= '<tr id="1" onclick="ver_motivo('.$cont.')"  class="gradeA">';
                  if($checks)
                      $tabla .= '<td class="center"><input type="checkbox" name="checkcuentas[]"  value="'.$valor[0].'"></td>';
                      
                      for($x=0;$x<$int_cabecera;$x++){
                        if($x==$int_cabecera-1) {
                          // Modificacion con el boton
                           $tabla .= '<td  style="font-size: 11px;"><input type="button" value="Ver motivo" id="ver_motivo'.$cont.'" onclick="ver_motivo('.$cont.')"></td>';
                           $motivos .= '<div  id="motivo_'.$cont.'" style="width: 669px; display: none;" > '.utf8_encode($valor[$x]).' </div>';
                        }
                        else 
                          $tabla .= '<td style="font-size: 11px;">'.utf8_encode($valor[$x]).'</td>';
                    }
                    
                      $tabla .= '</tr>';
                $cont++;
              }
              $tabla .= ' 
          </tbody>
        </table>';
        if($selects) {
          $tabla .= '<table  cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
            <tfoot id="pie'.$ID.'" >
              <tr>
              ';
                $cont = 1;
                foreach($cabecera as $head){
                  if(($cont) == count($cabecera))
                    {}
                  else
                    $tabla .= "<th id='last_pos_table'></th>";
                  $cont++;
                }
                $tabla .= '
              </tr>
            </tfoot>
          </table>';
        }
      // Mostramos la tabla para que sea gestionada por jquery
      echo $tabla.'[SEPAR2]'. $motivos.'[SEPAR2]'.$datos.'[SEPAR2]'.$result6[0]['FECHA'];
      exit();
    }else{
      echo "No existen cuentas agendadas";
      exit();
    }
  }
  //===========================================================================================================================


  /**
   * Metodo para obtener el nombre de un usuario mediante del ID
   * @param  String $ID
   * @return String nombre
   */
  private function get_name_by_ID($ID) {
    $sql = "SELECT  A.NOMBRES 
            FROM SR_USUARIO A
            WHERE A.USUARIO = '$ID'";
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    $nombre ='';
    foreach($result as $valor)
      $nombre = $valor['NOMBRES'];
    return $nombre; //Retornamos el nombre del ID
  }
  //===========================================================================================================================

  // VERIFICAR METODO POSIBLEMENTE OBSOLETO
  /**
   * Da respuesta a un llamado de Ajax para forzar al usuario ha que 
   * debe gestionar las cuentas en atraso primero.
   * @param  Date posee la Fecha que esta en atraso $fecha_ultima
   * @param  Date fecha que el usuario solicita gestionar $fecha
   * @return 0/1 dependiendo si la fecha 
   */
  public function validar_gestion($fecha_ultima, $fecha) {
    if($fecha_ultima < $fecha){
      echo '0';
      exit();
    }
    else{
      if($fecha_ultima == $fecha){
        echo '1';
        exit();
      }
      else{
        echo '0';
        exit();
      }
    }
  }
  //===========================================================================================================================


  /**
   * Obtenemos los datos que se van a mostrar en la tabla de los asesores
   * @param  String con la fecha seleccionada $fecha
   * @param  String código de un T o C $asesor
   * @return Imprime la tabla que va a mostrar los datos obtenidos para luego 
   * ser gestionada por Jquery
   */
  public function obtener_gestion($fecha, $asesor) {
    // Query para buscar en la base de datos 
    // las cuentas que existen por gestionar 
    $sql = "SELECT P.PERSONA, P.ID AS CEDULA,P.NOMBRE,C.NOMBRE as CLIENTE,COUNT(A.CUENTA) CANTIDAD_CUENTA, A.MOTIVO, A.FECHA
            FROM SR_AGENDA A, TG_CLIENTE C, TG_PERSONA P
            WHERE A.USUARIO = '$asesor'
            AND A.CLIENTE = C.CLIENTE
            AND A.PERSONA = P.PERSONA
            AND A.FECHA = '$fecha'
            GROUP BY  A.MOTIVO,P.PERSONA,P.NOMBRE, C.NOMBRE, A.FECHA
            ORDER BY CEDULA ASC";
    // Buscamos la ultima fecha que esta en atraso
    // Para luego forzar al usuario a gestionar esa fecha
    $sql2 = "SELECT DISTINCT FECHA FROM SR_AGENDA WHERE  
            USUARIO = '$asesor' AND
            ROWNUM = 1";

    $sql3 = "SELECT SUM(COUNT(DISTINCT A.PERSONA)) AS CEDULAS
            FROM SR_AGENDA A, SR_USUARIO C 
            WHERE A.USUARIO = '$asesor' AND 
            A.USUARIO = C.USUARIO AND 
            TRUNC(A.FECHA) < '".date('d/m/Y')."' AND 
            C.STATUS_USUARIO = 'A'
            GROUP BY A.PERSONA ";  
    
    $sql4 = " SELECT COUNT(*) as cuenta FROM SR_GESTION K WHERE K.USUARIO_GESTOR = '$asesor' AND TRUNC(K.FECHA_INGRESO) BETWEEN '$fecha' and '$fecha'";
    
    $sql5 = "SELECT  COUNT(DISTINCT L.PERSONA) AS CEDULA FROM SR_CUENTA L WHERE EXISTS( 
            SELECT M.CUENTA FROM SR_GESTION M WHERE M.CUENTA = L.CUENTA AND M.CARTERA = L.CARTERA AND M.CLIENTE = L.CLIENTE
            AND TRUNC(M.FECHA_INGRESO) BETWEEN '$fecha' AND '$fecha' AND M.USUARIO_GESTOR = '$asesor'
            )";

    $sql6 = "SELECT  COUNT(E.ID) AS NUEVOS
            FROM SR_CUENTA A,  TG_PERSONA E
            WHERE A.STATUS_CUENTA = 'A'
            AND A.PERSONA = E.PERSONA 
            AND NOT EXISTS (SELECT F.CUENTA FROM SR_GESTION F WHERE A.CLIENTE = F.CLIENTE AND A.CARTERA = F.CARTERA AND A.CUENTA = F.CUENTA AND F.FECHA_INGRESO >= A.FECHA_INGRESO)
            AND A.USUARIO_GESTOR = '$asesor'";

    $result2 = $this->conn->fetch_array_matriz($this->conn->consulta($sql2));
    $result3 = $this->conn->fetch_array_matriz($this->conn->consulta($sql3));
    $result4 = $this->conn->fetch_array_matriz($this->conn->consulta($sql4));             
    $result5 = $this->conn->fetch_array_matriz($this->conn->consulta($sql5));
    $result6 = $this->conn->fetch_array_matriz($this->conn->consulta($sql6));
    

    $this->fecha_ultima_gestion = $result2;
    $this->cuentas_atraso       = $result3;
    $this->cuentas_gestionadas  = $result4;
    $this->casos_gestionados    = $result5;
    $this->casos_nuevos         = $result6;
        
    $this->casos_nuevos         = $this->casos_nuevos        [0]['NUEVOS'];
    $this->cuentas_atraso       = $this->cuentas_atraso      [0]['CEDULAS'];
    $this->cuentas_gestionadas  = $this->cuentas_gestionadas [0]['CUENTA'];
    $this->casos_gestionados    = $this->casos_gestionados   [0]['CEDULA'];
    $this->fecha_ultima_gestion = $this->fecha_ultima_gestion[0]['FECHA'];   // Asignamos la ultima fecha que esta en atraso a la varible de la clase
    $this->fecha_ultima_gestion = explode('/', $this->fecha_ultima_gestion); // Obtenemos la fecha con este formato: xx/xx/xx
    echo $this->fecha_ultima_gestion[0].'/'.$this->fecha_ultima_gestion[1].'/'.($this->fecha_ultima_gestion[2]+2000).'[SEPAR]'; // Cambiamos el Formato a xx/xx/xxxx
    //Realizamos la consulta en la base de datos Oracle 
    //Que nos devuelve una matriz con los resultados    
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    //Retornamos los resultados
    $this->cargar_tabla($result);//Cargamos la nueva tabla con los datos segun la fecha
  }
  //===========================================================================================================================

  // SE PUEDE OPTIMIZAR
  /**
   * Retorna la cantidad de deudores que existen en una agenda
   * @param  Array con los resultados obtenidos de la consulta
   * de la funcion obtener_gestion $array
   * @return int cantidad de deudores 
   */
  private function contar_deudores($array){
    $cont = 0;
    $ultimo = '';
    if(isset($array)){
      foreach($array as $indice => $valor){
        if($ultimo==$valor[0])
          {}
        else{
          $cont++;
          $ultimo = $valor[0];
          //echo $ultimo.'<br>';
        }
      }
    }

    return $cont; // Devuelve el valor
  }


  private function contar_cuentas_atraso($array){
    $fecha = date('d/m/Y');
    $cont = '';
    if(isset($array)){
      foreach($array as $indice => $valor){
        if($valor[5] < $fecha)
          $cont++;
      }
    }
    return $cont++; // Devuelve el valor
  }

  //===========================================================================================================================


  /**
   * Carga la tabla con los datos del array de la consulta del metodo obtener_gestion
   * @param  Array con los datos $array
   * @return Código de la tabla
   */
  private function cargar_tabla($array) { 

   // Imprime la tabla que se encuentra debajo del calendario
   // dando informacion general de la agenda del asesor
   $datos = '';
   $datos = '<section class="col col-6" style="width: 250px;" >
          <table >
          <tr>
              <td style="font-size: 12px;" >General:</td>
            </tr>
            <tr>
              <td style="font-size: 11px;" >Cuentas en Atraso:</td>
              <td><input disabled type="text" value="'.$this->cuentas_atraso.'" style="width: 100px"/></td>
            </tr>
            <tr>
              <td style="font-size: 11px;" >Casos Nuevos:</td>
              <td><input disabled type="text" value="'.$this->casos_nuevos.'" style="width: 100px"/></td>
            </tr>
              <td style="font-size: 12px;" >Al Día:</td>
            <tr>
              <td style="font-size: 11px;" >Deudores en agenda:</td>
              <td><input disabled type="text" value="'.$this->contar_deudores($array).'" style="width: 100px"/></td>
            </tr>
            <tr>
              <td style="font-size: 11px;" >Gestiones Realizadas:</td>
              <td><input disabled type="text" value="'.$this->cuentas_gestionadas.'" style="width: 100px"/></td>
            </tr>
            <tr>
            </tr>
            <tr>
              <td style="font-size: 11px;" >Gestiones por Casos:</td>
              <td><input disabled type="text" value="'.$this->casos_gestionados.'" style="width: 100px"/></td>
            </tr>
          </table>
        </section>';
    // Imprime la tabla con los datos
    // NOTA: Este código es una copia de la funcion getTableInfo del archivo functions
    // se modificaron ciertas cosas para mostrar los motivos mediante un Boton
    if(isset($array)){
      echo '<div> <input style="opacity: 1;margin: 40px 0px 0px 13px;;position: absolute;z-index: 1;cursor: pointer;" onclick="activarDesactivarChecks(document.getElementsByName(\'checkcuentas[]\'), document.getElementById(\'selecttodos\'));" type="checkbox" id="selecttodos"> </div>';
      // Declaramos las variables usadas en la funcion 
      $ID           = "Tabla_actividades";
      $cabecera     = $this->cabecera;
      $contenido    = $array;
      $int_cabecera = 5;
      $checks       = true;
      $selects      = true;
      // Código de la funcion con la modificacion del boton
      $tabla = '  <table id="'.$ID.'" cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
          <thead>
            <tr>
              ';
              foreach($cabecera as $head){
                $tabla .= "<th style='font-weight: normal;' >".$head."</th>";
              }
              $tabla .= '
            </tr>
          </thead>
          <tbody id="tabla_gestion" >

          ';  $cont = 1;$motivos = '';
              foreach($contenido as $indice => $valor) {
                $tabla .= '<tr id="1" onclick="ver_motivo('.$cont.')"  class="gradeA">';
                  if($checks)
                      $tabla .= '<td class="center"><input type="checkbox" name="checkcuentas[]"  value="'.$valor[0].'"></td>';
                      
                      for($x=0;$x<$int_cabecera;$x++){
                        if($x==$int_cabecera-1) {
                          // Modificacion con el boton
                           $tabla .= '<td  style="font-size: 11px;"><input type="button" value="Ver motivo" id="ver_motivo'.$cont.'" onclick="ver_motivo('.$cont.')"></td>';
                           $motivos .= '<div  id="motivo_'.$cont.'" style="width: 235px; display: none;" > '.utf8_encode($valor[$x]).' </div>';
                        }
                        else 
                          $tabla .= '<td style="font-size: 11px;">'.utf8_encode($valor[$x]).'</td>';
                    }
                    
                      $tabla .= '</tr>';
                $cont++;
              }
              $tabla .= ' 
          </tbody>
        </table>';
        if($selects) {
          $tabla .= '<table  cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
            <tfoot id="pie'.$ID.'" >
              <tr>
              ';
                $cont = 1;
                foreach($cabecera as $head){
                  if(($cont) == count($cabecera))
                    {}
                  else
                    $tabla .= "<th id='last_pos_table'></th>";
                  $cont++;
                }
                $tabla .= '
              </tr>
            </tfoot>
          </table>';
        }
      // Mostramos la tabla para que sea gestionada por jquery
      echo $tabla.'[SEPAR2]'. $motivos.'[SEPAR2]'.$datos;
      exit();
    }else{
      echo "No existen cuentas agendadas";
      exit();
    }
  }
  //===========================================================================================================================


  /**
   * Obtiene un resumen de la cartera del usuario
   * @param  String $fecha
   * @param  String $asesor
   * @return llamada a la funcion cargar_tabla_resumen_portafolio
   */
  public function obtener_portafolio_resumen($asesor, $mes, $fecha_est='') {
   $sql = "SELECT B.NOMBRE||' ('||B.CLIENTE||') ' AS CLIENTE,
           C.DESCRIPCION CARTERA
           ,COUNT(A.CUENTA) AS CUENTA, 
           COUNT(DISTINCT(A.PERSONA)) DEUDORES,
           SUM(A.SALDO_ACTUAL) TOTAL_SALDO
           FROM SR_CUENTA A, TG_CLIENTE B, SR_CARTERA C
           WHERE A.CLIENTE = B.CLIENTE
           AND A.CARTERA = C.CARTERA
           AND A.CLIENTE = C.CLIENTE
           AND A.STATUS_CUENTA = 'A'
           AND A.USUARIO_GESTOR = '$asesor'
           GROUP BY B.CLIENTE,B.NOMBRE,C.DESCRIPCION
           ORDER BY NOMBRE";
    
    $sql2 = "SELECT '01/'|| to_char(trunc(last_day(add_months(sysdate,$mes))),'mm/yyyy') as primer_dia, trunc(last_day(add_months(sysdate,$mes))) as ultimo_dia FROM DUAL";
    //echo $sql2;
    //exit();
    $result2 = $this->conn->fetch_array_matriz($this->conn->consulta($sql2));
    $Fecha_p =''; $Fecha_u ='';
    foreach($result2 as $valor){ 
      $Fecha_p = $valor['PRIMER_DIA'];// Guardamos el valor devuelto por la cosulta a la base de datos
      $Fecha_u = $valor['ULTIMO_DIA'];// Guardamos el valor devuelto por la cosulta a la base de datos
    }

    $this->act_monto_apli($Fecha_p, $Fecha_u, $asesor);

   //Realizamos la consulta en la base de datos Oracle 
   //Que nos devuelve una matriz con los resultados    
   $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
   //Retornamos los resultados
   
   $this->cargar_tabla_resumen_portafolio($result, $asesor, $Fecha_p);//Cargamos la nueva tabla con los datos segun la fecha
  }
  //===========================================================================================================================


  /**
   * Carga la tabla con la informacion obtenida del metodo obtener_portafolio_resumen
   * @param  Array con los datos $array
   * @return [type]
   */
  private function cargar_tabla_resumen_portafolio($array, $asesor, $fecha_esta='') {
    // Verificamos si existe informacion contenida en el array
    if(isset($array)) {
      $TOTAL_cuentas  = 0;// Declaramos la variable para contar las cuentas
      $TOTAL_deudores = 0;// Declaramos la variable para contar los deudores
      $TOTAL_saldo    = 0;// Declaramos la variable para contar el saldo total
      foreach($array as $valor){
        $TOTAL_cuentas  += $valor['CUENTA'];              // Contamos las cuentas
        $TOTAL_deudores += $valor['DEUDORES'];            // Contamos los deudores
        $TOTAL_saldo    += (float) str_replace(',', '.', $valor['TOTAL_SALDO']); // Contamos el Saldo
      }
      $TOTAL_saldo = str_replace('.', ',', $TOTAL_saldo);
      if($asesor[0] != 'S')
      $sql =  "SELECT TRUNC(((B.MONTO/A.META )*100),2) EFECTIVIDAD
               FROM
               (SELECT SUM(META_VPC) AS META
               FROM SR_METAS_CUENTA
               WHERE USUARIO_GESTOR = '$asesor'
               AND MES = (SELECT MAX(MES)
               FROM SR_METAS_CUENTA
               WHERE USUARIO_GESTOR = '$asesor')) A,
               (SELECT SUM(MONTO_DEPOSITO) AS MONTO
               FROM SR_ABONO
               WHERE USUARIO_GESTOR = '$asesor'
               AND FECHA_INGRESO >= (SELECT MAX(MES)
               FROM SR_METAS_CUENTA
               WHERE USUARIO_GESTOR = '$asesor')
               AND FECHA_INGRESO <= LAST_DAY((SELECT MAX(MES)
               FROM SR_METAS_CUENTA
               WHERE USUARIO_GESTOR = '$asesor')) + 0.99
               AND STATUS_ABONO IN ('C','N')) B";
               // OBTENEMOS LA EFECTIVIDAD 
      else
      $sql =  "SELECT TRUNC(((B.MONTO/A.META )*100),2) EFECTIVIDAD
               FROM
               (SELECT SUM(META_VPC) AS META
               FROM SR_METAS_CUENTA
               WHERE USUARIO_SUPERIOR = '$asesor'
               AND MES = (SELECT MAX(MES)
               FROM SR_METAS_CUENTA
               WHERE USUARIO_SUPERIOR = '$asesor')) A,
               (SELECT SUM(MONTO_DEPOSITO) AS MONTO
               FROM SR_ABONO
               WHERE USUARIO_SUPERVISOR = '$asesor'
               AND FECHA_INGRESO >= (SELECT MAX(MES)
               FROM SR_METAS_CUENTA
               WHERE USUARIO_SUPERIOR = '$asesor')
               AND FECHA_INGRESO <= LAST_DAY((SELECT MAX(MES)
               FROM SR_METAS_CUENTA
               WHERE USUARIO_SUPERIOR = '$asesor')) + 0.99
               AND STATUS_ABONO IN ('C','N')) B";

               $efectividad = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
   if($asesor[0] != 'S')
      $sql =  " SELECT SUM(MONTO_DEPOSITO) AS MONTO
                 FROM SR_ABONO
                 WHERE USUARIO_GESTOR = '$asesor'
                 AND FECHA_INGRESO >= (SELECT MAX(MES)
                 FROM SR_METAS_CUENTA
                 WHERE USUARIO_GESTOR = '$asesor')
                 AND FECHA_INGRESO <= LAST_DAY((SELECT MAX(MES)
                 FROM SR_METAS_CUENTA
                 WHERE USUARIO_GESTOR = '$asesor')) + 0.99
                 AND STATUS_ABONO IN ('C','N')";
                 // OBTENEMOS LA EFECTIVIDAD 
    else
    $sql =  " SELECT SUM(MONTO_DEPOSITO) AS MONTO
                 FROM SR_ABONO
                 WHERE USUARIO_SUPERVISOR = '$asesor'
                 AND FECHA_INGRESO >= (SELECT MAX(MES)
                 FROM SR_METAS_CUENTA
                 WHERE USUARIO_SUPERVISOR = '$asesor')
                 AND FECHA_INGRESO <= LAST_DAY((SELECT MAX(MES)
                 FROM SR_METAS_CUENTA
                 WHERE USUARIO_SUPERVISOR = '$asesor')) + 0.99
                 AND STATUS_ABONO IN ('C','N')";
      $monto = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    if($asesor[0] == 'S')
      $sql = "SELECT TRUNC(SUM(META_VPC),2) AS META
             FROM SR_METAS_CUENTA
             WHERE USUARIO_GESTOR IN(
             SELECT U.USUARIO FROM SR_USUARIO U WHERE U.USUARIO_SUPERIOR = '$asesor' AND U.STATUS_USUARIO = 'A'
             )
             AND MES = '$fecha_esta'";
    else
      $sql = "SELECT TRUNC(SUM(META_VPC),2) AS META
              FROM SR_METAS_CUENTA
              WHERE USUARIO_GESTOR = '$asesor'
              AND MES = '$fecha_esta'";
     $meta = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
      // Tabla para mostrar la informacion resumida en el resumen de portafolio
      // Estadisticas
               
      echo '<section class="col col-6" style="width: 252px;float: left;min-height: 224px;margin: -30px 0px 0px 728.5px;position: absolute;background: white;border-style: solid;border-width: 1px;border-color: rgb(201, 201, 201);padding: 10px 10px;" >
              <h2>Estadísticas</h2>
              <hr><br>
              <table >
                <tr>
                  <td style="font-size: 11px;" >Casos Asignados:</td>
                  <td><input disabled type="text" value="'.$TOTAL_cuentas.'" style="width: 90px"/></td>
                </tr>
                <tr>
                  <td style="font-size: 11px;" >Monto Asignado:</td>
                  <td><input disabled type="text" value="'.$TOTAL_saldo.'" style="width: 90px"/></td>
                </tr>
                <tr>
                  <td style="font-size: 11px;" >Monto Reportado:</td>
                  <td><input disabled type="text" value="'.$monto[0]['MONTO'].'" style="width: 90px"/></td>
                </tr>
                <tr>
                  <td style="font-size: 11px;" >Monto Aplicado:</td>
                  <td><input id="monto_apli" disabled type="text" value="'.$this->monto_aplicado.'"  style="width: 90px"/></td>
                </tr>
                <tr>
                  <td style="font-size: 11px;" >Efectividad:</td>
                  <td><input disabled type="text" value="'.$efectividad[0]['EFECTIVIDAD'].'%" style="width: 90px"/></td>
                </tr>
              </table>
              <h2>Meta</h2>
              <hr><br>
              <table >
                <tr>
                  <td style="font-size: 11px;" >Meta:</td>
                  <td><input disabled type="text" value="'.$meta[0]['META'].'" style="width: 90px"/></td>
                </tr>
              </table>
            </section>';
      echo '<div style="position: absolute;margin: 177px 0px 0px 352px;" ><div style="float: left;margin: 0px 20px;" >Total: '.$TOTAL_cuentas.'</div><div style="float: left;margin: 0px 20px;" >    Total: '.$TOTAL_deudores.' </div><div style="float: left;margin: 0px 20px;" >   Total: '.$TOTAL_saldo.'</div></div>';
      if(isset($array)){
        echo getTableInfo("Tabla_resumen", $this->cabecera_portafolio, $array, 5, false,false);
        exit();
      }
      else{
          echo "No existen cuentas agendadas";
          exit();
      }
    }exit();
  }
  //===========================================================================================================================
  /*
  
   */
   public function act_monto_apli($fecha1, $fecha2, $asesor)
   {
    if($asesor[0] != 'S')
     $sql = "SELECT SUM(A.MONTO_DEPOSITO) AS MONTO_APLICADO
              FROM SR_ABONO A
              WHERE
              A.USUARIO_GESTOR = '$asesor' AND
              A.STATUS_ABONO = 'C' AND
              A.FECHA_DEPOSITO BETWEEN '$fecha1' AND '$fecha2'";
    else
      $sql = "SELECT SUM(A.MONTO_DEPOSITO) AS MONTO_APLICADO
              FROM SR_ABONO A
              WHERE
              A.USUARIO_SUPERVISOR = '$asesor' AND
              A.STATUS_ABONO = 'C' AND
              A.FECHA_DEPOSITO BETWEEN '$fecha1' AND '$fecha2'";

      $monto = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
      $this->monto_aplicado = $monto[0]['MONTO_APLICADO'];
      if(isset($monto[0]['MONTO_APLICADO']))
        echo $monto[0]['MONTO_APLICADO'].'[SEPAR]';
      else
        echo '0'.'[SEPAR]';
   }


  //===========================================================================================================================

  /**
   * Funcion para obtener los limites de cada mes para luego verificar las fechas
   * donde existen cuentas por gestionar
   * @param  [type] $fecha
   * @param  [type] $asesor
   * @return [type]
   */
  public function obtener_resumen($asesor, $mes) {
    $sql = "SELECT '01/'|| to_char(trunc(last_day(add_months(sysdate,$mes))),'mm/yyyy') as primer_dia, trunc(last_day(add_months(sysdate,$mes))) as ultimo_dia FROM DUAL";
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    $Fecha_p =''; $Fecha_u ='';
    foreach($result as $valor){ 
      $Fecha_p = $valor['PRIMER_DIA'];// Guardamos el valor devuelto por la cosulta a la base de datos
      $Fecha_u = $valor['ULTIMO_DIA'];// Guardamos el valor devuelto por la cosulta a la base de datos
    }
    // Verificamos si el asesor es un supervisor 
    if($asesor[0] == 'S'){
      
      $this->act_monto_apli($Fecha_p, $Fecha_u, $asesor);
      $this->obtener_fechas_cal_todo($Fecha_p, $Fecha_u, $asesor);
    }
    else {

      $this->act_monto_apli($Fecha_p, $Fecha_u, $asesor);
      $this->obtener_fechas_cal($Fecha_p, $Fecha_u, $asesor);
      
    }
  }
  //===========================================================================================================================
  

  /**
   * Obtiene todas las fechas de actividad de los gestores relacionado con el supervisor
   * @param  String formato de fecha d/m/Y contiene la primera fecha del mes 01/xx/xxxx $Fecha_p
   * @param  String formato de fecha d/m/Y contiene la ultima fecha del mes 31/xx/xxxx $Fecha_u
   * @param  String Código del supervisor a buscar $asesor
   * @return Cadena  con las fechas de gestion separadas por || y 
   * otra cadena con las fechas de los dias feriados. estas dos cadenas estan unidas
   * con el simbolo %, para luego medite jquery separarlas dos cadenas. 
   */
  private function obtener_fechas_cal($Fecha_p, $Fecha_u, $asesor) {
    $sql = "SELECT trunc(FECHA) as fecha 
            FROM SR_AGENDA 
            WHERE FECHA <= '".$Fecha_u[0].$Fecha_u[1].$Fecha_u[2].$Fecha_u[3].$Fecha_u[4].$Fecha_u[5].'20'.$Fecha_u[6].$Fecha_u[7]."'
            AND FECHA >= '$Fecha_p'
            AND USUARIO = '$asesor' 
            GROUP BY trunc(FECHA)
            ORDER BY fecha ASC";

    $sql2 = "SELECT trunc(min(distinct A.FECHA)) as fecha 
            from sr_agenda A where 
            A.usuario in (
            select U.USUARIO
            from sr_usuario U where
            U.USUARIO = '$asesor' and
            U.STATUS_USUARIO = 'A'
            )";
    $result3 = $this->conn->fetch_array_matriz($this->conn->consulta($sql2));
    $ante = substr($result3[0]['FECHA'], 0, -2);
        $desp = substr($result3[0]['FECHA'],-2);
        $fecha_tope = $ante.'20'.$desp;

        $this->fecha_ultima_gestion = $fecha_tope.'[SEPAR2]';

    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    $sql = "SELECT DIA, DESCRIPCION FROM SR_CALENDARIO WHERE TRIM(DESCRIPCION)<>'DOMINGO' AND DIA >= '$Fecha_p' AND DIA <= '".$Fecha_u[0].$Fecha_u[1].$Fecha_u[2].$Fecha_u[3].$Fecha_u[4].$Fecha_u[5].'20'.$Fecha_u[6].$Fecha_u[7]."'";
    $result2 = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    $cadena_fechas = '';
    if(isset($result) || isset($result2)){
      if(isset($result))
        foreach($result as $valor)
          $cadena_fechas .= $valor['FECHA'].'||';
       // Separador entre las fechas de gestion y feriadas
      if(isset($result2)) {
        $cadena_fechas .= '%';   
        foreach($result2 as $valor)
          $cadena_fechas .= $valor['DIA'].'||';
      }
      echo $this->fecha_ultima_gestion.$cadena_fechas;
      exit();
    }
    echo 'No hay actividad';
    exit();
  }
  //===========================================================================================================================

  public function acessoDirecto($fecha, $asesor){
    if($asesor[0] == 'S')
      $sql = " SELECT DISTINCT  A.PERSONA
            FROM SR_AGENDA A WHERE
            A.USUARIO IN (
            SELECT U.USUARIO
            FROM SR_USUARIO U WHERE
            U.USUARIO_SUPERIOR = '$asesor' AND
            U.STATUS_USUARIO = 'A') AND
            A.FECHA = '$fecha'";
    else
      $sql = "SELECT DISTINCT A.PERSONA
              FROM SR_AGENDA A WHERE
              A.FECHA = '$fecha' AND
              A.USUARIO = '$asesor'";
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));
    $cadena_fechas = '';
    foreach($result as $valor){
          $cadena_fechas .= $valor['PERSONA'].',';
    }

    echo $cadena_fechas;

  }


  /**
   * Obtiene todas las fechas de actividad de los gestores relacionado con el supervisor
   * @param  String formato de fecha d/m/Y contiene la primera fecha del mes 01/xx/xxxx $Fecha_p
   * @param  String formato de fecha d/m/Y contiene la ultima fecha del mes 31/xx/xxxx $Fecha_u
   * @param  String Código del supervisor a buscar $asesor
   * @return Cadena  con las fechas de gestion separadas por || y 
   * otra cadena con las fechas de los dias feriados. estas dos cadenas estan unidas
   * con el simbolo %, para luego medite jquery separarlas dos cadenas. 
   */
  private function obtener_fechas_cal_todo($Fecha_p, $Fecha_u, $asesor) {
    // query para obtener las fechas que existen agendadas
    $sql = "SELECT trunc(A.FECHA) as fecha
            FROM SR_AGENDA A,  SR_USUARIO C
            WHERE A.USUARIO = C.USUARIO
            AND A.FECHA <= '".$Fecha_u[0].$Fecha_u[1].$Fecha_u[2].$Fecha_u[3].$Fecha_u[4].$Fecha_u[5].'20'.$Fecha_u[6].$Fecha_u[7]."'
            AND A.FECHA >= '$Fecha_p'
            AND C.USUARIO_SUPERIOR = '$asesor'
            AND C.STATUS_USUARIO = 'A'
            GROUP BY TRUNC(A.FECHA)
            ORDER BY fecha ASC";

    $sql2 = "SELECT trunc(min(distinct A.FECHA)) as fecha 
            from sr_agenda A where 
            A.usuario in (
            select U.USUARIO
            from sr_usuario U where
            U.USUARIO_SUPERIOR = '$asesor' and
            U.STATUS_USUARIO = 'A'
            )";
    $result3 = $this->conn->fetch_array_matriz($this->conn->consulta($sql2));
    $ante = substr($result3[0]['FECHA'], 0, -2);
        $desp = substr($result3[0]['FECHA'],-2);
        $fecha_tope = $ante.'20'.$desp;

        $this->fecha_ultima_gestion = $fecha_tope.'[SEPAR2]';
    $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql)); // Obtenemos los resultados
    // Query para obtener las fechas de los días feriados
    $sql = "SELECT DIA, DESCRIPCION FROM SR_CALENDARIO WHERE TRIM(DESCRIPCION)<>'DOMINGO' AND DIA >= '$Fecha_p' AND DIA <= '".$Fecha_u[0].$Fecha_u[1].$Fecha_u[2].$Fecha_u[3].$Fecha_u[4].$Fecha_u[5].'20'.$Fecha_u[6].$Fecha_u[7]."'";
    $result2 = $this->conn->fetch_array_matriz($this->conn->consulta($sql));// Obtenemos los resultados
    $cadena_fechas = ''; //Decaramos la variable la cual sera la cadena resultante.
    if(isset($result) || isset($result2) || isset($result3)){        // Verificamos que existan datos 
      if(isset($result)){                       // Verificamos que tenemos datos de las cuentas agendadas
        foreach($result as $valor)              // Formamos la cadena de fechas agendadas separada por ||
          $cadena_fechas .= $valor['FECHA'].'||';
      }
      if(isset($result2)) {                       // Verificamos si existen datos de ser cierto separamos esta cadena de la otra con %
        $cadena_fechas .= '%';                    // Separador %. nos queda asi: [cadena_fechas_agendadas]%[cadena_fechas_feriadas]
        foreach($result2 as $valor)               // Formamos la cadena de los dias feriados
          $cadena_fechas .= $valor['DIA'].'||';
      }
      echo $this->fecha_ultima_gestion.$cadena_fechas;                        // Si existe la cadena con valorres la escribimos para que la reciba mediante Ajax
      exit();                                     // Matamos el proceso para que no imprima mas de lo debido..
    }
    echo 'No hay actividad';                     // De no existir valores devuelto por los querys mostramos esto
    exit();                                       // Matamos el proceso para que no imprima mas de lo debido..
  }
  //===========================================================================================================================
}

?>
