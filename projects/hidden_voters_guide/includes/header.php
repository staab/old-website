<?php
require_once('../../includes/prepend.php');
require_once('../../includes/prepend.php');require_once('../../includes/header.php');

define("VG_ROOT", PROJECTS_DIRECTORY."voters_guide/");
define("VG_HOST", HOST."projects/voters_guide/");

session_start();
if(!isset($_SESSION['Allowed'])){
	header('Location: allowaccesslogin.php');
}
else if($_SESSION['Allowed'] == 0){
	header('Location: allowaccesslogin.php');
}
else if($_SESSION['Allowed'] == 1){

}

require_once(VG_ROOT.'includes/classes/candidates.class.inc');


?>

<html>
	<head>
		<title>The Voter's Guide</title>
		<link rel="icon" type="image/x-icon" href="images/check.ico" />
		<link rel="stylesheet" href="css/stylesheet.css" />
		<link rel="stylesheet" href="css/lightbox.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/lightbox.js"></script>
		<script type="text/javascript">
			function movePositions(){
				var $pos = $('#positions_img'),
					posBottom = $pos.position().top + 80,
					divTop = $('h3.open').first().next().position().top;
				if(posBottom !== divTop){
					setTimeout(function(){
						if($('h3.open').first().next().position().top == null){
							divTop = 100;
						}
						else {
							divTop = $('h3.open').first().next().position().top;
						}
						$pos.animate({"top": divTop - 80}, 500);
					}, 1000);
				}
			}

			$(document).ready(function(){
				$('.nav li').hover(function(){
                    document.body.style.cursor = "pointer";
                }, function(){
                    document.body.style.cursor = "default";
                });

                $('a').hover(function(){
                    document.body.style.cursor = "pointer";
                }, function(){
                    document.body.style.cursor = "default";
                });

                $('.a').hover(function(){
                    document.body.style.cursor = "pointer";
                }, function(){
                    document.body.style.cursor = "default";
                });

				$('.candidates-list').on("click", "h3.closed", function(){
					var id = $(this).text(),
						$div = $('div.#'+id);
					$(this).removeClass("closed").addClass("open");
					$(this).find('img').removeClass('unrotate90').addClass('rotate90');
					$('.info-pop-block').animate({opacity: 0}, 500, function(){
						$(this).addClass('hidden').removeClass('info-pop-block');
					});
					var posHeight = $('.positions.offices.hidden').height();
					$('.positions.offices.hidden').css("height", 0).removeClass('hidden').animate({'height': posHeight}, 500);
					$('.top-link').removeClass('hidden');
					$div.removeClass("hidden");

					$div.css("height", 'auto');
					var height = $div.height();
					$div.css("height", "0px");
					$div.animate({'height': height}, 1000);
					movePositions();

				});
				$('.candidates-list').on("click", "h3.open", function(){
					var id = $(this).text(),
						$div = $('div.#'+id);
					$(this).removeClass("open").addClass("closed");
					$(this).find('img').removeClass('rotate90').addClass('unrotate90');
					$div.animate({'height': '0px'}, 1000, function(){
						$div.addClass("hidden");
						$div.css("height", 'auto');
					});
					movePositions();
				});

				$('.detailed-candidates-list').on("click", "h3.closed", function(){
					var id = $(this).text().split(' '),
						$div = $('#'+id[0]);
					$(this).removeClass("closed").addClass("open");
					$(this).find('img').removeClass('unrotate90').addClass('rotate90');
					$('.info-pop-block').animate({opacity: 0}, 500, function(){
						$(this).addClass('hidden').removeClass('info-pop-block');
					});

					$div.removeClass("hidden");

					$div.css("height", 'auto');
					var height = $div.height();
					$div.css("height", "0px");
					$div.animate({'height': height}, 1000);

				});
				$('.detailed-candidates-list').on("click", "h3.open", function(){
					var id = $(this).text().split(' '),
						$div = $('#'+id[0]);
					$(this).removeClass("open").addClass("closed");
					$(this).find('img').removeClass('rotate90').addClass('unrotate90');
					$div.animate({'height': '0px', "paddingTop": 0, "paddingBottom": 0, "marginTop": 0, "marginBottom": 0}, 1000, function(){
						$div.addClass("hidden");
						$div.css("height", 'auto').css('paddingTop', '10px').css("paddingBottom", "10px").css("marginTop", "10px").css("marginBottom", "30px");
					});
				});

			});
		</script>
	</head>
	<body>
		<img src="images/draft.png" id="draft" width="200px" height="175px" />
		<div class="lightbox-backdrop hidden"></div>
		<div class="wrapper">
			<a href="<?php echo VG_ROOT?>"><div class="banner"></div></a>
			<ul class="nav">
				<li><a href="positions.php">Our Positions</a></li>
				<li><a href="officeslist.php">The Races</a></li>
				<li><a href="candidateslist.php">The Candidates</a></li>
			</ul>
