function togglePasswordVisibility() {
    const passwordInput = document.getElementById("password");
    const toggleButton = document.querySelector(".toggle-password");
    const toggleIcon = document.getElementById("toggle-icon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.textContent = "ocultar"; // Cambiar a ícono de ojo cerrado
    } else {
        passwordInput.type = "password";
        toggleIcon.textContent = "ver"; // Cambiar a ícono de ojo abierto
    }
}