function $(idElement) {
  return document.getElementById(idElement);
}

function $create(idElement,width, height) {
  idElement.width = width;
  idElement.height = height;
  idElement.margin = 0;
  return idElement;
}

function consola(msj){
  console.log(msj)
}

class Juego {

  constructor(piezas, puzzle, terminado){
    this.piezas = piezas;
    this.puzzle = puzzle;
    this.terminado = terminado;
    this.currentCanvas = null;
    this.estadoPiezas = [];
    this.estadoPiezas.fill(-1);
    this.estadoPuzzle = [];
    this.estadoPuzzle.fill(-1);
  }
  /* @method 
  *  guardo un parte de la imagen y la devuelvo
  */
  dibujarImagenEnCanvas(canvas, xOrigen, yOrigen){
    let ctx = canvas.getContext("2d");
    let image = new Image();
    image.src = "imgs/paisaje.jpg";
    
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
   * 
   * @param {*} puzzle 
   */
  getEstadoPiezas(){return this.estadoPiezas;}
  setEstadoPiezas(estadoPiezas) { this.estadoPiezas = estadoPiezas; }
  getEstadoPuzzle(){return this.estadoPuzzle;}
  setEstadoPuzzle(estadoPuzzle) { this.estadoPuzzle = estadoPuzzle; }
  setPuzzle(puzzle) { this.puzzle = puzzle; }
  getPuzzle() {return this.puzzle; }
  setPiezas(piezas) { this.piezas = piezas; }
  getPiezas() { return this.piezas; }
  setCurrentCanvas(cCanvas) { this.currentCanvas = cCanvas; }
  setTerminado(terminado) { this.terminado = terminado; }
  
  getHijos(element){
    return $(element).childNodes;
  }

  crearEventosDeMouse(){

    this.piezas.addEventListener('dragstart', e => {
      e.dataTransfer.setData('id', e.target.id);
      consola(`id ${e.target.id}`);
    });
    
    this.puzzle.addEventListener('dragover', e => {
      e.preventDefault();
      e.target.classList.add('hover');
    });
    
    this.puzzle.addEventListener('dragleave', e => { 
      e.target.classList.remove('hover');
    });
    
    this.puzzle.addEventListener('drop', e => {
      e.target.classList.remove('hover');
      const id = e.dataTransfer.getData('id');
      console.log(`Id : ${id}`);
      const numero = id.split('_')[1];
      console.log(`e.target.id: ${e.target.id}`);
      if(e.target.id === numero){
        e.target.appendChild(document.getElementById(id));
        this.guardarEstadoJuego();
        terminado--;
        if (terminado === 0) {
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
            const canvas = document.createElement('canvas');
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
            this.dibujarImagenEnCanvas(canvas, coordXOrigen[j], coordYOrigen[i]);
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
      .then((data) => console.log(data))
      .catch((error) => console.log(error)); 

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
    
    let estadoPieza = this.getEstadoPiezas();   
    let estadoPuzzle = this.getEstadoPuzzle();  

    console.log(this.getPiezas());
    console.log(this.getPiezas().childNodes);
    console.log(this.getPuzzle());
    console.log(this.getPuzzle().childNodes);
    console.log('SECCION Piezas');
    this.getPiezas().childNodes.forEach((divCanva, i) => {    
    // SECTOR PIEZAS
      if(i != 0){
        console.log(`pieza : ${divCanva.id} // i = ${i}`);
        // SI TIENE HIJOS ENTONCES 
        if (divCanva.childNodes.length > 0) {
          // console.log(`TIENE ${pieza.childNodes.length} hijos`)
          estadoPieza[divCanva.id] = divCanva.childNodes[0].id; 
          this.setEstadoPiezas(estadoPieza);
          /** 
           * envio del estado al backend
           */
          console.log("PREVIO A MANDAR EL AJAXs")
          console.table(estadoPieza); 
          // this.enviarMensaje({'estado_piezas': estadoPieza});
        }else{
          // console.log(`no tiene hijos n = ${pieza.childNodes.length}`);
          estadoPieza[divCanva.id] = -1;
          this.setEstadoPiezas(estadoPieza);
        }
      }
    });
    /**
     * envio del estado de las piezas al backend
     */

    this.enviarMensaje({'estado_piezas': estadoPieza});

    console.table(estadoPieza);
    console.log('SECCION Puzzle : ');
    // SECTOR PUZZLE
    this.getPuzzle().childNodes.forEach((celda, j) => {
        if(j != 0){
          // console.log(`pieza : ${celda.id} // j = ${j}`);
          // SI TIENE HIJOS ENTONCES
          if(celda.childNodes.length > 0){
            // console.log(`TIENE ${celda.childNodes.length} hijos`)
            estadoPuzzle[celda.id] = celda.childNodes[0].id; 
            this.setEstadoPuzzle(estadoPuzzle);
          }else{
            // console.log(`no tiene hijos n = ${celda.childNodes.length}`);
            estadoPuzzle[celda.id] = -1;
            this.setEstadoPuzzle(estadoPuzzle);
          }
        }
      });
    /**
     * envio del estado del puzzle al backend
     */
    this.enviarMensaje({'estado_puzzle': estadoPuzzle});            
    console.table(estadoPuzzle);


  } // FIN METODO guardarEstadoJuego

} // FIN CLASE

const puzzle = $('puzzle');
const piezas = $('piezas');
const mensaje = $('mensaje');


const imagenes = [
  'canvas_0', 'canvas_1', 'canvas_2', 
  'canvas_3', 'canvas_4', 'canvas_5', 
  'canvas_6','canvas_7', 'canvas_8'
];

const juego = new Juego(puzzle, piezas, 0);

let currentCanvas = null;
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



 