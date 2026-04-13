<?php
ini_set("display_errors",1);
ignore_user_abort(true);
set_time_limit(0);
ini_set('memory_limit','-1');

function rgb_cmyk($r=0,$g=0,$b=0)
{
     $c = round((255 - $r)/255.0*100);
     $m = round((255 - $g)/255.0*100);
     $y = round((255 - $b)/255.0*100);
		   
     $b = min(array($c,$m,$y));
     $c = $c - $b; $m = $m - $b; $y = $y - $b;
		   
     $cmyk = array( 'c' => $c, 'm' => $m, 'y' => $y, 'k' => $b);
     return $cmyk;
}

if(true)
{
	//require_once('../../../inc/connection.php');
	//define('ROOTPATH',"/Applications/MAMP/htdocs/smpls/profiles/web/orank.co.za/");
	define('ROOTPATH',"/var/www/oranky.com/public_html/");
	//define('WWWROOT',"http://localhost/smpls/profiles/web/orank.co.za/");
	define('WWWROOT',"http://oranky.com/");
        
	$_REQUEST_URL = (isset($_GET['_page']) && (!empty($_GET['_page']))) ? $_GET['_page'] : "";
	$_REQUEST_TYPE = 1; 
	//require(ROOTPATH.'engine/searchEngine.php');
	require_once(__DIR__.'/../../../engine/searchEngine.php');
	
	global $array, $_MATRIX_RESULTS;
	
	header("Cache-Control: public, must-revalidate");
	header("Pragma: hack");
	header("Content-Type: text/pdf");
	
	require(ROOTPATH."inc/pdf/ellipse.php");
	
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
	
	header("Content-Disposition: attachment; filename=SEO Review Report - ".date('d M Y')." - ".$_REQUEST_URL.".pdf");
	// _________________________________________________ PDF Header
	
	$_WIDTH = 216; $_HEIGHT = 297; // --- Document Width & Height
	
	$pdf=new PDF_Ellipse('P','mm',array($_WIDTH,$_HEIGHT));
	$pdf->Open();
	
	// _____________________________________________________ Page # 1 - Cover Page __________________________________________________
	
	//$pdf->AddPage();$p = 1;
	
	// --------------------- START OF THE DATA -------------------------
	
	$v = array('1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'','9'=>'','10'=>'',
		   '11'=>'','12'=>'','13'=>'','14'=>'','15'=>'','16'=>'','17'=>'','18'=>'','19'=>'','20'=>'',
		   '21'=>'','22'=>'','23'=>'','24'=>'','25'=>'','26'=>'','27'=>'','28'=>'','29'=>'',
		   '30'=>'','31'=>'','32'=>'','33'=>'','34'=>'','35'=>'','36'=>'','37'=>'','38'=>'','39'=>'',
		   '40'=>'','41'=>'','42'=>'','43'=>'','44'=>'');
	
	$page="";
	//if(($rs) && ($rs->numRows() >= 1))
	if(true)
	{
		$RGB['r'] = array(226,250,100);$RGB['g'] = array(8,100,100);$RGB['b'] = array(30,100,100);
		
		if(isset($rs->fields['branding_color_main']) && (!empty($rs->fields['branding_color_main'])))list($RGB['r'][0],$RGB['g'][0],$RGB['b'][0]) = sscanf("#".$rs->fields['branding_color_main'],"#%02x%02x%02x");
		if(isset($rs->fields['branding_color_sub']) && (!empty($rs->fields['branding_color_sub'])))list($RGB['r'][1],$RGB['g'][1],$RGB['b'][1]) = sscanf("#".$rs->fields['branding_color_sub'],"#%02x%02x%02x");
		if(isset($rs->fields['branding_color_extra']) && (!empty($rs->fields['branding_color_extra'])))list($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]) = sscanf("#".$rs->fields['branding_color_extra'],"#%02x%02x%02x");
		
		$branding_color_main = "c24335";$branding_color_sub = "000000";$branding_color_extra = "c24335";
		
		list($RGB['r'][0],$RGB['g'][0],$RGB['b'][0]) = sscanf("#d7c834","#%02x%02x%02x");
		list($RGB['r'][1],$RGB['g'][1],$RGB['b'][1]) = sscanf("#000000","#%02x%02x%02x");
		list($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]) = sscanf("#d7c834","#%02x%02x%02x");
		
		list($C_A['r'][0],$C_A['g'][0],$C_A['b'][0]) = sscanf("#dc4245","#%02x%02x%02x");$S_DESC[0] = "danger";$_S_DESC["danger"] = 0; // -- DANGER
		list($C_A['r'][1],$C_A['g'][1],$C_A['b'][1]) = sscanf("#f9c132","#%02x%02x%02x");$S_DESC[1] = "warning";$_S_DESC["warning"] = 1; // -- WARNING
		list($C_A['r'][2],$C_A['g'][2],$C_A['b'][2]) = sscanf("#4fa747","#%02x%02x%02x");$S_DESC[2] = "success";$_S_DESC["success"] = 2; // -- SUCCESS
		list($C_A['r'][3],$C_A['g'][3],$C_A['b'][3]) = sscanf("#5f5f5f","#%02x%02x%02x");$S_DESC[3] = "informational";$_S_DESC["informational"] = 3; // -- INFORMATIONAL
		list($C_A['r'][4],$C_A['g'][4],$C_A['b'][4]) = sscanf("#2186e6","#%02x%02x%02x");$S_DESC['b'] = "none"; // -- BLUE
		
		$_STATUS = 0;
		
		$logo_main = "spiderblack-main-logo.png";
		$logo_bg = "background-profile.jpg";
		$logo_thumb = "spiderblack-thumnail-logo.png";
		
		$p = 1;
		$_START_LEFT = 24;$_START_TOP = 15;
		$_P_WIDTH = $_WIDTH - ($_START_LEFT*2);// --- Page Content Width
		$_PC_WIDTH = $_P_WIDTH - 13; // --- Refined  content width
		
		// _____________________________________________________ Cover Page __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		//$pdf->SetFillColor(81,86,89);
		$pdf->SetFillColor(248,249,251);$pdf->SetLineWidth(0.1);
		$pdf->RoundedRect(0,0,$_WIDTH,$_HEIGHT/2,0,'F');
		
	        
	       // --- --- ---
	        
	       $_TOTAL_AGGREGATE = $_TOTAL_SUCCESS = $_TOTAL_WARNING = $_TOTAL_DANGER = $_AGGREGATE_RATE = 0;
	       
	       if(isset($array) && is_array($array) && (sizeof($array)>=1))
	       {
		    $_TOTAL_SUCCESS = $array["progress"]["success"];
		    $_TOTAL_WARNING = $array["progress"]["warning"];
		    $_TOTAL_DANGER = $array["progress"]["danger"];
		    $_TOTAL_INFORMATIONAL = $array["progress"]["informational"];
	       }
	       
	       $_TOTAL_AGGREGATE = $_TOTAL_SUCCESS + $_TOTAL_WARNING + $_TOTAL_DANGER;
	       
	       $_AGGREGATE_RATE = round(((100*$_TOTAL_SUCCESS)/$_TOTAL_AGGREGATE),2);
	       
	       // --- --- ---
	       
		// IF STATUS CONDITIONS, THEN change the color
		
		$_STATUS = 0;
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS],$C_A['g'][$_STATUS],$C_A['b'][$_STATUS]);
		
		// --- Color coding
		
	       if($_AGGREGATE_RATE >= 65)  // -- SUCCESS = 2
	       {
		    $pdf->SetDrawColor($C_A['r'][2],$C_A['g'][2],$C_A['b'][2]);
		    $pdf->SetTextColor($C_A['r'][2],$C_A['g'][2],$C_A['b'][2]);
	       }
	       else if($_AGGREGATE_RATE >= 47.50)  // -- WARNING = 1
	       {
		    $pdf->SetDrawColor($C_A['r'][1],$C_A['g'][1],$C_A['b'][1]);
		    $pdf->SetTextColor($C_A['r'][1],$C_A['g'][1],$C_A['b'][1]);
	       }
	       else // -- DANGER = 0
	       {
		    $pdf->SetDrawColor($C_A['r'][0],$C_A['g'][0],$C_A['b'][0]);
		    $pdf->SetTextColor($C_A['r'][0],$C_A['g'][0],$C_A['b'][0]);
	       }
	       
		//$pdf->SetTextColor($C_A['r'][$_STATUS],$C_A['g'][$_STATUS],$C_A['b'][$_STATUS]);
		
		$pdf->SetFont('arial','B',52);$pdf->SetLineWidth(4.0);
		
		$pdf->Circle($_WIDTH/2,$h+50,27,'');$pdf->Text(($_WIDTH/2)-23.0,$h+56.75,number_format($_AGGREGATE_RATE,2));
		
		$pdf->SetTextColor(55);$pdf->SetFont('arial','B',18);$pdf->SetXY($w,103-2);$pdf->MultiCell($_P_WIDTH,5,"( ".$_REQUEST_URL." )",0,"C");
		$pdf->SetTextColor(55);$pdf->SetFont('arial','',11);$pdf->SetXY($w,103+9);$pdf->MultiCell($_P_WIDTH,5,"This is a SEO review report of ",0,"L");$pdf->SetTextColor(220,41,30);$pdf->Text($w+54.0,103+9+3.6,$_REQUEST_URL);
		$pdf->SetTextColor(220,41,30);$pdf->Text($w+16.5,103+9+3.6,"S");
		$pdf->SetTextColor($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]);$pdf->Text($w+19.0,103+9+3.6,"E");
		$pdf->SetTextColor(0,174,216);$pdf->Text($w+21.7,103+9+3.6,"O");
		$pdf->SetTextColor(55);$pdf->SetFont('arial','',11);$pdf->SetXY($w,103+1+9+7);$pdf->MultiCell($_P_WIDTH,5,"Date generated: ".date('d M Y'),0,"L");
		
		
		// --- Security Domains
		
		//$h = 63.0;
	       
		//$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.1);
		//$pdf->SetFont('arial','B',10);$pdf->SetXY(0,$h);$pdf->MultiCell(67.5,6,"Security Domains","B","R",true);
		
		//$h = $pdf->GetY()+0.0;
		
		//$pdf->Image(ROOTPATH."images/iconography/security-danger.png",$w-15,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w-8,$h);$pdf->Cell(130,10,"Governance",0,"L");$h+=6.5;
		//$pdf->Image(ROOTPATH."images/iconography/security-danger.png",$w-15,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w-8,$h);$pdf->Cell(130,10,"Protection",0,"L");$h+=6.5;
		//$pdf->Image(ROOTPATH."images/iconography/security-danger.png",$w-15,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w-8,$h);$pdf->Cell(130,10,"Defence",0,"L");$h+=6.5;
		//$pdf->Image(ROOTPATH."images/iconography/security-danger.png",$w-15,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w-8,$h);$pdf->Cell(130,10,"Resilience",0,"L");$h+=7;$h+=6*1.5;
		
		// --- SEO & Digital Marketing Tool
		
		$h = 63.0;
	       
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.1);
		$pdf->SetFont('arial','B',10);$pdf->SetXY(150,$h);$pdf->MultiCell(75,6,"SEO & Digital Marketing Tool","B","L",true);
		
		$h = $pdf->GetY()+0.0;
		
		$pdf->SetFillColor($C_A['r'][0],$C_A['g'][0],$C_A['b'][0]);$pdf->SetTextColor(255);$pdf->SetFont('arial','B',11);$pdf->RoundedRect($w+126,$h+2.5,8,5,1,'F');$pdf->Text($w+126+1.75,$h+2.5+3.75,$_TOTAL_DANGER);
		$pdf->Image(ROOTPATH."images/iconography/danger.png",$w+136,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w+13+130,$h);$pdf->Cell(130,10,"Errors",0,"L");$h+=6.5;
		$pdf->SetFillColor($C_A['r'][1],$C_A['g'][1],$C_A['b'][1]);$pdf->SetTextColor(255);$pdf->SetFont('arial','B',11);$pdf->RoundedRect($w+126,$h+2.5,8,5,1,'F');$pdf->Text($w+126+1.75,$h+2.5+3.75,$_TOTAL_WARNING);
		$pdf->Image(ROOTPATH."images/iconography/warning.png",$w+136,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w+13+130,$h);$pdf->Cell(130,10,"Require Improvement",0,"L");$h+=6.5;
		$pdf->SetFillColor($C_A['r'][2],$C_A['g'][2],$C_A['b'][2]);$pdf->SetTextColor(255);$pdf->SetFont('arial','B',11);$pdf->RoundedRect($w+126,$h+2.5,8,5,1,'F');$pdf->Text($w+126+1.75,$h+2.5+3.75,$_TOTAL_SUCCESS);
		$pdf->Image(ROOTPATH."images/iconography/success.png",$w+136,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w+13+130,$h);$pdf->Cell(130,10,"Passed",0,"L");$h+=6.5;
		$pdf->SetFillColor($C_A['r'][3],$C_A['g'][3],$C_A['b'][3]);$pdf->SetTextColor(255);$pdf->SetFont('arial','B',11);$pdf->RoundedRect($w+126,$h+2.5,8,5,1,'F');$pdf->Text($w+126+1.75,$h+2.5+3.75,$_TOTAL_INFORMATIONAL);
		$pdf->Image(ROOTPATH."images/iconography/informational.png",$w+136,$h+2.5,6.0,0,'','');$pdf->SetFont('arial','',11);$pdf->SetTextColor(55);$pdf->SetXY($w+13+130,$h);$pdf->Cell(130,10,"Informational",0,"L");$h+=7;$h+=6*1.5;
		
		
		$pdf->SetTextColor(0);$pdf->SetFont('arial','I',12);
		
		$h = 253;$h = 133;$center = $_WIDTH/2;
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->Line(65,$h,$_WIDTH-65,$h);
		
		$pdf->SetFillColor($_COLOR_SCHEME[0]);$pdf->SetLineWidth(0.4);$pdf->SetDash();
		$pdf->SetFillColor(255);$pdf->Circle($center-12.5,$h,3.7,'DF');$pdf->SetFillColor(220,41,30);$pdf->Circle($center-12.5,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center,$h,3.7,'DF');$pdf->SetFillColor($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]);$pdf->Circle($center,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center+12.5,$h,3.7,'DF');$pdf->SetFillColor(0,174,216);$pdf->Circle($center+12.5,$h,2,'F');$pdf->SetDash();
		
		
		$pdf->SetTextColor(65);$pdf->SetFont('arial','I',11);$pdf->SetXY($w,$h+9);$pdf->MultiCell($_P_WIDTH,5,"SEO: Search Engine Optimization",0,"R");
		
		$h = 269+13;
		
		$pdf->SetFillColor(81,86,89);$pdf->SetDrawColor(81,86,89);$pdf->SetLineWidth(1.5);
		
		$pdf->Line(0,$_HEIGHT/2,$_WIDTH,$_HEIGHT/2);
		
		
		// --- --- --- NEW SECTION
		
		$h = $pdf->GetY()+23.5;
		
		$pdf->SetFont('arial','',10);
		$_TXT = "A website is a vital tool responsible for enhancement of an organization's corporate image while increasing its name, brand, and identity's public awareness. It boosts the advocacy and marketing of the organization's programs of action as well as its product and services.";
		$_TXT .= "\n\n";
		$_TXT .= "In addition, a website makes relevant documentation and paper based records accessible and provides opportunities to stakeholders to participate by providing feedback. ";
		$_TXT .= "Cloud computing on the other hand provides for a secure, safe, and sound electronic records management ensuring the delivery of computing services over the internet, enabling faster innovation, flexible resources, and economies of scale.";
		$_TXT .= "\n\n";
		$_TXT .= "Oranky's SEO, Search Engine Optimization, reports provide meaningful and actionable reviews to improve a site or cloud environment's organizational objectives to accomplish strategic goals.";
		$pdf->SetXY($w,$h);$pdf->MultiCell($_P_WIDTH,6.2,$_TXT,0,"J");
		
		
		$pdf->SetLineWidth(0.1);
		
		$h +=7;$w =  $w + 60.5;$pdf->SetTextColor(55);
		
		$h = $_HEIGHT/2;$w =  $w + 2.5;
		$pdf->Polygon(array($w+6+15,$h+3+10,$w+6+15-2.5-10,$h+3-2.5,$w+6+15+2.5+10,$h+3-2.5),'F');
		
		$w = $w-27.0;$h = 232.5;
		
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",98,$h+20,20,0,'','');
		
		$pdf->SetFont('arial','',10);
		
		$pdf->Image(ROOTPATH."images/organizations/tel-64.png",$w+2.0+15,$h+13.3+29+1,6,0,'','');$pdf->Text($w+2.0+20+3,$h+13.3+29+5,"+27.87 791 5984");$w += 35.5;//$h +=8*1.0;
		$pdf->Image(ROOTPATH."images/icons/twitter-64.png",$w+3.0+15,$h+13.9+29+1,6,0,'','');$pdf->Text($w+2.0+20+3,$h+13.3+29+5,"@orankyseo");$w -= 35.5-9;$h +=8*1.0;
		$pdf->SetFont('arial','B',10);
		$pdf->Text($w+2.0+20+3,$h+13.3+29+5,"www.oranky.com");$h +=8*1.0;
		$pdf->SetFont('arial','',10);
		
		/*
		// ____________________________________  Page #2: QMS __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$pdf->SetFillColor(81,86,89);$pdf->RoundedRect(20-5,$h-25,31.5,30,3,'F');
		$pdf->SetTextColor(255);$pdf->SetFont('arial','B',18);$pdf->Text(25.5-3.5,$h-3.5,"Trust");
		$pdf->SetTextColor(145);$pdf->SetFont('arial','',10);$pdf->Text(25.5-6.50,$h-3.5+5.5,"ISO 9001:2015");
		
		$h += 10;
		$pdf->Image(ROOTPATH."images/generic/cellphone-iso.png",60.5,$h-5,100,0,'','');
		$pdf->SetTextColor(65);$pdf->SetFont('arial','',48);$pdf->Text($w+4+96.5,$h+89.5-20,"Annex SL");
		$pdf->SetFont('arial','',15.5);
		$pdf->Text($w+4+96.5,$h+95.5-20,"Common High Level Structure");
		
		$h = 102.5;
		
		$w -=7.5;
		
		$pdf->SetDrawColor(25);$pdf->SetFillColor(25);$pdf->SetLineWidth(0.1);
		$pdf->Circle($w+25+5.25,$h+35.95,2.5,'D');$pdf->Circle($w+46+2.65,$h+43.85,2.5,'D');$pdf->Circle($w+50+2.65,$h+51.75,2.5,'D');
		$pdf->SetDash(1,1);
		$pdf->Line($w-3,$h-11.6,$w+26,$h-11.6);
		$pdf->Line($w-3,$h-11.6,$w-3,$h+40.5);
		$pdf->Line($w-3,$h+40.5,$w+10,$h+40.5);
		
		$pdf->Line($w+10,$h+36,$w+10,$h+51.8);
		
		$pdf->Line($w+10,$h+36,$w+21+5.25,$h+36);
		$pdf->Line($w+10,$h+44.4,$w+40+5.05,$h+44.4);
		$pdf->Line($w+10,$h+51.8,$w+45+5.05,$h+51.8);
		
		$pdf->SetDash();
		
		$pdf->Polygon(array($w+14,$h-9.5-5+0.35,$w+14,$h-9.5+0.35,$w+14+2.5,$h-9.5-2.5+0.35),'F');
		
		$pdf->SetFont('arial','',11);$pdf->SetFillColor(25);
		$pdf->SetTextColor(255);$pdf->RoundedRect($w+1+15,$h-16,75,8,1,'F');$pdf->Text($w+4+15,$h+9.5-20,$_REQUEST_URL);
		
		//$pdf->SetFont('arial','',11);$pdf->SetTextColor(0);
		//$pdf->Text($w+4+96.5,$h-11,"Security Management & Records Management System");
		
		// --- Table
		$pdf->SetFont('arial','B',9);$pdf->SetFillColor(245);$pdf->SetDrawColor(95);$pdf->SetTextColor(65);$pdf->SetLineWidth(0.4);
		$pdf->SetFont('arial','IB',10);
		$pdf->SetXY($w,$h);$pdf->MultiCell($_P_WIDTH/3+15,8," Clause:","B","L",true);
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15,8,"QMS","B","C",true);
		$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15,8,"ISMS","B","C",true);
		$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15,8,"RMS","B","C",true);
		
		$pdf->SetFont('arial','',10);$pdf->SetDrawColor(155);$pdf->SetLineWidth(0.1);
		
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"1. Scope ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$w+71+0.5+15.5,$h+2.75,4,0,'','');$pdf->Image(ROOTPATH."images/icons/tick-black.png",$w+71+0.5+36.5+15.5,$h+2.75,4,0,'','');$pdf->Image(ROOTPATH."images/icons/tick-black.png",$w+71+0.5+73+15.5,$h+2.75,4,0,'','');
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"2. Normative References ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"3. Terms & Definations ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"4. Organizational Context ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$w+71+0.5+15.5,$h+2.75,4,0,'','');$pdf->Image(ROOTPATH."images/icons/tick-black.png",$w+71+0.5+73+15.5,$h+2.75,4,0,'','');
		$pdf->SetAlpha(0.15);$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+36.5+15.5,$h+1.75,6,0,'','');$pdf->SetAlpha(1);
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"5. Leadership ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$w+71+0.5+15.5,$h+2.75,4,0,'','');
		$pdf->SetAlpha(0.15);$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+36.5+15.5,$h+1.75,6,0,'','');$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+73+15.5,$h+1.75,6,0,'','');$pdf->SetAlpha(1);
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"6. Planning ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$w+71+0.5+15.5,$h+2.75,4,0,'','');
		$pdf->SetAlpha(0.15);$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+36.5+15.5,$h+1.75,6,0,'','');$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+73+15.5,$h+1.75,6,0,'','');$pdf->SetAlpha(1);
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"7. Support ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$pdf->SetAlpha(0.15);$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+15.5,$h+1.75,6,0,'','');$pdf->SetAlpha(1);
		$pdf->SetAlpha(0.15);$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+36.5+15.5,$h+1.75,6,0,'','');$pdf->Image(ROOTPATH."images/icons/question-mark-black.png",$w+71+73+15.5,$h+1.75,6,0,'','');$pdf->SetAlpha(1);
		$h+=8;
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"8. Operation ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$h+=8;
	        $pdf->SetFillColor(235,245,255);
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"9. Performance Evaluation ",0,'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L',true);$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L',true);
		$pdf->SetXY($w+71+0.5*3+(($_P_WIDTH/8)+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L',true);
		$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		
		$pdf->SetDrawColor(18,86,135);$pdf->SetFillColor(72,145,220);$pdf->SetLineWidth(0.5);
		    $pdf->Circle($w+71+0.5+18,$h+4.0,2,'FD');$pdf->Circle($w+71+0.5*2+($_P_WIDTH/8+15)+18,$h+4.0,2,'FD');$pdf->Circle($w+71+0.5*2+($_P_WIDTH/8+15)*2+18,$h+4.0,2,'FD');
	       $pdf->SetDrawColor(155);$pdf->SetFillColor(225,238,252);$pdf->SetLineWidth(0.1);
		$h+=8;
		$pdf->SetFillColor(235,245,255);
		//$pdf->SetDrawColor(95);
		$pdf->SetXY($w,$h);$pdf->MultiCell(71,8,"10. Improvement ","",'R');
		$pdf->SetXY($w+71+0.5,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*2+($_P_WIDTH/8+15),$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');$pdf->SetXY($w+71+0.5*3+($_P_WIDTH/8+15)*2,$h);$pdf->MultiCell($_P_WIDTH/8+15-0.15,8,"","B",'L');
		$h+=8;
		
		$pdf->Image(ROOTPATH."images/generic/security-cyber.png",$w+114+4.8,$h-1.0,15.0,0,'','');
		$pdf->Image(ROOTPATH."images/generic/pcv-files.png",$w+114+2.5+30.5,$h-3.0,32.5,0,'','');
		
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w-5+0.5,$h-17,10.0,0,'','');
		$pdf->SetDrawColor(25);$pdf->SetFillColor(25);$pdf->SetLineWidth(0.1);
		$pdf->Circle($w+25+3.65,$h-12.1,2.5,'D');
		$pdf->SetDash();
		$pdf->Line($w+11,$h-11.6,$w+26,$h-11.6);
		$pdf->SetDash();
		
		$pdf->Polygon(array($w+11,$h-9.5-5+0.35,$w+11,$h-9.5+0.35,$w+11-2.5,$h-9.5-2.5+0.35),'F');
		
		$h+=8*2.3;
		$pdf->SetFont('arial','',10);
		$pdf->SetXY(13,$h+4);$pdf->MultiCell($_P_WIDTH+13,8,"Analysing, monitoring, and reviewing how well your site or cloud environment is operating against the requirements of your Management System as well as your ISMS, Information Security Management System, and RMS, Records Management System.",0,"C");
		
		
		//$h = $_HEIGHT-$_START_TOP-15;
		$h+=8*5.0;$center = $_WIDTH/2;
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->Line(65,$h,$_WIDTH-65,$h);
		
		$pdf->SetFillColor($_COLOR_SCHEME[0]);$pdf->SetLineWidth(0.4);$pdf->SetDash();
		$pdf->SetFillColor(255);$pdf->Circle($center-12.5,$h,3.7,'DF');$pdf->SetFillColor(220,41,30);$pdf->Circle($center-12.5,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center,$h,3.7,'DF');$pdf->SetFillColor($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]);$pdf->Circle($center,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center+12.5,$h,3.7,'DF');$pdf->SetFillColor(0,174,216);$pdf->Circle($center+12.5,$h,2,'F');$pdf->SetDash();
		
		// --- Footer ---
		
		$w =  $_START_LEFT + 63;
		
		$h +=2.5+180;$pdf->Polygon(array($w+6+15,$h+3+10,$w+6+15-12.5-10,$h+3-12.5,$w+6+15+12.5+10,$h+3-12.5),'F');
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",98,$h+2,20,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->Text($w+6.5,$h+13*2.0,"www.oranky.com");
		*/
		
		// _____________________________________________________ Page #3. PREAMBLE AND INTRODUCTION __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(65);$pdf->SetXY($w,$h);$pdf->MultiCell($_P_WIDTH,10,"Preamble",0,"L");
		
		$h = 27;
		$pdf->SetFont('arial','B',10);
		
		 
		// --- --- --- NEW SECTION
		
		$h = $pdf->GetY()+4.2;
		
		$pdf->SetFont('arial','',10);
		$_TXT = "We, the team of \"Oranky\" recognise the rapid increase of online optimisation strategies driving website traffic today due to digital technology changes and innovations. As a result, these pose multiple challanges to online or digital marketing campaigns.";
		$_TXT .= "\n\n";
		$_TXT .= "We strongly believe that in order to remain competitive in today's saturated digital environment, a viable and well-defined SEO strategy is imperative in making your business thrive in the current digital landscape.";
		$_TXT .= "\n\n";
		$_TXT .= "We therefore, through our SEO algorithms, aim to improve your website traffic and organic ranking while ensuring you remain competitive in today's ever-changing digital environment.";
		$pdf->SetXY($w,$h);$pdf->MultiCell($_P_WIDTH,6.2,$_TXT,0,"J");$h+=8.2;$h+=(6.2*9.5);
		
	        
		$pdf->SetFont('arial','I',10); // -- -- Def: Format B..
		$_TXT = "How?";
		$pdf->SetXY($w,$h);$pdf->MultiCell($_PC_WIDTH,6.2,$_TXT,0,"J");$pdf->SetFont('arial','',10);//$h+=6.2;
		$_TXT = "by providing meaningful and actionable Search Engine Optimization reviews.";
		$pdf->SetXY($w+10,$h);$pdf->MultiCell($_PC_WIDTH-2,6.2,$_TXT,0,"J");$h+=8.2;$h+=(6.2*1);
		
		$h+=8*1.0;$center = $_WIDTH/2;
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->Line(65,$h,$_WIDTH-65,$h);
		
		$pdf->SetFillColor($_COLOR_SCHEME[0]);$pdf->SetLineWidth(0.4);$pdf->SetDash();
		$pdf->SetFillColor(255);$pdf->Circle($center-12.5,$h,3.7,'DF');$pdf->SetFillColor(81,86,89);$pdf->Circle($center-12.5,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center,$h,3.7,'DF');$pdf->SetFillColor(81,86,89);$pdf->Circle($center,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center+12.5,$h,3.7,'DF');$pdf->SetFillColor(81,86,89);$pdf->Circle($center+12.5,$h,2,'F');$pdf->SetDash();
		
		
		// --- --- --- NEW SECTION 
		
		$h = $pdf->GetY()+8.2+22.5;
		
		$pdf->SetXY($w,$h);$pdf->SetFont('arial','B',10);$pdf->MultiCell($_PC_WIDTH,10,"Introduction",0,"L");
		
		$h +=14;$pdf->SetFont('arial','',10);
		
		$_TXT = "Oranky's SEO score has a grading of a 100-point scale. The scale depicts the your site or cloud environment's Internet Marketing visibility i.e. your online marketing effectiveness. Its mechanism is predicated upon a bunch of factors such as the site meta data, site structure, site content, site security and many more.";
		$_TXT .= "\n\n";
		$_TXT .= "A SEO score of less than 35 means that a site or cloud environmentis less optimized and requires lots of improvements, whereas a SEO score above 75 means that your site or cloud environment is well optimized. At this stage, it means it's now ready for a strong digital marketing campaign.";
		$_TXT .= "\n\n";
		$_TXT .= "In case of state-owned enterprises and municipalities, a score below 35 symbolizes poor service delivery, high dependency on grants, need for government guarantees or bailouts and the particular govermental body might soon be placed under administration, whereas a SEO score above 75 means possible compliance with the Auditor-General's audit reports.";
		$pdf->SetXY($w,$h);$pdf->MultiCell($_P_WIDTH,6.2,$_TXT,0,"J");
		
		$h+=8*12.0;$center = $_WIDTH/2;
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->Line(65,$h,$_WIDTH-65,$h);
		
		$pdf->SetFillColor($_COLOR_SCHEME[0]);$pdf->SetLineWidth(0.4);$pdf->SetDash();
		$pdf->SetFillColor(255);$pdf->Circle($center-12.5,$h,3.7,'DF');$pdf->SetFillColor(220,41,30);$pdf->Circle($center-12.5,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center,$h,3.7,'DF');$pdf->SetFillColor($RGB['r'][2],$RGB['g'][2],$RGB['b'][2]);$pdf->Circle($center,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center+12.5,$h,3.7,'DF');$pdf->SetFillColor(0,174,216);$pdf->Circle($center+12.5,$h,2,'F');$pdf->SetDash();
		
		// --- Footer ---
		
		$pdf->SetFont('arial','',11);
		
		$w =  $_START_LEFT + 63;
		
		$h +=2.5+180;$pdf->Polygon(array($w+6+15,$h+3+10,$w+6+15-12.5-10,$h+3-12.5,$w+6+15+12.5+10,$h+3-12.5),'F');
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",98,$h+2,20,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->Text($w+6.5,$h+13*2.0,"www.oranky.com");
		
		// _____________________________________________________ Page #3. PREAMBLE AND INTRODUCTION __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$pdf->SetTextColor(65);
		$pdf->SetXY($w,$h);$pdf->SetFont('arial','B',10);$pdf->Cell(13,10,"-",0,"J");$pdf->SetXY($w+13,$h);$pdf->SetFont('arial','B',10);$pdf->MultiCell($_PC_WIDTH,10,"METHODOLOGY",0,"L");
		
		
		$h = 27;
		$pdf->SetFont('arial','B',10);
		
		 
		// --- --- --- NEW SECTION 
		
		$h = $pdf->GetY()+4.2;
		
		//$h +=14;
		$pdf->SetFont('arial','',10);
		
		$_TXT = "The SEO audit report is compiled in a manner that is accurate and geared to customer's satisfaction. After carefully auditing your site or cloud environment, we document the most critical factors in the following manner but not limited to:";
		$pdf->SetXY($w,$h);$pdf->Cell(13,6.2,"",0,"L");$pdf->SetXY($w+13,$h);$pdf->MultiCell($_PC_WIDTH,6.2,$_TXT,0,"J");$h+=8.2;$h+=(8.2*1.7);
	        $pdf->SetFont('arial','',10);    
		     $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+(13*2),$h);$pdf->MultiCell($_PC_WIDTH-(13*1),6.2,"identification of the scope of a typical SEO site or cloud environment audit and assessment;",0,"J");$h+=6.2;$h+=(6.2*1);
		     $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+(13*2),$h);$pdf->MultiCell($_PC_WIDTH-(13*1),6.2,"use of Artificial intelligence (AI) technics to identify specific customer's business needs;",0,"J");$h+=6.2;$h+=(6.2*0);
		     $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+(13*2),$h);$pdf->MultiCell($_PC_WIDTH-(13*1),6.2,"use of Machine Learning (ML) technics to interpret those specific needs into measurable and actionable SEO goals;",0,"J");$h+=8.2;$h+=(6.2*1);
		     $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+(13*2),$h);$pdf->MultiCell($_PC_WIDTH-(13*1),6.2,"evaluation of the site or cloud system SEO performance with actionable reviews; lastly",0,"J");$h+=6.2;$h+=(6.2*0);
		     $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+(13*2),$h);$pdf->MultiCell($_PC_WIDTH-(13*1),6.2,"compilation of a very simple but comprehensive report with recommendations.",0,"J");$h+=6.2;$h+=(6.2*0);
		
		
		// --- --- --- NEW SECTION
		
		$h = $pdf->GetY()+4.2;$pdf->SetFont('arial','B',10);
		
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"-",0,"J");$pdf->SetFont('arial','B',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_PC_WIDTH,10,"CONTENT STRATEGY APPROACH",0,"L");
		
		$h +=14;$pdf->SetFont('arial','',10);
		
		$_TXT = "Oranky takes advantage of rigorous Natural Language Processing (NLP) algorithms to conduct thorough content analysis of a site. Recognizing the role and function of key word phrases on SEO, it then-";
		$pdf->SetXY($w,$h);$pdf->Cell(13,6.2,"",0,"L");$pdf->SetXY($w+13,$h);$pdf->MultiCell($_PC_WIDTH,6.2,$_TXT,0,"J");$h+=8.2;$h+=(8.2*1.7);
	        
		$pdf->SetFont('arial','',10);       
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h);$pdf->MultiCell($_PC_WIDTH-13,6.2,"identifies the site's compelling keyword phrases;",0,"J");$h+=6.2;$h+=(6.2*0);
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h); $pdf->MultiCell($_PC_WIDTH-13,6.2,"plans a simple content strategy based on insights obtained from the analysis process;",0,"J");$h+=6.2;$h+=(6.2*0);
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h); $pdf->MultiCell($_PC_WIDTH-13,6.2,"creates a simple content strategy giving your business a competitive advangage over your competitors.",0,"J");$h+=6.2;$h+=(6.2*0);
		
		// --- --- --- NEW SECTION
		
		$h = $pdf->GetY()+4.2;$pdf->SetFont('arial','B',10);
		
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"-",0,"J");$pdf->SetFont('arial','B',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_PC_WIDTH,10,"SEO CRAWL BUDGET vs SITE ARCHITECTURE AND HTML",0,"L");
		
		$h +=14;$pdf->SetFont('arial','',10);
		
		$_TXT = "The amount of time and resources that Google and other search engines devote to crawling a site is commonly called the site's crawl budget. A site's HTML structure and architecture play a huge role towards a site's crawl budget. Oranky's HTML and architecture analysis process take into account crucial factors that influence this specific budget simply by-";
		$pdf->SetXY($w,$h);$pdf->Cell(13,6.2,"",0,"L");$pdf->SetXY($w+13,$h);$pdf->MultiCell($_PC_WIDTH,6.2,$_TXT,0,"J");$h+=8.2;$h+=(8.2*2.6);
	        
		$pdf->SetFont('arial','',10);       
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h);$pdf->MultiCell($_PC_WIDTH-13,6.2,"identifies the site structure and functioanl aspects that influnce crawlers;",0,"J");$h+=6.2;$h+=(6.2*0);
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h); $pdf->MultiCell($_PC_WIDTH-13,6.2,"analyses sitemaps to develop a SEO clear picture;",0,"J");$h+=6.2;$h+=(6.2*0);
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h); $pdf->MultiCell($_PC_WIDTH-13,6.2,"analyses usability of the site and not overlooking its human friendliness;",0,"J");$h+=6.2;$h+=(6.2*0);
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h); $pdf->MultiCell($_PC_WIDTH-13,6.2,"weighs the overall site architecure againts a typical search engine crawl budget taking into account the crawl rate limit and crawl health in particular;",0,"J");$h+=6.2;$h+=(6.2*1);
		    $pdf->Circle($w+(13*1.5),$h+3,1.0,'D');$pdf->SetXY($_START_LEFT+13+13,$h); $pdf->MultiCell($_PC_WIDTH-13,6.2,"makes recommendations on organic optimization strategy that could improve ranking, inbound marketing and drives traffic.",0,"J");$h+=6.2;$h+=(6.2*0);
		
		
		// --- Footer ---
		
		$pdf->SetFont('arial','',11);
		
		$w =  $_START_LEFT + 63;
		
		$h +=2.5+180;$pdf->Polygon(array($w+6+15,$h+3+10,$w+6+15-12.5-10,$h+3-12.5,$w+6+15+12.5+10,$h+3-12.5),'F');
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",98,$h+2,20,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->Text($w+6.5,$h+13*2.0,"www.oranky.com");
		
		// _____________________________________________________ Page #. Table of Contents __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(65);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,10," TABLE OF CONTENTS","B","L",true);
		
		$h = 27;
		$pdf->SetFont('arial','B',10);
		
		// --- Chapter 1: Definitions & Introductios
		
		$_h = $h;$w = $_START_LEFT;
		
		$pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"1.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"BRANDING",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[1]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"URL",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[2]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"Favicon",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[3]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"Custom 404 Page",0,"L");$h+=10;
		    
		// --- Chapter 2: Statement of Intent & Principles
		
		$h = $_h;$w = $_WIDTH/1.8;
		
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"2.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"KEY META TAGS",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);    
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[4]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"HTML Title Tag",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[5]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Meta Description Tag",0,"L");$h+=6;$h+=6*2;
		   
	       // --- Chapter 3: Stake Holders's involvement
	       
	       $_h = $h;$w = $_START_LEFT;
	       
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"3.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"CONTENT ANALYSIS",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);  
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[8]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Language",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[9]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"HTML Headings",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[21]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"In-Page Links",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[23]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Natural Language Processing (NLP)",0,"L");$h+=10;
		
		// --- Chapter 4: Procedures (Policy Management Unit)
		
		$h = $_h;$w = $_WIDTH/1.8;
		
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"4.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"INDEXING",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);  
		   $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[20]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"URL Resolve",0,"L");$h+=6;
		   $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[25]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"URL Underscores",0,"L");$h+=6;
		   $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[24]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Robots.txt",0,"L");$h+=6;$h+=6*4;
		    
		// --- Chapter 5: 
		
	       $_h = $h;$w = $_START_LEFT;
	       
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"5.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"TECHNOLOGIES",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);  
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[15]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Server Uptime",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[14]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Server IP Address",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[12]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Doctype",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[13]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Encoding",0,"L");$h+=10;
		    
		// --- Chapter 6: 
		
		$h = $_h;$w = $_WIDTH/1.8;
		
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"6.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"SECURITY",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);  
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[16]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"SSL Security",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[17]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Mixed Content",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[18]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Relative URLs",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[19]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Post Covid-19 Cyber Security Policy",0,"L");$h+=6;
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[22]["status"].".png",$w+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Information Security Policy",0,"L");$h+=6;$h+=6*1;
	        
		// --- Chapter 7: 
		
	       $_h = $h;$w = $_START_LEFT;
	       
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"7.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"TRAFFIC",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);  
		    $pdf->SetAlpha(0.75);$pdf->Image(ROOTPATH."images/iconography/".$_MATRIX_RESULTS[6]["status"].".png",$_START_LEFT+5.5,$h+2.5,6.0,0,'','');$pdf->SetAlpha(1);$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Traffic Rank",0,"L");$h+=6;$h+=6*1.5;
		    
		// --- Chapter 8: 
		
	       $_h = $h;$w = $_START_LEFT;
	       
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.1);
		$pdf->SetFont('arial','B',10);$pdf->SetXY(0,$h);$pdf->MultiCell($_WIDTH/1.5,6," Global Effort ","B","R",true);
		
		$h = $pdf->GetY()+4.2;
		
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"8.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"SUSTAINABILITY & ENVIRONMENTAL IMPACT",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);  
		    $pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Sustainable Development Goals (SDGs)",0,"L");$h+=6;$h+=6*1.5;
		   
		   // --- Chapter 9: 
		
		$_h = $h;$w = $_START_LEFT;
		
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.1);
		$pdf->SetFont('arial','B',10);$pdf->SetXY(0,$h);$pdf->MultiCell($_WIDTH/1.5,6," Governmental Bodies ","B","R",true);
		
		$h = $pdf->GetY()+4.2;
		
		$pdf->SetFont('arial','B',10);
		$pdf->SetXY($w,$h);$pdf->Cell(13,10,"9.",0,"L");$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"SERVICE DELIVERY",0,"L");$h+=7;
		$pdf->SetFont('arial','',10);  
		    $pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Dependency on Grants",0,"L");$h+=6;
		    $pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Service Delivery",0,"L");$h+=6;
		    $pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Tender Scam Vulnerability",0,"L");$h+=6;
		    $pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"O-thenticity",0,"L");$h+=6;$h+=6*1.5;
	        
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		// _____________________________________________________ Page #: Iconography __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(65);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,10," ICONOGRAPHY","B","L",true);
		
		$h = 27;
		$pdf->SetFont('arial','B',10);
		
		// --- Generic
		
	       $h = $pdf->GetY()+8.2;
	       
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.1);
		$pdf->SetFont('arial','B',10);$pdf->SetXY(0,$h);$pdf->MultiCell($_WIDTH/1.5,6," SEO ","B","R",true);
		
		$h = $pdf->GetY()+4.2;
		
		$pdf->SetFont('arial','',11);
		$pdf->Image(ROOTPATH."images/iconography/danger.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Errors",0,"L");$h+=7;
		$pdf->Image(ROOTPATH."images/iconography/warning.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Require Improvement",0,"L");$h+=7;
		$pdf->Image(ROOTPATH."images/iconography/success.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Passed",0,"L");$h+=7;
		$pdf->Image(ROOTPATH."images/iconography/informational.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Informational",0,"L");$h+=7;$h+=6*1.5;
		
		   // --- Information Security, Cybersecurity and Privacy 
		
		$_h = $h;$w = $_START_LEFT;
		
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.1);
		$pdf->SetFont('arial','B',10);$pdf->SetXY(0,$h);$pdf->MultiCell($_WIDTH/1.5,6," Information Security, Cybersecurity and Privacy ","B","R",true);
		
		$h = $pdf->GetY()+4.2;
		
		$pdf->SetFont('arial','',11);
		$pdf->Image(ROOTPATH."images/iconography/security-danger.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Require Protective Measures",0,"L");$h+=7;
		$pdf->Image(ROOTPATH."images/iconography/security-warning.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Require Preventive Measures",0,"L");$h+=7;
		$pdf->Image(ROOTPATH."images/iconography/security-success.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Secured",0,"L");$h+=7;$h+=6*1.5;
		
		   // --- Marketing 
		
		$_h = $h;$w = $_START_LEFT;
		
		$pdf->SetFillColor($_COLOR_SCHEME[5]+135);$pdf->SetDrawColor(95);$pdf->SetLineWidth(0.1);
		$pdf->SetFont('arial','B',10);$pdf->SetXY(0,$h);$pdf->MultiCell($_WIDTH/1.5,6," Marketing ","B","R",true);
		
		$h = $pdf->GetY()+4.2;
		
		$pdf->SetFont('arial','',11);
		$pdf->Image(ROOTPATH."images/iconography/competitors.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Competitors",0,"L");$h+=7;
		$pdf->Image(ROOTPATH."images/iconography/target-customer.png",$w+0,$h+2.5,6.0,0,'','');$pdf->SetXY($w+13,$h);$pdf->Cell(130,10,"Target Customers",0,"L");$h+=7;
		
	        
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		// _____________________________________________________ Page #: SEO Report Overview __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," SEO Report Overview ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
		// --- Generic
		
	       $_STATUS = 0;
	       
	       // --- --- --- 1. BRANDING
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"1.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"BRANDING",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $pdf->SetFont('arial','',11);$pdf->SetFillColor(75);
	       $pdf->SetTextColor(255);$pdf->RoundedRect($w+1+15+133.5,$h-16+6,75,6,1,'F');$pdf->Text($w+4+15+133.5,$h+8.0-20+6,"Impact Analysis");
	       $pdf->SetTextColor(65);
	       
		
	       $_ruleNo = 1;$_ruleName = "URL"; // --- --- --- RULE #1: BRANDING - URL 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
		
	       $_ruleNo = 2;$_ruleName = "Favicon"; // --- --- --- RULE #2: BRANDING - FAVICON 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 3;$_ruleName = "Custom 404 Page"; // --- --- --- RULE #3: BRANDING - CUSTOM 404 PAGE
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
	       
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Medium");$pdf->SetTextColor(65);
	       
	       // --- --- --- 2. KEY META TAGS
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"2.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"KEY META TAGS",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $_ruleNo = 4;$_ruleName = "Title Tag"; // --- --- --- RULE #4: KEY META TAGS - TITLE TAG 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 5;$_ruleName = "Meta Description"; // --- --- --- RULE #5: KEY META TAGS - DESCRIPTION META TAG
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
		
	       // --- --- --- 3. CONTENT ANALYSIS
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"3.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"CONTENT ANALYSIS",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $_ruleNo = 8;$_ruleName = "Language"; // --- --- --- RULE #8: CONTENT ANALYSIS - LANGUAGE 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Medium");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 9;$_ruleName = "HTML Headings"; // --- --- --- RULE #9: CONTENT ANALYSIS - HTML HEADINGS
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Medium");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 21;$_ruleName = "Site Links"; // --- --- --- RULE #21: CONTENT ANALYSIS - HTML IN-PAGE LINKS
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Medium");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 23;$_ruleName = "Natural Language Processing (NLP)"; // --- --- --- RULE #23: CONTENT ANALYSIS - NLP
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       
	       // --- --- --- 4. INDEXING
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"4.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"INDEXING",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $_ruleNo = 20;$_ruleName = "URL Resolve"; // --- --- --- RULE #20: INDEXING - URL RESOLVE 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 25;$_ruleName = "URL Underscores"; // --- --- --- RULE #25: INDEXING - URL UNDERSCORES
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 24;$_ruleName = "Robots.txt"; // --- --- --- RULE #24: INDEXING - ROBOTS.TXT 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       
	       // --- --- --- 5. TECHNOLOGIES
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"5.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"TECHNOLOGIES",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $_ruleNo = 15;$_ruleName = "Server Uptime"; // --- --- --- RULE #15: TECHNOLOGIES - SERVER UPTIME 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 14;$_ruleName = "Server IP Address"; // --- --- --- RULE #14: TECHNOLOGIES - SERVER IP
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 12;$_ruleName = "Doctype"; // --- --- --- RULE #12: TECHNOLOGIES - DOCTYPE
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 13;$_ruleName = "Encoding"; // --- --- --- RULE #13: TECHNOLOGIES - ENCODING
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
	       
	       
	       // --- --- --- 6. SECURITY
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"6.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"SECURITY",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $pdf->SetFont('arial','',11);$pdf->SetFillColor(75);
	       $pdf->SetTextColor(255);$pdf->RoundedRect($w+1+10+83.5,$h-16+6,100,6,1,'F');$pdf->Text($w+4+10+83.5,$h+8.0-20+6,"Security Domain");
	       $pdf->SetTextColor(65);
	       
	       $_ruleNo = 16;$_ruleName = "SSL Security"; // --- --- --- RULE #16: SECURITY - SSL SECURITY
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+95,$h-6*1.25+1.5,$w+95,$h-6*1.25+4.5);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+95+3,$h-6*1.25+4.0,"Protection");$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 17;$_ruleName = "Mixed Content"; // --- --- --- RULE #17: SECURITY - MIXED CONTENT
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+95,$h-6*1.25+1.5,$w+95,$h-6*1.25+4.5);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+95+3,$h-6*1.25+4.0,"Protection, Defence");$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 18;$_ruleName = "Relative URLs"; // --- --- --- RULE #18: SECURITY - RELATIVE URLS
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+95,$h-6*1.25+1.5,$w+95,$h-6*1.25+4.5);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+95+3,$h-6*1.25+4.0,"Governance");$pdf->Text($w+150+3,$h-6*1.25+4.0,"Medium");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 19;$_ruleName = "Post COVID-19 Cyber Security Policy"; // --- --- --- RULE #19: SECURITY - POST COVID-19 CYBER SECURITY
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+95,$h-6*1.25+1.5,$w+95,$h-6*1.25+4.5);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+95+3,$h-6*1.25+4.0,"Protection, Defence, Resilience");$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 22;$_ruleName = "Information Security Policy"; // --- --- --- RULE #22: SECURITY - INFORMATION SECURITY POLICY
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+95,$h-6*1.25+1.5,$w+95,$h-6*1.25+4.5);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+95+3,$h-6*1.25+4.0,"Governance");$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
		
		
		
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		// _____________________________________________________ Page #: SEO Report Overview #2 __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		//$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," SEO Report Overview ","B","R",true);
		
		//$h = 27;
		$pdf->SetTextColor(65);
		
		// --- Generic
		
	       $_STATUS = 0;
	       
	       
	       // --- --- --- 7. TRAFFIC
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"7.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"TRAFFIC",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $_ruleNo = 6;$_ruleName = "Traffic Rank"; // --- --- --- RULE #6: TRAFFIC - TRAFFIC RANK
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
	       
	       // --- --- --- 8. USTAINABILITY & ENVIRONMENTAL IMPACT
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"8.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"SUSTAINABILITY & ENVIRONMENTAL IMPACT",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $_ruleNo = 1;$_ruleName = "Sustainable Development Goals (SDGs)"; // --- --- --- RULE #6: BRANDING - URL 
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
	       
		
		
	       // --- --- --- 9. SERVICE DELIVERY
	       
	       $pdf->SetFont('arial','B',10);$h = $pdf->GetY()+4.2;
	       $pdf->SetXY($_START_LEFT,$h);$pdf->Cell(13,10,"9.",0,"L");$pdf->SetXY($_START_LEFT+13,$h);$pdf->Cell(130,10,"SERVICE DELIVERY",0,"L");$h+=7*1.5;
	       $pdf->SetFont('arial','',10);
	       
	       $_ruleNo = 6;$_ruleName = "Dependency on Grants"; // --- --- --- RULE #6: TRAFFIC - TRAFFIC RANK
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Medium");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 6;$_ruleName = "Service Delivery"; // --- --- --- RULE #6: TRAFFIC - TRAFFIC RANK
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 6;$_ruleName = "Tender Scam Vulnerability"; // --- --- --- RULE #6: TRAFFIC - TRAFFIC RANK
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"Low");$pdf->SetTextColor(65);
	       
	       $_ruleNo = 6;$_ruleName = "O-thenticity"; // --- --- --- RULE #6: TRAFFIC - TRAFFIC RANK
	       $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	       $pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
	       $pdf->SetAlpha(0.0);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w+6,$h+0.0,8,0,'','');$pdf->SetAlpha(1);$pdf->SetFont('arial','',10);$pdf->SetXY($w+13,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);$h+=6*1.25;
		
	       $pdf->SetLineWidth(0.1);$pdf->SetDrawColor(195);$pdf->SetDash(1,1);$pdf->Line($w+150,$h-6*1.25+1.5,$w+150,$h-6*1.25+4.5);$pdf->SetDash();
	       $pdf->SetFont('arial','',9.5);$pdf->SetTextColor(155);$pdf->Text($w+150+3,$h-6*1.25+4.0,"High");$pdf->SetTextColor(65);
	       
		
		
		
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		/*
		// _____________________________________________________ Page #: BRANDING: URL & Favicon __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 1. Branding ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #1: BRANDING - URL 
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 1;$_ruleName = "URL";$_subSection = "branding_url";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+6.5;$pdf->SetFont('arial','',12);
		$pdf->SetLeftMargin($w-3.0);$pdf->SetY($h);$pdf->writeHTML($_SUBJECT);
		
		$h = $pdf->GetY()+17.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,60,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+54.5);
		$pdf->writeHTML($_NOTICE);
		
		// --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #2: BRANDING - FAVICON 
		
		$h = $pdf->GetY()+24.2;
		
	         $_ruleNo = 2;$_ruleName = "Favicon";$_subSection = "branding_favicon";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+6.5;$pdf->SetFont('arial','',12);
		
		if($_STATUS==2)
		{
		     $_ICON_PATH = isset($_MATRIX_RESULTS[$_ruleNo]["icon_path"]) ? $_MATRIX_RESULTS[$_ruleNo]["icon_path"] : "";
		     //$pdf->Image($_ICON_PATH,$w-3.0,$h+0.0,4,0,'','');
		     $pdf->SetLeftMargin($w-3.0);
	        }
		else{$pdf->SetLeftMargin($w-3.0);}
		
		$pdf->SetY($h);$pdf->writeHTML($_SUBJECT);
		
		$h = $pdf->GetY()+17.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,60,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+54.5);
		$pdf->writeHTML($_NOTICE);
		
		// --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		
		// _____________________________________________________ Page #: BRANDING: Custom 404 Page __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetTextColor(65);
		
		// RULE #3: BRANDING - CUSTOM 404 PAGE 
		
	        $_ruleNo = 3;$_ruleName = "Custom 404 Page";$_subSection = "branding_custom_404_page";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+6.5;$pdf->SetFont('arial','',12);
		$pdf->SetLeftMargin($w-3.0);$pdf->SetY($h);$pdf->writeHTML($_SUBJECT);
		
		$h = $pdf->GetY()+17.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,60,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+54.5);
		$pdf->writeHTML($_NOTICE);
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		
		// _____________________________________________________ Page #: KEY META TAGS: URL & Title Tag __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 2. Key Meta Tags ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #4: KEY META TAGS - TITLE TAG 
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 2;$_ruleName = "Title Tag";$_subSection = "key_meta_tag_title_tag";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		$pdf->SetTextColor(155);
		$h = $pdf->GetY()+6.5;$pdf->SetFont('arial','',12);$pdf->SetTextColor(155);
		$pdf->SetLeftMargin($w-3.0);$pdf->SetY($h);$pdf->writeHTML($_SUBJECT);
		
		$h = $pdf->GetY()+17.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,46,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+40.5);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
		// --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #5: KEY META TAGS - DESCRIPTION META TAG 
		
		$h = $pdf->GetY()+24.2;
		
	         $_ruleNo = 5;$_ruleName = "Meta Description";$_subSection = "key_meta_tag_meta_description_tag";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+6.5;$pdf->SetFont('arial','',12);
		
		if($_STATUS==2)
		{
		     $_ICON_PATH = isset($_MATRIX_RESULTS[$_ruleNo]["icon_path"]) ? $_MATRIX_RESULTS[$_ruleNo]["icon_path"] : "";
		     //$pdf->Image($_ICON_PATH,$w-3.0,$h+0.0,4,0,'','');
		     $pdf->SetLeftMargin($w-3.0);
	        }
		else{$pdf->SetLeftMargin($w-3.0);}
		$pdf->SetTextColor(195);
		$pdf->SetY($h);$pdf->writeHTML($_SUBJECT);
		
		$h = $pdf->GetY()+17.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetTextColor(195);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,80,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+74.5);
		$pdf->SetTextColor(195);$pdf->writeHTML($_NOTICE);
		
		// --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		
		// _____________________________________________________ Page #: CONTENT ANALYSIS __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 3. Content Analysis ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #4: KEY META TAGS - TITLE TAG 
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 8;$_ruleName = "Language";$_subSection = "content_analysis_language";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #5: KEY META TAGS - DESCRIPTION META TAG 
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 9;$_ruleName = "HTML Headings";$_subSection = "content_html_headings";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #21: CONTENT ANALYSIS - HTML IN-PAGE LINKS 
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 21;$_ruleName = "Site Links";$_subSection = "content_html_in_page_links";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #23: CONTENT ANALYSIS - NLP
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 23;$_ruleName = "Natural Language Processing (NLP)";$_subSection = "content_nlp";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		// _____________________________________________________ Page #: INDEXING __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 4. Indexing ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #20: INDEXING - URL RESOLVE
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 20;$_ruleName = "URL Resolve";$_subSection = "indexing_url_resolve";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		
		// _____________________________________________________ Page #: TECHNOLOGIES __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 5. Technologies ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #15: TECHNOLOGIES - SERVER UPTIME
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 15;$_ruleName = "Server Uptime";$_subSection = "technologies_server_uptime";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #14: TECHNOLOGIES - SERVER IP
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 14;$_ruleName = "Server IP Address";$_subSection = "technologies_server_ip_address";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #12: TECHNOLOGIES - DOCTYPE 
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 12;$_ruleName = "Doctype";$_subSection = "technologies_doctype";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #13: TECHNOLOGIES - ENCODING
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 13;$_ruleName = "Encoding";$_subSection = "technologies_encoding";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		
		// _____________________________________________________ Page #: SECURITY __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 6. Security ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #16: SECURITY - SSL SECURITY
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 16;$_ruleName = "SSL Security";$_subSection = "security_ssl_security";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #17: SECURITY - MIXED CONTENT
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 17;$_ruleName = "Mixed Content";$_subSection = "security_mixed_content";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #18: SECURITY - RELATIVE URLS
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 18;$_ruleName = "Relative URLs";$_subSection = "security_relative_urls";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #19: SECURITY - POST COVID-19 CYBER SECURITY
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 19;$_ruleName = "Post COVID-19 Cyber Security Policy";$_subSection = "security_post_covid19_cyber_security";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #22: SECURITY - INFORMATION SECURITY POLICY
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 22;$_ruleName = "Information Security Policy";$_subSection = "security_information_security_policy";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		// _____________________________________________________ Page #: TRAFFIC __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 7. Traffic ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #6: TRAFFIC - TRAFFIC RANK
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 6;$_ruleName = "Traffic Rank";$_subSection = "traffic_traffic_rank";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		// _____________________________________________________ Page #: SUSTAINABILITY & ENVIRONMENTAL IMPACT __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 8. Sustainability & Environmental Impact ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #6: TRAFFIC - TRAFFIC RANK
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 6;$_ruleName = "Sustainable Development Goals (SDGs)";$_subSection = "traffic_traffic_rank";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		
		// _____________________________________________________ Page #: SERVICE DELIVERY __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		$_STATUS = 0;
		
		$pdf->SetFillColor(212,233,254);$pdf->SetDrawColor(33,134,230);$pdf->SetLineWidth(0.6);
		
		$pdf->SetFont('arial','',18);$pdf->SetTextColor(33,134,230);$pdf->SetXY(0,$h);$pdf->MultiCell($_PC_WIDTH,10," 9. Service Delivery ","B","R",true);
		
		$h = 27;
		$pdf->SetTextColor(65);
		
	        // RULE #6: TRAFFIC - TRAFFIC RANK
		
		$h = $pdf->GetY()+8.2;
		
	        $_ruleNo = 6;$_ruleName = "Dependency on Grants";$_subSection = "traffic_traffic_rank";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #22: SECURITY - INFORMATION SECURITY POLICY
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 6;$_ruleName = "Service Delivery";$_subSection = "traffic_traffic_rank";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #22: SECURITY - INFORMATION SECURITY POLICY
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 6;$_ruleName = "Tender Scam Vulnerability";$_subSection = "traffic_traffic_rank";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
	        // RULE #22: SECURITY - INFORMATION SECURITY POLICY
		
		$h = $pdf->GetY()+28.2;$pdf->SetTextColor(65);
		
	         $_ruleNo = 6;$_ruleName = "O-thenticity";$_subSection = "traffic_traffic_rank";
			
	        //$array["rules"]["branding_url"] = "";
	        $_STATUS = isset($_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]]) ? $_S_DESC[$_MATRIX_RESULTS[$_ruleNo]["status"]] : 0;$_BG_SUBTRACT = (isset($_STATUS) && ($_STATUS==3)) ? 15 : 0;
	        $_SUBJECT = isset($_MATRIX_RESULTS[$_ruleNo]["subject"]) ? $_MATRIX_RESULTS[$_ruleNo]["subject"] : "";
	        $_NOTICE = isset($_MATRIX_RESULTS[$_ruleNo]["notice"]) ? $_MATRIX_RESULTS[$_ruleNo]["notice"] : "Report only available for premium users";
		
		$pdf->SetDrawColor($C_A['r'][$_STATUS]-55,$C_A['g'][$_STATUS]-55,$C_A['b'][$_STATUS]-55);$pdf->SetFillColor($C_A['r'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['g'][$_STATUS]+165-$_BG_SUBTRACT,$C_A['b'][$_STATUS]+165-$_BG_SUBTRACT);$pdf->SetLineWidth(0.3);
		$pdf->SetFont('arial','B',10);$pdf->Image(ROOTPATH."images/iconography/".$S_DESC[$_STATUS].".png",$w-9,$h+0.0,8,0,'','');$pdf->SetXY($w-2,$h);$pdf->MultiCell($_WIDTH,6," ".$_ruleName,"B","L",true);
		
		$h = $pdf->GetY()+7.5;$w += 0.75;$pdf->SetFont('arial','',10.75);$pdf->SetFillColor($C_A['r'][3]+150,$C_A['g'][3]+150,$C_A['b'][3]+150);
		$pdf->SetLeftMargin($w-0.5);$pdf->SetY($h);
		$pdf->Rect(0,$h-5,220,26,'F');$pdf->SetLineWidth(1);$pdf->Line($w-2.5,$h-4.5,$w-2.5,$h+20.5);
		$pdf->SetTextColor(225);$pdf->SetFont('arial','',24);
		$pdf->writeHTML("<span style='color:#cccccc;line-height:24px;'>".$_NOTICE."</span>");
		
			 // --- --- --- --- --- --- --- --- --- --- --- --- End of rule
		
		// --- --- --- Footer
		
		$h = $_HEIGHT/3.95-0.5+187.5;
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",$w+$_PC_WIDTH+15,$h+15,11.5,0,'','');
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($w+$_PC_WIDTH-15,$h+13*1.7,"www.oranky.com");
		*/
		
		// _____________________________________________________ Page : LAST PAGE __________________________________________________
		
		$pdf->AddPage();$p++;$h = $_START_TOP;$w = $_START_LEFT;
		
		//$pdf->SetFillColor(81,86,89);
		$pdf->SetFillColor(248,249,251);$pdf->SetFillColor(248,249,251);$pdf->SetLineWidth(0.1); 
		$pdf->RoundedRect(0,0,$_WIDTH/2,$_HEIGHT,0,'F');
		
		$_TXT = "SEO Review\nReport";
		$pdf->SetTextColor(95);$pdf->SetFont('arial','B',38);$pdf->SetXY($w-13,122.5);$pdf->MultiCell($_P_WIDTH/2,25.5,$_TXT,0,"C");
		
		$pdf->SetFont('arial','B',12);
		$pdf->SetTextColor(95);$pdf->Text($w-12.0-5,$h+185.20-28*0.735,"SEO | Digital Marketing |  Information Security");$h +=8*2.0;
		
		$pdf->SetTextColor(95);$pdf->SetFont('arial','',11.5);
		$pdf->Image(ROOTPATH."images/icons/message-16.png",$w+1-5,$h+184.5-28*0.715,0,0,'','');$pdf->Text($w+7.5-5,$h+188.20-28*0.735,"oranky@spiderblackonline.co.za");$h +=8*1.0;
		$pdf->Image(ROOTPATH."images/organizations/tel-64.png",$w+1-5,$h+184.0-28*0.715,5,0,'','');$pdf->Text($w+7.5-5,$h+188.20-28*0.735,"+27.87 701 5384");
		
		$_STATUS = 0;
		
		$pdf->SetDrawColor(33,134,230);
		$pdf->SetTextColor(33,134,230);
		
		$pdf->SetFont('arial','B',58);$pdf->SetLineWidth(1.5);
		
		$pdf->SetTextColor(0);$pdf->SetFont('arial','I',12);
		
		$h = 253;$h = $_HEIGHT/2+90;$center = $_WIDTH/(1.35);
		
		$pdf->SetLineWidth(0.1);$pdf->SetDrawColor(0);$pdf->Line($center-45,$h,$center+45,$h);
		
		$pdf->SetFillColor($_COLOR_SCHEME[0]);$pdf->SetLineWidth(0.4);$pdf->SetDash();
		$pdf->SetFillColor(255);$pdf->Circle($center-12.5,$h,3.7,'DF');$pdf->SetFillColor($_COLOR_SCHEME[1]+55);$pdf->Circle($center-12.5,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center,$h,3.7,'DF');$pdf->SetFillColor($_COLOR_SCHEME[1]+55);$pdf->Circle($center,$h,2,'F');
		$pdf->SetFillColor(255);$pdf->Circle($center+12.5,$h,3.7,'DF');$pdf->SetFillColor($_COLOR_SCHEME[1]+55);$pdf->Circle($center+12.5,$h,2,'F');$pdf->SetDash();
		
		$h = 269+13;
		
		$pdf->SetFillColor(81,86,89);$pdf->SetDrawColor(81,86,89);$pdf->SetLineWidth(1.5);
		
		$pdf->Line(0,$_HEIGHT/2,$_WIDTH/2-0.8,$_HEIGHT/2);
		
		// --- --- --- NEW SECTION
		
		$h = $pdf->GetY()+23.5;
		
		
		$pdf->SetLineWidth(0.1);
		
		$h +=7;$w =  $w + 60.5;$pdf->SetTextColor(55);
		
		$h = $_HEIGHT/2;$w =  $w + 2.5;
		$pdf->Polygon(array($w+6+15,$h+3+10,$w+6+15-2.5-10,$h+3-2.5,$w+6+15+2.5+10,$h+3-2.5),'F');
		
		$w=120;$h = 90.0;
		$pdf->SetFont('arial','',16);$pdf->SetFillColor(55);
		
		$h +=11;$h +=6.5;$h +=15;
		
		$__TXT = "A website is a vital tool responsible for enhancement of an organization's corporate image while increasing its name, brand, and identity's public awareness. It boosts the advocacy and marketing of the organization's programs of action as well as its product and services.";
		$__TXT .= "\n\n";
		$__TXT .= "Oranky's SEO, Search Engine Optimization, reports provide meaningful and actionable reviews to improve a site or cloud environment's organizational objectives to accomplish strategic goals.";
		$__TXT .= "\n\n";
		$pdf->SetTextColor(205);$pdf->SetFont('arial','',15);$pdf->RoundedRect($center-40,$h+5,42.5*2,8,1,'F');$pdf->Text($w+2,$h+11.0,"SEO: Search Engine Optimization");$h +=13.0;
		//$pdf->SetTextColor(75);$pdf->SetFont('arial','',13);$pdf->SetXY($center-40+1,$h+5);$pdf->MultiCell(42.5*2,5,"ERP - Enterprise Resource Planning",0,"L");$h +=6.5;
		$pdf->SetTextColor(155);$pdf->SetFont('arial','',11);$pdf->SetXY($center-40+1,$h+5);$pdf->MultiCell(42.5*2,6,$__TXT,0,"J");$h +=15;
		
		
		// --- --- --- NEW SECTION
		
		$pdf->SetLineWidth(0.1);
		
		$h +=7;$w =  $w + 60.5;$pdf->SetTextColor(55);
		
		//$h = $_HEIGHT/2-90;$w =  $w + 2.5-5;
		//$pdf->Polygon(array($w+6+15,$h+3+10,$w+6+15,$h+3-10-2.5,$w+6+15+2.5+10,$h+3-2.5),'F');
		
		$w=120;$h = 64.5;
		$pdf->SetFont('arial','I',18);$pdf->SetTextColor(125);
		$center = $center +10;
		//$pdf->SetXY($center-50-5.5,$h+5.5,42.5*2);$pdf->MultiCell($_HEIGHT/2.0,15,"Industries:",0,"L");$h +=15;
		
	        $pdf->SetTextColor(0);$pdf->SetFont('arial','',16);
		//$pdf->Image(ROOTPATH."images/icons/tick-black.png",$center-45-5.5,$h+5+5.0,5.0,0,'','');
		//$pdf->Image(ROOTPATH."images/icons/tick_valid_approve_icon.png",$center-45-5.5,$h+5+5.0,5.0,0,'','');$pdf->SetXY($center-45,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Earth moving",0,"L");$h +=12.5;
		//$pdf->Image(ROOTPATH."images/icons/tick_valid_approve_icon.png",$center-45-5.5,$h+5+5.0,5.0,0,'','');$pdf->SetXY($center-45,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Forestry",0,"L");$h +=12.5;
		//$pdf->Image(ROOTPATH."images/icons/tick_valid_approve_icon.png",$center-45-5.5,$h+5+5.0,5.0,0,'','');$pdf->SetXY($center-45,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Logistics",0,"L");$h +=12.5;
		//$pdf->Image(ROOTPATH."images/icons/tick_valid_approve_icon.png",$center-45-5.5,$h+5+5.0,5.0,0,'','');$pdf->SetXY($center-45,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Ports",0,"L");$h +=12.5;
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$center-45-5.5,$h+5+5.5,5.0,0,'','');$pdf->SetXY($center-42.5,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Find and Fix Issues",0,"L");$h +=12.5;
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$center-45-5.5,$h+5+5.5,5.0,0,'','');$pdf->SetXY($center-42.5,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Optimize a Site",0,"L");$h +=12.5;
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$center-45-5.5,$h+5+5.5,5.0,0,'','');$pdf->SetXY($center-42.5,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Keyword Strategy",0,"L");$h +=12.5;
		$pdf->Image(ROOTPATH."images/icons/tick-black.png",$center-45-5.5,$h+5+5.5,5.0,0,'','');$pdf->SetXY($center-42.5,$h+5,42.5*2);$pdf->MultiCell($_HEIGHT/3.0,15,"Market Analysis",0,"L");$h +=12.5;
		
		
		$pdf->Image(ROOTPATH."images/organizations/spiderblack-logo-large-c.png",7,$h+158,25,0,'','');
		
		// --- Footer ---
		
		$h = 232.5;
		
		$pdf->Image(ROOTPATH."images/ovhcar-logo.png",102.25,$h+40,11.5,0,'','');$h +=8*1.0;
		$pdf->SetFont('arial','B',10);$pdf->SetTextColor(25);$pdf->Text($_START_LEFT+70.0,$h+13.3+29+5,"www.oranky.com");
		$h +=8*1.0;
		$pdf->SetFont('arial','',10);$pdf->SetTextColor(125);$pdf->Text(128,$h+39.4,"Oranky, a division of Spider Black Online (PTY) Ltd.");
		
	}
	
	$pdf->SetAuthor('Eric M. Mulovhedzi - Oranky, a division of Spider Black Online (PTY) Ltd');   
	$pdf->SetTitle("SEO Review Report - ".date('d M Y')." - ".$_REQUEST_URL);
	
	if(isset($_GET['_file']) && ($_GET['_file']==1))
	{
		$pdf->Output("SEO Review Report - ".date('d M Y')." - ".$_REQUEST_URL.".pdf","I");
		/*
		$userInfo = array($_SESSION['accesses']->_login['id'],$_SESSION['accesses']->_login['cname'],$_SESSION['accesses']->_login['email'],$_SESSION['accesses']->_login['cell'],$_SESSION['accesses']->_login['image']);
						
		if(is_array($userInfo))
		{
			include(ROOTPATH."inc/notifications_processworkflows.php");
			$itemInfo = array($_id,$page,NOW());
			
			loggerConfirmationNotificationUMLProcessWorkflow($userInfo,$itemInfo);
		}*/
	}
	else{$pdf->Output("SEO Review Report - ".date('d M Y')." - ".$_REQUEST_URL.".pdf","D");}
}
?>
		