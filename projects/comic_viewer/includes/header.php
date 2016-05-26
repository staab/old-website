<?php
define("COMIC_DIRECTORY", DOCUMENT_ROOT."projects/comic_viewer/");

// function ComicClassAutoload($sClass){
// 	$sClass = strtolower($sClass);
// 	$aClass = array();

//     $sIncludeFile = 'budget_item.class.inc';
//     $aClass['budgetitem'] = $sIncludeFile;

//     $sIncludeFile = 'budget_item_category.class.inc';
//     $aClass['budgetitemcategory'] = $sIncludeFile;
    
//     // finally, include or require the class, if we found it
//     if(isset($aClass[$sClass])){
// 		require_once COMIC_DIRECTORY."includes/classes/".$aClass[$sClass];
//     }
// }
// spl_autoload_register('ComicClassAutoload');

$oMySql = new MySql(); // this is necessary to select the correct database for the project.
?>