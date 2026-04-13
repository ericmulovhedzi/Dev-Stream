<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS,$_SSL_CERTIFICATE_SECURITY;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getServerUptime($_url)
{
	global $_SSL_CERTIFICATE_SECURITY;
	
	$upOrExist = false;$responseTime = $namelookupTime = 0;$ipTTL = '';
	
	list($novalue,$_url) = getURL($_url);
	
	$ch = curl_init($_url);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	if(curl_exec($ch))
	{
		$info = curl_getinfo($ch);
		$responseTime = $info['total_time'];
		$namelookupTime = $info['namelookup_time'];
		$_SSL_CERTIFICATE_SECURITY['redirect_url'] = $redirectURL = $info['redirect_url'];
		
		$upOrExist = true;
	}
	
	curl_close($ch);
	
	return array($upOrExist,$responseTime,$namelookupTime,$redirectURL,$_url);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	IP addresses are used by search engines to produce geographically tailored results for your site\'s intended audience or visitors.<br /><br />
	
';

list($title,$title_response_time,$title_namelookup_time,$redirectURL,$_url) = getServerUptime($url);

if(isset($title) && ($title))
{
	$_contentResults .= '
	<div class="col-md-6" style="border:0px solid #eee;float:left;">
		<h6 style="padding-top:2%;color:#555;text:right;">SLA monthly uptime: 30-day period</h6>
		<p style="padding-top:7.5%;padding-bottom:0%;margin-bottom:2%;"><span style="font-size:350%;text-decoration:line-through;">99.99</span>&nbsp;&nbsp;<span style="font-size:150%;">%</span></p>
		<p style="padding-top:0%;padding-bottom:0%;margin-top:0%;text-decoration:line-through;"><span>Total checks: 3500</span></p>
	</div>
	<div class="col-md-6" style="border-left:1px solid #eee;float:left;">
		<h6 style="padding-top:2%;color:#555;text:right;">Response time</h6>
		<p style="padding-top:7.5%;padding-bottom:0%;margin-bottom:2%;"><span style="font-size:350%;">'.round($title_response_time,4).'</span>&nbsp;&nbsp;<span style="font-size:150%;">ms</span></p>
		<p style="padding-top:0%;padding-bottom:0%;margin-top:0%;"><span>Name lookup time: '.$title_namelookup_time.'&nbsp;ms</span></p>
	</div>';
	
	$_ruleStatus = "informational";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;

	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
else
{
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$_contentResults = "Missing";
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>