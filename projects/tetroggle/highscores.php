<?php

$iScore = isset($_REQUEST['score']) ? $_REQUEST['score'] : null;
$sName = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;

if($iScore == null){
	echo json_encode(array('success' => 'false', 'error' => 'No Score came through.'));
	die();
}
if($sName == null){
	echo json_encode(array('success' => 'false', 'error' => 'No Name came through.'));
	die();
}

// $con = mysql_connect("jstaab.db.9174083.hostedresource.com", "jstaab", "F0ambr1cks");
$con = mysql_connect("localhost", "root", "");
// mysql_select_db('jstaab');
mysql_select_db('boggle_tetris');

$sQuery = "INSERT INTO bt_high_scores (Name, Score)
	VALUES ('$sName', $iScore);";
$bSuccess = mysql_query($sQuery);
if(!$bSuccess){
	echo json_encode(array('success' => 'false', 'error' => 'The insert failed.'));
	die();
}

$sQuery = "SELECT * FROM bt_high_scores 
	ORDER BY Score DESC
	LIMIT 10;";
$rResult = mysql_query($sQuery);
$i = 0;
$sResults = '<table>
		<tr>
			<th></th>
			<th>Name</th>
			<th>Score</th>
		</tr>';
while($aRow = mysql_fetch_array($rResult, MYSQL_ASSOC)){
	$i++;
	$sResults .= "<tr>
			<td>$i.</td>
			<td>{$aRow['Name']}</td>
			<td>{$aRow['Score']}</td>
		</tr>";
}
$sResults .= '</table>';

echo json_encode($sResults);

?>