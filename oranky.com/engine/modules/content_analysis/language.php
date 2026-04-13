<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

$_HTML_LANGUAGE_CODES = array
(
	'en'=>"English",'fr'=>"French",'it'=>"Italian",'ja'=>"Japanese",
	'id'=>"Indonesian",'in'=>"Indonesian",'ko'=>"Korean",'pt'=>"Portuguese",
	'ru'=>"Russian",'ro'=>"Romanian",'af'=>"Afrikaans",'ar'=>"Arabic",
	'de'=>"German",'le'=>"Greek",'he'=>"Hebrew",
	'zh'=>"Chinese",'zh-Hant'=>"Chinese (Traditional)",'zh-Hans'=>"Chinese (Simplified)",
	'nl'=>"Dutch"
);

function getLanguage($_url)
{
	$lang = $lang_html = '';
	global $_HTML_LANGUAGE_CODES;
	$doc = new DOMDocument;
	
	if(!@$doc->loadHTMLFile($_url))
	{}
	else
	{
		$xml = simplexml_import_dom($doc);
		
		$arr = $xml->xpath('//html[@lang]');
		$lang_html = $arr[0]['lang'];
		
		if(isset($lang_html) && (!empty($lang_html)))
		{
			$arr = explode('-',$lang_html);
			$lang = $arr[0];
			$lang = (isset($_HTML_LANGUAGE_CODES[$lang])) ? $_HTML_LANGUAGE_CODES[$lang] : $lang;
		}
	}
	
	return array($lang,$lang_html);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	You should always use a language attribute on the html tag to declare the default language of the text in the page. This is inherited by all other elements.<br /><br />
	This is meant to assist search engines and browsers.<br /><br />
	<strong>ISO Language Codes</strong>: in both HTML and XHTML, attributes utilize ISO language codes to instruct search engines about the current language of your site i.e. <a target="_blank" href="https://www.w3schools.com/tags/ref_language_codes.asp">ISO 639-1 defines abbreviations for languages</a>.<br /><br />
	<a target="_blank" href="https://www.w3schools.com/tags/ref_country_codes.asp"><strong>ISO Country Codes</strong></a>: In HTML, country codes can be used as an addition to the language code in the lang attribute..<br /><br />
';

list($title,$title_html) = getLanguage($url);

if(isset($title) && (!empty($title)))
{
	$_contentResults .= 'This is great, there is language that has been declared namely: <strong style="color:#4fa747;">'.$title.'</strong>.';
	
	$_contentResults .= '<br/><br />Actual HTML language attribute: <strong>'.$title_html.'</strong>.';
	
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