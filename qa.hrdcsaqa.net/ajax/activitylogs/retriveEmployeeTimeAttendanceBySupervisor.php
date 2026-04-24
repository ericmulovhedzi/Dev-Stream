<?
ini_set("display_errors",1);
ignore_user_abort(true);
set_time_limit(0);
ini_set('memory_limit','-1');
error_reporting(E_ERROR | E_PARSE);
 
require_once('../../inc/connection.php');

define('ROOTPATH_1',"/usr/www/users/hrdcsaqhwx/oefspiderws.hrdcsaqa.net/");
	
$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_module']) && ($_GET['_module'] >= 1) && isset($_GET['_employee']) && ($_GET['_employee'] >= 1) && isset($_GET['_date_from']) && (!empty($_GET['_date_from'])) && isset($_GET['_date_to']) && (!empty($_GET['_date_to'])))
{
	require_once('../../inc/excel/ovh/ovhxls_xml.php');
	$_XLS = new OVHXLS;
	
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$wfArr = $totalArr = $_MODULE_NAME = $_XLS_DOC = $_XLS_STYLE = $_DATA_ARR = array();$stages = $_FIELD_NAME = $_SQL_LIMIT = $_SQL_LIMIT_SUB = "" ;$_GET['_schedule'] = (isset($_GET['_schedule']) && ($_GET['_schedule']>=1)) ? $_GET['_schedule'] : 2;
	
	$fname = (isset($fname) && (!empty($fname))) ? strtoupper($fname) : "ITEMS";
	$_EMPLOYEE_NAME = $_SUPERVISOR = $_EMPLOYEE_RATE = "";$_TOTAL_HOURS = $_EMPOYEE_TARGET_HOURS = $_EMPLOYEE_PAYE = 0;
	
	
	$_POLICY_LUNCH_BREAK_HOUR = 1;
	
	$__DATA_ARR_DAYS = array();
	
	// --- Module Name
	
	$_ORG_NAME = $_ORG_LOGO = $_TIMESHEET_DISPLAY = $_WEB_DISPLAY = $_PAYPERIOD_DISPLAY = "";
	
	if($_SESSION['accesses']->_login['hr360'][0] == 1){$_ORG_NAME = "House of Nnyane";$_ORG_LOGO = "hom-main-logo.jpg";}
	else if($_SESSION['accesses']->_login['hr360'][0] == 2){$_ORG_NAME = "SnM Security Consultants (Pty) Ltd";$_ORG_LOGO = "snm-logo.png";}
	else if($_SESSION['accesses']->_login['hr360'][0] == 3){$_ORG_NAME = "Netshcoe Solutions";$_ORG_LOGO = "ccba-logo-main.png";}
	
	// --- Filters
	
	if(isset($_GET['_date_from']) && (!empty($_GET['_date_from'])))
	{
		
		$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) >= TO_DAYS('".$_GET['_date_from'] ."') ";
		
		$_TIMESHEET_DISPLAY .= date("jS F Y",strtotime($_GET['_date_from']));
		$_TIMESHEET_DISPLAY_ .= date("jS M Y",strtotime($_GET['_date_from']));
		$_PAYPERIOD_DISPLAY .= date("m/d",strtotime($_GET['_date_from']));
		$_WEB_DISPLAY .= "From <span style='color:#c00;'>".date("jS F Y",strtotime($_GET['_date_from']))."</span>";
	}
	
	
	if(isset($_GET['_date_to']) && (!empty($_GET['_date_to'])))
	{
		
		
		$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		
		$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime("01-".$_GET['_date_to']))."</span>";
		$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
		$_TIMESHEET_DISPLAY .= " - ".date("jS F Y",strtotime($_GET['_date_to']));
		$_TIMESHEET_DISPLAY_ .= " - ".date("jS M Y",strtotime($_GET['_date_to']));
		$_PAYPERIOD_DISPLAY .= " - ".date("m/d",strtotime($_GET['_date_to']));
		$_WEB_DISPLAY .= " to <span style='color:#DD7000;'>".date("jS F Y",strtotime($_GET['_date_to']))."</span>";
	}
	else
	{
		$_USER_DISPLAY .= " <i style='color:#333;'>On:</i> <span style='color:#DD7000;'>".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()))."</span>";
		
	}
	
	if(isset($_GET['_employee']) && ($_GET['_employee'] >= 1))
	{
		$_SQL_LIMIT .= " AND ((`B`.`supervisor`='".$_GET['_employee']."' AND `D`.position=36) OR (`A`.employee='".$_GET['_employee']."')) ";
		
	}
	
	if(!(isset($_SQL_LIMIT) && (!empty($_SQL_LIMIT))))
	{
		//$_SQL_LIMIT = " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		
		//$_USER_DISPLAY .= " <i style='color:#333;'>On:</i> <span style='color:#DD7000;'>".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()))."</span>";
		//$_PDF_DISPLAY .= " ON: ".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()));
	}
	
	$_EXUECTUTE = true;
	
		$employeesArr = genericItemsArr("_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees","");
		$_MODULE_NAME[1] = isset($employeesArr[$_GET['_employee']]) ? $employeesArr[$_GET['_employee']] : "N/A";
		$_EMPLOYEE_NAME = $_MODULE_NAME[1];
		
		$employeesArr = parentsArr($_SESSION['accesses']->_login['hr360'][0]," AND 1=1 ");
		$_MODULE_NAME[1] = isset($employeesArr[$_GET['_employee']]) ? $employeesArr[$_GET['_employee']] : "N/A";
		$_EMPLOYEE_NAME = $_MODULE_NAME[1];
		
	$fname = "Payroll Register - Team ".$_MODULE_NAME[1];
	$fname_payslip = "Payslip - ".$_MODULE_NAME[1];
	
	if(!(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1)))
	{
		$fname = $fname." - Total hours";
	}
		
	$_XLS_DOC = array(array(""), array("Reporting on Module: ","Payroll Register"), array("Payroll Register for Team/Supervisor: ",$_MODULE_NAME[1]), array("Date",$_WEB_DISPLAY),array(""));
	//$_XLS_STYLE = array(array(""),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array(""));
	
	$_XLS_DOC_TMP[] = $fname;//$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	
	
	$_SQL_ITEMS_STR = $_body_header = $_body_body = "";
	
		$date = DateTime::createFromFormat("Y-m-d", $_GET['_date_from']);
		$daysInMonth = $daysInMonth1 = cal_days_in_month(CAL_GREGORIAN, $date->format("m"), $date->format("Y"));
		$day = "";
		$workDays = 0;
		
		$_IS_MORNING = date('Y-m-', strtotime(substr($_GET['_date_from'],0,11)));
		$_XLS_DOC_TMP_ = array();
		for($___i = 1; $___i < ($daysInMonth+1); $___i++)
		{
		
			$_month = date("Y-m",strtotime($_GET['_date_to']));
			$myTime = strtotime($_month."-".$___i); 
			$day = date("D", $myTime); // Sun - Sat
		  
			$_BG_COLOR = $_DAY = $___i;
			if($day == "Sun" || $day == "Sat")
			{
				$_BG_COLOR = "background:#a8c4eb;border-bottom:2px solid #000;";
				$_DAY = $day;
				$_XLS_DOC_TMP_[] = strtoupper($_DAY);
			}
			else{$_XLS_DOC_TMP_[] = "DAY #".$_DAY;}
				
							
			//if($day != "Sun" && $day != "Sat")
			//{
				$_SQL_ITEMS_STR .= "SUM( IF( (TO_DAYS('".$_IS_MORNING.$___i."') = TO_DAYS(`A`.date)),TIMESTAMPDIFF(HOUR,CONCAT(CURDATE(),' ',`A`.signed_in_time),CONCAT(CURDATE(),' ',`A`.signed_out_time)) ,0) ) AS `".$___i."`,";
				$_SQL_ITEMS_STR .= "SUM( IF( (TO_DAYS('".$_IS_MORNING.$___i."') = TO_DAYS(`A`.date)),`A`.`is_reclockin`,0) ) AS `".$___i."_clock_type`,";
				$_SQL_ITEMS_STR .= "SUM( IF( (TO_DAYS('".$_IS_MORNING.$___i."') = TO_DAYS(`A`.date)),1,0) ) AS `".$___i."_clock_cyclye_count`,";
				$_SQL_ITEMS_STR .= "SUM( IF( (TO_DAYS('".$_IS_MORNING.$___i."') = TO_DAYS(`A`.date)),TIMESTAMPDIFF(HOUR,CONCAT(CURDATE(),' ',`A`.signed_in_time),CONCAT(CURDATE(),' ',`A`.signed_out_time))>1 ,0) ) AS `".$___i."_breaks`,";
				
				$__DATA_ARR_DAYS[$___i] = $_DAY;
				
				
				$_body_header .= "
				<td style='background:#c3daef;border-bottom:1px solid #ccc;color:#125687;".$_BG_COLOR."font-weight:bold;font-size:11px;' align='center'>&nbsp;".$_DAY."&nbsp;&nbsp;</td>";
			//}
			
			
		    //$daysInMonth--;
		    //$myTime += 86400; // 86,400 seconds = 24 hrs.
		}

	//echo $_SQL_ITEMS_STR;
	
	$colspan = 3+$daysInMonth; $_COLS_MINUS = 3;
	
	if(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))
	{
		$colspan = 6+$daysInMonth; $_COLS_MINUS = 0;
	}
		
	$_body .= "
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:right;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#aaa;text-indent:2px;font-size:12px;font-weight:normal;'><i>Reporting on Module: <span style='color:#555;'>Payroll Register</span>.</i>&nbsp;</td>	     
		</tr>
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:left;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b>Payroll Register for Team/Supervisor: <span style='color:#e62899;'>".$_MODULE_NAME[1]."</span>, Date: ".$_WEB_DISPLAY."</b></td>	     
		</tr>
		<tr>
			<td style='background:#eaeaea;text-align:left;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b></b></td>
			<td colspan='".($colspan-6+$_COLS_MINUS)."' style='background:#eaeaea;text-align:center;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b>DAYS OF MONTH</b></td>	     
			<td colspan='2' style='background:#eaeaea;text-align:center;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b>TOTALS</b></td>";
		
		if(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))
		{
			$_body .= "
			<td style='background:#eaeaea;text-align:left;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b></b></td>
			<td colspan='2' style='background:#eaeaea;text-align:center;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b>PAYS</b></td>";
		}
			
		$_body .= "
		</tr>
		<tr>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;width:350px;'>&nbsp;<b>Employee Name</b></td>
			";
		//$_XLS_DOC_TMP[] = "Employee Name";
		
		$_XLS_DOC_TMP = array_merge(array("EMPLOYEE NAME"),$_XLS_DOC_TMP_,array("HOURS WORKED","DAYS WORKED"));
		
		$_body .= $_body_header;
	
	$totalArr['total_hours'] = $totalArr['total_days'] = $totalArr['total_salary_wages'] = 0;
	
			
	//echo $_GET['_isemail'];
	
		$_body .= "
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;border-bottom:2px solid #000;' align='center'>Hours</td>	
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;border-bottom:2px solid #000;' align='center'>Days</td>";
				
	if(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))
	{
		$_body .= "	
				<td style='background:#d3d3d3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Rate</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;border-bottom:2px solid #000;' align='center'>Gross Pay</td>	
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;border-bottom:2px solid #000;' align='center'>Net Pay</td>
			";
	}
	
	$_body .= "
		</tr>";
	$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_DOC_TMP = array();$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_STYLE_TMP = array();//= "hdr_bold_fz10_bgred_colorred";
	
	$_TOTAL = 0;
	
	// --- zz
	
	$_IVENTORY_MODULE = $_GET['_employee']; 
	$_SQL_INVENTORY_SELECT = $_SQL_INVENTORY_ORDER_BY = "";
	$_SQL_LIMIT = "
			  SELECT
					`B`.`supervisor`,`D`.position AS `position`,CONCAT(`B`.name,' ',`B`.surname) AS `cname`,`B`.id AS `cid`,`B`.employee_no AS `employee_id`,
					`B`.`shift_board_employee_shift_rate`,`B`.`shift_board_employee_rate_basis`,`B`.`shift_board_employee_rate_type`,`B`.`shift_board_employee_start_time`,`B`.`shift_board_employee_end_time`,
					".$_SQL_ITEMS_STR."
					SUM(TIMESTAMPDIFF(HOUR,CONCAT(CURDATE(),' ',`A`.signed_in_time),CONCAT(CURDATE(),' ',`A`.signed_out_time))) AS `total`,
					CONCAT (`B`.name, ' ',`B`.surname) AS `sname`
				FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_clockin` AS `A`
					LEFT JOIN `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `B` ON `B`.id=`A`.employee
					LEFT JOIN `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `C` ON `C`.id=`B`.supervisor
					LEFT JOIN `pm_organograms_joints` AS `D` ON `D`.id=`C`.position
				WHERE `A`.pub=1 AND `A`.del=1 AND `A`.clockin_status='OUT' $_SQL_LIMIT
			  GROUP BY `A`.employee
			  ORDER BY `total` DESC
			  ";
	$rs =$db->Execute($_SQL_LIMIT);
	if(($rs) && ($rs->numRows() >= 1))
	{
		//$employeesArr = genericItemsArr("_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees","");
		
		$_HOURS_TOTAL = 0; $_INDEX = 1;
		while(!$rs->EOF)
		{
			$_XLS_DOC_TMP = array();$_XLS_STYLE_TMP = array();
			//$itemName = (isset($retrieveSelect[$rs->fields['item']]) && (!empty($retrieveSelect[$rs->fields['item']]))) ? $retrieveSelect[$rs->fields['item']] : "Item ".$rs->fields['item'];
			//$itemName = $rs->fields['name'];
			
			//$employee = isset($employeesArr[$rs->fields['employee']]) ? $employeesArr[$rs->fields['employee']] : "N/A";
			
			$islate_color = "#f3f3f3";
			if(isset($rs->fields['_islate']) && ($rs->fields['_islate'] == "Late")){$islate_color = "#FBE3E4";}
			else if(isset($rs->fields['_islate']) && ($rs->fields['_islate'] == "Early")){$islate_color = "#E6EFC2";}
			
			$_XLS_DOC_TMP[] = $itemName;$_XLS_STYLE_TMP[] = "";
			
			$_HOURS_TOTAL = $_HOURS_TOTAL + $rs->fields['total_hours'];
			
			$_image_export = (!empty($rs->fields['picture'])) ? "&nbsp;&nbsp;<a target='_blank' href='".WWWROOT."exports/pdf/employee/proof_of_clockin.php?_id=".$rs->fields['id']."'><img alt='' src='".WWWROOT."images/icons/camera-gray.png' border='0'></a>" : "";
			
			$_body .= "
				<tr>
					<td style='border-bottom:1px solid #ccc;font-size:11px;color:#008000;background:#f3f3f3;line-height:23px;font-weight:bold;' align='left'>&nbsp;".$_INDEX.".&nbsp;".$rs->fields['cname']."&nbsp;".$_image_export."</td>
					";
						
					$_DATA_ARR_1 = $_DATA_ARR_BG_COLOR_ = array();
						
					$_daysInMonth1 = $daysInMonth1;
					$_XLS_DOC_TMP_ = $_XLS_DOC_TMP__ = array();
					for($___i = 1; $___i < ($_daysInMonth1+1); $___i++)
					{
						$_month = date("Y-m",strtotime($_GET['_date_to']));
						$myTime = strtotime($_month."-".$___i); 
						$day = date("D", $myTime); // Sun - Sat
					  
						$_BG_COLOR = "";$_BG_COLOR_ = "#f9f9f9";
						if($day == "Sun" || $day == "Sat")
						{
							$_BG_COLOR = "background-color:#e3e3e3;";$_BG_COLOR_ = "#e3e3e3";
						}
						
						
						$_IS_MANUAL_CLOCKIN = (isset($rs->fields[$___i.'_clock_type']) && ($rs->fields[$___i.'_clock_type'] > 0)) ? $rs->fields[$___i.'_clock_type']:0;
						$_IS_MANUAL_CLOCKIN_COUNT = (isset($rs->fields[$___i.'_clock_cyclye_count']) && ($rs->fields[$___i.'_clock_cyclye_count'] > 0)) ? $rs->fields[$___i.'_clock_cyclye_count']:0;
						//echo $_IS_MANUAL_CLOCKIN."/".$_IS_MANUAL_CLOCKIN_COUNT."<br>";
						
						if($_IS_MANUAL_CLOCKIN >= 1)
						{
							$result = 0; $result = (($_IS_MANUAL_CLOCKIN_COUNT - $_IS_MANUAL_CLOCKIN) * 100) / $_IS_MANUAL_CLOCKIN_COUNT; 
							//echo $_IS_MANUAL_CLOCKIN."/".$_IS_MANUAL_CLOCKIN_COUNT.": ".$result."<br />";
							
							//if(($result >= 66) && ($result <= 100)){$_BG_COLOR = "background-color:#a34c4c;";}
							//else if(($result >= 50) && ($result < 66)){$_BG_COLOR = "background-color:#c47474;";}
							//else $_BG_COLOR = "background-color:#e3b3b3;";
							
							if(($result < 50) ){$_BG_COLOR = "background-color:#f5ae91;";$_BG_COLOR_ = "#f5ae91";}
							else if(($result >= 50) && ($result < 66)){$_BG_COLOR = "background-color:#facc5c;";$_BG_COLOR_ = "#facc5c";}
							else if(($result >= 66) && ($result <= 100)){$_BG_COLOR = "background-color:#fce5da;";$_BG_COLOR_ = "#fce5da;";}
							
						}
						
						//$__URL = "&p=employees_clockin_history&srch[employee]=43".$rs->fields['cid'];
						//<a href='".$_BASE_URI.$__URL."'>
						
						$_DATA_ARR_BG_COLOR_[$___i] = $_BG_COLOR_;
						$_DATA_ARR_1[$___i] = (isset($rs->fields[$___i]) && ($rs->fields[$___i] > 0) ? $rs->fields[$___i]:0);
						
						$_VALUE_V = (isset($rs->fields[$___i]) && ($rs->fields[$___i] > 0) ? $rs->fields[$___i]:"");
						if($day == "Sun" || $day == "Sat")
						{
							$_VALUE_V = (isset($_VALUE_V) && ($_VALUE_V>0)) ? $_VALUE_V : "";
						}
						else
						{
							if(strtotime($_GET['_date_to']) < strtotime(NOW())){$_VALUE_V = (isset($_VALUE_V) && ($_VALUE_V>0)) ? $_VALUE_V : "A";}
							else if($___i<date("d")){$_VALUE_V = (isset($_VALUE_V) && ($_VALUE_V>0)) ? $_VALUE_V : "A";}
							
						}
						
						$_XLS_DOC_TMP_[] = $_VALUE_V;
						$_XLS_DOC_TMP__[] = "";
						
						$_body .= "<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;$_BG_COLOR' align='center'><b>".$_VALUE_V."</b></td>";
						
						
						
					    //$daysInMonth1--;
					}
					
					
			$_EMPLOYEE_RATE = $rs->fields['shift_board_employee_shift_rate'];
			$_EMPLOYEE_RATE_BASIS = $rs->fields['shift_board_employee_rate_basis'];
			$_EMPLOYEE_RATE_TYPE = $rs->fields['shift_board_employee_rate_type'];
			
			$_EMPOYEE_TARGET_HOURS = 9; // 9 hours
			$_TOTAL_SALARY_WAGES = "R 0.00";
			$_TOTAL_SALARY_DAYS = 0;
			$_basis = "";
			
			if($_EMPLOYEE_RATE_BASIS == 1)
			{
				$_basis = "hr";
				$_TOTAL_SALARY_WAGES = "R".number_format(round(($_EMPLOYEE_RATE*$rs->fields['total']),2),2,"."," ");
				
				$totalArr['total_salary_wages'] += ($_EMPLOYEE_RATE*$rs->fields['total']);
			}
			else if($_EMPLOYEE_RATE_BASIS == 2)
			{
				$_basis = "day";
				$_TOTAL_SALARY_WAGES = "R".number_format(round(($_EMPLOYEE_RATE*round($rs->fields['total']/$_EMPOYEE_TARGET_HOURS)),2),2,"."," ");
				$_TOTAL_SALARY_DAYS = round($rs->fields['total']/$_EMPOYEE_TARGET_HOURS);
				
				$totalArr['total_salary_wages'] += ($_EMPLOYEE_RATE*round($rs->fields['total']/$_EMPOYEE_TARGET_HOURS));
			}
			
			$_body .= "
						<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#E6EFC2;' align='center'><b>".$rs->fields['total']."</b>&nbsp;</td>
						<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#E6EFC2;' align='center'><b>".$_TOTAL_SALARY_DAYS."</b>&nbsp;</td>";
				
			if(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))
			{
				$_body .= "
						<td style='border-bottom:1px solid #ececec;color:#213552;font-size:10px;background:#f9f9f9;' align='center'>".$_EMPLOYEE_RATE."/".$_basis."</td>
						<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#E6EFC2;' align='right'><b>".$_TOTAL_SALARY_WAGES."</b>&nbsp;</td>
						<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#E6EFC2;' align='right'><b>".$_TOTAL_SALARY_WAGES."</b>&nbsp;</td>
						";
				$_XLS_DOC_TMP = array_merge(array("EMPLOYEE NAME"),$_XLS_DOC_TMP_,array("HOURS WORKED","DAYS WORKED"));
			}
			else{
				$_XLS_DOC_TMP = array_merge(array($rs->fields['cname']),$_XLS_DOC_TMP_,array($rs->fields['total'],$_TOTAL_SALARY_DAYS));
					
			}
			//$_TOTAL_HOURS = $_TOTAL_HOURS + ($rs->fields['total_hours']);
							
			$totalArr['total_hours'] += $rs->fields['total'];
			$totalArr['total_days'] += $_TOTAL_SALARY_DAYS;
			
			//$totalArr['approve'] += $rs->fields['approve'];
			//$totalArr['total'] += $rs->fields['total'];
			
			//$_XLS_DOC_TMP[] = $rs->fields['reject'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['approve'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['total'];$_XLS_STYLE_TMP[] = "";
			
			$_DATA_ARR[] = array
					(
					     $_INDEX.". ".ucfirst(strtolower($rs->fields['cname'])),
					     $_DATA_ARR_1,
					     $_DATA_ARR_BG_COLOR_,
					     $rs->fields['total'],
					     $_TOTAL_SALARY_DAYS,
					     $_EMPLOYEE_RATE."/".$_basis,
					     "",
					     $_TOTAL_SALARY_WAGES,
					     $_TOTAL_SALARY_WAGES,
					     $rs->fields['employee_id'],
					     ""
					);
			
			
			$_body .= "
				</tr>";
				
			//$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_DOC_TMP = $_XLS_STYLE_TMP = array();
			$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_DOC_TMP = $_XLS_STYLE_TMP = array();
			
			
			$_INDEX++;
			$rs->MoveNext();
		}
		
	}//else{echo "Error: ".$db->errorMsg();}
	//print_r($__DATA_ARR_DAYS);
	$_XLS_DOC_TMP = array();
	
	$_XLS_DOC_TMP[] = "TOTALS:";
	$_body .= "
		<tr>
			<td colspan='".($colspan-5+$_COLS_MINUS)."' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>&nbsp;</td>";
		
	//$_XLS_DOC_TMP[] = $totalArr['reject'];$_XLS_DOC_TMP[] = $totalArr['approve'];$_XLS_DOC_TMP[] = $totalArr['total'];
	
	$_body .= "
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#D00000;font-weight:bold;font-size:11px;border-top:2px solid #000;border-bottom:2px solid #000;' align='center'>".$totalArr['total_hours']." hours&nbsp;</td>
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#D00000;font-weight:bold;font-size:11px;border-top:2px solid #000;border-bottom:2px solid #000;' align='center'>".$totalArr['total_days']." days&nbsp;</td>
		";
		
	if(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))
	{
		$_body .= "
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#D00000;font-weight:bold;font-size:11px;' align='center'>&nbsp;</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#D00000;font-weight:bold;font-size:11px;border-top:2px solid #000;border-bottom:2px solid #000;' align='center'>R".number_format(round($totalArr['total_salary_wages'],2),2,"."," ")."</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#D00000;font-weight:bold;font-size:11px;border-top:2px solid #000;border-bottom:2px solid #000;' align='center'>R".number_format(round($totalArr['total_salary_wages'],2),2,"."," ")."</td>
			";
	}
	else
	{
		$_XLS_DOC_TMP = isset($_XLS_DOC_TMP__) ? array_merge(array($rs->fields['cname']),$_XLS_DOC_TMP__,array(number_format(round($totalArr['total_salary_wages'],2),2,"."," "),number_format(round($totalArr['total_salary_wages'],2),2,"."," "))) : array();	
		
		//if(!isset($_XLS_DOC_TMP__)){echo "---";}
	}
	
	$_body .= "
			
		</tr>";
	
	$_XLS_DOC[] = array();$_XLS_DOC[] = $_XLS_DOC_TMP;//$_XLS_STYLE[] = "hdr_bold_fz10_bgred_colorred";

	
	$_body = "<table width='100%' class='main' border='0' valign='top' style='border:1px solid #e5e5e5;background:#fff;clear:both;margin-top:5px;font-family: Arial, Helvetica, Verdana, sans-serif;font-size:11px;'>".$_body."</table>";
	
	if(!(isset($_GET['_disp']) && ($_GET['_disp'] >= 1)))
	{
		
		$_GET['is_pdf']=1;
		if(isset($_GET['is_pdf']) && ($_GET['is_pdf'] >= 1))
		{
			$_body_1 = "<center>
				<a style='text-decoration:none;' target='_blank' href='../../exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_employee']." - ".$_TIMESHEET_DISPLAY.".pdf'><span style='background-color:#ddd;border:2px solid #D00000;padding:3px;color:#D00000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download PDF Payroll Register</span>
				</a> 
			";
			if(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))
			{
				//$_body_1 .= "<a style='text-decoration:none;' target='_blank' href='../../exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_employee']." - ".$_TIMESHEET_DISPLAY.".pdf'><span style='background-color:#ddd;border:2px solid #3283bd;padding:3px;color:#3283bd;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download SARS EMP201</span>
				//</a> ";
			}
			//if(isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1))
		if(true)
		{
			$_body_1 .= "<a style='text-decoration:none;' target='_blank' href='../../exports/excel/campaigns/tmp/".$fname.".xls'><span style='background-color:#ddd;border:2px solid #00A000;padding:3px;color:#008000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download Excel Payroll Register</span></a>";
		}
		
			$_body = $_body_1.$_body;
		}
		
		echo $_body;
	}
	
	if((isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1)) || (isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1)) || (isset($_GET['is_pdf']) && ($_GET['is_pdf'] >= 1)))
	{
		// -- Write Email to temporary folder
		//echo $_GET['_isemail']."---";
		$_XLS->addArray($_XLS_DOC,$_XLS_STYLE);
		
		//if(isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1))
		if(true)
		{
			//$_XLS->writeExcelToBrowser($fname.".xls");
			$_XLS->saveExcelToFile("../../exports/excel/campaigns/tmp/".$fname.".xls");
			
		}
		else
		{
			$_XLS->saveExcelToFile("../../exports/excel/campaigns/tmp/".$fname.".xls");
		}
		
		// -- PDF Generation
		//echo "../../exports/excel/campaigns/tmp/_graphCharts.php?_file=".urlencode($fname);
		//@require_once("../../exports/excel/campaigns/tmp/_graphCharts.php?_file=".urlencode($fname));
		//echo "http://oefaspen.net/docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname);
		//$ret = @file("http://oefaspen.net/docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname));
		
		header("Cache-Control: public, must-revalidate");
		header("Pragma: hack");
		header("Content-Type: text/pdf");
		
		//require("../../inc/pdf_1/ellipse.php");
		//require("../../inc/pdf/fpdf186/fpdf.php");
		//require("../../inc/pdf/fpdf186/fpdf.php");
		require(ROOTPATH_1."inc/pdf/fpdf186/fpdf.php");
		
			
		header("Content-Disposition: attachment; filename=BUSINESS-PROCESS-WORKFLOW.pdf");
		// _________________________________________________ PDF Header
		
		//$_WIDTH = 164; $_HEIGHT = 164; // --- Document Width & Height
		$_WIDTH = 216; $_HEIGHT = 297; // --- Document Width & Height
		
		//$pdf=new PDF_Ellipse('P','mm',array($_WIDTH,$_HEIGHT));
		//$pdf = new FPDF('P','mm',array($_HEIGHT,$_WIDTH));
		
		//$pdf = new FPDF();
		//$pdf = new FPDF('P','mm','A4');
		//$pdf->Open();
		$pdf = new FPDF('P','mm',array(210,297));
		
		$_WIDTH = 216; $_HEIGHT = 297; // --- Document Width & Height
		
		$_START_LEFT = 24;$_START_TOP = 15;
		$_P_WIDTH = $_WIDTH - ($_START_LEFT*2);// --- Page Content Width
		$_PC_WIDTH = $_P_WIDTH - 13; // --- Refined  content width
		
		$p = 1;
		
		$pdf->SetTextColor(0);
		
		list($RGB['r'][0],$RGB['g'][0],$RGB['b'][0]) = sscanf("#d7c834","#%02x%02x%02x");
		list($RGB['r'][1],$RGB['g'][1],$RGB['b'][1]) = sscanf("#000000","#%02x%02x%02x");
		list($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]) = sscanf("#d7c834","#%02x%02x%02x");
		
		//echo $_GET['_isemail']."---";
		//if(((isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
		if(true)
		{//echo $_GET['_isemail']."---";
		// _____________________________________________________ Page # 1 - Cover Page __________________________________________________
		
		
		$p = 0;
		
		$pdf->AddPage('L');$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$pdf->SetDrawColor(0);$pdf->SetLineWidth(0.1);//$pdf->Line($_HEIGHT/2,0,$_HEIGHT/2,$_WIDTH); 
		
		$h=0;$w = 155;
		$pdf->SetFillColor(155,155,155);$pdf->SetTextColor(0);$pdf->SetFont('arial','B',16);
		//$pdf->SetAlpha(0.25);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h);$pdf->MultiCell($_P_WIDTH/2.5,24,"","","C",true);
		//$pdf->SetAlpha(1);
		$pdf->SetFillColor(75,75,75);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+24);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+9.5);$pdf->Cell($_P_WIDTH/2.5,6,"Accuracy & Compliance","",0,"C",false);
		$pdf->SetFont('arial','',11);$pdf->SetTextColor(95);$pdf->SetXY($w+3.5+($_P_WIDTH/2.5),$h+17.55);$pdf->Cell($_P_WIDTH/2.61,6,"( Ensure accuracy and compliance ) ",0,0,"C",false);
		
		$pdf->SetFont('arial','B',11);$pdf->SetTextColor(55);$pdf->SetXY($w+2.5+($_P_WIDTH/2.5),$h+17.55+19);$pdf->Cell($_P_WIDTH/2.61,6,"Team: ".ucwords(strtolower($_EMPLOYEE_NAME)),"LB",0,"L",false);
		
		$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w+2.5+($_P_WIDTH/2.5),$h+17.55+19+6);$pdf->Cell($_P_WIDTH/2.61,6,"Date: ".ucwords(strtolower($_TIMESHEET_DISPLAY_)),"LB",0,"L",false);
		
		
		$h = $_START_TOP;$w = $_START_LEFT;
		
		    $pdf->SetTextColor(0);$pdf->SetFont('arial','',34);
		//$pdf->SetXY(0,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"OVH IoT: Manufacturing \nDivision",0,"C");
		if((!(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1)))){$pdf->SetXY(70-2,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"Payroll Register\nTotal hours",0,"L");}
		else{$pdf->SetXY(70-2,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"Payroll Register\nGross Pay and Net Pay",0,"L");}
		
		$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$_ORG_LOGO,10,13.5,55,0,'','');
				
		$h+=8*2.25;
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(0);$pdf->Line(70,$h-1,$_HEIGHT-6.0,$h-1);
		
		$h+=8*2.65;$center = 65.5;
		
		// --- --- --- Header
		
		$w = $_START_LEFT-17.5;//$h+=6*6.5;
		
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(39,44,49);$pdf->SetTextColor(255);
		$pdf->SetFont('arial','B',10);
		if((!(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
		{
			
			$pdf->SetXY($w,$h);$pdf->MultiCell(40,6,"Employee Name",1,'L',true);
			
			$__w = $w;$_width_x = 0;$_width__x = 0;
			foreach($__DATA_ARR_DAYS as $k=>$v)
			{
				//$_width_x = (isset($v) && (is_numeric($v))) ? 10 : 12.5;
				$pdf->SetFillColor(168,196,235);$pdf->SetTextColor(14,74,122);
				if($v == "Sun" || $v == "Sat")
				{
					$pdf->SetFillColor(129,173,235);
				}
				$_width_x = (isset($v) && (is_numeric($v))) ? 6.5 : 9.0;
				$pdf->SetFont('arial','',10);
				$pdf->SetXY($w+30+$__w,$h);$pdf->MultiCell($_width_x ,6,$v,1,'C',true);
				$__w = $__w + $_width_x;
			}
		}
		else
		{
			$__w = 20;$pdf->SetXY($w+60,$h);$pdf->SetXY($w,$h);$pdf->MultiCell(40+20,6,"Employee Name",1,'L',true);
		}
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(39,44,49);$pdf->SetTextColor(255);$pdf->SetFont('arial','B',10);
		
		if((!(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
		{
			$pdf->SetXY($w+30+$__w,$h);$pdf->MultiCell(10,6,"Hrs",1,'C',true);
			$pdf->SetXY($w+30+$__w+10,$h);$pdf->MultiCell(12.5,6,"Days",1,'C',true);
			
		}else{
			$pdf->SetXY($w+35+$__w,$h);$pdf->MultiCell(25,6,"Employee ID",1,'C',true);
			$pdf->SetXY($w+35+$__w+25,$h);$pdf->MultiCell(35,6,"Department",1,'C',true);
			$pdf->SetXY($w+35+$__w+25+35,$h);$pdf->MultiCell(35,6,"Pay Period",1,'C',true);
			$pdf->SetXY($w+35+$__w+25+35+35,$h);$pdf->MultiCell(15,6,"Hours",1,'C',true);
			$pdf->SetXY($w+35+$__w+25+35+35+15,$h);$pdf->MultiCell(15,6,"Days",1,'C',true);
			$pdf->SetXY($w+35+$__w+25+35+35+15+15,$h);$pdf->MultiCell(25,6,"Rate/Salary",1,'C',true);
			$pdf->SetXY($w+35+$__w+25+35+35+15+15+25,$h);$pdf->MultiCell(25,6,"Deductions",1,'C',true);
			$pdf->SetXY($w+35+$__w+25+35+35+15+15+25+25,$h);$pdf->MultiCell(25,6,"Gross Pay",1,'C',true);
			$pdf->SetXY($w+35+$__w+25+35+35+15+15+25+25+25,$h);$pdf->MultiCell(25,6,"Net Pay",1,'C',true);
		}
		
		if(((isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
		{
			
			//$pdf->SetXY($w+30+$__w,$h);$pdf->MultiCell(10,6,"Hrs",1,'C',true);
			//$pdf->SetXY($w+30+$__w+10,$h);$pdf->MultiCell(12.5,6,"Days",1,'C',true);
		
		}
		
		$h+=6;
		$pdf->SetFont('arial','',10);$_TOTAL = 0;$pdf->SetTextColor(39,44,49);
		
		
				// _____________________________________________________ ::: __________________________________________________
				
				$_INDEX = 0;$_CUT_OFF = 25;
				foreach($_DATA_ARR as $k=>$v)
				{
						$pdf->SetFont('arial','',9.0);
					
					
					
					if((!(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
					{
						$pdf->SetXY($w,$h);$pdf->MultiCell(40,5,$v[0],1,'L');$pdf->SetXY($w+60,$h);
						
						$__w = $w;$_width_x = 0;$_width__x = 0;
						foreach($v[1] as $_k=>$_v)
						{
							//$_width_x = (isset($v) && (is_numeric($v))) ? 10 : 12.5;
							$pdf->SetFillColor(249,249,249);$pdf->SetTextColor(0);
							
							//echo $v[2][$_k]."<br>";
							if(isset($v[2][$_k]) && (!empty($v[2][$_k])))
							{//echo $v[2][$_k]."<br>";
								list($RGB['r'][10],$RGB['g'][10],$RGB['b'][10]) = sscanf($v[2][$_k],"#%02x%02x%02x");
								$pdf->SetFillColor($RGB['r'][10],$RGB['g'][10],$RGB['b'][10]);
							}
							
							$_v = (isset($_v) && ($_v>0)) ? $_v : "";
							
							if($__DATA_ARR_DAYS[$_k] == "Sun" || $__DATA_ARR_DAYS[$_k] == "Sat")
							{
								$pdf->SetFillColor(220,220,220);
								$_v = (isset($_v) && ($_v>0)) ? $_v : "";
							}
							else
							{
								if(strtotime($_GET['_date_to']) < strtotime(NOW())){$_v = (isset($_v) && ($_v>0)) ? $_v : "A";}
								else if($__DATA_ARR_DAYS[$_k]<date("d")){$_v = (isset($_v) && ($_v>0)) ? $_v : "A";}
							}
							
							$_width_x = (isset($__DATA_ARR_DAYS[$_k]) && (is_numeric($__DATA_ARR_DAYS[$_k]))) ? 6.5 : 9.0;
							$pdf->SetFont('arial','B',8);
							
							$pdf->SetXY($w+30+$__w,$h);$pdf->MultiCell($_width_x ,5,$_v,1,'C',true);
							$__w = $__w + $_width_x;
						}
					}
					else
					{
						$__w = 20;$pdf->SetXY($w,$h);$pdf->MultiCell(40+20,5,$v[0],1,'L');$pdf->SetXY($w+60,$h);
					}
					
					$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);
					$pdf->SetFillColor(255);$pdf->SetTextColor(0);
					
					if((!(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
					{
						$pdf->SetFont('arial','B',8.0);
						$pdf->SetXY($w+30+$__w,$h);$pdf->MultiCell(10,5,$v[3],1,'C',true);
						$pdf->SetXY($w+30+$__w+10,$h);$pdf->MultiCell(12.5,5,$v[4],1,'C',true);
					}else{
						$pdf->SetXY($w+35+$__w,$h);$pdf->MultiCell(25,5,$v[9],1,'C',true);
						$pdf->SetXY($w+35+$__w+25,$h);$pdf->MultiCell(35,5,"",1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35,$h);$pdf->MultiCell(35,5,$_PAYPERIOD_DISPLAY,1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35,$h);$pdf->MultiCell(15,5,$v[3],1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15,$h);$pdf->MultiCell(15,5,$v[4],1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15+15,$h);$pdf->MultiCell(25,5,$v[5],1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15+15+25,$h);$pdf->MultiCell(25,5,$v[6],1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15+15+25+25,$h);$pdf->MultiCell(25,5,$v[7],1,'R',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15+15+25+25+25,$h);$pdf->MultiCell(25,5,$v[8],1,'R',true);
					}
						/*if($v[7] == "Early"){$pdf->SetFillColor(128,246,166);}else if($v[7] == "Late"){$pdf->SetFillColor(244,188,191);}else{$pdf->SetFillColor(255);}
						
					$pdf->MultiCell(15,5,$v[2],1,'C',true);
					$pdf->SetXY($w+75,$h);$pdf->MultiCell(20,5,$v[3],1,'C');$pdf->SetXY($w+95,$h);$pdf->MultiCell(20,5,$v[4],1,'L');
					$pdf->SetXY($w+115,$h);$pdf->MultiCell(30,5,$v[5],1,'L');$pdf->SetXY($w+145,$h);$pdf->MultiCell(30,5,$v[6],1,'L');$pdf->SetXY($w+145+30,$h);$pdf->MultiCell(30+25,5,$v[11],1,'L');
					$pdf->SetXY($w+205+25,$h);$pdf->SetFillColor(128,246,166);
					
						if($v[7] == "Early"){$pdf->SetFillColor(128,246,166);}else if($v[7] == "Late"){$pdf->SetFillColor(244,188,191);}else{$pdf->SetFillColor(255);}
						
					$pdf->MultiCell(25,5,$v[1],1,'C',true);
					$pdf->SetXY($w+230+25,$h);$pdf->MultiCell(25,5,$v[7],1,'C');//$pdf->SetXY($w+255,$h);$pdf->MultiCell(25,5,$v[10],1,'C');
						*/
						$pdf->SetTextColor(39,44,49);
					$h+=5;	
									
						
					$_INDEX++;
					    
					    if($_INDEX == $_CUT_OFF)
					    {
						 $pdf->AddPage('L');$p++;$h = $_START_TOP;//$w = $_START_LEFT;
						 //$pdf->SetAlpha(0.15);$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$rs_modules->fields['orglogo'],-1.5,163.5,65,0,'','');$pdf->SetAlpha(1);
						 $pdf->SetFont('arial','',10);$pdf->Text(146,200.4,$p);
						 //$mapUrl = "https://maps.googleapis.com|26.0957188,27.9996763&zoom=15&key=AIzaSyAgNLbmb6DS_AwOonDyxgmOytLHw7NtqxU";
						 //$mapUrl = "https://maps.googleapis.com/maps/api/js?key=AIzaSyAgNLbmb6DS_AwOonDyxgmOytLHw7NtqxU&libraries=maps,marker";
						 //$pdf->Image($mapUrl,51.5,63.5,65);
						 
						 $pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$_ORG_LOGO,221.5,188.5,65,0,'','');
						 
						 $_INDEX = 0;
						 if($p >= 2){$_CUT_OFF = 22;}
					    }
					    
				}
				$h+=1;
					$pdf->SetLineWidth(0.2);$pdf->SetDrawColor(0);
					$pdf->SetFillColor(189,189,189);$pdf->SetTextColor(209,65,44);
					
					if((!(isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
					{
						$pdf->SetFont('arial','B',9.0);
						$pdf->SetXY($w+30+$__w,$h);$pdf->MultiCell(10,5,$totalArr['total_hours'],"TB",'C',true);
						$pdf->SetXY($w+30+$__w+10,$h);$pdf->MultiCell(12.5,5,$totalArr['total_days'],"TBL",'C',true);
					}else{
						$pdf->SetFont('arial','B',9.0);
						
						$pdf->SetXY($w+35+$__w+25+35+35,$h);$pdf->MultiCell(15,5,$totalArr['total_hours'],1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15,$h);$pdf->MultiCell(15,5,$totalArr['total_days'],1,'C',true);
						//$pdf->SetXY($w+35+$__w+25+35+35+15+15,$h);$pdf->MultiCell(25,5,"",1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15+15+25,$h);$pdf->MultiCell(25,5,"",1,'C',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15+15+25+25,$h);$pdf->MultiCell(25,5,"R ".$totalArr['total_salary_wages'],1,'R',true);
						$pdf->SetXY($w+35+$__w+25+35+35+15+15+25+25+25,$h);$pdf->MultiCell(25,5,"R ".$totalArr['total_salary_wages'],1,'R',true);
					}
					
				// --- Footer
				
			}
		
		// ______________________________________________ Timesheet Cover Page #2 __________________________________________________
		
						
				// ---- End of Document
				
				// --- Document Author
				
				$pdf->SetAuthor(SYST_ABBR.' - OVH Enterprise Framework');   
				$pdf->SetTitle($fname." - ".$_GET['_employee']." - ".$_TIMESHEET_DISPLAY);
				
				@$pdf->Output(ROOTPATH_1."exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_employee']." - ".$_TIMESHEET_DISPLAY.".pdf","F");
				
				
				
				//@$pdf->Output(ROOTPATH_1."exports/pdf/campaigns/tmp/".$fname_payslip." - ".$_GET['_employee']." - ".date("Y-F-d").".pdf","F");
				
					//echo ROOTPATH."docs/dataobjects/activities/tmp/".$fname.".pdf";
					//exit;
		/*			
		// -- Actual Email Send
		
		require_once('../../inc/phpmailer/class.phpmailer.php');
		
		$subject = $_MODULE_NAME[3].": ".$_MODULE_NAME[2].": ".$_MODULE_NAME[1].": ".$_FIELD_NAME." Overview: ".substr(NOW(),0,11)." @ ".substr(NOW(),11,11);
		
		$emailer = new PHPMailer();
		$emailer->From      = 'CDBM@abi-hosting';
		$emailer->FromName  = 'OEFSBPM - ACTIVITIES REPORT';
		$emailer->AddReplyTo('lwazi.zwane@ovhstudio.co.za',$emailer->FromName);
		$emailer->Subject   = trim($subject);
		
		$emailer->AddAttachment("../../exports/excel/campaigns/tmp/".$fname.".xls",$fname.".xls");
		$emailer->AddAttachment("../../exports/excel/campaigns/tmp/".$fname.".pdf",$fname.".pdf");
		
		//$emailer->AddBCC("ericm00142@gmail.com");
		$emailer->AddBCC("malekawiseman@gmail.com");$emailer->AddBCC("lwazi.zwane@ovhstudio.co.za");
		//$emailer->AddCC("lwazi.zwane@ovhstudio.co.za");
		
		//$emailer->AddBCC("commac.creations@gmail.com");$emailer->AddBCC("lwazi.zwane@ovhstudio.co.za");
		//$emailer->AddBCC("syllucia.mosima@ovhstudio.co.za");$emailer->AddBCC("syllucia.mosima@ovhstudio.co.za");
		//$emailer->AddCC("lwazi.zwane@ovhstudio.co.za");
		//$emailer->AddCC("lwazi.zwane@ovhstudio.co.za");$emailer->AddCC("BMahlezana@aspenpharma.com");$emailer->AddCC("emudau@aspenpharma.com");
		
		//return $emailer->Send();
		//echo "SELECT GROUP_CONCAT(CONCAT(`A`.item) SEPARATOR',') AS `selectids`,GROUP_CONCAT(CONCAT('\'',`A`.subject,'\'') SEPARATOR',') AS `selectnames`,`A`.type FROM `users_links_activitiesreports` AS `A` WHERE `A`.`page`=".$db->qstr($_GET['_employee'])." AND `A`.`subject`=".$db->qstr($_GET['_field'])." AND `A`.`schedule`=".$db->qstr($_GET['_schedule'])." AND `A`.`pub`='1' AND `A`.`del`='1' GROUP BY `A`.type";
		$rs_c =$db->Execute("SELECT GROUP_CONCAT(CONCAT(`A`.item) SEPARATOR',') AS `selectids`,GROUP_CONCAT(CONCAT('\'',`A`.subject,'\'') SEPARATOR',') AS `selectnames`,`A`.type FROM `users_links_activitiesreports` AS `A` WHERE `A`.`page`=".$db->qstr($_GET['_employee'])." AND `A`.`subject`=".$db->qstr($_GET['_field'])." AND `A`.`schedule`=".$db->qstr($_GET['_schedule'])." AND `A`.`pub`='1' AND `A`.`del`='1' GROUP BY `A`.type");
		
		if(($rs_c) && ($rs_c->numRows() >= 1))
		{
			$receipient = array();
			$i=1;
			//$subject = $rs_c->fields['sname'].": ".date("Y-M-d");
			//$headers .= "From: ".$rs_c->fields['_from']." <CDBM@abi-hosting>" . "\r\n";
			
			while(!$rs_c->EOF)
			{
				$_SQL = $_OTHER_ROLES_DISTRICTS = "";$rolesDistrictsArr = array();
				if(isset($rs_c->fields['type']))
				{
					if($rs_c->fields['type']=="role")
					{
						$_SQL = $_SQL = "SELECT `A`.`name`,CONCAT(`A`.`name`,' ',`A`.`surname`) AS cname,`A`.id,`A`.email,`A`.cell,`A`.image FROM `users` AS `A` WHERE `A`.`type` IN (".$rs_c->fields['selectids'].") AND `A`.pub='1' AND `A`.del='1' ORDER BY `A`.name ASC";
						$rolesDistrictsArr = genericItemsArr("roles"," AND `id` IN (".$rs_c->fields['selectids'].") ");$_OTHER_ROLES_DISTRICTS .= "<br /<b><u style='color:#777;'>HR ROLES NOTIFIED</u></b><br />";
					}
					else if($rs_c->fields['type']=="district")
					{
						$_SQL = "SELECT `A`.`name`,CONCAT(`A`.`name`,' ',`A`.`surname`) AS cname,`A`.id,`A`.email,`A`.cell,`A`.image FROM `users` AS `A` WHERE `A`.`district` IN (".$rs_c->fields['selectids'].") AND `A`.pub='1' AND `A`.del='1' ORDER BY `A`.name ASC";
						$rolesDistrictsArr = genericItemsArr("districts"," AND `id` IN (".$rs_c->fields['selectids'].") ");$_OTHER_ROLES_DISTRICTS .= "<br /><b><u style='color:#777;'>DISTRICTS NOTIFIED</u></b><br />";
					}
					else if($rs_c->fields['type']=="user"){$_SQL = "SELECT `A`.`name`,CONCAT(`A`.`name`,' ',`A`.`surname`) AS cname,`A`.id,`A`.email,`A`.cell,`A`.image FROM `users` AS `A` WHERE `A`.`id` IN (".$rs_c->fields['selectids'].") AND `A`.pub='1' AND `A`.del='1' ORDER BY `A`.name ASC";}
					//print_r($rolesDistrictsArr);echo $_SQL;
					$rs = $db->Execute($_SQL);
					
					if(($rs) && ($rs->_numOfRows >= 1))
					{
						if(isset($rolesDistrictsArr)  && (is_array($rolesDistrictsArr)) && (sizeof($rolesDistrictsArr)>=1))
						{
							while(list($k,$v) = each($rolesDistrictsArr))
							{
								$_OTHER_ROLES_DISTRICTS .= "<li class='stage-rule' style='border:0px solid #ccc;text-align:left;font-size:11px;list-style:none;list-style-type:none;margin-top:6px;'><span style='background:#68ace5;padding:2px;-moz-border-radius:4px;border-radius:4px;-webkit-border-radius:4px;'>".strtoupper($v)."</span></li>";
							}
							$_OTHER_ROLES_DISTRICTS .= "<br />";
						}
						else{$_OTHER_ROLES_DISTRICTS = "";}
						//echo $_OTHER_ROLES_DISTRICTS;
						while(!$rs->EOF)
						{
							if(isset($rs->fields['email']) && (!empty($rs->fields['email'])))
							{
								$emailer->MsgHTML("<html><title>".$subject."</title><body>Hi ".$rs->fields['name'].",<br /><br />".$_OTHER_ROLES_DISTRICTS.$_body."</body></html>");
								//$rs->fields['email'] = "ericm00142@gmail.com";
								$rs->fields['email'] = "morena.maleka@ovhstudio.co.za";
								$emailer->AddAddress($rs->fields['email']);
								$emailer->Send();exit;
							}
							
							$rs->MoveNext();
						}
					}
				}
				
				$rs_c->MoveNext();
			}
		}*/
	}
}
else{echo "&nbsp;&nbsp;<strong style='font-size:14px;'>Please select all filter critetia..";}
?>