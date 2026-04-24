<?
ini_set("display_errors",1);
ignore_user_abort(true);
set_time_limit(0);
ini_set('memory_limit','-1');
error_reporting(E_ERROR | E_PARSE);
 

require_once('../../inc/connection.php');

$_body = $_USER_DISPLAY = $_PDF_DISPLAY = "";$_EXUECTUTE = false;

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_GET['_module']) && ($_GET['_module'] >= 1) && isset($_GET['_item']) && (!empty($_GET['_item'])))
{
	require_once('../../inc/excel/ovh/ovhxls_xml.php');
	$_XLS = new OVHXLS;
	
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	
	$wfArr = $totalArr = $_MODULE_NAME = $_XLS_DOC = $_XLS_STYLE = $_DATA_ARR = array();$stages = $_FIELD_NAME = $_SQL_LIMIT = $_SQL_LIMIT_SUB = "" ;$_GET['_schedule'] = (isset($_GET['_schedule']) && ($_GET['_schedule']>=1)) ? $_GET['_schedule'] : 2;
	
	$_PORDER_MODULE = 0;$_datesArr = array("&nbsp;","&nbsp;","&nbsp;","&nbsp;","&nbsp;","&nbsp;","&nbsp;","&nbsp;");
	
	$rs_pages =$db->Execute("SELECT `A`.`id` FROM `pages` AS `A` WHERE `A`.`is_inventory_linked`='".$_GET['_module']."' AND `A`.`is_inventory_purchase_order`='1' AND `A`.pub=1 AND `A`.del=1 LIMIT 1");
	
	if(($rs_pages) && ($rs_pages->numRows() >= 1))
	{
		$_PORDER_MODULE = $rs_pages->fields['id'];
		
	}
	$_body = $_error = "";
	;
// is_qc,isfulfilled
	$rs =$db->Execute("
			  SELECT `A`.po_id,`A`.sku_id,`A`.d_received,`A`.so_id,`A`.d_ordered,`A`.is_dispatched,`A`.d_dispatched,`A`.is_delivered,`A`.d_delivered,`A`.d_active_waranty,`A`.d_active_maintenance,`A`.customer 
			  FROM `_mod_".$_PORDER_MODULE."_grv_product_life_cycle` AS `A`
			  WHERE `A`.`serial_no`='".$_GET['_item']."' 
			  LIMIT 1;
			  ");
		
	if(($rs) && ($rs->numRows() >= 1))
	{
		for($_i=1; $_i<7;$_i++)
		{
			$_XLS_DOC_TMP = array();$_XLS_STYLE_TMP = array();
			
			if($_i==1){$_is_active = "";if($rs->fields['po_id']>=1){$_is_active = "active ";$_datesArr[1] = $rs->fields['d_received'];}$_body .= '<li class="'.$_is_active.'step0"></li>';}
			if($_i==2){$_is_active = "";if($rs->fields['po_id']>=1){$_is_active = "active ";$_datesArr[2] = $rs->fields['d_received'];}$_body .= '<li class="'.$_is_active.'step0"></li>';}
			if($_i==3){$_is_active = "";if($rs->fields['so_id']>=1){$_is_active = "active ";$_datesArr[3] = $rs->fields['d_ordered'];}$_body .= '<li class="'.$_is_active.'step0"></li>';}
			if($_i==4){$_is_active = "";if($rs->fields['is_dispatched']>=1){$_is_active = "active ";$_datesArr[4] = $rs->fields['d_dispatched'];}$_body .= '<li class="'.$_is_active.'step0"></li>';}
			if($_i==5){$_is_active = "";if($rs->fields['is_delivered']>=1){$_is_active = "active ";$_datesArr[5] = $rs->fields['d_delivered'];}$_body .= '<li class="'.$_is_active.'step0"></li>';}
			if($_i==6){$_is_active = "";if($rs->fields['is_delivered']>=1){$_is_active = "active ";$_datesArr[6] = $rs->fields['d_delivered'];}$_body .= '<li class="'.$_is_active.'step0"></li>';}
		}
		
		$_error = "<span style='color:#39892f;'>Serial no. '".$_GET['_item']."' fulfillment process successfully retrieved.</span>";
	}
	else
	{
		$_body .= '<li class="step0"></li>';
		$_body .= '<li class="step0"></li>';
		$_body .= '<li class="step0"></li>';
		$_body .= '<li class="step0"></li>';
		$_body .= '<li class="step0"></li>';
		$_body .= '<li class="step0"></li>';
		
		$_error = "<span style='color:#ee6263;'>Error 'Serial no. '".$_GET['_item']."' does not exist.</span>";
	}
	
	$_XLS_DOC_TMP = array();
	
	$_XLS_DOC[] = array();$_XLS_DOC[] = $_XLS_DOC_TMP;//$_XLS_STYLE[] = "hdr_bold_fz10_bgred_colorred";

	$_body = '
	<center style="background:transparent;margin-top;">
		<strong style="font-size:500%;">GRV Fulfillment Process</strong>
		<br /><span style="font-size:200%;">Stock or Goods Received Voucher Overview</span>
		<br />
		<br /><strong style="font-size:120%;">'.$_error.'</strong>
		<br />
	</center>
    <div class="card" style="background:transparent;margin-top;margin-top:25px;">
        
        <div class="row d-flex justify-content-center" style="border:0px solid #f00;width:101.5%;margin-top:0px;margin-left:50px;">
            <div class="col-12">
		<ul id="progressbar" class="text-center">
		    '.$_body.'
		</ul>
            </div>
        </div>
        
	
	<div class="row justify-content-between " style="border:0px solid #f00;width:89%;margin-left:50px;">
            <div class="row d-flex icon-content" style="margin-left:3.5%;width:11%;float:left;border:0px solid #f00;text-align:center;" align="center">
                <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold" style="font-weight:bold;font-size:110%;">Item<br>Received (PO)</p>
                    <p class="font-weight-bold" style="color:#f88;font-size:110%;">'.substr($_datesArr[1],0,11).'</p>
                </div>
            </div>
            <div class="row d-flex icon-content" style="margin-left:4%;width:11%;float:left;border:0px solid #f00;" align="center">
                <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold" style="font-weight:bold;font-size:110%;">Quality<br>Check (QC)</p>
                    <p class="font-weight-bold" style="color:#f88;font-size:110%;">'.substr($_datesArr[2],0,11).'</p>
                </div>
            </div>
            <div class="row d-flex icon-content" style="margin-left:4%;width:11%;float:left;border:0px solid #f00;" align="center">
                <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold" style="font-weight:bold;font-size:110%;">Item<br>Sold (SO)</p>
                    <p class="font-weight-bold" style="color:#f88;font-size:110%;">'.substr($_datesArr[3],0,11).'</p>
                </div>
            </div>
            <div class="row d-flex icon-content" style="margin-left:4%;width:11%;float:left;border:0px solid #f00;" align="center">
                <img class="icon" src="https://i.imgur.com/u1AzR7w.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold" style="font-weight:bold;font-size:110%;">Item<br>Dispatched</p>
                    <p class="font-weight-bold" style="color:#f88;font-size:110%;">'.substr($_datesArr[4],0,11).'</p>
                </div>
            </div>
            <div class="row d-flex icon-content" style="margin-left:4%;width:11%;float:left;border:0px solid #f00;" align="center">
                <img class="icon" src="https://i.imgur.com/HdsziHP.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold" style="font-weight:bold;font-size:110%;">Item<br>Delivered</p>
                    <p class="font-weight-bold" style="color:#f88;font-size:110%;">'.substr($_datesArr[5],0,11).'</p>
                </div>
            </div>
            <div class="row d-flex icon-content" style="margin-left:4%;width:11%;float:left;border:0px solid #f00;" align="center">
                <img class="icon" src="https://i.imgur.com/9nnc9Et.png">
                <div class="d-flex flex-column">
                    <p class="font-weight-bold" style="font-weight:bold;font-size:110%;">Item<br>Fulfilled</p>
                    <p class="font-weight-bold" style="color:#f88;font-size:110%;">'.substr($_datesArr[6],0,11).'</p>
                </div>
            </div>
        </div>
	
    </div>

	';
	
	$_body = "<table width='100%' class='main' border='0' valign='top' style='border:1px solid #e5e5e5;background:#fff;clear:both;margin-top:5px;font-family: Arial, Helvetica, Verdana, sans-serif;font-size:11px;'>".$_body."</table>";
	
	if(!(isset($_GET['_disp']) && ($_GET['_disp'] >= 1)))
	{
		if(isset($_GET['is_excel']) && ($_GET['is_excel'] >= 1))
		{
			$_body = "<center><a style='text-decoration:none;' href='../../docs/dataobjects/activities/tmp/".$fname.".xls'><span style='background-color:#ddd;border:2px solid #00A000;padding:3px;color:#008000;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;font-size:12px;font-weight:bold;'>Download Excel Report</span></a></center>".$_body;
		}
		
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
		
		require(ROOTPATH."inc/pdf/ellipse.php");
		
		header("Content-Disposition: attachment; filename=BUSINESS-PROCESS-WORKFLOW-.pdf");
		
		$pdf=new PDF_Ellipse('P','mm',array(210,297));
		$pdf->Open();
		
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
				$pdf->SetAlpha(0.25);$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h);$pdf->MultiCell($_P_WIDTH/2.5,24,"","","C",true);$pdf->SetAlpha(1);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+24);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5),$h+9.5);$pdf->Cell($_P_WIDTH/2.5,6,"CURRENT ASSET","",0,"C",false);
				$pdf->SetFont('arial','',11);$pdf->SetTextColor(135);$pdf->SetXY($w+3.5+($_P_WIDTH/2.5),$h+17.55);$pdf->Cell($_P_WIDTH/2.61,6,"( Total Stock Inventory Asset Value ) ",0,0,"C",false);
				
				$h = $_START_TOP;$w = $_START_LEFT;
				
				$pdf->SetTextColor(0);$pdf->SetFont('arial','',34);
				
				$pdf->SetXY(70-2,$h+1.85);$pdf->MultiCell($_HEIGHT,15,"Stock Inventory Overview",0,"L");
				$pdf->SetTextColor(155);
				$pdf->SetXY(70-2,$h+16.85);$pdf->MultiCell($_HEIGHT,15,"(Stock on hand)",0,"L");
				
				$pdf->SetTextColor(0);
				$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$rs_modules->fields['orglogo'],10,13.5,55,0,'','');
				
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
				$pdf->SetTextColor(120);$pdf->SetFont('arial','B',10);$pdf->Text($xPos,$yPos,"Module Parent Name:");$pdf->SetTextColor(75);$pdf->Text(48.5,$yPos,$_MODULE_NAME[3]." - ".$_MODULE_NAME[2]);$yPos+=6;
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
					$pdf->SetXY($w+50+40+15+25+20+30,$h);$pdf->MultiCell(25,6,"Virgin Price",1,'R',true);
					$pdf->SetXY($w+50+40+15+20+20+30+30,$h);$pdf->MultiCell(25,6,"Unit",1,'C',true);
					$pdf->SetXY($w+50+40+15+20+20+30+30+25,$h);$pdf->MultiCell(20,6,"Count",1,'C',true);
					$pdf->SetXY($w+50+40+15+20+20+30+30+30+15,$h);$pdf->MultiCell(30,6,"Cost",1,'R',true);
					
					$h+=6;
					$pdf->SetFont('arial','',10);$pdf->SetTextColor(39,44,49);
					
				$_INDEX = 0;$_CUT_OFF = 25;
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
					    $pdf->SetXY($w+50+40+15+20+20+30+30+25,$h);$pdf->MultiCell(20,5,"",1,'C');$pdf->SetXY($w+50+40+15+20+20+30+30+30+15,$h);$pdf->MultiCell(30,5,"R ".$v[8],1,'R',true);
					    
					    $h+=5;$_INDEX++;
					    
					    if($_INDEX == $_CUT_OFF)
					    {
						 $pdf->AddPage('L');$p++;$h = $_START_TOP;//$w = $_START_LEFT;
						 $pdf->SetAlpha(0.15);$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$rs_modules->fields['orglogo'],-1.5,163.5,65,0,'','');$pdf->SetAlpha(1);
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
				 
				
				$pdf->SetFont('arial','',10);$pdf->SetTextColor(0);
				$pdf->Text(147.0,203.5,$p);$pdf->SetFont('arial','',10);$pdf->Text(156,203.5,$rs_modules->fields['orgname']." (O-Framework V.2.10)");
				
				// --- Footer
				
				//$pdf->SetDrawColor(205);$pdf->SetLineWidth(0.1);$pdf->Line(0,277,210,277);$pdf->SetTextColor(175);$pdf->SetFont('arial','',9);
				//$pdf->Image(CONSUMER_ROOTPATH."images/organizations/aspen-main-logo.png",15,1,10,0,'','');$pdf->Text(85,285,"OVH Enterprise Framework v2.10");
				
						
				// _____________________________________________________ Page: SECTION 1: PROJECT DESCRIPTION AND PURPOSE __________________________________________________
				
				$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
				
				$h=0;$w = 73;
				$pdf->SetFillColor(75,75,75);$pdf->SetTextColor(0);$pdf->SetFont('arial','B',22);
				$pdf->SetAlpha(0.45);$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h);$pdf->MultiCell($_P_WIDTH/2.5,14,"","","C",true);$pdf->SetAlpha(1);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+5.0);$pdf->Cell($_P_WIDTH/2.5,6,date("F")." ".date("Y"),"",0,"C",false);
				//$pdf->SetFont('arial','',11.5);$pdf->SetTextColor(55);$pdf->SetXY($w+3+($_P_WIDTH/2.5) - 7.5,$h+17.75);$pdf->Cell($_P_WIDTH/2.61,6,"( Timesheet Book with 238 Weeks )","",0,"C",false);
				$pdf->SetXY($w+2+($_P_WIDTH/2.5) - 7.5,$h+14);$pdf->MultiCell($_P_WIDTH/2.5,2.5,"","","C",true);
				
				$h = $_START_TOP;$w = $_START_LEFT;
				
			       $pdf->SetTextColor(0);$pdf->SetFont('arial','',38); 
				
				$pdf->SetXY(10-2,$h-10.85);$pdf->MultiCell($_HEIGHT,15,"Balance Sheet ",0,"L");
				$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$rs_modules->fields['orglogo'],10-0,$h+3,46,0,'','');
				
				
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
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Investment Capital",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Retained Earnings",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Drawing",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Ordinary Share Capital",0,"L");$pdf->SetXY($_START_LEFT+105,$h);$pdf->Cell(30,5,"R0.00",0,"R","R");$pdf->SetXY($_START_LEFT+110+30,$h);$pdf->Cell(30,6,"R0.00",0,"R","R");$h+=6;
				$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);
				$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(100,6,"Total shareholder's equity","TB","L");$pdf->SetXY($_START_LEFT+101,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$pdf->SetXY($_START_LEFT+106+30,$h);$pdf->Cell(34,6,"R0.00","TB","R","R");$h+=6;
				
				// ---- End of Document
				
				// --- Document Author
				
				$pdf->SetAuthor(SYST_ABBR.' - OVH Enterprise Framework');   
				$pdf->SetTitle("Stock Inventory Overview - ".$_GET['_module']." - ".date("Y-F-d")."");
				
				@$pdf->Output(ROOTPATH."exports/pdf/campaigns/tmp/".$fname." - ".$_GET['_module']." - ".date("Y-F-d").".pdf","F");
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
else{echo "&nbsp;&nbsp;<strong style='font-size:14px;color:#ee6263;'>System error..</strong>";}
?>