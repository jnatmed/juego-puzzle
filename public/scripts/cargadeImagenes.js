window.addEventListener('load', function(){
    var listaI = document.getElementsByClassName('imagenes_principales');
    for (let index = 0; index < listaI.length; index++) {
        const element = listaI[index];
        element.width = screen.width * 0.2;    
        element.height = screen.height * 0.2;
        element.style.display = 'inline-block';    
        element.style.borderRadius = '10px';
        element.style.boxShadow = '0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)';

        element.addEventListener('click', function(){
            var listaIhidden = document.getElementsByClassName('imagenes_principales');
            for (let index = 0; index < listaIhidden.length; index++) {
                const imgaEsconder = listaIhidden[index];
                if(imgaEsconder.getAttribute('alt')!=element.getAttribute('alt')){
                    imgaEsconder.style.display = 'none';
                }
            }
            element.width = screen.width * 0.5;    
            element.height = screen.height * 0.6;
            var botonNivel3 = document.createElement('A');
            var botonNivel4 = document.createElement('A');
            var botonNivel5 = document.createElement('A');

            crearBoton(botonNivel3, 'Nivel 3x3', //innerHTML
                                    element.getAttribute('alt'), //nombreImagen
                                    '3'); //dificultad
            crearBoton(botonNivel4, 'Nivel 4x4', 
                                    element.getAttribute('alt'), 
                                    '4');
            crearBoton(botonNivel5, 'Nivel 5x5', 
                                    element.getAttribute('alt'), 
                                    '5');
            document.getElementById('botones_imagenes').appendChild(botonNivel3);
            document.getElementById('botones_imagenes').appendChild(botonNivel4);
            document.getElementById('botones_imagenes').appendChild(botonNivel5);
        });

    }
    
    

}, false);

function crearBoton(boton,inner, hrefName, hrefNivel){
    boton.innerHTML = inner;
    boton.style.padding = '5px';
    boton.style.margin = '10px';
    boton.style.aligntex = 'left';
    boton.style.borderRadius = '20px';
    boton.style.backgroundColor = '#B0C4DE';
    boton.style.display = 'flex';
    boton.width = '10px';
    boton.height = '10px';
    boton.href = '/jugar?ancho_pagina=' + screen.width + '&id_imagen=' + hrefName + '&dificultad='+ hrefNivel;
    boton.alignitems = 'center';
    return boton;
}

var mediaqueryList = window.matchMedia("(min-width: 700px)");
mediaqueryList.addListener(function(EventoMediaQueryList){
    var listaIhidden = document.getElementsByClassName('imagenes_principales');
    for (let index = 0; index < listaIhidden.length; index++) {
        const element = listaIhidden[index];
        if(element.style.display==='flex'){
            element.style.width = 'auto';
            element.style.height = '500px';
        }
    }
});