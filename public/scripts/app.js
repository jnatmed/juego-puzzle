import { Dificultad } from "./dificultad.js";
import { Rompecabezas } from "./rompecabezas.js";
import { FileVerifier } from "./fileverifier.js";

const verifier = new FileVerifier(2 * 1024 * 1024); // Tamaño máximo de 2 MB

// Configura la dificultad del juego (puedes cambiar estos valores)
const dificultadNovato = new Dificultad(3, 3);
const dificultadAvanzado = new Dificultad(4, 4);
const dificultadInsane = new Dificultad(6, 6);

const dificultadElegida = dificultadNovato;

// Input el elemento de entrada de archivo
const inputElement = document.getElementById("input_pc");
inputElement.addEventListener("change", async () => {
    try {
        const imageElement = await verifier.verifyFile(inputElement.files[0]);
        // Aquí puedes usar imageElement, que es una instancia de HTMLImageElement
        console.log("Imagen cargada desde PC:", imageElement.src);
        // Inicializa el juego con la dificultad seleccionada
        const rompecabezas = new Rompecabezas(dificultadElegida); // Cambia la dificultad según corresponda
        rompecabezas.iniciarJuego(imageElement.src);

    } catch (error) {
        alert(error);
        inputElement.value = "";
    }
});

// Input la entrada de URL
const urlInput = document.getElementById("url");
const loadFromURLButton = document.getElementById("loadFromURL");
loadFromURLButton.addEventListener("click", async () => {
    const url = urlInput.value;
    try {
        const imageElement = await verifier.verifyFile(url);
        // Aquí puedes usar imageElement, que es una instancia de HTMLImageElement
        console.log("Imagen cargada desde URL:", imageElement.src);
        // Inicializa el juego con la dificultad seleccionada
        const rompecabezas = new Rompecabezas(dificultadElegida); // Cambia la dificultad según corresponda
        rompecabezas.iniciarJuego(imageElement.src);

    } catch (error) {
        alert(error);
    }
});

// Input imágenes de la lista cargadas desde una API
const imageList = document.getElementById("image_list");
imageList.addEventListener("click", async (event) => {
    if (event.target.tagName === "IMG") {
        const imageUrl = event.target.src;
        try {
            const imageElement = await verifier.verifyFile(imageUrl);
            // Aquí puedes usar imageElement, que es una instancia de HTMLImageElement
            console.log("Imagen cargada desde la lista:", imageElement.src);
            // Inicializa el juego con la dificultad seleccionada
            const rompecabezas = new Rompecabezas(dificultadElegida); // Cambia la dificultad según corresponda
            rompecabezas.iniciarJuego(imageElement.src);

        } catch (error) {
            alert(error);
        }
    }
});