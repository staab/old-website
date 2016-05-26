<div class="content hidden" id="candidates_div">
	<a nohref="home_div">&#60 Back to Home</a>
	<a href="candidateslist.php" class="top-link hidden">Candidates List &#62</a>
	<h2>Offices</h2>
	<div class="info-block">
		<img src="images/positions1.png" id="positions_img" class="positions offices hidden"/>
		<?php
			echo Office::createOfficeList();
		?>
	</div>
	<div class="info-pop-block" id="candidates">
		<p>
			To find out more about the candidates for a particular office, click on one of the titles
			to the left. Otherwise, you can browse a list of candidates <a href="candidateslist.php">here</a>.
		</p>
	</div>
</div>