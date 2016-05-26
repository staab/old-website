<?php
session_name('VotersGuide');
session_start();
?>

<html>
	<head>
		<link rel="stylesheet" href="css/stylesheet.css" />
		<link rel="stylesheet" href="css/lightbox.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/lightbox.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				openLightbox("main_login");
			});
		</script>
	</head>
	<body>
		<div class="lightbox-backdrop"></div>
		<div class="lightbox-wrapper">
			<div id="main_login" class="lightbox">
				<h1>This is a restricted area. Please enter the password to continue.</h1>
				<form action="allowaccess.php">
					<label>Password</label>
					<input type="password" name="AllowAccessPassword"></input>
					<input type="submit" value="Submit"></input>
				</form>
			</div>
		</div>
	</body>
</html>