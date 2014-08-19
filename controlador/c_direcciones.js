
function guardarDireccion(persona){
    
    var direcciones = document.getElementsByName('txtDireccion');
    var status = document.getElementsByName('selectDireccion');
    var total = "";
    var agrega = ",";
    for (var i=0; i < direcciones.length; i++){
        
        if (i == (direcciones.length-1)){
            
            agrega = "";
            
        }
        
        total += direcciones[i].value+agrega;
        
    }
    
    $('#direccion_guardar').removeAttr("onclick");
    $('#direccion_guardar').attr("disabled", "disabled");
    $('#direccion_guardar').attr("onclick", "mostrarAdvertenciaDirecciones();");
   
   
   
   	$('#tabla_dir').html('');//vaciamos el div donde se encuentra la tabla

	var parametros = {"re_direccion" : 1,
                           "guardar" : 1,
                           "direcciones" : total,
                           "persona" : persona
                       };
	$.ajax({
		  type: "POST",
		  url: 'direcciones.php',
		  data: parametros,
		  
		  success: function(result) {
		     $('#tabla_dir').html(result);
                     llamaTodo('direcciones-Tabla');
		   }
  	});
   
    alert('Direccion Guardada. '+total+" persona: "+persona);
   
    
    
    
}


function agregarDireccion(persona)
{
 $('#direcciones-Tabla').dataTable().fnAddData( [
                        "<input autofocus class='editar' name='txtDireccion' type='text' size='125%'/>",
                        "<select class='selectEditar' name='selectStatus' style='width: 74px;'><option selected value='A'>Activa</option></select>"
                        ] 
                                        );
                                        
$('#direccion_guardar').removeAttr("onclick");
$('#direccion_guardar').removeAttr("disabled");
$('#direccion_guardar').attr("onclick", "guardarDireccion("+persona+");");


}