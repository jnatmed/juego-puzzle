document.addEventListener("DOMContentLoaded", function() {
    const menuIcon = document.getElementById("menu_icon");
    const menuPanel = document.getElementById("menu_general");

    menuIcon.addEventListener("click", function() {
        menuPanel.classList.toggle("hidden");
    });
    menuIcon.addEventListener("touchstart", function() {
        menuPanel.classList.toggle("hidden");
    });

});
