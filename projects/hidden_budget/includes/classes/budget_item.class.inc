<?php

Class budgetItem {
	public function __construct(){

	}

	public static function getAll($aParams){
		$oMySql = new MySql();

		$aWhere = array();
		if(isset($aParams['Month']) && $aParams['Month']){
			$aWhere[] = 'MONTH(`Date`) = '.$aParams['Month'];
		}
		if(isset($aParams['Year']) && $aParams['Year']){
			$aWhere[] = 'YEAR(`Date`) = '.$aParams['Year'];
		}
		$sWhere = count($aWhere) > 0 ? 'WHERE '.implode(" AND ", $aWhere) : '';

		$sQuery = "SELECT ItemId, Amount, c.Name as Category, Note, `Date`
			FROM jstaab.budget_items i
			LEFT JOIN jstaab.budget_item_categories c ON i.CategoryId = c.CategoryId
			$sWhere";
			
		return $oMySql->getArray($sQuery);
	}
}

?>