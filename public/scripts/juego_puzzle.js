let canvas = document.getElementById("canvas_4");
let ctx = canvas.getContext("2d");
let image = document.getElementById('imagen');

ctx.drawImage(image,
            150,150, //coordenada x e y en imagen origen
            100,100, //cant pixeles en ancho y alto que quiero tomar
            0,0, //coordenada x e y en destino
            100,100//cuanto va a ocupar la imagen en destino
);

console.log("Image: ANCHO: " + imagen.width + "\n LARGO: " + imagen.height);
console.log("Canvas: ANCHO: " + canvas.width + "\n LARGO: " + canvas.height);

canvas.addEventListener('click', function (event) { 
  // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
  console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
});
canvas.addEventListener('ontouch', function (event) { 
  // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
  console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
});
canvas.addEventListener('down', function (event) { 
  // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
  console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
});

