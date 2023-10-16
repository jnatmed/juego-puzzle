import { Pieza } from "./piezas.js";

// Clase Rompecabezas para gestionar el juego
export class Rompecabezas {
  constructor(dificultad) {
    this.dificultad = dificultad;
    this.piezasDesordenadas = [];
    this.piezasOrdenadas = [];
    this.imagenOriginal = new Image();
    this.filas = dificultad.filas;
    this.columnas = dificultad.columnas;
    this.movimientos = 0;
    this.aciertos = 0;
    this.errores = 0;
    this.tiempoInicio = null;
    this.tiempoActual = null;
    this.tiempoTranscurrido = 0;
  }

  // Método para fragmentar la imagen en lienzos y organizar las piezas
  fragmentarImagen(imagen) {
    const anchoPieza = imagen.width / this.columnas;
    const altoPieza = imagen.height / this.filas;
    
    for (let fila = 0; fila < this.filas; fila++) {
        for (let columna = 0; columna < this.columnas; columna++) {
            // Crea un nuevo lienzo (canvas) para cada fragmento de imagen
            const canvas = document.createElement('canvas');
            canvas.width = anchoPieza;
            canvas.height = altoPieza;

            const context = canvas.getContext('2d');

            const x = columna * anchoPieza;
            const y = fila * altoPieza;

            // Dibuja el fragmento de imagen en el lienzo
            context.drawImage(imagen, x, y, anchoPieza, altoPieza, 0, 0, anchoPieza, altoPieza);

            // Crear una nueva instancia de Pieza con el id, lienzo y fragmento de imagen correspondiente
            const id = fila * this.columnas + columna; // ID único para cada pieza
            const pieza = new Pieza(id, canvas, context.getImageData(0, 0, anchoPieza, altoPieza));

            // Agregar la pieza desordenada a la lista de piezas desordenadas
            this.piezasDesordenadas.push(pieza);

            // Agregar la pieza ordenada a la lista de piezas ordenadas
            this.piezasOrdenadas.push(pieza);

        }
    }
    
    // Ordena piezasOrdenadas por su ID
    this.piezasOrdenadas.sort((a, b) => a.id - b.id);
}

  // Método para organizar las piezas en base a la dificultad
  organizarPiezas() {
    // Lógica para organizar las piezas
    // Agregar las piezas desordenadas al div piezasDesordenadas
    const piezasDesordenadasDiv = document.getElementById('piezasDesordenadas');
    this.piezasDesordenadas.forEach((pieza) => {
      piezasDesordenadasDiv.appendChild(pieza.canvas);
    });
  
    // Crear un Blob a partir de la imagen original
    fetch(this.imagenOriginal.src)
      .then((response) => response.blob())
      .then((blob) => {
        // Generar una URL de objeto para el Blob
        const urlImagenOriginal = URL.createObjectURL(blob);
  
        // Configurar el fondo del div piezasOrdenadas con la URL de objeto
        const piezasOrdenadasDiv = document.getElementById('piezasOrdenadas');
        piezasOrdenadasDiv.style.backgroundImage = `url(${urlImagenOriginal})`;
        piezasOrdenadasDiv.style.backgroundSize = 'cover';
        piezasOrdenadasDiv.style.backgroundRepeat = 'no-repeat';
        piezasOrdenadasDiv.style.backgroundPosition = 'center';
        piezasOrdenadasDiv.style.opacity = 0.1; // Ajusta la opacidad según sea necesario
  
        // Asegúrate de revocar la URL de objeto una vez que ya no la necesites para liberar recursos
        URL.revokeObjectURL(urlImagenOriginal);
      });
  }
  

  // Método para inicializar el juego
  iniciarJuego(urlImagen) {
    this.imagenOriginal.src = urlImagen;
    this.tiempoInicio = new Date();
    this.imagenOriginal.onload = () => {
      this.fragmentarImagen(this.imagenOriginal);
      this.organizarPiezas();
    // Inicializa eventos de arrastrar y soltar
      this.inicializarEventos();
    };
  }
  

  // Método para verificar si el juego está completo
  verificarJuegoCompleto() {
    if (this.aciertos === this.dificultad.filas * this.dificultad.columnas) {
      this.tiempoActual = new Date();
      this.tiempoTranscurrido = (this.tiempoActual - this.tiempoInicio) / 1000;
      const puntaje = this.calcularPuntaje();
      console.log('¡Juego completado!');
      console.log(`Aciertos: ${this.aciertos}`);
      console.log(`Errores: ${this.errores}`);
      console.log(`Puntaje: ${puntaje}`);
      console.log(`Tiempo transcurrido: ${this.tiempoTranscurrido} segundos`);
    }
  }

  // Método para calcular el puntaje
  calcularPuntaje() {
    const puntosPorAcierto = this.aciertos * 10;
    const puntosPorError = this.errores * -5;
    const tiempoPenalizacion = this.tiempoTranscurrido;
    return Math.max(puntosPorAcierto + puntosPorError - tiempoPenalizacion, 0);
  }

  // Método para inicializar eventos de arrastrar y soltar
  inicializarEventos() {
    // Agregar eventos para arrastrar y soltar piezas
    // Implementa lógica de juego aquí, incluyendo la verificación de juego completo
  }
}
