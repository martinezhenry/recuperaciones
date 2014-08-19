<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$localizacion = "<div>
            <form class='sky-form'>
            <fieldset style='height: 90%;'>
          
            <table border='0' style='font-size: 11px;'>
            <tr>
            <td>
            <div style='display: table-caption;'>
                <label> Cedula/Rif</label>
                <input type='text' name='loc_cedula' id='loc_cedula'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label> Nombre</label>
                <input type='text' name='loc_nombre' id='loc_nombre'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label> Apellido</label>
                <input type='text' name='loc_apellido' id='loc_apellido'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label>Codigo de Area</label>
                <input type='text' maxlength='3' name='loc_codarea' id='loc_codarea'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label> Telefono</label>
                <input type='text' maxlength='7' name='loc_telefono' id='loc_telefono'/>
            </div>
            </td>
            
            </tr>
            <tr>
            <td colspan='5'>
            <div style='width:100%;'>
                <label> Direccion</label>
                <textarea style='width:100%;' rows='4' name='loc_direccion' id='loc_direccion'></textarea>
            </div>
            </td>
            
            </tr>
            </table>
           
           
            <br>
            <button onclick='cargarPersonas(loc_cedula.value, loc_nombre.value, loc_apellido.value, loc_codarea.value, loc_telefono.value, loc_direccion.value);' type='button'>Buscar</button>
            <br><br>
            <hr>
            <table style='width: 100%; font-size: 11px;' cellpadding='0' cellspacing='0' border='0' class='display dataTable' id='table_Localizacion'>
            <thead>
                <tr>
                    <th>Fuente</th>
                    <th>Fecha de Carga</th>
                    <th>Cedula</th>
                    <th>Nombres</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Fecha Nacimiento</th>
                    <th>Informacion Adicional</th>
                    
                    

                </tr>
            </thead>
            <tbody>    
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                </tr>


            </tbody>



            </table>
            <hr>
            </fieldset>
            </form>


           
            
        </div>
        

        <script src='../controlador/localizacion/c_localizacion.js'></script>

";
