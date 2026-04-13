<?
//require_once('inc/connection.php');

define('WWWROOT',"https://hrdcsaqa.net/oranky/");

ignore_user_abort(true);set_time_limit(0);ini_set('memory_limit','-1'); // Run forever and with no memory limit

header('Access-Control-Allow-Origin: *');

$array = $_MATRIX_RESULTS = $_SSL_CERTIFICATE_SECURITY = $_URL_RESOLVE = $_TECHNOLOGIES = $_IN_PAGE_TAGS = $_PRIVACY_TERMS_POLICY_TAGS = array();
$array["progress"] = array("informational"=>0,"danger"=>0,"success"=>0,"warning"=>0);
$array["total"] = 0;

$_KEY_PHRASES = $_KEY_PHRASES_DUPLICATES = array();

$_STOP_WORDS = array
(
	"A","ABOUT","ACTUALLY","ALMOST","ALSO","ALTHOUGH","ALWAYS","AM","AN","AND","ANY","ARE","AS","AT",
	"BE","BECAME","BECOME","BUT","BY",
	"CAN","COULD",
	"DID","DO","DOES",
	"EACH","EITHER","ELSE",
	"FOR","FROM",
	"HAD","HAS","HAVE","HENCE","HOW",
	"I","IF","IN","IS","IT","ITS",
	"JUST",
	"MAY","MAYBE","ME","MIGHT","MINE","MUST","MY","MINE","MUST","MY",
	"NEITHER","NOR","NOT",
	"OF","OH","OK","ON",
	"WHEN","WHERE","WHEREAS","WHEREVER","WHENEVER","WHETHER","WHICH","WHILE","WHO","WHOM","WHOEVER","WHOSE","WHY","WILL","WITH","WITHIN","WITHOUT","WOULD",
	"YES","YET","YOU","YOUR"
);

$_STOP_WORDS_USEFUL = array
(
	"ARE",
	"BEEN",
	"OUR",
	"THE","THERE","TO","THAT","THOSE","THESE","THEREFORE","THEREIN",
	"US",
	"WE","WHAT"
);

$_SSL_CERTIFICATE_SECURITY['hasSSLCertificate'] = false;
$_SSL_CERTIFICATE_SECURITY['redirect_url'] = "";

$_URL_RESOLVE = array(false,"","");

$array["rules"]["time_out"] = 0;

function removeCommonWords($input, $stopwords)
{
	
 	// EEEEEEK Stop words
	$stopwords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero');
 
	return preg_replace('/\b('.implode('|',$stopwords).')\b/i','',$input);
}

function stopWordsNLP($text, $stopwords)
{
	// Remove line breaks and spaces from stopwords
	$stopwordsLower = array_map(function($x){return "/ ".trim(strtolower($x))." /i";}, $stopwords);
	$stopwordsUpper = array_map(function($x){return "/ ".trim($x)." /i";}, $stopwords);
	
	// Replace all non-word chars with nothing
	$text = preg_replace('/[^A-Za-z0-9]+/',' ',$text);
	
	// Replace all non-word chars with semicolon
	$pattern = '/[_-]/';
	$text = preg_replace($pattern, ';', $text);
	
	foreach($stopwordsLower as $term){$text = preg_replace($term, ';', $text);}
	foreach($stopwordsUpper as $term){$text = preg_replace($term, ';', $text);}
	
	$text = removeCommonWords($text, $stopwords);
	
	$text_array = explode(";",$text);
	
	// remove whitespace and lowercase words in $text
	$text_array = array_map(function($x){return trim($x);}, $text_array);
	
	$text_array = array_filter($text_array);
	
	foreach($text_array as $x => $y)
	{
		$y = @trim(preg_replace('/[0-9]/','',$y)); // remove kerwords phrases that are only numbers
		if(empty($y)){unset($text_array[$x]);}
		else if(in_array(strtoupper($y),$stopwords))
		{
			unset($text_array[$x]);
		}
	}
	
	return $text_array;
}

if(true)
{
	global $_REQUEST_TYPE,$_REQUEST_URL;
	$_ruleNo = 0;
	
	if(isset($_REQUEST_URL)){$_REQUEST['_page'] = $_REQUEST_URL;}
	   
	if(isset($_REQUEST['_page']) && (!empty($_REQUEST['_page'])))
	{
		$url = 'http://'.$_REQUEST['_page'];
		
		$_RUNTIME_TIMEOUT = 15;
		
		$ctx = stream_context_create(array('http'=>
			array
			(
				'timeout' => $_RUNTIME_TIMEOUT, // 15 seconds,
				'method' => "REQUEST"
			)
		));
		
		$_responseTime = @file_get_contents($url, false, $ctx);
		
		if($_responseTime === false)
		{
			$_MATRIX_RESULTS[$_ruleNo] = array("errors"=>0,"warning"=>0,"status"=>0);
				
			$array["rules"]["time_out"] = $_RUNTIME_TIMEOUT;
		}
		else
		{
			/* RULE #1: BRANDING - URL */
			$_ruleNo = 1;$_ruleName = "URL";$_subSection = "branding_url";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/branding/url.php');
			
			/* RULE #2: BRANDING - FAVICON */
			$_ruleNo = 2;$_ruleName = "Favicon";$_subSection = "branding_favicon";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/branding/favicon.php');
			
			/* RULE #3: BRANDING - CUSTOM 404 PAGE */
			$_ruleNo = 3;$_ruleName = "Custom 404 Page";$_subSection = "branding_custom_404_page";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/branding/custom_404_page.php');
			
			/* RULE #4: KEY META TAGS - TITLE TAG */
			$_ruleNo = 4;$_ruleName = "Title Tag";$_subSection = "key_meta_tag_title_tag";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/key_meta_tags/title_tag.php');
			
			/* RULE #5: KEY META TAGS - DESCRIPTION META TAG */
			$_ruleNo = 5;$_ruleName = "Meta Description";$_subSection = "key_meta_tag_meta_description_tag";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/key_meta_tags/meta_description_tag.php');
			
			/* RULE #6: TRAFFIC - TRAFFIC RANK */
			$_ruleNo = 6;$_ruleName = "Traffic Rank";$_subSection = "traffic_traffic_rank";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/traffic/traffic_rank.php');
			
			/* RULE #7: REFERENCE BACKLINKS - BACKLINKS */
			//$_ruleNo = 7;$_ruleName = "Reference Backlinks";$_subSection = "reference_backlinks_backlinks";
			//$array["total"] = $array["total"] + 1;
			//@require_once('modules/reference_backlinks/backlinks.php');
			
			/* RULE #8: CONTENT ANALYSIS - LANGUAGE */
			$_ruleNo = 8;$_ruleName = "Language";$_subSection = "content_analysis_language";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/content_analysis/language.php');
			
			/* RULE #9: CONTENT ANALYSIS - HTML HEADINGS */
			$_ruleNo = 9;$_ruleName = "HTML Headings";$_subSection = "content_html_headings";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/content_analysis/html_headings.php');
			
			/* RULE #10: CONTENT ANALYSIS - HTML IN-PAGE LINKS */
			//$_ruleNo = 10;$_ruleName = "Site Links";$_subSection = "content_html_in_page_links";
			//$array["total"] = $array["total"] + 1;
			//@require_once('modules/content_analysis/html_in_page_links.php');
			
			/* RULE #11: CONTENT ANALYSIS - NLP */
			//$_ruleNo = 11;$_ruleName = "Natural language processing (NLP)";$_subSection = "";
			//$array["total"] = $array["total"] + 1;
			//@require_once('modules/content_analysis/nlp.php');
			
			/* RULE #12: TECHNOLOGIES - DOCTYPE */
			$_ruleNo = 12;$_ruleName = "Doctype";$_subSection = "technologies_doctype";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/technologies/doctype.php');
			
			/* RULE #13: TECHNOLOGIES - ENCODING */
			$_ruleNo = 13;$_ruleName = "Encoding";$_subSection = "technologies_encoding";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/technologies/encoding.php');
			
			/* RULE #14: TECHNOLOGIES - SERVER IP */
			$_ruleNo = 14;$_ruleName = "Server IP Address";$_subSection = "technologies_server_ip_address";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/technologies/server_ip_address.php');
			
			/* RULE #15: TECHNOLOGIES - SERVER UPTIME */
			$_ruleNo = 15;$_ruleName = "Server Uptime";$_subSection = "technologies_server_uptime";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/technologies/server_uptime.php');
			
			
			/* RULE #16: SECURITY - SSL SECURITY */
			$_ruleNo = 16;$_ruleName = "SSL Security";$_subSection = "security_ssl_security";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/security/ssl_security.php');
			
			/* RULE #17: SECURITY - MIXED CONTENT */
			$_ruleNo = 17;$_ruleName = "Mixed Content";$_subSection = "security_mixed_content";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/security/mixed_content.php');
			
			/* RULE #18: SECURITY - RELATIVE URLS */
			$_ruleNo = 18;$_ruleName = "Relative URLs";$_subSection = "security_relative_urls";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/security/relative_urls.php');
			
			/* RULE #19: SECURITY - POST COVID-19 CYBER SECURITY */
			$_ruleNo = 19;$_ruleName = "Post COVID-19 Cyber Security Policy";$_subSection = "security_post_covid19_cyber_security";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/security/post_covid19_cyber_security.php');
			
			/* RULE #20: INDEXING - URL RESOLVE */
			$_ruleNo = 20;$_ruleName = "URL Resolve";$_subSection = "indexing_url_resolve";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/indexing/url_resolve.php');
			
			/* RULE #21: CONTENT ANALYSIS - HTML IN-PAGE LINKS */
			$_ruleNo = 21;$_ruleName = "Site Links";$_subSection = "content_html_in_page_links";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/content_analysis/html_in_page_links.php');
			
			/* RULE #22: SECURITY - INFORMATION SECURITY POLICY */
			$_ruleNo = 22;$_ruleName = "Information Security Policy";$_subSection = "security_information_security_policy";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/security/information_security_policy.php');
			
			/* RULE #23: CONTENT ANALYSIS - NLP */
			$_ruleNo = 23;$_ruleName = "Natural Language Processing (NLP)";$_subSection = "content_nlp";
			$array["total"] = $array["total"] + 1;
			@require_once('modules/content_analysis/nlp.php');
			
			if(isset($_MATRIX_RESULTS) && is_array($_MATRIX_RESULTS) && (sizeof($_MATRIX_RESULTS)>=1))
			{
				foreach($_MATRIX_RESULTS as $_key=>$_val)
				{
					//print_r($_val);
					if(isset($array["progress"][$_val['status']])){$array["progress"][$_val['status']] = $array["progress"][$_val['status']] + 1;}
					else{$array["progress"][$_val['status']] = 1;}
				}
			}
			//$array["progress"] = array("informational"=>0,"danger"=>0,"success"=>0,"warning"=>0);
			//print_r($array["total"]);
			//print_r($array["progress"]);
		}
	}
}
else
{
	//echo "<div class='page-heading' style='width:97%;background-color:transparent;background-image:url(".WWWROOT."images/icons/arrow_down.png)'>Error: <i style='color:#f00;'>Please login first..!</i></div>";
}


//if(!(isset($_GET['_t']) && (!empty($_GET['_t'])))){echo json_encode($array);}
if(!(isset($_REQUEST_TYPE) && ($_REQUEST_TYPE == 1))){echo json_encode($array);}

?>