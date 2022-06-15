var nombre_alumno = document.getElementById("nombre_alumno");

nombre_alumno.addEventListener('change', (event) => {
    const nombre = event.target.value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            var respuesta = JSON.parse(this.response);
            var grado = document.getElementById("grado");
            grado.value = respuesta['grado'];     
            grado.disabled = true;
            console.table(respuesta);
        }   
        if(this.readyState == 2){
			console.table("response header received")
		}
    }
    var data = new FormData();
    data.append('nombre_alumno', nombre);
    xhttp.open("POST", "enviar_nombre");
	xhttp.send(data);
});


