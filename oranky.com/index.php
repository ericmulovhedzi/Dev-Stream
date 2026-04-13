<?
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

header('Access-Control-Allow-Origin: *');

if (!isset($_GET['p'])) {$_GET['p'] = 'home';}
//define('WWWROOT',"http://localhost/smpls/profiles/web/orank.co.za/");
//define('WWWROOT',"http://oranky.com/");
define('WWWROOT',"https://hrdcsaqa.net/oranky/");

function formStar(){echo "<i style='color:#FB4357;'>(*)</i>";}
?>
<!-- Copyright: 2021 -->
<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="en" lang="en">
<head> 
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Oranky | 
	 <?  
		if($_GET['p'] == "about") echo "About our road safety initiative";
		else if($_GET['p'] == "contacts") echo "Please, contact us for enquiries";
		else if($_GET['p'] == "how-it-all-works") echo "Download the iMbobo App on your smartphone";
		else if($_GET['p'] == "legal-donors-relations") echo "Our tactical initiatives, services and products";
		else if($_GET['p'] == "developers") echo "Integrate with Road Safety and Service Delivery APIs";
		else echo "Search Engine Optimization and Digital Marketing Tool";
	?>
	</title>

	<meta name="description" content="iMbobo App is an OVH Self Driving Car company initiative that aims to facilitate a smooth and seamless transition into the fourth industrial revolution 4IR self driving cars technology." />
	<meta name="keywords" content="road, safety, pothole, records, management, reporting, municipality, ward, councillor, service, delivery" />
	<meta name="distribution" content="global" /> 
	<meta name="robots" content="follow, all" /> 
	<meta name="language" content="en, sv" /> 
	<meta name="copyright" content="&copy; 2021 - iMbobo App NPC" />
	<meta name="author" content="Spider Black Online, OVH Self-driving Car Company,  />

	<meta name="geo.country" content="RSA" />
	<meta name="geo.region" content="za" /> 
	<meta name="geo.placename" content="Johannesburg, Midrand, Gauteng, South Africa" />
	<meta name="geo.position" content="28.02358,-26.041052" />

	<meta name="DC.Identifier" content="http://www.smsovh.co.za" /> 
	<meta name="DC.Title" content="IMBOBO APP" /> 
	<meta name="DC.Creator" content="OVH Self-drving Car Company" /> 
	<meta name="DC.Subject" scheme="SCIS" content="Pothole reporting and records management tool" /> 
	<meta name="DC.Description" content="iMbobo App is a othole reporting and records management solution provider base in South Africa." /> 
	
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!-- Bootstrap CSS -->
			
	<link rel="stylesheet" href="<? echo WWWROOT; ?>style/bootstrap/4.5.3/css/bootstrap.css" />
	
	<link href="<? echo WWWROOT; ?>style/styles.css" rel="stylesheet" type="text/css" />
	<link href="<? echo WWWROOT; ?>images/favicons/xfavicon.ico" type="image/x-icon" rel="shortcut icon"/>
	<link rel="alternate" type="application/rss+xml" title="Initiatives and Tactical Products and Services updates from iMbobo APP NPC Feeds" href="<? echo WWWROOT; ?>road-safety-service-delivery-solutions.xml" />
	
	<!-- Optional JavaScript -->
	<script type="text/javascript" src="<? echo WWWROOT; ?>style/jquery-3.6.0.min.js"></script>
	<script type="text/javascript" src="<? echo WWWROOT; ?>style/bootstrap/4.1.0/js/1.14.0/umd/popper.min.js"></script>
	<script type="text/javascript" src="<? echo WWWROOT; ?>style/bootstrap/4.5.3/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<? echo WWWROOT; ?>style/js.js"></script>
</head> 
<body>
<script type="text/javascript">
 /* var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-15925270-3']); _gaq.push(['_trackPageview']); (function() {	 var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();*/
</script>
<div id="body_wrap"> 
	<div id="page_wrap">
		<?
			include_once("modules/header.php");
			//include_once("modules/menu_mobile.php");
			
			// --- body
			if (file_exists("pages/".$_GET['p'].".php")){include_once("pages/".$_GET['p'].".php");} 
			else{include_once("server-error-404-page.php");}//{echo "<div><div><div class='col-md-12'><div style='padding:5%;'><h6 style='font-weight:normal;'>Sorry, this page is under construction..</h6></div></div></div></div>";}
			//include_once("modules/footer.php");
		?> 
	</div> 
</div>
</body>
</html>