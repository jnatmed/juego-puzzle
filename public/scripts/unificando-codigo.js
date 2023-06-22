actualizarMatriz(array, estadoMatriz){

    array.childNodes.forEach((div, cont) => {
        if(cont != 0){
            if (div.childNodes.length > 0){
                estadoMatriz[div.id] = div.childNodes[0].id;
            }else{
                estadoMatriz[div.id] = -1;
            }
        }
    });

    return estadoMatriz;
}


    this.getPiezas().childNodes.forEach((divCanva, i) => {    
    // SECTOR PIEZAS
      if(i != 0){
        // SI TIENE HIJOS ENTONCES 
        if (divCanva.childNodes.length > 0) {
          estadoPieza[divCanva.id] = divCanva.childNodes[0].id; 
          this.setEstadoPiezas(estadoPieza);
        }else{
          estadoPieza[divCanva.id] = -1;
          this.setEstadoPiezas(estadoPieza);
        }
      }
    });
