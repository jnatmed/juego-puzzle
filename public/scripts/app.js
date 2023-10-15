import { Pieza } from "./piezas.js";
import { Dificultad } from "./dificultad.js";
import { Rompecabezas } from "./rompecabezas.js";

// Inicialización del juego al cargar la página
document.getElementById('btnCargar').addEventListener('click', () => {
  const urlImagen = document.getElementById('inputURL').value;

  // Configura la dificultad del juego (puedes cambiar estos valores)
  const dificultadNovato = new Dificultad(3, 3);
  const dificultadAvanzado = new Dificultad(4, 4);
  const dificultadInsane = new Dificultad(6, 6);

  // Inicializa el juego con la dificultad seleccionada
  const rompecabezas = new Rompecabezas(dificultadNovato); // Cambia la dificultad según corresponda
  rompecabezas.iniciarJuego(urlImagen);
});
