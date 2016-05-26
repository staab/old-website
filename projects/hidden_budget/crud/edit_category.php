<?php
require_once('../../../includes/prepend.php');
require_once('../includes/header.php');

$iCategoryId = isset($_REQUEST['categoryId']) ? (int) ($_REQUEST['categoryId']) : '0';
$sName = isset($_REQUEST['name']) ? mysql_real_escape_string($_REQUEST['name']) : '';
$fAmount = isset($_REQUEST['amount']) ? (float) ($_REQUEST['amount']) : 0;

$sQuery = "UPDATE jstaab.budget_item_categories 
	SET Name = '$sName', BudgetedAmount = $fAmount
	WHERE CategoryId = $iCategoryId";
	
echo json_encode(array('success' => $oMySql->exec($sQuery), 'error' => null));

?>