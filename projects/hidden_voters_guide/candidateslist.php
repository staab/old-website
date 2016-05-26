<?php
require_once('includes/header.php');
?>
<div class="content" id="candidates_div">
	<a href="index.php">&#60 Back to Home</a>
	<h2>Candidates</h2>
	<div class="info-block">
		<?php
			echo Candidate::createDetailedCandidateList();
		?>
	</div>
	<div class="info-pop-block" id="candidates">
		<p>
			To find out more about any of the candidates, click on the name. 
			Otherwise, you can browse a list of Offices <a href="<?=VG_HOST?>officeslist.php">here.</a>
		</p>
	</div>
</div>
<?php
require_once(VG_ROOT.'/includes/footer.php');
?>