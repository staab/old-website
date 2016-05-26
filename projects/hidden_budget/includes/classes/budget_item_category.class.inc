<?php

Class budgetItemCategory {

	private $sName;

	public function __construct($iCategoryId){
		global $oMySql;
		$sQuery = "SELECT * 
			FROM jstaab.budget_item_categories
			WHERE CategoryId = $iCategoryId";
		$aData = $oMySql->getArray($sQuery);
		$aData = $aData[0];
		$this->sName = $aData['Name'];
	}

	public function getName(){
		return $this->sName;
	}

	public static function getAll(){
		global $oMySql;
		$sQuery = "SELECT * FROM jstaab.budget_item_categories";
		return $oMySql->getArray($sQuery);
	}

	public static function getReportByMonth(/*$iYear*/){
		global $oMySql;
		$sQuery = "SELECT CategoryId, MONTH(`Date`) AS Month, SUM(amount) AS Amount
			FROM jstaab.budget_items
			GROUP BY CategoryId, MONTH(`Date`)";
		return $oMySql->getArray($sQuery);
	}
}

?>