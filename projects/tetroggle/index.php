<!--DOCTYPE html -->
<html>
	<head>
		<title>Tetroggle</title>
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="scripts.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				Tetroggle.init($('.bt-game-window'));
			});
		</script>
	</head>
	<body>
		<div class="bt-wrapper">
			<div class="bt-start-message">
                                <p>(note, please use Firefox until I figure out the keybindings.)</p>
				<p>Tetroggle Controls:</p>
				<ul>
					<li>Type to enter letters</li>
					<li>Backspace if you make a mistake</li>
					<li>Enter to submit a letter</li>
					<li>"\" to pause the game</li>
				</ul>
				<p>Ready to Start?</p>
				<label>Difficulty:</label>
				<select class="difficulty-select">
					<option value="1000">Easy</option>
					<option value="500">Medium</option>
					<option value="200">Hard</option>
				</select> <br />
				Name: <input></input>
				<a nohref="#" class="bt-button">
					Go!
				</a>
			</div>
			<div class="bt-end-message">
				<h2>Game Over!</h2>
				<div class="bt-high-scores">
				</div>
				<a nohref="#" class="bt-button">
					Reset
				</a>
			</div>
			<div class="bt-column">
			</div>
			<div class="bt-game-window">
			</div>
			<div class="bt-footer">
				<label>Score: </label>
				<input type="text" class="bt-score" disabled="disabled" value="0"></input>
				<label>Your word: </label>
				<input class="bt-word" type="text"></input>
			</div>
		</div>
	</body>
</html>