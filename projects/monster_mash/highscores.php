<?php 
include('classes.php');

$con = mysql_connect("jstaab.db.9174083.hostedresource.com", "jstaab", "F0ambr1cks");
if(!$con){
	die("could not connect to mysql database" . mysql_error());
};
mysql_select_db("jstaab");

$strName = mysql_real_escape_string($_REQUEST['name']);
$iLevel = mysql_real_escape_string($_REQUEST['level']);
$iMonstersKilled = mysql_real_escape_string($_REQUEST['monsterskilled']);
$iTreasuresGotted = mysql_real_escape_string($_REQUEST['treasuresgotted']);
$iLuckinessRating = mysql_real_escape_string($_REQUEST['luckinessrating']);

$iScore = $iLuckinessRating*$iLevel*$iLevel+$iMonstersKilled+$iTreasuresGotted;

mysql_query("INSERT INTO jstaab.monster_mash_stats 
	(HeroName, Level, MonstersKilled, TreasuresGotted, Luck, Score)
	VALUES ('$strName', '$iLevel', '$iMonstersKilled', '$iTreasuresGotted', '$iLuckinessRating', '$iScore')")
	or die(mysql_error());

$oStats = new Stats();
$response = $oStats->buildDropDown($strName, $iLevel, $iMonstersKilled, $iTreasuresGotted, $iLuckinessRating, $iScore);

echo $response;

?>