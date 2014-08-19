<?php

/*
Clase para para la conexion con la base de datos
*/
class oracle{
    
 //private $servidor = '192.20.15.77/vpc';
 //static $ser = '192.20.15.77/vpc';
 private $servidor = '192.20.15.29/orcl.oficinacentral.vpc2.com';
 static $ser = '192.20.15.29/orcl.oficinacentral.vpc2.com';
 private $conexion;
 private $usuario;
 private $password;
 private $paso;
 
  //private $usuario = "VPCTOTAL";
  //private $password = "cable";
 
 function __construct($user, $pass){
     
     @$conex = oci_connect($user, $pass, $this->servidor);
     
     if (!$conex){
         
         $this->paso = oci_error()['code'];
        
         
     } else {
         
     $this->usuario = $user;
     $this->password = $pass;
     $this->conexion = $conex;
     $this->paso = true;
     $this->disconnect();
    
     }
   
     //return $this->usuario."  P ".$this->password;
     
 }
 
 function get_paso() { return $this->paso; }
 function get_servidor() { return $this->servidor; }
 function get_conexion() { $this->conexion = oci_connect($this->usuario, $this->password, $this->servidor, 'UTF8_UNICODE'); }
 function get_ip_conex() { return $this->getRealIP(); }
 
 /* METODO PARA CONECTAR CON LA BASE DE DATOS*/
  function conectar()
 {

    if(!isset($this->conexion))
    {
    @$this->conexion = oci_connect($this->usuario, $this->password, $this->servidor, 'UTF8_UNICODE'); //USUARIO, PASSW, la IP del servidor y la instancia de la base de datos sin esta ultima esto no funciona   
            if (!$this->conexion) {
            @$e = oci_error(); //muestra un mensaje de error en caso de no conectarse a oracle 10G 
            @trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            exit();
            } 

    }
    
      
 }//Fin funcion  


 
      /* METODO PARA CERRAR LA CONEXION A LA BASE DE DATOS */
  function disconnect()
 {
  oci_close($this->conexion);
 }
 
  /* METODO PARA REALIZAR UNA CONSULTA 
 INPUT:
 $sql | codigo sql para ejecutar la consulta
 OUTPUT: $result
 */
  function consulta($sql)
 {
    //echo $sql;
   $inicio = microtime(true);
	$this->conectar();
    
		$query = oci_parse($this->conexion,$sql);
		//Ejecutamos la sentencia en oracle 
                
		$resultado = oci_execute($query);
		if(!$resultado){
		return 'Oracle 10G Error: '.oci_error()['code'];
		exit();
		}
                
    $fin = microtime(true);
    $total = $fin-$inicio;
    $total = str_replace(".", ",", $total);
    
    
    $ip = $this->getRealIP();
    //echo "INSERT INTO SR_SEGUIMIENTO (QUERY, USUARIO, TIEMPO_EJECUCION) VALUES (':sql', '$this->usuario', '$total')";
    $q = oci_parse($this->conexion,"INSERT INTO SR_SEGUIMIENTO (QUERY, USUARIO, TIEMPO_EJECUCION, DIRECCION_IP, SISTEMA) VALUES (:sql, '$this->usuario', '$total', '$ip', 'RECUPERACIONES')");
    oci_bind_by_name($q, ":sql", $sql);
    //Ejecutamos la sentencia en oracle 
    $r = oci_execute($q);
    $this->disconnect();
   // echo $sql;
    unset($q, $r);
    return $query;
 }
 
 /*METODO PARA CREAR ARRAY DESDE UNA CONSULTA
 INPUT: $result
 OUTPUT: array con los resultados de una consulta
 */
  function fetch_assoc($result){
       $this->conectar();
		if(!is_resource($result)) return false;
		return oci_fetch_assoc($result);
		oci_free_statement($result);  //liberamos memoria de oracle 
	$this->disconnect();
 }
 
 /*METODO PARA CREAR ARRAY DESDE UNA CONSULTA
 INPUT: $result
 OUTPUT: array con los resultados de una consulta
 */
  function fetch_array($result){
	$this->conectar();
		if(!is_resource($result)) return false;
		return oci_fetch_array($result);
		oci_free_statement($result);  //liberamos memoria de oracle 
	$this->disconnect();
 }
 
 /*METODO crear una array con varios datos 
INPUT: $result
OUTPUT: array con los resultados de una consulta
*/
	public function fetch_array_matriz($result){
		$this->conectar();
		if(!is_resource($result)) return false;
		while($fila = oci_fetch_array($result)){
			$filas[] = $fila;
		}
		if(isset($filas))
			return($filas);
		oci_free_statement($result);
		unset($filas); //liberamos memoria de oracle 
		$this->disconnect();
	}
        
        
        private function getRealIP() {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))
                return $_SERVER['HTTP_CLIENT_IP'];

            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];

        return $_SERVER['REMOTE_ADDR'];
    }
    
    
       public function filas($result){
        //$this->conectar();
        $filas = oci_fetch_all($result, $output);
        //$this->disconnect();
        unset($output);
        return $filas;
        
        
    }
    

}
?>
