<?php

class Stats {

	public $strName;
	public $iLevel;
	public $iMonstersKilled;
	public $iTreasuresGotted;
	public $iLuckinessRating;

	function buildDropDown($strName, $iLevel, $iMonstersKilled, $iTreasuresGotted, $iLuckinessRating, $iScore){
		
		$result = mysql_query("SELECT * FROM jstaab.monster_mash_stats
			ORDER BY Score DESC LIMIT 4");

		$list = "<div id='stats-list'><h1>Game Over!</h1><h3>Top Scores</h3>";

		while($row = mysql_fetch_array($result)){
			$list .= "
				<span class='list_header'><h4>".$row['HeroName']."</h4> (Level ".$row['Level'].")</span>
				<span class='list_score'>Score: ".$row['Score']."</span>
				<ul>
					<li>Monsters Killed: ".$row['MonstersKilled']."</li>
					<li>Treasures Gotted: ".$row['TreasuresGotted']."</li>
					<li>Luckiness: ".$row['Luck']."</li>
				</ul>
			";
		}

		$list .= "</div>";

		return $list;
	}

}

?>