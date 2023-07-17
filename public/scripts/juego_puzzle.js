function $(idpiezas) {
  return document.getElementById(idpiezas);
}

function $create(idpiezas,width, height) {
  idpiezas.width = width;
  idpiezas.height = height;
  idpiezas.margin = 0;
  return idpiezas;
}

function consola(msj){
  console.log(msj)
}

function eliminarHijos(id){
  while (id.firstChild) {
    id.removeChild(id.firstChild);
  }
}


class Juego {

  constructor(piezas, puzzle, terminado, url_data){
    this.piezas = piezas;
    this.puzzle = puzzle;
    this.terminado = terminado;
    this.currentCanvas = null;
    this.estadoPiezas = [];
    this.estadoPiezas.fill(-1);
    this.estadoPuzzle = [];
    this.estadoPuzzle.fill(-1);
    this.url_data = url_data;
  }
  /* 
  *  guardo un parte de la imagen y la devuelvo
  */
  dibujarImagenEnCanvas(canvas, xOrigen, yOrigen){
    let ctx = canvas.getContext("2d");
    let image = new Image();
    image.src = this.getUrl_data();  
    
    let [anchoOrigen, anchoDestino, altoOrigen, altoDestino] = [100,100,100,100];              
    let [xDestino, yDestino] = [0,0];
  
    ctx.drawImage(image,
                xOrigen, yOrigen, //coordenada x e y en imagen origen
                anchoOrigen, altoOrigen, //cant pixeles en ancho y alto que quiero tomar
                xDestino, yDestino, //coordenada x e y en destino
                anchoDestino, altoDestino//cuanto va a ocupar la imagen en destino
    );
    return canvas;
  }

  /**
   *  GETTERs/SETTERs
   */
  getEstadoPiezas(){return this.estadoPiezas;}
  setEstadoPiezas(estadoPiezas) { this.estadoPiezas = estadoPiezas; }
  getEstadoPuzzle(){return this.estadoPuzzle;}
  setEstadoPuzzle(estadoPuzzle) { this.estadoPuzzle = estadoPuzzle; }
  setPuzzle(puzzle) { this.puzzle = puzzle; }
  getPuzzle() {return this.puzzle; }
  setPiezas(piezas) { this.piezas = piezas; }
  getPiezas() { return this.piezas; }
  getUrl_data() { return this.url_data; }
  setCurrentCanvas(cCanvas) { this.currentCanvas = cCanvas; }
  getTerminado() { return this.terminado; }
  setTerminado(terminado) { this.terminado = terminado; }


  getHijos(piezas){
    return $(piezas).childNodes;
  }

  crearEventosDeMouse(){

    this.piezas.addEventListener('dragstart', e => {
      e.dataTransfer.setData('id', e.target.id);
      e.target.style = 'cursor: move';
      consola(`id ${e.target.id}`);
    });
    
    this.piezas.addEventListener('mouseover', e => {
      e.target.style = 'cursor: move';
    });

    this.puzzle.addEventListener('dragover', e => {
      e.preventDefault();
      e.target.classList.add('hover');
      e.target.style = 'cursor: pointer';
    });
    
    this.puzzle.addEventListener('dragleave', e => { 
      e.target.classList.remove('hover');
      e.target.style = 'cursor: pointer';
    });
    
    this.puzzle.addEventListener('drop', e => {
      e.target.classList.remove('hover');
      const id = e.dataTransfer.getData('id');
      console.log(`Id : ${id}`);
      const numero = id.split('_')[1];
      console.log(`e.target.id: ${e.target.id}`);
      if(e.target.id === numero){
        e.target.appendChild($(id));
        this.guardarEstadoJuego();
        this.setTerminado(this.getTerminado() - 1 );
        if (this.getTerminado() === 0) {
          document.body.classList.add('ganaste');
        }
      }
    });
    
  }

  crearPlaceHolder(){
    for (let i = 0; i < this.terminado; i++) {
      const div = document.createElement('div');
      div.className = 'placeholder';
      div.id = i;
      /**
       *  variable externa:
       *        - puzzle
       */
      this.puzzle.appendChild(div);
      console.log(this.puzzle);
      let placeHolder = $(i); 
    
      placeHolder.addEventListener('touchstart', e => {
        // seleccionar el contenedor que identifica 
        // al placeHolder. 
        const placeHolderSeccionado = $(e.changedTouches[0].target.id);
        consola("placeHolder seleccionado : " + placeHolderSeccionado.id)
        /**
         *  variables externa: 
         *      - currentCanvas
         * el identificador del currentCanvas es xejemplo 
         * canvas_1 con el split se conviete en ['canvas', '1']
         * la posicion [1] tiene el numero de canvas
         */
        if ($(this.currentCanvas).id.split('_')[1] == placeHolderSeccionado.id){
            placeHolderSeccionado.appendChild($(this.currentCanvas))
            this.guardarEstadoJuego();
        }
      })
    }
  }

  crearCanvasyAsignarPiezas(){

    const coordXOrigen = [0,101,201];
    const coordYOrigen = [0,101,201];
    let fila = 0;

    for(let i = 0; i < coordYOrigen.length; i = i + 1 ) {
            
      for(let j = 0; j < coordXOrigen.length; j = j + 1 ) {

            // creo un canvas
            let canvas = document.createElement('canvas');
            // configuro las dimensiones: alto, ancho y margen
            $create(canvas,100,100);
            // le asigno un id
            /**
             * la cuenta seria asi: 
             *   i = [0, 1, 2]
             *   fila = [0, 3, 6]  
             *         [iteracion 0] => (0 + 0) = 0, (1 + 0) = 1, (2 + 0) = 2
             *         [iteracion 1] => (0 + 3) = 4, (1 + 3) = 4, (2 + 3) = 5
             *         [iteracion 2] => (0 + 6) = 6, (1 + 6) = 7, (2 + 6) = 8
             */
            canvas.id = "canvas_" + (j + fila);
            console.log(`(j = ${j} + fila = ${fila}) = ${j + fila}`)
            // le asigno una clase
            canvas.className = 'pieza';
            // dibujo un fragmento de la imagen en ese canvas
            canvas = this.dibujarImagenEnCanvas(canvas, coordXOrigen[j], coordYOrigen[i]);
            // lo hago arrastrable
            canvas.draggable = true;
            // asigno un evento de tactil para cuando se toca un canvas
            canvas.addEventListener('touchstart', e => {
              // al comenzar a arrastrar, identifico el canvas tocado
              const canvaSeccionado = $(e.changedTouches[0].target.id).id;
              consola("canvas seleccionado : " + canvaSeccionado)
              consola("0 " + e)
              // guardo temporalmente el canvas tocado
              this.currentCanvas = canvaSeccionado;
              let ctx = $(canvaSeccionado).getContext("2d");
              // muestro un cartel de canvas seleccionado
              let cartel = "Seleccionado";
              ctx.fillText(cartel,
                          20,
                          $(canvaSeccionado).clientHeight/2)
            })
            // asigno un evento tactil para cuando se mueve el canvas
            canvas.addEventListener('touchmove', e => {
              consola(this.currentCanvas.id);
              /**
               * por cada evento de toque en la pantalla, tomo al 
               * canvas selecionado, siendo el mismo el que guarde en 
               * el currentCanvas y le voy asignando las coordenadas 
               * de los eventos de toque, en la medida que se van creando. 
               */
              [...e.changedTouches].forEach(touch => {
                const dot = $(this.currentCanvas);
                dot.style.top = `${touch.clientY - dot.clientHeight/2}px`
                dot.style.left = `${touch.clientX - dot.clientWidth/2}px`
              })
      
            })
            // agrego el canvas a contenedor de piezas
            console.log(canvas)
            console.log(this.getPiezas());
            const divCanva = document.createElement('div');
            divCanva.className = 'divCanva';
            divCanva.id = `${j + fila}` ;
            divCanva.appendChild(canvas);
            this.getPiezas().appendChild(divCanva); 
      } // FIN FOR j
      fila = fila + 3;
      console.log(`fila = ${fila}`)
    } // FIN FOR i
          
  } // FIN METODO  



  enviarMensaje(dato) {

      if(dato['estado_puzzle'] !== undefined){
        console.log("contenido estado puzzle")
        console.table(dato['estado_puzzle']);
      }else{
        console.log("contenido estado piezas")
        console.table(dato['estado_piezas']);
      }
      
      fetch("/enviarEstado", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify(dato)
      })

      .then((response) => {
        // response.ok sera true con respuestas 2xx
        if (!response.ok) {
            throw new Error(`Hubo un Error HTTP : ${response.status}`);
          }
        return response.json();
        })
      .then((data) => console.log(`data : ${data}`))
      .catch((error) => console.log(`error : ${error}`)); 

  }

  /**
   */
  actualizarMatriz(array, estadoMatriz){

    array.childNodes.forEach((div, cont) => {
        if(cont != 0){
            if (div.childNodes.length > 0){
                estadoMatriz[div.id] = div.childNodes[0].id;
            }else{
                estadoMatriz[div.id] = -1;
            }
        }
    });

    return estadoMatriz;
  } 

  /**
   * SECTOR PUZZLE : 
   * ESTA MATRIZ ES LA QUE MANDO AL BACKEND PARA GUARDAR EL ESTADO DEL 
   * JUEGO ACTUAL
   *  - ENVIO LAS MATRICES : PUZZLE Y PIEZAS
   * PUZZLE : TIENE EL ESTADO DE AVANCE DE LA PARTIDA ACTUAL
   * PIEZAS : TIENE LAS PIEZAS QUE TODAVIA NO FUERON 
   * UBICADAS EN EL PUZZLE 
   */
  guardarEstadoJuego(){
    
    let estadoPieza = [];   
    let estadoPuzzle = [];  

    /**
     * SECTOR PIEZAS
     * aca actualizo la matriz cada vez que se realiza un movimiento
     * 1) uso una matriz auxiliar y luego seteo la matriz
     */
    estadoPieza = this.actualizarMatriz(this.getPiezas(), this.getEstadoPiezas());

    this.setEstadoPiezas(estadoPieza);

    console.log('SECCION Piezas');
    /**
     * 2) envio del estado de las piezas al backend
     * para que se guarde en la base de datos
     */
    this.enviarMensaje({'estado_piezas': estadoPieza});
    console.table(estadoPieza);

    console.log('SECCION Puzzle : ');
    /**
     * SECTOR PUZZLE
     * aca actualizo la matriz cada vez que se realiza un movimiento
     * 3) uso una matriz auxiliar y luego seteo la matriz
     */
    estadoPuzzle = this.actualizarMatriz(this.getPuzzle(), this.getEstadoPuzzle());
    
    this.setEstadoPuzzle(estadoPuzzle);

    /**
     * 4) envio el estado del puzzle al backend
     */
    this.enviarMensaje({'estado_puzzle': estadoPuzzle});            
    console.table(estadoPuzzle);

  } // FIN METODO guardarEstadoJuego

} // FIN CLASE



const puzzle = $('puzzle');
const piezas = $('piezas');
const mensaje = $('mensaje');


$('btn_cargar').addEventListener('click', e => {
  // let url_data = $('url_data').value;
  let url_data = 'https://cdn.pixabay.com/photo/2017/10/25/16/54/african-lion-2888519_1280.jpg';
  // let url_data = 'imgs/paisaje.jpg';
  eliminarHijos(piezas);
  eliminarHijos(puzzle);

  let imagenes = [];
  const cantidad_fragmentos = 9;
  for (let index = 0; index < (cantidad_fragmentos); index++) {
    const nombre_canva = 'canvas_' + index;
    imagenes.push(nombre_canva);  
  }

  const juego = new Juego(puzzle, piezas, 0, url_data);

  // let currentCanvas = null;
  juego.setCurrentCanvas(null);
  juego.setPiezas(piezas);
  juego.crearCanvasyAsignarPiezas();

  let terminado = imagenes.length;

  juego.setTerminado(terminado);
  juego.setPuzzle(puzzle);
  juego.crearPlaceHolder();
  juego.setPiezas(piezas);
  juego.setPuzzle(puzzle);
  juego.crearEventosDeMouse();

  juego.guardarEstadoJuego();

});

 