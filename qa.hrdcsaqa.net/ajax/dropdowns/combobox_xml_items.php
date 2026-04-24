<?php
require_once('../../inc/connection.php');

$array = $array_ext = array();

if(isset($_GET['_tbl']) && (!empty($_GET['_tbl'])) && isset($_GET['_val']) && (!empty($_GET['_val'])))
{
	if(file_exists(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_tbl']."/herculesvb/".$_GET['_val']))
	{
		$xml = simplexml_load_file(CONSUMER_ROOTPATH."docs_drafts/modules/".$_GET['_tbl']."/herculesvb/".$_GET['_val']);
		
		if(isset($_GET['_filter']) && (!empty($_GET['_filter'])))
		{
			$_GET['_filter'] = explode(";",$_GET['_filter']);
			$_GET['_filter'] = ( (is_array($_GET['_filter'])) && (sizeof($_GET['_filter'])>=1)) ? $_GET['_filter'] : array();
		}else{$_GET['_filter'] = array();}
		
		if(isset($_GET['_rsltnt']) && (!empty($_GET['_rsltnt'])))
		{
			$_GET['_rsltnt'] = explode(";",$_GET['_rsltnt']);
			$_GET['_rsltnt'] = ( (is_array($_GET['_rsltnt'])) && (sizeof($_GET['_rsltnt'])>=1)) ? $_GET['_rsltnt'] : array();
		}else{$_GET['_rsltnt'] = array();}
		//print_r(sizeof($_GET['_filter']));print_r(sizeof($_GET['_rsltnt']));
		//genericItemsXMLArr($xml,"",$_GET['_filter'],$_GET['_rsltnt']);
		genericItemsXMLArr($xml);
		global $arrXML;$array = $arrXML;
		
		
		if((is_array($_GET['_rsltnt'])) && (sizeof($_GET['_rsltnt'])>=1))
		{
			global $db;
			//print_r($_GET['_rsltnt']);
			//while(list($k,$v) = each($_GET['_rsltnt']))
			foreach($_GET['_rsltnt'] as $k=>$v)
			{
				if(($v == "requestor_email") && (isset($array[strtoupper($v)])) && (!empty($array[strtoupper($v)])))
				{//echo "--".$array[strtoupper($v)]="DHANESH.MOODLEY@ZA.SABMILLER.COM";
					$rs = $db->Execute("SELECT users.id,CONCAT(users.name,' ',users.surname) AS cname,users.cell,email,users.image,0 AS district FROM `users` WHERE (`email` LIKE '%".$array[strtoupper($v)]."%' OR `user` LIKE '%".$array[strtoupper($v)]."%') AND users.del=1 AND users.pub=1 LIMIT 1");
					if(($rs) && ($rs->numRows() >= 1)){$array_ext[$v]=array($rs->fields['id'],$array[strtoupper($v)],$rs->fields['cname']);}
					
				}
				else if(($v == "plant") && (isset($array[strtoupper($v)])) && (!empty($array[strtoupper($v)])) && isset($_GET['_rsltnt'][0]) && (!empty($_GET['_rsltnt'][0])))
				{
					$rs =$db->Execute("
						SELECT
							`A`.`id`,`A`.`type`,`A`.`name` FROM `pages_fields` AS `A`
						INNER JOIN `pages_tabs` AS `B` ON `A`.tab=`B`.id
						INNER JOIN `pages_sections` AS `C` ON `B`.section=`C`.id
						INNER JOIN `pages` AS `D` ON `C`.page=`D`.id
						WHERE `A`.`del`=1 AND `A`.`pub`=1 AND `D`.parent='".$_GET['_tbl']."' AND `D`.`name`='".str_replace("_"," ",(string)$array[strtoupper($_GET['_rsltnt'][0])])."' LIMIT 1");
					if(($rs) && ($rs->numRows() >= 1)){$array_ext[$v]=array($rs->fields['id'],$array[strtoupper($v)],$rs->fields['name']);}
				}
				else if(($v == "process") && (isset($array[strtoupper($v)])) && (!empty($array[strtoupper($v)])) && isset($_GET['_rsltnt'][0]) && (!empty($_GET['_rsltnt'][0])))
				{
					$rs =$db->Execute("SELECT `A`.`id`,`A`.`name` FROM `pages` AS `A` WHERE `A`.parent='".$_GET['_tbl']."' AND `A`.`name`='".str_replace("_"," ",(string)$array[strtoupper($_GET['_rsltnt'][0])])."' LIMIT 1");
					if(($rs) && ($rs->numRows() >= 1)){$array_ext[$v]=array($rs->fields['id'],$array[strtoupper($v)],$rs->fields['name']);}
				}
			}
		}
		
		echo json_encode(array("status"=>1,"desc"=>"successful","data"=>$array,"data_ext"=>$array_ext));exit;
	}
	else{echo json_encode(array("status"=>0,"desc"=>"file-doesnotexist","data"=>0));exit;}
}
else{echo json_encode(array("status"=>0,"desc"=>"no-tablenorvalue","data"=>0));exit;}
?>