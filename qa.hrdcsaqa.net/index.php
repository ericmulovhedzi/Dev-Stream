<?
//error_reporting(E_ALL);ini_set('display_errors', 1);

require_once('inc/connection.php');

// --- Check for modules with alarms on their dashboards ---

echo "---:---";

$_IS_MOBLE = false;

function isMobileDevice(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );

    //Return true if Mobile User Agent is detected
    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    //Otherwise return false..  
    return false;
}

if(isMobileDevice())
{ 
    $_IS_MOBLE = true;
}
else{
    $_IS_MOBLE = false;
}
//print_r($_SERVER['HTTP_USER_AGENT']);
/*$useragent=$_SERVER['HTTP_USER_AGENT'];

if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))

{ 
    echo "mobile";
}
else{
    echo "desktop";
}*/

if(isset($_SESSION['accesses']->_access))
{
$accessArr=$_SESSION['accesses']->_access;$alarmModulesArr=array();$_sql_in = "";
//while(list($k,$v) = each($accessArr))
foreach($accessArr as $k=>$v)
{
    if(isset($v) && (sizeof($v)>=1))
    {
    	//while(list($k_,$v_) = each($v))
	foreach($v as $k_=>$v_)
    	{
    		if($k_>0)
    		{
    			$_sql_in .= $k_.",";
    		}
    	}
    }
}
}

if(!empty($_sql_in))
{
	$_sql_in = substr_replace($_sql_in, '', -1);
	$alarmModulesArr[88] = "Alarm";
	$rs = $db->Execute("SELECT
						`A`.id, `A`.name
					   FROM
						`pages` AS `A`
					WHERE
					`A`.id IN($_sql_in) AND `A`.alarm=1 AND `A`.pub=1 AND `A`.del=1");
	if(($rs) && ($rs->_numOfRows >= 1))
	{
		while(!$rs->EOF)
		{
				//$alarmModulesArr[] = 88;
				$rs->MoveNext();
		}
				
	}
}



$alarmModulesArr=array();


//echo $_SESSION['accesses']->_login['position'];

// --- end of alarm checks

$_IS_BOOTSTRAP_MIGRATION = false;
$_BODY_FIT_SCREEN = "";
if(isset($_GET['p']) && (in_array($_GET['p'],array("fleetmanager","accounting_serial_no_lifecycle","home_widgetx","productivity")))) // --- IF is BOOTSTRAP Migration
{
    $_IS_BOOTSTRAP_MIGRATION = true;
}
if(isset($_GET['g']) && (in_array($_GET['g'],array("dashboard","developers","productivity")))) // --- IF is BOOTSTRAP Migration
{
    $_IS_BOOTSTRAP_MIGRATION = true;
    $_BODY_FIT_SCREEN = "style='max-height:100%;'";
}

?>
<!-- Copyright. 2011 - 2015. -->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html style='overflow:scroll;'>
<head>
    <title>O-Framework</title>
    <meta charset="UTF-8">
	<? if(!$_IS_BOOTSTRAP_MIGRATION){ ?>
    <link rel="stylesheet" href="style/themes/jquery-ui.min.css">
	<? }else{ ?>
    <link href="style/css/bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet">
	<? } ?>
    <link rel="stylesheet" href="style/themes/jquery-ui.min.css">
    <script src="style/js/jquery.min.js"></script>
    <script src="style/themes/jquery-ui.js"></script>
    <link rel="stylesheet" href="style/css/styles.css" type="text/css">
	<? if(!$_IS_BOOTSTRAP_MIGRATION){ ?>
    <script type="text/javascript" src="style/js/js.js"></script>
	<? } ?>
</head>
<body <? echo $_BODY_FIT_SCREEN;?>>
    <? if(isset($_logged) && $_logged){ ?>
     <script type="text/javascript">
	/*var start;
	alert(1)
	$(document).ready(function(){
	  start = Date.getTime();
	alert(1);
	  $(window).unload(function() {
	      end = Date.getTime();alert(end - start);
	      /*$.ajax({ url: "log.php",data: {'timeSpent': end - start}})*/
	  //  });
	//}
    </script>
    <table id="wrapper" border="0" cellspacing="0" cellpadding="0" style="<? if($_IS_BOOTSTRAP_MIGRATION){echo "border:0px solid #f00;height:90vh;"; } ?>">
	<tr>
	    <td class='hdr'>
		<?
		    
		    $_HOME_PAGE = "home";
		    if(isset($_SESSION['accesses']->_login['position'][0])&& ($_SESSION['accesses']->_login['position'][0] == 41))
		    {
			$_HOME_PAGE = "home_widget";
			if($_GET['p'] == "home"){$_GET['p'] = $_HOME_PAGE;}
		    }
		    
		    if(isset($_SESSION['accesses']->_login['position'][1])&& (!empty($_SESSION['accesses']->_login['position'][1]))){
		    
		?>
		    <li><a href='#'>OVH Cloud-ERP: <span style='color:#007bff;'><? echo $_SESSION['accesses']->_login['position'][1]; ?>'s</span> <span style='color:#e61e2b;'>DASHBOARD</span>..!</a></li>
		    <li><a href="<? echo WWWROOT; ?>?g=dashboard&p=<? echo strtolower($_SESSION['accesses']->_login['position'][1]); ?>" id='a_dashboard'><img src='images/icons/house.png' /></a></li>
		    <li><a href="<? echo WWWROOT; ?>?g=productivity&p=productivity" id='a_productivity'><img src='images/icons/clock.png' /></a></li>
		<?
		
		
			$pos4 = strpos($_SESSION['accesses']->_login['type'][1], "HR MANAGER");
			if(($pos4 !== false)){ ?>
				    <li><a href="<? echo WWWROOT; ?>?g=hr_360&p=dashboard" id='a_legal'><img src='images/icons/user_business.png' /></a></li>
				<? }
			
		}else{ ?>
		    <li><a href='#'>OVH ERP: <span style='color:#e61e2b;'>DASHBOARD</span>..!</a></li>
		    <li><a href="<? echo WWWROOT; ?>?g=home&p=<? echo $_HOME_PAGE; ?>" id='a_home'><img src='images/icons/house.png' /></a></li>
		    <? 
		    if(isset($_SESSION['accesses']->_login['type'][1])&& (!empty($_SESSION['accesses']->_login['type'][1]))){
			
			$pos = strpos($_SESSION['accesses']->_login['type'][1], "FLEET ADMINISTRATOR");
			$pos1 = strpos($_SESSION['accesses']->_login['type'][1], "OPERATIONS ADMINISTRATOR");
			$pos2 = strpos($_SESSION['accesses']->_login['type'][1], "SUPPLY CHAIN ADMINISTRATOR");
			$pos4 = strpos($_SESSION['accesses']->_login['type'][1], "HR MANAGER");
			$pos5 = strpos($_SESSION['accesses']->_login['type'][1], "SCHOOL ADMINISTRATOR");
			$pos6 = strpos($_SESSION['accesses']->_login['type'][1], "SCHOOL TEACHER"); 
			$pos7 = strpos($_SESSION['accesses']->_login['type'][1], "CREDIT CONTROLLER"); // DEBTORS MANAGER
			$pos8 = strpos($_SESSION['accesses']->_login['type'][1], "IOT SAAS ADMINISTRATOR"); // DEBTORS MANAGER
			$pos9 = strpos($_SESSION['accesses']->_login['type'][1], "INVENTORY MANAGER"); // INVENTORY MANAGER
			$pos10 = strpos($_SESSION['accesses']->_login['type'][1], "EVENT MANAGER"); // INVENTORY MANAGER
			
			if(($pos !== false) || ($pos1 !== false)){
		    ?>
			    <li><a href="<? echo WWWROOT; ?>?g=fleetmanager&p=fleetmanager" id='a_fleetmanager'><img src='images/icons/map_icon-48.png' /></a></li>
			<? }else if(($pos2 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=legal&p=legal" id='a_legal'><img src='images/icons/legalx16.png' /></a></li>
			<? }else if(($pos4 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=hr_360&p=dashboard" id='a_legal'><img src='images/icons/user_business.png' /></a></li>
			<? }else if(($pos5 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=schools&p=dashboard" id='a_legal'><img src='images/icons/if_graduation-hat_16.png' /></a></li>
			<? }else if(($pos6 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=schools&p=learners" id='a_legal'><img src='images/icons/if_graduation-hat_16.png' /></a></li>
			<? }else if(($pos7 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=accounting&p=dashboard" id='a_legal'><img src='images/icons/accounting-16.png' /></a></li>
			<? }else if(($pos8 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=iot_saas&p=subscribers" id='a_legal'><img src='images/icons/ovh-switch.png' /></a></li>
			<? }else if(($pos9 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=reports&p=reports" id='a_reports'><img class='degree-anti' src='images/icons/report-x.png' /></a></li>
			    <li><a href="<? echo WWWROOT; ?>?g=accounting_360&p=dashboard" id='a_accounting_360'><img class='xdegree-anti' src='images/icons/accounting-16.png' /></a></li>
			<? }else if(($pos10 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=event_360&p=dashboard" id='a_accounting_360'><img class='xdegree-anti' src='images/icons/user_business.png' /></a></li>
			<? } ?>
		    <? } ?>
		<? } ?>
		
		<? //echo $_SESSION['accesses']->_login['position'][0]; ?>
		
		<? if(!(isset($_SESSION['accesses']->_login['position'][0])&& ($_SESSION['accesses']->_login['position'][0] == 41))){ // -- If user is workforce ?>
		    
			<!--<li><a href="<? echo WWWROOT; ?>?g=businessmodules&p=businessmodules" id='a_businessmodules'><img src='images/icons/briefcase.png' /></a></li>-->
		    <? if(isset($alarmModulesArr) && (sizeof($alarmModulesArr) > 0)){ ?>
			<li><a href="<? echo WWWROOT; ?>?g=dashboard&p=dashboard" id='a_dashboard'><img class='degree-antix' src='images/icons/blinking-img-big-faster.gif' style='width:16px;' /></a></li> <!-- shield_green_24.png -->
		    <? } ?>
			
			<!--<li><a href="<? echo WWWROOT; ?>?g=reports&p=reports" id='a_reports'><img class='degree-anti' src='images/icons/report-x.png' /></a></li>-->
			<!--<li><a href="<? echo WWWROOT; ?>?g=developers&p=developers" id='a_help'><img src='images/icons/question_motivational.png' /></a></li>-->
			<? if(isset($_SESSION['accesses']->_login['type'][1])&& (!empty($_SESSION['accesses']->_login['type'][1]))){ ?><li><!-- <a href="<? echo WWWROOT; ?>?g=archives&p=archives" id='a_help'><img src='images/icons/shield_green_16.png' style='border:0px solid #aaa;-moz-border-radius:4px;border-radius:4px;-webkit-border-radius:4px;' /></a></li>--><? } ?>
			
		<? } ?>	
			
			<? $pos3 = strpos($_SESSION['accesses']->_login['type'][1], "SUPER ADMIN");
			if(($pos3 !== false)){ ?>
			    <li><a href="<? echo WWWROOT; ?>?g=legal&p=legal" id='a_legal'><img src='images/icons/legalx16.png' /></a></li>
			<? } ?>
			
			<li><a href="<? echo WWWROOT; ?>?logout=x-)1_d&auth_20_mnt" id='a_logout'><img src='images/icons/button-red.png' /></a></li>
			<li class='right'>
			    <a href="<? echo WWWROOT; ?>?g=home&p=profile" id='a_profile'>
				    <? echo ucfirst(strtolower($_SESSION['accesses']->_login['cname'])); ?>
				    <span style='color:#555;'><? echo (isset($_SESSION['accesses']->_login['position'][4])&& (!empty($_SESSION['accesses']->_login['position'][4])))? $_SESSION['accesses']->_login['position'][4] : "WF"; ?></span>
				    <span><? echo (isset($_SESSION['accesses']->_login['type'][1])&& (!empty($_SESSION['accesses']->_login['type'][1])))? $_SESSION['accesses']->_login['type'][1] : "N/A"; ?></span>
			    </a>
			</li>
			
	    </td>
	</tr>
	<? if($_IS_BOOTSTRAP_MIGRATION){ ?>
	<tr>
	    <td valign='top'>
	    <?  
		if(isset($_GET['g']) && (!empty($_GET['g'])) && (file_exists("pages/".$_GET['g'].".php")))
		{
		    $_SESSION['accesses']->_last_activities['uri'] = $_SERVER['REQUEST_URI'];
		    include_once("pages/".$_GET['g'].".php");
		}
		else{echo "<blockquote class='cancel'>Sorry, page not found.</blockquote>";}
	    ?>
	    </td>
	</tr>
	<? }else{ ?>
	<tr>
	    <td valign='top' class='body' style='overflow:hidden;'>
	    <? 
		if(isset($_GET['g']) && (!empty($_GET['g'])) && (file_exists("pages/".$_GET['g'].".php")))
		{
		    $_SESSION['accesses']->_last_activities['uri'] = $_SERVER['REQUEST_URI'];
		    include_once("pages/".$_GET['g'].".php");
		}
		else{echo "<blockquote class='cancel'>Sorry, page not found...</blockquote>";}
	    ?>
	    </td>
	</tr>
	<? } ?>
    </table>
   
    <? }else{
	if(isset($_GET['p']) && (!empty($_GET['p'])) && (file_exists("pages/".$_GET['p'].".php")) && ($_GET['p']=="login"))
	{include_once("pages/".$_GET['p'].".php");}
	else if(isset($_GET['g']) && (!empty($_GET['g'])) && (file_exists("pages/".$_GET['g'].".php")))
		{
		    include_once("pages/".$_GET['g'].".php");
		}
	else{echo "<blockquote class='cancel'>Sorry, page not found..</blockquote>";}
	?>
	<div id="footer">
	    <a href="<? echo WWWROOT; ?>?p=tou">Terms of Use</a> | <a href="<? echo WWWROOT; ?>?p=tnc">Terms &amp; Conditions</a>
	    <br />
	    Copyrights (c) 2026, <u>OVH ERP v3.5</u>.
	</div>
	<?
    }?>
</body>
</html>
