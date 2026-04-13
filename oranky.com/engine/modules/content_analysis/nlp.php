<?
/*
	RULE - TITLE TAG :
		Category: KEY META TAGS
*/
require_once(__DIR__.'/../../scripts/sectionalContent.php');
//require_once('../../scripts/sectionalContent.php');

global $array,$_MATRIX_RESULTS;
global $_ruleNo, $_ruleName, $_subSection, $url;

function strip_tags_content($text, $tags = '', $invert = FALSE) {

  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
  $tags = array_unique($tags[1]);
   
  if(is_array($tags) AND count($tags) > 0) {
    if($invert == FALSE) {
      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
    }
    else {
      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
    }
  }
  elseif($invert == FALSE) {
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
  }
  return $text;
}

function stopWords($text, $stopwords) {

  // Remove line breaks and spaces from stopwords
    $stopwords = array_map(function($x){return trim(strtolower($x));}, $stopwords);//print_r($stopwords);

  // Replace all non-word chars with comma
  $pattern = '/[0-9\W]/';
  $text = preg_replace($pattern, ',', $text);

  // Create an array from $text
  $text_array = explode(",",$text);

  // remove whitespace and lowercase words in $text
  $text_array = array_map(function($x){return trim(strtolower($x));}, $text_array);

  foreach ($text_array as $term) {
    if (!in_array($term, $stopwords)) {
      $keywords[] = $term;
    }
  };

  return array_filter($keywords);
}

function stopWordsNLPx($text, $stopwords) {

  // Remove line breaks and spaces from stopwords
    $stopwords = array_map(function($x){return "/".trim(strtolower($x))."/";}, $stopwords);//print_r($stopwords);

	$text = preg_replace('/[^A-Za-z0-9-]+/', ' ', $text);

  // Replace all non-word chars with comma
  //$pattern = '/[0-9\W]/';
  $pattern = '/[0-9]/';
  $text = preg_replace($pattern, ',', $text);
	
	foreach ($stopwords as $term)
	{
		//$text = preg_replace($term, ',', $text);
	}
	
  // Create an array from $text
  $text_array = explode(",",$text);

  // remove whitespace and lowercase words in $text
  $text_array = array_map(function($x){return trim(strtolower($x));}, $text_array);
  $keywords = $text_array;
//print_r($text_array);
  /*foreach ($text_array as $term) {
    if (!in_array($term, $stopwords)) {
      $keywords[] = $term;
    }
  };*/

  return array_filter($keywords);
}

function getNLP($_url)
{
	$lang = $lang_html = '';$nlpArr = $nlpArray = array();
	global $_STOP_WORDS, $_STOP_WORDS_USEFUL, $_KEY_PHRASES, $_KEY_PHRASES_DUPLICATES;
	
	$_STOP_WORDS = array_merge($_STOP_WORDS,$_STOP_WORDS_USEFUL);
	
	//print_r( $_KEY_PHRASES);
	
	foreach($_KEY_PHRASES as $x => $y)
	{
		if(isset($y) && is_array($y) && (sizeof($y)>=1))
		{
			foreach($y as $v)
			{
				if(isset($nlpArr[$v])){$nlpArr[$v]++;}
				else
				{
					$nlpArr[$v] = 1;
				}
			}
		}
	}
	
	if(isset($nlpArr) && is_array($nlpArr) && (sizeof($nlpArr)>=1))
	{
		foreach($nlpArr as $a => $b)
		{
			foreach($nlpArr as $c => $d)
			{
				if(($c != $a) && (stripos($c,$a) !== false))
				{
					//$nlpArr[$a]++;
					$nlpArr[$a] = $nlpArr[$a] + $d;
				}
			}
		}
	}
	
	//print_r($nlpArr);
	$page = @file_get_contents($_url);
	$page = strip_tags($page, '<body><script><style><link><a><form><select><label><input><button><h1><h2><h3><h4><h5><h6>');
	$body = preg_match('/<body[^>]*>(.*?)<\/body>/ims', $page, $match) ? $match[1] : "";
	
	$body = strip_tags_content($body, '<script>', TRUE);
	$body = strip_tags_content($body, '<style>', TRUE);
	$body = strip_tags_content($body, '<link>', TRUE);
	$body = strip_tags_content($body, '<a>', TRUE);
	$body = strip_tags_content($body, '<form>', TRUE);
	$body = strip_tags_content($body, '<select>', TRUE);$body = strip_tags_content($body, '<label>', TRUE);
	$body = strip_tags_content($body, '<input>', TRUE);$body = strip_tags_content($body, '<button>', TRUE);
	
	$body = strip_tags_content($body, '<h1>', TRUE);$body = strip_tags_content($body, '<h2>', TRUE);$body = strip_tags_content($body, '<h3>', TRUE);
	$body = strip_tags_content($body, '<h4>', TRUE);$body = strip_tags_content($body, '<h5>', TRUE);$body = strip_tags_content($body, '<h6>', TRUE);
	
	//$body = stopWords($body, $_STOP_WORDS);
	
	$bodyArr = stopWordsNLPx($body, $_STOP_WORDS);
	
	//print_r($bodyArr);
	
	if(isset($nlpArr) && is_array($nlpArr) && (sizeof($nlpArr)>=1) && isset($bodyArr) && is_array($bodyArr) && (sizeof($bodyArr)>=1))
	{
		foreach($nlpArr as $e => $f)
		{
			foreach($bodyArr as $g)
			{
				if(stripos($g,$e) !== false)
				{
					$nlpArr[$e] = $nlpArr[$e] + substr_count($g,strtolower($e));
				}
			}
		}
	}
	
	if(isset($nlpArr) && is_array($nlpArr) && (sizeof($nlpArr)>=1) && isset($_KEY_PHRASES_DUPLICATES) && is_array($_KEY_PHRASES_DUPLICATES) && (sizeof($_KEY_PHRASES_DUPLICATES)>=1))
	{
		foreach($nlpArr as $h => $i)
		{
			foreach($_KEY_PHRASES_DUPLICATES as $j)
			{
				if(stripos($j,$h) !== false)
				{
					$nlpArr[$h] = $nlpArr[$h] - 1;
				}
			}
		}
	}
	
	$hasNPL = false;
	if(isset($nlpArr) && is_array($nlpArr) && (sizeof($nlpArr)>=1))
	{
		arsort($nlpArr);
		$hasNPL = true;
	}
	
	//print_r($nlpArr);
	
	return array($hasNPL,$nlpArr);
}

$title = "";
$_ruleStatus = "danger";
$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus);
$_contentResults='';
$_notice =
'
	You should always use a language attribute on the html tag to declare the default language of the text in the page. This is inherited by all other elements.<br /><br />
	This is meant to assist search engines and browsers.<br /><br />
	<strong>ISO Language Codes</strong>: in both HTML and XHTML, attributes utilize ISO language codes to instruct search engines about the current language of your site i.e. <a target="_blank" href="https://www.w3schools.com/tags/ref_language_codes.asp">ISO 639-1 defines abbreviations for languages</a>.<br /><br />
	<a target="_blank" href="https://www.w3schools.com/tags/ref_country_codes.asp"><strong>ISO Country Codes</strong></a>: In HTML, country codes can be used as an addition to the language code in the lang attribute..<br /><br />
';

list($title,$_NLPArr) = getNLP($url);

if(isset($title) && (!empty($title)))
{
	$_contentResults = "";
	$_resultsKEYWORD_PHRASES = $_resultsKEYWORD_PHRASES_LESS = $_resultsKEYWORDS = '';
	
	//global $_HTML_HEADING_TAGS;
	
	foreach($_NLPArr as $key=>$val)
	{
		//$numOfItems = (isset($_NLPArr[$val]) && is_array($_NLPArr[$val]) && (sizeof($_NLPArr[$val]) > 0)) ? sizeof($_NLPArr[$val]) : 0;
		
		//$color = "secondary";if($numOfItems == 0){$color = "warning";}
		$color = "secondary";
		
		if( is_numeric(str_word_count(trim($key),0)) && (str_word_count(trim($key),0) >= 2) ) // --- Keyword Phrases
		{
		  if($val >= 2)
		  {
		    $_resultsKEYWORD_PHRASES .= '<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;background-color:#f9f9f9;font-size:100%;margin-bottom:0.5%;">'.$key.' <span class="badge badge-'.$color.'" style="font-size:90%;">'.$val.'</span></button>&nbsp;&nbsp;&nbsp;';
		  }
		  else
		  {
		    $_resultsKEYWORD_PHRASES_LESS .= '<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;background-color:#f9f9f9;font-size:100%;margin-bottom:0.5%;">'.$key.' <span class="badge badge-'.$color.'" style="font-size:90%;">'.$val.'</span></button>&nbsp;&nbsp;&nbsp;';
		  }
		}
		else // --- Single Keyword
		{
		  $_resultsKEYWORDS .= '<button type="button" class="btn btn-outline-secondary btn-sm" style="border:1px solid #ccc;background-color:#f9f9f9;font-size:100%;margin-bottom:0.5%;">'.$key.' <span class="badge badge-'.$color.'" style="font-size:90%;">'.$val.'</span></button>&nbsp;&nbsp;&nbsp;';
		}
	}
	
	$_contentResults .= '<div class="col-md-12 img-thumbnail" style="margin-bottom:1.5%;float:left;border:1px solid #fff;padding:0.5%;background-color:#eee;border-bottom:1px solid #ccc;">
			     <img src="'.WWWROOT.'images/iconography/target-customer.png" style="width:22px;" />&nbsp;&nbsp;<strong>Compelling Keyword Phrases: <span style="color:#4ea747;">(Ready for a Digital Marketing Campaign)</span></strong>
			    </div>';
	$_contentResults .= $_resultsKEYWORD_PHRASES;
	$_contentResults .= '<div class="col-md-12 img-thumbnail" style="margin-bottom:1.5%;margin-top:2.0%;float:left;border:1px solid #fff;padding:0.5%;background-color:#eee;border-bottom:1px solid #ccc;">
			    <img src="'.WWWROOT.'images/icons/arrows-double-down.png" style="width:16px;" />&nbsp;&nbsp;<strong>Less compelling Keyword Phrases:</strong>
			    </div>';
	$_contentResults .= $_resultsKEYWORD_PHRASES_LESS;
	$_contentResults .= '<div class="col-md-12 img-thumbnail" style="margin-bottom:1.5%;margin-top:2.0%;float:left;border:1px solid #fff;padding:0.5%;background-color:#eee;border-bottom:1px solid #ccc;">
			     <img src="'.WWWROOT.'images/icons/arrows-double-down.png" style="width:16px;" />&nbsp;&nbsp;<strong>Single Keywords:'.__DIR__.'</strong>
			    </div>';
	$_contentResults .= $_resultsKEYWORDS;
	
	$_ruleStatus = "success";
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>$_ruleStatus);
	
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
else
{
	$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>1,"warning"=>1,"status"=>$_ruleStatus);
	$_contentResults = "Missing";
	$array["rules"][$_subSection] = sectionalContent($_ruleNo,$_ruleName,$_ruleStatus,$_contentResults,$_notice);
}
?>