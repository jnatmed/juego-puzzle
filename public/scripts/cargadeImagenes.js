window.addEventListener('load', function(){
    var listaI = document.getElementsByClassName('imagenes_principales');
    for (let index = 0; index < listaI.length; index++) {
        const element = listaI[index];
        element.width = screen.width * 0.2;    
        element.height = screen.height * 0.2;
        element.style.display = 'inline-block';    
        element.style.padding = '10px';

    }
    var listaEnlaces = document.getElementsByClassName('enlacesImagenes');
    // console.debug(listaEnlaces);
    for (let index = 0; index < listaEnlaces.length; index++) {
        const element = listaEnlaces[index];
        element.href = element.href + '&ancho_pagina=' + screen.width;
        // element.style.pointerEvents = 'none';
        // console.log('childNodes[0]: ' + element.childNodes[0]);
        // var fisrtChild = element.firstChild;
        // alert(element.firstChild.hasChildNodes);
    //     element.addEventListener('onclick',function(){
    //         var listaI = document.getElementsByClassName('imagenes_principales');
    //         for (let index = 0; index < listaI.length; index++) {
    //             const element = listaI[index];
    //             element.style.display = 'none';
    //             // console.log(element.style.active)
    //         }

    //     },false)
    }

    var imgs = document.getElementsByClassName('imagenes_principales');
    for (let index = 0; index < imgs.length; index++) {
        const element = imgs[index];
        // console.log(element);
        element.addEventListener('click',function(){
            var a = document.createAttribute('a');
            a.appendChild(element);
            a.title = element.getAttribute('alt');
            a.href = "/jugar?id_imagen=" + a.title;
            document.body.appendChild(a);
        });
    }
}, false);

