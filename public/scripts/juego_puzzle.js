let canvas = document.getElementById("canvas");
let ctx = canvas.getContext("2d");
let image = document.getElementById('imagen');

console.log(image.hidden);

let aux = 10;

ctx.drawImage(image, 
              0, 0, // hoja de mosaico x, y (esquina superior izquierda de la captura) 
              aux, aux, //Que tanto va a crecer la imagen
              0, 0, //Donde quieres que se coloque la imagen
              aux, aux //dHeight
);

console.log("Image: ANCHO: " + image.width + "\n LARGO: " + image.height);
console.log("Canvas: ANCHO: " + canvas.width + "\n LARGO: " + canvas.height);

canvas.addEventListener('click', function (event) {
  // alert("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
  console.log("coord X: " + event.offsetX + " - coord Y: " + event.offsetY);
});

