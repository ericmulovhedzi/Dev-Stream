<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS,$_SSL_CERTIFICATE_SECURITY;
global $_ruleNo, $_ruleName, $_subSection, $url;

$_HTML_HEADING_TAGS = array("h1","h2","h3","h4","h5","h6");

function getHTMLHeadings($_url)
{
	global $_HTML_HEADING_TAGS, $_STOP_WORDS, $_STOP_WORDS_USEFUL, $_KEY_PHRASES, $_KEY_PHRASES_DUPLICATES;
	$hasHTMLHeadings = false;$htmlHeadingsArr = array();
	
	$_STOP_WORDS = array_merge($_STOP_WORDS,$_STOP_WORDS_USEFUL);
	
	$doc = new DOMDocument;
	
	if(!@$doc->loadHTMLFile($_url)){}
	else
	{
		$xml = simplexml_import_dom($doc);
		
		foreach($_HTML_HEADING_TAGS as &$val)
		{
			$arr = $xml->xpath('//'.$val);
			
			if(isset($arr) && is_array($arr))
			{
				$_i = 0;
				foreach($arr as &$_val)
				{
					$str_val = "";
					
					if(isset($_val[0])){$str_val = @strval($_val[0]);}
					
					if(isset($_val->a))
					{
						
						$str_val .= @strval($_val->a);
						$_KEY_PHRASES_DUPLICATES[] = $str_val;
					}
					else if(isset($_val->span)){$str_val .= @strval($_val->span);}
					else if(isset($_val->b)){$str_val .= @strval($_val->b);}
					else if(isset($_val->i)){$str_val .= @strval($_val->i);}
					else if(isset($_val->u)){$str_val .= @strval($_val->u);}
					else if(isset($_val->strong)){$str_val .= @strval($_val->strong);}
					
					if(!empty($str_val))
					{
						$htmlHeadingsArr[$val][] = $str_val;
						$_KEY_PHRASES[] = stopWordsNLP($str_val, $_STOP_WORDS);
						$hasHTMLHeadings  = true;
					}
				}
			}
		}
		
	}
	print_r($keyPhrases );
	return array($hasHTMLHeadings,$htmlHeadingsArr);
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

list($title,$_htmlHeadingsArr) = getHTMLHeadings($url);

if(isset($title) && ($title))
{
	$_contentResults = '';
	
	global $_HTML_HEADING_TAGS;
	
	foreach($_HTML_HEADING_TAGS as $val)
	{
		$numOfItems = (isset($_htmlHeadingsArr[$val]) && is_array($_htmlHeadingsArr[$val]) && (sizeof($_htmlHeadingsArr[$val]) > 0)) ? sizeof($_htmlHeadingsArr[$val]) : 0;
		
		$color = "secondary";if($numOfItems == 0){$color = "warning";}
		
		$_contentResults .= '<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;font-size:100%;"><strong>&lt;'.strtoupper($val).'&gt;</strong> <span class="badge badge-'.$color.'" style="font-size:90%;">'.$numOfItems.'</span></button>&nbsp;&nbsp;&nbsp;';
	}
	
	$_contentResults .= "<br /><br /><span>Search engines find it hard to determine which parts of your content are the most important if it is not included in HTML headers</span>.";
	
	$_contentResults .= '
	<div class="col-md-12 img-thumbnail" style="margin-top:2.5%;float:left;padding:1%;border:1px solid #fff;padding:0%;">
				<table style="margin:0%;width:100%;borderx:1px solid #ddd;" class="table-striped">';
				
	if(isset($_htmlHeadingsArr) && (is_array($_htmlHeadingsArr)) && (sizeof($_htmlHeadingsArr) > 0))
	{
		$_I = 0;
		foreach($_htmlHeadingsArr as $key => $value)
		{
			if(isset($value) && (is_array($value)) && (sizeof($value) > 0))
			{
				foreach($value as $val)
				{
					if($_I == 4){$_contentResults .= '<tbody class="collapse multi-collapse" id="multiCollapseHeaders">';}
					
					$_contentResults .= '<tr style="border:1px; solid #cccccc;line-height:19px;"><td><strong>&lt;'.strtoupper($key).'&gt;</strong></td><td>'.$val.'</td></tr>';
					
					$_I++;
				}
			}
		}
		if($_I >= 5){$_contentResults .= '</tbody><tr style="border:1px; solid #cccccc;line-height:19px;"><td>&nbsp;</td><td><a href="#" data-toggle="collapse" data-target="#multiCollapseHeaders" aria-expanded="false" aria-controls="multiCollapseHeaders">View more<a/></td></tr>';}
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
	$_contentResults = "Unfortunately, your site does not have any <strong>HTML heading</strong> within its content.<br /><br />";
	
	global $_HTML_HEADING_TAGS;
	
	foreach($_HTML_HEADING_TAGS as $val)
	{
		$numOfItems = (isset($_htmlHeadingsArr[$val]) && is_array($_htmlHeadingsArr[$val]) && (sizeof($_htmlHeadingsArr[$val]) > 0)) ? sizeof($_htmlHeadingsArr[$val]) : 0;
		
		$color = "secondary";if($numOfItems == 0){$color = "danger";}
		
		$_contentResults .= '<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;font-size:100%;"><strong>'.strtoupper($val).'</strong> <span class="badge badge-'.$color.'" style="font-size:90%;">'.$numOfItems.'</span></button>&nbsp;&nbsp;&nbsp;';
	}
	
	$_contentResults .= "<br /><br /><span style='color:#dc4245;'>Search engines are not able to determine which parts of your content are the most important</span>.";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}

?>