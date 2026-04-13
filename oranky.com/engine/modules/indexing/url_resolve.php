<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS,$_SSL_CERTIFICATE_SECURITY;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getURLResolve($_url)
{
	global $_URL_RESOLVE;
	$hasSSLCertificate = false;
	
	list($novalue,$_url) = getURL($_url);
	
	if(isset($_URL_RESOLVE) && is_array($_URL_RESOLVE) && (sizeof($_URL_RESOLVE) >=1 ))
	{
		$hasSSLCertificate = $_URL_RESOLVE[0];
	}
	
	return array($hasSSLCertificate,$_URL_RESOLVE,$_url);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	Refer to:<br /><br />
		&nbsp;&nbsp;- <code>http://www.yourwebsite.co.za</code><br />
		&nbsp;&nbsp;- <code>https://www.yourwebsite.co.za</code><br />
		&nbsp;&nbsp;- <code>http://yourwebsite.co.za</code><br />
		&nbsp;&nbsp;- <code>https://yourwebsite.co.za</code><br /><br />
	Note that search engines view these four different versions of a web pages as different websites. This phenomenon, in turn, poses major content duplication problems for your site, which is a major violation from the search engine\'s sperspective.<br /><br />
	Search engines have a way in which they handle duplicate contnet such as described in the <a target="_blank" href="https://static.googleusercontent.com/media/guidelines.raterhub.com/en//searchqualityevaluatorguidelines.pdf">Google Search Quality Valuator Guidelines</a>.<br /><br />
<strong>Recommendations:</strong><br /><br />
	&nbsp;&nbsp;<strong>1.</strong> Note that one can either use either JavaScript or PHP, ASP.net or any other native programming languages header directives.<br /><br />
		<span class="img-thumbnail" style="display:block;background-color:#fff;border:1px solid #fff;width:80%;">
			<code>Location: <a target="_blank" href="https://www.w3.org/">https://www.w3.org/</a></code>
		</span>
';

list($title,$urlHeadersArr,$_url_actual) = getURLResolve($url);

if(isset($title) && ($title))
{
	$_contentResults = "Genius, your site has a redirect mechanism currently in place. All four (4) versions of your web page point to the same URL.<br /><br />";
	$_contentResults .= "Header Response: <span style='color:#4fa747;'>".(isset($urlHeadersArr[2])?$urlHeadersArr[2]:"")."</span><br /><br />";
	
	$_redirectedto_url = isset($urlHeadersArr[1]) ? $urlHeadersArr[1] : "";
	
	$_contentResults .= '
	<div class="col-md-12 img-thumbnail" style="background-colorx:#f7f7f7;float:left;padding:1%;border:1px solid #fff;padding:0%;">
		
				<table style="margin:0%;width:100%;borderx:1px solid #ddd;" class="table-striped">
					<thead class="thead-light table-secondary">
						<tr><td><strong>Actual URL</strong></td><td><strong>Resolved-to URL</strong></td></tr>
					</thead>
					<tr style="border:1px; solid #cccccc;line-height:19px;"><td><a target="_blank" href="http://'.$_url_actual.'/">http://'.$_url_actual.'/</a></td><td><a target="_blank" href="'.$_redirectedto_url.'">'.$_redirectedto_url.'</a></td></tr>
					<tr style="border:1px; solid #cccccc;line-height:19px;"><td><a target="_blank" href="http://www.'.$_url_actual.'/">http://www.'.$_url_actual.'/</a></td><td><a target="_blank" href="'.$_redirectedto_url.'">'.$_redirectedto_url.'</a></td></tr>
					<tr style="border:1px; solid #cccccc;line-height:19px;"><td><a target="_blank" href="https://'.$_url_actual.'/">https://'.$_url_actual.'/</a></td><td><a target="_blank" href="'.$_redirectedto_url.'">'.$_redirectedto_url.'</a></td></tr>
					<tr style="border:1px; solid #cccccc;line-height:19px;"><td><a target="_blank" href="https://www.'.$_url_actual.'/">https://www.'.$_url_actual.'</a></td><td><a target="_blank" href="'.$_redirectedto_url.'">'.$_redirectedto_url.'</a></td></tr>
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
	$_contentResults = "Unfortunately, your site does not have a redirect mechanism.<br /><br />";
	
	if(isset($urlHeadersArr[2]))
	{
		$_contentResults .= '
		<div class="col-md-12 img-thumbnail" style="background-color:#f7f7f7;float:left;padding:1%;border:1px solid #fff;">
			<div class="caption" style="text-align:left;margin:1%;">
				<p style="text-align:left;line-height:150%;margin:2.5% 1% 1% 1%;">
					'.$urlHeadersArr[2].'
				</p>
			</div>
		</div>
		';
		}
	
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}

?>