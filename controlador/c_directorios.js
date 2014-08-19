


function Directorio(){
 this.tamaño ="a";

 this.obtenerTamañoDir = function obtenerTamañoDir(usuario){
    
        var contenedor = document.getElementById('');
    
    

    
    
   
};

 this.recarga = function recarga(usuario){
//alert(usuario);
    //this.tamaño = this.obtenerTamañoDir(usuario);

//$('#sup').find("option[\'0\']").removeAttr("selected disabled");
//$('#sup').find("option[value=\'seleccione\']").val(usuario);
  // $(\'#objeto\').attr(\'data\', "hola.php");
  
  
      //  $(\'#iframe\').reload();
      
      var ajax = nuevoAjax();


    ajax.onreadystatechange = function()
    {
        if (ajax.readyState == 1)
        {
          //  contenedor.innerHTML = "Cargando...";
           // jQuery("#contenedor-TablaPortafolio").html("<img alt='cargando' src='img/ajax-loader.gif' />");
           
        }
        if (ajax.readyState == 4)
        {
           //alert(ajax.responseText);
           var datos = ajax.responseText;
        
           datos = datos.split(",");
           var carpeta = datos[0];
           var permitir = datos[1];
          
           
           if (carpeta == "")
           {
               alert("ERROR: Ruta del Directorio no localizado!");
           } else {
           $('#iframe').attr('src', "../elfinder/otro.php?pasada="+carpeta+"&permitir="+permitir+"&usuario="+usuario);
           //alert(tamaño);
           }
        } 
    };
    
 
    ajax.open("GET", "../controlador/c_directorios.php?Directorio=si&usuario="+usuario, true);
    ajax.send();



};



}


function cambiaValor(){
    
    
    window.tamaño = "hola";
    
}


function bloquearSelects(){
    
    $('#ger').attr('disabled', '');
    $('#coor').attr('disabled', '');
    $('#sup').attr('disabled', '');
    $('#ges').attr('disabled', '');
    
}
