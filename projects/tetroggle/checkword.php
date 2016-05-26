<?php

$sWord = isset($_REQUEST['word']) ? $_REQUEST['word'] : null;

$sCurrentDir = str_replace('checkword.php', '', __FILE__);
$sDictionary = file_get_contents($sCurrentDir."wordlist.txt");

echo json_encode(array(
	'success' => strpos($sDictionary, "\r\n$sWord\r\n") !== false
));
?>