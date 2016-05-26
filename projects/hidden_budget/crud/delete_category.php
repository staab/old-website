<?php
require_once('../../../includes/prepend.php');
require_once('../includes/header.php');

$iCategoryId = isset($_REQUEST['CategoryId']) ? (int) ($_REQUEST['CategoryId']) : 0;

$sQuery = "DELETE FROM jstaab.budget_item_categories WHERE CategoryId = $iCategoryId";

$oMySql->exec($sQuery);

header("Location: ../index.php");

?>