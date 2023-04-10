
class Juego {

  dibujarImagenEnCanvas(idCanvas, xOrigen, yOrigen) {
    let canvas = document.getElementById(idCanvas);
    let context = canvas.getContext("2d");
    let image = document.getElementById("imagen");

    let [anchoOrigen, anchoDestino, altoOrigen, altoDestino] = [100, 100, 100, 100];
    let [xDestino, yDestino] = [0, 0];

    context.drawImage(image,
      xOrigen, yOrigen, //coordenada x e y en imagen origen
      anchoOrigen, altoOrigen, //cant pixeles en ancho y alto que quiero tomar
      xDestino, yDestino, //coordenada x e y en destino
      anchoDestino, altoDestino//cuanto va a ocupar la imagen en destino
    );
  }
}

function $(idDiv) {
  return document.getElementById(idDiv);
}

function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));
  console.log(ev.target.children.length);
}

const juego = new Juego();

const coordXOrigen = [0, 101, 201];
const coordYOrigen = [0, 101, 201];

let fila = 0;

coordYOrigen.forEach(function (coordY) {
  // console.log('[foreach] y:', coordY);
  coordXOrigen.forEach(function (coordX, i) {

    // console.log('canvas_'+ (i + fila) +' (' +  coordX + "," + coordY + ')');
    juego.dibujarImagenEnCanvas("canvas_" + (i + fila), coordX, coordY);
  });
  fila = fila + 3;
});



// let pieza_0 = $("canvas_0");
// let pieza_1 = $("canvas_1");
// let pieza_2 = $("canvas_2");
// let div_8 = $("2");

// console.log("CANT HIJOS: " + div_8.children.length);
// div_8.appendChild(pieza_0);
// console.log("CANT HIJOS: " + div_8.children.length);
// div_8.appendChild(pieza_1);
// console.log("CANT HIJOS: " + div_8.children.length);
// div_8.appendChild(pieza_2);
// console.log("CANT HIJOS: " + div_8.children.length);


// juego.dibujarImagenEnCanvas("canvas_0",0,0);
// juego.dibujarImagenEnCanvas("canvas_1",101,0);
// juego.dibujarImagenEnCanvas("canvas_2",201,0);

// juego.dibujarImagenEnCanvas("canvas_3",0,101);
// juego.dibujarImagenEnCanvas("canvas_4",101,101);
// juego.dibujarImagenEnCanvas("canvas_5",201,101);

// juego.dibujarImagenEnCanvas("canvas_6",0,201);
// juego.dibujarImagenEnCanvas("canvas_7",101,201);
// juego.dibujarImagenEnCanvas("canvas_8",201,201);


// console.log("Image: ANCHO: " + imagen.width + "\n LARGO: " + imagen.height);
// console.log("Canvas: ANCHO: " + canvas.width + "\n LARGO: " + canvas.height);

// canvas.addEventListener('click', function (event) {
  // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
//   console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
// });
// canvas.addEventListener('ontouch', function (event) {
   // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
//   console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
// });
// canvas.addEventListener('down', function (event) {
  // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
//   console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
// });

