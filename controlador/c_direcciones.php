<?php
@session_start();
require_once($_SESSION['ROOT_PATH']."/modelo/conexion.php");
require_once 'c_autorizar.php';

/**
* Clase principal del controlador de direcciones
*
* Atributos:
*           $conn
*           $id_person
*           $combina
*           $directions
*           $combina
*
* Metodos:  obtener_telefonos()
*           get_id_persona()
*
* Desarrollo: Luis Peña
* Copyright VPC 2014
*/
Class C_direccion extends Oracle {
  private $conn;                   // Para la conexion de la base de datos
  private $id_person;              // Para almacenar el id_persona
  private $directions = array();   //Almacena los numeros de las personas
  private $combina    = array();   // Guarda la secuencia de los estatus traidos de la base de datos
  

  //===== CONSTRUCTOR =====
  public function __construct($id_persona='') {
    $this->conn = new Oracle($_SESSION['USER'], $_SESSION['pass']);
    $this->set_id_person($id_persona);
  }

  //==========GET's & SET's=================
  public function set_id_person($ID          )  {$this->id_person = $ID;}
  public function get_id_person(             )  {return $this->id_person;}
  public function set_direcciones($directions)  {$this->directions = $directions;}
  public function set_combina($combinacion   )  {if(count($this->combina)==0) { $this->combina = $combinacion; }else{}}
  public function get_combina(               )  {echo $this->combinaString;}

  public function get_direccion($pos=''      ){
    if($pos == '')
      return $this->directions;
    else
      return $this->directions[$pos];
  }
  
  //===== MÉTODOS =====

  /**
  * Este método se encarga de extraer la direcciones de la persona
  * que actualmente está en session_start()
  *
  * Parametro:  cédula de la persona
  * Salida:     cadena Guardado con Exito
  */

  public function obtener_direcciones($cedula) {
      $id = 27;
      if (!autorizar($id, $_SESSION['USER'])){
          return false;
      } else {
        //SQL para la cunsulta
        $sql = "SELECT D.PERSONA, D.DIRECCION, D.STATUS_DIRECCION, D.ID_DIRECCION FROM TG_PERSONA_DIR_ADI D WHERE D.PERSONA = '$cedula'"; /*Buscar el id persona antes*/

        //Realizamos la consulta en la base de datos Oracle 
        //Que nos devuelve una matriz con los resultados    
        $result = $this->conn->fetch_array_matriz($this->conn->consulta($sql));

        //Retornamos los resultados
        return $result;
      }
  }

  /***
  * Este Método se encarga de guardar en base de datos los status modificados
  *
  * Parametro:  secuencia de la combinacion de los estatus
  * Salida:     cadena de confirmacion de de resultado
  */
  public function guardar_status($combinaciones) {
    /**  
     * Realizamos un explode de la cadena con la secuencia de status
     * recorremos los arrays para identificar cual status estaá modificado
     */
   $id = 28;
   if (!autorizar($id, $_SESSION['USER'])){
       return false;
       
   } else {

        $combinaciones =  explode('/', $combinaciones);

        $veri = false; // Variable para verificar si se ha ejecutado un query en la base de datos

        // |descomentar para ver datos| print_r($this->combina);
        // |descomentar para ver datos| print_r($combinaciones);

        /**
        * Recorremos la secuencia de los status para identificar
        * cual valor ha cambiado
        */
        for($x=0;$x<count($this->combina);$x++) {
          /**
          * Verificamos si los valores son iguales o no.
          * Si no son iguales, ejecuta el query debido a que hubo cambios
          * Si son iguales no hace nada omite el query. 
          */ 
          if (!($this->combina[$x]==$combinaciones[$x])) {
            $sql = "UPDATE TG_PERSONA_DIR_ADI D  SET D.STATUS_DIRECCION = '".$combinaciones[$x]."' WHERE D.PERSONA = '".$this->get_id_person()."' and D.DIRECCION = '".$this->get_direccion($x)."'";

              //Realizamos la consulta en la base de datos Oracle     
              $this->conn->consulta($sql);
              $veri = true; //identificador para saber si se ha ejecutado un query
              $this->combina[$x] = $combinaciones[$x]; // Actualizamos la secuencia del objeto con la ultima que se envio
          }
          else
          {/*No hace nada*/}
        }
        /**
        * Verificamos si se ha ejecutado un query
        */
        if($veri)
          echo "Guardado con Exito"; //retornamos el mensaje de exito
        else
          echo "No se ha realizado un cambio.\n Por favor verifique los datos o Cancele el proceso";// retorna en caso de que no se haya cambiado nada

        exit();// para que no continue ejecutando;

  }
  }

  /**
  *
  * Método para cargar la tabla que contiene las direcciones actualizadas
  *
  * Entrada: ID de la persona
  * Salida:  tabla completa con html incorporado
  */
  public function cargarTabla($idpersona) {
    /**
    * $table es una cadena que contendra toda la tabla con los datos cargados 
    */
      $id = 29;
      global $e;
      if (!autorizar($id, $_SESSION['USER'])){
          return false;
          
      } else {
        $table = '
                    <table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" class="display dataTable" id="direcciones-Tabla">
                    <thead>
                            <tr>
                                    <th width="300px">'.$e['dirt_tab1'].'</th>
                                    <th width="20px">'.$e['dirt_tab2'].'</th>
                            </tr>
                    </thead>
                    <tbody>';                  
                        $datos = $this->obtener_direcciones($idpersona);//Se coloca el id_persona que este en session
                        $x = 0;                 //contador que ayuda a posicionar en un array los valores para realizar la secuencia
                        $combinacion = array(); //Guarda en cada posicion el valor de los selects
                        $directions  = array(); //Guarda en cada posicion una direccion de la persona
                        if (is_array($datos)){
                        foreach($datos as $r) {
                          $sele = false;        //Para identificar que option imprimirá y su seleted 
                          if($r['STATUS_DIRECCION']=='A')
                            $sele = true;

                          $table = $table."<tr class=\"gradeA\"><td>".$r['DIRECCION']."</td><td><label class='select'><select id=\"dir_select_status$x\" class='select_llamadas' disabled> "; 
                          if($sele)             //Si status_direccion es A, imprime A como selected
                            $table = $table."<option value='A'selected>Activo</option> <option value='I'>Inactivo</option>";
                           else                 //Si status_direccion es I, imprime I como selected
                             $table = $table."<option value='A' >Activo</option> <option value='I' selected>Inactivo</option>";
                          $table = $table."</select></label></td></tr>";
                          $directions[$x] = $r['DIRECCION'];         //Guarda las direcciones de la persona
                          $combinacion[$x] = $r['STATUS_DIRECCION']; //Guarda en cada indice el valor de los status 
                          $x++;                                      //Contamos cuantos campos se ha impreso
                        }

                        $this->set_id_person($r['PERSONA']);         //Guardamos el ID de la persona

                        $this->set_direcciones($directions);        //Guardamos las direcciones en un atributo de la clase
                        }
                        $this->set_combina($combinacion);           //Guardamos la combinacion en un atributo de la clase

                        $table = $table.'

                                    </tbody></table>

                                    <table style="display: none;" cellpadding="0" cellspacing="0" border="0" class="display" id="example0" width="100%">
                                        <tfoot id="piedirecciones-Tabla">
                                                <tr>
                                                        <th></th>
                                                        <th></th>

                                                </tr>
                                        </tfoot>
                                </table>



                             ';            //Terminamos de crear la tabla
        echo $table;   //retormanos la tabla creada
        echo "<script> var cantidad_dir = $x; </script>";
  }
}
  //==================
  function guardarDireccion($persona, $direcciones, $usuario){
      $id = 30;
      if (!autorizar($id, $_SESSION['USER'])){
          return false;
          
      } else {
        $fecha = date("d/m/Y");

        $direcciones = explode(",", $direcciones);

        foreach ($direcciones as $direccion){

        $sql = "INSERT INTO TG_PERSONA_DIR_ADI D
            (PERSONA, DIRECCION, FUENTE, STATUS_DIRECCION, USUARIO_INGRESO, FECHA_INGRESO)
            VALUES
            ('$persona','$direccion','2','A','$usuario','$fecha')";

        $this->conn->consulta($sql);

        }


    }
  

  
  }
  
}


//Copyright VPC 2014
?>