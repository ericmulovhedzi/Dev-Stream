<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getURL($_url)
{
	/*
		The URL to parse.
		
		Component
		--
		Specify one of PHP_URL_SCHEME, PHP_URL_HOST, PHP_URL_PORT, PHP_URL_USER, PHP_URL_PASS, PHP_URL_PATH, PHP_URL_QUERY or PHP_URL_FRAGMENT to retrieve just a specific URL component as a string.
	*/
	
	$_url = urldecode($_url); // --- Step 1: URL decode it first, just incase.
	
	//$_url = str_replace("www.", "", $_url); // --- Step 2: Replace any 'www.' string.
	
	$parse = parse_url($_url,PHP_URL_HOST); /// --- Step 3: parse the actual URL
	
	$_url = preg_replace('/^www\./', '', $parse); // --- Step 2: Replace any 'www.' string.
	
	$domain_name = explode('.',$_url);
	
	return array(trim($domain_name[0]),$parse);
}

function getSubdomainHost($_url)
{
	$str = "";
	$arr = explode(".", $_url);
	
	if(isset($arr) && (sizeof($arr) > 0))
	{
		$arr = array_slice($arr,1);
		if(isset($arr) && (sizeof($arr) > 0))
		{
			$str = implode($arr,".");
		}
	}
	
	return $str;
}

$title = "";
$_ruleStatus = "danger";
$_contentResults ='';
$_notice =
'
	This is your "<strong>first impression</strong>", the first thing your visitors will see.<br /><br />
	Your domain name is a branding opportunity! The right domain name can increase brand recognition.<br /><br />
	<strong>NB: Make sure it is Easy-To-Type</strong> i.e. Google, Facebook, Twitter, Instagram, Yahoo, CNN. One big thing these domain names have in common is that they\'re all easy to spell.<br /><br />
	A good domain name should always be between 6 to 14 characters. Remember, the shorter, the better.<br /><br />
	Lastly, make sure it\'s <strong>easy to pronounce</strong> and avoid hyphens and numbers.
';
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"subject"=>"","notice"=>$_notice);

list($title,$domainname) = getURL($url);

if(isset($title) && (!empty($title)))
{
	$_contentResults = $domainname;
	
	$strlen = strlen(trim($title));
	
	if(($strlen < 6) || ($strlen > 14))
	{
		$_ruleStatus = "informational";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"notice"=>$_notice);
	}
	else
	{
		$_ruleStatus = "informational";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"notice"=>$_notice);
	}
	
	$_contentResults .=
		'
			<br /><br /><strong>Length</strong>: '.$strlen.' character(s).
		';
		
	$_contentResults_PDF = "Length: '.$strlen.' character(s).";
	$_MATRIX_RESULTS[$_ruleNo]["subject"] = $_contentResults;
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
		
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
else
{
	$_contentResults="Missing ".$title;
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus,"subject"=>$_contentResults,"notice"=>$_notice);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>