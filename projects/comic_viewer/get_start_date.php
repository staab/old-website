<?php

$sName = isset($_REQUEST['Name']) ? $_REQUEST['Name'] : '';
$sComic = isset($_REQUEST['Comic']) ? $_REQUEST['Comic'] : '';

$sJson = file_get_contents("./start_dates.csv", "r");
$oData = json_decode($sJson);

if(isset($oData->$sName) && isset($oData->$sName->$sComic)){
	echo $oData->$sName->$sComic;
}
else {
	echo $oData->Really_Anonymous->$sComic;
}

?>