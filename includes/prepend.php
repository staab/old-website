<?php
// Only put stuff in here that we want in every php page in the whole site.
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']."jstaab/");
// define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']."/");
define("PROJECTS_DIRECTORY", DOCUMENT_ROOT."projects/");
define("CLASSES_DIRECTORY", DOCUMENT_ROOT."includes/classes/");
define("HOST", "http://".$_SERVER['HTTP_HOST']."/Jstaab/");
// define("HOST", "http://".$_SERVER['HTTP_HOST']."/");

function GlobalClassAutoload($sClass){
	$sClass = strtolower($sClass);
	$aClass = array();

    $sIncludeFile = 'utility.class.inc';
    $aClass['utility'] = $sIncludeFile;

    $sIncludeFile = 'inflect.class.inc';
    $aClass['inflect'] = $sIncludeFile;

    $sIncludeFile = 'mysql.class.inc';
    $aClass['mysql'] = $sIncludeFile;
    
    // finally, include or require the class, if we found it
    if(isset($aClass[$sClass])){
		require_once(CLASSES_DIRECTORY.$aClass[$sClass]);
    }
}
spl_autoload_register('GlobalClassAutoload');

$oUtil = new Utility();
$oMySql = new MySql();

?>