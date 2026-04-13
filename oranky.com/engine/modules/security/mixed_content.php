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

function getMixedCOntent($_url)
{
	global $_HREF_TAGS, $_SRC_TAGS;
	$hasSSLCertificate = $notSure = true;$hrefArr = $srcArr = $linkErrorsArr = array();$totalLinks = $totalErrors = 0;
	
	$doc = new DOMDocument;
	
	if(!@$doc->loadHTMLFile($_url)){}
	else
	{
		$xml = simplexml_import_dom($doc);
		//print_r($_LINK_TAGS);
		// --- HREF TAGS
		
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
									$pos = @strpos($attribute_val,"https://");
									if($pos === false)
									{
										//$linkErrorsArr[] = '&lt;'.$val.' href="'.$attribute_val."&gt;";$hasSSLCertificate = false;
										
										// Get relative URLS
										if(strpos($attribute_val,"http://") === false)
										{
											$linkRelativeArr[] = $attribute_val;
										}
										else
										{
											$linkErrorsArr[] = '&lt;'.$val.' href="'.$attribute_val."&gt;";$hasSSLCertificate = false;
										}
									}
									else{$linkSuccessArr[] =  $attribute_val;}
								}
								else{$linkJavascriptArr[] =  $attribute_val;}
							}
							else{$linkTelArr[] =  $attribute_val;}
						}
						else{$linkMailtoArr[] =  $attribute_val;}
						
						$totalLinks++;
					}
					else{$linkSectionArr[] = $attribute_val;}
				}
			}
			
			$hrefArr[$val] = array(sizeof($arr),$linkSuccessArr,$linkRelativeArr,sizeof($linkSectionArr),sizeof($linkMailtoArr),sizeof($linkTelArr),sizeof($linkJavascriptArr));
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
									$pos = @strpos($attribute_val,"https://");
									if($pos === false)
									{
										//$linkErrorsArr[] = '&lt;'.$val.' src="'.$attribute_val."&gt;";$hasSSLCertificate = false;
										
										// Get relative URLS
										if(strpos($attribute_val,"http://") === false)
										{
											$linkRelativeArr[] = $attribute_val;
										}
										else
										{
											$linkErrorsArr[] = '&lt;'.$val.' src="'.$attribute_val."&gt;";$hasSSLCertificate = false;
										}
									}
									else{$linkSuccessArr[] =  $attribute_val;}
								}
								else{$linkJavascriptArr[] =  $attribute_val;}
							}
							else{$linkTelArr[] =  $attribute_val;}
						}
						else{$linkMailtoArr[] =  $attribute_val;}
						
						$totalLinks++;
					}
					else{$linkSectionArr[] = $attribute_val;}
				}
			}
			
			$srcArr[$val] = array(sizeof($arr),$linkSuccessArr,$linkRelativeArr,sizeof($linkSectionArr),sizeof($linkMailtoArr),sizeof($linkTelArr),sizeof($linkJavascriptArr));
		}
	}
	
	//print_r($hrefArr);
	//print_r($srcArr);
	
	return array($hasSSLCertificate,$totalLinks,$linkErrorsArr,$hrefArr,$srcArr);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
Implementing HTTPS on your site is a very important step to protecting your site and your visistors from attack, but mixed content can render that protection useless. Most importantly, increasingly insecure mixed content will be blocked by browsers.<br /><br />
When visitors visit your web page served over HTTPS, their connection with the web server is encrypted with TLS and is therefore safeguarded from most sniffers and <a target="_blank" href="https://en.wikipedia.org/wiki/Man-in-the-middle_attack">man-in-the-middle attacks</a>.<br /><br />
HTTPS pages that include content fetched using cleartext HTTP are called <a target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/Security/Mixed_content">mixed content</a> page.<br /><br />
Web pages like these are only partially encrypted, leaving the unencrypted content accessible to sniffers and <a target="_blank" href="https://en.wikipedia.org/wiki/Man-in-the-middle_attack">man-in-the-middle attacks</a> attackers. This leaves your web pages unsafe.<br /><br />
<strong>What is "mixed content" error?</strong><br /><br />
When an HTML page is loaded via a secure connection, but some embedded resources, like images, scripts and styles are loaded from non-secure origins, browsers show "unsecure site" error message, scaring your users away.<br /><br />
Mixed content come in two different types namely: passive content and active content.<br /><br />
<strong>Passive Content:</strong><br />
	&nbsp;&nbsp;- <code>&lt;img&gt;</code><br />
	&nbsp;&nbsp;- <code>&lt;audio&gt;</code><br />
	&nbsp;&nbsp;- <code>&lt;video&gt;</code><br />
	&nbsp;&nbsp;- <code>&lt;object&gt;</code><br /><br />
<strong>Active Content:</strong><br />
	&nbsp;&nbsp;- <code>&lt;script&gt;</code><br />
	&nbsp;&nbsp;- <code>&lt;link&gt;</code><br />
	&nbsp;&nbsp;- <code>&lt;iframe&gt;</code><br />
	&nbsp;&nbsp;- <code>&lt;XMLHttpRequest&gt;</code><br />
	&nbsp;&nbsp;- <code>&lt;Navigator.sendBeacon&gt;</code><br /><br />
<strong>Recommendations:</strong><br /><br />
	&nbsp;&nbsp;<strong>1.</strong> Always use HTTPS URLs for your web pages link.<br />
	&nbsp;&nbsp;<strong>2.</strong> Always use the <code>Content-Security-Policy-Report-Only</code> header.<br /><br />
		<span class="img-thumbnail" style="display:block;background-color:#fff;border:1px solid #fff;width:80%;">
			<code>Content-Security-Policy-Report-Only: default-src https: "unsafe-inline" "unsafe-eval"; report-uri https://example.com/reportingEndpoint</code>
		</span><br />
	&nbsp;&nbsp;<strong>3.</strong> Always use the Upgrade-Insecure-Requests CSP directive.<br /><br />
		<span class="img-thumbnail" style="display:block;background-color:#fff;border:1px solid #fff;width:80%;">
			<code>&lt;meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"&gt;</code>
		</span><br />
		or, we can also use the <code>block-all-mixed-content</code> CSP directive to just block all mixed-content.<br />
		<span class="img-thumbnail" style="display:block;background-color:#fff;border:1px solid #fff;width:80%;">
			<code>&lt;meta http-equiv="Content-Security-Policy" content="block-all-mixed-content"&gt;
		</span>
';

$_securityDomains =
	'
		<table style="margin:2.5% 0% 0% 0%;width:100%;font-size:85%;border-top:1px dashed #ddd;border-bottom:1px solid #ddd;line-height:2.5px;" class="table table-striped">
			<thead><tr style="line-height:10px;font-style:italic;"><th class="text-secondary" colspan="2">Security Domains:</th></tr></thead>
			<tbody>
				<tr><td style="width:1.5%;border-right:1px solid #ddd;"></td><td class="text-secondary">Governance</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-danger" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Protection</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-danger" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Defence</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
	
if(isset($_SSL_CERTIFICATE_SECURITY['hasSSLCertificate']) && ($_SSL_CERTIFICATE_SECURITY['hasSSLCertificate']))
{
	list($title,$totalLinks,$linkErrorsArr,$_hrefArr,$_srcArr) = getMixedCOntent($url);
//$hrefArr,$srcArr
	
	//if(isset($hrefArr) && is_array($hrefArr) && (sizeof($hrefArr) > 0))
	
	global $_HREF_TAGS, $_SRC_TAGS;
		
	if(isset($title) && ($title))
	{
		$_contentResults = "Genius, your site does not have mixed-content.<br /><br />";
		
		$_contentResults .= '
		<div class="col-md-12 img-thumbnail" style="background-color:#f7f7f7;float:left;padding:1%;border:1px solid #fff;">
			<div class="caption" style="text-align:left;margin:1%;">
				<h6 style="color:#333;margin:1%;color:#444;font-size:102.5%;" class="text-secondary">Zero (<strong style="color:#4ea747;">0</strong>) Mixed-content errors discovered</h6>
				<p style="text-align:left;line-height:150%;margin:2.5% 1% 1% 1%;">
					We did not find any mixed content error(s) out of '.$totalLinks.' links on your site.
				</p>
			</div>
		</div>
		';
	
		$_ruleStatus = "success";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
		
		$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
	}
	else
	{
		$_contentResults = "Unfortunately, your site has mixed-content.<br /><br />";
		
		$_totalErrors = isset($linkErrorsArr) && is_array($linkErrorsArr) ? sizeof($linkErrorsArr) : 0;
		
		$_contentResults .= '
			<div class="col-md-6 img-thumbnail" style="background-color:#f7f7f7;float:left;padding:1%;border:1px solid #fff;">
				<div class="caption" style="text-align:left;margin:1%;">
					<h6 style="color:#333;margin:1%;color:#444;font-size:102.5%;" class="text-secondary">Mixed-content discovered: <strong style="color:#dc4245;">'.$_totalErrors.'</strong> / <strong style="color:#444;">'.$totalLinks.'</strong></h6>
					<p style="text-align:left;line-height:150%;margin:2.5% 1% 1% 1%;">
						We discovered <strong>'.$_totalErrors.'</strong> mixed content error(s) out of '.$totalLinks.' links on your site. Please, make sure that you strickly use abosolute URLs only.
					</p>
				</div>
			</div>
			<div class="col-md-6 img-thumbnail" style="background-color:#f7f7f7;float:left;padding:1%;border:1px solid #fff;">
				<div class="caption" style="text-align:left;margin:1%;">
					<p style="margin:2.5% 1% 2.0% 1%;">
						<select name="mixed-content-select-input" multiple style="width:100%;height:82px;font-size:90%;">';
					
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
			
	$_securityDomains =
	'
		<table style="margin:2.5% 0% 0% 0%;width:100%;font-size:85%;border-top:1px dashed #ddd;border-bottom:1px solid #ddd;line-height:2.5px;" class="table table-striped">
			<thead><tr style="line-height:10px;font-style:italic;"><th class="text-secondary" colspan="2">Security Domains:</th></tr></thead>
			<tbody>
				<tr><td style="width:1.5%;border-right:1px solid #ddd;"></td><td class="text-secondary">Governance</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-'.$_ruleStatus.'" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Protection</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-'.$_ruleStatus.'" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Defence</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
		
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1);
		$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
		$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
	}
}
else
{
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$_contentResults = "Unfortunately, your site is not secured.";
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
}
?>