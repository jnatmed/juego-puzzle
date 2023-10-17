const equivalencias = {
    "a": "a",
    "b": "a",
    "c": "a",
    "d": "b",
    "e": "b",
    "f": "c",
    "g": "t",
    "h": "e",
    "i": "h",
    "j": "i",
    "k": "ca",
    "l": "k",
    "m": "l",
    "n": "l",
    "ñ": "m",
    "o": "n",
    "p": "o",
    "q": "o",
    "r": "pp",
    "s": "cu",
    "t": "ku",
    "u": "x",
    "v": "x",
    "w": "u",
    "x": "u",
    "y": "z",
    "z": "p"
  };
  
  function traducirFrase(frase) {
    frase = frase.toLowerCase();
    let fraseTraducida = "";
  
    for (let i = 0; i < frase.length; i++) {
      const letra = frase[i];
      if (letra in equivalencias) {
        fraseTraducida += equivalencias[letra];
      } else {
        fraseTraducida += letra;
      }
    }
  
    return fraseTraducida;
  }
  
  const fraseOriginal = "LAS DIFERENCIAS NOS ENRIQUECEN Y EL RESPETO NOS UNE";
  const fraseTraducida = traducirFrase(fraseOriginal);
  
  console.log("Frase original: " + fraseOriginal);
  console.log("Frase traducida: " + fraseTraducida);
  