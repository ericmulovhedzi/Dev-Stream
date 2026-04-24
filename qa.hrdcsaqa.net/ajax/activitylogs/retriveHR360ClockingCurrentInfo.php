<?
set_time_limit(0);
ini_set('memory_limit','-1');
//error_reporting(E_ALL);
 
require_once('../../inc/connection.php');

$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_employee']) && ($_GET['_employee'] >= 1) && isset($_GET['_clockin_date']) && (!empty($_GET['_clockin_date'])))
{
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$rs_w =$db->Execute("SELECT `A`.`clockin_status`,`A`.`signed_in_time`,`A`.`signed_out_time`,`A`.`date` FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_clockin` AS `A` WHERE `A`.`employee`='".$_GET['_employee']."' AND TO_DAYS(`A`.date) = TO_DAYS('".$_GET['_clockin_date']."') AND `A`.pub=1 AND `A`.del=1 ORDER BY `A`.date DESC LIMIT 1");
	if(($rs_w) && ($rs_w->numRows() >= 1)){
		$_DATE = ($rs_w->fields['clockin_status'] == "IN") ? $rs_w->fields['signed_in_time'] : $rs_w->fields['signed_out_time'];
		$balance = $rs_w->fields['clockin_status']." at ".$_DATE." on ".substr($rs_w->fields['date'],0,11);
	}
	else{$balance = "N/A";}
	
	echo $balance;
	
	
}
else{echo "N/A";}
?>