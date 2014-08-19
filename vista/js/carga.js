// Se coloca <div class="progreso"></div> para que luego las funciones se encargen de lo demas


function start_progreso(){
    alert('start');
    $('.progreso').css({
        
        'font-size' : '15px',
        'background-repeat': 'no-repeat',
        'background-position': '90% 50%',
        'height': '470px'
    });

    $('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>Procesando ()</div>');
    //$('.progreso').html('<div style="margin: 0% 0%;"><img src="img/proceso.gif"></div>');
}

function add_progreso(val){
    alert(val);
    $('.progreso').css({
        
        'font-size' : '15px',
        'background-repeat': 'no-repeat',
        'background-position': '90% 50%',
        'height': '470px'
    });

    $('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>'+val+'%</div>');
    
}

function stop_progreso(){
    $('.progreso').css({
        
        'font-size' : '15px',
        'background-repeat': 'no-repeat',
        'background-position': '90% 50%',
        'height': '470px'
    });
    $('.progreso').html('<div style="margin: 29% 36%;"><img src="img/progress.gif"><img src="img/progress.gif"><img src="img/progress.gif"><br>Procesado (OK)<br>Por favor espere...</div>');
    $('.progreso').css({
    'background': 'none',
    'background-image': 'none',
    'background-repeat': 'none',
    'background-position': '0',
    'height': 'auto'
    });
}
