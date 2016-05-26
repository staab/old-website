<?php
require_once('../../../includes/prepend.php');
require_once('../includes/header.php');

$iItemId = isset($_REQUEST['itemId']) ? (float) ($_REQUEST['itemId']) : 0;
$fAmount = isset($_REQUEST['amount']) ? (float) ($_REQUEST['amount']) : 0;
$iCategoryId = isset($_REQUEST['category']) ? (int) ($_REQUEST['category']) : '0';
$sNote = isset($_REQUEST['note']) ? mysql_real_escape_string($_REQUEST['note']) : '';
$sDate = isset($_REQUEST['date']) ? mysql_real_escape_string($_REQUEST['date']) : '';

$sQuery = "UPDATE jstaab.budget_items 
	SET Amount = $fAmount, CategoryId = $iCategoryId, Note = '$sNote', `Date` = '$sDate'
	WHERE ItemId = $iItemId";
	
echo json_encode(array('success' => $oMySql->exec($sQuery), 'error' => null));

?>