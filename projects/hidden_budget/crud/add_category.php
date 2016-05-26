<?php
require_once('../../../includes/prepend.php');
require_once('../includes/header.php');

$sCategory = isset($_REQUEST['Category']) ? mysql_real_escape_string($_REQUEST['Category']) : 'uncategorized';

$sQuery = "INSERT INTO jstaab.budget_item_categories (Name) VALUES ('$sCategory')";

$bResult = $oMySql->exec($sQuery);

header("Location: ../index.php");

?>