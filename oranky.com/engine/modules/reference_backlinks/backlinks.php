<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getBacklinks($_url)
{
	list($novalue,$_url) = getURL($_url);//echo $_url;
	/*
	$url="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=link:".$_url."&filter=0";
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt ($ch, CURLOPT_NOBODY, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$json = curl_exec($ch);print_r($json);
	curl_close($ch);
	$data=json_decode($json,true);
	if($data['responseStatus']==200)
	return $data['responseData']['cursor']['resultCount'];
	else
	return false;*/
	
	//$site = fopen('http://search.live.com/results.aspx?q=link%3A'.urlencode($_url),'r');
	$site = fopen('http://www.google.com/search?q='.urlencode($_url),'r'); 
	while($cont = fread($site,1024657)){ 
	    $total .= $cont; 
	} 
	fclose($site); 
	$match_expression = '/<h5>Page 1 of (.*) results<\/h5>/Us'; 
	preg_match($match_expression,$total,$matches);print_r($matches);print_r($total);
	//return $matches[1]; 
	
	//return array($alexa['globalRank'],$alexa['CountryRank'],$_url);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "informational";
$_contentResults='';

$title = getBacklinks($url);

$_notice =
	'
		<strong>Disclaimer</strong>: The Oranky technology uses <a target="_blank" href="https://www.alexa.com/siteinfo/'.$__url.'">Alexa</a>\'s <a target="_blank" href="https://www.w3schools.com/ai/">artificial intelligence</a> and <a target="_blank" href="https://www.w3schools.com/python/python_ml_getting_started.asp">machine learning</a> technoligies for this information.<br /><br />
		<strong>NB</strong>: A low rank means that your website gets a lot of visitors.<br /><br />
		<strong>Info</strong>: Review the <a target="_blank" href="https://www.alexa.com/topsites/countries/ZA">most visited websites</a> in your country.<br /><br />
		Most traffic rank estimates are for domains only (e.g., domain.com). They do not provide separate rankings for subpages within a domain (e.g., http://www.domain.co.za/ourproducts.html ) or subdomains i.e. subdomain.domain.co.za. For example,  <a target="_blank" href="https://support.alexa.com/hc/en-us/articles/200449744-How-are-Alexa-s-traffic-rankings-determined-">how are Alexa\'s traffic rankings determined</a>?
	';

if(isset($title) && (!empty($title)))
{
	
	
	$title = @number_format(trim($title));
	/*
	$_contentResults = "You site is ranked: <span class='text-primary' style='font-size:110%;'># ".$title."</span> globaly.<br /><i>In global internet traffic and engagement over the past 90 days.</i>";
	
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
		
	if($title1 <= 0)
	{
		$_contentResults .=
			'
				<br /><br />Your domestic or local rank is very low.
			';
	}
	else
	{
		$_contentResults .=
			'
				<br /><br />You are locally ranked: <strong># ".$title."</strong>
			';
	}
	
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);*/
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
}
else
{
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$_contentResults = 'Your website  has not been indexed yet nor ranked anywhere globally or locally.<br /><br />Please, try to register with either <a target="_blank" href="https://analytics.google.com/analytics/web/">Google Analytics</a> or <a target="_blank" href="https://www.alexa.com/">Alexa Traffic Rank</a> estimates.';
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>