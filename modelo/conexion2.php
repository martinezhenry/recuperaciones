<?php

/*
Clase para para la conexion con la base de datos
*/
class oracle{
    
 //private $servidor = '192.20.15.77/vpc';
 //static $ser = '192.20.15.77/vpc';
 private $servidor = '192.20.15.77/vpc';
 static $ser = '192.20.15.77/vpc';
 private $conexion;
 private $usuario;
 private $password;
 private $paso;
 private $tns;
 
 
  //private $usuario = "VPCTOTAL";
  //private $password = "cable";
 
 function __construct($user, $pass){
     
      
     $this->tns = "  
            (DESCRIPTION =
                (ADDRESS_LIST =
                  (ADDRESS = (PROTOCOL = TCP)(HOST = 192.20.15.77)(PORT = 1521))
                )
                (CONNECT_DATA =
                  (SERVICE_NAME = vpc)
                )
              )
       ";
    
     $conex = new PDO("oci:dbname=(DESCRIPTION =
                (ADDRESS_LIST =
                  (ADDRESS = (PROTOCOL = TCP)(HOST = 192.20.15.77)(PORT = 1521))
                )
                (CONNECT_DATA =
                  (SERVICE_NAME = vpc)
                )
              )",$user,$pass);
          
         
     $this->usuario = $user;
     $this->password = $pass;
     $this->conexion = $conex;
     $this->paso = true;
     $this->disconnect();

 
 }
 
 
 function get_paso() { return $this->paso; }
 function get_servidor() { return $this->servidor; }
 function get_conexion() { $this->conexion = new PDO("oci:dbname=(DESCRIPTION =
                (ADDRESS_LIST =
                  (ADDRESS = (PROTOCOL = TCP)(HOST = 192.20.15.77)(PORT = 1521))
                )
                (CONNECT_DATA =
                  (SERVICE_NAME = vpc)
                )
              )",$this->usuario,$this->password); }
 
 /* METODO PARA CONECTAR CON LA BASE DE DATOS*/
  function conectar()
 {

    if(!isset($this->conexion))
    {
        try{
            $this->conexion = new PDO("oci:dbname=(DESCRIPTION =
                (ADDRESS_LIST =
                  (ADDRESS = (PROTOCOL = TCP)(HOST = 192.20.15.77)(PORT = 1521))
                )
                (CONNECT_DATA =
                  (SERVICE_NAME = vpc)
                )
              )",$this->usuario,$this->password); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
        }catch(PDOException $e){
            echo ($e->getMessage());
          
        }

    }
    
       
 }//Fin funcion  


 
      /* METODO PARA CERRAR LA CONEXION A LA BASE DE DATOS */
  function disconnect()
 {
  unset($this->conexion);
 }
 
  /* METODO PARA REALIZAR UNA CONSULTA 
 INPUT:
 $sql | codigo sql para ejecutar la consulta
 OUTPUT: $result
 */
  function consulta($sql)
 {
  
   $inicio = microtime(true);
	$this->conectar();
    
		$query = $this->conexion->prepare($sql);
		//Ejecutamos la sentencia en oracle 
                
		$resultado = $query->execute();
		/*if(!$resultado){
		return 'Oracle 10G Error: '.oci_error()['code'];
		exit();
		}
                */
    $fin = microtime(true);
    $total = $fin-$inicio;
    $total = str_replace(".", ",", $total);
    
    
    $ip = $this->getRealIP();
    //echo "INSERT INTO SR_SEGUIMIENTO (QUERY, USUARIO, TIEMPO_EJECUCION) VALUES (':sql', '$this->usuario', '$total')";
    $q = $this->conexion->prepare("INSERT INTO SR_SEGUIMIENTO (QUERY, USUARIO, TIEMPO_EJECUCION, DIRECCION_IP, SISTEMA) VALUES ('$sql', '$this->usuario', '$total', '$ip', 'RECUPERACIONES')");
   // oci_bind_by_name($q, ":sql", $sql);
    //Ejecutamos la sentencia en oracle 
   $r = $q->execute();
    $this->disconnect();
 
    unset($q, $r);
    return $query;
 }
 
 /*METODO PARA CREAR ARRAY DESDE UNA CONSULTA
 INPUT: $result
 OUTPUT: array con los resultados de una consulta
 */
  function fetch_assoc($result){
       $this->conectar();
		
		return $result->fetch();
		
	$this->disconnect();
 }
 
 /*METODO PARA CREAR ARRAY DESDE UNA CONSULTA
 INPUT: $result
 OUTPUT: array con los resultados de una consulta
 */
  function fetch_array($result){
       $this->conectar();
		
		return $result->fetch();
		
	$this->disconnect();
 }
 
 /*METODO crear una array con varios datos 
INPUT: $result
OUTPUT: array con los resultados de una consulta
*/
	public function fetch_array_matriz($result){
            
            $this->conectar();

                 while($fila = $result->fetch()){
                 $filas[] = $fila;
         }
         $this->disconnect();
         return $filas;
		
	

	}
        
        
        private function getRealIP() {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))
                return $_SERVER['HTTP_CLIENT_IP'];

            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }
    
    
    public function filas($result){
        $this->conectar();
        $filas = $result->fetchAll();
        $this->disconnect();
        return count($filas);
        
        
    }
    

}
?>
