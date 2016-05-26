
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylesheet.css" />
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="ajaxCalls.js"></script>
		<script id="script" type="text/javascript">
			var i = 0; //GLOBAL VARIABLE for giving monsters and treasures distinct ids
			var mk = 0; //GLOBAL VARIABLE for counting number of monsters killed
			var tg = 0; //GLOBAL VARIABLE for counting number of treasures gotten
			var aMonsters = [];

			function character(type, position){
				this.charType = type;
				this.level = 1;
				this.mana = 100;
				this.health = 100;
				if(position){
					this.charLocation = position;
					this.top = position.top;
					this.left = position.left;
				}

				this.getImage = function(name){

					switch(name){
						case "staab":
							this.image1 = "staab1.png";
							this.image2 = "staab2.png";
						break;
						case "donny":
							this.image1 = "donny1.png";
							this.image2 = "donny2.png";
						break;
						case "jordan":
							this.image1 = "jordan1.png";
							this.image2 = "jordan2.png";
						break;
					}
					$('#hero0').css("background", "url('"+this.image1+"')")
				}

				this.createNewChar = function(){
					var charDiv = document.createElement('div');
					charDiv.id = this.charType + i;
					i++;

					if(this.charType == "hero"){
						charDiv.className = "hero"
						charDiv.style.background = this.image1;
					}
					else if(this.charType == "monster"){
						charDiv.className = "monster"
						charDiv.style.top = Math.floor(Math.random()*560);
						charDiv.style.left = Math.floor(Math.random()*760);
					}
					else if(this.charType == "treasure"){
						charDiv.className = "treasure"
						charDiv.style.top = this.top;
						charDiv.style.left = this.left;
					}
					else if(this.charType == "bomb"){
						charDiv.className = "bomb";
						charDiv.style.top = this.top;
						charDiv.style.left = this.left;
					}
					else if(this.charType == "hPotion"){
						charDiv.className = "hPotion";
						charDiv.style.top = this.top;
						charDiv.style.left = this.left;
					}
					document.getElementById("game-wrapper").appendChild(charDiv);
				}

				this.fight = function(){
					$('#hero0').css("background-image", "url('"+hero.image2+"')");
					setTimeout(function(){$('#hero0').css("background-image", "url('"+hero.image1+"')")}, 150);

					var monsters = document.getElementsByTagName('div'), j = 0;
					for(j in monsters){
						if(monsters[j] && monsters[j].className == "monster"){
							var $monster = $('#'+monsters[j].id),
				      		monsterPosition = $monster.position(),
				      		monsterCenter = {top: monsterPosition.top+20, left: monsterPosition.left+20},
							heroPosition = $('#hero0').position(),
				      		heroCenter = {top: heroPosition.top+12, left: heroPosition.left+20};

							if((Math.abs(heroCenter.top - monsterCenter.top) < 60) && 
							(Math.abs(heroCenter.left - monsterCenter.left) < 70)){
								killMonster(monsters[j], monsterPosition);
							}
						}
					}
				}

				this.fireball = function(){
					if(this.mana >= 50){
						var $hero = $('#hero0'),
				      	heroPosition = $hero.position(),
				      	heroCenter = {top: heroPosition.top+13, left: heroPosition.left+20},
				      	monsters = document.getElementsByTagName('div');
				      	for(j in monsters){
							if(monsters[j] && monsters[j].className == "monster"){
						      	var $monster = $('#'+monsters[j].id),
					      		monsterPosition = $monster.position(),
					      		monsterCenter = {top: monsterPosition.top+20, left: monsterPosition.left+20};

								var fireball = document.createElement('div');
								fireball.className = "fireball";
								fireball.style.left = heroCenter.left;
								fireball.style.top = heroCenter.top;
								var wrapper = document.getElementById("game-wrapper");
								wrapper.appendChild(fireball);

								$('.fireball').animate({top: monsterCenter.top, left: monsterCenter.left}, 200);
								setTimeout(function(){
									killMonster(monsters[j], monsterPosition);
									wrapper.removeChild(fireball);
								}, 200);
								this.mana -= 50;
								this.regenerateMana();

								break;
							}
						}
					}
				}

				this.energyRing = function(){
					if(this.mana >= 75){
						var $hero = $('#hero0'),
				      	heroPosition = $hero.position(),
				      	heroCenter = {top: heroPosition.top+13, left: heroPosition.left+20},
				      	monsters = document.getElementsByTagName('div');

						var energyring = document.createElement('img');
						energyring.className = "energyring";
						energyring.style.left = heroCenter.left;
						energyring.style.top = heroCenter.top;
						energyring.setAttribute("src", "energyring.png");
						var wrapper = document.getElementById("game-wrapper");
						wrapper.appendChild(energyring);
						var $energyring = $('.energyring');

						$energyring.animate({
							width: 400,
							height: 400,
							opacity: 0,
							left: heroCenter.left-200,
							top: heroCenter.top-200
						}, 400);

						setTimeout(function(){
					      	for(j in monsters){
								if(monsters[j] && monsters[j].className == "monster"){
							      	var $monster = $('#'+monsters[j].id),
						      		monsterPosition = $monster.position(),
						      		monsterCenter = {top: monsterPosition.top+20, left: monsterPosition.left+20};
						      		if((Math.abs(heroCenter.top - monsterCenter.top) < 400) && 
										(Math.abs(heroCenter.left -monsterCenter.left) < 400)){
										killMonster(monsters[j], monsterPosition);
										killMonster(monsters[j], monsterPosition);
										dropThings(monsterPosition);
									}
								}
							}
							$energyring.css("width", "1");
							$energyring.css("height", "1");
							$energyring.css("opacity", "1");
							wrapper.removeChild(energyring);
							hero.mana -= 75;
							hero.regenerateMana();
						}, 400);
					}
				}

				this.regenerateMana = function(){
					var manaBar = document.getElementById('mana-level');
					manaBar.style.width = this.mana + "px";
					if(this.mana <= 99){
						setTimeout("hero.regenerateMana()", 300);
						this.mana +=1;
					}
				}

				this.regenerateHealth = function(){
					var healthBar = document.getElementById('health-level');
					healthBar.style.width = this.health + "px";
					if(this.health <= 99){
						setTimeout("hero.regenerateHealth()", 1000);
						this.health +=1;
					}
				}

				this.levelUp = function(){
					var nextLevel = (hero.level+9)*(hero.level),
						previousLevel = (hero.level+8)*(hero.level-1),
						progress = document.getElementById("progress"),
						progressSpan = document.getElementById("progress-span");
					progress.style.width = Math.floor(((mk-previousLevel)/(nextLevel-previousLevel))*100) + "px";
					progressSpan.innerHTML = hero.level;
					if(mk >= nextLevel){
						hero.level++;
					}
					progress.style.width = Math.floor(((mk-previousLevel)/(nextLevel-previousLevel))*100) + "px";
				}
			}

			$(document).keydown(function(e){
				var $hero = $('#hero0');
				var heroPosition = $hero.position();
				var keyCode = e.keyCode || e.which,
      				key = {left: 37, up: 38, right: 39, down: 40, space: 32, enter: 13, one: 49,
      					two: 50, three: 51, four: 52, five: 53, six: 54, seven: 55, eight: 56, nine: 57, ten: 48};
      			switch (keyCode){
					case key.left:
						if(parseInt($hero.css("left")) >= 0){
							$hero.css("left", "-=10");
						}
					break;
					case key.up:
						if(parseInt(heroPosition.top) >= 0){ 
							$hero.css("top", "-=10")
						}
					break;
					case key.right:
						if(parseInt($hero.css("left")) <= 760){
							$hero.css("left", "+=10")
						}
					break;
					case key.down:
						if(parseInt($hero.css("top")) <= 570){
							$hero.css("top", "+=10")
						}
					break;
					case key.space:
						playSound("sword");
						hero.fight();
					break;
					case key.enter:
						if(i <= 1){
							closeLightbox();
						}
					break;
					case key.one:
						if(hero.level >= 2){
							hero.fireball();
						}
					break;
					case key.two:
						if(hero.level >=3){
							hero.energyRing();
						}
					break;
				}
				var treasures = document.getElementsByTagName('div'), j = 0;
				for(j in treasures){
					if(treasures[j] && treasures[j].className == "treasure"){
						var $treasure = $('#'+treasures[j].id),
			      		treasurePosition = $treasure.position(),
			      		treasureCenter = {top: treasurePosition.top+20, left: treasurePosition.left+20},
						heroPosition = $('#hero0').position(),
			      		heroCenter = {top: heroPosition.top+12, left: heroPosition.left+20};
			      		if((Math.abs(heroCenter.top - treasureCenter.top) < 32) && 
							(Math.abs(heroCenter.left - treasureCenter.left) < 40)){
			      			playSound("treasure");
			      			treasures[j].parentNode.removeChild(treasures[j]);
			      			tg++;
			      			document.getElementById('treasures-gotted').innerHTML = tg;
							var luckRating = Math.floor((tg/mk)*10);
							document.getElementById('luckiness-rating').innerHTML = luckRating;
							var score = luckRating*hero.level*hero.level+mk+tg;
							document.getElementById('score').innerHTML = score;
			      		}
			      	}
			    }
			    var bombs = document.getElementsByTagName('div'), j = 0;
				for(j in bombs){
					if(bombs[j] && bombs[j].className == "bomb"){
						var $bomb = $('#'+bombs[j].id),
			      		bombPosition = $bomb.position(),
			      		bombCenter = {top: bombPosition.top+20, left: bombPosition.left+15},
						heroPosition = $('#hero0').position(),
			      		heroCenter = {top: heroPosition.top+12, left: heroPosition.left+15};
			      		if((Math.abs(heroCenter.top - bombCenter.top) < 32) && 
							(Math.abs(heroCenter.left - bombCenter.left) < 40)){
							playSound("bomb");
			      			bombs[j].parentNode.removeChild(bombs[j]);
			      			var monsters = document.getElementsByTagName('div');
			      			j = 0;
							for(j in monsters){
								if(monsters[j] && monsters[j].className == "monster"){
									monsterPosition = $('#'+monsters[j].id).position();
									monsters[j].parentNode.removeChild(monsters[j]);
									dropThings(monsterPosition);
								}
							}
			      		}
			      	}
			    }
			    var hPotions = document.getElementsByTagName('div'), j = 0;
				for(j in hPotions){
					if(hPotions[j] && hPotions[j].className == "hPotion"){
						var $hPotion = $('#'+hPotions[j].id),
			      		hPotionPosition = $hPotion.position(),
			      		hPotionCenter = {top: hPotionPosition.top+20, left: hPotionPosition.left+20},
						heroPosition = $('#hero0').position(),
			      		heroCenter = {top: heroPosition.top+12, left: heroPosition.left+20};
			      		if((Math.abs(heroCenter.top - hPotionCenter.top) < 32) && 
							(Math.abs(heroCenter.left - hPotionCenter.left) < 40)){
			      			hPotions[j].parentNode.removeChild(hPotions[j]);
			      			hero.health = 100;
			      			hero.regenerateHealth();
			      		}
			      	}
			    }
			});

			function dropThings(monsterPosition){
				mk++;
  				hero.levelUp();
				document.getElementById('monsters-killed').innerHTML = mk;
				var luckRating = Math.floor((tg/mk)*10);
				document.getElementById('luckiness-rating').innerHTML = luckRating;
				var score = luckRating*hero.level*hero.level+mk+tg;
				document.getElementById('score').innerHTML = score;

				var treasureNumber = Math.floor(Math.random()*10);
				var treasureEvent = Math.floor(Math.random()*5);
				var k = 0;
				if(treasureEvent > 2){
					while(k < treasureNumber){
						var treasure = new character("treasure", monsterPosition);
						treasure.createNewChar();
						k++;
					}
				}
				var bombEvent = Math.floor(Math.random()*20);
				if(bombEvent < 2 ){
					var bomb = new character("bomb", monsterPosition);
					bomb.createNewChar();
				}
				var hPotionEvent = Math.floor(Math.random()*30);
				if(hPotionEvent < 2 ){
					var hPotion = new character("hPotion", monsterPosition);
					hPotion.createNewChar();
				}
			}

			function findMonster(){
				var divs = document.getElementsByTagName('div'), j = 0;
				for(j in divs){
					if(divs[j] && divs[j].className == "monster"){
						moveMonster(divs[j]);
					}
				}

				setTimeout("findMonster()", 100);
			}

			function moveMonster(monster){
				var direction = Math.floor(Math.random()*4),
		      		$monster = $('#'+monster.id),
		      		position = $monster.position();

		      		$monster.css("top", position.top).css("left", position.left);//fix for monster movement acceleration

		      	var center = {top: position.top+20, left: position.left+20},
		      		heroPosition = $('#hero0').position(),
		      		heroCenter = {top: heroPosition.top+12, left: heroPosition.left+20};

				switch(direction){
					case 0:
						if(!(position.top <= 40)){
							$('#'+monster.id).animate({top: '-=20'}, 90, 'linear');
						}
					break;
					case 1:
						if(position.left <= 720){
							$('#'+monster.id).animate({left: '+=20'}, 90, 'linear');
						}
					break;
					case 2:
						if(position.top <= 530){
							$('#'+monster.id).animate({top: '+=20'}, 90, 'linear');
						}
					break;
					case 3:
						if(!(position.left <= 40)){
							$('#'+monster.id).animate({left: '-=20'}, 90, 'linear');
						}
					break;
				}

				if((Math.abs(heroCenter.top - center.top) < 30) && (Math.abs(heroCenter.left - center.left) < 40)){
					hero.health -= 75;
					hero.regenerateHealth();
					monster.parentNode.removeChild(monster);
					dropThings(position);
					if(hero.health <= 0){
						showStats();
					}
				}
			}

			function killMonster(monster, monsterPosition){
				aMonsters[monster.id].health -= (300/hero.level);
				if(aMonsters[monster.id].health <= 0){
					playSound("monster");
					monster.parentNode.removeChild(monster);
					dropThings(monsterPosition);
				}
			}

			function createCharacter(type){
				if(type == "hero"){
					hero = new character(type);//GLOBAL VARIABLE
					hero.createNewChar();
				}
				else{
					var newCharacter = new character(type);
					newCharacter.createNewChar();
				}
			}

			function monsterSpawn(){
				var divs = document.getElementsByTagName('div');
				var numDivs = parseInt(divs.length);
				if(numDivs < 1000){
					setTimeout(function(){
						aMonsters["monster"+i] = new character("monster");
						aMonsters["monster"+i].createNewChar();
						monsterSpawn();
					}, 2000);
				}
			}

			function closeLightbox(){
				if(hero.image1){
					$('#lightbox-backdrop').animate({opacity: 0}, 500, 'linear');
					$('#lightbox').animate({opacity: 0}, 500, 'linear');
					var heroName = document.getElementById("hero-name");
					var heroNameInput = document.getElementById("hero-name-input").value;
					if(!heroNameInput == ""){
						heroName.innerHTML = heroNameInput;
					}

					setTimeout(function(){
						$('#lightbox-backdrop').css("display", "none");
						$('#lightbox').css("display", "none");
					}, 1000);

					monsterSpawn();
					findMonster();
				}
				else{
					alert("Please choose a character!");
				}
			}

			$(document).ready(function(){$(".char_choice").click(function(){
					$(".char_choice").css("background-color", "white");
					$(this).css("background-color", "#dfe");
					hero.getImage(this.id);
				})
			});

			function playSound(sound) {
				var soundDiv =  document.getElementById("sound");
				soundDiv.innerHTML = "";
				switch (sound){
					case "bomb":
						soundDiv.innerHTML=
						"<embed src=\"LOZ_Bomb_Blow.wav\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
					break;
					case "treasure":
						soundDiv.innerHTML=
						"<embed src=\"LOZ_Shield.wav\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
					break;
					case "sword":
						soundDiv.innerHTML=
						"<embed src=\"LOZ_Sword.wav\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
					break;
					case "monster":
						soundDiv.innerHTML=
						"<embed src=\"monster.wav\" hidden=\"true\" autostart=\"true\" loop=\"false\" />";
					break;
				}
			}
		</script>
	</head>
	<body onload="createCharacter('hero'); ">
		<div id="lightbox-backdrop">
		</div>
		<div id="game-wrapper">
			<div id="lightbox-wrapper">
				<div id="lightbox">
					<h1>Choose Your Character</h1>
					<div id="staab" class="char_choice"><img src="staab1.png" /></div>
					<div id="donny" class="char_choice"><img src="donny1.png" /></div>
					<div id="jordan" class="char_choice"><img src="jordan1.png" /></div>
					<label>Hero Name: </label><input type="text" id="hero-name-input" value=
						<?php (isset($_GET['heroname'])) ? $strHeroname = $_GET['heroname'] : $strHeroname = "" ; echo "\"".$strHeroname."\"" ?>
					></input>
					<input type="button" value="Start!" onclick="closeLightbox();"></input>
				</div>
			</div>
			<div id="stats">
				<h2 id="hero-name" value="Hero">Hero</h2>
				<label>Level: </label><div id="level"><div id="progress">
					<span id="progress-span">1</span>
				</div></div><br />
				Monsters Killed: <span id="monsters-killed"></span><br />
				Treasures Gotted: <span id="treasures-gotted"></span><br />
				Luckiness Rating: <span id="luckiness-rating"></span><br />
				Score: <span id="score"></span><br />
			</div>
			<div id="hero-status">
				<div id="health"><div id="health-level"></div></div>
				<div id="mana"><div id="mana-level"></div></div>
			</div>
			<a href="#" id="instructions">
				How to Play
				<div>
					<h2>(How to Play)</h2>
					<ul>
						<li>Arrow Keys to Move</li>
						<li>Space Bar to Fight</li>
						<li>As you level up, you will be able to use the number keys to access different powers.</li>
						<li>Kill monsters! Get treasure!</li>
					</ul>
				</div>
			</a>
		</div>
		<div id="sound"></div>
	</body>




</html>