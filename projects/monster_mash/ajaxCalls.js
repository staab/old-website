
function showStats(){
	
	var name = $('#hero-name').html(),
		level = $('#progress-span').html(),
		monsterskilled = $('#monsters-killed').html(),
		treasuresgotted = $('#treasures-gotted').html(),
		luckinessrating = $('#luckiness-rating').html();

	$.ajax({
		url: "highscores.php",
		data: {
			name: name,
			level: level,
			monsterskilled: monsterskilled,
			treasuresgotted: treasuresgotted,
			luckinessrating: luckinessrating
		},
		success: function(data){
			var statsDiv = document.createElement("div");
			statsDiv.className = "stats_div";
			statsDiv.innerHTML = data;
			var wrapper = document.getElementById('game-wrapper');
			wrapper.innerHTML = "";
			wrapper.appendChild(statsDiv);

			var restartButton = document.createElement('a');
			var restartText = document.createTextNode("Try Again!");
			var heroLink = "/projects/monstermash/?heroname="+name;
			restartButton.setAttribute("href", heroLink);
			restartButton.id = "restart-button";
			restartButton.appendChild(restartText);
			//document.getElementById('game-wrapper').innerHTML = "";
			document.getElementById('game-wrapper').appendChild(restartButton);
			i = 0;
		}
	});
}