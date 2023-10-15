export class Pieza {
  constructor(id, canvas, imagenFragmento) {
    this.id = id;
    this.canvas = canvas;
    this.imagenFragmento = imagenFragmento;
    this.estado = 'desordenada'; // Estado inicial
    this.posicionCorrecta = id; // La posición correcta en el rompecabezas
  }

  mover() {
    // Implementa el código para permitir que la pieza sea arrastrada y movida por el jugador.
  }

  verificarPosicion() {
    // Implementa la lógica para verificar si la pieza está en su posición correcta.
    return this.posicionCorrecta === this.id;
  }

  dibujar(contexto) {
    // Dibuja la pieza en el contexto especificado (lienzo).
    contexto.drawImage(this.imagenFragmento, 0, 0, this.canvas.width, this.canvas.height);
  }

  obtenerEstado() {
    return this.estado;
  }

  establecerEstado(estado) {
    this.estado = estado;
  }
}

// En la clase Rompecabezas, puedes crear instancias de Pieza y gestionar su interacción con el juego.
