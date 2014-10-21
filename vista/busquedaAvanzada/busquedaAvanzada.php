<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$busqueda = "<div>
            <form class='sky-form'>
            <fieldset style='height: 90%;'>
          
            <table border='0' style='font-size: 11px;'>
            <tr>
            <td>
            <div style='display: table-caption;'>
                <label> Cedula/Rif</label>
                <input type='text' name='bus_cedula' id='bus_cedula'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label> Nombre</label>
                <input type='text' name='bus_nombre' id='bus_nombre'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label> Apellido</label>
                <input type='text' name='bus_apellido' id='bus_apellido'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label>Codigo de Area</label>
                <input type='text' maxlength='3' name='bus_codarea' id='bus_codarea'/>
            </div>
            </td>
            <td>
            <div style='display: table-caption;'>
                <label> Telefono</label>
                <input type='text' maxlength='11' name='bus_telefono' id='bus_telefono'/>
            </div>
            </td>
            
            </tr>
            <tr>
            <td colspan='5'>
            <div style='width:100%;'>
                <label> Direccion</label>
                <textarea style='width:100%;' rows='4' name='bus_direccion' id='bus_direccion'></textarea>
            </div>
            </td>
            
            </tr>
            </table>
           
           
            <br>
            <button onclick='cargarDatos(bus_cedula.value, bus_nombre.value, bus_apellido.value, bus_codarea.value, bus_telefono.value, bus_direccion.value);' type='button'>Buscar</button>
            <br><br>
            <hr>
            <div style = 'height:500px; overflow:auto;'>
            <table style='width: 100%; font-size: 11px;' cellpadding='0' cellspacing='0' border='0' class='display dataTable' id='table_busqueda'>
            <thead>
                <tr>
                    <th>Fuente</th>
                    <th>Cedula</th>
                    <th>Nombres</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Fecha Nacimiento</th>
                  
                    
                    

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
                  

                </tr>
                
                <tr>
                   
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
   

                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
     

                </tr>


            </tbody>



            </table>
            </div>
            <hr>
            </fieldset>
            </form>


           
            
        </div>
                <script src='../controlador/busquedaAvanzada/busquedaAvanzada.js'></script>
";