<?php
/**
 * Devuelve el código de la tabla con los datos ya plasmados
 * @param  String (Identificador de la tabla)  $ID
 * @param  Array  (Nombre de la cabecera de la tabla)  $cabecera
 * @param  Array  (Contenido de la tabla)  $contenido
 * @param  Int Número de cuantas columnas tendra la tabla se cuenta apartir de 0  $int_cabecera
 * @param  boolean (para habilitar los checks) $checks
 * @return Código de la tabla con la toda la Info
 */
function getTableInfo($ID, $cabecera, $contenido, $int_cabecera, $checks=true, $selects = true){
	$tabla = '	<table id="'.$ID.'" cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							';
							// creamos la cabecera de la tabla
							foreach($cabecera as $head){
								$tabla .= "<th style='font-weight: normal;' >".$head."</th>";
							}
							$tabla .= '
						</tr>
					</thead>
					<tbody id="tabla_gestion" >

					';
					// Creamos las celdas de la tabla con la informacion obtenida	
					$cont = 1;
							foreach($contenido as $indice => $valor) {

								$tabla .= '<tr id="1"  class="gradeA">';
									if($checks)
											$tabla .= '<td class="center"><input type="checkbox" name="checkcuentas[]" value="'.$valor[0].'"></td>';
											
											for($x=0;$x<$int_cabecera;$x++){
												//echo $valor[$x];
											$tabla .= '<td style="font-size: 11px;">'.$valor[$x].'</td>';
									
										}
										  $tabla .= '</tr>';
								$cont++;
							}
							$tabla .= '	
					</tbody>
				</table>';                                                        
				// Imprime el pie de pagina
				if($selects) {
					$tabla .= '<table  cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
						<tfoot id="pie'.$ID.'" >
							<tr>
							';
								$cont = 1;
								foreach($cabecera as $head){
									if(($cont) == count($cabecera))
										$tabla .= "<th id='last_pos_table'></th>";
									else
										$tabla .= "<th id='last_pos_table'></th>";
									$cont++;
								}
								$tabla .= '
							</tr>
						</tfoot>
					</table>';
				}
	return $tabla;
}


?>