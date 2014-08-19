
<div class="sky-tabs sky-tabs-pos-top-left sky-tabs-anim-scale sky-tabs-response-to-icons">
<input type="radio" name="sky-tabs"  id="recibidas" class="sky-tab-content-1">
<label for="recibidas"><span><span><i class="fa fa-file-text-o"></i>Recibidas</span></span></label>

<input type="radio" name="sky-tabs" id="enviar" class="sky-tab-content-2">
<label for="enviar"><span><span><i class="fa fa-pencil"></i>Enviar</span></span></label>

<ul>
					<li class="sky-tab-content-1">					
                                    
                                            <form name="" onsubmit="" action="#" method="POST">	
                                                        <input type="hidden" name="form_notificaciones">
							<fieldset>				
								<div class="row">
									<section>
                                                                        
                                                                            <div id="recibidas_content" style="height: 200px; width: 100%; overflow: auto;">
                                                                                
                                                                                
                                                                                
                                                                            </div>
                                                                            <label class="textarea">
                                                                                    <i class="fa fa-append fa-comment"></i>
                                                                                    <textarea name="contenidoNoti" id="contenidoNoti" rows="5" readonly="readonly" placeholder="Contenido del SMS"></textarea>
                                                                            </label>
                        

                                                                             
                                                                         
                     
									</section>
                                                                        
								</div>
                                                        </fieldset>
                                                        </form>
						
					</li>
                                        
                                        
                                        
                                        <li class="sky-tab-content-2">					
						
                                            <form id="form-enviarNoti" name="" onsubmit="" action="#" method="POST">	
                                                        <input type="hidden" name="form_notificaciones">
							<fieldset>				
								<div class="row">
									<section>
                                                                        
                                                                            <div id="desti_content" style="height: 200px; width: 100%; overflow: auto;">
                                                                                
                                                                                
                                                                                
                                                                            </div>
                                                                            <label class="textarea">
                                                                                    <i class="fa fa-append fa-comment"></i>
                                                                                    <textarea name="enviar-content" id="enviar-content" rows="4" maxlength="250" placeholder="Contenido del SMS"></textarea>
                                                                            </label>
                        

                                                                             
                                                                            <button class="button" id="btnEnviarNoti" type="button">Enviar</button>
                     
									</section>
                                                                        
								</div>
                                                        </fieldset>
                                                        </form>
                                            
					</li>
                                        
                                        
                                        

</div>


<script src="../controlador/c_notificaciones.js"></script>
<script>

    $('#recibidas').click(function () { cargarNotificaciones(); });
    $('#enviar').click(function () { cargarDestinatarios(); });
    $('#btnEnviarNoti').click(function () { enviarNotificacion(document.getElementsByName('checkDesti'), document.getElementById('enviar-content').value); });
    
    
    
    

</script>

<style>
    
    .sinLeer {
        
        background: dodgerblue !important;
   
        
    }
    
    .leido {
        
        background: skyblue !important;
        
    }
    
</style>