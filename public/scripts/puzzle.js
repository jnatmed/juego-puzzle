/**

 */
var canvas=document.getElementById("canvas");
var ctx = canvas.getContext("2d");
var empty=9;
var moves=-1;

/**
Mezclar arreglo: 
Se hace al principio del juego
 */
function shuffle(array) { 
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

	  return array;
	}

/**el arreglo va del 0 al 8, debido a que
el cero `0` representa la celda vacia. 
 */
var ar=[1,2,3,4,5,6,7,8,0];
im=shuffle([1,2,3,4,5,6,7,8,0]);

/**
aca busco cual es la celda que esta vacia
y guardo el valor en empty
 */
for(var i=0;i<=8;i++){
	if(im[i]==0)
		empty=i+1; // esta es la celda que esta vacia
}

var restart=0;

function won(){
	// a) limpia el ultimo cuadro 
	ctx.clearRect(300,300,150,150); 
	// b) capturo la ultima pieza que no se usa en toda la partida
	var img=document.getElementById("puzz9");
	var pat=ctx.createPattern(img,"repeat");
	ctx.fillStyle=pat;
	// c) la agrego al canvas para completar la imagen
	ctx.fillRect(300,300,150,150); 
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
}

/**
Cada vez que dibuja, 
- aumenta moves en +1
- tomo el elemento moves y le sumo un +1 
- vacio el elemento message

 */

function draw(){
	
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

  if(restart==1){
	/**
	vuelvo a mezclar el arreglo de numeros
	 */
	  im=shuffle([1,2,3,4,5,6,7,8,0]);
		for(var i=0;i<=8;i++){
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
		
		console.log(empty);
		
	/**
	limpia el canvas
	 */	
	 ctx.clearRect(0,0,450,450); // LIMPIA TODO EL CANVAS
	  restart=0;
  }
  /**
	aca controlo el no haber llegado a un estado de exito
   */
	for(var i=0;i<9;i++){
		if(im[i]!=ar[i])
			t=1;	
	}
	/**
	mostrando en la consola los dos arreglos
	para que sirva como guia de como va avanzando la resolucion del 
	rompecabezas.
	 */
	console.log(im); // arreglo con estado actual del rompecabezas
	console.log(ar); // arreglo con las posiciones del [1..9] 

	/**
	 * la variable 3 en x e y, es valida solo para un arreglo de 3x3
	 * para casos de 4x4 cambia a x=4 e y=4, y asi con 5x5
	 */
	for(var i=0;i<3;i++){
    	for(var j=0;j<3;j++){
    		   component(i,j);    		
    	}
    }
	

	/**
	antes de controlar la variable `t` la muestro.
	 */
	console.log(t);
	
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
    z=x+3*y;
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
	 */
    ctx.fillRect(150*x,150*y,150,150);    
}

function moveup() {
	ctx.clearRect(0,0,450,450); 
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
    if(empty==7||empty==8||empty==9){
    	au=document.getElementById("no");
    	au.play();
    	moves--;
    	draw();
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
    	var curr=empty;
    	empty=empty+3;
    	var next=empty;
        im[curr-1]=im[next-1];
        im[next-1]=0;
		/**
		se manda a dibujar de nuevo todo el lienzo
		 */
        draw();
    	
    }

	/**
	finalmente muestro por consola, la celda vacia actual. 
	 */
    console.log(empty);
}

function movedown() {
	ctx.clearRect(0,0,450,450);
	if(restart==1)
	{
	
	draw();
	return;
	}
	if(empty==1||empty==2||empty==3) {
		au=document.getElementById("no");
    	au.play();
		moves--;
		draw();
    }
    else{
    	au=document.getElementById("cut");
    	au.play();
    	text="puzz";
    	var curr=empty;
    	empty=empty-3;
    	var next=empty;
        im[curr-1]=im[next-1];
        im[next-1]=0;
        draw();
        
    }
    
    console.log(empty);
    
}

function moveleft() {
	ctx.clearRect(0,0,450,450);
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
	if(restart==1)
	{
	
	draw();
	return;
	}
	
	if(empty==3||empty==6||empty==9) {
		au=document.getElementById("no");
    	au.play();
		moves--;  
		draw();
	    }
	    else{
	    	au=document.getElementById("cut");
	    	au.play();
	    	text="puzz";
	    	var curr=empty;
	    	empty=empty+1;
	    	var next=empty;
	        im[curr-1]=im[next-1];
	        im[next-1]=0;
	        draw();
	    	
	  
	    }
	  console.log(empty);
}

function moveright() {
	ctx.clearRect(0,0,450,450);
	if(restart==1)
	{
	moves--;
	draw();
	return;
	}
	  if(empty==1||empty==4||empty==7) {
		  au=document.getElementById("no");
	    	au.play();
		  moves--;
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
	    }console.log(empty);
	
	  }
	  
window.addEventListener('keydown', function (e) {
    key = e.keyCode;
    if(key==37){
		e.preventDefault();
		enviarInfo('left');
    	moveleft();
    }
    if(key==38){
		e.preventDefault();
		enviarInfo('up');
    	moveup();
    }
    if(key==39){
		e.preventDefault();
		enviarInfo('right');
    	moveright();
    }
    if(key==40){
		e.preventDefault();
		enviarInfo('down');
    	movedown();
    }
	if(key==83){
		e.preventDefault();
		enviarInfo('letra-s');
		start();
	}
    
});

function enviarInfo(movimiento){
	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			console.log(this.response);
		}
	}
	// data : {
	// 	action : movimiento,
	// }
	var data = {
		movimiento : movimiento
	}

	xhttp.open("POST", "envio-movimiento", true);
	// xhttp.open("POST");
	xhttp.send(data);
}

function start(){
	/**
	 * aca la idea es cargar el estado inicial del puzzle 
	 * pidiendo la informacion al servidor
	 * mediante ajax
	 */
draw();
}
ctx.font = "30px Arial";
ctx.fillText("Hit S to start the game",80,210);
