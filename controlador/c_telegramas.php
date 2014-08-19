<?php
session_start();
require_once "../modelo/conexion.php";
require_once "c_direcciones.php";
($_SESSION['lang'] === 'es') ? require_once $_SESSION['ROOT_PATH'].'/vista/language/es.conf' : require_once $_SESSION['ROOT_PATH'].'/vista/language/en.conf';

$conex = new oracle($_SESSION['USER'], $_SESSION['pass']);


/**
 *    
 *  Conecta con la BD para obtener los registros de los tipos de aviso 
 *  y crea un selec que los contendra
 * 
 * 
 **/
function cargarTipoAviso()
{
           $id = 49;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
        // Variable global de conexion
        global $conex;
        // Consulta a ejecutar
        $sql = "select * from sr_aviso_cobro";
        // Se ejecuta la consulta
        $st = $conex->consulta($sql);
        // Se obtiene el numero de filas devueltas
        $numrow = $conex->filas($st);

        // Se verifica el numero de filas sea mayor de 0
        if ($numrow > 0) {
            // Se ejecuta la consulta
            $st = $conex->consulta($sql);
            // Se recorre las filas devueltas
            while ($fila = $conex->fetch_array($st)) {
                // Se asigna las filas en un arreglo
                $aviso[] = $fila;
            }
            // Se devuelve el arreglo con las filas obtenidas
            return $aviso;
            // Se destruyen varibles
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else { // En caso de que en numero de filas devueltas sea 0
            return $aviso = "ERROR: No existen Avisos";
        }
    }
}


/**
 *    
 *  Carga los telegramas de una persona de la BD
 * 
 *    PARAMETROS:
 *      -- $persona = identificador de la persona de la desea buscar los datos
 * 
 **/
function cargarTelegramas($persona)
{
    $id = 50;
    global $e;
    if (!autorizar($id, $_SESSION['USER'])){
        
        return false;
    } else {
        // Consulta a ejecutar
         $sql = "select t.impreso, t.id_telegrama, t.fecha, a.texto from sr_telegramas t, sr_aviso_cobro a 
                 where T.TIPO_AVISO = A.TIPO_AVISO and persona = '$persona'"; 
         // Variable global de conexion
         global $conex;
         // Se ejecuta la consulta
        $st = $conex->consulta($sql);
        // Se obtiene el numero de filas devueltas
        $numrow = $conex->filas($st);

        // Se verifica el numero de filas sea mayor de 0
        if ($numrow > 0) {
            // Se ejecuta la consulta
            $st = $conex->consulta($sql);
            // Se recorre las filas devueltas
            while ($fila = $conex->fetch_array($st)) {
                // Se asigna las filas en un arreglo
                $telegramas[] = $fila;
            }
             // Se devuelve el arreglo con las filas obtenidas
            return $telegramas;
            // Se destruyen varibles
            unset($output);
            unset($sql);
            unset($st);
            exit();
        } else { // En caso de que en numero de filas devueltas sea 0
            return $telegramas = $e['not_tab11'];
        }
    }
}



    //==========================================================================
    //
    //                              GET
    //
    //==========================================================================

// Se verifica que se recibe una varible aviso por el metodo GET
if (isset($_GET['aviso'])) //===================================================
{
    // Se llam la funcion cargarTipoAviso()
    $avisos = cargarTipoAviso();
    // Se comprueba que el resultado devuelto sea un arreglo
    if (is_array($avisos))
    {  
        // Se imprime el resultado en un select
        echo "<select onchange=\"colocarTexto(document.getElementById('tipoAvisoTexto'), this.options[this.selectedIndex].text);\" class='selectEditar' style='width: 200px;' name = 'tipoAvisos' id='tipoAvisos'>
         
                <option selected disabled>Seleccione...</option>
            
             ";
        
        foreach ($avisos as $value)
        {
            
            echo "<option value='".$value['TIPO_AVISO']."'>".$value['TEXTO']."</option>";
            
        }
        
        echo "</select>";
        exit();
        
    } else // En caso de que el valor obtenido no sea una arreglo
        
    {
        echo "<label class='alerta'>$avisos</label>";
    }
    
    // Se verifica que se recibe una varible enviar por el metodo GET
} else if (isset($_GET['enviar'])) //===========================================
{
    global $conex;
    
    // Se obtienen los valores
    $tipoAviso = $_GET['tipoAviso'];
    $persona = $_GET['persona'];
    $idDireccion = $_GET['idDireccion'];

    
    // Query a ejecutar para obtener identificador a guardar
    $sql = "Select SQ_TELEGRAMAS.nextval from dual";
    // Se ejecuta el query
    $st = $conex->consulta($sql);
    // Se obtiene el resulta en una arreglo
    $value = $conex->fetch_array($st);
    // Se le asigna el valor del arreglo a una variable
    $idTelegrama = $value[0];
    // Query a ejecutar para guardar un registro
    $sql = "insert into sr_telegramas
        (id_telegrama, tipo_aviso, fecha, impreso, persona, id_direccion)
        values ('$idTelegrama', '$tipoAviso', '".  date("d/m/Y")."', 'N', '$persona', '$idDireccion')";
    // Se ejecuta el query
    $st = $conex->consulta($sql);
   // Se imprime mensaje a mostrar
    echo "Guardado";
    // Se destruyen las varibles
    unset($output);
    unset($sql);
    unset($st);
    exit();

    // Se verifica que se recibe una varible cargarTelegramas por el metodo GET
} else if (isset ($_GET['cargarTelegramas'])) //================================
{
    $persona = $_GET['persona'];
    // Se llam la funcion cargarTelegramas()
    $telegramas = cargarTelegramas($persona);
    // Se comprueba que el resultado devuelto sea un arreglo
    if (is_array($telegramas))
    {
        
        // Se imprime el resultado en una tabla
        echo '<table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="telegramas-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>'.$e['tt_tab1'].'</th>
                                                        <th>'.$e['tt_tab2'].'</th>
                                                        <th>'.$e['tt_tab3'].'</th>
                                                        <th>'.$e['tt_tab4'].'</th>
                                                        

                                                </tr>
                                        </thead>
                                        <tbody>
                                        ';
        
        foreach ($telegramas as $value) {
            
        if ($value['IMPRESO'] == "Y")
        {
            $checked = "checked";
        } else
        {
            $checked = "";
        }

                                      echo '<tr class="gradeA">
                                        
                                                <td class="center"><input disabled type="checkbox" '.$checked.' name="check-telegramas" value="1"></td>
                                                <td>'.$value['ID_TELEGRAMA'].'</td>
                                                <td>'.$value['FECHA'].'</td>
                                                <td>'.$value['TEXTO'].'</td>
                                                
		
                  
                                        </tr>';
                                        
        }
        
        echo '                </tbody>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
				<tfoot id="pietelegramas-Tabla">
					<tr>
                                                <th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
				
					</tr>
				</tfoot>
			</table>';

                                  
    } else // En caso que el resultado devuelto no sea una arreglo se imprime la tabla vacia
    {
        echo "<label class='alerta'>$telegramas</label>";
        
        echo '<table style="font-size: 11px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" id="telegramas-Tabla" width="100%">
                                        <thead>
                                                <tr>
                                                        <th>'.$e['tt_tab1'].'</th>
                                                        <th>'.$e['tt_tab2'].'</th>
                                                        <th>'.$e['tt_tab3'].'</th>
                                                        <th>'.$e['tt_tab4'].'</th>
                                                        

                                                </tr>
                                        </thead>
                                        <tbody>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                 <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                               
		
                  
                                        </tr>
                                        

                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        

                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>
                                        
                                        <tr class="gradeA" id="5">
                                        
                                                <td class="center"><input type="checkbox" name="check-telegramas" value="1"></td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                
		
                  
                                        </tr>

	
                                </tbody>
			</table>

			<table cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
				<tfoot id="pietelegramas-Tabla">
					<tr>
                                                <th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
						<th id="selectz"></th>
				
					</tr>
				</tfoot>
			</table>';
        
    }
        
    // Se verifica que se recibe una varible cargarDirecciones por el metodo GET
} else if (isset ($_GET['cargarDirecciones'])) //===============================
{
    // Se crea una instancia de la clase C_direccion
    $direc = new C_direccion();
    $cedula = $_GET['idPersona'];
    // Se llama al metodo que se encarga de obtener las direcciones
    $direcciones = $direc->obtener_direcciones($cedula."' and D.status_direccion = 'A'--");
        // Se verifica que el resultado devuelto sea un arreglo
        if (is_array($direcciones))
    {
            // Se imprime un select con los valores obtenidos
        echo "<select class='selectEditar' style='width: 200px;' name = 'direccionesTelegramas' id='direccionesTelegramas'>
         
                <option selected disabled>Seleccione...</option>
            
             ";
        
        foreach ($direcciones as $value)
        {
            
            echo "<option value='".$value['ID_DIRECCION']."'>".$value['DIRECCION']."</option>";
            
        }
        
        echo "</select>";
        exit();
        
    } else // En caso de que no se obtenga resultados en una arreglo se muestra mensaje de alerta
        
    {
        echo "<label class='alerta'>$direcciones</label>";
    }
    
    
}

/*
 *  Autor: Henry Martinez
 *  Marzo 2014
 *  Copyright VPC 2014
 * 
 */



?>