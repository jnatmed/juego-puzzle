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

document.getElementById("boton_achicar_div").addEventListener("click", function() {
  var div = document.getElementById("busqueda");
  var boton_achicar_div = document.getElementById("boton_achicar_div");

  if (div.style.height == "10px"){
      div.style.height = "20%"; 
      boton_achicar_div.style.transform = "rotate(180deg)"; // Rota el botón al presionarlo
    }else{
      div.style.height = "10px"; 
      boton_achicar_div.style.transform = "rotate(180deg)"; // Rota el botón al presionarlo
  }
});




