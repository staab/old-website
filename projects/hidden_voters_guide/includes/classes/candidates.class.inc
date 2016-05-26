<?php

class Office {
	private $sOffice;
	private $sRecommendation;
	private $sComment;
	private $aPositions = array( //later, grab these from db.
			'GovAbortion' => '',
			'HumanLife' => '',
			'HomoRights' => '',
			'ERA' => '',
			'Private Credit' => ''
		);

	public function __construct(){
		//get $aPositions from db
	}

	public function getOffice(){
		return $this->sOffice;
	}

	public function getRecommendation(){
		return $this->sRecommendation;
	}

	public function getComment(){
		return $this->sComment;
	}

	public function getPositions(){
		return $this->sPositions;
	}

	public static function getOffices(){
		//get Offices from db. For now...
		$aOffices = array(
				'President' => '',
				'USSenate' => '',
				'USCongress10' => '',
				'USCongress12' => '',
				'USCongress13' => '',
				'StateSenate11' => '',
				'StateAssembly22' => ''
			);

		return $aOffices;
	}

	public static function createOfficeList(){
		$sList = '<div class="candidates-list">';
		$aOffices = Office::getOffices();
		foreach($aOffices as $key => $value){
			$sList .= '<h3 class="a closed"><img src="images/arrow.png" class="unrotate90" />'.$key.'</h3><div class="office-item hidden" id="'.$key.'">';
			$sList .= Candidate::createCandidateList($key);
			$sList .= '<div class="recommendation">
							<b>Our Recommendation: Greg Morris</b>
							<p>Greg Morris is clearly the stand-up candidate in this race. 
							Lorem ipsum dolore elit sint fugiat non laboris veniam quis. 
							Lorem ipsum cillum anim do adipisicing sunt reprehenderit ea cupidatat 
							consequat anim proident ut dolor sunt. </p>
						</div>
				</div>';
		}
		$sList .= '<div class="clearfix"></div></div>';

		return $sList;
	}
}

class Candidate extends Office {
	private $iId;
	private $sName;
	private $sParty;

	public function getId(){
		return $this->Id;
	}

	public function getName(){
		return $this->Name;
	}

	public function getParty(){
		return $this->Party;
	}

	public static function getCandidates($sOffice = 0){
		//db call... This'll have to be a multidimensional array with the candidates positions.
		$aCandidates = array(
				'Byron D. Sher' => '',
				'Charles B. Olson' => '',
				'Greg Morris' => '',
				'Victor R. Garza' => ''
			);

		return $aCandidates;
	}

	public static function createCandidateList($sOffice = 0){
		$sList = '<div><ul>';
		$aCandidates = Candidate::getCandidates($sOffice);
		foreach($aCandidates as $key => $value){
			$sList .= '<li class="candidate-item">
						<div>'.$key.'</div>
						<div>Yes</div>
						<div>No</div>
						<div>Yes</div>
						<div>No</div>
						<div>Yes</div>
						<div>No</div>
						<div>Yes</div>
						<div>No</div>
						<div>Yes</div>
					</li>';
		}
		$sList .= '</ul></div>';
		return $sList;
	}

	public static function createDetailedCandidateList($sOffice = 0){
		$aCandidates = Candidate::getCandidates($sOffice);
		$sList = '<div class="detailed-candidates-list">';
		foreach($aCandidates as $key => $value){
			$keyOne = explode(' ', $key);
			$sList .= '<h3 class="a closed"><img src="images/arrow.png" class="unrotate90" />'.$key.'</h3>
						<h5>Party</h5>
						<div class="candidate-details hidden" id="'.$keyOne[0].'" />
							<dl class="positions">
								<dt>Position: </dt><dd>Yes</dd><br />
								<dt>Position: </dt><dd>No</dd><br />
								<dt>Position: </dt><dd>Yes</dd><br />
								<dt>Position: </dt><dd>No</dd><br />
								<dt>Position: </dt><dd>Yes</dd><br />
								<dt>Position: </dt><dd>Yes</dd><br />
							</dl>
							<h4>Comments</h4>
							<p>
								This candidate... Lorem ipsum dolor Excepteur officia Excepteur tempor ut Ut ut in in magna qui nostrud. Lorem ipsum mollit dolore magna ullamco anim do enim laboris dolore amet nostrud sunt quis. Lorem ipsum laboris dolor dolore dolor et consectetur. You can find his voting record <a href="#">here</a>
							</p>
						</div>';
		}
		$sList .= '</div>';
		return $sList;
	}
}

?>