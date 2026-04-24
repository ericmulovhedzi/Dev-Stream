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
else if(!(isset($_GET['srccol']) && ((!empty($_GET['srccol'])) || ($_GET['srccol']>=0)) ))
{
	$_err=0;$msg="No search column specified.";
}
else if(!(isset($_GET['rsltcol']) && ((!empty($_GET['rsltcol'])) || ($_GET['rsltcol']>=0)) ))
{
	$_err=0;$msg="No results column specified.";
}

$array = array("status"=>$_err,"desc"=>$msg);

if($_err == 1)
{
	if($_GET['srctype'] == 2) // --- source_db (2)
	{
		global $db;
		//echo "SELECT `".trim($_GET['srccol'])."`,`".trim($_GET['rsltcol'])."` FROM `".trim($_GET['src'])."` WHERE `".trim($_GET['srccol'])."` LIKE '%".trim($_GET['_val'])."%' ORDER BY `".trim($_GET['rsltcol'])."` ASC";
		$rs = $db->Execute("SELECT `".trim($_GET['srccol'])."`,`".trim($_GET['rsltcol'])."` FROM `".trim($_GET['src'])."` WHERE `".trim($_GET['srccol'])."` LIKE '%".trim($_GET['_val'])."%' ORDER BY `".trim($_GET['rsltcol'])."` ASC");
		//print_r($rs);
		if(($rs) && ($rs->numRows() >= 1))
		{
			$row = 1;$j = 0;$state = 0;
			while(!$rs->EOF)
			{
				$_id = is_numeric(trim($rs->fields[$_GET['rsltcol']])) ? (int)trim($rs->fields[$_GET['rsltcol']]) : trim($rs->fields[$_GET['rsltcol']]);
					$_label = trim($rs->fields[$_GET['rsltcol']])." - ".trim($rs->fields[$_GET['srccol']]);
					//$DATA_AT_0 = is_numeric(trim($rs->fields[0])) ? (int)trim($rs->fields[0]) : trim($rs->fields[0]);
					$new_data[$_id] = array("id"=>$_id,"val"=>trim($rs->fields[$_GET['srccol']]),"label"=>$_label);
					
					$state = 1;$j++;
					if($j == 50){/*$new_data=array_unique($new_data);sort($new_data);*/break;}
				$rs->MoveNext();
			}
			if($state == 1){if(is_array($new_data) && (sizeof($new_data) >= 1)){echo json_encode(array("status"=>1,"data_n"=>$new_data));exit;}}
		}
		else{$array = array("status"=>0,"desc"=>"No SQL Table found..");}
	}
	else  // --- source_csv (1)
	{
		$dir = CONSUMER_ROOTPATH."SYSTEM_FILES/MANUAL_UPLOADS/MASTER_DATA/";
		//echo $dir.$_GET['src'];
		if(($handle_r = fopen($dir.$_GET['src'],"r")) !== FALSE)
		{
			$row = 1;$j = 0;$state = 0;
			
			while(!feof($handle_r))
			{
				if($row < 1){$row++;continue;}
				$data = str_getcsv(trim(fgets($handle_r)),"|");
				
				if(
					(isset($data[$_GET['srccol']]) && (strpos(strtolower(trim($data[$_GET['srccol']])),strtolower(trim($_GET['_val']))) !== false)) 
					|| 
					(isset($data[$_GET['rsltcol']]) && (strpos(strtolower(trim($data[$_GET['rsltcol']])),strtolower(trim($_GET['_val']))) !== false))
				  )
				{
					$_id = is_numeric(trim($data[$_GET['rsltcol']])) ? (int)trim($data[$_GET['rsltcol']]) : trim($data[$_GET['rsltcol']]);
					$_label = trim($data[$_GET['rsltcol']])." - ".trim($data[$_GET['srccol']]);
					//$DATA_AT_0 = is_numeric(trim($data[0])) ? (int)trim($data[0]) : trim($data[0]);
					$new_data[$_id] = array("id"=>$_id,"val"=>trim($data[$_GET['srccol']]),"label"=>$_label);
					
					$state = 1;$j++;
					if($j == 50){/*$new_data=array_unique($new_data);sort($new_data);*/break;}
				}
					
				$row++;
			}
			
			fclose($handle_r);
			
			if($state == 1){if(is_array($new_data) && (sizeof($new_data) >= 1)){echo json_encode(array("status"=>1,"data_n"=>$new_data));exit;}}
		}else
		{$array = array("status"=>0,"desc"=>"Cannot Open the file..");}
	}
}

echo json_encode($array);
?>