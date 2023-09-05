// Función para actualizar el temporizador
function actualizarTemporizador() {
  segundos++;
  if (segundos == 60) {
    segundos = 0;
    minutos++;
  }

  // Formatear el tiempo en formato MM:SS
  const tiempoFormateado = (minutos < 10 ? "0" : "") + minutos + ":" + (segundos < 10 ? "0" : "") + segundos;

  // Actualizar el contenido del elemento del temporizador
  timerElement.textContent = tiempoFormateado;
}

function obtenerTiempoEnSegundos() {
  // Obtener el contenido del elemento que muestra el tiempo (por ejemplo, "03:45")
  const tiempoTexto = timerElement.textContent;

  // Dividir el tiempo en minutos y segundos
  const partesTiempo = tiempoTexto.split(":");
  const minutos = parseInt(partesTiempo[0], 10); // Convertir a número base 10
  const segundos = parseInt(partesTiempo[1], 10); // Convertir a número base 10

  // Calcular el tiempo total en segundos
  return minutos * 60 + segundos;  
}

function $(id_element) {
  return document.getElementById(id_element);
}

function $create(idpiezas,width, height) {
  idpiezas.width = width;
  idpiezas.height = height;
  idpiezas.margin = 0;
  return idpiezas;
}

function consola(msj){
  console.log(msj)
}

function eliminarHijos(id){
  while (id.firstChild) {
    id.removeChild(id.firstChild);
  }
}

async function blobToDataURI(blob) {
    return new Promise(resolve => {
        const reader = new FileReader();
        reader.onload = event => {
            resolve(event.target.result);
        };
        reader.readAsDataURL(blob);
    });
}

class Juego {

  constructor(piezas, puzzle, terminado, url_data, intervalId){
    this.piezas = piezas;
    this.puzzle = puzzle;
    this.terminado = terminado;
    this.currentCanvas = null;
    this.estadoPiezas = [];
    this.estadoPiezas.fill(-1);
    this.estadoPuzzle = [];
    this.estadoPuzzle.fill(-1);
    this.url_data = url_data;
    this.aciertos = 0;
    this.errores = 0;

    this.minutos = 0;
    this.segundos = 0;
    this.intervalId = intervalId;

    this.movimientos = 0;
  }

  /**
   *  GETTERs/SETTERs
   */
  getEstadoPiezas(){return this.estadoPiezas;}
  setEstadoPiezas(estadoPiezas) { this.estadoPiezas = estadoPiezas; }
  getEstadoPuzzle(){return this.estadoPuzzle;}
  setEstadoPuzzle(estadoPuzzle) { this.estadoPuzzle = estadoPuzzle; }
  setPuzzle(puzzle) { this.puzzle = puzzle; }
  getPuzzle() {return this.puzzle; }
  setPiezas(piezas) { this.piezas = piezas; }
  getPiezas() { return this.piezas; }
  getUrl_data() { return this.url_data; }
  setCurrentCanvas(cCanvas) { this.currentCanvas = cCanvas; }
  getTerminado() { return this.terminado; }
  setTerminado(terminado) { this.terminado = terminado; }
  getAciertos() { return this.aciertos; }
  setAciertos(aciertos) { this.aciertos = aciertos; }
  getErrores() { return this.errores; }
  setErrores(errores) { this.errores = errores}
  
  actualizarTemporizador() {
    this.segundos++;
    if (this.segundos == 60) {
      this.segundos = 0;
      this.minutos++;
    }

    // Formatear el tiempo en formato MM:SS
    const tiempoFormateado = (this.minutos < 10 ? "0" : "") + this.minutos + ":" + (this.segundos < 10 ? "0" : "") + this.segundos;

    // Actualizar el contenido del elemento del temporizador
    timerElement.textContent = tiempoFormateado;
  }   


  getHijos(piezas){
    return $(piezas).childNodes;
  }

  crearEventosDeMouse(){

    this.piezas.addEventListener('dragstart', e => {
      e.dataTransfer.setData('id', e.target.id);
      e.target.style = 'cursor: move';
      consola(`id ${e.target.id}`);
    });
    
    this.piezas.addEventListener('mouseover', e => {
      e.target.style = 'cursor: move';
    });

    this.puzzle.addEventListener('dragover', e => {
      e.preventDefault();
      e.target.classList.add('hover');
      e.target.style = 'cursor: pointer';
    });
    
    this.puzzle.addEventListener('dragleave', e => { 
      e.target.classList.remove('hover');
      e.target.style = 'cursor: pointer';
    });
  
    this.puzzle.addEventListener('drop', e => {
      e.target.classList.remove('hover');
      const id = e.dataTransfer.getData('id');
      console.log(`Id : ${id}`);
      const numero = id.split('_')[1];
      console.log(`e.target.id: ${e.target.id}`);

      const cartelPuntaje = $('puntaje_actual');

      if(e.target.id === numero){
        e.target.appendChild($(id));
        e.target.style.opacity = '1';
        
        this.setAciertos(this.getAciertos() + 1);
        $('aciertos').innerHTML = `Aciertos: ${this.getAciertos()}`;
        $('aciertos').setAttribute('data-value', this.getAciertos());
        cartelPuntaje.innerHTML = `Puntaje Final: ${this.calcularPuntaje()}`;
        cartelPuntaje.setAttribute('data-value', this.calcularPuntaje());

        this.guardarEstadoJuego('jugando');
        this.setTerminado(this.getTerminado() - 1 );
        if (this.getTerminado() === 0) {
          const ganasteCartel = $('ganaste_cartel');

          ganasteCartel.style.display = 'block';

          setTimeout(() => {
            ganasteCartel.style.display = 'none';
          }, 3000); // Mostrar durante 3 segundos

          const cartelPuntaje = $('puntaje_actual');
          cartelPuntaje.innerHTML = `Puntaje Final: ${this.calcularPuntaje()}`;
          cartelPuntaje.setAttribute('data-value', this.calcularPuntaje());

          // Detener el intervalo
          clearInterval(this.intervalId);
          this.guardarEstadoJuego('terminado');
        }
      }else{
          this.setErrores(this.getErrores() + 1);
          $('errores').innerHTML = `Errores: ${this.getErrores()}`;
          $('errores').setAttribute('data-value', this.getErrores());
          cartelPuntaje.innerHTML = `Puntaje Final: ${this.calcularPuntaje()}`;
          cartelPuntaje.setAttribute('data-value', this.calcularPuntaje());
          this.guardarEstadoJuego('jugando');
      }
    });
    
  }

  async crearPlaceHolder(dataURI){

    const img = new Image();
    img.src = dataURI;
    await img.decode(); // Esperar a que la imagen se cargue completamente en memoria

    for (let i = 0; i < this.terminado; i++) {
      const div = document.createElement('div');
      div.className = 'placeholder';
      div.id = i;
      // div.style.opacity = '.1';
      /**
       *  variable externa:
       *        - puzzle
       */
      this.puzzle.appendChild(div);
      // console.log(this.puzzle);
      let placeHolder = $(i); 
    
      placeHolder.addEventListener('touchstart', e => {
        // seleccionar el contenedor que identifica 
        // al placeHolder. 
        const placeHolderSeccionado = $(e.changedTouches[0].target.id);
        consola("placeHolder seleccionado : " + placeHolderSeccionado.id)
        /**
         *  variables externa: 
         *      - currentCanvas
         * el identificador del currentCanvas es xejemplo 
         * canvas_1 con el split se conviete en ['canvas', '1']
         * la posicion [1] tiene el numero de canvas
         */
        const cartelPuntaje = $('puntaje_actual');

        if ($(this.currentCanvas).id.split('_')[1] == placeHolderSeccionado.id){
            placeHolderSeccionado.style.opacity = '1';
            placeHolderSeccionado.appendChild($(this.currentCanvas))
            /**
             * incremento el puntaje en 1
             */
            this.setAciertos(this.getAciertos() + 1);
            $('aciertos').innerHTML = `Puntaje: ${this.getAciertos()}`;
            $('aciertos').setAttribute('data-value', this.getAciertos());
            cartelPuntaje.innerHTML = `Puntaje Final: ${this.calcularPuntaje()}`;
            cartelPuntaje.setAttribute('data-value', this.calcularPuntaje());
            this.guardarEstadoJuego('jugando');

            if (this.getTerminado() === 0) {
              const ganasteCartel = $('ganaste_cartel');

              ganasteCartel.style.display = 'block';

              // Detener el intervalo del tiempo 
              clearInterval(this.intervalId);

              this.guardarEstadoJuego('terminado');

              
              cartelPuntaje.innerHTML = `Puntaje Final: ${this.calcularPuntaje()}`;
              cartelPuntaje.setAttribute('data-value', this.calcularPuntaje());

              setTimeout(() => {
                ganasteCartel.style.display = 'none';
              }, 3000); // Mostrar durante 3 segundos

            }

        }else {
              this.setErrores(this.getErrores() + 1);
              $('errores').innerHTML = `Errores: ${this.getErrores()}`;
              $('errores').setAttribute('data-value', this.getErrores());
              this.guardarEstadoJuego('jugando');
              cartelPuntaje.innerHTML = `Puntaje Final: ${this.calcularPuntaje()}`;
              cartelPuntaje.setAttribute('data-value', this.calcularPuntaje());
        }
      })     
    }

  }

  /**
   * 
   * @param {*} dataURI 
   */
  async crearCanvasyAsignarPiezas(dataURI){

    const dimensionesCanva = 100;
    const numCanvases = 9;
    const img = new Image();
    img.src = dataURI;
    await img.decode(); // Esperar a que la imagen se cargue completamente en memoria
    const piezasDiv = this.getPiezas();

    const anchoDelFragmento = img.width / Math.sqrt(numCanvases);
    const altoDelFragmento = img.height / Math.sqrt(numCanvases);

      for(let j = 0; j < numCanvases; j++ ) {

            // creo un canvas
            let canvas = document.createElement('canvas');
            canvas.id = "canvas_" + j;
            // le asigno una clase
            canvas.className = 'pieza';
            // dibujo un fragmento de la imagen en ese canvas
            const origenX = (j % Math.sqrt(numCanvases)) * anchoDelFragmento; 
            const origenY = Math.floor(j / Math.sqrt(numCanvases)) * altoDelFragmento;

            const ctx = canvas.getContext("2d");

            ctx.drawImage(img, origenX, origenY, 
                          anchoDelFragmento, altoDelFragmento, 0, 0, 
                          dimensionesCanva, dimensionesCanva);

            // lo hago arrastrable
            canvas.draggable = true;
            // asigno un evento de tactil para cuando se toca un canvas
            canvas.addEventListener('touchstart', e => {
              // al comenzar a arrastrar, identifico el canvas tocado
              const canvaSeccionado = $(e.changedTouches[0].target.id).id;
              consola("canvas seleccionado : " + canvaSeccionado)
              consola("0 " + e)
              // guardo temporalmente el canvas tocado
              this.currentCanvas = canvaSeccionado;
              let ctx = $(canvaSeccionado).getContext("2d");
              // muestro un cartel de canvas seleccionado
              let cartel = "Seleccionado";
              ctx.fillText(cartel,
                          20,
                          $(canvaSeccionado).clientHeight/2)
            })
            // asigno un evento tactil para cuando se mueve el canvas
            canvas.addEventListener('touchmove', e => {
              consola(this.currentCanvas.id);
              /**
               * por cada evento de toque en la pantalla, tomo al 
               * canvas selecionado, siendo el mismo el que guarde en 
               * el currentCanvas y le voy asignando las coordenadas 
               * de los eventos de toque, en la medida que se van creando. 
               */
              [...e.changedTouches].forEach(touch => {
                const dot = $(this.currentCanvas);
                dot.style.top = `${touch.clientY - dot.clientHeight/2}px`
                dot.style.left = `${touch.clientX - dot.clientWidth/2}px`
              })
      
            })

            // agrego el canvas a contenedor de piezas
            const divCanva = document.createElement('div');
            divCanva.className = 'divCanva';
            divCanva.id = j ;
            divCanva.appendChild(canvas);
            this.getPiezas().appendChild(divCanva);
            
      } // FIN FOR j
      
      const piezasArray = Array.from(piezasDiv.children); // Convierto a un array para mezclar

      // Mezclo las piezas aleatoriamente usando el algoritmo Fisher-Yates
      for (let i = piezasArray.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [piezasArray[i], piezasArray[j]] = [piezasArray[j], piezasArray[i]];
      }

      // Agrego las piezas mezcladas al div 'piezas'
      piezasArray.forEach((divCanva, index) => {
        piezasDiv.appendChild(divCanva);
      });


  } // FIN METODO  

  elementosAArray(selector) {
    // Obtengo el elemento del documento con el ID especificado en el selector
    const contenedor = $(selector);
    const arrayResultante = [];
    
    // Selecciono todos los elementos dentro del contenedor que tengan una de las dos clases CSS: .divCanva o .placeholder y luego los recorro uno por uno
    contenedor.querySelectorAll('.divCanva, .placeholder').forEach(elemento => {
      // Para cada elemento, creo un objeto vacío llamado infoElemento para almacenar su información
      const infoElemento = {};      
      // Almaceno el ID del elemento padre en el objeto infoElemento
      infoElemento.id = elemento.id;
      // Utilizo querySelector para encontrar un elemento descendiente de tipo canvas (si existe) dentro del elemento actual
      const hijo = elemento.querySelector('canvas');
      // Verifico si se encontró un hijo canvas
      if (hijo) {
        // Si se encontró un hijo canvas, almaceno su ID en el objeto infoElemento
        infoElemento.canvasId = hijo.id;
      }
      // Finalmente, agrego el objeto infoElemento al arrayResultante
      arrayResultante.push(infoElemento);
    });
    // La función devuelve el arrayResultante que contiene información sobre los elementos encontrados
    return arrayResultante;
  }

  async guardarImagesCanvas() {
    /**
     * inicializo un array canvasImages
     */
    const canvasImages = [];

    // guardo en una lista los canvas desordenados
    
    const canvasElements = Array.from(this.getPiezas().querySelectorAll('canvas'));

    // en la otra lista los canvas ordenados
    const canvasElementsPuzzle = Array.from(this.getPuzzle().querySelectorAll('canvas'));

    // Uno ambas listas
    const combinedCanvasElements = canvasElementsPuzzle.concat(canvasElements);

    /**
     * Recorro todos los elementos de la lista
     * obtengo el id del nodo padre
     * y lo guardo junto con la imagen y el id del padre
     * todo esto dentro de un objeto que contiene la información 
     * de todas las imágenes y eso es lo que voy a devolver
     */
  const promises = combinedCanvasElements.map(canvas => new Promise((resolve) => {
    canvas.toBlob(async function(blob) {
      if (blob) {
        const imageURL = URL.createObjectURL(blob);
        canvasImages.push({
          idCanvas: canvas.id.split('_')[1],
          imageDataUrl: imageURL,
        });
      }
      resolve(); // Resolvemos la promesa después de procesar cada canvas
    }, 'image/png');
  }));

  // Esperar a que todas las promesas se resuelvan
  await Promise.all(promises);
  console.log(canvasImages);
  return canvasImages;
  }    

  async guardarEstadoJuego(progreso_partida){
    // aumento en 1 la cantidad de movimientos
    this.movimientos++;


    let estado_partida = {
      divPiezasDesordenadas: this.elementosAArray('piezas'),
      divPiezasCompletadas: this.elementosAArray('puzzle'),
      aciertos: $('aciertos').getAttribute('data-value'), 
      errores: $('errores').getAttribute('data-value'), 
      tiempoTranscurrido: $('tiempo').innerHTML 
    };

    // console.log(estadoPartida);

    const id_usuario = $('id_usuario').getAttribute('data-value'); 
    const id_partida = $('id_partida').getAttribute('data-value'); 

    const imagesCanvas = this.movimientos > 1 ? await this.guardarImagesCanvas() : [];
    // Convertir el objeto estadoPartida a una cadena JSON
    let jsonData = {
      id_usuario: id_usuario,
      id_partida: id_partida,
      estado_partida: estado_partida,
      progreso: progreso_partida,
      puntaje: $('puntaje_actual').getAttribute('data-value'),
      imagesCanvas: imagesCanvas
    };

    jsonData = JSON.stringify(jsonData);

    console.log(jsonData);
    // Realizar la petición AJAX al servidor utilizando fetch
    fetch('/guardar_estado', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: jsonData
    })
    .then(response => response.text())
    .then(responseText => {
       console.log(responseText);
    })
    .catch(error => {
      console.log(error.message);
    });
    
  } // FIN METODO guardarEstadoJuego

  /**
   * El calculo del puntaje es de la siguiente manera:
   * Por cada acierto, ganas 10 puntos.
   * Por cada error, pierdes 5 puntos.
   * El tiempo se resta de la puntuación, donde cada segundo reduce la puntuación en 1 punto.
   * @returns {BigInteger}
   */
  calcularPuntaje() {
    // Calcular el puntaje
    const puntosPorAcierto = this.aciertos * 10;
    const puntosPorError = this.errores * -2; // Reducción del impacto de los errores
    const tiempoTranscurrido = obtenerTiempoEnSegundos();
    const puntosPorTiempo = tiempoTranscurrido * -0.5; // Reducción del impacto del tiempo

    // Puntaje mínimo (por ejemplo, 0)
    const puntajeMinimo = 0;

    const puntaje = puntosPorAcierto + puntosPorError + puntosPorTiempo;

    // Garantizar que el puntaje nunca sea menor que el puntaje mínimo
    return Math.max(puntaje, puntajeMinimo);
  }

  async enviarDataURIAlBackend(dataURIRecibida) {
    try {
      // Realiza una solicitud POST al servidor
      // console.log(dataURIRecibida);
      const response = await fetch('/guardar_imagen', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ dataURI : dataURIRecibida}), // Convierte el dataURI en una cadena JSON
      });

      if (response.ok) {
        const resultado = await response.json(); // Si el servidor responde con JSON
        console.log('El servidor respondió:', resultado);
      } else {
        console.error('Error al enviar el dataURI al servidor:', response.statusText);
      }
    } catch (error) {
      console.error('Error en la solicitud:', error);
    }
  }  

} // FIN CLASE

// Obtener el elemento del temporizador
const timerElement = $('tiempo');
const puzzle = $('puzzle');
const piezas = $('piezas');
const mensaje = $('mensaje');
const label_error = $('msj_error_url_imagen');

// Inicializar variables para el temporizador
let segundos = 0;
let minutos = 0;

$('btn_cargar').addEventListener('click', async (e) => {

  
  try {

    let url_data = $('url_data').value;
    /**
     * estos procesos deben deben ejecutarse
     * con await a fin de asegurar que la
     * siguiente variable contenga el valor
     * esperado.
     */
    const response = await fetch(url_data);

    if (!response.ok) {
      label_error.innerHTML = 'La solicitud no fue exitosa: ' + response.status;
      throw new Error(label_error);
    }else{
      label_error.innerHTML = '';
    }

    const blob = await response.blob();
    const dataURI = await blobToDataURI(blob);

    // Iniciar el temporizador
    const intervalo = setInterval(actualizarTemporizador, 1000); // Cada segundo (1000 ms)

    eliminarHijos(piezas);
    eliminarHijos(puzzle);

    const juego = new Juego(puzzle, piezas, 0, url_data, intervalo);

    juego.setCurrentCanvas(null);
    juego.setPiezas(piezas);
    juego.crearCanvasyAsignarPiezas(dataURI);
    
    // envio la imagen al backend
    await juego.enviarDataURIAlBackend(dataURI);

    let terminado = 9;
  
    juego.setTerminado(terminado);
    juego.setPuzzle(puzzle);

    juego.crearPlaceHolder(dataURI);
    juego.setPiezas(piezas);
    juego.setPuzzle(puzzle);
    juego.crearEventosDeMouse();
    
    juego.guardarEstadoJuego('iniciado');

  } catch (error) {
    if (error instanceof DOMException && error.name === 'TypeError') {
      label_error.innerHTML = 'Error de CORS:', error.message;
      console.error('Error de CORS:', error.message);

    } else {
      
      const CORs = '<a href="https://developer.mozilla.org/es/docs/Web/HTTP/CORS">CORs</a>'

      label_error.innerHTML = `Error en la solicitud: ${error.message} - 
                               Prueba con otra URL. Esta fué bloqueada por politicas de ${CORs}`;
      console.error(`Error en la solicitud: ${error.message}`);

      // agrego un estilo para que inicie una estilo de vibracion
      label_error.classList.add('vibrating-label');

      setTimeout(() => {
        label_error.classList.remove('vibrating-label');
      }, 1000); // Detiene la vibración después de 2 segundos
    }
  }
});
