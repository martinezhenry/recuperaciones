<?php
@session_start();
require_once($_SESSION['ROOT_PATH']."/modelo/conexion.php");
require_once 'c_autorizar.php';


/**
* Clase principal del controlador llamadas
*
* Atributos:$cedula
*           $conn
*           $id_person
*           $combina
*
*
* Metodos:  obtener_telefonos()
*           get_id_persona()
*
* Desarrollo: Luis Peña
* Copyright VPC 2014
*/

Class C_llamada extends Oracle {
  private $cedula;
  private $conn;      // Para la conexion de la base de datos
  private $id_person; // Para almacenar el id_persona
  private $numeros = array();//Almacena los numeros de las personas
  private $combina = array();   // Guarda la secuencia de los estatus traidos de la base de datos
  private $combina2;
  private $combinaString; //Guarda la combinacion en cadena de los status;
  //===== CONSTRUCTOR =====
  public function __construct($id_persona='') {
    $this->conn = new Oracle($_SESSION['USER'], $_SESSION['pass']);
    $this->id_person = $id_persona;
  }
  //======================================
  public function set_id_person($ID){$this->id_person = $ID;}
  public function get_id_person(){return $this->id_person;}
  public function set_numeros($numeros){$this->numeros = $numeros;}
  public function set_combina($combinacion){if(count($this->combina)==0) { $this->combina = $combinacion; }else{}}
  public function get_combina(){echo $this->combinaString;}
  

  //===== METODOS =====

  /**
  * Definicion del metodo para obtener los números de teléfonos de la persona
  * Parametro:  cédula del participante
  * Salida:     cadena Guardado con Exito
  */

  public function obtener_telefonos($cedula) {
      $id = 35;
      if (!autorizar($id, $_SESSION['USER'])){
          return false;
          
      } else {
        //SQL para la cunsulta
        $sql = "SELECT J.PERSONA, J.COD_AREA,J.TELEFONO, J.STATUS_TELEFONO FROM tg_persona_tel_adi j WHERE J.PERSONA = '$cedula'"; /*Buscar el id persona antes*/

        //Realizamos la consulta en la base de datos Oracle 
        //Que nos devuelve una matriz con los resultados    
        $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));

        //Retornamos los resultados
        return $result;
      }
  }

  /**
  * Guardamos los cambios que se hayan hecho en la tabla de telefonos
  * Parametro:  secuencia de la combinacion de los estatus
  * Salida:     Una matriz con toda la descripción de los números de las personas
  */
  public function guardar_status($combinaciones) {
      $id = 36;
      if (!autorizar($id, $_SESSION['USER'])){
          return false;
          
      } else {
        $this->combinaString = $combinaciones;//Guardamos la cadena de la combinacion de los status
        $num2 = array();//Aux para obtener los numeros de telefonos por separados
        $cont = 0;// contador para ubicarnos en el array
        foreach($this->numeros as $num){//En cada posicion del array numeros ejecutamos explode para separar el codigo de area del telefono
          $num2[$cont] = explode('%', $num);
          $cont++;
        }
        //Obtenemos todos los numeros en una matriz

        $combinaciones =  explode('/', $combinaciones);// realizamos un explode de la cadena con la secuencia de status
        //Recorremos los arrays para identificar cual status estaá modificado
        $veri = false;


        for($x=0;$x<count($this->combina);$x++){
          $num2[$x][0] = str_replace('&', '', $num2[$x][0]);
          $num2[$x][1] = str_replace('/', '', $num2[$x][1]);

          //echo $this->combina[$x]." == ".$combinaciones[$x]."\n";
          if (!($this->combina[$x]==$combinaciones[$x])) {
            $sql = "UPDATE tg_persona_tel_adi j  SET J.STATUS_TELEFONO = '".$combinaciones[$x]."' WHERE J.PERSONA = '".$this->get_id_person()."' and J.COD_AREA||J.TELEFONO = trim('".$num2[$x][0].$num2[$x][1]."')";
              //Realizamos la consulta en la base de datos Oracle     
              $this->conn->consulta($sql);
              $veri = true;
              $this->combina[$x] = $combinaciones[$x];
          }
          else
          {/*No hace nada*/}


          //Comparando los arrays en sus respectivas posiciones determinamos si hay cambios! y si existe! ejecutamos el sql para la modificacion

        }

        if($veri){
          echo "Guardado con Exito";
          //header('location: ../vista/llamadas.php');

        }
        else
          echo "No se ha realizado un cambio.\n Por favor verifique los datos o Cancele el proceso";//Avisamos que todo va bien
        exit();// para que no continue ejecutando;
  }
  }


public function cargarTabla($idpersona)
{
    $id = 37;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        return false;
        
    } else {
      
        
        $table = '
        <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" class="display dataTable" id="llamadas-Tabla">
                        <thead>
                          <tr>
                          <th width="100">'.$e['tll_tab1'].'</th>
                          <th width="100">'.$e['tll_tab2'].'</th>
                          <th width="20px">'.$e['tll_tab3'].'</th>
                          </tr>
                        </thead>
                        <tbody>   
                      ';                  
                    $datos = $this->obtener_telefonos($idpersona);//Se coloca el id_persona que este en session
                    $x = 0;
                    $combinacion = array();
                    $numeros = array();
                    if (is_array($datos)){
                    foreach($datos as $r){
                      //Por los momentos no se muestra el $r[persona]
                      $sele = false; 
                      if($r['STATUS_TELEFONO']=='A')
                        $sele = true;

                      $table = $table."<tr class='gradeA' width: 25;\"><td>".$r['COD_AREA']."</td><td>".$r['TELEFONO']."</td><td><label class='select'><select id=\"select_status$x\" class='select_llamadas' disabled> "; 
                      if($sele)
                        $table = $table."<option value='A'selected>Activo</option>      <option value='I'>Inactivo</option>";
                      else
                        $table = $table."<option value='A' >Activo</option>              <option value='I' selected>Inactivo</option>";

                      $table = $table."</select></label></td></tr>";
              //Guardamos el ID de la persona
              if($r['COD_AREA']==NULL)
                $numeros[$x] = '&%'.$r['TELEFONO'].'/';
              else
                $numeros[$x] = $r['COD_AREA'].'%'.$r['TELEFONO'];//Guardamos los numeros telefonicos de la persona.
              $combinacion[$x] = $r['STATUS_TELEFONO']; //Creamos una array que contendra una combinacion de los estatus traidos de la base de datos
              $x++;
                    }
                    } // FIN DEL IF is_array
                    $this->set_id_person($idpersona);
                    $this->set_numeros($numeros);
                    $this->set_combina($combinacion);
                                     //registramos la combinacion en el objeto

        $table = $table.'</table>';

        echo $table;
      }
}

  //==================
//==================
  function guardarTelefono($persona, $cod_area, $telefono, $usuario){
      $id = 57;
      if (!autorizar($id, $_SESSION['USER'])){
          return false;
          
      } else {
        $fecha = date("d/m/Y");


        $sql = "INSERT INTO TG_PERSONA_TEL_ADI D
            (PERSONA, COD_AREA, TELEFONO, FUENTE, STATUS_TELEFONO, USUARIO_INGRESO, FECHA_INGRESO)
            VALUES
            ('$persona','$cod_area','$telefono','1','A','$usuario','$fecha')";

        $this->conn->consulta($sql);

    }
  
    return;
  
  }
  
}


//Copyright VPC 2014
?>