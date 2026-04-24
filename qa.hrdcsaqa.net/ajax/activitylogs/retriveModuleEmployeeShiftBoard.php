<?
set_time_limit(0);
ini_set('memory_limit','-1');
//error_reporting(E_ALL);
 
require_once('../../inc/connection.php');

define('ROOTPATH_1',"/usr/www/users/hrdcsaqhwx/oefspiderws.hrdcsaqa.net/");
	
$_body = $_USER_DISPLAY = $_PDF_DISPLAY = $_IDS_IN = "";$_EXUECTUTE = false;

function validateDate($date = '', $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_module']) && ($_GET['_module'] >= 1) && isset($_GET['_employee']) && ($_GET['_employee'] >= 1))
{
	require_once('../../inc/excel/ovh/ovhxls_xml.php');
	$_XLS = new OVHXLS;
	
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
		
	$wfArr = $totalArr = $_MODULE_NAME = $_XLS_DOC = $_XLS_STYLE = $_DATA_ARR = array();$stages = $_FIELD_NAME = $_SQL_LIMIT = $_SQL_LIMIT_SUB = "" ;$_GET['_schedule'] = (isset($_GET['_schedule']) && ($_GET['_schedule']>=1)) ? $_GET['_schedule'] : 2;
	
	list($fname,$retrieveSelect) = retrieveSelect($_GET['_employee'],$_GET['_module']);
	
	$fname = (isset($fname) && (!empty($fname))) ? strtoupper($fname) : "ITEMS";
	$_EMPLOYEE_NAME = "";$_TOTAL_HOURS = 0;
	
	// --- Module Name
	
	$pos4 = strpos($_SESSION['accesses']->_login['type'][1], "HR MANAGER");
	if(($pos4 !== false))
	{
		if(isset($_SESSION['accesses']->_login['position'][5]) && (!empty($_SESSION['accesses']->_login['position'][5])))
		{
			$_rs = $db->Execute("SELECT
							`A`.`id`,`C`.`name`
						FROM `roles` AS `A`
						INNER JOIN `groups` AS `B` ON `B`.id=`A`.group
						INNER JOIN `pm_organograms` AS `C` ON `B`.id=`C`.hr_group
						WHERE `C`.`id`='".$_GET['_module']."'");
				
			if(($_rs) && ($_rs->_numOfRows >= 1))
			{
				$_IDS_IN = "";
				$_EMPLOYEE_NAME = $_FIELD_NAME = $_rs->fields['name'];
				while(!$_rs->EOF)
				{
					$_IDS_IN .= $_rs->fields['id'].",";
					$_rs->MoveNext();
				}
				
				$_IDS_IN = isset($_IDS_IN ) && (!empty($_IDS_IN)) ? substr($_IDS_IN, 0,-1) : "";
				
				
				
			}
							
		}
	}

	// --- Filters
	
	if(isset($_GET['_date_to']) && (!empty($_GET['_date_to'])))
	{
		//$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		//$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime("01-".$_GET['_date_to']))."</span>";
		//$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
	}
	
	$_WEEK_OF_THE_YEAR = date('W');
	
	if(isset($_GET['_week']) && (!empty($_GET['_week'])))
	{
		//$_WEEK_OF_THE_YEAR = date('W');
		
		$_SQL_LIMIT .= " AND TWEEKOFYEAR(CURDATE()) = TO_DAYS('".$_WEEK_OF_THE_YEAR."') ";
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		//$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime("01-".$_GET['_date_to']))."</span>";
		//$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
	}
	
	if(isset($_GET['_employee']) && ($_GET['_employee'] >= 1))
	{
		$_SQL_LIMIT .= " AND ((`B`.`supervisor`='".$_GET['_employee']."' AND `D`.position=36) OR (`A`.id='".$_GET['_employee']."')) ";
		
	}
	
	$_WEEK_OF_THE_YEAR = date('W');
	
	if(!(isset($_SQL_LIMIT) && (!empty($_SQL_LIMIT))))
	{
		//$_SQL_LIMIT = " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		
		//$_USER_DISPLAY .= " <i style='color:#333;'>On:</i> <span style='color:#DD7000;'>".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()))."</span>";
		//$_PDF_DISPLAY .= " ON: ".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()));
		
		$_WEEK_OF_THE_YEAR = date('W');
	}
	
	$_EXUECTUTE = true;
	
	$_FIELD_NAME = $fname = "Shift Board";//$fname = "Shift Board - ".$_MODULE_NAME[1];
	$fname_payslip = "Payslip";
	
	$_XLS_DOC = array(array(""), array("Reporting on Module: ",""), array("Parent: ",""), array("Timesheet for",$_FIELD_NAME),array(""));
	$_XLS_STYLE = array(array(""),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array(""));
	
	$_XLS_DOC_TMP[] = $fname;$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	
	$_MODULE_NAME[0] = $_MODULE_NAME[1] = $_MODULE_NAME[2] = "";
	
	$colspan = 8;
	$_body = "
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:right;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#aaa;text-indent:2px;font-size:12px;font-weight:normal;'><i>Reporting on Module: <span style='color:#555;'>".$_MODULE_NAME[1]."</span> of Parent <span style='color:#555;'>".$_MODULE_NAME[2]."</span></i>&nbsp;</td>	     
		</tr>
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:left;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:18px;'><b>Week of year: <span style='color:#e62899;'>".$_WEEK_OF_THE_YEAR."</span>$_USER_DISPLAY</b></td>	     
		</tr>
		<tr>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Employee Name</b></td>";
			
			$_date = new DateTime(); // Create a DateTime object for the current date
			$_date->modify('this week monday');
			$_date_today = date('d');
			for ($i_ = 0; $i_ < 7; $i_++)
			{
				$_day = $_date->format('d');
				$_day_desc = $_date->format('D');
				$_day_month = $_date->format('M');
				
				$_style_ = ($_day == $_date_today) ? "#FBE3E4" : "#c3daef";
				
				$_body .= "
					<td style='background:".$_style_.";border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;width:110px;' align='center'>
						<div style='width:45%;float:left;font-size:38px;padding-top:10px;'>".$_day."</div>
						<div style='width:45%;float:left;'>".strtoupper($_day_desc)."<br />".strtoupper($_day_month)."</div>
					</td>";
					
				$_date->modify('+1 day'); // Move to the next day
				
			}
				    
	$totalArr['reject'] = $totalArr['approve'] = $totalArr['total'] = 0;
	
	$_XLS_DOC_TMP[] = "Rejected";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	$_XLS_DOC_TMP[] = "Approved";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	$_XLS_DOC_TMP[] = "Total";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	
	$_body .= "	
		</tr>";
		
	$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_DOC_TMP = array();$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_STYLE_TMP = array();//= "hdr_bold_fz10_bgred_colorred";
	
	$_TOTAL = 0;
	
	// ---
	
	$_IVENTORY_MODULE = $_GET['_module']; 
	$_SQL_INVENTORY_SELECT = $_SQL_INVENTORY_ORDER_BY = "";
	
	//echo  $_SQL_LIMIT;
	
	$rs =$db->Execute("
			  SELECT
				`A`.`id`,CONCAT(`A`.`name`,' ',`A`.`surname`) AS `name`,
				`A`.shift_board_employee_shift_type,`A`.shift_board_employee_start_time,`A`.shift_board_employee_end_time,`A`.shift_board_employee_shift_type_rotation,
				0 AS `total_hours`
			  FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `A` 
				LEFT JOIN `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `B` ON `B`.id=`A`.id
				LEFT JOIN `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `C` ON `C`.id=`B`.supervisor
				LEFT JOIN `pm_organograms_joints` AS `D` ON `D`.id=`C`.position
			  WHERE `A`.`pub`=1 AND `A`.`del`=1  $_SQL_LIMIT
			  
			  ");
	if(($rs) && ($rs->numRows() >= 1))
	{
		$_binLocatinsArr = genericItemsArr("lists"," ");
		$_HOURS_TOTAL = 0;
		while(!$rs->EOF)
		{
			$_XLS_DOC_TMP = array();$_XLS_STYLE_TMP = array();
			//$itemName = (isset($retrieveSelect[$rs->fields['item']]) && (!empty($retrieveSelect[$rs->fields['item']]))) ? $retrieveSelect[$rs->fields['item']] : "Item ".$rs->fields['item'];
			$itemName = $rs->fields['name'];
			//$category = isset($_binLocatinsArr[$rs->fields['_category']]) ? $_binLocatinsArr[$rs->fields['_category']] : "N/A";
			
			$islate_color = "#f3f3f3";
			if(isset($rs->fields['_islate']) && ($rs->fields['_islate'] == "Late")){$islate_color = "#FBE3E4";}
			else if(isset($rs->fields['_islate']) && ($rs->fields['_islate'] == "Early")){$islate_color = "#E6EFC2";}
			
			$_XLS_DOC_TMP[] = $itemName;$_XLS_STYLE_TMP[] = "";
			
			$_HOURS_TOTAL = $_HOURS_TOTAL + $rs->fields['total_hours'];
			
			$_body .= "
				<tr>
					<td style='border-bottom:1px solid #ccc;font-size:11px;color:#008000;background:#f3f3f3;line-height:23px;font-weight:bold;' align='left'>&nbsp;".$rs->fields['name']."&nbsp; 
					</td>
					";
				
			$_date = new DateTime(); // Create a DateTime object for the current date
			$_date->modify('this week monday');
			$_date_today = date('d');
			for ($i_ = 0; $i_ < 7; $i_++)
			{
				$_day = $_date->format('d');
				$_day_desc = $_date->format('D');
				$_day_month = $_date->format('M');
				$_day_date = $_date->format('Y-m-d');
				
				$shift_board_employee_shift_type = $rs->fields['shift_board_employee_shift_type'];
				$shift_board_employee_shift_type_rotation = $rs->fields['shift_board_employee_shift_type_rotation'];
				$shift_board_employee_start_time = $rs->fields['shift_board_employee_start_time'];
				$shift_board_employee_end_time = $rs->fields['shift_board_employee_end_time'];
					
				$_sheduled = $_sheduled_color = "";
				
				$___rs =$db->Execute("
				  SELECT
					`A`.`id`,`A`.`shift_type`,`A`.`rotation`,`A`.`start_time`,`A`.`end_time` 
				  FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_shift_schedule` AS `A` 
				  WHERE `A`.`employee`=".$rs->fields['id']." AND TO_DAYS(`A`.`shift_date`) = TO_DAYS('".$_day_date."')  AND `A`.`pub`=1 AND `A`.`del`=1 
				  
				  ");
				if(($___rs) && ($___rs->numRows() >= 1))
				{
					$_sheduled = "border-left: 2px solid #e34761;background-color:#fff;color:#e34761;";
					$_sheduled_color = "background-color:#fff;color:#e34761;font-weight:bold;";
					$shift_board_employee_shift_type = $___rs->fields['shift_type'];
					$shift_board_employee_shift_type_rotation = $___rs->fields['rotation'];
					$shift_board_employee_start_time = $___rs->fields['start_time'];
					$shift_board_employee_end_time = $___rs->fields['end_time'];
				}
	
				$_style_ = "";
				
				$startEndTime = $totalHours = "";
				if(isset($shift_board_employee_start_time) && (!empty($shift_board_employee_start_time)) && ($shift_board_employee_start_time != "00:00:00") && isset($shift_board_employee_end_time) && (!empty($shift_board_employee_end_time)) && ($shift_board_employee_end_time != "00:00:00"))
				{
					$startEndTime = substr($shift_board_employee_start_time,0,5)." - ".substr($shift_board_employee_end_time,0,5);
					
					$date1 = isset($shift_board_employee_start_time) && validateDate($shift_board_employee_start_time)  ? new DateTime('2025-01-01 '.$shift_board_employee_start_time.':00') : "";
					$date2 = isset($shift_board_employee_end_time) && validateDate($shift_board_employee_end_time) ? new DateTime('2025-01-01 '.$shift_board_employee_end_time.':00') : "";
					
					$totalHours = "0 hrs";
					if(validateDate($date1) && validateDate($date2))
					{
						$interval = $date1->diff($date2);
						$totalHours = (($interval->days * 24) + $interval->h)." hrs";
						
					}
				}
				else{$shift_board_employee_start_time = $shift_board_employee_end_time = "";}
				
				$img_shift_type = "";
				if(isset($shift_board_employee_shift_type) && (!empty($shift_board_employee_shift_type)))
				{
					$img_shift_type = "<img alt='".$shift_board_employee_shift_type."' src='images/icons/shift-".$shift_board_employee_shift_type.".png' border='0'>";
					if($shift_board_employee_shift_type == "day"){$_style_ = "#FEFEFE";}
					else if($shift_board_employee_shift_type == "evening"){$_style_ = "#eaf8fc";}
					else if($shift_board_employee_shift_type == "grave"){$_style_ = "#FBE3E4";} 
				}
				
				$id = $rs->fields['id'];
				
				$_shit_type = isset($employeeShiftArr[$shift_board_employee_shift_type]) ? "<strong style='color:#555;$_sheduled_color'>".$employeeShiftArr[$shift_board_employee_shift_type]."</strong>" : "";
				$_shit_type_rotation = isset($employeeShiftRotationArr[$shift_board_employee_shift_type_rotation]) ? "<strong style='color:#555;$_sheduled_color'>".$employeeShiftRotationArr[$shift_board_employee_shift_type_rotation]."</strong>" : "";
				
				
				
				$_body .= "
					<td id='shift-".$id."-".($i_+1)."' class='shift' style='background:".$_style_.";cursor:pointer;border-bottom:1px solid #ccc;border-right:1px solid #ddd;color:#555;font-size:12px;width:125px;$_sheduled' align='center' style='text-align:center;'>
						<div style='width:100%;padding-top:10px;text-align:center;border-bottomx:1px dashed #ccc;'><span style='font-weightx:bold;'>".$startEndTime."</span><br />".$_shit_type."</div>
						<div style='clear:both;text-align:center;$_sheduled_color'>".$img_shift_type."</div>
						<input id='shift-employee-name-".$id."-".($i_+1)."' type='hidden' value='".$rs->fields['name']."' >
						<input id='shift-employee-date-".$id."-".($i_+1)."' type='hidden' value='".$_day_date."' >
						<input id='shift-type-".$id."-".($i_+1)."' type='hidden' value='".$shift_board_employee_shift_type."' >
						<input id='shift-type-rotation-".$id."-".($i_+1)."' type='hidden' value='".$shift_board_employee_shift_type_rotation."' >
						<input id='shift-start-time-".$id."-".($i_+1)."' type='hidden' value='".$shift_board_employee_start_time."' >
						<input id='shift-end-time-".$id."-".($i_+1)."' type='hidden' value='".$shift_board_employee_end_time."' >
					</td>";
					
				$_date->modify('+1 day'); // Move to the next day
				
			}
					
					
			$_TOTAL_HOURS = $_TOTAL_HOURS + ($rs->fields['total_hours']);
							
			//$totalArr['reject'] += $rs->fields['reject'];
			//$totalArr['approve'] += $rs->fields['approve'];
			//$totalArr['total'] += $rs->fields['total'];
			
			//$_XLS_DOC_TMP[] = $rs->fields['reject'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['approve'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['total'];$_XLS_STYLE_TMP[] = "";
			
			//$_DATA_ARR[] = array(substr($itemName,0,18),$rs->fields['_barcode'],$category,$bin,substr($rs->fields['_description'],0,10),$rs->fields['_cost'],$rs->fields['_qty'],0,number_format(round($rs->fields['_qty']*$rs->fields['_cost'],2),2,"."," "));
			
			$_DATA_ARR[] = array($rs->fields['name'],$shift_board_employee_start_time,$shift_board_employee_end_time,$shift_board_employee_shift_type);
			$_body .= "
				</tr>";
				
			//$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_DOC_TMP = $_XLS_STYLE_TMP = array();
			$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_DOC_TMP = $_XLS_STYLE_TMP = array();
			
			
			
			$rs->MoveNext();
		}
	}
	
	$_XLS_DOC_TMP = array();
	
	$_XLS_DOC_TMP[] = "TOTALS:";
	$_body .= "
		<tr>
			<td colspan='".($colspan-1)."' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>&nbsp; </td>";
		
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
			$_body = "<center><a style='text-decoration:none;' target='_blank' href='../../exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_module']." - ".date("Y-F-d").".pdf'><span style='background-color:#ddd;border:2px solid #125687;padding:3px;color:#125687;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download PDF Shift Board</span></a> 
			".$_body;
		}
		
		echo $_body."
		<script>
			jQuery('.shift').on('click',function(){
			
				var _ID = jQuery(this).attr('id').split('-')[1]+'-'+jQuery(this).attr('id').split('-')[2];
				jQuery('#shift-employee').val(jQuery(this).attr('id').split('-')[1]);
				jQuery('#_week_of_year').val('".$_WEEK_OF_THE_YEAR."');
				jQuery('#shift_date').val(jQuery('#shift-employee-date-'+_ID).val());
				jQuery('#myModalHeading').html(jQuery('#shift-employee-name-'+_ID).val());
				jQuery('#myModalSubHeading').html(jQuery('#shift-employee-date-'+_ID).val());
				jQuery('#shift_board_employee_shift_type option[value=\"'+jQuery('#shift-type-'+_ID).val()+'\"]').prop('selected', true);
				jQuery('#shift_board_employee_shift_type_rotation option[value=\"'+jQuery('#shift-type-rotation-'+_ID).val()+'\"]').prop('selected', true);
				jQuery('#shift_board_employee_start_time option[value=\"'+jQuery('#shift-start-time-'+_ID).val()+'\"]').prop('selected', true);
				jQuery('#shift_board_employee_end_time option[value=\"'+jQuery('#shift-end-time-'+_ID).val()+'\"]').prop('selected', true);
				jQuery('#myModal').modal('show');
					
			});
		</script>";
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
		require("../../inc/pdf/fpdf186/fpdf.php");
		
			
		header("Content-Disposition: attachment; filename=BUSINESS-PROCESS-WORKFLOW.pdf");
		// _________________________________________________ PDF Header
		
		//$_WIDTH = 164; $_HEIGHT = 164; // --- Document Width & Height
		$_WIDTH = 216; $_HEIGHT = 297; // --- Document Width & Height
		
		//$pdf=new PDF_Ellipse('P','mm',array($_WIDTH,$_HEIGHT));
		$pdf = new FPDF('P','mm',array($_WIDTH,$_HEIGHT));
		//$pdf = new FPDF();
		//$pdf = new FPDF('P','mm','A4');
		//$pdf->Open();
		
		
		$_WIDTH = 216; $_HEIGHT = 297; // --- Document Width & Height
		
		$_START_LEFT = 24;$_START_TOP = 15;
		$_P_WIDTH = $_WIDTH - ($_START_LEFT*2);// --- Page Content Width
		$_PC_WIDTH = $_P_WIDTH - 13; // --- Refined  content width
		
		$p = 1;
		
		$pdf->SetTextColor(0);
		
		list($RGB['r'][0],$RGB['g'][0],$RGB['b'][0]) = sscanf("#d7c834","#%02x%02x%02x");
		list($RGB['r'][1],$RGB['g'][1],$RGB['b'][1]) = sscanf("#000000","#%02x%02x%02x");
		list($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]) = sscanf("#d7c834","#%02x%02x%02x");
		
		// _____________________________________________________ Page # 1 - Cover Page __________________________________________________
		
		
				
		$pdf->AddPage("L");$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
				$h=0;$w = 73;
				$pdf->SetFillColor(175,175,175);$pdf->SetTextColor(0);$pdf->SetFont('arial','B',32);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h);$pdf->MultiCell($_P_WIDTH/2.5,24,"","","C",true);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+9.0);$pdf->Cell($_P_WIDTH/2.5,6,"Timesheet","",0,"C",false);
				$pdf->SetFillColor(75,75,75);
				$pdf->SetFont('arial','',11.5);$pdf->SetTextColor(55);$pdf->SetXY($w+3+($_P_WIDTH/2.5) - 7.5,$h+17.75);$pdf->Cell($_P_WIDTH/2.61,6,"( Timesheet Book - Weeklys )","",0,"C",false);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+24);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
				
				$h = $_START_TOP;$w = $_START_LEFT;
				
				    $pdf->SetTextColor(0);$pdf->SetFont('arial','',18);
				//$pdf->SetXY(0,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"OVH IoT: Manufacturing \nDivision",0,"C");$_EMPLOYEE_NAME = "";$_TOTAL_HOURS = 0;
				$pdf->SetXY(10-1,$h+5.85);$pdf->MultiCell($_HEIGHT,15,$_EMPLOYEE_NAME,0,"L");
				
				$h+=8*2.25;
				$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(0);$pdf->Line(10,$h-1,$_HEIGHT-6.0,$h-1);
				
				$_STATUS = 0;
				
				$pdf->SetDrawColor(33,134,230);
				$pdf->SetTextColor(33,134,230);
				
				$h = 269+13;
				
				$pdf->SetFillColor(81,86,89);$pdf->SetDrawColor(81,86,89);$pdf->SetLineWidth(1.5);
				
				//$pdf->Line(0,$_HEIGHT/2,$_WIDTH/2-0.8,$_HEIGHT/2);
				
				// --- --- --- NEW SECTION
				
				$pdf->SetLineWidth(0.1);
				
				$h = $_HEIGHT/2;$w =  $w + 2.5;
				//$pdf->Polygon(array($_WIDTH,-7.5,$_WIDTH,$_HEIGHT+7.5,$_WIDTH/3,$_HEIGHT/2),'F');
				
				// --- --- --- NEW SECTION
				
				$w=120;$h = 56.0;
				
				// --- --- --- Description of work
				
				$w = $_START_LEFT-15.5;//$h+=6*6.5;
				
				
				$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(39,44,49);$pdf->SetTextColor(255);
				
				
				list($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]) = sscanf("#e3e3e3","#%02x%02x%02x");$pdf->SetFillColor($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]);
				list($RGB['r'][4],$RGB['g'][5],$RGB['b'][5]) = sscanf("#DB0000","#%02x%02x%02x");$pdf->SetTextColor($RGB['r'][5],$RGB['g'][5],$RGB['b'][5]);
				
				$pdf->SetFont('arial','B',11);
				$pdf->SetXY($w,$h);$pdf->MultiCell(70,18," Employee Name",1,'L',true);
				
				/*$pdf->SetXY($w+25+25,$h);$pdf->MultiCell(20,8,"Time In",1,'C',true);
				$pdf->SetXY($w+25+25+20,$h);$pdf->MultiCell(20,8,"Time Out",1,'C',true);
				$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(20,8,"Arrival",1,'C',true);
				$pdf->SetXY(5+$w+25+25+20+20+20,$h);$pdf->MultiCell(20,8,"Breaks",1,'C',true);$pdf->SetXY(5+$w+25+25+20+20+20+20,$h);$pdf->MultiCell(20,8,"Overtime",1,'C',true);$pdf->SetXY(5+$w+25+25+20+20+20+20+20,$h);$pdf->MultiCell(30,8,"Total Hours",1,'C',true);
				*/
				
				$_date = new DateTime(); // Create a DateTime object for the current date
				$_date->modify('this week monday');
				$_date_today = date('d');
				for ($i_ = 0; $i_ < 7; $i_++)
				{
					$_day = $_date->format('d');
					$_day_desc = $_date->format('D');
					$_day_month = $_date->format('M');
					
					//$_style_ = ($_day == $_date_today) ? "#FBE3E4" : "#c3daef";
					list($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]) = sscanf("#c4daef","#%02x%02x%02x");$pdf->SetFillColor($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]);
					list($RGB['r'][5],$RGB['g'][5],$RGB['b'][5]) = sscanf("#175687","#%02x%02x%02x");$pdf->SetTextColor($RGB['r'][5],$RGB['g'][5],$RGB['b'][5]);
					if($_day == $_date_today)
					{
						list($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]) = sscanf("#FBE3E4","#%02x%02x%02x");$pdf->SetFillColor($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]);
					}
					
					$pdf->SetFont('arial','B',28);$pdf->SetXY($w+70+30*$i_,$h);$pdf->MultiCell(30,18," ".$_day,1,'L',true);
					$pdf->SetFont('arial','',10);$pdf->Text($w+70+16+30*$i_,$h+8,strtoupper($_day_desc));
					$pdf->SetFont('arial','',10);$pdf->Text($w+70+16+30*$i_,$h+8+4,strtoupper($_day_month));
					
					/*$_body .= "
						<td style='background:".$_style_.";border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;width:110px;' align='center'>
							<div style='width:45%;float:left;font-size:38px;padding-top:10px;'>".$_day."</div>
							<div style='width:45%;float:left;'>".strtoupper($_day_desc)."<br />".strtoupper($_day_month)."</div>
						</td>";*/
						
					$_date->modify('+1 day'); // Move to the next day
					
				}
				
				$h+=18;
				$pdf->SetFont('arial','',9.5);$_TOTAL = 0;$pdf->SetTextColor(39,44,49);$pdf->SetFillColor(245);
				
				// _____________________________________________________ ::: __________________________________________________
				
				$_CUT_OFF = 3;$_INDEX = 0;
				
				foreach($_DATA_ARR as $k=>$v)
				{
					
					list($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]) = sscanf("#e3e3e3","#%02x%02x%02x");$pdf->SetFillColor($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]);
					list($RGB['r'][5],$RGB['g'][5],$RGB['b'][5]) = sscanf("#DB0000","#%02x%02x%02x");$pdf->SetTextColor($RGB['r'][5],$RGB['g'][5],$RGB['b'][5]);
					
					$pdf->SetFont('arial','B',10);
					$pdf->SetXY($w,$h);$pdf->MultiCell(70,18,$v[0],1,'L',true);
					
					$_date = new DateTime(); // Create a DateTime object for the current date
					$_date->modify('this week monday');
					$_date_today = date('d');
					for ($i_ = 0; $i_ < 7; $i_++)
					{
						$_day = $_date->format('d');
						$_day_desc = $_date->format('D');
						$_day_month = $_date->format('M');
						
						$_style_ = "#FEFEFE";
						
						$startEndTime = $totalHours = "";
						if(isset($v[1]) && (!empty($v[1])) && isset($v[2]) && (!empty($v[2])))
						{
							$startEndTime = $v[1]." - ".$v[2];
							
							$date1 = new DateTime('2025-01-01 '.$v[1].':00');
							$date2 = new DateTime('2025-01-01 '.$v[2].':00');
							
							$interval = $date1->diff($date2);
							$totalHours = (($interval->days * 24) + $interval->h)." hrs";
						}
						
						$img_shift_type = "";
						if(isset($v[3]) && (!empty($v[3])))
						{
							$img_shift_type = "shift-".$v[3];
							if($v[3] == "day"){$_style_ = "#FEFEFE";}
							else if($v[3] == "evening"){$_style_ = "#eaf8fc";}
							else if($v[3] == "grave"){$_style_ = "#FBE3E4";}
							//$pdf->Image(CONSUMER_ROOTPATH."images/icons/".$img_shift_type,$w+70+30*$i_,$h+9.55+5,0,0,'','');
						}
						
						list($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]) = sscanf($_style_,"#%02x%02x%02x");$pdf->SetFillColor($RGB['r'][4],$RGB['g'][4],$RGB['b'][4]);
						list($RGB['r'][5],$RGB['g'][5],$RGB['b'][5]) = sscanf("#555555","#%02x%02x%02x");$pdf->SetTextColor($RGB['r'][5],$RGB['g'][5],$RGB['b'][5]);
						
						$pdf->SetFont('arial','',10);$pdf->SetXY($w+70+30*$i_,$h);$pdf->MultiCell(30,18,"",1,'L',true);
						$pdf->SetFont('arial','',10);$pdf->Text($w+70+30*$i_,$h+5," ".$startEndTime);
						$pdf->SetFont('arial','',10);$pdf->Text($w+70+30*$i_,$h+9.5," ".strtoupper($totalHours));
						
						if(isset($v[3]) && (!empty($v[3])))
						{
							//$img_shift_type = "shift-".$v[3];
							$pdf->Image("../../images/icons/".$img_shift_type,$w+70+30*$i_,$h+9.55+5,0,0,'','');
						}
						//$pdf->SetFont('arial','',10);$pdf->Text($w+70+16+30*$i_,$h+8+4,strtoupper($_day_month));
						/*$_body .= "
							<td style='background:".$_style_.";border-bottom:1px solid #ccc;color:#555;font-size:12px;width:110px;' align='center'>
								<div style='width:100%;padding-top:10px;text-align:left;border-bottomx:1px dashed #ccc;'><span style='font-weightx:bold;'>".$startEndTime."</span><br />".$totalHours."</div>
								<div style='width:100%;clear:both;'>".$img_shift_type."</div>
							</td>";*/
							
						$_date->modify('+1 day'); // Move to the next day
						
					}
			
					    $h+=18;$_INDEX++;
					    
					    if($_INDEX == $_CUT_OFF)
					    {
						 $pdf->AddPage('L');$p++;$h = $_START_TOP;//$w = $_START_LEFT;
						 //$pdf->SetAlpha(0.15);$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$rs_modules->fields['orglogo'],-1.5,163.5,65,0,'','');$pdf->SetAlpha(1);
						 $pdf->SetFont('arial','',10);
						 
						 $_INDEX = 0;
						 if($p >= 2){$_CUT_OFF = 6;}
					    }
					    
				}
				
				// --- Footer
				
				//$pdf->SetDrawColor(205);$pdf->SetLineWidth(0.1);$pdf->Line(0,277,210,277);$pdf->SetTextColor(175);$pdf->SetFont('arial','',9);
				//$pdf->Image(CONSUMER_ROOTPATH."images/organizations/aspen-main-logo.png",15,1,10,0,'','');$pdf->Text(85,285,"OVH Enterprise Framework v2.10");
				
						
				// ---- End of Document
				
				// --- Document Author
				
				$pdf->SetAuthor(SYST_ABBR.' - OVH Enterprise Framework');   
				$pdf->SetTitle($fname." - ".$_GET['_module']." - ".date("Y-F-d"));
				
				@$pdf->Output(ROOTPATH_1."exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_module']." - ".date("Y-F-d").".pdf","F");
				
				
					//echo ROOTPATH."docs/dataobjects/activities/tmp/".$fname.".pdf";
					exit;
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
		//echo "SELECT GROUP_CONCAT(CONCAT(`A`.item) SEPARATOR',') AS `selectids`,GROUP_CONCAT(CONCAT('\'',`A`.subject,'\'') SEPARATOR',') AS `selectnames`,`A`.type FROM `users_links_activitiesreports` AS `A` WHERE `A`.`page`=".$db->qstr($_GET['_module'])." AND `A`.`subject`=".$db->qstr($_GET['_field'])." AND `A`.`schedule`=".$db->qstr($_GET['_schedule'])." AND `A`.`pub`='1' AND `A`.`del`='1' GROUP BY `A`.type";
		$rs_c =$db->Execute("SELECT GROUP_CONCAT(CONCAT(`A`.item) SEPARATOR',') AS `selectids`,GROUP_CONCAT(CONCAT('\'',`A`.subject,'\'') SEPARATOR',') AS `selectnames`,`A`.type FROM `users_links_activitiesreports` AS `A` WHERE `A`.`page`=".$db->qstr($_GET['_module'])." AND `A`.`subject`=".$db->qstr($_GET['_field'])." AND `A`.`schedule`=".$db->qstr($_GET['_schedule'])." AND `A`.`pub`='1' AND `A`.`del`='1' GROUP BY `A`.type");
		
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
else{echo "&nbsp;&nbsp;<strong style='font-size:14px;'>You are logged from the system..";}
?>