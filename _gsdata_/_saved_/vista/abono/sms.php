<?php 


$telefonos="";
for($i=0; $i <= 5; $i++)
{
    $telefonos .= "<option>$i</option>";
    
}

function cargarPlantillas()
{
    $sql = "Select id, texto_a,texto_b from SR_SMS_TEXTO;";   
    // preparamos el statement para la consulta  
    $st = oci_parse($conn,$sql);
    //ejecutamos el query  
    oci_execute($st);  
    //$plantillas="";

    while ($fila = oci_fetch_array($st)) 
    {
        return "<option>$fila[1] $fila[2]</option>";

    }

}


//$telefonos="";
//cantidad de SMS entrantes
$entrantesSMS=8;
$fecha= date("d\/m\/Y");

$sms = '
<script>

function TomarPlantilla() {
 
  var textArea = document.getElementById("reg_textarea");
  var textoPlantilla = document.getElementById("reg_select1");
  var posicion=document.getElementById("reg_select1").options.selectedIndex; //posicion
             
  textArea.textContent = textoPlantilla.options[posicion].text;
  
  
}


function enviar() {
 
  var telefonos = document.getElementById("reg_select_multiple");
  var posicion=document.getElementById("reg_select1").options.selectedIndex; //posicion
  telefonos = textoPlantilla.options[posicion].text;
  
  
}



function traer()
{
    var accion = 0;
    var checkTodos = document.getElementById("todos");
    var todosloschck = document.forms["formulario"].checks;
    
    
    if(checkTodos.checked == 1)
    {
        accion = 1;
        
    } else 
    {
        accion = 0;

    }
    
        var i=0;
        for (i=0; i <= todosloschck.length; i++)
        {
           // checkCambia.checked;
            todosloschck[i].checked = accion;


        }
    
}

function contenido(contenido)
{
    
    var textArea = document.getElementById("reg_textarea2");
    textArea.textContent = contenido;
}

function filtroBuscar(valor)
{

    alert(valor);

}


</script>


<!-- jQuery -->
		<!-- <script src="js/jquery.min.js"></script> -->
	    

<div class="notification_dropdown dropdown">
    <a href="#" class="notification_icon dropdown-toggle" data-toggle="dropdown">
            <span class="label label-danger">'.$entrantesSMS.'</span>
            <i class="icon-comment-alt icon-2x"></i>
    </a>
    <ul class="dropdown-menu">
            <li>
            
<div class="dropdown_heading">SMS</div>
    <div class="dropdown_content">
   
	<div class="tabbable tabbable-bordered">
            
            <ul class="nav nav-tabs">
                <li id="pestaña_a" class="active"><a data-toggle="tab" href="#tb1_a">Enviar SMS</a></li>
                <li id="pestaña_b"><a data-toggle="tab" href="#tb1_b">Leer SMS</a></li>
            </ul>
            
            <div class="tab-content">
		<div id="tb1_a" class="tab-pane active">
                  <form>
                    <p>Enviar SMS:</p>
                    
                          <div>
                                                <label>Fecha de Envios</label>
                                                <div class="input-group date ebro_datepicker" data-date-format="dd-mm-yyyy" >
                                                    <input class="form-control" type="text">
                                                    <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                                </div>
                                                <span class="help-block">dd-mm-yyyy</span>
                                            </div>

                    <label for="reg_input">Cedula:</label>
                    <input type="text" id="reg_input" class="form-control">
                    
                    <label for="reg_select">Telefonos:</label>
                    <select multiple="multiple" id="reg_select_multiple" class="form-control">
														
                    
                    '.cargarPlantillas().'                     
                        
                   </select>
                    
                    <label for="reg_select">Plantilla:</label>
                    <select onchange="TomarPlantilla();" id="reg_select1" class="form-control">
                        '.$plantillas.'
                    </select>
                    
                    <label for="reg_textarea">Mensaje:</label>
                    <textarea name="reg_textarea" id="reg_textarea" cols="10" rows="3" class="form-control" readonly="readonly">Contenido del SMS a enviar</textarea>
                    <br>
                    <div align="right">
                    <button type="submit" onclick="enviar()" class="btn btn-default">Enviar</button>
                    </div>
                 </form>

		</div>
		
		
		
		
		
		
		<div id="tb1_b" class="tab-pane">
                    <p>SMS Entrantes:</p>
                        
                    <div><table>
                    <tr><td>
                                                <label>Fecha de Inicio:</label>
                                                <div class="input-group date ebro_datepicker" data-date-format="dd-mm-yyyy" style=" width: 10;">
                                                    <input class="form-control" type="text">
                                                    <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                                </div>
                                                <span class="help-block">dd-mm-yyyy</span>
                                         </td><td><ul>
                                                <label>Fecha de Fin:</label>
                                                <div class="input-group date ebro_datepicker" data-date-format="dd-mm-yyyy" >
                                                    <input class="form-control" type="text">
                                                    <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                                </div>
                                                <span class="help-block">dd-mm-yyyy</span></ul>
                                            </div>
                                            </td></tr></table>
                                        
                    <div>     
                    
                     <table WIDTH=100%><tr> <td>  
                    <div>
                   
                    <select aria-controls="dt_basic" id="reg_select2" class="form-control">
														
                        <option>Todos</option> 
                        <option>Leidos</option> 
                        <option>No Leidos</option> 
                      
                    </select>
                    </td><td>
                    <button type="button" onclick="filtroBuscar(document.getElementById(\'reg_select2\').value);" class="btn btn-default">Buscar</button>
                    
                       </td></tr></table>
                    </div>
                    
                    <br>
                   

                    <div style="width: 100%; overflow: auto; outline: 0px; position: relative; height: 220px;" >
                    <table id="dt_basic" class="table table-striped">
			<thead>
                        	<tr>
                                    <th><input onclick="traer();" id="todos" type="checkbox" value="Todos"></th>
                                    <th>Id</th>
                                    <th>Fecha</th>
                                    <th>Numero</th>
                                    <th>Contenido</th>
                                    <th>Estado</th>
                                    


				</tr>
			</thead>
                        <form name="formulario">
			<tbody>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="javascript:contenido(document.getElementById(\'contenido\').innerHTML)">1</a></td><td>03/02/2014</td><td>0000-0000000</td><td id="contenido">SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="javascript:contenido(document.getElementById(\'contenido2\').innerHTML)">2</a></td><td>03/02/2014</td><td>0000-0000000</td><td id="contenido2">SMS Recibido2.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="javascript:contenido(document.getElementById(\'contenido3\').innerHTML)">3</a></td><td>03/02/2014</td><td>0000-0000000</td><td id="contenido3">SMS Recibido3.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="javascript:contenido(document.getElementById(\'contenido4\').innerHTML)">4</a></td><td>03/02/2014</td><td>0000-0000000</td><td id="contenido4">SMS Recibido4.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="javascript:contenido(document.getElementById(\'contenido5\').innerHTML)">5</a></td><td>03/02/2014</td><td>0000-0000000</td><td id="contenido5">SMS Recibido5.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="javascript:contenido(document.getElementById(\'contenido6\').innerHTML)">6</a></td><td>03/02/2014</td><td>0000-0000000</td><td id="contenido6">SMS Recibido6.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">7</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">8</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">9</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">10</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">11</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">12</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">13</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">14</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
                            <tr><td><input name="checks" type="checkbox"></td><td><a href="#">15</a></td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td><td>Leido</td>
			


                          
			</tbody>
                        </form>
        
            </table>
			</div>
			
			<textarea name="reg_textarea" id="reg_textarea2" cols="10" rows="3" class="form-control" readonly="readonly">Contenido del SMS a enviar</textarea><br>
                        <div align="right"><button type="button" onclick="" class="btn btn-default">Expediente</button></div>
	   
		</div>
		
		
		
		
		
		
		
            </div>
        </div>
     </div>



            </li>
    </ul>
    </div>
    





';



?>