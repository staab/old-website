<?php
require_once('../../../includes/prepend.php');
require_once('../includes/header.php');

$iItemId = isset($_REQUEST['ItemId']) ? (int) ($_REQUEST['ItemId']) : 0;

$sQuery = "DELETE FROM jstaab.budget_items WHERE ItemId = $iItemId";

$oMySql->exec($sQuery);

header("Location: ../index.php");

?>