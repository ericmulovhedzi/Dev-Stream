<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS,$_SSL_CERTIFICATE_SECURITY;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getSSLSecurity($_url)
{
	global $_SSL_CERTIFICATE_SECURITY;
	$hasSSLCertificate = $notSure = false;$subjectArr = $issuerArr = array();$validTo = "0000-00-00";$httpsURL = "";
	
	list($novalue,$_url) = getURL($_url);
	
	$g = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
	//$g = stream_context_create (array("ssl" => array("capture_peer_cert" => true, "capture_peer_cert_chain" => true, "verify_peer" => false, "peer_name" => "", "verify_peer_name" => false, "allow_self_signed" => true, "capture_session_meta" => true, "sni_enabled" => true)));
	
	$socket = @stream_socket_client("ssl://".$_url.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $g);
	
	if(isset($socket) && ($socket))
	{
		$cont = stream_context_get_params($socket);//print_r($cont);
		$certDataArr = openssl_x509_parse($cont["options"]["ssl"]["peer_certificate"]);//print_r($cert_data);
		
		if(isset($certDataArr) && is_array($certDataArr))
		{
			if(isset($certDataArr['subject']['CN']) && (!empty($certDataArr['subject']['CN'])))
			{
				$pos = strpos($certDataArr['subject']['CN'],$_url);//echo $certDataArr['subject']['CN']." : ".$_url;
				if(!($pos === false))
				{//echo $certDataArr['subject']['CN']." : ".$_url;
					$hasSSLCertificate = true;
					$subjectArr = $certDataArr['subject'];
					$issuerArr = $certDataArr['issuer'];
					$validTo = $certDataArr['validTo_time_t'];
				}
				else
				{
					$__url = getSubdomainHost($_url); // --- check if it's subdomain
					
					if(isset($__url) && (!empty($__url)))
					{
						$pos = strpos($certDataArr['subject']['CN'],$__url);
						if(!($pos === false))
						{
							$hasSSLCertificate = true;
							$subjectArr = $certDataArr['subject'];
							$issuerArr = $certDataArr['issuer'];
							$validTo = $certDataArr['validTo_time_t'];
						}
					}
				}
			}
		}
	}
	else
	{
		$headers = @get_headers("https://".$_url);
		
		if(strpos($headers[0],'200')===false)
		{
			$notSure = false;
		}
		else
		{
			$httpsURL = "https://".$_url;
			$notSure = true;
		}
	}
	
	$_SSL_CERTIFICATE_SECURITY['hasSSLCertificate'] = $hasSSLCertificate;
	
	return array($hasSSLCertificate,$notSure,$httpsURL,$subjectArr,$issuerArr,$validTo);
}

$title = "";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
<strong>HTTP vs HTTPS</strong><br /><br />
There are many benefits to <code>https</code>, the secure version of the <code>http</code> protocol, so many in fact, that it is now considered essential for websites. The biggest benefits are as follows:<br /><br />
	&nbsp;&nbsp;- Security, for example, https prevents <a target="_blank" href="https://en.wikipedia.org/wiki/Man-in-the-middle_attack">man-in-the-middle attacks</a><br />
	&nbsp;&nbsp;- Privacy, for example, no online eavesdropping on users by third parties<br />
	&nbsp;&nbsp;- Speed enhancement<br />
	&nbsp;&nbsp;- Security, which allows processing of sensitive information such as payment processing;<br />
	&nbsp;&nbsp;- Keeps referral data in analytics<br />
	&nbsp;&nbsp;- Potentially improves rankings in Google Search Results<br />
	&nbsp;&nbsp;- http websites show as insecure in browsers, whereas https shows as secure<br /><br />	
Hypertext Transfer Protocol Secure or HTTPS ensures safety and confidentiality of the information exchange between the website and the visitors\'s device.<br /><br />
As a matter of fact, Around 2014 and 2015, <a target="_blank" href="https://developers.google.com/search/blog/2014/08/https-as-ranking-signal">Google itself announced</a> that an HTTPS (over HTTP) website would receive an extra boost in their ranking.<br /><br />
<strong>Recommendations:</strong><br /><br />
	&nbsp;&nbsp;- Redirect all of your HTTP pages to the HTTPS version of your site<br />
	&nbsp;&nbsp;- Make sure that all of your content (JavaScript, CSS, etc.) is linked to HTTPS<br />
	&nbsp;&nbsp;- Re-update your XML sitemap to ensure the URLs include HTTPS as well as your robots.txt file to reference this version<br />
	&nbsp;&nbsp;- Lastly, register the HTTPS version of your site in Google and Microsoft Bing Search Console and or Webmaster Tools<br /><br />
<strong>NB</strong>: Alway make sure to renew your SSL certificates every year, before they expire.
';

$_securityDomains =
	'
		<table style="margin:2.5% 0% 0% 0%;width:100%;font-size:85%;border-top:1px dashed #ddd;border-bottom:1px solid #ddd;line-height:2.5px;" class="table table-striped">
			<thead><tr style="line-height:10px;font-style:italic;"><th class="text-secondary" colspan="2">Security Domains:</th></tr></thead>
			<tbody>
				<tr><td style="width:1.5%;border-right:1px solid #ddd;"></td><td class="text-secondary">Governance</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-danger" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Protection</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Defence</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
	
list($title,$notSure,$httpsURL,$subjectArr,$issuerArr,$validTo) = getSSLSecurity($url);

if(isset($title) && ($title) || (isset($notSure) && ($notSure)))
{
	if($notSure)
	{
		$_contentResults .= '
		<div class="col-md-6" style="border:0px solid #eee;float:left;">
			<img src="http://oefimboboapp.net/orank.co.za/images/generic/ssl-certificate.jpg" />
			<p style="padding-top:2%;">
				<strong style="color:#444;">'.$httpsURL.'</strong>
			</p>
		</div>
		<div class="col-md-6" style="border-left:1px solid #eee;float:left;">
			<h6 style="padding-top:2%;color:#555;text:right;">Your page seems to be secured, however, the SSL certificate issuer could not be retrived.</h6>
		</div>
		';
		
		$_ruleStatus = "success";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
	}
	else
	{
		//echo $_SSL_CERTIFICATE_SECURITY['redirect_url'];
		
		$_contentResults .= '
		<div class="col-md-6" style="border:0px solid #eee;float:left;">
			<img src="http://oefimboboapp.net/orank.co.za/images/generic/ssl-certificate.jpg" />
			<p style="padding-top:2%;">
				<strong style="color:#444;">'.((isset($subjectArr['CN']) && (!empty($subjectArr['CN'])))?$subjectArr['CN']:"null").'</strong><br />
				<span style="font-size:90%;">Issued by: '.((isset($issuerArr['CN']) && (!empty($issuerArr['CN'])))?$issuerArr['CN']:"null").'</span><br />
				<span style="font-size:90%;">Expires: '.(isset($validTo)?date('D, M j, Y G:i:s a',$validTo):"null").'</span>
			</p>
		</div>
		<div class="col-md-6" style="border-left:1px solid #eee;float:left;">
			<h6 style="padding-top:2%;color:#555;text:right;">Subject name:</h6>
			<p style="padding-top:2%;">
				'.((isset($subjectArr['C']) && (!empty($subjectArr['C'])))?'<span style="font-size:90%;"><span style="color:#b19b00;">Country</span>: '.$subjectArr['C'].'</span>':"").'
				'.((isset($subjectArr['ST']) && (!empty($subjectArr['ST'])))?'<br /><span style="font-size:90%;"><span style="color:#b19b00;">State/Province</span>: '.$subjectArr['ST'].'</span>':"").'
				'.((isset($subjectArr['L']) && (!empty($subjectArr['L'])))?'<br /><span style="font-size:90%;"><span style="color:#b19b00;">Locality</span>: '.$subjectArr['L'].'</span>':"").'
				'.((isset($subjectArr['O']) && (!empty($subjectArr['O'])))?'<br /><span style="font-size:90%;"><span style="color:#b19b00;">Organization</span>: '.$subjectArr['O'].'</span>':"").'
				'.((isset($subjectArr['OU']) && (!empty($subjectArr['OU'])))?'<br /><span style="font-size:90%;"><span style="color:#b19b00;">Organizational Unit</span>: '.$subjectArr['OU'].'</span>':"").'
				'.((isset($subjectArr['CN']) && (!empty($subjectArr['CN'])))?'<br /><span style="font-size:90%;"><span style="color:#0096db;">Common Name</span>: '.$subjectArr['CN'].'</span>':"").'
			</p>
		</div>
		';
		
		$_ruleStatus = "success";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
	}
	
	
	$_securityDomains =
	'
		<table style="margin:2.5% 0% 0% 0%;width:100%;font-size:85%;border-top:1px dashed #ddd;border-bottom:1px solid #ddd;line-height:2.5px;" class="table table-striped">
			<thead><tr style="line-height:10px;font-style:italic;"><th class="text-secondary" colspan="2">Security Domains:</th></tr></thead>
			<tbody>
				<tr><td style="width:1.5%;border-right:1px solid #ddd;"></td><td class="text-secondary">Governance</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-'.$_ruleStatus.'" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Protection</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Defence</td></tr>
				<tr><td style="border-right:1px solid #ddd;"></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
	
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
}
else
{
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$_contentResults = "Missing";
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
}
?>