<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getTitle($_url)
{
	global $_STOP_WORDS, $_STOP_WORDS_USEFUL, $_KEY_PHRASES;
	
	$_STOP_WORDS = array_merge($_STOP_WORDS,$_STOP_WORDS_USEFUL);
	
	$page = @file_get_contents($_url);
	$page = strip_tags($page, '<title>');
	$title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? htmlspecialchars_decode(html_entity_decode($match[1])) : "";
	
	$_KEY_PHRASES[] = stopWordsNLP($title, $_STOP_WORDS);
	
	return trim(html_entity_decode($title,ENT_QUOTES,"UTF-8"));
}

$title = "";
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	Your HTML <a target="_blank" href="https://www.w3schools.com/tags/tag_title.asp">title tag</a> appears in browser tabs, bookmarks and in search result pages.<br /><br />
	Make sure your title tags are clear, around 65 characters and include your most important keywords related to your organization\'s initiatice, products or and service.<br /><br />
	Many SEOs and SEO websites recommend a title tag length of approximately 50 to 70 characters simply because that\'s what Google shows in the SERPs.
';
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"subject"=>"","notice"=>$_notice);

$title = getTitle($url);

if(isset($title) && (!empty($title)))
{
	$_contentResults = $title;
	
	$strlen = strlen(trim($title));
	
	if($strlen >= 65)
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