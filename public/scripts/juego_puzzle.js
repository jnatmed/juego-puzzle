
class Juego {

    (idCanvas, xOrigen, yOrigen){
    let canvas = document.getElementById(idCanvas);
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
  }
}

const juego = new Juego();

const coordXOrigen = [0,101,201];
const coordYOrigen = [0,101,201];

let fila = 0;

coordYOrigen.forEach(function(coordY){
    // console.log('[foreach] y:', coordY);
    coordXOrigen.forEach(function(coordX, i){

      // console.log('canvas_'+ (i + fila) +' (' +  coordX + "," + coordY + ')');
      juego.dibujarImagenEnCanvas("canvas_" + (i + fila), coordX, coordY);
    });
    fila = fila + 3;
  });
  
  
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

