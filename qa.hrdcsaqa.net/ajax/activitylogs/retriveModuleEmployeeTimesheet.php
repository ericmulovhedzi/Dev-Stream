<?
set_time_limit(0);
ini_set('memory_limit','-1');
//error_reporting(E_ALL);
 
require_once('../../inc/connection.php');

define('ROOTPATH_1',"/usr/www/users/hrdcsaqhwx/oefspiderws.hrdcsaqa.net/");
	
$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_module']) && ($_GET['_module'] >= 1) && isset($_GET['_employee']) && ($_GET['_employee'] >= 1))
{
	require_once('../../inc/excel/ovh/ovhxls_xml.php');
	$_XLS = new OVHXLS;
	
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$wfArr = $totalArr = $_MODULE_NAME = $_XLS_DOC = $_XLS_STYLE = $_DATA_ARR = array();$stages = $_FIELD_NAME = $_SQL_LIMIT = $_SQL_LIMIT_SUB = "" ;$_GET['_schedule'] = (isset($_GET['_schedule']) && ($_GET['_schedule']>=1)) ? $_GET['_schedule'] : 2;
	
	list($fname,$retrieveSelect) = retrieveSelect($_GET['_employee'],$_GET['_module']);
	
	$rs_w =$db->Execute("SELECT `B`.`id`,`A`.`description`,`B`.`name` FROM `workflows_page_workflows` AS `A` INNER JOIN `workflows_groups` AS `B` ON `A`.workflow=`B`.id WHERE `B`.`page`='".$_GET['_module']."' AND `A`.pub=1 AND `A`.del=1 AND `B`.pub=1 AND `B`.del=1 ORDER BY `B`.rank ASC");
	$colspan = (($rs_w) && ($rs_w->numRows() >= 1)) ? 8 + $rs_w->numRows() : 8;
	
	$fname = (isset($fname) && (!empty($fname))) ? strtoupper($fname) : "ITEMS";
	$_EMPLOYEE_NAME = "";$_TOTAL_HOURS = 0;
	// --- Module Name
	
	$rs_modules = $db->Execute("
					SELECT `C`.id,`C`.name,`D`.id AS pid,`D`.name AS pname,`E`.name AS ppname,`F`.name AS orgname,`F`.branding_logo_main AS orglogo,`F`.branding_color_main AS orgcolor1,`F`.branding_color_sub AS orgcolor2
					FROM `pages` AS `C`
					INNER JOIN `pages` AS `D` ON `D`.id=`C`.parent
					INNER JOIN `pages` AS `E` ON `E`.id=`D`.parent
					INNER JOIN `ovhef_pricing_organizations` AS `F` ON `F`.id=`C`.pricing_client
					WHERE `C`.live='1' AND `C`.id=".$_GET['_module']." AND `C`.pub='1' AND `C`.del='1' AND `D`.pub='1' AND `D`.del='1' ORDER BY `D`.rank ASC,`C`.rank ASC LIMIT 1
				");
	if(($rs_modules) && ($rs_modules->numRows() >= 1))
	{
		$_MODULE_NAME[3] = $rs_modules->fields['ppname'];$_MODULE_NAME[2] = $rs_modules->fields['pname'];$_MODULE_NAME[1] = $rs_modules->fields['name'];
		
		$rs_field = $db->Execute("SELECT id,CONCAT(users.name ,' ' ,users.surname) AS cname FROM `users` WHERE `users`.id='".$_GET['_employee']."' AND `users`.pub='1' AND `users`.del='1' LIMIT 1");
		if(($rs_field) && ($rs_field->_numOfRows >= 1))
		{
			$_EMPLOYEE_NAME = $_FIELD_NAME = $rs_field->fields['cname'];
			
		}
	}

	// --- Filters
	
	if(isset($_GET['_date_to']) && (!empty($_GET['_date_to'])))
	{
		$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		$_USER_DISPLAY .= " <i style='color:#333;'>on Month: </i> <span style='color:#DD7000;'>".date("F, Y",strtotime("01-".$_GET['_date_to']))."</span>";
		$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
	}
	
	if(!(isset($_SQL_LIMIT) && (!empty($_SQL_LIMIT))))
	{
		//$_SQL_LIMIT = " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		
		//$_USER_DISPLAY .= " <i style='color:#333;'>On:</i> <span style='color:#DD7000;'>".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()))."</span>";
		//$_PDF_DISPLAY .= " ON: ".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()));
	}
	
	$_EXUECTUTE = true;
	
	$fname = "Timesheet - ".$_MODULE_NAME[1];
	$fname_payslip = "Payslip - ".$_MODULE_NAME[1];
	
	$_XLS_DOC = array(array(""), array("Reporting on Module: ",$_MODULE_NAME[1]), array("Parent: ",$_MODULE_NAME[2]), array("Timesheet for",$_FIELD_NAME),array(""));
	$_XLS_STYLE = array(array(""),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array(""));
	
	$_XLS_DOC_TMP[] = $fname;$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	
	$colspan = 9;
	$_body = "
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:right;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#aaa;text-indent:2px;font-size:12px;font-weight:normal;'><i>Reporting on Module: <span style='color:#555;'>".$_MODULE_NAME[1]."</span> of Parent <span style='color:#555;'>".$_MODULE_NAME[2]."</span></i>&nbsp;</td>	     
		</tr>
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:left;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b>Timesheet for <span style='color:#e62899;'>$_FIELD_NAME</span>$_USER_DISPLAY</b></td>	     
		</tr>
		<tr>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Name / No. of Activities</b></td>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Date</b></td>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:center;'>&nbsp;<b>Day</b></td>";
		
		$_body .= "
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Time In&nbsp;</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Time Out&nbsp;</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Arrival Status&nbsp;</td>";
			
	if(($rs_w) && ($rs_w->numRows() >= 1))
	{
		while(!$rs_w->EOF)
		{
			$wfArr[] = $rs_w->fields['id'];//array($rs->fields['name'],$rs->fields['description']);
			$totalArr[$rs_w->fields['id']] = 0;
			
			$stages .= ",SUM(IF(`A`.`stage`=".$rs_w->fields['id'].",`A`.`count`,0)) AS `stage_".$rs_w->fields['id']."`";
			
			$_XLS_DOC_TMP[] = $rs_w->fields['name'];$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
			//$_body .= "
			//	<td style='background:#eee;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;".$rs_w->fields['name']."&nbsp;</td>";
			$rs_w->MoveNext();
		}
	}
	
	$totalArr['reject'] = $totalArr['approve'] = $totalArr['total'] = 0;
	
	$_XLS_DOC_TMP[] = "Rejected";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	$_XLS_DOC_TMP[] = "Approved";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	$_XLS_DOC_TMP[] = "Total";$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	
	$_body .= "
			<td style='background:#c3daef;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Breaks</td>
			<td style='background:#FBE3E4;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Overtime</td>	
			<td style='background:#E6EFC2;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Total Hours&nbsp;</td>
		</tr>";
		
	$_XLS_DOC[] = $_XLS_DOC_TMP;$_XLS_DOC_TMP = array();$_XLS_STYLE[] = $_XLS_STYLE_TMP;$_XLS_STYLE_TMP = array();//= "hdr_bold_fz10_bgred_colorred";
	
	$_TOTAL = 0;
	
	// ---
	
	$_IVENTORY_MODULE = $_GET['_module']; 
	$_SQL_INVENTORY_SELECT = $_SQL_INVENTORY_ORDER_BY = "";
	/*$rs__inv = $db->Execute("SELECT
				`A`.id,`A`.name,
				`A`.`is_barcode`,`A`.`is_barcode_qty`,`A`.`is_barcode_cost`,`A`.`is_item`,`A`.`is_desc`,`A`.`is_bin`,`A`.`is_category`,
				`A`.`has_barcode`,`A`.`has_barcode_qty`,`A`.`has_barcode_price`,`A`.`has_item`,`A`.`has_desc`
				FROM `pages_fields` AS `A`
				LEFT JOIN `pages_tabs` AS `B` ON `B`.id=`A`.tab LEFT JOIN `pages_sections` AS `C` ON `C`.id=`B`.section LEFT JOIN `pages` AS `D` ON `D`.id=`C`.page
				WHERE `C`.page=".$_IVENTORY_MODULE." AND `D`.`is_inventory_barcode`='1' AND `A`.`pub`='1' AND `A`.`del`='1' AND `B`.`pub`='1' AND `B`.`del`='1' AND `C`.`pub`='1' AND `C`.`del`='1' ORDER BY `A`.id ASC");
		
	if(($rs__inv) && ($rs__inv->numRows() >= 1))
	{
		while(!$rs__inv->EOF)
		{
			if($rs__inv->fields['is_barcode'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_barcode`";}
			else if($rs__inv->fields['is_item'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_name`";}
			else if($rs__inv->fields['is_barcode_qty'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_qty`";$_SQL_INVENTORY_ORDER_BY = "ORDER BY `A`.`".$rs__inv->fields['id']."` DESC";}
			else if($rs__inv->fields['is_barcode_cost'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_cost`";}
			//else if($rs__inv->fields['is_bin'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_supplier`";}
			else if($rs__inv->fields['is_bin'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_bin`";}
			else if($rs__inv->fields['is_category'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_category`";}
			else if($rs__inv->fields['is_desc'] == 1){$_SQL_INVENTORY_SELECT .= ",`A`.`".$rs__inv->fields['id']."` AS `_description`";}
			
			$rs__inv->MoveNext();	
		}
	}*/
	
	// `A`.`295` AS `_name`,`A`.`296` AS `_barcode`,`A`.`297` AS `_supplier`,`A`.`298` AS `_qty`,`A`.`312` AS `_cost`,`A`.`310` AS `_bin`,`A`.`311` AS `_category`,`A`.`309` AS `_description`
	
	$rs =$db->Execute("
			  SELECT
				DATE_FORMAT(MIN(`A`.date),'%d/%m/%Y') AS `date`,DAY(MIN(`A`.date)) AS `day`,DATE_FORMAT(MIN(`A`.date),'%W') AS `day_formated`,`A`.`1051` AS `name`,  
				TIMESTAMPDIFF(HOUR, `A`.date, MAX(`A`.date_update)) AS `total_hours`,
				DATE_FORMAT(MIN(`A`.date),'%h:%i %p') AS `time_in`,
				IF(TIMESTAMPDIFF(SECOND, MIN(`A`.date), MAX(`A`.date_update)) = 0, '', DATE_FORMAT(`A`.date_update,'%h:%i %p')) AS `time_out`,
				IF(TIMESTAMPDIFF(SECOND, MIN(`A`.date), MAX(`A`.date_update)) = 0, COUNT(`A`.id), COUNT(`A`.id)+1) AS `islegit`,
				`A`.`1058` AS `_islate`
			  FROM `_mod_".$_GET['_module']."` AS `A` 
			  WHERE 1=1 
			  GROUP BY DAY(`A`.date),`A`.`1051`
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
					<td style='border-bottom:1px solid #ccc;font-size:11px;color:#008000;background:#f3f3f3;line-height:23px;font-weight:bold;' align='left'>&nbsp;".$rs->fields['name']." - ".$rs->fields['islegit']." time(s)&nbsp;</td>
					<td style='border-bottom:1px solid #ccc;font-size:11px;color:#008000;background:#f3f3f3;line-height:23px;font-weight:bold;' align='left'>&nbsp;".$rs->fields['date']."&nbsp;</td>";
				
				
			$_body .= "
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".$rs->fields['day_formated']."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".$rs->fields['time_in']."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".$rs->fields['time_out']."</b>&nbsp;</td>
					
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:".$islate_color.";' align='center'><b>".$rs->fields['_islate']."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#c3daef;' align='center'><b></b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#FBE3E4;' align='center'><b></b>&nbsp;</td>
					
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#E6EFC2;' align='center'><b>".$rs->fields['total_hours']." hrs</b>&nbsp;</td>
					";
					
					
			$_TOTAL_HOURS = $_TOTAL_HOURS + ($rs->fields['total_hours']);
							
			//$totalArr['reject'] += $rs->fields['reject'];
			//$totalArr['approve'] += $rs->fields['approve'];
			//$totalArr['total'] += $rs->fields['total'];
			
			//$_XLS_DOC_TMP[] = $rs->fields['reject'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['approve'];$_XLS_STYLE_TMP[] = "";
			//$_XLS_DOC_TMP[] = $rs->fields['total'];$_XLS_STYLE_TMP[] = "";
			
			//$_DATA_ARR[] = array(substr($itemName,0,18),$rs->fields['_barcode'],$category,$bin,substr($rs->fields['_description'],0,10),$rs->fields['_cost'],$rs->fields['_qty'],0,number_format(round($rs->fields['_qty']*$rs->fields['_cost'],2),2,"."," "));
			
			$_DATA_ARR[] = array($rs->fields['name'],$rs->fields['date'],$rs->fields['day_formated'],$rs->fields['time_in'],$rs->fields['time_out'],$rs->fields['_islate'],"","","",$rs->fields['total_hours']);
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
			<td colspan='".($colspan-1)."' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>TOTAL:&nbsp;</td>";
		
	$_XLS_DOC_TMP[] = $totalArr['reject'];$_XLS_DOC_TMP[] = $totalArr['approve'];$_XLS_DOC_TMP[] = $totalArr['total'];
	
	$_body .= "
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#008000;font-weight:bold;font-size:11px;' align='center'>".$_HOURS_TOTAL."&nbsp;</td>
		</tr>";
	$_body .= "
		<tr>
			<td colspan='".($colspan-1)."' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>Rate Per Hour:&nbsp;</td>
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#008000;font-weight:bold;font-size:11px;' align='center'>R 23&nbsp;</td>
		</tr>";
	$_body .= "
		<tr>
			<td colspan='".($colspan-1)."' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>Total Pay:&nbsp;</td>
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#008000;font-weight:bold;font-size:11px;' align='center'>R ".(23*$_HOURS_TOTAL)."&nbsp;</td>
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
			$_body = "<center><a style='text-decoration:none;' target='_blank' href='../../exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_module']." - ".date("Y-F-d").".pdf'><span style='background-color:#ddd;border:2px solid #00A000;padding:3px;color:#008000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download PDF Timesheet</span></a> 
			<a style='text-decoration:none;' target='_blank' href='../../exports/pdf/campaigns/tmp/".$fname_payslip." - ".$_GET['_module']." - ".date("Y-F-d").".pdf'><span style='background-color:#ddd;border:2px solid #e62899;padding:3px;color:#e62899;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download PDF Payslip</span></a></center>".$_body;
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
		
		
				
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
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
				
				$pdf->SetFont('arial','B',9.5);
				$pdf->SetXY($w,$h);$pdf->MultiCell(25,8,"Date",1,'C',true);
				$pdf->SetXY($w+25,$h);$pdf->MultiCell(25,8,"Day",1,'C',true);
				$pdf->SetXY($w+25+25,$h);$pdf->MultiCell(20,8,"Time In",1,'C',true);
				$pdf->SetXY($w+25+25+20,$h);$pdf->MultiCell(20,8,"Time Out",1,'C',true);
				$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(20,8,"Arrival",1,'C',true);
				$pdf->SetXY(5+$w+25+25+20+20+20,$h);$pdf->MultiCell(20,8,"Breaks",1,'C',true);$pdf->SetXY(5+$w+25+25+20+20+20+20,$h);$pdf->MultiCell(20,8,"Overtime",1,'C',true);$pdf->SetXY(5+$w+25+25+20+20+20+20+20,$h);$pdf->MultiCell(30,8,"Total Hours",1,'C',true);
				
				
				$h+=8;
				$pdf->SetFont('arial','',9.5);$_TOTAL = 0;$pdf->SetTextColor(39,44,49);$pdf->SetFillColor(245);
				
				// _____________________________________________________ ::: __________________________________________________
				
				$_CUT_OFF = 0;
				
				foreach($_DATA_ARR as $k=>$v)
				{
					/*
					//$pdf->SetXY($xPos,$yPos);$pdf->SetFillColor(245);$pdf->Cell($tWidth,$tHeight,$k,1,1,'L',true);$pdf->SetXY($xPos+$tWidth,$yPos);$pdf->SetFillColor(255);$pdf->Cell($tWidth/2.9,$tHeight,$v,1,1,'R',true);$_i++;
					$pdf->SetTextColor(0);$pdf->SetFillColor(255);
					
					    $pdf->SetXY($w,$h);$pdf->MultiCell(45,5,$v[0],1,'L',true);$pdf->SetFont('arial','',9.5);$pdf->SetXY($w+45,$h);$pdf->MultiCell(50,5,$v[1],1,'L',true);$pdf->SetFont('arial','',10);
					    $pdf->SetXY($w+50+40,$h);$pdf->MultiCell(30,5,$v[2],1,'L',true);
					    $pdf->SetXY($w+50+40+30,$h);$pdf->MultiCell(30,5,$v[3],1,'C');$pdf->SetXY($w+50+40+25+35,$h);$pdf->MultiCell(30,5,substr($v[4],0,15),1,'L');
					    $pdf->SetXY($w+50+40+15+25+20+30,$h);$pdf->MultiCell(30,5,"R ".$v[5],1,'R');
					    $pdf->SetFillColor(244,188,191);
					    $pdf->SetXY($w+50+40+15+20+20+30+35,$h);$pdf->MultiCell(20,5,$v[6],1,'C',true);
					    $pdf->SetFillColor(128,246,166);
					    $pdf->SetXY($w+50+40+15+20+20+30+30+25,$h);$pdf->MultiCell(20,5,"",1,'C');$pdf->SetXY($w+50+40+15+20+20+30+30+30+15,$h);$pdf->MultiCell(30,5,"R ".$v[8],1,'R',true);
					*/
					
						$pdf->SetXY($w,$h);$pdf->MultiCell(25,6," ".$v[1],1,'C',true);$pdf->SetXY($w+25,$h);$pdf->MultiCell(25,6,$v[2],1,'C',true);$pdf->SetXY($w+25+25,$h);$pdf->MultiCell(20,6,$v[3],1,'C');$pdf->SetXY($w+25+25+20,$h);$pdf->MultiCell(20,6,$v[4],1,'C');
						$pdf->SetXY(5+$w+25+25+20+20,$h);$pdf->MultiCell(20,6,$v[5],1,'C');$pdf->SetXY(5+$w+25+25+20+20+20,$h);$pdf->MultiCell(20,6,"",1,'C');$pdf->SetXY(5+$w+25+25+20+20+20+20,$h);$pdf->MultiCell(30,6,"",1,'C',true);
						$pdf->SetXY(5+$w+25+25+20+20+20+20+20,$h);$pdf->MultiCell(30,6,$v[9]." hrs",1,'C',true);
						
					    $h+=6;$_INDEX++;
					    
					    if($_INDEX == $_CUT_OFF)
					    {
						 $pdf->AddPage('L');$p++;$h = $_START_TOP;//$w = $_START_LEFT;
						 //$pdf->SetAlpha(0.15);$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$rs_modules->fields['orglogo'],-1.5,163.5,65,0,'','');$pdf->SetAlpha(1);
						 $pdf->SetFont('arial','',10);
						 
						 $_INDEX = 0;
						 if($p >= 2){$_CUT_OFF = 32;}
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
				
				
				
				@$pdf->Output(ROOTPATH_1."exports/pdf/campaigns/tmp/".$fname_payslip." - ".$_GET['_module']." - ".date("Y-F-d").".pdf","F");
				
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