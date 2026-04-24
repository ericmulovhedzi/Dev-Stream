<?php
require_once('../../inc/connection.php');

$array = array();

if($_GET['_name'] == 'source_db') 
{
	global $db;
	
	$rs = $db->Execute("SELECT * FROM `".$_GET['_value']."` LIMIT 1");
	//print_r($rs);
	if(($rs) && ($rs->numRows() >= 1))
	{
		$_numOfCols = $rs->NumCols();
		
		for($i=0;$i<$_numOfCols;$i++)
		{
			$fieldObj = $rs->FetchField($i);
			if(in_array($fieldObj->name,array("del"))) continue;
			$array[] = array($fieldObj->name => $fieldObj->name);
		}
	}
	else{$array[] = array('0' => '---');}
}else
{
	$dir = ROOTPATH."SYSTEM_FILES/MANUAL_UPLOADS/MASTER_DATA/";
	
	if(($handle_r = fopen($dir.$_GET['_value'],"r")) !== FALSE)
	{
		$row = 1;
		
		while(!feof($handle_r))
		{
			$_data = str_getcsv(trim(fgets($handle_r)),"|");
			
			//if($row < 1){$row++;continue;}
			
			//while (list($k,$v) = each($_data))
			foreach($_data as $k=>$v)
			{
				$array[] = array($k=>$v." ($k)".$row);
			}
			break;
		}
		fclose($handle_r);
	}else
	{
		$array[] = array('0' => '---'); 
	}
            
}
echo json_encode($array);
?>