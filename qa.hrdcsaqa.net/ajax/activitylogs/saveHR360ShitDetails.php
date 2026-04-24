<?
set_time_limit(0);
ini_set('memory_limit','-1');
//error_reporting(E_ALL);
 
require_once('../../inc/connection.php');

$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;$status=0;

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_employee']) && ($_GET['_employee'] >= 1) && isset($_GET['_shift_date']) && (!empty($_GET['_shift_date'])))
{
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$__rs = $db->Execute("SELECT `A`.`id` FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_shift_schedule` AS `A` 
	WHERE `A`.employee=".$_GET['_employee']." AND TO_DAYS(`A`.`shift_date`) = TO_DAYS('".$_GET['_shift_date']."') LIMIT 1;");

	if(($__rs) && ($__rs->_numOfRows >= 1))
	{
		if($db->Execute("UPDATE `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_shift_schedule` 
			     SET `shift_type`='".$_GET['_shift_type']."', `rotation`='".$_GET['_rotation']."',
			     `week_of_year`='".$_GET['_week_of_year']."',
			     `start_date`='".$_GET['_start_date']."', `start_time`='".$_GET['_start_time']."',
			     `end_date`='".$_GET['_end_date']."', `end_time`='".$_GET['_end_time']."',`date_update`=".$db->qstr(NOW())." 
			     WHERE `employee`='".$_GET['_employee']."' AND TO_DAYS(`shift_date`) = TO_DAYS('".$_GET['_shift_date']."') LIMIT 1"))
		{
			/*$db->Execute("UPDATE `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` 
			     SET `shift_board_employee_shift_type`='".$_GET['_shift_type']."', `shift_board_employee_shift_type_rotation`='".$_GET['_rotation']."',
			     `shift_board_employee_start_time`='".$_GET['_start_time']."',
			     `shift_board_employee_end_time`='".$_GET['_end_time']."',`date_update`=".$db->qstr(NOW())." 
			     WHERE `id`='".$_GET['_employee']."' LIMIT 1");*/
		}
		
		require_once("../../inc/phpmailerx/class.phpmailer.php");
		require_once("../../batch/hr_360/shift/shiftboard_schedule.php");
		
		scheduleEmployeeShift($_SESSION['accesses']->_login['hr360'][0],$_GET['_employee'],$_GET['_shift_date'],"Re-");
		
		$status=1;
	}
	else
	{
		$db->Execute("INSERT INTO `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_shift_schedule`
			     (`employee`,`shift_date`,`shift_type`,`rotation`,`week_of_year`,`start_date`,`start_time`,`end_date`,`end_time`,`date`)
			     VALUES(".$db->qstr($_GET['_employee']).",".$db->qstr($_GET['_shift_date']).",".$db->qstr($_GET['_shift_type']).",".$db->qstr($_GET['_rotation']).",".$db->qstr($_GET['_week_of_year']).","
			     .$db->qstr($_GET['_start_date']).",".$db->qstr($_GET['_start_time']).",".$db->qstr($_GET['_end_date']).",".$db->qstr($_GET['_end_time']).",".$db->qstr(NOW()).")");
			
		require_once("../../inc/phpmailerx/class.phpmailer.php");
		require_once("../../batch/hr_360/shift/shiftboard_schedule.php");
		
		scheduleEmployeeShift($_SESSION['accesses']->_login['hr360'][0],$_GET['_employee'],$_GET['_shift_date'],"");
		
		$status=1;
	}
	
	echo $status;
	
}
else{echo $status;}
?>