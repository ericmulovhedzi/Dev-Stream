<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

$_DNSRECORDS_TTL = array
(
	300 => array('max_secs'=>3600,'time_desc'=>'5 minutes','speed'=>'Very Short','description'=>'TTL Time for DNS Records: <strong>Very Short</strong>, Websites within this timeframe use a low TTL focus to make fast changes but still can utilize some level of caching to help reduce resource consumption.'),
	3600 => array('max_secs'=>86400,'time_desc'=>'1 hour','speed'=>'Short','description'=>'TTL Time for DNS Records: <strong>Short</strong>, Websites within this timeframe use a low TTL focus to make fast changes but still can utilize some level of caching to help reduce resource consumption.'),
	86400 => array('max_secs'=>604800 ,'time_desc'=>'24 hours','speed'=>'Long','description'=>'TTL Time for DNS Records: <strong>Long</strong>, The opposite applies for websites using a 24 hour TTL as the focus shifts more towards a daily cache utilization.'),
	604800 => array('max_secs'=>1000000000000000,'time_desc'=>'7 days','speed'=>'Very Long','description'=>'TTL Time for DNS Records: <strong>Very Long</strong>, Weekly TTLs are not as common, but may be used for sites that contain publish or reputable information that does not change all that often.')
);

function getServerIPAddress($_url)
{
	$upOrExist = true;$ip = '';$ipTTL = '';
	global $_DNSRECORDS_TTL;
	
	list($novalue,$_url) = getURL($_url);
	
	$ip = gethostbyname($_url);
	//echo $ip.' - '.$_url;
	if($ip == $_url){$upOrExist = false;}
	else
	{
		$dnsRecords = dns_get_record($_url);
		
		if(isset($dnsRecords) && is_array($dnsRecords) && isset($dnsRecords[0]) && isset($dnsRecords[0]['ttl']) && ($dnsRecords[0]['ttl'] >=0))
		{
			$ipTTL = $dnsRecords[0]['ttl'];
			
			if(($ipTTL >= 0) && ($ipTTL < 3600))
			{
				$ipTTL_val = $_DNSRECORDS_TTL[300]['description'];
			}
			else if(($ipTTL >= 3600) && ($ipTTL < 86400))
			{
				$ipTTL_val = $_DNSRECORDS_TTL[3600]['description'];
			}
			else if(($ipTTL >= 86400) && ($ipTTL < 604800))
			{
				$ipTTL_val = $_DNSRECORDS_TTL[86400]['description'];
			}
			else if(($ipTTL >= 604800))
			{
				$ipTTL_val = $_DNSRECORDS_TTL[604800]['description'];
			}
		}
	}
	
	return array($upOrExist,$ip,$ipTTL,$ipTTL_val,$_url);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	IP addresses are used by search engines to produce geographically tailored results for your site\'s intended audience or visitors.<br /><br />
	Recomendation: always host your website on a server which is geographically close to your targeted audience or visitors.<br /><br />
	You need to look into more server monitoring resources fo in-depth understanding on how to monitor your server and entire infrastructure for critical performance metrics and stay on top of your data center resources. 
	This would also help you get in-depth visibility into key performance indicators of application servers, mail servers, web servers, virtual servers, database servers, and more to eliminate outages and server performance issues.<br /><br />
	<strong>What is DNS TTL:</strong><br /><br />
	DNS TTL (time to live) is a setting that tells the DNS resolver how long to cache a query before requesting a new one. The information gathered is then stored in the cache of the recursive or local resolver for the TTL before it reaches back out to collect new, updated details.<br /><br />
	Typical TTL times for DNS records:<br /><br />
	<strong>(a) 300 seconds = 5 minutes = "Very Short"</strong><br /><i>Websites within this timeframe use a low TTL focus to make fast changes but still can utilize some level of caching to help reduce resource consumption.</i><br />
	<strong>(b) 3600 seconds = 1 hour = "Short"</strong><br /><i>Websites within this timeframe use a low TTL focus to make fast changes but still can utilize some level of caching to help reduce resource consumption.</i><br />
	<strong>(c) 86400 seconds = 24 hours = "Long"</strong><br /><i>The opposite applies for websites using a 24 hour TTL as the focus shifts more towards a daily cache utilization.</i><br />
	<strong>(d) 604800 seconds = 7 days = "Very long"</strong><br /><i>Weekly TTLs are not as common, but may be used for sites that contain publish or reputable information that does not change all that often.</i>
';

list($title,$title_html,$title_ttl,$title_ttl_desc,$_url) = getServerIPAddress($url);

if(isset($title) && ($title))
{
	$_contentResults .= '<strong><code>'.$title_html.'</code></strong><br /><br />';
	$_contentResults .= 'Domain name: '.$_url.'<br /><br />';
	$_contentResults .= 'TTL: <strong>'.$title_ttl.'</strong> seconds / '.(ceil($title_ttl/60)).' minutes / '.(round($title_ttl/(60*60),2)).' hours<br /><br />';
	$_contentResults .= $title_ttl_desc;
	
	$_ruleStatus = "success";
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