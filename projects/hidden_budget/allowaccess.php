<?php
if(isset($_REQUEST['AllowAccessPassword'])){
	session_start();
	$_SESSION['Allowed'] = $_REQUEST['AllowAccessPassword'] == 'jsbudget' ? 1 : 0;
}
header('Location: index.php');
exit;
?>