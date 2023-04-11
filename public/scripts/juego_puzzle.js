class Juego {

  /* @method 
  *  guardo un parte de la imagen y la devuelvo
  */
  dibujarImagenEnCanvas(canvas, xOrigen, yOrigen){
    let ctx = canvas.getContext("2d");
    let image = document.getElementById("imagen");
    
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

}

function $(idElement) {
  return document.getElementById(idElement);
}

function $create(idElement,width, height) {
  idElement.width = width;
  idElement.height = height;
  idElement.margin = 0;
  return idElement;
}

const juego = new Juego();

const coordXOrigen = [0,101,201];
const coordYOrigen = [0,101,201];

let fila = 0;

const puzzle = $('puzzle');
const piezas = $('piezas');
const mensaje = $('mensaje');


coordYOrigen.forEach(function(coordY){

    coordXOrigen.forEach(function(coordX, i){
      
      const canvas = document.createElement('canvas');
      $create(canvas,100,100);
      canvas.id = "canvas_" + (i + fila);
      juego.dibujarImagenEnCanvas(canvas, coordX, coordY);
      canvas.draggable = true;
      piezas.appendChild(canvas);
    });
    fila = fila + 3;
});

const imagenes = [
  'canvas_0', 'canvas_1', 'canvas_2', 
  'canvas_3', 'canvas_4', 'canvas_5', 
  'canvas_6','canvas_7', 'canvas_8'
];

let terminado = imagenes.length;

for (let i = 0; i < terminado; i++) {
  const div = document.createElement('div');
  div.className = 'placeholder';
  div.dataset.id = i;
  puzzle.appendChild(div);
}

piezas.addEventListener('dragstart', e => {
  e.dataTransfer.setData('id', e.target.id);
});

puzzle.addEventListener('dragover', e => {
  e.preventDefault();
  e.target.classList.add('hover');
});

puzzle.addEventListener('dragleave', e => {
  e.target.classList.remove('hover');
});

puzzle.addEventListener('drop', e => {
  e.target.classList.remove('hover');
  const id = e.dataTransfer.getData('id');
  // const numero = id.split('-')[1];
  e.target.appendChild(document.getElementById(id));
});





// console.log("Image: ANCHO: " + imagen.width + "\n LARGO: " + imagen.height);
// console.log("Canvas: ANCHO: " + canvas.width + "\n LARGO: " + canvas.height);

// canvas.addEventListener('click', function (event) { 
//   // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
//   console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
// });
// canvas.addEventListener('ontouch', function (event) { 
//   // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
//   console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
// });
// canvas.addEventListener('down', function (event) { 
//   // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
//   console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
// });

