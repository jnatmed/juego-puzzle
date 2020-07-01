// import {Matriz} from './matriz';
var cantElementos = parseInt(document.getElementById('cantElementos').value);
var canvas=document.getElementById("canvas");
var ctx = canvas.getContext("2d");
var empty=cantElementos;
var moves=-1;


/**
 * DEFINICION DE CLASE
 */
class Matriz {
	constructor(){
		this.rangos = [];
		this.combinaciones = [];
	}
    range(ini,fin){
		var a = new Array();
		// console.log('ini:'+ ini);
		// console.log('ini:'+ fin);
        for(var i = ini; i <= fin; i++){
			a.push(i);
            // console.log('i:'+ a);  
        }
        return a;       
	}
	setRangos(rangos){
		for (let index = 0; index < rangos.length; index++) {
			const element = rangos[index];
			this.rangos.push(this.range(element['x'], element['y']));
		}		
	}

	estaEnSector(x,y){
		console.log("estaEnSector: ");
		var rangoX, rangoY, coordenadasXeY;
		for (let index = 0; index < this.rangos.length; index++) {
			const element = this.rangos[index];
			console.debug(element);
			if (element.indexOf(x)!=-1){
				console.log("encontre x en rango: (" + element[0] + ":"+ element[element.length-1] + ")");	
				rangoX = '('+ element[0] +','+ element[element.length-1] +')';
			}
			if (element.indexOf(y)!=-1){
				console.log("encontre y en rango: (" + element[0] + ":" + element[element.length-1] + ")");	
				rangoY = '('+ element[0] +','+ element[element.length-1] +')';
			}
		}
		coordenadasXeY = rangoX + ':' + rangoY;
		console.log(coordenadasXeY);
		console.log('sector tocado: ' + this.combinaciones[coordenadasXeY]);
		return this.combinaciones[coordenadasXeY];
	}
	setCombinaciones(combinaciones){
		this.combinaciones = combinaciones;
	}
	getRangos(){
		return this.rangos;
	}
	getCombinaciones(){
		return this.combinaciones;
	}
	getRangos(){
		return this.rangos;
	}
}
/*
Mezclar arreglo: 
Se hace al principio del juego
 */
function barajar(array) { 
	/**
	 currentIndex => igual al largo total del arreglo, en este caso = 9 elementos
	 randomIndex = sera un posible indice aleatorio.
	 temporaryValue = una variable auxiliar
	 */
	  var currentIndex = array.length, temporaryValue, randomIndex;

	  // Mientras haya elementos para mezclar
	  while (currentIndex !== 0) {

	    // Tomar un elemento de forma aleatoria
	    randomIndex = Math.floor(Math.random() * currentIndex);
	    currentIndex -= 1;

		// Intercambia las posiciones.
	    temporaryValue = array[currentIndex];
	    array[currentIndex] = array[randomIndex];
	    array[randomIndex] = temporaryValue;
		/**
		Pero puede ocurrir que el resultado de esta mezcla
		caiga en un orden con exito
		 */
	  }
	  console.log('ARREGLO BARAJADO: ' + array);
	  console.log('dentro de funcion barajar => ar: ' + ar);
	  return array;
	}

/**el arreglo va del 0 al 8, debido a que
el cero `0` representa la celda vacia. 
 */


function crearArregloOrdenado(){
	var cantElementos = parseInt(document.getElementById('cantElementos').value);
	var arregloOrdenado=[];
	for (let index = 0; index < (cantElementos -1) ; index++) {
		arregloOrdenado[index] =  index + 1; 
	}
	arregloOrdenado[(cantElementos-1)] = 0;	
	return arregloOrdenado;
}


/**
 * implementacion para jugabilidad en teclado
 */
function listaProhibidos(dificultad){
	var listaMoveUp=new Array;
	var listaMoveDown=new Array;
	var listaMoveLeft=new Array;
	var listaMoveRight=new Array; 
	/**
	 *Prohibidos moveup
	 */
	for (let index = 0; index < dificultad ; index++) {
		listaMoveUp.push((dificultad*dificultad)-index);		
	}
	/**
	 * Prohibidos movedown
	 */
	for (let index = 1; index <= dificultad; index++) {
		listaMoveDown.push(index);
		
	}
	/**
	 * Prohibidos moveleft
	 */
	for (let index = 0; index < dificultad; index++) {
		listaMoveLeft.push(dificultad+(dificultad * index));
		
	}
	/**
	 * Prohibidos moveright
	 */
	for (let index = 0; index < dificultad; index++) {
		listaMoveRight.push((dificultad*index)+1);
		
	}
	return {
		'moveup' : listaMoveUp,
		'movedown' : listaMoveDown,
		'moveleft' : listaMoveLeft,
		'moveright' : listaMoveRight
		}
}


var im=JSON.parse(document.getElementById('estado_actual').getAttribute('value'));
var ar=crearArregloOrdenado();
var dificultad = parseInt(document.getElementById('dificultad').value);
var mov_prohibidos = listaProhibidos(dificultad);
console.log('im: ' + im);
console.log('ar: ' + ar);
console.log(mov_prohibidos['movedown']);


function fondoIncorrecto(){
	
	// document.body.style.backgroundImage = "url('public/imgs/fuego.jpg')";
}

/**
aca busco cual es la celda que esta vacia
y guardo el valor en empty
 */

for(var i=0;i<=(cantElementos-1);i++){
	if(im[i]==0)
		empty=i+1; // esta es la celda que esta vacia
}

var restart=0;juegoNuevo=true;

function won(){
	// a) limpia el ultimo cuadro 
	var dificultad = parseInt(document.getElementById('dificultad').value);

	var x1 = parseInt(document.getElementById('tamanio_pieza'));
	var x0 = parseInt(document.getElementById('canvas').getAttribute('height')) - x1;
	ctx.clearRect(x0,x0,x1,x1); 
	// b) capturo la ultima pieza que no se usa en toda la partida
	var cantElementos = document.getElementById('cantElementos').value;
	var img=document.getElementById("puzz"+cantElementos);
	var pat=ctx.createPattern(img,"repeat");
	ctx.fillStyle=pat;
	// c) la agrego al canvas para completar la imagen
	ctx.fillRect(x0,x0,x1,x1); 
	// d) muestro un mensaje de juego ganado
	m=document.getElementById("message");
	m.innerHTML="You won the game in "+moves.toString() +" moves";
	// e) y emito sonido de felicitaciones
	au=document.getElementById("cheers");
	au.play();
	// f) pongo el restar en 1
	restart=1;
	// g) pongo movimientos en -1
	moves=-1;

	enviarInfo(dificultad,im);

}

/**
Cada vez que dibuja, 
- aumenta moves en +1
- tomo el elemento moves y le sumo un +1 
- vacio el elemento message

 */

function draw(){
	// console.log("/*************NUEVO MOVIMIENTO***********/");
	moves++;
	mov=document.getElementById("moves");
	mov.innerHTML="MOVES: "+ moves.toString();
	m=document.getElementById("message");
	m.innerHTML="";
  var t;
  /**
  crea una variable t => 0 en cero
  controlo que restart sea igual a 1
  vuelvo a mezclar si es asi. 
  por cada movimiento, si se llego a un estado 
  de exito, entonces mando a dibujar y barajar de nuevo el arreglo
   */ 
  t=0;

  var cantElementos = parseInt(document.getElementById('cantElementos').value);

  if(restart==1){
	/**
	vuelvo a mezclar el arreglo de numeros
	 */
	var array=[];
	for (let index = 0; index < (cantElementos -1) ; index++) {
		array[index] =  index + 1; 
	}
	array[(cantElementos-1)] = 0;
  
	im=barajar(array);
	for(var i=0;i<=(cantElementos -1);i++){
		/**
		si la posicion `i` es igual a 0
		le sumo la posicion + 1 en la . Ej: si en la 
		posicion 0 hay un cero, le coloco el siguiente a 
		la variable `empty` . 
		siempre le coloco el siguiente. 
			*/
		if(im[i]==0)
			empty=i+1;							
	}
	
	// console.log('valor de empty: ' + empty);
		
	/**
	limpia el canvas
	 */	
	var anchoCanvas = parseInt(document.getElementById('canvas').width);
	 ctx.clearRect(0,0,anchoCanvas,anchoCanvas); // LIMPIA TODO EL CANVAS
	  restart=0;
  }
  /**
	aca controlo el no haber llegado a un estado de exito
   */
	// console.log('ANTES DEL CONTROL t => ' + t);
  	// console.log("cantElementos: " + cantElementos);
	for(var i=0;i<cantElementos;i++){
		if(im[i]!=ar[i]){
			t=1;	
			// console.log("Los arreglos son DINSTINTOS");
		}
	}

	// console.log('DESPUES DEL CONTROL t => ' + t);
	/**
	mostrando en la consola los dos arreglos
	para que sirva como guia de como va avanzando la resolucion del 
	rompecabezas.
	 */
	// console.log(im); // arreglo con estado actual del rompecabezas
	// console.log(ar); // arreglo con las posiciones del [1..9] 

	/**
	 * la variable 3 en x e y, es valida solo para un arreglo de 3x3
	 * para casos de 4x4 cambia a x=4 e y=4, y asi con 5x5
	 */
	var dificultad = parseInt(document.getElementById('dificultad').value);
	for(var i=0;i<dificultad;i++){
    	for(var j=0;j<dificultad;j++){
    		   component(i,j);    		
    	}
    }
	

	/**
	antes de controlar la variable `t` la muestro.
	 */
	
	/**
	Finalmente si, t==0 entonces significar que llegue a un estado 
	de exito. 
	 */
	if(t==0){
		console.log("one more");
		won();
	}
	
}

/**
	dibujo de nuevo los bloques de la imagen en el canvas
 */

function component(x, y) {
    
	/**
	aqui se realiza un calculo sensillo
	donde por tomando los valores de 
	x => [0,1,2] e y => [0,1,2]
	se genera el rango de numeros del 1 al 9 [1,2,3,4,5,6,7,8,9]
	
	 */
	var dificultad = parseInt(document.getElementById('dificultad').value);

    z=x+dificultad*y;
    z=im[z];
	/**
	se lo concatena a la cadena `puzz` para hacer referencia a cada uno de los 
	identificadores de cada bloque de la imagen.
	 */
    var text="puzz";
    text=text+z.toString();
	/**
	si `z` es cero, se dibuja un blanco a partir de las coordenadas x e y
	sino se copia el patron de la imagen correspondiente y 
	 */

	
    if(z!=0)
    {
    	var img=document.getElementById(text);
    	var pat=ctx.createPattern(img,"repeat");
    	ctx.fillStyle=pat;
    }
    
    else{
		/**
		pinto de blanco la celda
		 */
    	ctx.fillStyle="white";
    }
    
	/**
	fillRect es la combinacion de dos metodos que se pueden usar por separado
	fill() y rect(x0,y0,x1,y1)
	150 => es el tamaño de la pieza, y las medidas de 450 x 450 
	155 => si el puzzle fuera de 4x4 y las medidas 620 x 620 
	126 => si el puzzle fuera de 5x5 y las medidas 630 x 630
	 */
	var tamanio_pieza = parseInt(document.getElementById('tamanio_pieza').value);
	ctx.fillRect(tamanio_pieza*x,tamanio_pieza*y,tamanio_pieza,tamanio_pieza);  
}

function moveup() {
	/**
	 * x => 450 e y => 450, donde 450 es el tamaño maximo del arreglo
	 */
	var anchoCanvas = document.getElementById('canvas').width;
	ctx.clearRect(0,0,anchoCanvas,anchoCanvas); 

	if(restart==1)
	/**
	 * en que momento se ejecuta esta parte del codigo?
	 * si cada vez que dibujo controlo si gano, y 
	 * de ser asi ya muestro el canvas
	 * en estado de exito.
	 * 
	 */
		{
		draw();
		return;
		}
	/**
	 * para movimiento prohibidos al moverse hacia arriba si el puzzle es de: 
	 * 3x3 => 7, 8, 9
	 * 4x4 => 
	 * 5x5 => 
	 *  */	
    if(mov_prohibidos['moveup'].indexOf(empty)!=-1){
    	au=document.getElementById("no");
    	au.play();
		moves--;
    	draw();
		fondoIncorrecto();
    } 
    else{
		/**
		por aca viene los sonidos. Si el movimiento es permitido
		se hace un sonido de movimiento de pieza
		 */
    	au=document.getElementById("cut");
    	au.play();
    	text="puzz";
		/**
		aca ocurre el intercambio 
		en el caso de empty = 0 
		quedaria: 
		im(0) = im(3)
		im(3) = 0

		datos => curr: es current osea celda vacia actual
				 next: es proxima celda vacia 
		 */
		var dificultad = parseInt(document.getElementById('dificultad').value);

    	var curr=empty;
    	empty=empty+dificultad;
    	var next=empty;
        im[curr-1]=im[next-1];
        im[next-1]=0;
		/**
		se manda a dibujar de nuevo todo el lienzo
		 */
        draw();
		
		enviarInfo(dificultad,im);

    }

	/**
	finalmente muestro por consola, la celda vacia actual. 
	 */
    // console.log('valor de empty: ' + empty);;
}

function movedown() {
	var anchoCanvas = document.getElementById('canvas').width;
	ctx.clearRect(0,0,anchoCanvas,anchoCanvas);
	if(restart==1){
		draw();
		return;
	}
	if(mov_prohibidos['movedown'].indexOf(empty)!=-1) {
		au=document.getElementById("no");
    	au.play();
		moves--;
		fondoIncorrecto();
		draw();
    }else{
    	au=document.getElementById("cut");
    	au.play();
    	text="puzz";
		var curr=empty;
		var dificultad = parseInt(document.getElementById('dificultad').value);

    	empty=empty-dificultad;
    	var next=empty;
        im[curr-1]=im[next-1];
        im[next-1]=0;
		draw();        
		
		enviarInfo(dificultad,im);

    }
    
    // console.log('valor de empty: ' + empty);;
    
}

function moveleft() {
	var anchoCanvas = document.getElementById('canvas').width;
	let dificultad = parseInt(document.getElementById('dificultad').getAttribute('value'));

	ctx.clearRect(0,0,anchoCanvas,anchoCanvas);
	/**
	 * limpio canvas => (x-ini, y-ini, x-fin, y-fin)
	 * 
	 * si (restart esta en 1) {
	 * 		vuelvo a dibujar todo el canvas desde cero
	 * }sino: si(es movimiento prohibido){
	 * 		rechazar movimiento	
	 * }sino{
	 * 		guardar movimiento
	 * }
	 * 
	 * arreglo de movimientos prohibidos
	 * para 3x3-izquierda: 3,6,9
	 */
	if(restart==1){
		draw();
		return;
	}
	
	if(mov_prohibidos['moveleft'].indexOf(empty)!=-1) {
		au=document.getElementById("no");
    	au.play();
		moves--;  
		fondoIncorrecto();
		draw();
	}else{
		au=document.getElementById("cut");
		au.play();
		text="puzz";
		var curr=empty;
		empty=empty+1;
		var next=empty;
		im[curr-1]=im[next-1];
		im[next-1]=0;
		draw();   	

		enviarInfo(dificultad,im);

	}
	// console.log('valor de empty: ' + empty);;
}

function moveright() {
	var anchoCanvas = document.getElementById('canvas').width;
	let dificultad = parseInt(document.getElementById('dificultad').getAttribute('value'));

	ctx.clearRect(0,0,anchoCanvas,anchoCanvas);
	if(restart==1){
		moves--;
		draw();
		return;
	}
	if(mov_prohibidos['moveright'].indexOf(empty)!=-1) {
		  au=document.getElementById("no");
	    	au.play();
		  moves--;
		  fondoIncorrecto();
		  draw();
	    }
	    else{
	    	au=document.getElementById("cut");
	    	au.play();
	    	text="puzz";
	    	var curr=empty;
	    	empty=empty-1;
	    	var next=empty;
	        im[curr-1]=im[next-1];
	        im[next-1]=0;
			draw();

			enviarInfo(dificultad,im);
		}
	  }
	  
window.addEventListener('keydown', function (e) {
    key = e.keyCode;
    if(key==37){
		e.preventDefault();
		moveleft();
		mostrarMovimiento(getMarcaTiempo(), im);

    }
    if(key==38){
		e.preventDefault();
		moveup();
		mostrarMovimiento(getMarcaTiempo(), im);

    }
    if(key==39){
		e.preventDefault();
		moveright();
		mostrarMovimiento(getMarcaTiempo(), im);
    }
    if(key==40){
		e.preventDefault();
		movedown();
		mostrarMovimiento(getMarcaTiempo(), im);
    }
	if(key==83){
		e.preventDefault();
		start();
		mostrarMovimiento(getMarcaTiempo(), im);
	}
    
});

function mensajeRespuesta(mensaje){
	/**
	 * cambio color de fondo del contenedor id=`mensajes_estado`
	 * creo h2 con display success
	 * creo p con display Movimiento valido.!
	 */
	var article_msj = document.getElementsByClassName('mensaje_error');
	
	// article_msj.style.display = 'flex';
	// article_msj.style.width = ''+screen.width * 0.33+'px'; 	

	var p = document.getElementById('mensaje');

	// mensaje.style.color = 'white';
	switch(mensaje){
		case 'OK':
			// article_msj.style.backgroundColor = '#4CAF50';
			p.innerHTML = 'Movimiento Correcto..'; 
			break;
		case 'TRAMPA':
			// article_msj.style.backgroundColor = '#F44336';
			p.innerHTML = 'El jugador Hizo Trampa..'; 			
			break;
		default: 
			// article_msj.style.backgroundColor = '#3693D8';
			p.innerHTML = 'ni ok ni trampa, entonces que?..';
	}																					
}

function enviarInfo(dificultad, estadoJuego){
	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			var respuesta = JSON.parse(this.response);
			// var respuesta = this.response;
			console.log(respuesta);
			// console.log('recibo control_movimiento: ' + respuesta['control_movimiento']);
			// console.log('recibo marca_de_tiempo: ' + respuesta['marca_de_tiempo']);
			// console.log('recibo sector_vacio: ' + respuesta['sector_vacio']);
			// console.log('recibo nuevo_estado_actual: ' + respuesta['nuevo_estado_actual']);
			// console.log('recibo estados_futuros: ' + respuesta['estados_futuros']);
			mensajeRespuesta(respuesta['control_movimiento']);
		}
		if(this.readyState == 2){
			console.log("response header received")
		}
	}
	/**
	 * cargo en un FormData los datos que vamos a enviar
	 * mediante Ajax: 
	 *  - el movimiento
	 *  - el tiempo en que realizo el movimiento
	 */
	var marcadeTiempo = getMarcaTiempo();
	var estadoJuego = JSON.stringify(estadoJuego);
	console.log('envio estadoJuego: ' + estadoJuego);
	console.log('setor_vacio: ' + empty);

	var dificultad = parseInt(document.getElementById('dificultad').getAttribute('value'));	
	var data = new FormData();
	data.append('marca_de_tiempo', marcadeTiempo);
	data.append('nuevo_estado_actual', estadoJuego);
	data.append('sector_vacio', empty);
	data.append('dificultad', dificultad);
	
	
	xhttp.open("POST", "enviar_movimiento");
	xhttp.send(data);
}

function start(){
	/**
	 * aca se carga el estado inicial del puzzle 
	 * pidiendo la informacion al servidor
	 * mediante ajax
	 */
	// console.log("/*****NUEVO JUEGO*******/");
	draw();
}

function mostrarMovimiento(marcadeTiempo, estadodeJuego){
	var tag_lista_Movimientos = document.getElementById('lista_movimientos');
	var cont = tag_lista_Movimientos.childElementCount;	
	var item = document.createElement('li');
	item.innerHTML = "Tiempo: "+ marcadeTiempo  + " Estado del Juego: " + estadodeJuego;
	var listaLI = tag_lista_Movimientos.getElementsByTagName('li');
	if(cont<3){
		tag_lista_Movimientos.appendChild(item);
	}else{
		tag_lista_Movimientos.removeChild(tag_lista_Movimientos.firstChild)
		tag_lista_Movimientos.appendChild(item);
	}	
}	

function getMarcaTiempo(){
	var today = new Date();
	return (today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds()+":"+today.getMilliseconds());
}

canvas.addEventListener('touchstart',function(evt){
	responsivo(evt);
}, true);


function responsivo(evt){
	
	matriz = new Matriz();

	var dificultad = parseInt(document.getElementById('dificultad').value);

	var rangos = JSON.parse(document.getElementById('matriz').getAttribute('value'));
	var combinaciones = JSON.parse(document.getElementById('combinaciones').getAttribute('value'));
	var movPermitidos = JSON.parse(document.getElementById('movPermitidos').getAttribute('value'));
	// console.log('MATRIZ : ' + JSON.stringify(rangos));
	// console.log('COMBINACIONES : ' + JSON.stringify(combinaciones));
	// console.log('MOV PERMITIDOS : ' + JSON.stringify(movPermitidos));	

	matriz.setCombinaciones(combinaciones);
	matriz.setRangos(rangos);	
	
	var bcr = evt.target.getBoundingClientRect();
	var x = parseInt(evt.targetTouches[0].clientX - bcr.x);
	var y = parseInt(evt.targetTouches[0].clientY - bcr.y);
	var sector = matriz.estaEnSector(x,y)
	
	if (!juegoNuevo){
		if(movPermitidos[sector].indexOf(empty)!=-1){
	
			sonido = document.getElementById("cut");
			sonido.play();
			var valor_actual = empty;
			empty=sector;
			var proxima_celda_vacia = empty;
			im[valor_actual-1]=im[proxima_celda_vacia - 1];
			im[proxima_celda_vacia-1]=0;
			draw();
			 
			enviarInfo(dificultad,im);

			mostrarMovimiento(getMarcaTiempo(), im);
	
		}else{
	
			// console.log("movimiento no permitido")
			sonido = document.getElementById("no");
			sonido.play();
			fondoIncorrecto();
		}
	}else{
		juegoNuevo = false;
		// restart = 1;
		start();
	}
	// },true);
}

ctx.font = "30px Arial";
ctx.fillText("Hit S to start the game",80,210);
