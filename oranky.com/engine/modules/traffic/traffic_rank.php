<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getTrafficRank($_url)
{
	list($novalue,$_url) = getURL($_url);//echo $_url;
	
	$alexaData = simplexml_load_file("http://data.alexa.com/data?cli=10&url=".$_url);
	$alexa['globalRank'] =  isset($alexaData->SD->POPULARITY) ? $alexaData->SD->POPULARITY->attributes()->TEXT : 0 ;
	$alexa['CountryRank'] =  isset($alexaData->SD->COUNTRY) ? $alexaData->SD->COUNTRY->attributes() : 0 ;
	
	return array($alexa['globalRank'],$alexa['CountryRank'],$_url);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "informational";
$_contentResults='';

list($title,$title1,$__url) = getTrafficRank($url);

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
	
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
else
{
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1);
	$_contentResults = 'Your website  has not been indexed yet nor ranked anywhere globally or locally.<br /><br />Please, try to register with either <a target="_blank" href="https://analytics.google.com/analytics/web/">Google Analytics</a> or <a target="_blank" href="https://www.alexa.com/">Alexa Traffic Rank</a> estimates.';
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>