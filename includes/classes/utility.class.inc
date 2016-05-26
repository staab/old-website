<?php

Class Utility {
	public function ucfirstPhrase($sPhrase, $sDelimiter = " ", $sReplaceDelimiter = null){
		$sReplaceDelimiter = $sReplaceDelimiter ? $sReplaceDelimiter : $sDelimiter;
		$aPhrase = explode($sDelimiter, $sPhrase);
		foreach($aPhrase as $iKey => $sWord){
			$aPhrase[$iKey] = ucfirst($sWord);
		}
		return implode($sReplaceDelimiter, $aPhrase);
	}

	public static function getSingleColumn($aData, $sValue, $sKey = false){
		$aResults = array();
		foreach($aData as $aValues){
			if($sKey){
				$aResults[$aValues[$sKey]] = $aValues[$sValue];
			}
			else {
				$aResults[] = $aValues[$sValue];
			}
		}
		return $aResults;
	}

	public static function getTable($aRows, $aHeadings = array()){
		// Start with the headings
		$sRow = '';
		foreach($aHeadings as $sHeading){
			$sRow .= "<th>".$sHeading."</th>";
		}
		$sTable = "<tr>".$sRow."</tr>";

		// Move on to the rest of the table
		foreach($aRows as $iRow => $aRow){
			$sRow = '';
			foreach($aRow as $sField){
				$sRow .= "<td>".$sField."</td>";
			}
			$sTable .= "<tr value='$iRow'>".$sRow."</tr>";
		}
		$sTable = "<table>".$sTable."</table>";
		return $sTable;
	}
}

?>