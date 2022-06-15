
  let boton = document.getElementById("boton"); // Encuentra el elemento "boton" en el sitio
  boton.onclick = traeInfo; // Agrega función onclick al elemento
    
  function traeInfo(evento) {
	const data = null;

	const xhr = new XMLHttpRequest();
	xhr.withCredentials = true;
	
	xhr.addEventListener("readystatechange", function () {
		if (this.readyState === this.DONE) {
			console.table(this.responseText);
		}
	});
	
	xhr.open("GET", "https://ip-geo-location.p.rapidapi.com/ip/check?format=json");
	xhr.setRequestHeader("X-RapidAPI-Host", "ip-geo-location.p.rapidapi.com");
	xhr.setRequestHeader("X-RapidAPI-Key", "3be26f54a2msh006c59a828e1db8p19b192jsn720b4a3e484d");
	
	xhr.send(data);
  }


	