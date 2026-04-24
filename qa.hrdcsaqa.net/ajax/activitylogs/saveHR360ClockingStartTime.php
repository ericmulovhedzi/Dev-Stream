<?
set_time_limit(0);
ini_set('memory_limit','-1');
//error_reporting(E_ALL);
 
require_once('../../inc/connection.php');

$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_clockin_id']) && ($_GET['_clockin_id'] >= 1) && isset($_GET['_clockin_time']) && (!empty($_GET['_clockin_time'])))
{
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	if($db->Execute("UPDATE `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_clockin` SET `signed_in_time`='".$_GET['_clockin_time']."' WHERE `id`='".$_GET['_clockin_id']."' LIMIT 1"))
	{
		$balance = $_GET['_clockin_time'];
	}
	else{$balance = "--:--";}
	
	echo $balance;
	
	
}
else{echo "N/A";}
?>