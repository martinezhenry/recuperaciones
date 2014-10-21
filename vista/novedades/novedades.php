<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

        
require_once '../controlador/novedades/c_novedades.php';


$novedades = '

<script src="../controlador/novedades/c_novedades.js"></script>
            <div>
                 <form id="form_novedad" method="post" action="?pantalla='.md5('expediente').'" class=\'sky-form\'>
                  <fieldset style=\'height: 90%;\'>
                  
                <div><a href="#" onclick="marcarVisto();">Marcar como visto</a>
                <a href="#" onclick="enviarExpedi();">Expediente</a>
             
                </div>

                  <div style="height: 95%; overflow : auto;">
                <table style=\'width: 100%; font-size: 11px;\' cellpadding=\'0\' cellspacing=\'0\' border=\'0\' class=\'display dataTable\' id=\'table_Novedades\'>
                    <thead>
                        <tr>
                            <th><input id="checkToNove" onclick="mTodos();" type="checkbox" value=""/></th>
                            <th>CEDULA</th>
                            <th>NOMBRE</th>
                            <th>NOVEDAD</th>
                            <th>STATUS</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        ';
                        
                        $datos = consultarNovedad();


if (!$datos){
    
 $novedades .= "<tr><td style='text-align : center;' colspan=5>No Existen Resultados</td></tr>";
    
} else {
    
    foreach ($datos as $value) {
    $novedades .= "<tr >";
    $novedades .= "<td class='center'><input name='checkNovedad' type='checkbox' value='".$value['ID_NOVEDAD'].";".$value['PERSONA']."' /></td>";
    $novedades .= "<td class='center'>".$value['CEDULA']."<div hidden id='personaId'>".$value['PERSONA']."</div></td>";
    $novedades .= "<td class='center'>".$value['NOMBRE']."</td>";
    $novedades .= "<td class='center'>".$value['NOVEDAD']."</td>";
    $novedades .= "<td class='center'>".$value['STATUS']."</td>";
    $novedades .= "<td class='center'>".$value['FECHA_INGRESO']."</td>";
    $novedades .= "</tr>";
        
        
    }
    
}
              $novedades .= '          

                        
                    </tbody>
                </table>
                </div>
                </fieldset>
                 </form>
            </div>
        ';
?>