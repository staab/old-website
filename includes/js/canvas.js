var lastMouseMove = {posX: 0, posY: 0};

// Draws a background based on mouse position. x and y are the coordinates of the mouse.
function drawBackground(x, y){
	var canvas = document.getElementById("background"),
		$canvas = $(canvas),
		context = canvas.getContext("2d"),
		backgroundColor = $('body').css('background-color');

	$canvas.height($(document).height());

	// Erase canvas
	var width = canvas.width = $canvas.width();
	var height = canvas.height = $canvas.height();

	x = (x/150) + 100;
	y = (y/150) + 100;

	x = x < 100 ? 100 : x;
	y = y < 100 ? 100 : y;

	negator = 1;
	for(i = -height/2; i < height/2; i += y*2){
		for(j = 0; j < width; j += x*1.2){
			negator = negator*(-1);

			// if((j+i)%2 > 1){
			// 	context.fillStyle = "#000";
			// 	context.strokeStyle = "#000";
			// }
			// else {
			// 	context.strokeStyle = backgroundColor;
			// 	context.fillStyle = backgroundColor;
			// }
			// context.fillRect(j, i, j, i);

			if(negator > 0){
				context.beginPath();
				context.moveTo(width, height/2);
				context.bezierCurveTo(j - 500, height/2 - i, -100, height/2 + i*1.5, width, i + height/2);
				context.strokeStyle = '#000';
				context.lineWidth = 2;
				context.stroke();
			}

			context.beginPath();
			context.moveTo(width, height/2 + i);
			context.bezierCurveTo(0, height/2, width/2, height/2 + (height/4 * negator), j, height/2 + (height/2 * negator));
			context.strokeStyle = '#000';
			context.lineWidth = 2;
			context.stroke();
		}
	}
}

function createThumbnails(){
	$('.project-thumbnail').each(function(){
		var projectName = $(this).attr('id'),
			canvas = this,
			$canvas = $(canvas),
			context = canvas.getContext("2d"),
			negator = -1;

		width = canvas.width = $canvas.closest('a').width();
		height = canvas.height = $canvas.closest('a').height();
		$canvas.css({'position': 'absolute', 'top': '0', 'left': '0'});

		for(y = 0; y < height; y += 10){
			negator = -negator

			endX = negator > 0 ? width : 0;

			context.beginPath();
			context.moveTo(width/2, height);
			context.bezierCurveTo(width/2 + 100*negator, height, endX, height, endX, y);
			context.strokeStyle = '#000';
			context.lineWidth = 2;
			context.stroke();
		}
	});
}


//update the background on mouse movement.
$(document).ready(function(){
	drawBackground(0, 0);
	createThumbnails();
	$(document).mousemove(function(event){
		if(Math.abs(event.clientX - lastMouseMove.posX) > 25 || Math.abs(event.clientY - lastMouseMove.posY) > 25){
			lastMouseMove.posX = event.clientX;
			lastMouseMove.posY = event.clientY;
			drawBackground(event.clientX, event.clientY);
		}
	});
});