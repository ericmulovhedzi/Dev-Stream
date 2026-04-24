<?
ini_set("display_errors",1);
ignore_user_abort(true);
set_time_limit(0);
ini_set('memory_limit','-1');
error_reporting(E_ERROR | E_PARSE);
 

require_once('../../inc/connection.php');

$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;

//if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_module']) && ($_GET['_module'] >= 1))
//{
//if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_module']) && ($_GET['_module'] >= 0) && (isset($_GET['_employee']) || ($_GET['_employee'] >= 0)) && isset($_GET['_date_from']) && (!empty($_GET['_date_from'])) && isset($_GET['_date_to']) && (!empty($_GET['_date_to'])))
if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_module']) && ($_GET['_module'] >= 0) && (isset($_GET['_employee']) || ($_GET['_employee'] >= 0))  )
{
	require_once('../../inc/excel/ovh/ovhxls_xml.php');
	$_XLS = new OVHXLS;
	
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$wfArr = $totalArr = $_MODULE_NAME = $_XLS_DOC = $_XLS_STYLE = $_DATA_ARR = array();$stages = $_FIELD_NAME = $_SQL_LIMIT = $_SQL_LIMIT_SUB = "" ;$_GET['_schedule'] = (isset($_GET['_schedule']) && ($_GET['_schedule']>=1)) ? $_GET['_schedule'] : 2;
	
	//list($fname,$retrieveSelect) = retrieveSelect($_GET['_field'],$_GET['_module']);
	
	//$rs_w =$db->Execute("SELECT `B`.`id`,`A`.`description`,`B`.`name` FROM `workflows_page_workflows` AS `A` INNER JOIN `workflows_groups` AS `B` ON `A`.workflow=`B`.id WHERE `B`.`page`='".$_GET['_module']."' AND `A`.pub=1 AND `A`.del=1 AND `B`.pub=1 AND `B`.del=1 ORDER BY `B`.rank ASC");
	//$colspan = (($rs_w) && ($rs_w->numRows() >= 1)) ? 8 + $rs_w->numRows() : 8;
	
	$fname = (isset($fname) && (!empty($fname))) ? strtoupper($fname) : "ITEMS";
	
	$_MODULE_NAME[1] = "Stock Inventory";
	$_MODULE_NAME[3] = "Accounting";
	
	// --- Module Name
	
	// --- Module Name
	
	$_ORG_NAME = $_ORG_LOGO = "";
	
	if($_SESSION['accesses']->_login['accounting360'][0] == 1){$_ORG_NAME = "House of Nnyane";$_ORG_LOGO = "hom-main-logo.jpg";}
	else if($_SESSION['accesses']->_login['accounting360'][0] == 2){$_ORG_NAME = "SnM Security Consultants (Pty) Ltd";$_ORG_LOGO = "snm-logo.png";}
	
	else if($_SESSION['accesses']->_login['accounting360'][0] == 4){$_ORG_NAME = "KMJ Mining and Engineering";$_ORG_LOGO = "ccba-logo-main.png";}
	
	/*$rs_modules = $db->Execute("
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
		
		$rs_field = $db->Execute("SELECT `B`.name,`B`.id FROM `reporting_links` AS `A` INNER JOIN `pages_fields` AS `B` ON `A`.item=`B`.id WHERE `B`.id='".$_GET['_field']."' AND `A`.type='field' AND `A`.pub='1' AND `A`.del='1' ORDER BY `B`.name ASC LIMIT 1");
		if(($rs_field) && ($rs_field->_numOfRows >= 1))
		{
			$_FIELD_NAME = $rs_field->fields['name'];
			
		}
	}*/
	
	if(isset($_GET['_field']) && ($_GET['_field']=='user'))
	{
		$_FIELD_NAME = "USERS";$fname = "USERS WHO LOGGED ITEMS";
	}
	
	// --- Filters
	
	if(isset($_GET['_date_from']) && (!empty($_GET['_date_from'])))
	{
		$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) >= TO_DAYS('".$_GET['_date_from']."') ";
		$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) >= TO_DAYS('".$_GET['_date_from']."') ";
		$_USER_DISPLAY .= " <i style='color:#333;'>From:</i> <span style='color:#00A000;'>".date("l",strtotime($_GET['_date_from']))." the ".date("jS",strtotime($_GET['_date_from']))." of ".date("F, Y",strtotime($_GET['_date_from']))."</span>";
		$_PDF_DISPLAY .= "FROM: ".date("jS",strtotime($_GET['_date_from']))." of ".date("F, Y",strtotime($_GET['_date_from']));
	}
	
	if(isset($_GET['_date_to']) && (!empty($_GET['_date_to'])))
	{
		$_SQL_LIMIT .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) <= TO_DAYS('".$_GET['_date_to'] ."') ";
		$_USER_DISPLAY .= " <i style='color:#333;'>Up to:</i> <span style='color:#DD7000;'>".date("l",strtotime($_GET['_date_to']))." the ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']))."</span>";
		$_PDF_DISPLAY .= " UP TO: ".date("jS",strtotime($_GET['_date_to']))." of ".date("F, Y",strtotime($_GET['_date_to']));
	}
	
	if(!(isset($_SQL_LIMIT) && (!empty($_SQL_LIMIT))))
	{
		//$_SQL_LIMIT = " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		//$_SQL_LIMIT_SUB .= " AND TO_DAYS(`A`.date) = TO_DAYS(CURRENT_DATE()) " ;
		$_USER_DISPLAY .= " <i style='color:#333;'>On:</i> <span style='color:#DD7000;'>".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()))."</span>";
		$_PDF_DISPLAY .= " ON: ".date("l",strtotime(NOW()))." the ".date("jS",strtotime(NOW()))." of ".date("F, Y",strtotime(NOW()));
	}
	
	$_SQL_LIMIT = "";
	
	if(isset($_GET['_employee']) && ($_GET['_employee'] >= 1))
	{
		$_SQL_LIMIT .= " AND `A`.`item_location`= '".$_GET['_employee']."' ";
		
	}
	
	$_EXUECTUTE = true;
	
	$fname = "Stock Inventory Overview ";
	
	$_XLS_DOC = array(array(""), array("Reporting on Module: ",$_MODULE_NAME[1]), array("Parent: ",$_MODULE_NAME[2]), array("Inventory Control of",$_FIELD_NAME),array(""));
	$_XLS_STYLE = array(array(""),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array("hdr_bold_fz10_bgred_colorred","hdr_bold_fz10_bgred_colorred"),array(""));
	
	$_XLS_DOC_TMP[] = $fname;$_XLS_STYLE_TMP[] = "hdr_bold_fz10_bgred_colorred";
	$_body = "
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:right;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#aaa;text-indent:2px;font-size:12px;font-weight:normal;'><i>Reporting on Module: <span style='color:#555;'>".$_MODULE_NAME[1]."</span> of Parent <span style='color:#555;'>".$_MODULE_NAME[2]."</span></i>&nbsp;</td>	     
		</tr>
		<tr>
			<td colspan='$colspan' style='background:#eaeaea;text-align:left;line-height:25px;height:25px;border-bottom:1px solid #ccc;color:#053e57;text-indent:2px;font-size:13px;'><b>Inventory Control of <span style='color:#e62899;'>$_FIELD_NAME</span>$_USER_DISPLAY</b></td>	     
		</tr>
		<tr>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:left;'>&nbsp;<b>Item Name</b></td>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:center;'>&nbsp;<b>Item Barcode No.</b></td>
			<td style='border-bottom:1px solid #ccc;font-size:11px;background:#e3e3e3;line-height:23px;color:#DB0000;text-align:center;'>&nbsp;<b>Category</b></td>";
		
		$_body .= "
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Warehouse Bin Location&nbsp;</td>
				<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='left'>&nbsp;Description&nbsp;</td>";
			
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
			<td style='background:#c3daef;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Cost Price</td>
			<td style='background:#FBE3E4;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Qty</td>	
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>Count</td>
			<td style='background:#E6EFC2;border-bottom:1px solid #ccc;color:#125687;font-weight:bold;font-size:11px;' align='center'>&nbsp;Total Cost&nbsp;</td>
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
	}
	
	$rs =$db->Execute("
			  SELECT `A`.id
				".$_SQL_INVENTORY_SELECT."
			  FROM `_mod_".$_GET['_module']."` AS `A`
			  WHERE `A`.`status`<>2 AND 1=1 $_SQL_LIMIT 
			  GROUP BY `A`.`id`
			  ".$_SQL_INVENTORY_ORDER_BY."
			  ");*/
	
	
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_barcode` AS `_barcode`";
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_serial_no` AS `_serial_no`";
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_name` AS `_name`";
	$_SQL_INVENTORY_SELECT .= ",`A`.`qty` AS `_qty`";$_SQL_INVENTORY_ORDER_BY = "ORDER BY `A`.`qty` DESC";
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_cost_price` AS `_cost`";
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_classification` AS `_bin`";
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_classification` AS `_category`";
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_brand` AS `_brand`";
	$_SQL_INVENTORY_SELECT .= ",`A`.`item_description` AS `_description`";
	
	$_SQL_LIMIT = "";
	
	$rs =$db->Execute("
			  SELECT `A`.id
				".$_SQL_INVENTORY_SELECT."
			  FROM `_accounting_360_".$_SESSION['accesses']->_login['accounting360'][0]."_inventory_sku` AS `A`
			  WHERE `A`.pub=1 AND `A`.del=1 AND 1=1 $_SQL_LIMIT 
			  GROUP BY `A`.`id`
			  ".$_SQL_INVENTORY_ORDER_BY."
			  ");
	
	if(($rs) && ($rs->numRows() >= 1))
	{
		$_binLocatinsArr = genericItemsArr("lists"," ");
		
		while(!$rs->EOF)
		{
			$_XLS_DOC_TMP = array();$_XLS_STYLE_TMP = array();
			//$itemName = (isset($retrieveSelect[$rs->fields['item']]) && (!empty($retrieveSelect[$rs->fields['item']]))) ? $retrieveSelect[$rs->fields['item']] : "Item ".$rs->fields['item'];
			$itemName = $rs->fields['_name'];
			$category = isset($_binLocatinsArr[$rs->fields['_category']]) ? $_binLocatinsArr[$rs->fields['_category']] : "";
			$bin = isset($_binLocatinsArr[$rs->fields['_bin']]) ? $_binLocatinsArr[$rs->fields['_bin']] : "";
			
			$_XLS_DOC_TMP[] = $itemName;$_XLS_STYLE_TMP[] = "";
			
			$exploreCursorStyle = $exploreImg = "";
			if(isset($_GET['_isexplore']) && (!empty($_GET['_isexplore'])))
			{
				$exploreImg = "<img id='img-toggle-".$rs->fields['_name']."' src='". WWWROOT."images/icons/arrow_down.png' style='margin:3px;float:left;' />";
				$exploreCursorStyle = "cursor:zoom-out;";
			}
			
			$_body .= "
				<tr>
					<td id='explore-".$rs->fields['item']."' style='".$exploreCursorStyle."border-bottom:1px solid #ccc;font-size:11px;color:#008000;background:#f3f3f3;line-height:23px;font-weight:bold;' align='left'>&nbsp;".$exploreImg.$itemName."&nbsp;</td>";
				
			if((isset($rs->fields['_qty'])) && ($rs->fields['_qty']>=1)){$rs->fields['_qty'] = $rs->fields['_qty'] - 1;}	
			$_body .= "
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".$rs->fields['_barcode']."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".$category."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b>".$bin."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='left'><b>&nbsp;".$rs->fields['_description']."</b>&nbsp;</td>
					
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#c3daef;' align='center'><b>".$rs->fields['_cost']."</b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#FBE3E4;' align='center'><b>".$rs->fields['_qty']."</b>&nbsp;</td>
					
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#f9f9f9;' align='center'><b></b>&nbsp;</td>
					<td style='border-bottom:1px solid #ececec;color:#213552;font-size:11px;background:#E6EFC2;' align='right'>".(isset($rs->fields['_qty']) && ($rs->fields['_qty'] != 0)?"<b>R ".number_format(round($rs->fields['_qty']*$rs->fields['_cost'],2),2,"."," ")."</b>&nbsp;":"&nbsp;")."</td>
					";
					
			$_TOTAL = $_TOTAL + ($rs->fields['_qty']*$rs->fields['_cost']);
							
			$totalArr['reject'] += $rs->fields['reject'];
			$totalArr['approve'] += $rs->fields['approve'];
			$totalArr['total'] += $rs->fields['total'];
			
			$_XLS_DOC_TMP[] = $rs->fields['reject'];$_XLS_STYLE_TMP[] = "";
			$_XLS_DOC_TMP[] = $rs->fields['approve'];$_XLS_STYLE_TMP[] = "";
			$_XLS_DOC_TMP[] = $rs->fields['total'];$_XLS_STYLE_TMP[] = "";
			//echo $rs->fields['_barcode']."<br />";
			$_DATA_ARR[] = array(substr($itemName,0,18),$rs->fields['_barcode'],$category,$bin,substr($rs->fields['_description'],0,10),$rs->fields['_cost'],$rs->fields['_qty'],0,(isset($rs->fields['_qty']) && ($rs->fields['_qty'] != 0)?"R ".number_format(round($rs->fields['_qty']*$rs->fields['_cost'],2),2,"."," ")."":""));
			
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
			<td colspan='5' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>SUB-TOTAL:&nbsp;</td>
			<td colspan='3' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>&nbsp;</td>";
		
	$_XLS_DOC_TMP[] = $totalArr['reject'];$_XLS_DOC_TMP[] = $totalArr['approve'];$_XLS_DOC_TMP[] = $totalArr['total'];
	
	$_body .= "
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#008000;font-weight:bold;font-size:11px;' align='right'>R ".number_format(round($_TOTAL,2),2,"."," ")."&nbsp;</td>
		</tr>";
		
	$_body .= "
		<tr>
			<td colspan='5' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>VAT (15%):&nbsp;</td>
			<td colspan='3' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>&nbsp;</td>";
		
	//$_XLS_DOC_TMP[] = $totalArr['reject'];$_XLS_DOC_TMP[] = $totalArr['approve'];$_XLS_DOC_TMP[] = $totalArr['total'];
	
	$_body .= "
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#008000;font-weight:bold;font-size:11px;' align='right'>R ".number_format(round($_TOTAL*0.15,2),2,"."," ")."&nbsp;</td>
		</tr>";
		
	$_body .= "
		<tr>
			<td colspan='5' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>GRAND TOTAL:&nbsp;</td>
			<td colspan='3' style='background:#e3e3e3;border-bottom: 1px solid #ccc;color:#DB0000;font-weight:bold;font-size:11px;line-height:23px;text-align:right;'>&nbsp;</td>";
		
	//$_XLS_DOC_TMP[] = $totalArr['reject'];$_XLS_DOC_TMP[] = $totalArr['approve'];$_XLS_DOC_TMP[] = $totalArr['total'];
	
	$_body .= "
			<td style='background:#e3e3e3;border-bottom:1px solid #ccc;color:#008000;font-weight:bold;font-size:11px;' align='right'>R ".number_format(round($_TOTAL*1.15,2),2,"."," ")."&nbsp;</td>
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
			$_body = "<center><a style='text-decoration:none;' target='_blank' href='../../exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_module']." - ".date("Y-F-d").".pdf'><span style='background-color:#ddd;border:2px solid #00A000;padding:3px;color:#008000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download PDF Report</span></a></center>".$_body;
		}
		
		echo $_body;
	}
	
	if((isset($_GET['_isemail']) && ($_GET['_isemail'] >= 1)) || (isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1)) || (isset($_GET['is_pdf']) && ($_GET['is_pdf'] >= 1)))
	{
		// -- Write Email to temporary folder
		
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
		}
		
		// -- PDF Generation
		//echo "../../docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname);
		//@require_once("../../docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname));
		//echo "http://oefaspen.net/docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname);
		//$ret = @file("http://oefaspen.net/docs/dataobjects/activities/tmp/_graphCharts.php?_file=".urlencode($fname));
		
		header("Cache-Control: public, must-revalidate");
		header("Pragma: hack");
		header("Content-Type: text/pdf");
		
		define('ROOTPATH_1',"/usr/www/users/hrdcsaqhwx/oefspiderws.hrdcsaqa.net/");
		//require(ROOTPATH_1."inc/pdf/ellipse.php");
		require(ROOTPATH_1."inc/pdf/fpdf186/fpdf.php");
		
		header("Content-Disposition: attachment; filename=BUSINESS-PROCESS-WORKFLOW-.pdf");
		
		//$pdf=new PDF_Ellipse('P','mm',array(210,297));
		$pdf = new FPDF('P','mm',array(210,297));
		//$pdf->Open();
		
		$_WIDTH = 216; $_HEIGHT = 297; // --- Document Width & Height
		
		$_START_LEFT = 24;$_START_TOP = 15;
		$_P_WIDTH = $_WIDTH - ($_START_LEFT*2);// --- Page Content Width
		$_PC_WIDTH = $_P_WIDTH - 13; // --- Refined  content width
		
		$p = 1;
		
		// _____________________________________________________ Page # 1 - Cover Page __________________________________________________
		
				$p = 0;
				
				$pdf->AddPage('L');$p++;$h = $_START_TOP;$w = $_START_LEFT;$pdf->SetFont('arial','',10);
				
				$pdf->SetDrawColor(0);$pdf->SetLineWidth(0.1);//$pdf->Line($_HEIGHT/2,0,$_HEIGHT/2,$_WIDTH); 
				
				$h=0;$w = 155;
				$pdf->SetFillColor(75,75,75);$pdf->SetTextColor(0);$pdf->SetFont('arial','B',20);
				//$pdf->SetAlpha(0.25);$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h);$pdf->MultiCell($_P_WIDTH/2.5,24,"","","C",true);$pdf->SetAlpha(1);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+24);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+9.5);$pdf->Cell($_P_WIDTH/2.5,6,"CURRENT ASSET","",0,"C",false);
				$pdf->SetFont('arial','',11);$pdf->SetTextColor(135);$pdf->SetXY($w+3.5+($_P_WIDTH/2.5),$h+17.55);$pdf->Cell($_P_WIDTH/2.61,6,"( Total Stock Inventory Asset Value ) ",0,0,"C",false);
				
				$h = $_START_TOP;$w = $_START_LEFT;
				
				$pdf->SetTextColor(0);$pdf->SetFont('arial','',34);
				
				$pdf->SetXY(70-2,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"Stock Inventory Overview",0,"L");
				$pdf->SetTextColor(155);
				$pdf->SetXY(70-2,$h+16.85);$pdf->MultiCell($_HEIGHT,15,"(Stock on hand)",0,"L");
				
				$pdf->SetTextColor(0);
				$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$_ORG_LOGO,10,13.5,55,0,'','');
				
				$h+=8*2.25;
				$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(0);$pdf->Line(70,$h-1,$_HEIGHT-6.0,$h-1);
				
				$h+=8*1.65;$center = 65.5;
				
				// _____________________________________________________ ::: __________________________________________________
				
				//$pdf->AddPage();$p = 1;
				$xPos = 15-6.0;$yPos = 55;
				
				// --- Document Header
				
				$logo_main = strtolower(SYST_ABBR)."-main-logo.png";
				//if((!empty($logo_main)) && file_exists(ROOTPATH."images/organizations/".$logo_main)){$pdf->Image(ROOTPATH."images/organizations/".$logo_main,150,$yPos-10,50,0,'','');}
				
				//$pdf->SetTextColor(100);$pdf->SetFont('arial','',18);$pdf->Text($xPos,$yPos,"Stock Inventory Overview");$yPos+=8;
				//$pdf->SetTextColor(150);$pdf->SetFont('arial','B',10);$pdf->Text($xPos,$yPos,"Reporting On:");$pdf->SetTextColor(0,0,0);$pdf->Text(41.5,$yPos,$fname);$yPos+=6;
				$pdf->SetTextColor(120);$pdf->SetFont('arial','B',10);$pdf->Text($xPos,$yPos,"Module Name:");$pdf->SetTextColor(75);$pdf->Text(48.5,$yPos,$_MODULE_NAME[1]);$yPos+=6;
				$pdf->SetTextColor(120);$pdf->SetFont('arial','B',10);$pdf->Text($xPos,$yPos,"Module Parent Name:");$pdf->SetTextColor(75);$pdf->Text(48.5,$yPos,$_MODULE_NAME[3]);$yPos+=6;
				$pdf->SetTextColor(185,0,0);$pdf->SetFont('arial','B',10);$pdf->Text($xPos,$yPos,$_PDF_DISPLAY);$pdf->SetTextColor(75);//$pdf->Text(43.5,$yPos,$_PDF_DISPLAY);
				
				$pdf->SetTextColor(0);
				$pdf->SetFont('arial','B',12);$pdf->Text(198.5,$yPos-6,"Accounting Period: ".date("F")."-".date("Y"));
				$pdf->SetTextColor(0);
				$pdf->SetFont('arial','',10);$pdf->Text(198.5,$yPos,"".substr("Date: ".NOW(),0,10)." @ ".substr(NOW(),11,25));
				
				$pdf->SetFillColor(252);$pdf->SetDrawColor(125);$pdf->SetFont('arial','',11);
				
				//$pdf->Line(0,$yPos+3,210,$yPos+3);
				$yPos+=6;$xPos = 15;
				$h = $yPos;$w = $_START_LEFT-15.5;
				
				$pdf->SetFont('arial','',10);
				
					$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->SetFillColor(39,44,49);$pdf->SetTextColor(255);
					
					//$pdf->SetFont('arial','',10);
					//$pdf->SetTextColor(0);$pdf->SetXY($w+110,$h);$pdf->MultiCell(80,6,"Quantity Purchased",1,'C',FALSE);
					//$pdf->SetFillColor(244,188,191);$pdf->SetTextColor(0);$pdf->SetXY($w+110+80,$h);$pdf->MultiCell(45,6,"Received",1,'C',true);
					//$pdf->SetFillColor(128,246,166);$pdf->SetXY($w+110+80+45,$h);$pdf->MultiCell(45,6,"Sold",1,'C',true);
					//$h+=6;
					$pdf->SetFillColor(25);$pdf->SetTextColor(255);$pdf->SetFont('arial','',10);
					$pdf->SetXY($w,$h);$pdf->MultiCell(50,6,"Item Name",1,'L',true);
					$pdf->SetXY($w+50,$h);$pdf->MultiCell(40,6,"Barcode No.",1,'C',true);
					$pdf->SetXY($w+50+40,$h);$pdf->MultiCell(30,6,"Category",1,'L',true);
					$pdf->SetXY($w+50+40+30,$h);$pdf->MultiCell(30,6,"Wareh. Bin Loc.",1,'C',true);
					$pdf->SetXY($w+50+40+25+35,$h);$pdf->MultiCell(30,6,"Description",1,'L',true);
					$pdf->SetXY($w+50+40+15+25+20+30,$h);$pdf->MultiCell(25,6,"Cost Price",1,'R',true);
					$pdf->SetXY($w+50+40+15+20+20+30+30,$h);$pdf->MultiCell(25,6,"Qty",1,'C',true);
					$pdf->SetXY($w+50+40+15+20+20+30+30+25,$h);$pdf->MultiCell(20,6,"Count",1,'C',true);
					$pdf->SetXY($w+50+40+15+20+20+30+30+30+15,$h);$pdf->MultiCell(30,6,"Cost",1,'R',true);
					
					$h+=6;
					$pdf->SetFont('arial','',10);$pdf->SetTextColor(39,44,49);
					
				$_INDEX = 0;$_CUT_OFF = 20;
				foreach($_DATA_ARR as $k=>$v)
				{
					//$pdf->SetXY($xPos,$yPos);$pdf->SetFillColor(245);$pdf->Cell($tWidth,$tHeight,$k,1,1,'L',true);$pdf->SetXY($xPos+$tWidth,$yPos);$pdf->SetFillColor(255);$pdf->Cell($tWidth/2.9,$tHeight,$v,1,1,'R',true);$_i++;
					$pdf->SetTextColor(0);$pdf->SetFillColor(255);
					
					    $pdf->SetXY($w,$h);$pdf->MultiCell(45,5,$v[0],1,'L',true);$pdf->SetFont('arial','',9.5);$pdf->SetXY($w+45,$h);$pdf->MultiCell(50,5,$v[1],1,'L',true);$pdf->SetFont('arial','',10);
					    $pdf->SetXY($w+50+40,$h);$pdf->MultiCell(30,5,$v[2],1,'L',true);
					    $pdf->SetXY($w+50+40+30,$h);$pdf->MultiCell(30,5,$v[3],1,'C');$pdf->SetXY($w+50+40+25+35,$h);$pdf->MultiCell(30,5,substr($v[4],0,15),1,'L');
					    $pdf->SetXY($w+50+40+15+25+20+30,$h);$pdf->MultiCell(30,5,"R ".$v[5],1,'R');
					    $pdf->SetFillColor(244,188,191);
					    $pdf->SetXY($w+50+40+15+20+20+30+35,$h);$pdf->MultiCell(20,5,$v[6],1,'C',true);
					    $pdf->SetFillColor(128,246,166);
					    $pdf->SetXY($w+50+40+15+20+20+30+30+25,$h);$pdf->MultiCell(20,5,"",1,'C');$pdf->SetXY($w+50+40+15+20+20+30+30+30+15,$h);$pdf->MultiCell(30,5,"".$v[8],1,'R',true);
					    
					    $h+=5;$_INDEX++;
					    
					    if($_INDEX == $_CUT_OFF)
					    {
						 $pdf->AddPage('L');$p++;$h = $_START_TOP;//$w = $_START_LEFT;
						 //$pdf->SetAlpha(0.15);$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$rs_modules->fields['orglogo'],-1.5,163.5,65,0,'','');$pdf->SetAlpha(1);
						 $pdf->SetFont('arial','',10);
						 
						 $_INDEX = 0;
						 if($p >= 2){$_CUT_OFF = 32;}
					    }
					    
				}
				
				$pdf->SetFillColor(255);$pdf->SetTextColor(245,0,0);$pdf->SetFont('arial','B',10);
				//$pdf->SetXY($w,$h);$pdf->MultiCell(180,5,"",1,'R',true);
				$pdf->SetFillColor(255);$pdf->SetTextColor(245,0,0);
				$pdf->SetXY($w+180,$h);$pdf->MultiCell(70,7,"Total Inventory Asset Value:",1,'R',true);
				$pdf->SetFillColor(255);$pdf->SetTextColor(245,0,0);$pdf->SetFont('arial','B',10);
				$pdf->SetXY($w+180+70,$h);$pdf->MultiCell(30,7,"R ".number_format(round($_TOTAL,2),2,"."," "),1,'R',true);
				 $h+=7;
				$pdf->SetXY($w+180,$h);$pdf->MultiCell(70,7,"VAT (15%):",1,'R',true);
				$pdf->SetFillColor(255);$pdf->SetTextColor(245,0,0);$pdf->SetFont('arial','B',10);
				$pdf->SetXY($w+180+70,$h);$pdf->MultiCell(30,7,"R ".number_format(round($_TOTAL*0.15,2),2,"."," "),1,'R',true);
				 $h+=7;
				$pdf->SetXY($w+180,$h);$pdf->MultiCell(70,7,"GRAND TOTAL:",1,'R',true);
				$pdf->SetFillColor(255);$pdf->SetTextColor(245,0,0);$pdf->SetFont('arial','B',10);
				$pdf->SetXY($w+180+70,$h);$pdf->MultiCell(30,7,"R ".number_format(round($_TOTAL*1.15,2),2,"."," "),1,'R',true);
				
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(0);
				$pdf->Text(147.0,203.5,$p);$pdf->SetFont('arial','',10);$pdf->Text(156,203.5,$rs_modules->fields['orgname']." (O-Framework V.2.10)");
				
				// --- Footer
				
				//$pdf->SetDrawColor(205);$pdf->SetLineWidth(0.1);$pdf->Line(0,277,210,277);$pdf->SetTextColor(175);$pdf->SetFont('arial','',9);
				//$pdf->Image(CONSUMER_ROOTPATH."images/organizations/aspen-main-logo.png",15,1,10,0,'','');$pdf->Text(85,285,"OVH Enterprise Framework v2.10");
				
						
				// _____________________________________________________ Page: SECTION 1: PROJECT DESCRIPTION AND PURPOSE __________________________________________________
				
				$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
				
				$h=0;$w = 73;
				$pdf->SetFillColor(75,75,75);$pdf->SetTextColor(0);$pdf->SetFont('arial','B',22);
				//$pdf->SetAlpha(0.45);$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h);$pdf->MultiCell($_P_WIDTH/2.5,14,"","","C",true);$pdf->SetAlpha(1);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+5.0);$pdf->Cell($_P_WIDTH/2.5,6,date("F")." ".date("Y"),"",0,"C",false);
				//$pdf->SetFont('arial','',11.5);$pdf->SetTextColor(55);$pdf->SetXY($w+3+($_P_WIDTH/2.5) - 7.5,$h+17.75);$pdf->Cell($_P_WIDTH/2.61,6,"( Timesheet Book with 238 Weeks )","",0,"C",false);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+14);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
				
				$h = $_START_TOP;$w = $_START_LEFT;
				
			       $pdf->SetTextColor(0);$pdf->SetFont('arial','',38); 
				
				$pdf->SetXY(10-2,$h-10.85);$pdf->MultiCell($_HEIGHT,15,"Balance Sheet ",0,"L");
				$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$_ORG_LOGO,10-0,$h+3,46,0,'','');
				
				
				$pdf->SetFont('arial','B',18);$pdf->SetTextColor(25);
				$pdf->SetXY($_START_LEFT+105,$h+8);$pdf->Cell(30,10,date("Y",strtotime ('-1 year',strtotime(date('Y')))),0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h+8);$pdf->Cell(30,10,date('Y'),0,"R","R");
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(105);
				$pdf->SetXY($_START_LEFT+105,$h+12.5);$pdf->Cell(30,10,"prior year",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h+12.5);$pdf->Cell(30,10,"current year",0,"R","R");
				
				$pdf->SetTextColor(0);$pdf->SetFont('arial','',18);
				
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.6);
				$pdf->SetDrawColor(79,167,71);$pdf->SetFillColor(79+145,167+145,71+145);$pdf->SetLineWidth(0.6);
				$h += 21;
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,10," ASSETS ","B","L",true);
				
				$h = 30;
				
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.6);
				$pdf->SetDrawColor(220-55,66-55,69-55);$pdf->SetFillColor(220+165,66+165,69+165);$pdf->SetLineWidth(0.6);
				
				 
				 // --- --- ---- Current assets:
				
				$h = $pdf->GetY()+3.2-2;
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.2);
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY(0,$h);$pdf->MultiCell(55,6," Current assets:","B","R",true);
				
				    $h = $pdf->GetY()+1.2; // --- --- --- Sub Sections
				    
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(105);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Cash",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Accounts receivable",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Pre-paid expenses",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetTextColor(245,0,0);$pdf->SetFont('arial','B',10);$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Inventory (Stock)",0,"L");$pdf->SetFont('arial','',10);$pdf->SetTextColor(105);$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetTextColor(245,0,0);$pdf->SetFont('arial','B',10);$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R ".number_format(round($_TOTAL,2),2,"."," "),0,"R","R");$h+=6;
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Total current assets","TB","L");$pdf->SetXY($_START_LEFT+101,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$pdf->SetTextColor(245,0,0);$pdf->SetFont('arial','B',10);$pdf->SetXY($_START_LEFT+106+30,$h);$pdf->Cell(34,6,"R ".number_format(round($_TOTAL,2),2,"."," "),"TB","R","R");$h+=6;
				
				
				 // --- --- ---- Fixed assets:
				
				$h = $pdf->GetY()+9.2;
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.2);
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY(0,$h);$pdf->MultiCell(55,6," Fixed assets:","B","R",true);
				
				    $h = $pdf->GetY()+1.2; // --- --- --- Sub Sections
				    
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(105);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Properties",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Furniture and Fixtures",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Vehicles",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Machinery and equipment",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Computer hardware, tablets, software",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Research and development",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Total fixed assets","TB","L");$pdf->SetXY($_START_LEFT+101,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$pdf->SetXY($_START_LEFT+106+30,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$h+=6;
				
				
				 // --- --- ---- LIABILITIES --- --- ---- 
				
				$h = $pdf->GetY()+13.2;
				
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.6);
				$pdf->SetDrawColor(79,167,71);$pdf->SetFillColor(79+145,167+145,71+145);$pdf->SetLineWidth(0.6);
				
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,10," LIABILITIES ","B","L",true);
				
				
				 // --- --- ---- Fixed assets:
				
				$h = $pdf->GetY()+3.2;
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.2);
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY(0,$h);$pdf->MultiCell(55,6," Current liabilities:","B","R",true);
				
				    $h = $pdf->GetY()+1.2; // --- --- --- Sub Sections
				    
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(105);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Accounts payable",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Accrued expenses",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Unearned revenue",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Total current liabilities","TB","L");$pdf->SetXY($_START_LEFT+101,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$pdf->SetXY($_START_LEFT+106+30,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$h+=6;
				
				 // --- --- ---- Fixed assets:
				
				$h = $pdf->GetY()+9.2;
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.2);
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY(0,$h);$pdf->MultiCell(55,6," Long-term liabilities:","B","R",true);
				
				    $h = $pdf->GetY()+1.2; // --- --- --- Sub Sections
				    
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(105);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Loans",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Mortgages",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Total long-term liabilities","TB","L");$pdf->SetXY($_START_LEFT+101,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$pdf->SetXY($_START_LEFT+106+30,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$h+=6;
				
				 // --- --- ---- EQUITY --- --- ---- 
				
				$h = $pdf->GetY()+13.2;
				
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.6);
				$pdf->SetDrawColor(79,167,71);$pdf->SetFillColor(79+145,167+145,71+145);$pdf->SetLineWidth(0.6);
				
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,10," SHAREHOLDER'S EQUITY ","B","L",true);
				
				
				 // --- --- ---- Fixed assets:
				
				$h = $pdf->GetY()+3.2;
				$pdf->SetFillColor(100+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.2);
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(55);$pdf->SetXY(0,$h);$pdf->MultiCell(55,6," Current liabilities:","B","R",true);
				
				    $h = $pdf->GetY()+1.2; // --- --- --- Sub Sections
				    
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(105);
				//$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Investment Capital",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				//$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Retained Earnings",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				//$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Drawing",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				//$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Ordinary Share Capital",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				//$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);
				//$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Total shareholder's equity","TB","L");$pdf->SetXY($_START_LEFT+101,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$pdf->SetXY($_START_LEFT+106+30,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$h+=6;
				
				// ---- End of Document
				
				// --- Document Author
				
				$pdf->SetAuthor(SYST_ABBR.' - OVH Enterprise Framework');   
				$pdf->SetTitle("Stock Inventory Overview - ".$_GET['_module']." - ".date("Y-F-d")."");
				
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