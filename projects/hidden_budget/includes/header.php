<?php
define("BUDGET_CLASSES_DIRECTORY", DOCUMENT_ROOT."projects/hidden_budget/includes/classes/");

function BudgetClassAutoload($sClass){
	$sClass = strtolower($sClass);
	$aClass = array();

    $sIncludeFile = 'budget_item.class.inc';
    $aClass['budgetitem'] = $sIncludeFile;

    $sIncludeFile = 'budget_item_category.class.inc';
    $aClass['budgetitemcategory'] = $sIncludeFile;
    
    // finally, include or require the class, if we found it
    if(isset($aClass[$sClass])){
		require_once BUDGET_CLASSES_DIRECTORY.$aClass[$sClass];
    }
}
spl_autoload_register('BudgetClassAutoload');

session_start();
if(!isset($_SESSION['Allowed'])){
	header('Location: allowaccesslogin.php');
}
else if($_SESSION['Allowed'] == 0){
	header('Location: allowaccesslogin.php');
}
else if($_SESSION['Allowed'] == 1){

}

$oMySql = new MySql("jstaabbudget"); // this is necessary to select the correct database for the project.
?>