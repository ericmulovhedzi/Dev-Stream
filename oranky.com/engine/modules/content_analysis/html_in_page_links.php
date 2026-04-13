<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS,$_SSL_CERTIFICATE_SECURITY;
global $_ruleNo, $_ruleName, $_subSection, $url;

$_HTML_HEADING_TAGS = array("h1","h2","h3","h4","h5","h6");

function getHTMLInPageLinks($_url)
{
	global $_IN_PAGE_TAGS;
	$hasHTMLURLs = false;$htmlInPageLinksArr = array();
	
	if(isset($_IN_PAGE_TAGS) && (is_array($_IN_PAGE_TAGS)) && (sizeof($_IN_PAGE_TAGS) > 0))
	{
		$htmlInPageLinksArr = $_IN_PAGE_TAGS;
		$hasHTMLURLs = true;
	}
	//print_r($htmlInPageLinksArr);
	return array($hasHTMLURLs,$htmlInPageLinksArr);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	<strong>What is an Absolute URL?</strong><br /><br />
	This is a full address of the website including every sub link folder you need to take to get to the page.<br /><br />
	<strong>What is a Relative URL?</strong><br /><br />
	A relative URL on the other hand, is an address that is relative to where you are or a sub folder containing the sub page.<br /><br />
	For example, if an URL was an actual street address, an <strong>absolute URL</strong> would be the actual full address: <code>RSA, Gauteng, Johannesburg, Midrand, No. 1 Asparagus Road</code>, a <strong>relative URL</strong>, on the other hand, would just be <code>No. 1 Asparagus Road</code>.<br /><br />
	<strong>Why you should always use absolute URL:</strong><br /><br />
	Simply, for your site to ramain within <a target="_blank" href="https://developers.google.com/search/blog/2017/01/what-crawl-budget-means-for-googlebot">Crawl Budget</a>.<br /><br />
	Cral budget is, basically, is a finite number of URLs on a site set by search engine crawlers including <a target="_blank" href="https://www.google.com/">Google</a>.<br /><br />
	This budget is based on a number of things and this include but not limited to: your site authority, your actual site Pagerank, the complexity of your site, and how often your site gets updated.<br /><br />
	If your site is not <code>search engine friendly</code>, search engines will spend less time crawling it and do it less often.<br /><br />
	So, having multiple or no relative links will impact on how deep, extensive, and how often search engines crawl your site, and therefore, having an impact on your <a target="_blank" href="https://simple.wikipedia.org/wiki/Search_engine_optimization">SEO</a>.<br /><br />
	<strong>Three types of Relative URLs:</strong><br /><br />
		&nbsp;&nbsp;- <code>&lt;a href="//example.com"&gt;<span class="text-secondary">Scheme-relative URL</span>&lt;/a&gt;</code><br />
		&nbsp;&nbsp;- <code>&lt;a href="/en-US/docs/Web/HTML"&gt;<span class="text-secondary">Origin-relative URL</span>&lt;/a&gt;</code><br />
		&nbsp;&nbsp;- <code>&lt;a href="./p"&gt;<span class="text-secondary">Directory-relative URL</span>&lt;/a&gt;</code><br /><br />
		
	Reference: <a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a">https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a</a>
';

list($title,$_htmlInPageLinksArr) = getHTMLInPageLinks($url);

if(isset($title) && ($title))
{
	$totalLinks = (isset($_htmlInPageLinksArr) && (is_array($_htmlInPageLinksArr)) && (sizeof($_htmlInPageLinksArr) > 0)) ? sizeof($_htmlInPageLinksArr) : 0;
	
	$_contentResults = 'We discovered a total of <strong>'.$totalLinks.'</strong> links.';
	
	$_contentResults .= '
	<div class="col-md-12 img-thumbnail" style="margin-top:2.5%;float:left;padding:1%;border:1px solid #fff;padding:0%;">
				<table style="margin:0%;width:100%;borderx:1px solid #ddd;" class="table-striped">
				<thead style="font-weight:bold;"><tr style="line-height:19px;"><td>Index</td><td>Anchor</td><td>Type</td></tr></thead>';
				
	if(isset($_htmlInPageLinksArr) && (is_array($_htmlInPageLinksArr)) && (sizeof($_htmlInPageLinksArr) > 0))
	{
		$_I = 0;
		foreach($_htmlInPageLinksArr as $key => $value)
		{
			if(isset($value) && (is_array($value)) && (sizeof($value) > 0))
			{
				//foreach($value as $val)
				//{
					if($_I == 4){$_contentResults .= '<tbody class="collapse multi-collapse" id="multiCollapseInPageLinks">';}
					
					$_linkType = (isset($value[2]) && ($value[2])) ? "External Link" : "Internal Link";
					$_contentResults .= '<tr style="border:1px; solid #cccccc;line-height:19px;"><td>'.($_I+1).'.</td><td><a target="_blank" href="'.$key.'">'.$value[0].'</a></td><td>'.$_linkType.'</td></tr>';
					
					$_I++;
				//}
			}
		}
		if($_I >= 5){$_contentResults .= '</tbody><tr style="border:1px; solid #cccccc;line-height:19px;"><td>&nbsp;</td><td colspan="2"><a href="#" data-toggle="collapse" data-target="#multiCollapseInPageLinks" aria-expanded="false" aria-controls="multiCollapseInPageLinks">View more<a/></td></tr>';}
	}
	
	$_contentResults .= '
				</table>
			
	</div>
	';
	
	$_ruleStatus = "success";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
	
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
else
{
	$_contentResults = "Unfortunately, your site does not have any <strong>HTML Links</strong>.<br /><br />";
	
	$_contentResults .= "<span style='color:#dc4245;'>Your visitors won't be able to navigate your site. Further more, search engines are not able to determine which parts of your content are the most important</span>.";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}

?>