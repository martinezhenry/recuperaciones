<?php 
/*############################################################################ 
#                                                                            # 
#Nombre de la funcion: MeDir                                                 # 
#                                                                            # 
#Autor: SERBice® # 
#                                                                            # 
#Descripcion de la funcion: Recorre un directorio midiendo todos los         # 
#                           archivos que contiene (incluso en sus            # 
#                           subdirectorios, hasta el ultimo).                # 
#                                                                            # 
#Parametros de la funcion: El unico parametro de la funcion MeDir es $dir,   # 
#                          Dicho parametro establece el directorio sobre el  # 
#                          cual actuara la funcion, es decir, que establece  # 
#                          el directorio del cual se obtendra informacion de # 
#                          su tamaño completo, incluyendo subdirectorios.    # 
#                          Si $dir no se establece se utilizara el directorio# 
#                          donde se encuentra el archivo que llamo a la      # 
#                          funcion                                           # 
#                                                                            # 
#Este Software se distribuye bajo Licencia GPL, por lo cual se solicita que  # 
#se utilice con fines no lucrativos, es decir, que sea de uso Personal y No  # 
#Comercial. Que se conserven los derechos de autor y que cualquier           # 
#modificacion le sea notifiacda al autor, para saber y estar al tanto de     # 
#los avances del software en cuestion; y de esta manera enriquezer aun mas   # 
#esta pequeña herramienta                                                    # 
#                                                                            # 
#Atentamente: SERBice®                                                       # 
#                                                                            # 
############################################################################*/ 
function MeDir($dir=".") 
    { 
        /* Creamos un array con todos los nombres de directorios y 
        archivos contenidos dentro del directorio inicial */ 
        $arr = scandir($dir); 

        /* establecemos que la variable $sizedir es igual a cero */ 
        $sizedir = 0; 

        /* Recorremos el array saltando los directorios . y .. */ 
        for ($i=2; $i<count($arr); $i++) 
            { 
                /* Si es un archivo hacer..... */ 
                if (is_file($dir ."/". $arr[$i])) 
                    { 
                        /* Establecemos que la variable $sizedir es igual 
                        a ella misma más el tamaño del fichero $dir ."/". $arr[$i] */ 
                        $sizedir += filesize($dir ."/". $arr[$i]); 
                    } 
                /* Si es un directorio hacer..... */ 
                elseif (is_dir($dir ."/". $arr[$i])) 
                    { 
                        /* Establecemos que la variable $sizedir es igual 
                        a ella misma más el valor devuelto por MeDir */ 
                        $sizedir += MeDir($dir . "/" . $arr[$i]); 
                    } 
                /* Si no sabemos que es reaccionamos como si fuera un archivo y ... */ 
                else 
                    { 
                        /* Establecemos que la variable $sizedir es igual 
                        a ella misma más el tamaño del fichero $dir ."/". $arr[$i] */ 
                        $sizedir += filesize($dir ."/". $arr[$i]); 
                    } 
            } 
        /* Devolvemos el valor total de $sizedir */ 
        return $sizedir; 
    }
  
    
?>