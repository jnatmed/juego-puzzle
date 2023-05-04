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
  }
  /* @method 
  *  guardo un parte de la imagen y la devuelvo
  */
  dibujarImagenEnCanvas(canvas, xOrigen, yOrigen){
    let ctx = canvas.getContext("2d");
    var image = new Image();
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
  setPuzzle(puzzle) { this.puzzle = puzzle; }
  setPiezas(piezas) { this.piezas = piezas; }
  setCurrentCanvas(cCanvas) { this.currentCanvas = cCanvas; }
  setTerminado(terminado) { this.terminado = terminado; }

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
        const placeHolderSeccionado = $(e.changedTouches[0].target.id);
        consola("placeHolder seleccionado : " + placeHolderSeccionado.id)
        /**
         *  variables externa: 
         *      - currentCanvas
         */
        if ($(this.currentCanvas).id.split('_')[1] == placeHolderSeccionado.id){
            placeHolderSeccionado.appendChild($(this.currentCanvas))
        }
      })
    }
  }
}

const puzzle = $('puzzle');
const piezas = $('piezas');
const mensaje = $('mensaje');
const coordXOrigen = [0,101,201];
const coordYOrigen = [0,101,201];
let fila = 0;
let currentCanvas = null;
const imagenes = [
  'canvas_0', 'canvas_1', 'canvas_2', 
  'canvas_3', 'canvas_4', 'canvas_5', 
  'canvas_6','canvas_7', 'canvas_8'
];

const juego = new Juego(puzzle, piezas, 0);

coordYOrigen.forEach(function(coordY){

    coordXOrigen.forEach(function(coordX, i){
      
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
      canvas.id = "canvas_" + (i + fila);
      // le asigno una clase
      canvas.className = 'pieza';
      // dibujo un fragmento de la imagen en ese canvas
      juego.dibujarImagenEnCanvas(canvas, coordX, coordY);
      // lo hago arrastrable
      canvas.draggable = true;
      // asigno un evento de tactil para cuando se toca un canvas
      canvas.addEventListener('touchstart', e => {
        // al comenzar a arrastrar, identifico el canvas tocado
        const canvaSeccionado = $(e.changedTouches[0].target.id).id;
        consola("canvas seleccionado : " + canvaSeccionado)
        consola("0 " + e)
        // guardo temporalmente el canvas tocado
        currentCanvas = canvaSeccionado;
        let ctx = $(canvaSeccionado).getContext("2d");
        // muestro un cartel de canvas seleccionado
        let cartel = "Seleccionado";
        ctx.fillText(cartel,
                    20,
                    $(canvaSeccionado).clientHeight/2)
      })
      // asigno un evento tactil para cuando se mueve el canvas
      canvas.addEventListener('touchmove', e => {
        consola(currentCanvas.id);
        /**
         * por cada evento de toque en la pantalla, tomo al 
         * canvas selecionado, siendo el mismo el que guarde en 
         * el currentCanvas y le voy asignando las coordenadas 
         * de los eventos de toque, en la medida que se van creando. 
         */
        [...e.changedTouches].forEach(touch => {
          const dot = $(currentCanvas);
          dot.style.top = `${touch.clientY - dot.clientHeight/2}px`
          dot.style.left = `${touch.clientX - dot.clientWidth/2}px`
        })

      })
      // agrego el canvas a contenedor de piezas
      piezas.appendChild(canvas);
    });
    fila = fila + 3;
});

let terminado = imagenes.length;

juego.setTerminado(terminado);
juego.setPuzzle(puzzle);
juego.crearPlaceHolder();
juego.setPiezas(piezas);
juego.setPuzzle(puzzle);
juego.crearEventosDeMouse();




 