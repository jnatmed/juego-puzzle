var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");
var image = document.getElementById('source');

ctx.drawImage(image, 150, //sx: 
  100, //sy: 
  20, //sWidth
  50, //sHeight
  25,  //dx: coordenada X del canvas destino en la cual se coloca la esquina superior
  20, //dy: coordenada Y del canvas destino en la cual se coloca la esquina superior izquierda
  100, //dWidth
  604 //dHeight
);

console.log("Canvas: ANCHO: " + canvas.width + "\n LARGO: " + canvas.height);

canvas.addEventListener('click', function (event) {
  alert("coord X: " + event.pageX + " - coord Y: " + event.pageY);
});

