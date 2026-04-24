<?php
require_once('../../inc/connection.php');

$array = array(array());

if(isset($_GET['_tbl']) && (!empty($_GET['_tbl'])) && isset($_GET['_fld']) && ((!empty($_GET['_fld'])) || ($_GET['_col']>=0)) && isset($_GET['_col']) && ((!empty($_GET['_col'])) || ($_GET['_col']>=0)) && isset($_GET['_value']) && (!empty($_GET['_value'])))
{
	if(isset($_GET['_isfile']) && ($_GET['_isfile']==1)) // --- source_csv (1)
	{
		if(($handle_r = fopen(CONSUMER_ROOTPATH."SYSTEM_FILES/MANUAL_UPLOADS/MASTER_DATA/".$_GET['_tbl'],"r")) !== FALSE)
		{
			$row =0;$tempArr=array();
			while(!feof($handle_r))
			{
				$data = str_getcsv(trim(fgets($handle_r)),"|");
				$data[$_GET['_col']] = (is_numeric(trim($data[$_GET['_col']])) ? (int)trim($data[$_GET['_col']]) : trim($data[$_GET['_col']]));
				$data[$_GET['_fld']] = (is_numeric(trim($data[$_GET['_fld']])) ? (int)trim($data[$_GET['_fld']]) : trim($data[$_GET['_fld']]));
				$data[$_GET['_lbl']] = (is_numeric(trim($data[$_GET['_lbl']])) ? (int)trim($data[$_GET['_lbl']]) : trim($data[$_GET['_lbl']]));
				
				if(isset($data[$_GET['_col']]) && (!empty($data[$_GET['_col']])) && (strcmp(trim($data[$_GET['_col']]),trim($_GET['_value'])) == 0) && isset($data[$_GET['_fld']]) && (!empty($data[$_GET['_fld']])) && isset($data[$_GET['_lbl']]) && (!empty($data[$_GET['_lbl']])))
				{
				     if(!in_array(trim($data[$_GET['_fld']]),$tempArr))
				     {
					$array[] = array(trim($data[$_GET['_fld']]) => trim($data[$_GET['_lbl']]));
					$tempArr[]=trim($data[$_GET['_fld']]);
					}
				     
				     if(sizeof($array)>50){fclose($handle_r);echo json_encode($array);exit;}
				}
			}
		     
		     fclose($handle_r);
		}
	}
	else // --- source_db (2)
	{
		global $db;
		$rs = $db->Execute("SELECT id,`".$_GET['_fld']."`,`".$_GET['_lbl']."` FROM `".$_GET['_tbl']."` WHERE `".$_GET['_col']."`='".$_GET['_value']."' AND pub='1' AND del='1' ORDER BY `".$_GET['_fld']."` ASC");
		if(($rs) && ($rs->_numOfRows >= 1))
		{
			while (!$rs->EOF){$array[] = array($rs->fields['_fld'] => $rs->fields[$_GET['_lbl']]);$rs->MoveNext();}
		}
	}
}
echo json_encode($array);
?>