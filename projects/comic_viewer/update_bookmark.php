<?php

$sName = isset($_REQUEST['Name']) ? $_REQUEST['Name'] : '';
$sComic = isset($_REQUEST['Comic']) ? $_REQUEST['Comic'] : '';
$sDate = isset($_REQUEST['ComicDate']) ? $_REQUEST['ComicDate'] : '';
$bRewind = isset($_REQUEST['Rewind']) ? $_REQUEST['Rewind'] : '';

//Keep the bookmarks a couple days before the last loaded, so we don't miss any
$oDate = new DateTime($sDate);
if($bRewind){
	$oInterval = new DateInterval('P20D');
}
else if($sComic == 'dilbert'){
	$oInterval = new DateInterval('P11D');	
}
else {
	$oInterval = new DateInterval('P7D');
}
$oDate->sub($oInterval);
$sDate = $oDate->format('Y/m/d');

$sJson = file_get_contents("./start_dates.csv", "r");
$oData = json_decode($sJson);

$oData->$sName->$sComic = $sDate;

file_put_contents("./start_dates.csv", json_encode($oData));

?>