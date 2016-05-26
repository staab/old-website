<?php

$aComicData = array(
	"pearlsbeforeswine" => array(
		"DateFormat" => "Y/m/d",
		"BaseUrl" => "http://www.gocomics.com/pearlsbeforeswine/",
		"ImgUrl" => "http://www.gocomics.com/pearlsbeforeswine/",
		"Regex" => "|<img[^>]+class=\"strip\" src=\"([^>]+)\"[^>]+>|U"
	),
	"dilbert" => array(
		"DateFormat" => "Y-m-d",
		"BaseUrl" => "http://www.dilbert.com/strips/comic/",
		"ImgUrl" => "http://www.dilbert.com",
		"Regex" => "|<img[^>]+src=\"(/dyn/str_strip/[^>]+\.strip\.gif)\"[^>]+>|U"
	)
);

$sDate = isset($_REQUEST['ComicDate']) ? $_REQUEST['ComicDate'] : '';
$sComic = isset($_REQUEST['Comic']) ? $_REQUEST['Comic'] : '';

// Format the date to match the url
$oDate = new DateTime($sDate);
$sDate = $oDate->format($aComicData[$sComic]['DateFormat']);

$sBaseUrl = $aComicData[$sComic]['BaseUrl'];
$sUrl = $sBaseUrl.$sDate;
// Get the contents of the page
$sContents = file_get_contents($sUrl);
// Match the tag that has the strip
$sRegEx = $aComicData[$sComic]['Regex'];
preg_match_all($sRegEx, $sContents, $aComicImg);

$sSrc = isset($aComicImg[1][0]) ? $aComicImg[1][0] : '';
if(strpos($sSrc, "http") === false){
	$sSrc = $aComicData[$sComic]["ImgUrl"].$sSrc;
}

//Now get the date for the next comic in standard format. I'm doing it here because php has better date functions than js.
$oDate->add(new DateInterval('P1D'));
$sDate = $oDate->format('Y/m/d');

echo json_encode(array("date" => $sDate, "img" => $sSrc));

?>