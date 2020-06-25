window.addEventListener('load', function(){
    // var menu = document.getElementById(".menu_bar");
    nav.addEventListener('click',function(a){
        document.getElementById("nav").style.left=0; 
    });
},false);


var data = null;

var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function () {
	if (this.readyState === this.DONE) {
		console.log(this.responseText);
	}
});

xhr.open("GET", "https://30-000-radio-stations-and-music-charts.p.rapidapi.com/rapidapi?country=ALL&keyword=%3Crequired%3E&genre=ALL");
xhr.setRequestHeader("x-rapidapi-host", "30-000-radio-stations-and-music-charts.p.rapidapi.com");
xhr.setRequestHeader("x-rapidapi-key", "3be26f54a2msh006c59a828e1db8p19b192jsn720b4a3e484d");

xhr.send(data);