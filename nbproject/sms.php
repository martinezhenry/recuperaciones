<?php 


$telefonos="";
for($i=0; $i <= 5; $i++)
{
    $telefonos .= "<option>$i</option>";
    
}


$plantillas="";

for($i=0; $i <= 4; $i++)
{
    $plantillas .= "<option>$i</option>";
    
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
  alert(telefonos.textContent);
  
}

         function activar()
{
       alert("");
var pestaña_a = document.getElementById("pestaña_a");
var pestaña_b = document.getElementById("pestaña_b");
    /*
    if(link.id == "pestaña_a")
    {
        pestaña_a.class = "active";
        pestaña_b.class = "";
    } else
    {
        pestaña_a.class = "";
        pestaña_b.class = "active";
        
    }
    
    
   */
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
                <li id="pestaña_a" class="active"><a data-toggle="tab" onclick="activar();" href="#tb1_a">Enqssviar SMS</a></li>
                <li id="pestaña_b"><a data-toggle="tab" onclick="activar();" href="#tb1_b">Leer SMS</a></li>
            </ul>
            
            <div class="tab-content">
		<div id="tb1_a" class="tab-pane">
                  <form>
                    <p>Enviar SMS:</p>
                    
                          <div>
                                                <label>Fecha de Envioss</label>
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
														
                    
                    '.$telefonos.'                     
                        
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
		
		
		
		
		
		
		<div id="tb1_b" class="tab-pane active  ">
                    <p>SMS Entrantes:</p>
                    <div style="width: 100%; overflow: auto; outline: 0px; position: relative; height: 416px;" >
                    <table  overflow:auto id="dt_basic" class="table table-striped">
			<thead>
                        	<tr>
                                    <th>Id</th>
                                    <th>Fecha</th>
                                    <th>Numero</th>
                                    <th>Contenido</th>
				</tr>
			</thead>
			<tbody>
                            <tr><td>1</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>2</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>3</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>4</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>5</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
							 <tr><td>3</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>4</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>5</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
							 <tr><td>1</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>2</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>3</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>4</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>5</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
							 <tr><td>3</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>4</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>
                            <tr><td>5</td><td>03/02/2014</td><td>0000-0000000</td><td>SMS Recibido.</td>


                          
			</tbody>
        
            </table>
			</div>
			
			<textarea name="reg_textarea" id="reg_textarea2" cols="10" rows="3" class="form-control" readonly="readonly">Contenido del SMS a enviar</textarea>
	   
		</div>
		
		
		
		
		
		
		
            </div>
        </div>
     </div>



            </li>
    </ul>
    </div>
    





';



?>