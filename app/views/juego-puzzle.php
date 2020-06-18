<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<link rel="stylesheet" href="public/css/stilos.css">
	<title>Juego de Rompecabezas</title>
</head>
<body>
	<audio id="cheers" controls="none" preload="auto">
	<source src="public/audios/aud.mp3" type="audio/mp3">
	</audio>

	<audio id="cut" controls="none" preload="auto">
	<source src="public/audios/cut.mp4" type="audio/mp4">
	</audio>

	<audio id="no" controls="none" preload="auto">
	<source src="public/audios/no.mp4" type="audio/mp4">
	</audio>
  
	<h1 class="titulo_juego">Clasico juego de Puzzle</h1>

	<section class="juego">
		<section class="lienzo">
			<h1 id="message"></h1> <!-- Esta propiedad se va modificando mientras corre el juego-->
			<h1 id="moves"></h1> <!-- tambien se va modificando -->
			<canvas id="canvas" height="450px" width="450px"></canvas>
		</section>
		<section class="imagen_original">
			<h1>Imagen Original</h1>	
			<article class="piezas">
				<?php
					$cont = 1; 
					foreach ($piezas as $pieza){?>
					<article>
						<img id="puzz<?= $cont;?>" src="data:image/jpeg;base64,<?= $pieza;?>">
					</article>          
				<?php
					$cont=$cont+1; 
					}?>    
			</article>
		</section>
	</section>
	<script src="public/scripts/puzzle.js"></script>
</body>
</html>