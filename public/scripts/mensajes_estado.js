document.addEventListener("DOMContentLoaded", function () {
    const closeButton = document.getElementById("close-button");
    const successMessage = document.querySelector(".success-message");

    closeButton.addEventListener("click", function () {
        successMessage.style.display = "none"; // Oculta el div cuando se hace clic en la "X"
    });

    // Muestra el div de mensaje de éxito (por ejemplo, después de procesar una solicitud)
    function showSuccessMessage() {
        successMessage.style.display = "grid"; // Muestra el div
    }

    // Llama a la función para mostrar el mensaje de éxito (puedes llamarla en tu código cuando sea necesario)
    showSuccessMessage(); // Llamamos a la función para mostrar el mensaje por defecto
});