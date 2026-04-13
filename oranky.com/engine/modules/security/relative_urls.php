<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS,$_SSL_CERTIFICATE_SECURITY;
global $_ruleNo, $_ruleName, $_subSection, $url;

$_HREF_TAGS = array("a","area","base","link");
$_SRC_TAGS = array("audio","embed","iframe","img","input","script","source","track","video","iframe");

function getRelativeURLs($_url)
{
	global $_HREF_TAGS, $_SRC_TAGS, $_IN_PAGE_TAGS, $_PRIVACY_TERMS_POLICY_TAGS, $_STOP_WORDS, $_STOP_WORDS_USEFUL, $_KEY_PHRASES;
	
	$_STOP_WORDS = array_merge($_STOP_WORDS,$_STOP_WORDS_USEFUL);
	
	$hasSSLCertificate = $notSure = true;$hrefArr = $srcArr = $linkErrorsArr = array();$totalLinks = $totalErrors = 0;
	
	$doc = new DOMDocument;
	
	if(!@$doc->loadHTMLFile($_url)){}
	else
	{
		$xml = simplexml_import_dom($doc);
		//print_r($_LINK_TAGS);
		// --- HREF TAGS
		
		list($novalue,$__url) = getURL($_url);
		
		foreach($_HREF_TAGS as &$val)
		{
			$arr = $xml->xpath('//'.$val.'[@href]');
			
			$linkSuccessArr = $linkSectionArr = $linkRelativeArr = $linkMailtoArr = $linkTelArr = $linkJavascriptArr = array();
			if(isset($arr) && is_array($arr))
			{
				$_i = 0;
				foreach($arr as &$_val)
				{
					$attribute_val = @strval($_val[0]['href']);
					if(strpos($attribute_val,"#") === false) // --- Ignore all links targeted to #, they are usually for redirecting audiance to a particular section within the same page.
					{
						if(strpos($attribute_val,"mailto:") === false) // --- Also, ignore all links that link to an email address
						{
							if(strpos($attribute_val,"tel:") === false) // --- Also, ignore all links that link to a phone number
							{
								if(strpos($attribute_val,"javascript:") === false) // --- Also, ignore all links that link to a JavaScript
								{
									$isExternal = false;
									
									$tag_val = trim(@strval($_val[0]));
										
									if((strpos($attribute_val,"http://") === false) && (strpos($attribute_val,"https://") === false))
									{
										$linkErrorsArr[] = '&lt;'.$val.' href="'.$attribute_val."&gt;";$hasSSLCertificate = false;
										
									}
									else
									{
										if(strpos($attribute_val,$__url) === false)
										{
											$isExternal = true;
											if(empty($tag_val)){$tag_val = "No Achor Text Available";}
										}
										
										if(!empty($tag_val))
										{
											// --- If statement to sanitize the relevance of the link
											$_tag_val = strtolower($tag_val);
											if(
												(!(
													($attribute_val == "http://www.".$__url) || ($attribute_val == "http://www.".$__url."/")
													||
													($attribute_val == "https://www.".$__url) || ($attribute_val == "https://www.".$__url."/")
													||
													($attribute_val == "http://".$__url) || ($attribute_val == "http://".$__url."/")
													||
													($attribute_val == "https://".$__url) || ($attribute_val == "https://".$__url."/")
												))
												&&
												(
													/*(strpos($_tag_val,"More") === false) && (strpos($_tag_val,"more") === false) && (strpos($_tag_val,"read more") === false) && (strpos($_tag_val,"view more") === false) && (strpos($_tag_val,"see more") === false) && (strpos($_tag_val,"See more") === false) && (strpos($_tag_val,"See More") === false)*/
													 (strpos($_tag_val,"more") === false)
												)
											   ) // --- Avoid all links that re-drect or navigate to home page
											{
												$_IN_PAGE_TAGS[$attribute_val] = array($tag_val,'&lt;'.$val.' href="'.$attribute_val."&gt;",$isExternal);
												
												if($tag_val != "No Achor Text Available")
												{
													if(
														(
															(strpos($_tag_val,"privacy") === false) && (strpos($_tag_val,"terms") === false) && (strpos($_tag_val,"policy") === false) && (strpos($_tag_val,"disclaimer") === false)
														)
													)
													{
														$_KEY_PHRASES[] = stopWordsNLP($tag_val, $_STOP_WORDS);
													}
												}
											}
										}
									}
									
									if(!empty($tag_val))
									{
										$_tag_val = strtolower($tag_val);
										if(
											(!(
												(strpos($_tag_val,"privacy") === false) && (strpos($_tag_val,"terms") === false) && (strpos($_tag_val,"policy") === false) && (strpos($_tag_val,"disclaimer") === false)
											))
										  )
										{
											$_PRIVACY_TERMS_POLICY_TAGS[$attribute_val] = array($tag_val,'&lt;'.$val.' href="'.$attribute_val."&gt;",$isExternal);
										}
									}
									
								}
							}
						}
						
						$totalLinks++;
					}
					else
					{
						$tag_val = trim(@strval($_val[0]));
						
						if(!empty($tag_val))
						{
							$_KEY_PHRASES[] = stopWordsNLP($tag_val, $_STOP_WORDS);
						}
					}
				}
			}
		}
		
		// --- SRC TAGS
		
		foreach($_SRC_TAGS as &$val)
		{
			$arr = $xml->xpath('//'.$val.'[@src]');
			
			$linkSuccessArr = $linkSectionArr = $linkRelativeArr = $linkMailtoArr = $linkTelArr = $linkJavascriptArr = array();
			if(isset($arr) && is_array($arr))
			{
				$_i = 0;
				foreach($arr as &$_val)
				{
					$attribute_val = @strval($_val[0]['src']);
					if(strpos($attribute_val,"#") === false) // --- Ignore all links targeted to #, they are usually for redirecting audiance to a particular section within the same page.
					{
						if(strpos($attribute_val,"mailto:") === false) // --- Also, ignore all links that link to an email address
						{
							if(strpos($attribute_val,"tel:") === false) // --- Also, ignore all links that link to a phone number
							{
								if(strpos($attribute_val,"javascript:") === false) // --- Also, ignore all links that link to a JavaScript
								{
									if((strpos($attribute_val,"http://") === false) && (strpos($attribute_val,"https://") === false))
									{
										$linkErrorsArr[] = '&lt;'.$val.' src="'.$attribute_val."&gt;";$hasSSLCertificate = false;
										
									}
								}
							}
						}
						
						$totalLinks++;
					}
				}
			}
		}
	}
	
	return array($hasSSLCertificate,$totalLinks,$linkErrorsArr);
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
	Crawl budget is, basically, is a finite number of URLs on a site set by search engine crawlers including <a target="_blank" href="https://www.google.com/">Google</a>.<br /><br />
	This budget is based on a number of things and this include but not limited to: your site authority, your actual site Pagerank, the complexity of your site, and how often your site gets updated.<br /><br />
	If your site is not <code>search engine friendly</code>, search engines will spend less time crawling it and do it less often.<br /><br />
	So, having multiple or no relative links will impact on how deep, extensive, and how often search engines crawl your site, and therefore, having an impact on your <a target="_blank" href="https://simple.wikipedia.org/wiki/Search_engine_optimization">SEO</a>.<br /><br />
	<strong>Three types of Relative URLs:</strong><br /><br />
		&nbsp;&nbsp;- <code>&lt;a href="//example.com"&gt;<span class="text-secondary">Scheme-relative URL</span>&lt;/a&gt;</code><br />
		&nbsp;&nbsp;- <code>&lt;a href="/en-US/docs/Web/HTML"&gt;<span class="text-secondary">Origin-relative URL</span>&lt;/a&gt;</code><br />
		&nbsp;&nbsp;- <code>&lt;a href="./p"&gt;<span class="text-secondary">Directory-relative URL</span>&lt;/a&gt;</code><br /><br />
		
	Reference: <a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a">https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a</a>
';

$_securityDomains =
	'
		<table style="margin:2.5% 0% 0% 0%;width:100%;font-size:85%;border-top:1px dashed #ddd;border-bottom:1px solid #ddd;line-height:2.5px;" class="table table-striped">
			<thead><tr style="line-height:10px;font-style:italic;"><th colspan="2" class="text-secondary">Security Domains:</th></tr></thead>
			<tbody>
				<tr><td style="width:1.5%;border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-danger" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Governance</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Protection</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Defence</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
	
list($title,$totalLinks,$linkErrorsArr) = getRelativeURLs($url);
//$hrefArr,$srcArr

//if(isset($hrefArr) && is_array($hrefArr) && (sizeof($hrefArr) > 0))

global $_HREF_TAGS, $_SRC_TAGS;
	
if(isset($title) && ($title))
{
	$_contentResults = "Genius, your site does not have mixed-content.<br /><br />";
	
	$_contentResults .= '
	<div class="col-md-12 img-thumbnail" style="background-color:#f7f7f7;float:left;padding:1%;border:1px solid #fff;">
		<div class="caption" style="text-align:left;margin:1%;">
			<h6 style="color:#333;margin:1%;color:#444;font-size:102.5%;" class="text-secondary">Zero (<strong style="color:#4ea747;">0</strong>) relative URL errors discovered</h6>
			<p style="text-align:left;line-height:150%;margin:2.5% 1% 1% 1%;">
				We did not find any relative URL error(s) out of '.$totalLinks.' links on your site.
			</p>
		</div>
	</div>
	';
	
	$_ruleStatus = "success";
	
	$_securityDomains =
	'
		<table style="margin:2.5% 0% 0% 0%;width:100%;font-size:85%;border-top:1px dashed #ddd;border-bottom:1px solid #ddd;line-height:2.5px;" class="table table-striped">
			<thead><tr style="line-height:10px;font-style:italic;"><th colspan="2" class="text-secondary">Security Domains:</th></tr></thead>
			<tbody>
				<tr><td style="width:1.5%;border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-'.$_ruleStatus.'" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Governance</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Protection</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Defence</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
	
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
}
else
{
	$_contentResults = "Unfortunately, your site has relative URL.<br /><br />";
	
	$_totalErrors = isset($linkErrorsArr) && is_array($linkErrorsArr) ? sizeof($linkErrorsArr) : 0;
	
	$_contentResults .= '
		<div class="col-md-6 img-thumbnail" style="background-color:#f7f7f7;float:left;padding:1%;border:1px solid #fff;">
			<div class="caption" style="text-align:left;margin:1%;">
				<h6 style="color:#333;margin:1%;color:#444;font-size:102.5%;" class="text-secondary">Relative URL discovered: <strong style="color:#dc4245;">'.$_totalErrors.'</strong> / <strong style="color:#444;">'.$totalLinks.'</strong></h6>
				<p style="text-align:left;line-height:150%;margin:2.5% 1% 1% 1%;">
					We discovered <strong>'.$_totalErrors.'</strong> URL error(s) out of '.$totalLinks.' URLs on your site. Please, make sure that you strickly use abosolute URLs only.
				</p>
			</div>
		</div>
		<div class="col-md-6 img-thumbnail" style="background-color:#f7f7f7;float:left;padding:1%;border:1px solid #fff;">
			<div class="caption" style="text-align:left;margin:1%;">
				<p style="margin:2.5% 1% 2.0% 1%;">
					<select name="relativeerrorslist" multiple style="width:100%;height:82px;font-size:90%;">';
				
	if($_totalErrors > 0)
	{
		foreach($linkErrorsArr as $value)
		{
			$_contentResults .= '<option><code>'.$value.'</code></option>';
		}
	}
	
	$_contentResults .= '
					</select>
				</p>
			</div>
		</div>
		';
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
}

?>