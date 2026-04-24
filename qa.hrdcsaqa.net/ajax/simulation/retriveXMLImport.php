<?
require_once('../../inc/connection.php');

global $db;
$_FIELDS_CONVERTED_INTO_IDS = array();$_XMLDATAFIELDSAUDIT="";$_fieldTypesArr = genericItemsArr("cf_types");

function RecurseXML($xml,$parent="") 
{
   global $db, $_FIELDS_CONVERTED_INTO_IDS, $_XMLDATAFIELDSAUDIT, $_fieldTypesArr;$child_count = 0;
   foreach($xml as $key=>$value) 
   { 
      $child_count++;     
      if(RecurseXML($value,$parent.".".$key) == 0)  // no childern, aka "leaf node" 
      {
	//print((string)$key." :: ".str_replace("_"," ",(string)$key)." = ".(string)$value."<BR>\n");
	$rs =$db->Execute("
			SELECT
				`A`.`id`,`A`.`type`,`A`.`name` FROM `pages_fields` AS `A`
			INNER JOIN `pages_tabs` AS `B` ON `A`.tab=`B`.id
			INNER JOIN `pages_sections` AS `C` ON `B`.section=`C`.id
			WHERE `A`.`del`=1 AND `A`.`pub`=1 AND `C`.page='".$_REQUEST['_p']."' AND `A`.`xmlmapping`='".(string)$key."'");
	if(isset($rs) && ($rs->numRows() >= 1))
	{
		while(!$rs->EOF)
		{
			$_FIELDS_CONVERTED_INTO_IDS[$rs->fields['id']] = array($rs->fields['type'],trim((string)$value));
			$type = isset($_fieldTypesArr[$rs->fields['type']]) ? $_fieldTypesArr[$rs->fields['type']]:"";
			$_XMLDATAFIELDSAUDIT .= "<tr><td style='height:19px;line-height:19px;border-bottom:1px solid #ddd;'>".trim((string)$key)."</td><td style='border-bottom:1px solid #ddd;text-align:left;'>".trim((string)$value)."</td><td style='border-bottom:1px solid #ddd;text-align:left;'>".$rs->fields['name']." (".$rs->fields['id'].")</td><td style='border-bottom:1px solid #ddd;text-align:center;'>$type</td><td style='border-bottom:1px solid #ddd;background-color:#E6EFC2;color:#384f22;text-align:center;'>MAPPED</td></tr>";
			$rs->MoveNext();
		}
	}
	else{$_XMLDATAFIELDSAUDIT .= "<tr><td style='height:19px;line-height:19px;border-bottom:1px solid #ddd;'>".trim((string)$key)."</td><td style='border-bottom:1px solid #ddd;text-align:left;'>".trim((string)$value)."</td><td style='border-bottom:1px solid #ddd;'>&nbsp;</td><td style='border-bottom:1px solid #ddd;'>&nbsp;</td><td style='border-bottom:1px solid #ddd;background-color:#FBE3E4;color:#8b1d09;text-align:center;'>NOT MAPPED</td></tr>";}
      }
   }
   return $child_count; 
}

function XMLMappingNotification($_file="",$_path="")
{
	global $_XMLDATAFIELDSAUDIT;
	
	if(isset($_XMLDATAFIELDSAUDIT) && (!empty($_XMLDATAFIELDSAUDIT)) && isset($_file) && (!empty($_file)))
	{
		$date = substr(NOW(),0,11);$time = substr(NOW(),11,25);
		
		$body = "
		<center style='font:12px/18px Arial,Helvetica,Verdana,sans-serif;'>
		<table border='0' style='margin-top:30px;width:100%;border:1px solid #ccc;color:#111;font-size:12px;font-family:Arial,Helvetica,Verdana,sans-serif;font-size:10px;font:12px/18px Arial,Helvetica,Verdana,sans-serif;-moz-border-radius:5px;-border-radius:5px;-webkit-border-radius:5px;' cellspacing='15' cellpadding='5' >
			<tr>
				<td valign='middle' colspan='2' style='letter-spacing:2px;background-color:#E6EFC2;border:1px solid #384f22;color:#384f22;line-height:30px;font-size:16px;font-weight:bold;-moz-border-radius:5px;-border-radius:5px;-webkit-border-radius:5px;'>&nbsp;&nbsp;XML Fields Mapping: $_file</td>
			</tr>
			<tr>
				<td colspan='2' style='text-align:justify;line-height:25px;'>
					<h3 style='color:#666;font-size:12px;font-weight:bold;letter-spacing:1px;'>Below are XML Data againts O-Framework Fields mapping results:</h3>
					<hr>
					<b>XML File: <a target='_blank' href='#' style='color:#af292e;text-decoration:none;'>".$_file."</a></b><br />
					<b>Date Logged: <span style='color:#DB0000;font-weight:bold;'>".$date."</span> @ <i style='color:#555'>".$time."</i></b><br />
					<hr><br />
					<center style='font:12px/18px Arial,Helvetica,Verdana,sans-serif;'>
						<table style='width:100%;border:1px solid #ddd;color:#111;background-color:#fefefe;'><th style='background-color:#eee;text-align:left;'>XML Attribute</th><th style='background-color:#eee;text-align:left;'>XML Value</th><th style='background-color:#eee;width:220px;text-align:left;'>Mapped Field Name</th><th style='background-color:#eee;width:70px;text-align:center;'>Type</th><th style='background-color:#eee;width:90px;text-align:center;'>Status</th>$_XMLDATAFIELDSAUDIT</table>
					</center>
					<br />Thank you
					<br />
					<a target='_blank' href='#' style='color:#953939;font-weight:bold;text-decoration:none;'>CCBSA</a></b>
				</td> 
			</tr>
		</table>
		<br />
		<p style='font-size:11px;'><a target='_blank' style='color:#ccc;' href='#'>OVH Enterprise Framework v2.10</a></p>
		</center>
		";
			
		//echo $body."<br/>";
			
		$subject = "XML Mapping for file - $_file : ".$date." @ ".$time;
		
		require_once('../../inc/phpmailer/class.phpmailer.php');
		
		$emailer = new PHPMailer();
		$emailer->From      = 'root@localhost.af.didata.local';
		$emailer->FromName  = 'CCBSA O-Framework Server 2';
		$emailer->AddReplyTo('Mitesh.Singh@sabmiller.com', $emailer->FromName);
		$emailer->Subject   = trim($subject);
		$emailer->MsgHTML("<html><title>".$subject."</title><body>".$body."</body></html>");
		
		$email = "Mitesh.Singh@sabmiller.com";
		$emailer->AddAddress($email);
		$emailer->AddCC("wroelofse@za.ccsabco.com");$emailer->AddCC("PDeVilliers@ccbagroup.com");$emailer->AddCC("rvanjaarsveld@za.ccsabco.com");$emailer->AddCC("wroelofse@za.ccsabco.com");$emailer->AddCC("WROELOFSE@ccfortune.co.za");
		
		$emailer->AddCC("Nivesh.Rampat@za.ab-inbev.com");$emailer->AddCC("Leslie.Gombart@za.sabmiller.com");$emailer->AddCC("Douwes.Sorgdrager@sabmiller.com");$emailer->AddCC("eric@ovhstudio.co.za");
		
		$emailer->AddBCC("rudzani.mulovhedzi@ovhstudio.co.za");$emailer->AddBCC("commac.creations@gmail.com");
		
		if(isset($_path) && (!empty($_path)))
		{
			$emailer->AddAttachment($_path,$_file);	
		}
		
		return $emailer->Send();
		unset($_XMLDATAFIELDSAUDIT);
	}
}

//if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1))
if(true)
{
	if(!(isset($_REQUEST['_p']) && ($_REQUEST['_p']>=1))){echo json_encode(array("status"=>0,"desc"=>"nomodule","data"=>0,"data_v"=>"NO MODULE","content"=>""));exit;}
	else if(!(isset($_REQUEST['isnew']) && ($_REQUEST['isnew']>=1))){echo json_encode(array("status"=>0,"desc"=>"noitem","data"=>0,"data_v"=>"NO ITEM","content"=>""));exit;}
	
	$dir = CONSUMER_ROOTPATH."docs_drafts/modules/".$_REQUEST['_p']."/".$_REQUEST['isnew']."/xml";
	
	if($handle = opendir($dir)) 
	{
		$hasAnyFile = false;
		while(false !== ($file = readdir($handle))) 
		{
			if(($file == ".DS_Store") || ($file == "..") || ($file == ".")){continue;}$nextpath = $dir.'/' .$file; 
			
			if(true)
			{
				if(!is_dir ($nextpath))
				{
					if(file_exists($nextpath))
					{
						$hasAnyFile = true;
						$xml = simplexml_load_file($nextpath);
						
						RecurseXML($xml->MATERIAL_ITEM);
						
						$xml_content = file_get_contents($nextpath);
						echo json_encode(array("status"=>1,"desc"=>"successful","data"=>$_FIELDS_CONVERTED_INTO_IDS,"data_v"=>$file,"content"=>$xml_content));
						if(isset($_REQUEST['_ntfy']) && ($_REQUEST['_ntfy']>=1)){XMLMappingNotification($file,$nextpath);}
						exit;
					}
					else{echo json_encode(array("status"=>0,"desc"=>"fileopenfail","data"=>array(),"data_v"=>$file,"content"=>simplexml_load_file($nextpath)));exit;}
				}
				
				if(!$hasAnyFile){echo json_encode(array("status"=>0,"desc"=>"nofile: ".$file,"data"=>array(),"data_v"=>"NO FILE LOADED:","content"=>$file));exit;}
			}
		}
	}
	else{echo json_encode(array("status"=>0,"desc"=>"nodirectory","data"=>array(),"data_v"=>"NO DIRECTORY LOADED","content"=>""));exit;}
}
else{echo json_encode(array("status"=>0,"desc"=>"islogout","data"=>0,"data_v"=>"","content"=>""));exit;}

?>