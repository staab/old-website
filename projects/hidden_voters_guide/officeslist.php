<?php
include('includes/header.php');
?>
<div class="content" id="candidates_div">
	<a href="<?php echo VG_ROOT ?>">&#60 Back to Home</a>
	<a href="candidateslist.php" class="top-link hidden">Candidates List &#62</a>
	<h2>Offices</h2>
	<div class="info-block">
		<img src="images/positions2.png" id="positions_img" class="positions offices hidden"/>
		<?php
			echo Office::createOfficeList();
		?>
	</div>
	<div class="info-pop-block" id="candidates">
		<p>
			To find out more about the candidates for a particular office, click on one of the titles
			to the left. Otherwise, you can browse a list of candidates <a href="candidateslist.php">here.</a>
		</p>
	</div>
</div>
<?php
require_once(VG_ROOT.'includes/footer.php');
?>