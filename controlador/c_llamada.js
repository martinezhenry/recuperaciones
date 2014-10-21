/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function guardarTelefono(persona){
    
    var telefono = document.getElementById('txtTelefono');
    var status = document.getElementsByName('selectTelefono');
    var total = "";
    var agrega = ",";
    var cod_area = document.getElementById('txtCod_area');
   

    if (cod_area.value == ""){

     alertify.error("Debe ingresar el codigo de area.");
     cod_area.focus();
     return;
       
   }
   if (telefono.value == ""){
             
       alertify.error("Debe ingresar un telefono.");
       telefono.focus();
       return;
   }
   

   
   	$('#tabla_ll').html('Cargando');//vaciamos el div donde se encuentra la tabla

	var parametros = {"recargar" : 1,
                           "guardar" : 1,
                           "telefono" : telefono.value,
                           "cod_area" : cod_area.value,
                           "persona" : persona
                       };
	$.ajax({
		  type: "post",
		  url: 'expediente/contenedor/llamadas.php',
		  data: parametros,
		  
		  success: function(result) {
                    // alert(result);
		     $('#tabla_ll').html(result);
                     llamaTodo('llamadas-Tabla');
                         //alert('Telefono Guardado.');
                         alertify.success("Telefono Guardado.");
                            // alert(cod_area);
    $('#llamada_guardar').removeAttr("onclick");
    $('#llamada_guardar').attr("disabled", "disabled");
    $('#llamada_guardar').attr("onclick", "mostrarAdvertencia();");
		   }
  	});
   

    
    
    
}

function agregarTelefono(persona)
{
   
 $('#llamadas-Tabla').dataTable().fnAddData( [
                        "<input autofocus class='editar' name='txtCod_area' id='txtCod_area' type='text' size='10%'/>",
                        "<input autofocus class='editar' name='txtTelefono' id='txtTelefono' type='text' size='20%'/>",
                        "<select class='selectEditar' name='selectTelefono' style='width: 74px;'><option selected value='A'>Activa</option></select>"
                        ] 
                                        );
                                
                                $('#txtCod_area').numeric(false);
                                $('#txtTelefono').numeric(false);
                                        
$('#llamada_guardar').removeAttr("onclick");
$('#llamada_guardar').removeAttr("disabled");
$('#llamada_guardar').attr("onclick", "guardarTelefono("+persona+");");


}