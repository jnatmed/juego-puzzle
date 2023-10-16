export class FileVerifier {
    constructor(maxSize) {
        this.maxSize = maxSize;
    }

    verifyFile(file) {
        if (file instanceof File) {
            if (!this.isImage(file)) {
                return Promise.reject("El archivo seleccionado no es una imagen válida.");
            }
            if (!this.isSizeValid(file)) {
                return Promise.reject("El archivo excede el tamaño máximo permitido.");
            }

            // Crear una URL de objeto para mostrar la imagen en una etiqueta <img>
            const imageUrl = URL.createObjectURL(file);
            const imageElement = new Image();
            imageElement.src = imageUrl;

            return Promise.resolve(imageElement);
        } else if (typeof file === "string") {
            // Cargar una imagen desde una URL
            const imageElement = new Image();
            imageElement.src = file;

            return new Promise((resolve, reject) => {
                imageElement.onload = () => resolve(imageElement);
                imageElement.onerror = () => reject("No se pudo cargar la imagen desde la URL.");
            });
        }

        return Promise.reject("Entrada no válida");
    }

    isImage(file) {
        return file.type.startsWith("image/");
    }

    isSizeValid(file) {
        return file.size <= this.maxSize;
    }
}