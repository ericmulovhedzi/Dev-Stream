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
	
	// --- Module Name
	
	/*$rs_modules = $db->Execute("
					SELECT `C`.id,`C`.name,`D`.id AS pid,`D`.name AS pname,`E`.name AS ppname,`F`.name AS orgname,`F`.branding_logo_main AS orglogo,`F`.branding_color_main AS orgcolor1,`F`.branding_color_sub AS orgcolor2
					FROM `pm_organograms` AS `C`
					INNER JOIN `pm_organograms` AS `C` ON `B`.id=`C`.hr_group
					INNER JOIN `ovhef_pricing_organizations` AS `F` ON `F`.organogram_id=`C`.id
					WHERE `C`.live='1' AND `C`.id=".$_GET['_module']." AND `C`.pub='1' AND `C`.del='1' AND `D`.pub='1' AND `D`.del='1' ORDER BY `D`.rank ASC,`C`.rank ASC LIMIT 1
				");
	if(($rs_modules) && ($rs_modules->numRows() >= 1))
	{
		$_MODULE_NAME[3] = $rs_modules->fields['ppname'];$_MODULE_NAME[2] = $rs_modules->fields['pname'];$_MODULE_NAME[1] = $rs_modules->fields['name'];
		
	}*/
	
	$_ORG_NAME = $_ORG_LOGO = $_TIMESHEET_DISPLAY = "";
	
	if($_SESSION['accesses']->_login['hr360'][0] == 1){$_ORG_NAME = "House of Nnyane";$_ORG_LOGO = "hom-main-logo.jpg";}
	else if($_SESSION['accesses']->_login['hr360'][0] == 2){$_ORG_NAME = "SnM Security Consultants (Pty) Ltd";$_ORG_LOGO = "snm-logo.png";}
	else if($_SESSION['accesses']->_login['hr360'][0] == 3){$_ORG_NAME = "Netshcoe Solutions";$_ORG_LOGO = "ccba-logo-main.png";}
	
	// --- Filters
	
	if(isset($_GET['_date_from']) && (!empty($_GET['_date_from'])))
	{
		//$_SQL_LIMIT .= " AND MONTH('".$_GET['_date_to'] ."') = MONTH(`A`.`date`) AND YEAR('".$_GET['_date_to'] ."') = YEAR(`A`.`date`) ";
		
		//$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime($_GET['_date_to']))."</span>";
		//$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
		
		
		$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) >= TO_DAYS('".$_GET['_date_from'] ."') ";
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		//$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime("01-".$_GET['_date_from']))."</span>";
		//$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
		$_TIMESHEET_DISPLAY .= date("jS F Y",strtotime($_GET['_date_from']));
	}
	
	
	if(isset($_GET['_date_to']) && (!empty($_GET['_date_to'])))
	{
		//$_SQL_LIMIT .= " AND MONTH('".$_GET['_date_to'] ."') = MONTH(`A`.`date`) AND YEAR('".$_GET['_date_to'] ."') = YEAR(`A`.`date`) ";
		
		//$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime($_GET['_date_to']))."</span>";
		//$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
		
		
		$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime("01-".$_GET['_date_to']))."</span>";
		$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
		$_TIMESHEET_DISPLAY .= " - ".date("jS F Y",strtotime($_GET['_date_to']));
	}
	else
	{
		$_USER_DISPLAY .= " <i style='color:#333;'>On:</i> <span style='color:#DD7000;'>".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()))."</span>";
		
	}
	
	if(isset($_GET['_employee']) && ($_GET['_employee'] >= 1))
	{
		$_SQL_LIMIT .= " AND `A`.`employee`= '".$_GET['_employee']."' ";
		
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
		
	$fname = "Time n Attendance - ".$_MODULE_NAME[1];
	$fname_payslip = "Payslip - ".$_MODULE_NAME[1];
	
	$_XLS_DOC = array(array(""), array("Reporting on Module: ",$_MODULE_NAME[1]), array("Parent: ",$_MODULE_NAME[2]), array("Timesheet for",$_FIELD_NAME),array(""));
	$_XLS_STYLE = array(array(""),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array(""));
	
	$_XLS_DOC_TMP[] = $fname;$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	
	$colspan = 12;
	$_body = "
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:right;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#aaa;text-indent:2px;font-size:12px;font-weight:normal;'><i>Reporting on Module: <span style='color:#555;'>Time Attendance</span>.</i>&nbsp;</td>	     
		</tr>
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:left;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b>Timesheet for Employee <span style='color:#e62899;'>".$_MODULE_NAME[1]."</span></b></td>	     
		</tr>
		<tr>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Employee Name</b></td>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Geo-location</b></td>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Status </b></td>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Start Time</b></td>
			<td colspan='2' style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:center;'>&nbsp;<b>End Time</b></td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Address&nbsp;</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Signed-in Time&nbsp;</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Signed-out Time&nbsp;</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Arrival Status&nbsp;</td>";
			
	
	$totalArr['reject'] = $totalArr['approve'] = $totalArr['total'] = 0;
	
	$_XLS_DOC_TMP[] = "Rejected";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	$_XLS_DOC_TMP[] = "Approved";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	$_XLS_DOC_TMP[] = "Total";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	
	$_body .= "
			<td style='background:#c3daef;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Date</td>
			<!--<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Minutes Early&nbsp;</td>-->
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Minutes Late</td>	
		</tr>";
		
	$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_DOC_TMP = array();$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_STYLE_TMP = array();//= "hdr_bold_fz10_bgred_colorred";
	
	$_TOTAL = 0;
	
	// ---
	
	$_IVENTORY_MODULE = $_GET['_employee']; 
	$_SQL_INVENTORY_SELECT = $_SQL_INVENTORY_ORDER_BY = "";
	//echo $_SQL_LIMIT="";
	//echo $_SQL_LIMIT;
	$rs =$db->Execute("
			  SELECT
				DATE_FORMAT(`A`.date,'%d/%m/%Y') AS `date`,DAY(`A`.date) AS `day`,DATE_FORMAT(`A`.date,'%W') AS `day_formated`,
				`A`.`employee`,`A`.`department`,
				`A`.`clockin_status`,
				`A`.`gps_lat`,
				`A`.`gps_lon`,
				`A`.`start_time`,`A`.`end_time`,
				`A`.`signed_in_time`,`A`.`signed_out_time`,
				`A`.`arrival_status`,
				`A`.`minutes_early`,`A`.`minutes_late`,
				`A`.picture,`A`.id,`A`.date,
				CONCAT(`B`.name,' ',`B`.surname) AS `cname`,
				`A`.province,`A`.city,`A`.suburb
			  FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_clockin` AS `A`
			  LEFT JOIN `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `B` ON `B`.id=`A`.employee 
			  WHERE `A`.pub=1 AND `A`.del=1 ".$_SQL_LIMIT."
			  ORDER BY `A`.date ASC
			  ");
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
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'>".substr($rs->fields['gps_lat'],0,6)." : ".substr($rs->fields['gps_lon'],0,5)."</b>&nbsp;</td>";
				
				
			$_body .= "
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".$rs->fields['clockin_status']."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".date('h:i A', strtotime($rs->fields['start_time']))."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".date('h:i A', strtotime($rs->fields['end_time']))."</b>&nbsp;</td>
					<td colspan='2' style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='left'>". (isset($rs->fields['province']) && ($rs->fields['province']!="0.00")?$rs->fields['province'].", ".$rs->fields['suburb']:"")."&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".(isset($rs->fields['signed_in_time']) && ($rs->fields['signed_in_time']!="00:00:00")?date('h:i A', strtotime($rs->fields['signed_in_time'])):"")."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".(isset($rs->fields['signed_out_time']) && ($rs->fields['signed_out_time']!="00:00:00")?date('h:i A', strtotime($rs->fields['signed_out_time'])):"")."</b>&nbsp;</td>
					
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:".$islate_color.";' align='center'><b>".$rs->fields['arrival_status']."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#c3daef;' align='center'><b></b>".substr($rs->fields['date'],0,11)."</td>
					<!--<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#FBE3E4;' align='center'><b>".(isset($rs->fields['minutes_early']) && ($rs->fields['minutes_early']!="0.00")?$rs->fields['minutes_early']:"")."</b>&nbsp;</td>-->
					
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#E6EFC2;' align='center'><b>".(isset($rs->fields['minutes_late']) && ($rs->fields['minutes_late']!="0.00")?$rs->fields['minutes_late']:"")."</b>&nbsp;</td>
					";
					
					
			$_TOTAL_HOURS = $_TOTAL_HOURS + ($rs->fields['total_hours']);
							
			//$totalArr['reject'] += $rs->fields['reject'];
			//$totalArr['approve'] += $rs->fields['approve'];
			//$totalArr['total'] += $rs->fields['total'];
			
			//$_XLS_DOC_TMP[] = $rs->fields['reject'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['approve'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['total'];$_XLS_STYLE_TMP[] = "";
			
			$_DATA_ARR[] = array
					(
					     $_INDEX.". ".ucfirst(strtolower($rs->fields['cname'])),
					     substr($rs->fields['gps_lat'],0,6)." : ".substr($rs->fields['gps_lon'],0,5),
					     $rs->fields['clockin_status'],
					     date('h:i A', strtotime($rs->fields['start_time'])),
					     date('h:i A', strtotime($rs->fields['end_time'])),
					     (isset($rs->fields['signed_in_time']) && ($rs->fields['signed_in_time']!="00:00:00")?date('h:i A', strtotime($rs->fields['signed_in_time'])):""),
					     (isset($rs->fields['signed_out_time']) && ($rs->fields['signed_out_time']!="00:00:00")?date('h:i A', strtotime($rs->fields['signed_out_time'])):""),
					     ucfirst(strtolower($rs->fields['arrival_status'])),
					     substr($rs->fields['date'],0,11),
					     (isset($rs->fields['minutes_early']) && ($rs->fields['minutes_early']!="0.00")?$rs->fields['minutes_early']:""),
					     (isset($rs->fields['minutes_late']) && ($rs->fields['minutes_late']!="0.00")?$rs->fields['minutes_late']:""),
					     (isset($rs->fields['province']) && ($rs->fields['province']!="0.00")?$rs->fields['province'].", ".$rs->fields['suburb']:"")
					);
			
					     //(isset($rs->fields['province']) && ($rs->fields['province']!="0.00")?$rs->fields['province'].", ".$rs->fields['city'].", ".$rs->fields['suburb']:"")
			//$_DATA_ARR[] = array($rs->fields['name'],$rs->fields['date'],$rs->fields['day_formated'],$rs->fields['time_in'],$rs->fields['time_out'],$rs->fields['_islate'],"","","",$rs->fields['total_hours']);
			
			
			
			
			$_body .= "
				</tr>";
				
			//$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_DOC_TMP = $_XLS_STYLE_TMP = array();
			$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_DOC_TMP = $_XLS_STYLE_TMP = array();
			
			
			$_INDEX++;
			$rs->MoveNext();
		}
		
	}else{echo "Error: ".$db->errorMsg();}
	
	$_XLS_DOC_TMP = array();
	
	$_XLS_DOC_TMP[] = "TOTALS:";
	$_body .= "
		<tr>
			<td colspan='".($colspan-1)."' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>&nbsp;</td>";
		
	$_XLS_DOC_TMP[] = $totalArr['reject'];$_XLS_DOC_TMP[] = $totalArr['approve'];$_XLS_DOC_TMP[] = $totalArr['total'];
	
	$_body .= "
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#008000;font-weight:bold;font-size:11px;' align='center'>&nbsp;</td>
		</tr>";
	
	$_XLS_DOC[] = array();$_XLS_DOC[] = $_XLS_DOC_TMP;//$_XLS_STYLE[] = "hdr_bold_fz10_bgred_colorred";

	
	$_body = "<table width='100%' class='main' border='0' valign='top' style='border:1px solid #e5e5e5;background:#fff;clear:both;margin-top:5px;font-family: Arial, Helvetica, Verdana, sans-serif;font-size:11px;'>".$_body."</table>";
	
	if(!(isset($_GET['_disp']) && ($_GET['_disp'] >= 1)))
	{
		if(isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1))
		{
			$_body = "<center><a style='text-decoration:none;' href='../../docs/dataobjects/activities/tmp/".$fname.".xls'><span style='background-color:#ddd;border:2px solid #00A000;padding:3px;color:#008000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download Excel Report</span></a></center>".$_body;
		}
		
		$_GET['is_pdf']=1;
		if(isset($_GET['is_pdf']) && ($_GET['is_pdf'] >= 1))
		{
			$_body = "<center>
				<a style='text-decoration:none;' target='_blank' href='../../exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_employee']." - ".$_TIMESHEET_DISPLAY.".pdf'><span style='background-color:#ddd;border:2px solid #008000;padding:3px;color:#008000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download PDF Timesheet</span>
				</a> 
			".$_body;
		}
		
		echo $_body;
	}
	
	if((isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1)) || (isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1)) || (isset($_GET['is_pdf']) && ($_GET['is_pdf'] >= 1)))
	{
		// -- Write Email to temporary folder
		/*
		$_XLS->addArray($_XLS_DOC,$_XLS_STYLE);
		
		if(isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1))
		{
			//$_XLS->writeExcelToBrowser($fname.".xls");
			$_XLS->saveExcelToFile("../../docs/dataobjects/activities/tmp/".$fname.".xls");
			
			exit;
		}
		else
		{
			$_XLS->saveExcelToFile("../../docs/dataobjects/activities/tmp/".$fname.".xls");
		}*/
		
		// -- PDF Generation
		//echo "../../docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname);
		//@require_once("../../docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname));
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
		
		
		if(((isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1))))
		{//echo $_GET['_isemail']."---";
		// _____________________________________________________ Page # 1 - Cover Page __________________________________________________
		
		
		$p = 0;
		
		$pdf->AddPage('L');$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$pdf->SetDrawColor(0);$pdf->SetLineWidth(0.1);//$pdf->Line($_HEIGHT/2,0,$_HEIGHT/2,$_WIDTH); 
		
		$h=0;$w = 155;
		$pdf->SetFillColor(155,155,155);$pdf->SetTextColor(0);$pdf->SetFont('arial','B',18);
		//$pdf->SetAlpha(0.25);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h);$pdf->MultiCell($_P_WIDTH/2.5,24,"","","C",true);
		//$pdf->SetAlpha(1);
		$pdf->SetFillColor(75,75,75);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+24);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+9.5);$pdf->Cell($_P_WIDTH/2.5,6,"Real-time Reporting","",0,"C",false);
		$pdf->SetFont('arial','',11);$pdf->SetTextColor(95);$pdf->SetXY($w+3.5+($_P_WIDTH/2.5),$h+17.55);$pdf->Cell($_P_WIDTH/2.61,6,"( Monitor & control productive hours ) ",0,0,"C",false);
		
		$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w+2.5+($_P_WIDTH/2.5),$h+17.55+19);$pdf->Cell($_P_WIDTH/2.61,6,"Employee: ".ucwords(strtolower($_EMPLOYEE_NAME)),"LB",0,"L",false);
		
		
		$h = $_START_TOP;$w = $_START_LEFT;
		
		    $pdf->SetTextColor(0);$pdf->SetFont('arial','',34);
		//$pdf->SetXY(0,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"OVH IoT: Manufacturing \nDivision",0,"C");
		$pdf->SetXY(70-2,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"Attendance Status\nReport",0,"L");
		
		$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$_ORG_LOGO,10,13.5,55,0,'','');
				
		$h+=8*2.25;
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(0);$pdf->Line(70,$h-1,$_HEIGHT-6.0,$h-1);
		
		$h+=8*2.65;$center = 65.5;
		
		
		// --- --- --- Header
		
		$w = $_START_LEFT-15.5;//$h+=6*6.5;
		
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(39,44,49);$pdf->SetTextColor(255);
		
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->MultiCell(40,6,"Name",1,'L',true);
		$pdf->SetXY($w+40,$h);$pdf->MultiCell(20,6,"Date",1,'C',true);
		$pdf->SetXY($w+40+20,$h);$pdf->MultiCell(15,6,"Status",1,'C',true);
		$pdf->SetXY($w+40+20+15,$h);$pdf->MultiCell(20,6,"Start Time",1,'C',true);
		$pdf->SetXY($w+40+20+15+20,$h);$pdf->MultiCell(20,6,"End Time",1,'C',true);
		$pdf->SetXY($w+40+20+15+20+20,$h);$pdf->MultiCell(30,6,"Signed-in Time",1,'C',true);
		$pdf->SetXY($w+40+20+15+20+20+30,$h);$pdf->MultiCell(30,6,"Signed-out Time",1,'C',true);
		$pdf->SetXY($w+40+20+15+20+20+30+30,$h);$pdf->MultiCell(30+25,6,"A",1,'L',true);
		$pdf->SetXY($w+50+40+15+20+20+30+30+25,$h);$pdf->MultiCell(25,6,"Geo-Location",1,'C',true);
		$pdf->SetXY($w+50+40+15+20+20+30+30+25+25,$h);$pdf->MultiCell(25,6,"Arrival Status",1,'C',true);
		//$pdf->SetXY($w+50+40+15+20+20+30+30+25+25,$h);$pdf->MultiCell(25,6,"Minutes Late",1,'C',true);
		
		$h+=6;
		$pdf->SetFont('arial','',10);$_TOTAL = 0;$pdf->SetTextColor(39,44,49);
		
		
				
				// _____________________________________________________ ::: __________________________________________________
				
				$_INDEX = 0;$_CUT_OFF = 25;
				foreach($_DATA_ARR as $k=>$v)
				{
						
					$pdf->SetXY($w,$h);$pdf->MultiCell(40,5,$v[0],1,'L');$pdf->SetFont('arial','',9.0);$pdf->SetXY($w+40,$h);$pdf->MultiCell(20,5,$v[8],1,'L');$pdf->SetFont('arial','',10);
					$pdf->SetXY($w+60,$h);
					
						if($v[7] == "Early"){$pdf->SetFillColor(128,246,166);}else if($v[7] == "Late"){$pdf->SetFillColor(244,188,191);}else{$pdf->SetFillColor(255);}
						
					$pdf->MultiCell(15,5,$v[2],1,'C',true);
					$pdf->SetXY($w+75,$h);$pdf->MultiCell(20,5,$v[3],1,'C');$pdf->SetXY($w+95,$h);$pdf->MultiCell(20,5,$v[4],1,'L');
					$pdf->SetXY($w+115,$h);$pdf->MultiCell(30,5,$v[5],1,'L');$pdf->SetXY($w+145,$h);$pdf->MultiCell(30,5,$v[6],1,'L');$pdf->SetXY($w+145+30,$h);$pdf->MultiCell(30+25,5,$v[11],1,'L');
					$pdf->SetXY($w+205+25,$h);$pdf->SetFillColor(128,246,166);
					
						if($v[7] == "Early"){$pdf->SetFillColor(128,246,166);}else if($v[7] == "Late"){$pdf->SetFillColor(244,188,191);}else{$pdf->SetFillColor(255);}
						
					$pdf->MultiCell(25,5,$v[1],1,'C',true);
					$pdf->SetXY($w+230+25,$h);$pdf->MultiCell(25,5,$v[7],1,'C');//$pdf->SetXY($w+255,$h);$pdf->MultiCell(25,5,$v[10],1,'C');
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
				
				// --- Footer
				
			}
		
		// ______________________________________________ Timesheet Cover Page #2 __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		
		$h=0;$w = 73;
		$pdf->SetFillColor(155,155,155);$pdf->SetTextColor(0);$pdf->SetFont('arial','B',32);
		//$pdf->SetAlpha(0.45);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h);$pdf->MultiCell($_P_WIDTH/2.5,24,"","","C",true);
		//$pdf->SetAlpha(1);
		$pdf->SetFillColor(75,75,75);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+9.0);$pdf->Cell($_P_WIDTH/2.5,6,"Timesheet","",0,"C",false);
		$pdf->SetFont('arial','',11.5);$pdf->SetTextColor(95);$pdf->SetXY($w+3+($_P_WIDTH/2.5) - 7.5,$h+17.75);$pdf->Cell($_P_WIDTH/2.61,6,"( Timesheet Book with 238 Weeks )","",0,"C",false);
		$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+24);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
		
		$h = $_START_TOP;$w = $_START_LEFT;
		
		    $pdf->SetTextColor(0);$pdf->SetFont('arial','',16);
		
		$pdf->SetXY(10-2,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"Date: ".$_TIMESHEET_DISPLAY,0,"L");
		
		$h+=8*2.25;
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(0);$pdf->Line(10,$h-1,$_HEIGHT-6.0,$h-1);
		
		$_STATUS = 0;
		
		$pdf->SetDrawColor(33,134,230);
		$pdf->SetTextColor(33,134,230);
		
		$h = 249+13;
		
		$pdf->SetFillColor(81,86,89);$pdf->SetDrawColor(81,86,89);$pdf->SetLineWidth(1.5);
		
		//$pdf->Line(0,$_HEIGHT/2,$_WIDTH/2-0.8,$_HEIGHT/2);
		
		
				$pdf->SetTextColor(0);
				$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$_ORG_LOGO,9,3.5,55,0,'','');
				
		// --- --- --- NEW SECTION
		
		$pdf->SetLineWidth(0.1);
		
		$h = $_HEIGHT/2;$w =  $w + 2.5;
		//$pdf->Polygon(array($_WIDTH,-7.5,$_WIDTH,$_HEIGHT+7.5,$_WIDTH/3,$_HEIGHT/2),'F');
		
		// --- --- --- NEW SECTION
		
		$w=120;$h = 40.0;
		
		
		// --- --- --- Description of work
		
		$w = $_START_LEFT-15.5;//$h+=6*6.5;
		
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(39,44,49);$pdf->SetTextColor(255);
		
		$pdf->SetFont('arial','B',9.5);
		$pdf->SetXY($w,$h);$pdf->MultiCell(25,8,"Shift Date",1,'C',true);
		$pdf->SetXY($w+25,$h);$pdf->MultiCell(25,8,"Shift Day",1,'C',true);
		$pdf->SetXY($w+25+25,$h);$pdf->MultiCell(20,8,"Time In",1,'C',true);
		$pdf->SetXY($w+25+25+20,$h);$pdf->MultiCell(20,8,"Lunch",1,'C',true);
		$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(20,8,"Time Out",1,'C',true);$pdf->SetXY(5+$w+25+25+20+20+20,$h);$pdf->MultiCell(20,8,"Overtime",1,'C',true);
		$pdf->SetXY(5+$w+25+25+20+20+20+20,$h);$pdf->MultiCell(30,8,"Target (Hours)",1,'C',true);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,8,"Total Hours",1,'C',true);
		
		$h+=8;
		$pdf->SetFont('arial','',9.5);$_TOTAL = 0;$pdf->SetTextColor(39,44,49);$pdf->SetFillColor(245);
		
		$__SQL_LIMIT = "
				  SELECT
					DATE_FORMAT(`A`.date,'%d/%m/%Y') AS `date`,DAY(`A`.date) AS `day`,DATE_FORMAT(`A`.date,'%W') AS `day_formated`,
					`A`.`employee`,
					`A`.`clockin_status`,
					`A`.`start_time`,`A`.`end_time`,
					`A`.`signed_in_time`,`A`.`signed_out_time`,
					CONCAT(`B`.name,' ',`B`.surname) AS `cname`,
					GROUP_CONCAT(`A`.date) AS `concatenated_dates`,
					GROUP_CONCAT(`A`.`clockin_status`) AS `concatenated_clockin_status`,
					GROUP_CONCAT(`A`.`signed_in_time`) AS `concatenated_signed_in_times`,
					GROUP_CONCAT(`A`.`signed_out_time`) AS `concatenated_signed_out_times`,
					
					CONCAT(`B`.name,' ',`B`.surname) AS `cname`,
					`B`.`supervisor`,
					`B`.shift_board_employee_shift_rate,
					`B`.shift_board_employee_rate_basis,
					`B`.shift_board_employee_rate_type,
					`B`.`tax_paye_no`,`B`.`tax_personal_income_tax_no`,
					`B`.`shift_board_employee_start_time`,
					`B`.`shift_board_employee_end_time`
					
				  FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_clockin` AS `A`
				  LEFT JOIN `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `B` ON `B`.id=`A`.employee 
				  WHERE `A`.pub=1 AND `A`.del=1 ".$_SQL_LIMIT."
				  ORDER BY `A`.date ASC
				  ";
		$_rs =$db->Execute($__SQL_LIMIT);
		if(($_rs) && ($_rs->numRows() >= 1))
		{
			$supervisorsArr = parentsArr($_SESSION['accesses']->_login['hr360'][0]," AND `B`.`position` IN ('36') ");
			$_SUPERVISOR = isset($supervisorsArr[$_rs->fields['supervisor']]) ? $supervisorsArr[$_rs->fields['supervisor']] : "";
			$_EMPLOYEE_RATE = $_rs->fields['shift_board_employee_shift_rate'];
			$_EMPLOYEE_RATE_BASIS = $_rs->fields['shift_board_employee_rate_basis'];
			$_EMPLOYEE_RATE_TYPE = $_rs->fields['shift_board_employee_rate_type'];
			$_EMPLOYEE_PAYE = $_rs->fields['tax_personal_income_tax_no']; //$_rs->fields['tax_paye_no'];
			$_EMPLOYEE_NAME = $_rs->fields['cname'];
			
			//$_HOURS_TOTAL = 0; $_INDEX = 1;
			$___DATA_ARR = array();$_clockkedDateCurrent = "";$_II = 1;
			while(!$_rs->EOF)
			{//echo "date: ".$_rs->fields['date']."<br />";
				$clockinArr = explode(",",$_rs->fields['concatenated_clockin_status']);//print_r($clockinArr);
				$clockinInsArr = explode(",",$_rs->fields['concatenated_signed_in_times']);
				$clockinOutsArr = explode(",",$_rs->fields['concatenated_signed_out_times']);
				$clockinDatesArr = explode(",",$_rs->fields['concatenated_dates']);//print_r($clockinDatesArr);
				
				if(isset($clockinArr) && is_array($clockinArr))
				//if(isset($clockinDatesArr) && is_array($clockinDatesArr))
				{
					//if(sizeof($clockinArr) % 2==0) // Even numbered sized
					if(true)
					{
						$_CLOCK_START = $_CLOCK_END = $_INDEX = 0;
						
						foreach ($clockinArr as $k => $state)
						{
							//echo $_clockkedDateCurrent." === ".date('Y:m:d', strtotime($clockinDatesArr[$k]))."<br />";
							if($_clockkedDateCurrent != date('Y:m:d', strtotime($clockinDatesArr[$k])))
							{
								if($state=="OUT")
								{
									//$_CLOCK_START = $_CLOCK_END = 0;
									//$_INDEX = 0;
									//$state = "";
									$_clockkedDateCurrent = date('Y:m:d', strtotime($clockinDatesArr[$k]));
									//continue;
								}
							}
							
							//if(($state=="IN") && ($_clockkedDateCurrent != $clockinDatesArr[$k]))
							if(($state=="IN"))
							{
								$_CLOCK_START = $clockinInsArr[$k];
								//$_INDEX = 0;
								//$_clockkedDateCurrent = $clockinDatesArr[$k];
								//echo "IN ::: ".date('Y:m:d', strtotime($clockinDatesArr[$k]))."<br />";
							}
							
							//echo "STATE: ".$state."<br />";
							
							if(($state=="OUT") && ($_INDEX >=1))
							{//echo date('Y:m:d', strtotime($clockinDatesArr[$k]))." ".$_CLOCK_START;
								$_CLOCK_END = $clockinOutsArr[$k];
								$dateTimeObject1 = date_create(date('Y:m:d', strtotime($clockinDatesArr[$k]))." ".$_CLOCK_START);$dateTimeObject2 = date_create(date('Y:m:d', strtotime($clockinDatesArr[$k]))." ".$_CLOCK_END); 
								$interval = date_diff($dateTimeObject1, $dateTimeObject2); $interval->format('%R%a days');
								$_MINUTES_EARLY = $interval->days * 24 * 60;
								$_MINUTES_EARLY += $interval->h * 60;
								$_MINUTES_EARLY += $interval->i;
								
								$_HOURS_TIMESHEET = floor($_MINUTES_EARLY / 60).':'.($_MINUTES_EARLY -   floor($_MINUTES_EARLY / 60) * 60);
								
								//echo $clockinDatesArr[$k].": ".$_CLOCK_START." - ".$_CLOCK_END." = ".$_HOURS_TIMESHEET." (".$_MINUTES_EARLY." mins)<br />";
								
								$_dateTimeObject1 = date_create(date('Y:m:d', strtotime($clockinDatesArr[$k]))." ".$_rs->fields['shift_board_employee_start_time']);$_dateTimeObject2 = date_create(date('Y:m:d', strtotime($clockinDatesArr[$k]))." ".$_rs->fields['shift_board_employee_end_time']); 
								$_interval = date_diff($_dateTimeObject1, $_dateTimeObject2); $_interval->format('%R%a days');
								$__MINUTES_EARLY = $_interval->days * 24 * 60;
								$__MINUTES_EARLY += $_interval->h * 60;
								$__MINUTES_EARLY += $_interval->i;
								
								$__HOURS_TIMESHEET = (floor($__MINUTES_EARLY / 60) - $_POLICY_LUNCH_BREAK_HOUR).':'.($__MINUTES_EARLY -   floor($__MINUTES_EARLY / 60) * 60);
								
								//echo $clockinDatesArr[$k].": ".$_rs->fields['shift_board_employee_start_time']." - ".$_rs->fields['shift_board_employee_end_time']." = ".$__HOURS_TIMESHEET." (".$__MINUTES_EARLY." mins)<br />";
								//echo date('d/m/Y', strtotime($clockinDatesArr[$k]))." - ".$_HOURS_TIMESHEET." x".$__HOURS_TIMESHEET."<br />";
								$___DATA_ARR[date('d/m/Y', strtotime($clockinDatesArr[$k]))][] = array
										(
										     date('d/m/Y', strtotime($clockinDatesArr[$k])),
										     date('l', strtotime($clockinDatesArr[$k])),
										     date('h:i A', strtotime($_CLOCK_START)),
										     date('h:i A', strtotime($_CLOCK_END)),
										     "",
										     "",
										     0,
										     $_HOURS_TIMESHEET,
										     $__HOURS_TIMESHEET
										);
								
			
								/*$pdf->SetXY($w,$h);$pdf->MultiCell(25,6,date('d/m/Y', strtotime($clockinDatesArr[$k])),1,'C',true);$pdf->SetXY($w+25,$h);$pdf->MultiCell(25,6,date('l', strtotime($clockinDatesArr[$k])),1,'C',true);$pdf->SetXY($w+25+25,$h);$pdf->MultiCell(20,6,date('h:i A', strtotime($_CLOCK_START)),1,'C');$pdf->SetXY($w+25+25+20,$h);$pdf->MultiCell(20,6,date('h:i A', strtotime($_CLOCK_END)),1,'C');
								$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(20,6,"",1,'C');$pdf->SetXY(5+$w+25+25+20+20+20,$h);$pdf->MultiCell(20,6,"",1,'C');$pdf->SetXY(5+$w+25+25+20+20+20+20,$h);$pdf->MultiCell(30,6,"8:00",1,'C',false);
								$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,"8:00",1,'C',true);
								$h+=6;*/
								
								$_CLOCK_START = $_CLOCK_END = 0;
							}
							$_INDEX++;
						}
					}
					else  // Odd numbered sized
					{
						
					}
				}
				
				$_rs->MoveNext();
			}
			
			$tMin_TOTAL = $tHour_TOTAL = 0;
						
			//print_r($___DATA_ARR);
			if(isset($___DATA_ARR) && is_array($___DATA_ARR))
			{
						
				foreach ($___DATA_ARR as $k => $v)
				{
					//echo $k." x:x ".$v."<br />"; // -----------------------------
					if(isset($v) && is_array($v))
					{
						$START_TIME = $END_TIME = "";
						$_BREAKS = sizeof($v) - 1;
						$tMin = $tHour = 0;
						$_i = 0;
						foreach ($v as $_k => $_v)
						{
							if(isset($_v) && is_array($_v))
							{
								//echo ".    ".$_k." -:- ".$_v[2]." - ".$_v[3]." = ".$_v[7].", bks:".$_BREAKS."<br />"; // -----------------------------
								if($_i==0){$START_TIME = $_v[2];}$_i++;
								$END_TIME = $_v[3];
								
								$time         = explode(':' , $_v[7]);
								$tMin     +=   $time[1];
								$tHour    +=   $time[0];
								
							}
						}
						
						$tHour  += floor ( $tMin / 60   ) ;
						$tMin   =   $tMin % 60  ;
						//echo $tHour.':'.$tMin;
						$_EMPOYEE_TARGET_HOURS = $v[0][8];
						//$_EMPOYEE_TARGET_HOURS = ($v[0][8] >= 1) ? $v[0][8] - 1 : $v[0][8];
						
						$pdf->SetXY($w,$h);$pdf->MultiCell(25,6,$_II.". ".$v[0][0],1,'L',true);$pdf->SetXY($w+25,$h);$pdf->MultiCell(25,6,$v[0][1],1,'C',true);$pdf->SetXY($w+25+25,$h);$pdf->MultiCell(20,6,$START_TIME,1,'C');$pdf->SetXY($w+25+25+20,$h);$pdf->MultiCell(20,6,$_BREAKS,1,'C');
						$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(20,6,$END_TIME,1,'C');$pdf->SetXY(5+$w+25+25+20+20+20,$h);$pdf->MultiCell(20,6,"",1,'C');$pdf->SetXY(5+$w+25+25+20+20+20+20,$h);$pdf->MultiCell(30,6,$v[0][8],1,'C',false);
						$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,$tHour.':'.$tMin,1,'C',true);
						$h+=6;
						$_II++;
						
						$time_TOTAL         = explode(':' , $tHour.':'.$tMin);
						$tMin_TOTAL     +=   $time_TOTAL[1];
						$tHour_TOTAL    +=   $time_TOTAL[0];
					}
				}
			}
		}
		
		$tHour_TOTAL  += floor ( $tMin_TOTAL / 60   ) ;
		$tMin_TOTAL   =   $tMin_TOTAL % 60  ;
		
		
		$myTime = strtotime($_GET['_date_to']);  // Use whatever date format you want
		
		$date = DateTime::createFromFormat("Y-m-d", $_GET['_date_to']);
		//echo $date->format("Y");

		//$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $date->format("d"), $date->format("Y")); // 31
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $date->format("m"), $date->format("Y"));
		
		$workDays = 0;
		
		while($daysInMonth > 0)
		{
		    $day = date("D", $myTime); // Sun - Sat
		    if($day != "Sun" && $day != "Sat")
			$workDays++;
		
		    $daysInMonth--;
		    $myTime += 86400; // 86,400 seconds = 24 hrs.
		}


 
		$pdf->SetFont('arial','B',10);
		//$pdf->SetXY($w,$h);$pdf->MultiCell(50,6," Total Weekly Hours",1,'L',true);
		$pdf->SetXY($w,$h);$pdf->MultiCell(50,6," Total Hours",1,'L',true);
		$pdf->SetFillColor(255);$pdf->SetXY($w+25+25,$h);$pdf->MultiCell(20+90+5,6,"",1,'C',true);
		$pdf->SetFillColor(245);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,$tHour_TOTAL.':'.$tMin_TOTAL,1,'C',true);
		$h+=6;$pdf->SetFillColor(255);
		$pdf->SetXY($w,$h);$pdf->MultiCell(25+20+90+30+30,6," Notes:",1,'L',true);
		$h+=6;
		
		$rs___ = $db->Execute
		("
			SELECT `A`.id,`A`.comment,`A`.date FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_clockin_absentia` AS `A` WHERE `A`.pub=1 AND `A`.del=1 AND `A`.employee=".$_GET['_employee']."
			;
		");
		
		if(($rs___) && ($rs___->_numOfRows >= 1))
		{
			while(!$rs___->EOF)
			{
				
				$pdf->SetFont('arial','I',9);$pdf->SetXY($w,$h);$pdf->MultiCell(25+20+90+30+30,6,substr($rs___->fields['date'],0,10).": ".$rs___->fields['comment'],1,'L',true);
				$h+=6;
				
				$rs___->MoveNext();
			}
		}
		
		//$_EMPOYEE_TARGET_HOURS = ($_EMPOYEE_TARGET_HOURS >= 1) ? $_EMPOYEE_TARGET_HOURS - 1 : $_EMPOYEE_TARGET_HOURS; // 
			
		$_hours_days = $_hours_day = $_hours_days_display = "";$_hours_days_display_total = "";
		$_TOTAL_SALARY_WAGES = 0;
		
		if($_EMPLOYEE_RATE_BASIS == 1)
		{
			$_hours_days="Hours";$_hours_day="Hour";
						
			$_hours_days_display = "Days";
			$_hours_days_display_total = " (R".$_EMPLOYEE_RATE." * ".round($tHour_TOTAL)."hrs";
			$_TOTAL_SALARY_WAGES = ($_EMPLOYEE_RATE*$tHour_TOTAL);
		}
		else if($_EMPLOYEE_RATE_BASIS == 2)
		{
			$_hours_days="Days";$_hours_day="Day";
			
			$_hours_days_display = " (".$tHour_TOTAL.':'.$tMin_TOTAL."hrs / ".$_EMPOYEE_TARGET_HOURS." = ".round($tHour_TOTAL/$_EMPOYEE_TARGET_HOURS,1).")";
			$_hours_days_display_total = " (R".$_EMPLOYEE_RATE." * ".round($tHour_TOTAL/$_EMPOYEE_TARGET_HOURS)."days)";
			$_TOTAL_SALARY_WAGES = ($_EMPLOYEE_RATE*round($tHour_TOTAL/$_EMPOYEE_TARGET_HOURS));
		}
		
		$workDays = 22;
		
		$pdf->SetXY($w,$h);$pdf->MultiCell(25+20+90+30+30,6," ",1,'L',true);
		$h+=6;$pdf->SetFont('arial','',10);$pdf->SetFillColor(245);
		$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(40+30,6," Rate Per ".$_hours_day,1,'L');$pdf->SetFont('arial','B',10);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,"R".$_EMPLOYEE_RATE,1,'R',true);
		$h+=6;$pdf->SetFont('arial','',10);
		$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(40+30,6," Target Work Hours (".$workDays."days * ".$_EMPOYEE_TARGET_HOURS."hrs)",1,'L');$pdf->SetFont('arial','B',10);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,$workDays*$_EMPOYEE_TARGET_HOURS,1,'R',true);
		$h+=6;$pdf->SetFont('arial','',10);
		$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(40+30,6," Total Work Hours",1,'L');$pdf->SetFont('arial','B',10);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,$tHour_TOTAL.':'.$tMin_TOTAL,1,'R',true);
		$h+=6;$pdf->SetFont('arial','',10);
		
		$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(40+30,6," Total Work ".$_hours_days.$_hours_days_display,1,'L');$pdf->SetFont('arial','B',10);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,round($tHour_TOTAL/$_EMPOYEE_TARGET_HOURS),1,'R',true);
		$h+=6;$pdf->SetFont('arial','',10);
		$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(40+30,6," PAYE Tax (@ 18%, Ref: ".$_EMPLOYEE_PAYE.")",1,'L');$pdf->SetFont('arial','B',10);$pdf->SetFillColor(244,188,191);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,'R0.00',1,'R',true);$pdf->SetFillColor(255);
		$h+=6;$pdf->SetFont('arial','',10);
		$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(40+30,6," Total Pays ".$_hours_days_display_total,1,'L');$pdf->SetFont('arial','B',10);$pdf->SetFillColor(128,246,166);$pdf->SetXY(5+$w+25+25+20+20+20+20+30,$h);$pdf->MultiCell(30,6,"R ".number_format(round($_TOTAL_SALARY_WAGES,2),2,"."," "),1,'R',true);$pdf->SetFillColor(255);
		$h+=6;
		$h+=6;$pdf->SetFillColor(255);
		$pdf->SetXY($w,$h);$pdf->MultiCell(40,6,"Employee Name:",0,'L',true);$pdf->SetXY($w+30,$h-2);$pdf->MultiCell(50,6,ucwords(strtolower($_EMPLOYEE_NAME)),"B",'L',true);
		$pdf->SetXY($w+82.5,$h);$pdf->MultiCell(40,6,"Supervisor:",0,'L',true);$pdf->SetXY($w+32+72.5,$h-2);$pdf->MultiCell(60.5,6,ucwords(strtolower($_SUPERVISOR)),"B",'L',true);
		$h+=7;
		$pdf->SetXY($w,$h);$pdf->MultiCell(40,6,"Employee Signature:",0,'L',true);$pdf->SetXY($w+36.5,$h-2);$pdf->MultiCell(43.5,6,"","B",'L',true);
		$pdf->SetXY($w+82.5,$h);$pdf->MultiCell(40,6,"Supervisor Signature:",0,'L',true);$pdf->SetXY($w+32+89.0,$h-2);$pdf->MultiCell(44.0,6,"","B",'L',true);
		$h+=7;
		
		// --- Footer ---
		
		$h = 241.5;
		
		//$pdf->Image(ROOTPATH."images/organizations/spiderblack-logo-large-c.png",93.25,$h+27.5,30.5,0,'','');$h +=8*1.0;
		$pdf->SetFont('arial','',10);$pdf->SetTextColor(125);$pdf->Text(130,$h+47.4,$_ORG_NAME." (PTY) Ltd");
						
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
		
		$emailer->AddAttachment("../../docs/dataobjects/activities/tmp/".$fname.".xls",$fname.".xls");
		$emailer->AddAttachment("../../docs/dataobjects/activities/tmp/".$fname.".pdf",$fname.".pdf");
		
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