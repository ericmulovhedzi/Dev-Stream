<?php
require_once('../../inc/connection.php');

$array = array();
$_err=1;$msg="";

if(!(isset($_GET['_val']) && (!empty($_GET['_val']))))
{
	$_err=0;$msg="Search value is empty.";
}
else if(!(isset($_GET['src']) && (!empty($_GET['src']))))
{
	$_err=0;$msg="No source file specified.";
}
else if(!(isset($_GET['srccol']) && (!empty($_GET['srccol']))))
{
	$_err=0;$msg="No search column specified.";
}
else if(!(isset($_GET['rsltcol']) && (!empty($_GET['rsltcol']))))
{
	$_err=0;$msg="No results column specified.";
}

$array = array($_err=>$msg);

if($_err == 1)
{
	if($_GET['srctype'] == 2) // --- source_db (2)
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
	}
	else  // --- source_csv (1)
	{
		$dir = ROOTPATH."SYSTEM_FILES/";
		
		if(($handle_r = fopen($dir.$_GET['src'],"r")) !== FALSE)
		{
			$row = 1;$j = 0;$state = 0;
			
			while(!feof($handle_r))
			{
				if($row < 1){$row++;continue;}
				$data = str_getcsv(trim(fgets($handle_r)),"|");
				
				if(isset($data[$_GET['srccol']]) && (strpos(strtolower(trim($data[$_GET['srccol']])),strtolower(trim($_GET['_val']))) !== false))
				{
					$_label = trim($data[$_GET['rsltcol']]);
					//$DATA_AT_0 = is_numeric(trim($data[0])) ? (int)trim($data[0]) : trim($data[0]);
					$new_data[] = array("id"=>(is_numeric(trim($data[$_GET['rsltcol']])) ? (int)trim($data[$_GET['rsltcol']]) : trim($data[$_GET['rsltcol']])),"label"=>$_label);
					
					$state = 1;$j++;
					if($j > 50) break;
				}
					
				$row++;
			}
			
			fclose($handle_r);
			
			if($state == 1){if(is_array($new_data) && (sizeof($new_data) >= 1)){echo json_encode(array("status"=>1,"data_n"=>$new_data));exit;}}
		}else
		{
			$array[] = array('0' => '---'); 
		}
		    
	}
}

echo json_encode($array);
?>