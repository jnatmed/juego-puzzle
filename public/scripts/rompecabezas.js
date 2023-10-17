import { Pieza } from "./piezas.js";

// Clase Rompecabezas para gestionar el juego
export class Rompecabezas {
  constructor(dificultad, tamanioPieza) {
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
    this.tamanioPieza = tamanioPieza;
  }

  // Método para inicializar el juego
  iniciarJuego(urlImagen) {
    this.imagenOriginal.src = urlImagen;
    this.tiempoInicio = new Date();
    this.imagenOriginal.onload = () => {
      this.redimensionarYFragmentarImagen();
      this.ajustarTamanioContenedor();
      this.organizarPiezas();
      this.inicializarEventos();
    };
  }

  redimensionarYFragmentarImagen() {
    const imagenRedimensionada = this.redimensionarImagen(this.imagenOriginal);
    this.fragmentarImagen(imagenRedimensionada);
  }

  redimensionarImagen(imagen) {
    const anchoDeseado = this.tamanioPieza.ancho;
    const altoDeseado = this.tamanioPieza.alto;
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');

    // Define el tamaño deseado en el canvas
    canvas.width = anchoDeseado;
    canvas.height = altoDeseado;

    // Dibuja la imagen original redimensionada en el canvas
    context.drawImage(imagen, 0, 0, anchoDeseado, altoDeseado);

    // Crea una nueva imagen a partir del canvas redimensionado
    const imagenRedimensionada = new Image();
    imagenRedimensionada.src = canvas.toDataURL();

    return imagenRedimensionada;
  }

  // Método para fragmentar la imagen en lienzos y organizar las piezas
  fragmentarImagen(imagen) {

    const imagenRedimensionada = this.redimensionarImagen(imagen);
    const anchoPieza = this.tamanioPieza.ancho / this.columnas;
    const altoPieza = this.tamanioPieza.alto / this.filas;
    
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
            context.drawImage(imagenRedimensionada, x, y, anchoPieza, altoPieza, 0, 0, anchoPieza, altoPieza);

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
  
  }
  
  ajustarTamanioContenedor() {
    // Obtiene el contenedor de piezas desordenadas
    const contenedorPiezasDesordenadas = document.getElementById('piezasDesordenadas');

    const anchoContenedor = this.tamanioPieza.ancho;
    const altoContenedor = this.tamanioPieza.alto;

    // Establece el tamaño del contenedor
    contenedorPiezasDesordenadas.style.width = `${anchoContenedor}px`;
    contenedorPiezasDesordenadas.style.height = `${altoContenedor}px`;
  }  

  // Método para inicializar eventos de arrastrar y soltar
  inicializarEventos() {
    // Agregar eventos para arrastrar y soltar piezas
    // Obtener todas las piezas del rompecabezas
    const piezas = this.piezasDesordenadas;

    // Recorrer todas las piezas y agregar eventos de arrastrar y soltar
    piezas.forEach((pieza) => {
      pieza.canvas.addEventListener('dragstart', (e) => {
        // Manejar el evento de inicio de arrastre
        e.dataTransfer.setData('text/plain', pieza.id);
      });

      pieza.canvas.addEventListener('dragover', (e) => {
        // Evitar que el navegador maneje el evento por defecto
        e.preventDefault();
      });

      pieza.canvas.addEventListener('drop', (e) => {
        // Manejar el evento de soltar la pieza
        const piezaId = e.dataTransfer.getData('text/plain');
        const piezaArrastrada = piezas.find((p) => p.id.toString() === piezaId);

        if (piezaArrastrada) {
          // sector para realizar la logica del juego
          
        }
      });
    });
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

}
