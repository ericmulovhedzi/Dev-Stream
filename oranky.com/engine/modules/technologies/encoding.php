<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getEncoding($_url)
{
	$encoding = false;$encoding_val = '';
	global $_HTML_LANGUAGE_CODES;
	$doc = new DOMDocument;
	
	if(!@$doc->loadHTMLFile($_url))
	{}
	else
	{
		$xml = simplexml_import_dom($doc);
		
		$arr = $xml->xpath('//meta[@charset]');
		$encoding_val = $arr[0]['charset'];
		
		if(isset($encoding_val) && (!empty($encoding_val)))
		{
			$encoding = true;
		}
	}
	
	return array($encoding,$encoding_val);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	The <code>character set</code> or <code>charset</code> encoding specifies the character encoding for the HTML document.<br /><br />
	Browsers will always encode input, according to the character-set used in your page and, remember, character encoding can also prevent problems with your page rendering of <a target="_blank" href="https://www.w3schools.com/html/html_charset.asp">special characters</a>.<br /><br />
	Example of HTML 5 declaration: <code>&lt;meta charset="UTF-8" /&gt;</code>.<br />
	Example of an Old HTML declaration: <code>&lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8" /&gt;</code>.<br /><br />
	Please, see the <a target="_blank" href="https://www.w3schools.com/tags/ref_urlencode.asp">ASCII Encoding Reference</a>.<br /><br />
	Some more character set encoding related references:<br />
	<a target="_blank" href="https://www.w3schools.com/html/html_charset.asp">HTML Chasets</a> | <a target="_blank" href="https://www.w3schools.com/html/html_entities.asp">HTML Entities</a> | <a target="_blank" href="https://www.w3schools.com/html/html_symbols.asp">HTML Symbols</a> | <a target="_blank" href="https://www.w3schools.com/html/html_urlencode.asp">HTML URL Encode</a> | <a target="_blank" href="https://www.w3schools.com/html/html_emojis.asp">Emojis in HTML</a>
';

list($title,$title_html) = getEncoding($url);

if(isset($title) && ($title))
{
	$_contentResults .= 'Great, document type has been declared: <strong style="color:#4fa747;">'.$title_html.'</strong>.<br /><br />';
	$_contentResults .= 'Declared charset: <code>'.$title_html.'</code>';
	
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