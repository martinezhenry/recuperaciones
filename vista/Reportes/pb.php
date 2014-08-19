<?php
session_start();
include "../../modelo/conexion.php";
$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);
//$_GET['prueba'] = "1";
//$_GET['otro'] = "2";

 /*  $_GET['cargarAsignaciones'];
   $_GET['usuario'];
   $_GET['filtro'];
   $_GET['Inicio'];
   $_GET['Fin'];
   $_GET['agrega'];
   $_GET['identificador'];*/
/*while (current($_GET)){
echo key($_GET). "  fun key<br>";
//echo $key;
next($_GET);
}
include "../../controlador/c_portafolio.php";

//url("../../controlador/c_portafolio.php?prueba=1");

*/

$grupoGestion = "E";
$tablaGestion = "3";
$codigoGestion = "702";


$sql = "insert into sr_gestion
        (gestion, cliente, cartera, cuenta, usuario_gestor, tabla_gestion, grupo_gestion, codigo_gestion, descripcion, fecha_proxima_gestion, fecha_promesa, hora_promesa, monto_promesa, usuario_ingreso, fecha_ingreso, usuario_ult_mod, fecha_ult_gestion, telef_cod_area, telef_gestion, hora_proxima_gestion, nombre_contacto, status_mercantil, parentesco, apellido_contacto, abonos, eliminar)
        values
        ('14125843', '10025', '8', '2000551', 'T5711', '3', 'E', '702', '', '07/04/2014', '08/04/2014', '', '1000', 'sesion', '07/04/2014', 'sesion', '', '1111', '1111111', '', 'p', '', '1', 'p', '', 'N')";


preg_match('/\((.+)\)/', $sql, $campos);
$campos = explode(",", $campos[1]);

preg_match('/\'(.+)\'/', $sql, $valores);

$valores = str_replace("'", "", $valores[0]);

$valores = explode(",", $valores);


for ($i=0; $i < count($campos); $i++){
    
    $completa[$campos[$i]] = $valores[$i];
    
    
}





echo $sql;
echo "<br><br>";
$respuesta = validacion($completa, $tablaGestion, $grupoGestion, $codigoGestion);

if ($respuesta == ""){
    
    echo "haz el insert";
    
} else {
    
    echo $respuesta;
    
}

$agarra = "";
echo "<br><br>";
$operacion = "(|campo1|*2)/|campo2|";

//preg_match('/\|(.+)\|/', $operacion, $cam);
//print_r ($cam);
echo $operacion;
$empieza =0;/*
for ($i =0; $i < strlen($operacion);$i++){
    
    if ($operacion[$i] == "|"){
        
        $empieza++;
        
    }
    
    if ($empieza == 1 && $operacion[$i] != "|"){
        
        $agarra .= $operacion[$i];
        
    }
    
    if ($empieza == 2){
        
        $arr[] = $agarra;
        $agarra = "";
        $empieza = 0;
        
    }
    
    
}
*/
echo "<br>";
//print_r($arr);
echo "<br>";

$v = array('campo1' => '200',
           'campo2' => '100' 
          );
  /*        $i=0;
          foreach ($v as $clave => $value) {
              
             if ($clave == $arr[$i])
             {
                 $operacion = str_replace($arr[$i], $value, $operacion);
             }
    $i++;
}*/


          $i=0;
          foreach ($v as $clave => $value) {
         
                 $operacion = str_replace($clave, $value, $operacion);
            
    $i++;
}

print_r($v);

echo "<br>";

$operacion = str_replace("|", "", $operacion);

echo "operacion: ".$operacion;


function validacion($arreglo, $tablaGestion, $grupoGestion, $codigoGestion){
    global $conex;
    $st = $conex->consulta("SELECT * FROM SR_PARAMETROS WHERE TABLA_GESTION = '$tablaGestion'
                        AND GRUPO_GESTION = '$grupoGestion'
                        AND COD_GESTION = '$codigoGestion'");
    $respuesta = "";
while ($fila = $conex->fetch_array($st)){
    
    //print_r($fila);
    
    foreach ($arreglo as $key => $value) {
            $key = trim($key);
            $fila['CAMPO'] = trim($fila['CAMPO']);
            $value = trim($value);
            $fila['VALOR'] = trim($fila['VALOR']);
        if (strnatcmp((strtoupper($fila['CAMPO'])), (strtoupper($key))) === 0){
            
            
            echo "Existe concidencia campo = ". $key;
            echo "<br>";
            echo $fila['OPERADOR'];
            echo "<br>";
            echo $fila['VALOR'];
            $date = "date('Y/m/d')";
            echo "<br>";
            //printf($date);
            
            (strnatcmp($fila['VALOR'],"SYSDATE") === 0) ? $fila['VALOR'] = date("d/m/Y") : "";
            //(strnatcmp($fila['VALOR'],"") === 0) ? $fila['VALOR'] = date("d/m/Y") : "";
            //  $fila['OPERADOR'] = ">";
            
            if (strnatcmp($fila['OPERADOR'],"=") === 0)  {
                
                     if (strnatcmp($value, $fila['VALOR']) == 0){
                
                        $respuesta .= "<br>".$fila['MENSAJE'];
                        
                
                
                    }
                
                    } else if (strnatcmp($fila['OPERADOR'],">") === 0){
                        
                     
                        if (strnatcmp($value, $fila['VALOR']) == -1 || strnatcmp($value, $fila['VALOR']) == 0){
                
                       $respuesta .= "<br>".$fila['MENSAJE'];
                
                
                    }
                        

                    } else if (strnatcmp($fila['OPERADOR'],"<") === 0){

                        if (strnatcmp($value, $fila['VALOR']) == 1 || strnatcmp($value, $fila['VALOR']) == 0){
                
                        $respuesta .= "<br>".$fila['MENSAJE'];
                
                
                    }

                    }
   
       
            
            
        } else {
            
          //   echo "No hay comvidencia";
            
            
        }
        
        
        
        
    }
    
    
}
    
return $respuesta;
    
}




?>