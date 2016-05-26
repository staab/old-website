<?php
if(isset($_REQUEST['AllowAccessPassword'])){
	session_start();
	$_SESSION['Allowed'] = $_REQUEST['AllowAccessPassword'] == 'vg12' ? 1 : 0;
}

header('Location: index.php');
exit;

?>