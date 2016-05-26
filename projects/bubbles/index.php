<?php
require_once('../../includes/prepend.php');
require_once('../../includes/header.php');
?>
		<title>Circles</title>
		<style>
			canvas {
				margin: 0 auto;
				background-color: black;
			}
		</style>
		<script type="text/javascript">


			$(document).ready(function(){
				$("#my_canvas").click(function(e){
					var random1 = Math.floor(Math.random()*9);
					var random2 = Math.floor(Math.random()*9);
					var random3 = Math.floor(Math.random()*9);
					var canvas = document.getElementById('my_canvas');
					var context = canvas.getContext("2d");
					var centerX = e.pageX;
					var centerY = e.pageY;
					var radius = Math.floor(Math.random()*60);


					context.id = "context";
					context.beginPath();
					context.arc(centerX, centerY, radius, 0, 2 * Math.PI, false);
					//context.fillStyle = "#" + random1 + "f" + random2 +"f" + random3 + "f";
					context.fillStyle = "#" + Math.floor(Math.random()*16777215).toString(16);
					context.fill();
					context.lineWidth = 2;
					context.strokeStyle = "white";
					context.stroke();
				});
			})
		</script>
	</head>
	<body>
		<canvas id="my_canvas" width="800" height="600"></canvas>
	</body>

</html>