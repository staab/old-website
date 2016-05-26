<?php
require_once('../../../includes/prepend.php');
require_once('../includes/header.php');

$fAmount = isset($_REQUEST['Amount']) ? (float) ($_REQUEST['Amount']) : 0;
$iCategory = isset($_REQUEST['Category']) ? (int) ($_REQUEST['Category']) : '0';
$sNote = isset($_REQUEST['Note']) ? mysql_real_escape_string($_REQUEST['Note']) : '';

$sQuery = "INSERT INTO jstaab.budget_items (Amount, CategoryId, Note, `Date`)
	VALUES ($fAmount, '$iCategory', '$sNote', NOW())";

$oMySql->exec($sQuery);

header("Location: ../index.php");

?>