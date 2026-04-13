<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getCustom404Page($_url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$_url."/testingmeofhelloworlsting/usuallydoesnotreturn/anythingotherthanerrors");
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$is404 = ($http_code == 404) ? 1 : 0;
	//$is404x = curl_getinfo($ch);print_r($is404x);
	curl_close($ch);
	
	return array($is404,$http_code);
}

$title = "";
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	This is your "<strong>first impression</strong>", the first thing your visitors will see.<br /><br />
	Your domain name is a branding opportunity! The right domain name can increase brand recognition.<br /><br />
	<strong>NB: Make sure it is Easy-To-Type</strong> i.e. Google, Facebook, Twitter, Instagram, Yahoo, CNN. One big thing these domain names have in common is that they\'re all easy to spell.<br /><br />
	A good domain name should always be between 6 to 14 characters. Remember, the shorter, the better.<br /><br />
	Lastly, make sure it\'s <strong>easy to pronounce</strong> and avoid hyphens and numbers.
';
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"subject"=>"","notice"=>$_notice);

list($title,$http_code) = getCustom404Page($url);
//echo $title;
if(isset($title) && ($title))
{
	$_contentResults = "Returned HTTP code: <strong style='color:#dc4245;'>".$http_code."</strong>";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus,"subject"=>$_contentResults,"notice"=>$_notice);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
}
else
{
	$_contentResults = "Returned HTTP code: <strong style='color:#4fa747;'>".$http_code."</strong>";
	$_ruleStatus = "success";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"subject"=>$_contentResults,"notice"=>$_notice);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>