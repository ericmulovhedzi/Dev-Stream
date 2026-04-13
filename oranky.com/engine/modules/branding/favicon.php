<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function getFavicon($_url)
{
	$favicon = '';
	
	$doc = new DOMDocument;
	
	if(!@$doc->loadHTMLFile($_url))
	{
		$err_item = "";    
		$errors = libxml_get_errors();
		
		foreach($errors as $error)
		{
		    $err_item .= $error->message; 
		}   
		
		$status = 0;
	}else
	{
		$xml = simplexml_import_dom($doc);
		
		$arr = $xml->xpath('//link[@rel="shortcut icon"]'); // --- 1st check
		$favicon = $arr[0]['href'];
		
		if(isset($favicon) && (!empty($favicon)))
		{
			$favicon = parse_url($favicon,PHP_URL_HOST);
			if(isset($favicon) && (!empty($favicon))){$favicon = $arr[0]['href'];}
			else
			{
				$pos = substr($arr[0]['href'], 0,1);
				if($pos != "/"){$favicon = $_url."/".$arr[0]['href'];}
				else $favicon = $_url.$arr[0]['href'];;
			}
		}
		else
		{
			$arr = $xml->xpath('//link[@rel="icon"]'); // --- 2nd check
			$favicon = $arr[0]['href'];
			
			if(isset($favicon) && (!empty($favicon)))
			{
				$favicon = parse_url($favicon,PHP_URL_HOST);
				if(isset($favicon) && (!empty($favicon))){$favicon = $arr[0]['href'];}
				else
				{
					$pos = substr($arr[0]['href'], 0,1);
					if($pos != "/"){$favicon = $_url."/".$arr[0]['href'];}
					else $favicon = $_url.$arr[0]['href'];
				}
			}
		}
		
		//echo $favicon;
		
		$status = 1;
	}
	
	return array($favicon,$status,$err_item);
}

$title = "";
$_ruleStatus = "danger";
$_contentResults='';
$_notice =
'
	This is your "<strong>first impression</strong>", the first thing your visitors will see.<br /><br />
	Your domain name is a branding opportunity! The right domain name can increase brand recognition.<br /><br />
	<strong>NB: Make sure it is Easy-To-Type</strong> i.e. Google, Facebook, Twitter, Instagram, Yahoo, CNN. One big thing these domain names have in common is that they\'re all easy to spell.<br /><br />
	A good domain name should always be between 6 to 14 characters. Remember, the shorter, the better.<br /><br />
	Lastly, make sure it\'s <strong>easy to pronounce</strong> and avoid hyphens and numbers.
';
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"subject"=>"","notice"=>$_notice);

list($title,$status,$error) = getFavicon($url);

if(isset($title) && (!empty($title)))
{ 
	if($status == 1)
	{
		$_contentResults = '<img src="'.$title.'" style="width:16px;margin-top:0px;" alt="shortcut icon for website: '.$url.'" />&nbsp;&nbsp;';
		$_contentResults .= 'Genius, your website has a favicon.';
		
		$_contentResults .= '<br /><br />Your favicon URL: <a target="_blank" href="'.$title.'"><code>'.$title.'</code></a>&nbsp;&nbsp;';
	}
	else
	{
		$_contentResults = 'Error, '.$error;
	}
	
	$_ruleStatus = "success";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus,"subject"=>$_contentResults,"notice"=>$_notice);
	$_MATRIX_RESULTS[$_ruleNo]["icon_path"] = $title;
	$_MATRIX_RESULTS[$_ruleNo]["status"] = $_ruleStatus;
		
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
else
{
	$_contentResults = "Missing, your website does not have a favicon.<br /><br />Please, make sure you add one.";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus,"subject"=>$_contentResults,"notice"=>$_notice);
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>