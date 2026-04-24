<?
set_time_limit(0);
ini_set('memory_limit','-1');
//error_reporting(E_ALL);
 
require_once('../../inc/connection.php');

$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_customer']) && ($_GET['_customer'] >= 1))
{
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$rs_w =$db->Execute("SELECT `A`.`balance` FROM `_school_".$_SESSION['accesses']->_login['school'][0]."_students` AS `A` WHERE `A`.`id`='".$_GET['_customer']."' AND `A`.pub=1 AND `A`.del=1 LIMIT 1");
	$balance = (($rs_w) && ($rs_w->numRows() >= 1)) ? $rs_w->fields['balance'] : "0.00";
	
	echo $balance;
	
	
}
else{echo "0.00";}
?>