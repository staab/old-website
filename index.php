<?php
ini_set('error_reporting', E_ALL && E_STRICT);
ini_set('display_errors', 'stdout');
define('ROOT', str_replace(__FILE__, 'index.php'));
require_once(ROOT.'includes/prepend.php');
require_once(ROOT.'includes/header.php');
// Beginning html gets prepended here
$aProjectsDirectory = scandir(PROJECTS_DIRECTORY);
// Eliminate everything in here that isn't a directory
unset($aProjectsDirectory[0]); // Current directory string
unset($aProjectsDirectory[1]); // Parent directory string
foreach($aProjectsDirectory as $iKey => $sProject){
	if(is_dir(PROJECTS_DIRECTORY.$sProject) && strpos($sProject, "hidden_") !== 0){
		// Create an array of project names keyed to location for link
		$aProjects["projects/".$sProject] = $oUtil->ucfirstPhrase($sProject, "_", " ");
	}
}
?>
		<link rel="stylesheet" type="text/css" href="includes/css/base.css" />
		<script type="text/javascript" src="includes/js/scripts.js"></script>
		<script type="text/javascript" src="includes/js/canvas.js"></script>
		<script type="text/javascript">
		</script>
	</head>
	<body>
		<canvas id="background">
		</canvas>
		<div class="wrapper">
			<div id="header">
				<h1 id="page_top_header">Jstaab.com</h1>
			</div>
			<div class="content-block">
				<p>
					Welcome! Not too much to say; my name is Jon Staab, I'm a professional web developer for 
					<a href="http://www.economicmodeling.com" target="_blank">Economic Modeling Specialists Inc.</a>, and I love my job so much I do it on
					weekends. This site is dedicated, for the time being, to projects I do in my free time. You can see a list of those
					down below, if you're interested.
				</p>
				<p>
					Outside of that, if you want to see a little of my work, you can try <a href="https://usdemo.emsicareercoach.com" target="_blank">Career Coach</a>,
					the web app I work on at EMSI, or <a href="http://www.graceagenda.com" target="_blank">The Grace Agenda</a>, which I'll be taking on this coming year.
				</p>
				<p>
					I'm not currently accepting work, since I'm quite happy with my job, I'm enjoying being a recent graduate
					from <a href="http://www.nsa.edu" target="_blank">New Saint Andrews College</a> and I'm more interested in using my free time
					to learn new programming languages. Currently I work in html, css, javascript, php, mysql, and, recently,
					with bash scripting.
				</p>
			</div>
			<div class="content-block thumb-nav">
				<h2>Projects</h2>
				<ul>
					<?php
					foreach($aProjects as $sDirectory => $sProjectName){
						?>
							<li><a href="<?=$sDirectory?>" class="ellipsis"><span><?=$sProjectName?></span><canvas class="project-thumbnail" id="<?=$sProjectName?>"></canvas></a></li>
						<?php
					}
					?>
				</ul>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="footer">
			<span>&copy; Jonathan Staab <?=date('Y')?></span>
			<span class="right"><a href="mailto:info@jstaab.com">Contact</a></span>
		</div>
		<script type="text/javascript" src="includes/js/domready.js"></script>
	</body>
</html>
