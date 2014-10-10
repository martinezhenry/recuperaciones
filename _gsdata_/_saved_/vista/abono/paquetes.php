<!DOCTYPE html> 
<html>
    <script src="js/jquery-1.9.1.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
       
	<head>
		<title>Sky Forms</title>
		
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
		
		<link rel="stylesheet" href="css/demo.css" />
		<link rel="stylesheet" href="css/sky-forms.css" />
		<link rel="stylesheet" href="sky-forms-blue.css" />
    
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head>
	<body class="bg-red">
		<div class="body">			
		
			<!-- Green color scheme -->
                        <FORM name="proceso" ACTION="procesos.php"  METHOD="POST" onsubmit="return validateForm()" class="sky-form" enctype="multipart/form-data"> 
				<header>PANEL DE FACTURACION</header>
                                <!--<fieldset>
                                <label class='select'>
                                <select name="select"onChange=" procesos(this.value);">
                                    <option selected value=''>Selecccione Proceso</option>
                                    <option value ='1'>Portafolio</option>
                                    <option value ='2'>Traslado de cuentas</option>
                                    <option value ='3'>Reporte de Abono</option>
                                    <option value ='5'>Reporte Hora de Primera gestion</option>
                                    <option value ='6'>Reporte Inventario Portafolios</option>
                                </select>
                                 </label>
                                    </fieldset> 
                                <section>-->
                                    <footer>
                                            <fieldset>
                                                <section class='col col-6'>
                                            <label class='input'>
                                       <i class='icon-append icon-calendar'></i>
                                                   <input type='text' name='desde' id='start' placeholder='Ingrese Fecha Inicio' />
                                            </label>
                                        </section>
                                        <section class='col col-6'>
                                            <label class='input'>
                                                   <i class='icon-append icon-calendar'></i>
                                                   <input type='text' name='hasta' id='finish' placeholder='Ingrese Fecha Fin' />
                                            </label>
                                        </section>
                                            <label class='label'>Formato para subir excel:</label>
                                            <a href='./img/FACTURACION.xls'>FORMATO FACTURACION</a>
                                            <label class='label'></label>
                                            <label class='label'>ARCHIVO DE FACTURACION</label>
                                            <label class='select'>
                                                     <i></i>
                                    </label>
                                           <label class='label'></label>
                                <label class='label'></label>
                                <label for='file' class='input input-file'>
                                <div class='button'>
                                <input name='archivo' type='file' id='file' onchange='this.parentNode.nextSibling.value = this.value' />SUBIR</div><input type='text' placeholder='Adjunte archivo excel' readonly='' />
                                 </label>
                                </fieldset>
                                <input type='submit' value='GENERAR' class='button'>
                                 </footer>
				</section>
			</form>
			<!--/ Green color scheme -->			
		</div>
	</body>
         <script>
             $(function(){
                                                    $('#start').datepicker({
                                                            dateFormat: 'dd/mm/yy',
                                                            prevText: '<i class="icon-chevron-left"></i>',
                                                            nextText: '<i class="icon-chevron-right"></i>',
                                                            onSelect: function( selectedDate )
                                                            {
                                                                    $('#finish').datepicker('option', 'minDate', selectedDate);
                                                            }
                                                    });
                                                    $('#finish').datepicker({
                                                            dateFormat: 'dd/mm/yy',
                                                            prevText: '<i class="icon-chevron-left"></i>',
                                                            nextText: '<i class="icon-chevron-right"></i>',
                                                            onSelect: function( selectedDate )
                                                            {
                                                                    $('#start').datepicker('option', 'maxDate', selectedDate);
                                                            }
                                                    });
                                                });
                                                
                                                
                function procesos(proceso){
                  if (proceso == ''){
                    document.getElementById("p1").innerHTML=""
                  };
                if (proceso == '1'){
                    document.getElementById("p1").innerHTML="<footer>\n\
                                                    <fieldset>\n\
                                                    <section>\n\
                                                    <input type='hidden' name='tipo' value='portafolio'>\n\
                                                    <label class='label'>Genera de portafolio</label>\n\
                                                    <label class='select' >\n\
                                                            <select name ='cliente'>\n\
                                                                    <option value='0'>TODOS</option>\n\
                                                                    <option value='10025'>BANESCO</option>\n\
                                                                    <option value='136'>PROVINCIAL</option>\n\
                                                                    <option value='21'>VENEZUELA</option>\n\
                                                                    <option value='18'>MERCANTIL</option>\n\
                                                                    <option value='10034'>DIGITEL</option>\n\
                                                                    <option value='10150'>CORPBANCA</option>\n\
                                                                    <option value='19' />BOD</option>\n\
                                                            </select>\n\
                                                            <i></i>\n\
                                                    </label>\n\
                                            </section>\n\
                                            <fieldset>\n\
                                                <input type='submit' value='GENERAR' class='button'>\n\
                                            </footer>";
                }
                        if (proceso == '2'){
                            document.getElementById("p1").innerHTML="<footer>\n\
                                                                    <fieldset>\n\
                                                                    <label class='label'>Formato para subir excel:</label>\n\
                                                                    <a href='./img/TRASLADO_CUENTAS.xls'>FORMATO TRASLADO</a>\n\
                                                                    <label class='label'></label>\n\
                                                                    <input type='hidden' name='tipo' value='traslado'/>\n\
                                                                    <label class='label'>Cliente</label>\n\
                                                                    <label class='select'>\n\
                                                                    <select name ='cliente'>\n\
                                                                            <option selected value='' >SELECCIONE CLIENTE</option>\n\
                                                                            <option value='10025'>BANESCO</option>\n\
                                                                            <option value='136'>PROVINCIAL</option>\n\
                                                                            <option value='21'>VENEZUELA</option>\n\
                                                                            <option value='18'>MERCANTIL</option>\n\
                                                                            <option value='10034'>DIGITEL</option>\n\
                                                                            <option value='10150'>CORPBANCA</option>\n\
                                                                            <option value='19' />BOD</option>\n\
                                                                    </select>\n\
                                                                             <i></i>\n\
                                                            </label>\n\
                                                                   <label class='label'></label>\n\
                                                        <label class='label'></label>\n\
                                                        <label for='file' class='input input-file'>\n\
                                                        <div class='button'>\n\
                                                        <input name='archivo' type='file' id='file' onchange='this.parentNode.nextSibling.value = this.value' />SUBIR</div><input type='text' placeholder='Adjunte archivo excel' readonly='' />\n\
                                                         </label>\n\
                                                        </fieldset>\n\
                                                        <input type='submit' value='GENERAR' class='button'>\n\
                                                         </footer>";
                         
                             }  
                          if (proceso == '3'){
                              document.getElementById("p1").innerHTML="<footer>\n\
                                       <fieldset>\n\
                                       <input type='hidden' name='tipo' value='r_abono'/>\n\
                                       <section class='col col-6'>\n\
                                            <label class='input'>\n\
                                       <i class='icon-append icon-calendar'></i>\n\
                                                   <input type='text' name='desde' id='start' placeholder='Ingrese Fecha Inicio' />\n\
                                            </label>\n\
                                        </section>\n\
                                        <section class='col col-6'>\n\
                                            <label class='input'>\n\
                                                   <i class='icon-append icon-calendar'></i>\n\
                                                   <input type='text' name='hasta' id='finish' placeholder='Ingrese Fecha Fin' />\n\
                                            </label>\n\
                                        </section></fieldset>\n\
                                    <input type='submit' value='GENERAR' class='button'>\n\
                                  </footer>";
                                     $(function(){
                                                    $('#start').datepicker({
                                                            dateFormat: 'dd/mm/yy',
                                                            prevText: '<i class="icon-chevron-left"></i>',
                                                            nextText: '<i class="icon-chevron-right"></i>',
                                                            onSelect: function( selectedDate )
                                                            {
                                                                    $('#finish').datepicker('option', 'minDate', selectedDate);
                                                            }
                                                    });
                                                    $('#finish').datepicker({
                                                            dateFormat: 'dd/mm/yy',
                                                            prevText: '<i class="icon-chevron-left"></i>',
                                                            nextText: '<i class="icon-chevron-right"></i>',
                                                            onSelect: function( selectedDate )
                                                            {
                                                                    $('#start').datepicker('option', 'maxDate', selectedDate);
                                                            }
                                                    });
                                                });
                        }
                         if (proceso == '4'){
                                   document.getElementById("p1").innerHTML="<footer>\n\
                                                    <fieldset>\n\
                                                    <section>\n\
                                                    <input type='hidden' name='tipo' value='r_g_cuentas'>\n\
                                                    <label class='label'>Genera de gestiones por cuentas</label>\n\
                                                    <label class='select' >\n\
                                                            <select name ='cliente'>\n\
                                                                <option selected value='' >SELECCIONE CLIENTE</option>\n\
                                                                <option value='10025'>BANESCO</option>\n\
                                                                <option value='136'>PROVINCIAL</option>\n\
                                                                <option value='21'>VENEZUELA</option>\n\
                                                                <option value='18'>MERCANTIL</option>\n\
                                                                <option value='10034'>DIGITEL</option>\n\
                                                                <option value='10150'>CORPBANCA</option>\n\
                                                                <option value='19' />BOD</option>\n\
                                                            </select>\n\
                                                            <i></i>\n\
                                                    </label>\n\
                                            </section>\n\
                                            <fieldset>\n\
                                                <input type='submit' value='GENERAR' class='button'>\n\
                                            </footer>";
                        }
                         if (proceso == '5'){
                              document.getElementById("p1").innerHTML="<footer>\n\
                                       <fieldset>\n\
                                       <input type='hidden' name='tipo' value='r_primera_hora'/>\n\
                                       <section class='col col-6'>\n\
                                            <label class='input'>\n\
                                       <i class='icon-append icon-calendar'></i>\n\
                                                   <input type='text' name='desde' id='start' placeholder='Ingrese Fecha Inicio' />\n\
                                            </label>\n\
                                        </section>\n\
                                        <section class='col col-6'>\n\
                                            <label class='input'>\n\
                                                   <i class='icon-append icon-calendar'></i>\n\
                                                   <input type='text' name='hasta' id='finish' placeholder='Ingrese Fecha Fin' />\n\
                                            </label>\n\
                                        </section></fieldset>\n\
                                    <input type='submit' value='GENERAR' class='button'>\n\
                                  </footer>";
                                     $(function(){
                                                    $('#start').datepicker({
                                                            dateFormat: 'dd/mm/yy',
                                                            prevText: '<i class="icon-chevron-left"></i>',
                                                            nextText: '<i class="icon-chevron-right"></i>',
                                                            onSelect: function( selectedDate )
                                                            {
                                                                    $('#finish').datepicker('option', 'minDate', selectedDate);
                                                            }
                                                    });
                                                    $('#finish').datepicker({
                                                            dateFormat: 'dd/mm/yy',
                                                            prevText: '<i class="icon-chevron-left"></i>',
                                                            nextText: '<i class="icon-chevron-right"></i>',
                                                            onSelect: function( selectedDate )
                                                            {
                                                                    $('#start').datepicker('option', 'maxDate', selectedDate);
                                                            }
                                                    });
                                                });
                        }
                        if (proceso == '6'){
                            document.getElementById("p1").innerHTML="<footer>\n\
                                                        <input type='hidden' name='tipo' value='inven_portafolio'>\n\
                                                        <input type='submit' value='GENERAR' class='button'>\n\
                                                    </footer>";
                }
                };
                
                function validateForm()
                {
                                           
                        var x=document.forms["proceso"]["archivo"].value;
                        var xa=x.substring(x.lastIndexOf("."));
                          if (x==null || x=="")
                        {
                          alert('Debe adjuntar archivo Excel');
                          return false;
                        }
                           if (xa!=".xls" && xa!=".xlsx")
                        {
                          alert('El formato es incorreco ('+xa+')');
                          return false;
                        }
                          
                    
                        var y =document.forms["proceso"]["desde"].value;
                        var cli;
                        if (y==null || y=="")
                        {
                        alert("Debe Ingresar Fecha Inicio");
                        return false;
                        } 
                          var x=document.forms["proceso"]["hasta"].value;
                        var cli;
                        if (x ==null || x=="")
                        {
                        alert("Debe Ingresar Fecha Fin");
                        return false;
                        } 
                        
                                          
                   };
                    
                        
                   
                        	
        </script>
</html>




