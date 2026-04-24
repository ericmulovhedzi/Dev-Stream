<?
require_once('../../inc/connection.php');

if(isset($_SESSION['accesses']->_login['id']) && (count($_SESSION['accesses']->_login) >= 1) && isset($_REQUEST['activity']) && (!empty($_REQUEST['activity'])))
{
	$tableArr = array("","");
	
	if($_REQUEST['activity']==4){$tableArr = array("ovh_clocking_logs","");}
	else if($_REQUEST['activity']==6){$tableArr = array("status","");}
	else if($_REQUEST['activity']==7){$tableArr = array("action_logs","");}
	
	if(isset($tableArr[0]) && (!empty($tableArr[0])))
	{
		$rsHeadersArr=array('A'=>array());
		$rs = $db->Execute("SELECT MIN(`A`.`date`) AS `dateMin`,MAX(`A`.`date`) AS `dateMax`,MONTH(`A`.`date`) AS `month`,YEAR(`A`.`date`) AS year,DATE_FORMAT(`A`.`date`,'%m-%y') as monthyear
				   FROM `".$tableArr[0]."` AS `A` WHERE `A`.`date` >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
				   GROUP BY MONTH(`A`.`date`) ORDER BY `A`.`date` ASC");
		
		if(($rs) && ($rs->numRows() >= 1))
		{
		    while(!$rs->EOF)
		    {
			$rsHeadersArr['A'][$rs->fields['monthyear']] = "MONTH(`A`.`date`)=".$rs->fields['month']." AND YEAR(`A`.`date`)=".$rs->fields['year'];
			$rs->MoveNext();
		    }
		}
		
		$sql = "SELECT  COUNT(`A`.id) AS total";
			$tmpArr = $rsHeadersArr['A'];while (list($k,$v) = each($tmpArr)){$sql .= ",SUM(IF($v,1,0)) AS `$k`";}
		$sql .= " FROM `".$tableArr[0]."` AS `A` ORDER BY total DESC";
		
		$rs = $db->Execute($sql);$_dataJS = "";
		if(($rs) && ($rs->_numOfRows >= 1))
		{
			$tmpArr = $rsHeadersArr['A'];while (list($_k,$_v) = each($tmpArr))
			{
				$_dataJS .= "{month:'".$_k."',activities:".$rs->fields[$_k]."},";
			}
			
			echo (isset($_dataJS) && (!empty($_dataJS))) ? "[".substr($_dataJS,0,-1)."]" : "[{month:'Null',activities:0}]";exit;
		}
	}
}

echo "[{month:'Null',activities:0}]";exit;
?>