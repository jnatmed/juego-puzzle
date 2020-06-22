window.addEventListener('load', function(){
    var listaI = document.getElementsByClassName('imagenes_principales');
    for (let index = 0; index < listaI.length; index++) {
        const element = listaI[index];
        element.width = screen.width * 0.2 ;    
        element.height = screen.height * 0.2;
        element.style.display = 'inline-block';    
        element.style.padding = '10px';
        element.addEventListener('touchstart',function(){
            alert("me presionaste.!");
        },false)
    }
    var listaEnlaces = document.getElementsByClassName('enlacesImagenes');
    for (let index = 0; index < listaEnlaces.length; index++) {
        const element = listaEnlaces[index];
        element.href = element.href + '";ancho_pagina="' + screen.width + '"';
    }
}, false);

