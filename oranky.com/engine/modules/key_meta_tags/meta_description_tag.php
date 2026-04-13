<?
/*
	RULE - META DESCRIPTION TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getDescription($_url)
{
    $tags = @get_meta_tags($_url);
    return @($tags['description'] ? $tags['description'] : "");
}

$title = "";
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	<a target="_blank" href="https://www.w3schools.com/tags/tag_meta.asp">Meta tag</a> define a description of your web page. These are keywords are very important because they appear in bold when they match the user\'s search query in most search engines.<br /><br />
	<a target="_blank" href="https://www.w3schools.com/tags/tag_meta.asp">Meta descriptions</a>, however, allow you to influence how your web pages are described and displayed in search results. A good description acts as a potential organic marketing tool for your initiatives, products, and services and encourages the viewer to click through to your site.<br /><br />
	Make sure your meta description tags are clear, the appropriate description should be between 65 and 160 characters spaces included and should include your most important keywords related to your organization\'s initiatives, products or and services.<br /><br />
	<strong>NB</strong>: Make sure that each of your web pages have a unique meta tag description that is explicit and contains your most important keywords for each page best describing your initiatives, products or and services.
';
$_noticex =
'
	define a description of your web page. These are keywords are very important because they appear in bold when they match the user\'s search query in most search engines.<br /><br />
	, however, allow you to influence how your web pages are described and displayed in search results. A good description acts as a potential organic marketing tool for your initiatives, products, and services and encourages the viewer to click through to your site.<br /><br />
	Make sure your meta description tags are clear, the appropriate description should be between 65 and 160 characters spaces included and should include your most important keywords related to your organization\'s initiatives, products or and services.<br /><br />
	<strong>NB</strong>: Make sure that each of your web pages have a unique meta tag description that is explicit and contains your most important keywords for each page best describing your initiatives, products or and services.
';
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"subject"=>"","notice"=>$_notice);

$title = getDescription($url);

if(isset($title) && (!empty($title)))
{
	$_contentResults = $title;
	
	$strlen = strlen(trim($title));
	
	if(($strlen < 65) || ($strlen > 170))
	{
		$_ruleStatus = "warning";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>1,"status"=>$_ruleStatus,"notice"=>$_notice);
	}
	else
	{
		$_ruleStatus = "success";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"notice"=>$_notice);
	}
	
	$_contentResults .=
		'
			<br /><br /><strong>Length</strong>: '.$strlen.' character(s)
		';
		
	//$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
	$_MATRIX_RESULTS[$_ruleNo]["subject"] = $_contentResults;
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
	//$_MATRIX_RESULTS[$_ruleNo]["notice"] = $_notice;
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
else
{
	$_contentResults="Missing ".$title;
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus,"subject"=>$_contentResults,"notice"=>$_notice);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>