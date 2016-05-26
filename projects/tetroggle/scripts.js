var Tetroggle = {
	gameOn: false,
	$gameWrapper: null,
	$gameWindow: null, //the jquery object we're dealing with here
	$startMessage: $(''),
	$endMessage: $(''),
	$activeBlock: $(''),
	bPaused: 'false',
	activeBlock: {}, //js object for active blocks
	oActiveBlocks: {}, //js object with properties for each active block
	aActiveBlockTypes: ['l', 'pyramid', 'square', 'long', 's'], // possible block configurations
	aAlphabet: ['a', 'a', 'a', 'a', 'a', 'a', 'b', 'b', 'c', 'c', 'd', 'd', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 
		'e', 'e', 'e', 'e', 'e', 'f', 'f', 'g', 'g', 'h', 'h', 'h', 'h', 'h', 'i', 'i', 'i', 'i', 'i', 'i', 
		'j', 'k', 'l', 'l', 'l', 'l', 'm', 'm', 'n', 'n', 'n', 'n', 'n', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 
		'p', 'p', 'qu', 'r', 'r', 'r', 'r', 'r', 's', 's', 's', 's', 's', 's', 't', 't', 't', 't', 't', 't', 't', 
		't', 't', 'u', 'u', 'u', 'v', 'v', 'w', 'w', 'w', 'x', 'y', 'y', 'y', 'z'],//letters of the alphabet
	sPlayerName: '',
	aSelectedBlockSelectors: [], //array of jquery objects that may have multiple matches
	aSelectedBlocks: [], //array of jquery objects actually selected
	defaultSpeed: 800,
	speed: this.defaultSpeed,
	rotation: 0,
	init: function(element){ 
		var game = this;
		if(!this.$gameWindow){
			this.$gameWindow = element;
		}
		this.$gameWrapper = this.$gameWindow.parent('.bt-wrapper');
		this.$startMessage = this.$gameWrapper.find('.bt-start-message');
		this.$startMessage.show();
		this.$startMessage.find('.bt-button').click(function(){
			game.start();
		});
		this.$endMessage = this.$gameWrapper.find('.bt-end-message');
		this.$endMessage.find('.bt-button').click(function(){
			game.gameReset();
		});
		$(document).keydown(function(event){
			if(event.keyCode == 8 || event.charCode == 92){
				event.preventDefault();
			}
			game.handleKeypress(event);
		});
	},	
	start: function(){
		this.defaultSpeed = parseInt(this.$startMessage.find('.difficulty-select').val());
		this.gameOn = true;
		this.sPlayerName = this.$startMessage.find('input').val();
		this.$startMessage.hide();
		this.setupBlocks();
	},
	end: function(){
		if(this.gameOn == false) return;
		this.gameOn = false;
		var score = this.$gameWrapper.find('input.bt-score').val(),
			name = this.sPlayerName,
			game = this;
		this.aSelectedBlocks = [];
		this.aSelectedBlockSelectors = [];
		this.refreshInput();
		this.$endMessage.show();

		$.ajax({
			url: 'highscores.php',
			data: {
				score: score,
				name: name
			},
			success: function(res){
				res = $.parseJSON(res);
				game.$endMessage.find('.bt-high-scores').empty().append(res);
			}
		});
	},
	gameReset: function(){
		this.$endMessage.hide();
		this.$gameWindow.empty();
		this.$gameWrapper.find('input.bt-score').val(0);
		this.$gameWindow.closest('.bt-wrapper').find('.bt-start-message').show();		
	},
	setupBlocks: function(){
		this.speed = this.defaultSpeed; //reset speed
		this.rotation = 0; //reset rotation
		this.createActiveBlock();
		var $activeBlock = $('<div class="bt-active-block" name="'+this.activeBlock.blockType+'">').css({
			width: this.activeBlock.width + "px",
			height: this.activeBlock.height + "px",
			top: (0 - this.activeBlock.height) +"px",
			left: "120px"
		});

		for(var i in this.oActiveBlocks){
			var block = this.oActiveBlocks[i],
				$block = this.createBlock(block, i);
				$block.appendTo($activeBlock);
		}
		$activeBlock.appendTo(this.$gameWindow);
		this.$activeBlock = $activeBlock;

		this.dropBlocks($activeBlock);

		//cleanup for next time
		this.activeBlock = {}; 
		this.oActiveBlocks = {};
	},
	dropBlocks: function($activeBlock, speed){
		var game = this,
			bCollision,
			activeBlockPosition;
		this.speed = typeof(speed) != 'undefined' ? speed : this.speed;
		$activeBlock.animate({'top': '+=30'}, this.speed, 'linear', function(){
			activeBlockPosition = $(this).position();
			bCollision = game.isFatalCollision($activeBlock);
			bFull = game.isFull($activeBlock);
			if(!bCollision){
				game.dropBlocks($(this));
			}
			else {
				if(bFull){
					game.end();
				}
				else {
					$(this).children().each(function(){
						var thisBlockPosition = $(this).position();
						$(this).css({
							'top': activeBlockPosition.top + thisBlockPosition.top,
							'left': activeBlockPosition.left + thisBlockPosition.left
						}).removeClass('active').appendTo(game.$gameWindow);
					});
					$(this).remove();
					game.setupBlocks();
				}
			}
		});
	},
	//check if the block is touching anything
	isFatalCollision: function($activeBlock){
		var activeBlockPosition = $activeBlock.position(),
			finalReturn = false;
		//check the window edges first
		if(activeBlockPosition.top >= this.$gameWindow.height() - $activeBlock.height()
			|| activeBlockPosition.left < 0
			|| activeBlockPosition.left > this.$gameWindow.width() - $activeBlock.width()){
			return true;
		}

		// check all the other blocks
		$activeBlock.children().each(function(){
			var childPosition = $(this).position();
			$('.bt-block').not('.active').each(function(){
				var blockPosition = $(this).position();
				if(activeBlockPosition.left + childPosition.left == blockPosition.left 
					&& (activeBlockPosition.top + childPosition.top <= blockPosition.top + 30
						&& activeBlockPosition.top + childPosition.top >= blockPosition.top - 30)){
					finalReturn = true;
				}
					
			});
		});
		return finalReturn;
	},
	isBlockingCollision: function($activeBlock, direction){
		var activeBlockPosition = $activeBlock.position(),
			finalReturn = false;
		//check the window edges first
		if(activeBlockPosition.top >= this.$gameWindow.height() - $activeBlock.height()
			|| (activeBlockPosition.left <= 0 && direction == 'left')
			|| (activeBlockPosition.left >= this.$gameWindow.width() - $activeBlock.width() && direction == 'right')){
			return true;
		}

		// check all the other blocks
		$activeBlock.children().each(function(){
			var childPosition = $(this).position();
			$('.bt-block').not('.active').each(function(){
				var blockPosition = $(this).position();
				if(direction == 'right'
					&& activeBlockPosition.left + childPosition.left == blockPosition.left - 30 
					&& (activeBlockPosition.top + childPosition.top <= blockPosition.top + 30
						&& activeBlockPosition.top + childPosition.top >= blockPosition.top - 30)){
					finalReturn = true;
				}
				else if(direction == 'left'
					&& activeBlockPosition.left + childPosition.left == blockPosition.left + 30 
					&& (activeBlockPosition.top + childPosition.top <= blockPosition.top + 30
						&& activeBlockPosition.top + childPosition.top >= blockPosition.top - 30)){
					finalReturn = true;
				}
					
			});
		});
		return finalReturn;
	},
	isFull: function($activeBlock){
		var activeBlockPosition = $activeBlock.position();
		if(activeBlockPosition.top <= 0){
			return true;
		}
		return false;
	},
	createActiveBlock: function(){
		var blockType = this.aActiveBlockTypes[Math.floor(Math.random()*5)];
		switch(blockType){
			case 'l':
				this.activeBlock = {width: 60, height: 90, blockType: 'l'}
				this.oActiveBlocks = {
					block0: {top: 0, left: 0},
					block1: {top: 30, left: 0},
					block2: {top: 60, left: 0},
					block3: {top: 60, left: 30}
				};
				break;
			case 'pyramid':
				this.activeBlock = {width: 90, height: 60, blockType: 'pyramid'}
				this.oActiveBlocks = {
					block0: {top: 0, left: 30},
					block1: {top: 30, left: 0},
					block2: {top: 30, left: 30},
					block3: {top: 30, left: 60}
				};
				break;
			case 'square':
				this.activeBlock = {width: 60, height: 60, blockType: 'square'}
				this.oActiveBlocks = {
					block0: {top: 0, left: 0},
					block1: {top: 0, left: 30},
					block2: {top: 30, left: 0},
					block3: {top: 30, left: 30}
				};
				break;
			case 'long':
				this.activeBlock = {width: 30, height: 150, blockType: 'long'}
				this.oActiveBlocks = {
					block0: {top: 0, left: 0},
					block1: {top: 30, left: 0},
					block2: {top: 60, left: 0},
					block3: {top: 90, left: 0},
					block4: {top: 120, left: 0}
				};
				break;
			case 's':
			default:
				this.activeBlock = {width: 90, height: 60, blockType: 's'}
				this.oActiveBlocks = {
					block0: {top: 0, left: 30},
					block1: {top: 0, left: 60},
					block2: {top: 30, left: 0},
					block3: {top: 30, left: 30}
				};
		}
	},
	createBlock: function(block, number){
		var letter = this.aAlphabet[Math.floor(Math.random()*this.aAlphabet.length)],
		$block = $('<div class="bt-block active block'+number+' '+letter+'">').css({
			top: block.top + "px",
			left: block.left + "px"
		}).html(letter.toUpperCase());

		if(letter == "qu"){
			$block.css("textIndent", "-6px");
		}

		return $block;
	},
	rotateBlock: function(){
		var parentBlock = {},
			xOffset = 0,
			bInvert,
			game = this;
		this.rotation = this.rotation == 270 ? 0 : this.rotation + 90;
		bInvert = this.rotation == 0 || this.rotation == 180 ? true : false;
		parentBlock.height = this.$activeBlock.height();
		parentBlock.width = this.$activeBlock.width();
		parentBlock.position = this.$activeBlock.position();
		if(this.rotation == 90 || this.rotation == 270){
			switch(this.$activeBlock.attr("name")){
				case 'l':
					xOffset = -30;
					break;
				case 'pyramid':
				case 'square':
				case 's':
					xOffset = 0;
					break;
				case 'long':
					xOffset = -60;
					break;
				default:
			}
		}
		else {
			switch(this.$activeBlock.attr("name")){
				case 'l':
					xOffset = 30;
					break;
				case 'pyramid':
				case 'square':
				case 's':
					xOffset = 0;
					break;
				case 'long':
					xOffset = 60;
					break;
				default:
			}
		}
		//test collision. Testing height here because it later becomes width.
		if(xOffset + parentBlock.position.left < 0 
			|| xOffset + parentBlock.position.left + parentBlock.height > this.$gameWindow.width()){
			//undo rotation
			this.rotation = this.rotation == 0 ? 270 : this.rotation - 90;
			return false;
		}

		this.$activeBlock.children().each(function(){
			var thisBlockPosition = $(this).position();
			$(this).css({
				'left': '',
				'right': thisBlockPosition.top,
				'top': thisBlockPosition.left
			});
		});
		this.$activeBlock.css({
			'height': parentBlock.width,
			'width': parentBlock.height,
			'left': xOffset + parentBlock.position.left
		});
	},
	moveBlock: function(direction){
		bCollision = this.isBlockingCollision(this.$activeBlock, direction);
		if(bCollision){
			return false;
		}
		switch(direction){
			case 'down':
				var activeBlockPosition = this.$activeBlock.position();
				this.dropBlocks(this.$activeBlock, 1);
				break;
			case 'left':
				this.$activeBlock.css({'left': '-=30'});
				break;
			case 'right':
				this.$activeBlock.css({'left': '+=30'});
				break;
			default:
		}
	},
	unselectBlock: function(){
		this.aSelectedBlockSelectors.splice(this.aSelectedBlockSelectors.length - 1, this.aSelectedBlockSelectors.length);
		if(this.aSelectedBlocks != null){
			this.aSelectedBlocks.splice(this.aSelectedBlocks.length - 1, this.aSelectedBlocks.length);
		}
		this.refreshInput();
		this.chainBlocks();
	},
	selectBlocks: function(letter){
		if(this.bPaused == 'true'){
			return false;
		}
		else {
			this.aSelectedBlockSelectors.push(letter);
			this.refreshInput();
			this.chainBlocks();
		}
	},
	refreshInput: function(){
		var sWord = '';
		for(var i in this.aSelectedBlockSelectors){
			sWord += this.aSelectedBlockSelectors[i];
		}
		this.$gameWrapper.find('input.bt-word').val(sWord);
	},
	chainBlocks: function(){
		var game = this,
			aChainedBlocks,
			bChainSuccess,
			finalReturn,
			oSelectableBlocks = {},
			$first,
			$second,
			aSelectableLocationPairs = [];

		for(var i in this.aSelectedBlockSelectors){
			oSelectableBlocks[i] = new Array();
			$('.'+this.aSelectedBlockSelectors[i]).not('.active').each(function(){
				oSelectableBlocks[i].push($(this));
			});
		}

		if(oSelectableBlocks[0] == undefined){
			game.$gameWindow.find('.bt-block').removeClass('bt-selected');
			game.aSelectedBlocks = [];
			return;
		}

		for(var i in oSelectableBlocks){
			if(i == 0) continue;
			$firsts = oSelectableBlocks[i];
			$seconds = oSelectableBlocks[i - 1];
			aSelectableLocationPairs.push(game.compareBlockLocations($firsts, $seconds));
		}		

		aChainedBlocks = aSelectableLocationPairs;

		function aggregateChain(last, second){
			var mergedArray = null;
			for(var i in last){
				for(var j in second){
					if(last[i] != undefined && last[i][0] == second[j][1] /*&& !second[j][0].is('.bt-selected')*/){
						mergedArray = $.merge([second[j][0]], last[i]);
						break;
					}
				}
				if(mergedArray != null) break;
			}
			return [mergedArray];
		}

		while(aChainedBlocks.length > 1){
			aChainedBlocks[aChainedBlocks.length - 2] = 
				aggregateChain(aChainedBlocks[aChainedBlocks.length - 1], aChainedBlocks[aChainedBlocks.length - 2]);
			aChainedBlocks.splice(aChainedBlocks.length - 1, aChainedBlocks.length);
		}

		game.$gameWindow.find('.bt-block').removeClass('bt-selected');
		game.aSelectedBlocks = [];
		if(aChainedBlocks.length == 0){
			oSelectableBlocks[0][0].addClass('bt-selected');
			game.aSelectedBlocks = [];
		}
		else {
			game.aSelectedBlocks = aChainedBlocks[0][0];
		}
		for(var i in game.aSelectedBlocks){
			game.aSelectedBlocks[i].addClass('bt-selected');
		}
	},
	compareBlockLocations: function(firsts, seconds){
		var finalReturn = [],
			$first,
			$second,
			firstPosition,
			secondPosition;
		for(var i in firsts){
			$first = firsts[i];
			for(var j in seconds){
				$second = seconds[j],
				firstPosition = $first.position(),
				secondPosition = $second.position();
				if(Math.abs(firstPosition.top - secondPosition.top) < 45 
					&& Math.abs(firstPosition.left - secondPosition.left) < 45
					&& !$first.is($second)){
					finalReturn.push([$second, $first]);
				}
			}
		}
		return finalReturn;
	},
	submitWord: function(){
		var word = '',
			game = this,
			scoreInput = game.$gameWrapper.find('input.bt-score')
			wordScore = 0;
		for(var i in this.aSelectedBlocks){
			word += this.aSelectedBlocks[i].text().toLowerCase();
		}
		wordScore = game.getWordScore(word);
		$.ajax({
			url: 'checkword.php',
			data: {
				word: word.toLowerCase()
			},
			success: function(res){
				console.log(res);
				try {
					res = JSON.parse(res);
				}
				catch(e){
					$('body').prepend(res);
				}
				if(res.success){
					game.removeSelected();
					scoreInput.val(parseInt(scoreInput.val(), 10) + wordScore);
					$('.bt-column').append(word+"<br/>");
					$('.bt-selected').removeClass('bt-selected');
				}
			}
		});

		this.aSelectedBlockSelectors = [];
		this.refreshInput();
	},
	getWordScore: function(word){
		var score = 0;
		switch(word.length){
			case 0:
			case 1:
			case 2:
				score = 0;
				break;
			case 3:
			case 4: 
				score = 1;
				break;
			case 5: 
				score = 2;
				break;
			case 6:
				score = 3;
				break;
			case 7:
				score = 5;
			case 8:
			default:
				score = 11;
		}

		return score;
	},
	removeSelected: function(){
		var $selected = $('.bt-selected'),
			aLeftPos = [],
			aTopPos = [],
			aStacked = [],
			selectedPosition = {},
			game = this;

		$selected.each(function(){
			selectedPosition = $(this).position();
			if($.inArray(selectedPosition.left, aLeftPos) == -1){
				aStacked.push(1);
				//these should always be synchronzed
				aLeftPos.push(selectedPosition.left);
				aTopPos.push(selectedPosition.top);
			}
			else {
				aStacked[aStacked.length - 1] += 1; //set up how many to remove per column
			}
		});

		$selected.remove();
		for(var i in aLeftPos){
			$('.bt-block').not('.active').each(function(){
				var thisPos = $(this).position();
				if(thisPos.left == aLeftPos[i] && thisPos.top < aTopPos[i]){
					$(this).animate({'top': '+='+30*aStacked[i]}, 300);
				}
			});
		}
	},
	togglePause: function(){
		if(this.bPaused == 'true'){
			this.bPaused = 'false';
			this.$activeBlock.clearQueue();
			this.dropBlocks(this.$activeBlock, this.defaultSpeed);
		}
		else {
			this.bPaused = 'true';
			this.dropBlocks(this.$activeBlock, 1000000000);
		}
		return true;
	},
	handleKeypress: function(event){
		if(!this.gameOn) return;
		switch(event.keyCode){
			// Tetris bit
			case 38: //up
				this.rotateBlock()
				break;
			case 40: //down
				this.moveBlock('down');
				break;
			case 37: //left
				this.moveBlock('left');
				break;
			case 39: //right
				this.moveBlock('right');
				break;
			default:
		}
		switch(event.which){
			// Letters
			case 97: //a
			case 65:
				this.selectBlocks('a');
				break;
			case 98: //b
			case 66:
				this.selectBlocks('b');
				break;
			case 99: //c
			case 67:
				this.selectBlocks('c');
				break;
			case 100: //d
			case 68:
				this.selectBlocks('d');
				break;
			case 101: //e
			case 69:
				this.selectBlocks('e');
				break;
			case 102: //f
			case 70:
				this.selectBlocks('f');
				break;
			case 103: //g
			case 71:
				this.selectBlocks('g');
				break;
			case 104: //h
			case 72:
				this.selectBlocks('h');
				break;
			case 105: //i
			case 73:
				this.selectBlocks('i');
				break;
			case 106: //j
			case 74:
				this.selectBlocks('j');
				break;
			case 107: //k
			case 75:
				this.selectBlocks('k');
				break;
			case 108: //l
			case 76:
				this.selectBlocks('l');
				break;
			case 109: //m
			case 77:
				this.selectBlocks('m');
				break;
			case 110: //n
			case 78:
				this.selectBlocks('n');
				break;
			case 111: //o
			case 79:
				this.selectBlocks('o');
				break;
			case 112: //p
			case 80:
				this.selectBlocks('p');
				break;
			case 113: //q
			case 81:
				this.selectBlocks('qu');
				break;
			case 114: //r
			case 82:
				this.selectBlocks('r');
				break;
			case 115: //s
			case 83:
				this.selectBlocks('s');
				break;
			case 116: //t
			case 84:
				this.selectBlocks('t');
				break;
			case 117: //u
			case 85:
				if(this.aSelectedBlockSelectors.slice(-1)[0] !== "qu"){
					this.selectBlocks('u');
				}
				break;
			case 118: //v
			case 86:
				this.selectBlocks('v');
				break;
			case 119: //w
			case 87:
				this.selectBlocks('w');
				break;
			case 120: //x
			case 88:
				this.selectBlocks('x');
				break;
			case 121: //y
			case 89:
				this.selectBlocks('y');
				break;
			case 122: //z
			case 90:
				this.selectBlocks('z');
				break;
			//misc
			case 13: //enter
				this.submitWord();
				break;
			case 8: //backspace
				this.unselectBlock();
				break;
			case 92: // backslash
				this.togglePause();
				break;
			default:
				// alert(event.which);
		}
	}
};