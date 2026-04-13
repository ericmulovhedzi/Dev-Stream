<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getDoctype($_url)
{
	$doesExist = false;$htmlDocTypeVersion = "OLDER";$htmlTag = "";
	
	$page = @file_get_contents($_url);
	$pos = strpos($page,'<!DOCTYPE');
	if($pos === false)
	{
		$pos = strpos($page,'<!doctype');if($pos === false)
		{
			$pos = strpos($page,'<!DocType');if($pos === false)
			{
				$pos = strpos($page,'<!Doctype');if($pos === false)
				{
					$doesExist = false;
				}else{$doesExist = true;$pos_dtv = strpos($page,'<!Doctype html>');if(!($pos_dtv === false)){$htmlDocTypeVersion = "HTML5";$htmlTag = '&lt;!Doctype html&gt;';}}
			}else{$doesExist = true;$pos_dtv = strpos($page,'<!DocType html>');if(!($pos_dtv === false)){$htmlDocTypeVersion = "HTML5";$htmlTag = '&lt;!DocType html&gt;';}}
		}else{$doesExist = true;$pos_dtv = strpos($page,'<!doctype html>');if(!($pos_dtv === false)){$htmlDocTypeVersion = "HTML5";$htmlTag = '&lt;!doctype html&gt;';}}
	}else{$doesExist = true;$pos_dtv = strpos($page,'<!DOCTYPE html>');if(!($pos_dtv === false)){$htmlDocTypeVersion = "HTML5";$htmlTag = '&lt;!DOCTYPE html&gt;';}}
	
	return array($doesExist,$htmlDocTypeVersion,$htmlTag);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	This is meant to instruct browsers about the docyment type being used as well as the version of HTML the page is written in.<br /><br />
	All HTML documents must always start with a <a target="_blank" href="https://www.w3schools.com/tags/ref_country_codes.asp"><code>&lt;!DOCTYPE&gt;</code></a> declaration.<br /><br />
	<strong>NB</strong>: Note that in older documents (HTML 4 or XHTML), the declaration was and is more complicated because the declaration always have to refer to a DTD (Document Type Definition), but, in HTML 5, however, the declaration is very simple and is as shown above.
';

list($title,$title_dtv,$title_html) = getDoctype($url);

if(isset($title) && ($title))
{
	$_contentResults .= 'This is great, document type has been declared: <strong style="color:#4fa747;">'.$title_dtv.'</strong>.<br /><br />';
	$_contentResults .= 'Doctype: <code>'.$title_html.'</code>';
	
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