<?php 
require_once '../controlador/c_plantillasSms.php';


    ?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
                <?php //llamado de ccs
  require_once 'ccs.php';
  //llamados a js
  require_once 'js.php';
  ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
        <title></title>
    </head>
    <body class="bg-blue">
        
          <div>
            <form class="sky-form">
                
                <fieldset>
                    <h2>Crear Plantilla SMS</h2>
                    <label>Variables</label>
                    
                    <?php 
                          $variables = cargarVariables();
                          
               
                    ?>
                    
                    <select id="variable">
                        <option value="0">Seleccione...</option>
                        <?php 
                           
                        foreach ($variables as $value) {

                       ?>
                       <option value ="<?php echo $value['ID'] ?>"> <?php echo $value['CAMPO'] ?></option>

                      <?php  } ?>

                   </select>
                    
                    <a id="agregar" href="#">Agregar Variable</a>
                    
                    <br><br>
                    <textarea style="width: 500px;" name="plantilla" id="plantilla" rows="4"></textarea>
                    <br>
                    <a id="guardar" href="#">Guardar</a>
                    <div id="resultados"></div>
                    <br><br>
                    <hr>
                    <h2>Plantillas</h2>
                    <br>
                    <table id="T_plantillas" style="font-size: 12px;" cellpadding="0" cellspacing="0" border="0" class="display dataTable" width="100%">
                        <thead>
                            <tr>
                                <th width="7%">Codigo</th>
                                <th width="85%">Plantilla</th>
                                <th width="5%">Status</th>
                                <th width="5%">Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    
                </fieldset>
            </form>
          </div>
        
        <?php
        // put your code here
        
        ?>
        
        <script>
             
                $(document).ready(function(){
                   var idVariables = [];
                   $('#agregar').click(function(){
                       
                      //$('#plantilla').val($('#plantilla').val()+" ;"+$(this).val()+";");
                      
                      $('#plantilla').val($('#plantilla').val()+" ;"+ $('#variable option:selected').html() +";");
                      idVariables.push($('#variable').val());
                       
                       
                   });
                
                   
                   $('#guardar').click(function(){
                       
                      
                       var parametros = { plantilla        : $('#plantilla').val(),
                                          id_variables     : JSON.stringify(idVariables),
                                          guardarPlantilla : 1
                                        };
                       $.ajax({
                           url      : '../controlador/c_plantillasSms.php',
                           data     : parametros,
                           type     : 'post',
                           success  : function(result){
                               
                               $('#resultados').html(result);
                               $('#plantilla').val("");
                               idVariables.length = 0;
                               
                           }
                          
                
                        });
                       
                       
                   });
                   
                   cargarPlantillas();
                   
                    
                });

                  
                  function cargarPlantillas(){
                      
                      var parametros = { cargarPlantillas : 1 };
                      $.ajax({
                          
                          url    : '../controlador/c_plantillasSms.php',
                          type   : 'post',
                          data   : parametros,
                          success: function(result){
                          
                              var plantillas = JSON.parse(result);
                              
                              for (var i=0;i < plantillas.length;i++ ){
                              var t, a;
                              (plantillas[i].ESTATUS ==='DESACTIVADA') ? t = 'Activar': t = 'Desactivar' ;
                              (plantillas[i].ESTATUS ==='DESACTIVADA') ? a = 1 : a = 0 ;
                              var fila = "<tr id='f_"+plantillas[i].ID+"' class='gradeA'><td width='7%'>";
                              fila += plantillas[i].ID;
                              fila += "</td><td width='83%'>";
                              fila += plantillas[i].TEXTO_A;
                              fila += "</td><td width='5%'>";
                              fila += plantillas[i].ESTATUS;
                              fila += "</td><td width='5%'>";
                              fila += "<a id='"+plantillas[i].ID+"' onclick='cambiarStatusPlantilla(this.id, "+a+")' href='#'>"+t+"</a>&nbsp";
                              fila += "<a id='"+plantillas[i].ID+"' onclick='eliminarPlantilla(this.id)' href='#'>Eliminar</a>";
                              fila += "</td></tr>";
                              $("#T_plantillas").append(fila);
                          }
                          llamaTodo('T_plantillas');
                          }
                          
                          
                      });
                      
                  }
                  
                  
                  function eliminarPlantilla(id){
                  
                   var parametros = { eliminarPlantilla : 1, 
                                      id_plantilla      : id
                                    };
                      $.ajax({
                          
                          url    : '../controlador/c_plantillasSms.php',
                          type   : 'post',
                          data   : parametros,
                          success: function(result){
                              
                              if (result)
                              $("#f_"+id).remove();
                              
                          }
                      });
                  
                  
                  }

                function cambiarStatusPlantilla(id, a){
                  
                   var parametros = { cambiarStatus : 1, 
                                      id_plantilla  : id,
                                      accion        : a
                                    };
                      $.ajax({
                          
                          url    : '../controlador/c_plantillasSms.php',
                          type   : 'post',
                          data   : parametros,
                          success: function(result){
                              var t, s, x;
                              (a === 0) ? t = 'DESACTIVADA' : t = 'ACTIVADA' ;
                              (a === 0) ? s = 'Activar' : s = 'Desactivar' ;
                              (a === 0) ? x = 1 : x = 0 ;
                              
                              if (result)
                              $("#f_"+id +" td:nth-child(3)").html(t);
                              $("#f_"+id +" td:nth-child(4) a:nth-child(1)").html(s);
                              $("#f_"+id +" td:nth-child(4) a:nth-child(1)").attr("onclick", "cambiarStatusPlantilla(this.id, "+x+");");
                              
                          }
                      });
                  
                  
                  }
                
        </script>
        
    </body>
</html>

<?php



?>
