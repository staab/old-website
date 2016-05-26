<?php

Class MySql {

	public function __construct(){
		// $con = mysql_connect("jstaab.db.9174083.hostedresource.com", "jstaab", "F0ambr1cks");
		$con = mysql_connect("localhost", "root", "");
		if(!$con){
			die("could not connect to mysql database" . mysql_error());
		};
		mysql_select_db("jstaab");
	}

	public function exec($sQuery){
		$rResult = mysql_query($sQuery);
		return $rResult;
	}

	public function getArray($sQuery){
		$rResult = mysql_query($sQuery);
		$aResults = array();
		while($aRow = mysql_fetch_array($rResult)){
			foreach($aRow as $key => $value){
				if(is_numeric($key)){
					unset($aRow[$key]);
				}
			}
			$aResults[] = $aRow;
		}
		return $aResults;
	}
}

?>