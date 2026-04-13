<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS,$_SSL_CERTIFICATE_SECURITY;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getPostCovid19CyberSecurity($_url)
{
	global $_URL_RESOLVE, $_TECHNOLOGIES;
	$hasCyberSecurity = false;$cyberSecurityArr = $srcArr = $linkErrorsArr = array();$totalErrors = 0;
	
	$_headersArr = get_headers($_url, true);
	
	if(isset($_headersArr) && is_array($_headersArr) && (sizeof($_headersArr) >=1 ))
	{
		foreach($_headersArr as $keys => $vals)
		{
			if(preg_match("/".$keys."/i", "strict-transport-security")) // --- SUB RULE #1: strict-transport-security
			{
				if(is_array($vals) && (sizeof($vals) >=1 ))
				{
					$cyberSecurityArr['Strict-Transport-Security'] = isset($vals[0]) ? $vals[0] : "";
					$cyberSecurityArr['Strict-Transport-Security'] = (isset($vals[1]) && ($vals[0] != $vals[1])) ? $cyberSecurityArr['Strict-Transport-Security']." & ".$vals[1] : $cyberSecurityArr['Strict-Transport-Security'];
				}
				else{$cyberSecurityArr['Strict-Transport-Security'] = $vals;}
				
				$hasCyberSecurity = true;
			}
			
			if(preg_match("/".$keys."/i", "x-xss-protection")) // --- SUB RULE #2: x-xss-protection
			{
				if(is_array($vals) && (sizeof($vals) >=1 ))
				{
					$cyberSecurityArr['X-XSS-Protection'] = isset($vals[0]) ? $vals[0] : "";
					$cyberSecurityArr['X-XSS-Protection'] = (isset($vals[1]) && ($vals[0] != $vals[1])) ? $cyberSecurityArr['X-XSS-Protection']." & ".$vals[1] : $cyberSecurityArr['X-XSS-Protection'];
				}
				else{$cyberSecurityArr['X-XSS-Protection'] = $vals;}
				
				$hasCyberSecurity = true;
			}
			
			if(preg_match("/".$keys."/i", "x-frame-options")) // --- SUB RULE #3: x-frame-options
			{
				if(is_array($vals) && (sizeof($vals) >=1 ))
				{
					$cyberSecurityArr['X-Frame-Options'] = isset($vals[0]) ? $vals[0] : "";
					$cyberSecurityArr['X-Frame-Options'] = (isset($vals[1]) && ($vals[0] != $vals[1])) ? $cyberSecurityArr['X-Frame-Options']." & ".$vals[1] : $cyberSecurityArr['X-Frame-Options'];
				}
				else{$cyberSecurityArr['X-Frame-Options'] = $vals;}
				
				$hasCyberSecurity = true;
			}
			
			if(preg_match("/".$keys."/i", "content-security-policy")) // --- SUB RULE #4: content-security-policy
			{
				if(is_array($vals) && (sizeof($vals) >=1 ))
				{
					$cyberSecurityArr['Content-Security-Policy'] = isset($vals[0]) ? $vals[0] : "";
					$cyberSecurityArr['Content-Security-Policy'] = (isset($vals[1]) && ($vals[0] != $vals[1])) ? $cyberSecurityArr['Content-Security-Policy']." & ".$vals[1] : $cyberSecurityArr['Content-Security-Policy'];
				}
				else{$cyberSecurityArr['Content-Security-Policy'] = $vals;}
				
				$hasCyberSecurity = true;
			}
			
			if(preg_match("/".$keys."/i", "x-content-type-options") && (!preg_match("/".$keys."/i", "content-type"))) // --- SUB RULE #5: x-content-type-options
			{
				if(is_array($vals) && (sizeof($vals) >=1 ))
				{
					$cyberSecurityArr['X-Content-Type-Options'] = isset($vals[0]) ? $vals[0] : "";
					$cyberSecurityArr['X-Content-Type-Options'] = (isset($vals[1]) && ($vals[0] != $vals[1])) ? $cyberSecurityArr['X-Content-Type-Options']." & ".$vals[1] : $cyberSecurityArr['X-Content-Type-Options'];
				}
				else{$cyberSecurityArr['X-Content-Type-Options'] = $vals;}
				
				$hasCyberSecurity = true;
			}
			
			// --- OTHER MODULES ---
			
			if(preg_match("/".$keys."/i", "server")) // --- SUB RULE #X1
			{
				if(is_array($vals) && (sizeof($vals) >=1 ))
				{
					$_TECHNOLOGIES['webserver'] = isset($vals[0]) ? $vals[0] : "";
					$_TECHNOLOGIES['webserver'] = (isset($vals[1]) && ($vals[0] != $vals[1])) ? $_TECHNOLOGIES['webserver']." & ".$vals[1] : $_TECHNOLOGIES['webserver'];
				}
				else{$_TECHNOLOGIES['webserver'] = $vals;}
			}
			
			if($keys === 0) // --- SUB RULE #X1
			{
				$_URL_RESOLVE[2] = $vals;
			}
			
			if(preg_match("/".$keys."/i", "location")) // --- SUB RULE #X3
			{
				if(isset($vals) && (!is_array($vals)))
				{//print_r($vals);
					if(preg_match("/https/i", $vals))
					{
						$_URL_RESOLVE[0] = true;
						$_URL_RESOLVE[1] = $vals;
					}
				}
			}
			
			if(preg_match("/".$keys."/i", "content-length")) // --- SUB RULE #X4
			{
				$_URL_RESOLVE[3] = $vals;
			}
		
		}
	}
	//print_r($cyberSecurityArr);
	return array($hasCyberSecurity,$cyberSecurityArr);
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
	Note that search engines view these as different websites.<br /><br />	
	
';

$_securityDomains =
	'
		<table style="margin:2.5% 0% 0% 0%;width:100%;font-size:85%;border-top:1px dashed #ddd;border-bottom:1px solid #ddd;line-height:2.5px;" class="table table-striped">
			<thead><tr style="line-height:10px;font-style:italic;"><th class="text-secondary" colspan="2">Security Domains:</th></tr></thead>
			<tbody>
				<tr><td style="width:1.5%;border-right:1px solid #ddd;"></td><td class="text-secondary">Governance</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-danger" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Protection</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-danger" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Defence</td></tr>
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-danger" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
	
list($title,$_cyberSecurityArr) = getPostCovid19CyberSecurity($url);

if(isset($title) && ($title))
{
	$_totalErrors = (isset($_cyberSecurityArr) && is_array($_cyberSecurityArr) && (sizeof($_cyberSecurityArr) > 0)) ? sizeof($_cyberSecurityArr) : 0 ;
	
	if($_totalErrors == 5)
	{
		$_contentResults = "Genius, your site has all has <strong style='color:#1ea747;'>".$_totalErrors."</strong> major <strong style='color:#1ea747;'>Post Covid-19 Cyber Security</strong> in place.<br /><br />";
		
		$_ruleStatus = "success";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0);
	}
	else
	{
		$_contentResults = "Unfortunately, your site or cloud solution only has <strong style='color:#dc4245;'>".$_totalErrors."</strong> <strong style='color:#f9c132;'>Post Covid-19 Cyber Security</strong> in place.<br /><br />";
		
		$_ruleStatus = "warning";
		$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>1);
	}
	
	if(($_totalErrors > 0) && ($_totalErrors < 5))
	{
		$_contentResults_header = '
			<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li></li>
		';
		
		$_contentResults_body = '
			<div class="tab-content" id="myTabContent">
		';
		// --- RULE 1
		if(isset($_cyberSecurityArr['Strict-Transport-Security']))
		{
			if(($_cyberSecurityArr['Strict-Transport-Security'] === 0) || empty($_cyberSecurityArr['Strict-Transport-Security']))
			{
				$_contentResults_header .= '<li class="nav-item"><a class="nav-link active" id="hsts-tab" data-toggle="tab" href="#hsts" role="tab" aria-controls="hsts" aria-selected="false">HSTS <i class="fa fa-exclamation-triangle"></i></a></li>';
				
				$_contentResults_body .= '<div class="tab-pane fade show active img-thumbnail" id="hsts" role="tabpanel" aria-labelledby="hsts-tab"><div class="img-thumbnail"><br />';
					$_contentResults_body .= "<strong>HTTP Strict Transport Security (HSTS)</strong> header directive is currently set to a value <strong>0</strong> or empty string:<br />";
					$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately the possibility of HTTP connections are not entirely eliminated, making your cloud solution or site vulnerable</span>.<br /><br />";
				$_contentResults_body .= '</div></div>';
			}
		}
		else
		{
			$_contentResults_header .= '<li class="nav-item"><a class="nav-link active" id="hsts-tab" data-toggle="tab" href="#hsts" role="tab" aria-controls="hsts" aria-selected="false">HSTS <i class="fa fa-minus-circle"></i></a></li>';
			
			$_contentResults_body .= '<div class="tab-pane fade show active" id="hsts" role="tabpanel" aria-labelledby="hsts-tab"><div class="img-thumbnail"><br />';
				$_contentResults_body .= "<strong>HTTP Strict Transport Security (HSTS)</strong> header directive currently does not exist:<br />";
				$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately the possibility of HTTP connections are not entirely eliminated, making your cloud solution or site vulnerable</span>.<br /><br />";
			$_contentResults_body .= '</div></div>';
		}
		
		// --- RULE 2
		if(isset($_cyberSecurityArr['X-XSS-Protection']))
		{
			if(($_cyberSecurityArr['X-XSS-Protection'] === 0) || empty($_cyberSecurityArr['X-XSS-Protection']))
			{
				$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="x-xss-tab" data-toggle="tab" href="#x-xss" role="tab" aria-controls="x-xss" aria-selected="false">X-XSS <i class="fa fa-exclamation-triangle"></i></a></li>';
				
				$_contentResults_body .= '<div class="tab-pane fade" id="x-xss" role="tabpanel" aria-labelledby="x-xss-tab"><div class="img-thumbnail"><br />';
					$_contentResults_body .= "<strong>X-XSS-Protection (X-XSS)</strong> header directive is currently set to a value <strong>".$_cyberSecurityArr['X-XSS-Protection']."</strong> or empty string:<br />";
					$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your site is still vulnerable to Cross-Site Scripting attacks</span>.<br /><br />";
				$_contentResults_body .= '</div></div>';
			}
		}
		else
		{
			$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="x-xss-tab" data-toggle="tab" href="#x-xss" role="tab" aria-controls="x-xss" aria-selected="false">X-XSS <i class="fa fa-minus-circle"></i></a></li>';
			
			$_contentResults_body .= '<div class="tab-pane fade" id="x-xss" role="tabpanel" aria-labelledby="x-xss-tab"><div class="img-thumbnail"><br />';
				$_contentResults_body .= "<strong>X-XSS-Protection (X-XSS)</strong> header directive currently does not exist:<br />";
				$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your cloud solution or site is still vulnerable to Cross-Site Scripting attacks</span>.<br /><br />";
			$_contentResults_body .= '</div></div>';
		}
		
		// --- RULE 3
		if(isset($_cyberSecurityArr['Content-Security-Policy']))
		{
			if(($_cyberSecurityArr['Content-Security-Policy'] === 0) || empty($_cyberSecurityArr['Content-Security-Policy']))
			{
				$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="csp-tab" data-toggle="tab" href="#csp" role="tab" aria-controls="csp" aria-selected="false">CSP <i class="fa fa-exclamation-triangle"></i></a></li>';
				
				$_contentResults_body .= '<div class="tab-pane fade" id="csp" role="tabpanel" aria-labelledby="csp-tab"><div class="img-thumbnail"><br />';
					$_contentResults_body .= "<strong>Content Security Policy (CSP)</strong> header directive is currently set to a value <strong>0</strong> or empty string:<br />";
					$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your site is still vulnerable to Cross-Site Scripting and other code injection attacks</span>.<br /><br />";
				$_contentResults_body .= '</div></div>';
			}
		}
		else
		{
			$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="csp-tab" data-toggle="tab" href="#csp" role="tab" aria-controls="csp" aria-selected="false">CSP <i class="fa fa-minus-circle"></i></a></li>';
			
			$_contentResults_body .= '<div class="tab-pane fade" id="csp" role="tabpanel" aria-labelledby="csp-tab"><div class="img-thumbnail"><br />';
				$_contentResults_body .= "<strong>Content Security Policy (CSP)</strong> header directive currently does not exist:<br />";
				$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your cloud solution or site is still vulnerable to Cross-Site Scripting and other code injection attacks</span>.<br /><br />";
			$_contentResults_body .= '</div></div>';
		}
		
		// --- RULE 4
		if(isset($_cyberSecurityArr['X-Frame-Options']))
		{
			if(($_cyberSecurityArr['X-Frame-Options'] === 0) || empty($_cyberSecurityArr['X-Frame-Options']))
			{
				$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="x-frame-tab" data-toggle="tab" href="#x-frame" role="tab" aria-controls="x-frame" aria-selected="false">X-FRAME <i class="fa fa-exclamation-triangle"></i></a></li>';
				
				$_contentResults_body .= '<div class="tab-pane fade" id="x-frame" role="tabpanel" aria-labelledby="x-frame-tab"><div class="img-thumbnail"><br />';
					$_contentResults_body .= "<strong>X-Frame-Options (X-FRAME)</strong> header directive is currently set to a value <strong>0</strong> or empty string:<br />";
					$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your site is still vulnerable to some injected code running in the background</span>.<br /><br />";
				$_contentResults_body .= '</div></div>';
			}
		}
		else
		{
			$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="x-frame-tab" data-toggle="tab" href="#x-frame" role="tab" aria-controls="x-frame" aria-selected="false">X-FRAME <i class="fa fa-minus-circle"></i></a></li>';
			
			$_contentResults_body .= '<div class="tab-pane fade" id="x-frame" role="tabpanel" aria-labelledby="x-frame-tab"><div class="img-thumbnail"><br />';
				$_contentResults_body .= "<strong>X-Frame-Options (X-FRAME)</strong> header directive currently does not exist:<br />";
				$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your cloud solution or site is still vulnerable to some injected code running in the background.</span>.<br /><br />";
			$_contentResults_body .= '</div></div>';
		}
		
		$_contentResults_header .= '</ul>';
		$_contentResults_body .= '</div>';
		
		$_contentResults .= $_contentResults_header;
		$_contentResults .= $_contentResults_body;
	}
	
	$_contentResults .= '
	<div class="col-md-12 img-thumbnail" style="margin-top:2.5%;float:left;padding:1%;border:1px solid #fff;padding:0%;">
				<table style="margin:0%;width:100%;borderx:1px solid #ddd;" class="table-striped">
					<thead class="thead-light table-secondary">
						<tr><td style="width:30%;"><strong>HTTP Response Header</strong></td><td><strong>Value</strong></td></tr>
					</thead>';
				
	if(isset($_cyberSecurityArr) && (is_array($_cyberSecurityArr)) && (sizeof($_cyberSecurityArr) > 0))
	{
		foreach($_cyberSecurityArr as $key => $value)
		{
			$_contentResults .= '<tr style="border:1px; solid #cccccc;line-height:19px;"><td>'.$key.'</td><td>'.$value.'</td></tr>';
		}
	}
	
	$_contentResults .= '
				</table>
			
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
				<tr><td style="border-right:1px solid #ddd;"><i class="fa fa-shield-alt text-'.$_ruleStatus.'" style="font-size:120%;line-height:2.5px;"></i></td><td class="text-secondary">Resilience</td></tr>
			</tbody>
		</table>
	';
	
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
}
else
{
	$_contentResults = "Unfortunately, your site does not have any <strong style='color:#dc4245;'>Post Covid-19 Cyber Security</strong> in place.<br /><br />";
	$_contentResults .= "<strong>NB</strong>: Please, enure that you initiate a <strong>Post Covid-19 Cyber Security</strong> policy for all your servers running your cloud solutions and sites urgently.<br /><br />";
	
	$_contentResults_header = '
			<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li></li>
		';
	
	$_contentResults_body = '
		<div class="tab-content" id="myTabContent">
	';
	// --- RULE 1
	
	$_contentResults_header .= '<li class="nav-item"><a class="nav-link active" id="hsts-tab" data-toggle="tab" href="#hsts" role="tab" aria-controls="hsts" aria-selected="false">HSTS <i class="fa fa-minus-circle"></i></a></li>';
	
	$_contentResults_body .= '<div class="tab-pane fade show active" id="hsts" role="tabpanel" aria-labelledby="hsts-tab"><div class="img-thumbnail"><br />';
		$_contentResults_body .= "<strong>HTTP Strict Transport Security (HSTS)</strong> header directive currently does not exist:<br />";
		$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately the possibility of HTTP connections are not entirely eliminated, making your cloud solution or site vulnerable</span>.<br /><br />";
	$_contentResults_body .= '</div></div>';

	// --- RULE 2
	
	$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="x-xss-tab" data-toggle="tab" href="#x-xss" role="tab" aria-controls="x-xss" aria-selected="false">X-XSS <i class="fa fa-minus-circle"></i></a></li>';
	
	$_contentResults_body .= '<div class="tab-pane fade" id="x-xss" role="tabpanel" aria-labelledby="x-xss-tab"><div class="img-thumbnail"><br />';
		$_contentResults_body .= "<strong>X-XSS-Protection (X-XSS)</strong> header directive currently does not exist:<br />";
		$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your cloud solution or site is still vulnerable to Cross-Site Scripting attacks</span>.<br /><br />";
	$_contentResults_body .= '</div></div>';

	// --- RULE 3
	
	$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="csp-tab" data-toggle="tab" href="#csp" role="tab" aria-controls="csp" aria-selected="false">CSP <i class="fa fa-minus-circle"></i></a></li>';
	
	$_contentResults_body .= '<div class="tab-pane fade" id="csp" role="tabpanel" aria-labelledby="csp-tab"><div class="img-thumbnail"><br />';
		$_contentResults_body .= "<strong>Content Security Policy (CSP)</strong> header directive currently does not exist:<br />";
		$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your cloud solution or site is still vulnerable to Cross-Site Scripting and other code injection attacks</span>.<br /><br />";
	$_contentResults_body .= '</div></div>';

	// --- RULE 4
	
	$_contentResults_header .= '<li class="nav-item"><a class="nav-link" id="x-frame-tab" data-toggle="tab" href="#x-frame" role="tab" aria-controls="x-frame" aria-selected="false">X-FRAME <i class="fa fa-minus-circle"></i></a></li>';
	
	$_contentResults_body .= '<div class="tab-pane fade" id="x-frame" role="tabpanel" aria-labelledby="x-frame-tab"><div class="img-thumbnail"><br />';
		$_contentResults_body .= "<strong>X-Frame-Options (X-FRAME)</strong> header directive currently does not exist:<br />";
		$_contentResults_body .= "<span style='color:#dc4245;'>Unfortunately your cloud solution or site is still vulnerable to some injected code running in the background.</span>.<br /><br />";
	$_contentResults_body .= '</div></div>';

	$_contentResults_header .= '</ul>';
	$_contentResults_body .= '</div>';
	
	$_contentResults .= $_contentResults_header;
	$_contentResults .= $_contentResults_body;
	
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice,$_securityDomains);
}

?>