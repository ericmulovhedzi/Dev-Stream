<?php

set_time_limit(0);
ini_set('memory_limit','-1');
error_reporting(E_ALL);
//ini_set('display_errors', 1);

function rgb_cmykx($r=0,$g=0,$b=0)
{
     $c = round((255 - $r)/255.0*100);
     $m = round((255 - $g)/255.0*100);
     $y = round((255 - $b)/255.0*100);
		   
     $b = min(array($c,$m,$y));
     $c = $c - $b; $m = $m - $b; $y = $y - $b;
		   
     $cmyk = array( 'c' => $c, 'm' => $m, 'y' => $y, 'k' => $b);
     return $cmyk;
}
require_once('../../../inc/connection.php');

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1))
{
	define('ROOTPATH_1',"/usr/www/users/hrdcsaqhwx/oefspiderws.hrdcsaqa.net/");
	//define('WWWROOT',"http://localhost/smpls/profiles/sp/");
	
        //echo ROOTPATH;
	header("Cache-Control: public, must-revalidate");
	header("Pragma: hack");
	header("Content-Type: text/pdf");
	
	//require(ROOTPATH_1."inc/pdf_1/ellipse.php");
	//require(ROOTPATH_1."inc/pdf/fpdi_1.5.4/fpdf.php");
	//require(ROOTPATH_1."inc/pdf/fpdf186/fpdf.php");
	
	//require(ROOTPATH_1."inc/pdf_1/fpdf.php");
	
	//require(ROOTPATH."inc/pdf_1/fpdf.php");
	//require(ROOTPATH_1."inc/pdf/fpdi_1.5.4/fpdi.php");
	
	//require("../../../inc/pdf/fpdf186/fpdf.php");
	require("../../../inc/pdf/fpdf186/fpdf.php");
	
	global $db;
	
	$v = array();$v_=array();$_id=1;$logged_by="";
	
	$_COLOR_SCHEME = array
			(
				0=>0,
				1=>75, /* - DOCUMENT TITLE TEXT COLOR */
				2=>0,
				3=>0,
				4=>0, /* -  */
				5=>100, /* - DASHED DRAW LINES */
				6=>0
			);
	
	$_ALL_STAGES = $_ALL_USERS = "";
	
	header("Content-Disposition: attachment; filename=BUSINESS-PROCESS-WORKFLOW-".$_id.".pdf");
	// _________________________________________________ PDF Header
	
	//$_WIDTH = 164; $_HEIGHT = 164; // --- Document Width & Height
	$_WIDTH = 216; $_HEIGHT = 297; // --- Document Width & Height
	
	//$pdf=new PDF_Ellipse('P','mm',array($_WIDTH,$_HEIGHT));
	$pdf = new FPDF('P','mm',array($_WIDTH,$_HEIGHT));
	//$pdf = new FPDF();
	//$pdf = new FPDF('P','mm','A4');
	//$pdf->Open();
	
	// _____________________________________________________ Page # 1 - Cover Page __________________________________________________
	
	//$pdf->AddPage();$p = 1;
	
	// --------------------- START OF THE DATA -------------------------
	
	$v = array('1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'','9'=>'','10'=>'',
		   '11'=>'','12'=>'','13'=>'','14'=>'','15'=>'','16'=>'','17'=>'','18'=>'','19'=>'','20'=>'',
		   '21'=>'','22'=>'','23'=>'','24'=>'','25'=>'','26'=>'','27'=>'','28'=>'','29'=>'',
		   '30'=>'','31'=>'','32'=>'','33'=>'','34'=>'','35'=>'','36'=>'','37'=>'','38'=>'','39'=>'',
		   '40'=>'','41'=>'','42'=>'','43'=>'','44'=>'');
	
	$_proposal_typeArr = array('1'=>'Web Hosting','2'=>'Web Design / Development','3'=>'','11'=>'Business Cards','20'=>'Web Hosting & Domain Registration');
	
	$_REFERENCE = "";
	//$pageCount = $pdf->setSourceFile("Trail Making Test 4th Edition_page-0002.pdf");
	  $db->SetFetchMode(ADODB_FETCH_ASSOC);
	 //echo "---";
	  $rs_ = $db->Execute("SELECT
			      DATE_FORMAT(`A`.date,'%d/%m/%Y') AS `date`,
			      `A`.`signed_in_time`,`A`.`signed_out_time`,`A`.clockin_status,
			      `A`.gps_lat,`A`.gps_lon,
			      CONCAT(`B`.name,' ',`B`.surname) AS `cname`,
			      `A`.`province`,`A`.`city_municipality`,`A`.`city`,`A`.`suburb`,`postal_code`,`A`.`street_address`,`A`.`featured_name`,
			      `A`.picture
			      FROM `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employee_clockin` AS `A`
			      LEFT JOIN `_hr_360_".$_SESSION['accesses']->_login['hr360'][0]."_employees` AS `B` ON `B`.id=`A`.employee
			      WHERE `A`.id=".$_GET['_id'].";");
	  if(($rs_) && ($rs_->numRows() >= 1))
	  {//	echo "---";
	       $_orgname = $_orglogo = $_orgcolor1 = $_orgcolor2 = "";
	       
		//$_REFERENCE = $rs_->fields['code'];
		$_MODULE_NAME = "";
		$p = 1;
		$_START_LEFT = 24;$_START_TOP = 15;
		$_P_WIDTH = $_WIDTH - ($_START_LEFT*2);// --- Page Content Width
		$_PC_WIDTH = $_P_WIDTH - 13; // --- Refined  content width
		
		$pdf->SetTextColor(0);
		
		list($RGB['r'][0],$RGB['g'][0],$RGB['b'][0]) = sscanf("#d7c834","#%02x%02x%02x");
		list($RGB['r'][1],$RGB['g'][1],$RGB['b'][1]) = sscanf("#000000","#%02x%02x%02x");
		list($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]) = sscanf("#d7c834","#%02x%02x%02x");
		
		
		$_CM_CUSTOMER = $_CM_SHIPMENTMETHOD = $_CM_DELIVERYDATE = $_CM_ADDRESS = "";
		
		$valuesArr = $valuesArr1 =$valuesArr2 = array();
		
		
	$_ORG_NAME = $_ORG_LOGO = "";
	if($_SESSION['accesses']->_login['hr360'][0] == 1){$_ORG_NAME = "House of Nnyane";$_ORG_LOGO = "hom-main-logo.jpg";}
	
		// ____________________________________ PAGE #4 __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
					
					//$pdf->Line($_WIDTH/2,0,$_WIDTH/2,297);
					
					$w = $_START_LEFT-11.5;
					$pdf->SetTextColor(25);$pdf->SetFont('arial','',15);
					
					$w = $_START_LEFT;
					
					$pdf->SetFont('arial','',14);$pdf->SetTextColor($RGB['r'][0],$RGB['g'][0],$RGB['b'][0]);
					
					$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(155);$pdf->Line($w-6.5,$h+40+10.5,$_WIDTH-4.5*4,$h+40+10.5);
					
					if(file_exists(CONSUMER_ROOTPATH."docs/hr_360/".$_SESSION['accesses']->_login['hr360'][0]."/employee/clocking/".$_GET['_id']."/proof_of_clockin.jpg"))
					{
						//$pdf->Image(ROOTPATH."images/documents/ovhproperty/ovh-property-logo.png",178,(4.5*1.7),25,0,'','');
						$pdf->Image(CONSUMER_ROOTPATH."docs/hr_360/".$_SESSION['accesses']->_login['hr360'][0]."/employee/clocking/".$_GET['_id']."/proof_of_clockin.jpg",4.5,30+4.5,$_WIDTH-(4.5*2),0,'','');
					}
					
					// --- Structural Design
					
					
					$pdf->SetFillColor(0);
					
					    $pdf->SetLineWidth(0.5);$pdf->SetDrawColor(55);
					$pdf->Line(4.5,4.5,$_WIDTH,4.5);// Top
					
					$pdf->Line(0,4.5+30,$_WIDTH-4.5,4.5+30); 
					
					$pdf->Line(4.5,0,4.5,$_HEIGHT);// Left
					//`,`A`.`city_municipality`,`A`.`city`,`A`.`suburb`,`postal_code`,`A`.`street_address`,`A`.`featured_name`A`.`signed_in_time`,`A`.`signed_out_time`,
					$pdf->SetTextColor(0);$pdf->SetFont('arial','B',18);
					$pdf->SetXY($_WIDTH/2-39-50,4.5*1.7);$pdf->MultiCell($_WIDTH/2+50,12,"Exhibit A: SITE CLOCK-IN EVIDENCE",0,"R");
					$pdf->SetTextColor(95);$pdf->SetFont('arial','',11.0);
					$pdf->SetXY($_WIDTH/2-39,4.5*3.5);$pdf->MultiCell($_WIDTH/2,8,"SUPERVISOR: ".$rs_->fields['cname'],0,"R");
					$pdf->SetXY($_WIDTH/2-39,4.5*4.75);$pdf->MultiCell($_WIDTH/2,8,date('h:i A', strtotime($rs_->fields['signed_in_time']))." | ".$rs_->fields['date'],0,"R");
					$pdf->SetXY($_WIDTH/2-39-60,4.5*6.0);$pdf->MultiCell($_WIDTH/2+60,8,"Location: ".$rs_->fields['province'].", ".$rs_->fields['city'].", ".$rs_->fields['suburb'].", ".$rs_->fields['street_address']." (".substr($rs_->fields['gps_lat'],0,6).", ".substr($rs_->fields['gps_lon'],0,5).")",0,"R");
					
					if(file_exists(CONSUMER_ROOTPATH."docs/hr_360/".$_SESSION['accesses']->_login['hr360'][0]."/employee/clocking/".$_GET['_id']."/proof_of_clockin.jpg"))
					{
						//$pdf->Image(ROOTPATH."images/documents/ovhproperty/ovh-property-logo.png",178,(4.5*1.7),25,0,'','');
						$pdf->Image(CONSUMER_ROOTPATH."docs/hr_360/".$_SESSION['accesses']->_login['hr360'][0]."/employee/clocking/".$_GET['_id']."/proof_of_clockin.jpg",178,(4.5*2.3),30,40,'','');
					}
					
					$pdf->SetLineWidth(0.5);$pdf->SetDrawColor(55);
					$pdf->Line(0,$_HEIGHT-4.5,$_WIDTH,$_HEIGHT-4.5);// Bottom
					$pdf->Line($_WIDTH-4.5,0,$_WIDTH-4.5,$_HEIGHT);// Right
					
					// --- --- --- HEADER
					
					$h+=(6.2*16.3);
					
					$pdf->SetFont('arial','B',128);$pdf->SetTextColor(225);
					$pdf->Text($_WIDTH/2.0-13*6.77+0.25-3,$h+131,"01");
					
					$pdf->SetTextColor(25);$pdf->SetFont('arial','B',18);
					$pdf->Text($_WIDTH/2-13*3.75+5,$h+104,"Our Vision");
					$pdf->SetTextColor(105);$pdf->SetFont('arial','',11.5);
					$pdf->SetXY($_WIDTH/2-13*3.8+5,$h+109);$pdf->MultiCell($_WIDTH/1.75,8,"To become South Africa's property and asset maintenance business of choice that significantly contributes positively to the lives of all its stakeholders..",0,"L");
					
					$pdf->Image(CONSUMER_ROOTPATH."images/organizations/".$_ORG_LOGO,140.0,270+4.5,65,0,'','');
					
					$pdf->SetFont('arial','',11);$pdf->SetTextColor(75);$pdf->Text($w+$_PC_WIDTH-91.5,286,"www.houseofnnyane.co.za");
		    
	  }
	
	$pdf->SetAuthor('Eric M. Mulovhedzi - Spider Black');   
	$pdf->SetTitle("Proof of Clocking - ".$_GET['_id']." - ".$rs_->fields['cname']);
	
	//if(isset($_GET['_file']) && ($_GET['_file']==1))
	if(false)
	{
		//$pdf->Output(ROOTPATH."docs/dataobjects/BUSINESS-PROCESS-WORKFLOW-".$_id.".pdf","F");
		/*
		$userInfo = array($_SESSION['accesses']->_login['id'],$_SESSION['accesses']->_login['cname'],$_SESSION['accesses']->_login['email'],$_SESSION['accesses']->_login['cell'],$_SESSION['accesses']->_login['image']);
						
		if(is_array($userInfo))
		{
			include(ROOTPATH."inc/notifications_processworkflows.php");
			$itemInfo = array($_id,$page,NOW());
			
			loggerConfirmationNotificationUMLProcessWorkflow($userInfo,$itemInfo);
		}*/
	}
	else{$pdf->Output("Proof of Clocking - ".$_GET['_id']." - ".$rs_->fields['cname'].".pdf","I");}
}
?>
		